<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Order;
use App\Models\Product;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mail;
use PDF;
use Session;

class UserController extends Controller
{
    public $pass = 'mulgazanzari';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware(['auth'])->except(['success_tbc']);
    }

    public function profile(Request $request): View
    {
        if ($request->has('order_id')) {
            $order = Order::findOrFail($request->get('order_id'));

            return view('client.user.order', compact('order'));
        } else {
            return view('client.user.profile');
        }
    }

    public function update_personal_info(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        $update = $user->update($request->except(['password', 'password_confirmation']));

        if ($update) {
            $request->session()->flash('info_updated', trans('site.personal_data_update_success'));
        }

        return redirect()->back();
    }

    public function update_password(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! Hash::check($request->old_password, $user->password)) {
            $request->session()->flash('old_password_error', trans('site.old_password_error'));

            return redirect()->back();
        }

        $request->validate([
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $password_updated = $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        if ($password_updated) {
            $request->session()->flash('password_updated', true);
        } else {
            $request->session()->flash('password_updated', false);
        }

        return redirect()->back();
    }

    public function post_checkout(Request $request)
    {
        if (! Session::has('cart')) {
            return redirect()->route('index');
        }

        $this->remove_from_cart_deleted_products();

        $request->validate([
            'district_id' => 'required|numeric',
            'address' => 'required',
            'payment_type' => 'required|numeric',
        ]);

        $district = DB::table('districts')->select('delivery_price')
            ->where('id', $request->district_id)->first();

        if ($district) {
            $delivery_price = $district->delivery_price;
        } else {
            return redirect()->back();
        }

        $percent = 0;

        // შეიყვანა ფასდაკლების კოდი
        if ($request->coupon) {
            $sale = DB::table('coupons')->where('code', $request->coupon)->first();

            if ($sale) {
                $percent = $sale->percent;
            }
        }

        $total = $this->get_cart_prices($percent, $delivery_price);

        // ონლაინ გადახდა
        if ($request->payment_type == 1) {
            return redirect()->back();
            /*
            $payment = new TbcPayProcessor(base_path() . '/tbc/cert.pem', 'mulgazanzari', $_SERVER['REMOTE_ADDR']);
            $payment->amount = number_format($total, 2, '.', '') * 100; // თეთრი
            $payment->currency = 981; // 981 = GEL
            $payment->description = 'Your product description, will be shown to client on card processing page!';
            $payment->language = 'GE'; // ინტერფეისის ენა
            $tbc_transaction = $payment->sms_start_transaction();

            $order_data = [];
            $order_code = substr(uniqid(), 0, 11);
            $order_data['user_id'] = Auth::user()->id;
            $order_data['district_id'] = $request->district_id;
            $order_data['payment_type'] = $request->payment_type;
            $order_data['pay_status'] = 0;
            $order_data['code'] = $order_code;
            $order_data['sale_code'] = $request->code;
            $order_data['sale_percent'] = $percent;
            $order_data['address'] = $request->address;
            $order_data['total'] = $total;
            $order_data['created_at'] = Carbon::now();
            $order_data['delivery'] = $request->delivery;
            $order_data['delivery_price'] = $delivery_price;
            $order_data['transaction_id'] = $tbc_transaction['TRANSACTION_ID']; // TBC ტრანზაქციის id
            $order_data['pay_status'] = 3; // გადახდის საწყისი ეტაპი

            if ($this->insertOrderGetID($order_data))
            {
                // უბრალოდ გადავიდა გადახდის გვერდზე, ტრანზაქციის შენახვა ფაილში
                // $txt = $tbc_transaction['TRANSACTION_ID'].'|'.Auth::user()->first_name . ' ' . Auth::user()->last_name.'|'.Auth::user()->phone . '|' . date('Y-m-d H:i:s') . "\n";
                //file_put_contents('../public_html/uploads/tbc/started_transactions.txt', $txt, FILE_APPEND);

                return view('tbc.pay', compact('tbc_transaction'));
            }
            else
            {
                return redirect('/cart')->with('order_sent', false);
            }
             *
             */
        } elseif ($request->payment_type == 2) { // საბანკო გადარიცხვა
            $invoice_data = [
                'address' => $request->address,
                'delivery_price' => $delivery_price,
            ];

            $order_data = [];
            $order_code = substr(uniqid(), 0, 11);
            $order_data['user_id'] = Auth::user()->id;
            $order_data['district_id'] = $request->district_id;
            $order_data['payment_type'] = $request->payment_type;
            $order_data['pay_status'] = 0;
            $order_data['code'] = $order_code;
            $order_data['sale_code'] = $request->code;
            $order_data['sale_percent'] = $percent;
            $order_data['address'] = $request->address;
            $order_data['total'] = $total['total'];
            $order_data['year'] = date('Y');
            $order_data['month'] = date('m');
            $order_data['day'] = date('d');
            $order_data['created_at'] = Carbon::now();
            $order_data['delivery'] = $request->delivery;
            $order_data['delivery_price'] = $delivery_price;

            if ($this->insertOrderGetID($order_data, $total['prices'])) {
                $invoice_data = [
                    'address' => $request->address,
                    'delivery_price' => $delivery_price,
                    'order_code' => $order_code,
                ];

                // $pdf = PDF::loadView('client.products.invoice', $invoice_data, ['format' => 'A4-P']);
                // $pdf->save('../public_html/uploads/invoices/'.$order_code.'.pdf');
                // return $pdf->download($order_code.'.pdf');

                /*
                $data = [
                    'order' => $order_data
                ];

                //if(request()->ip() === '188.129.146.186')
                //{
                    Mail::send('emails.order', $data,  function($message) use ($data){
                        $message->from('noreply@mbc.ge');
                        $message->to(Auth::user()->email);
                        //$message->to('petriashvili.elene@gmail.com');
                        $message->bcc(['petriashvili.elene@gmail.com']);
                        $message->subject(trans('invoice.order'));
                        $message->attach('../public_html/uploads/invoices/'.$data['order']['code'].'.pdf');
                    });
                // }
                */

                Session::forget('cart');

                return redirect('/profile')->with(['order_sent' => true, 'order_code' => $order_code]);
            } else {
                return redirect('/cart')->with('order_sent', false);
            }
        } else {
            return redirect()->back();
        }
    }

    public function insertOrderGetID($order_data, $item_prices)
    {
        $order_id = DB::table('orders')->insertGetId($order_data);

        foreach (Session::get('cart') as $prod_id => $item_data) {
            $orderProduct = [
                'order_id' => $order_id,
                'product_id' => $prod_id,
                'unit_price' => $item_prices[$prod_id],
                'price' => $item_prices[$prod_id] * $item_data['qty'],
                'qty' => $item_data['qty'],
            ];

            DB::table('order_products')->insert($orderProduct);
        }

        return $order_id;
    }

    public function success_tbc(Request $request): RedirectResponse
    {
        // ტრანზაქცია ვერ მოიძებნა
        if (empty($request['trans_id'])) {
            return redirect()->route('fail_tbc');
        }

        $order = Order::where('transaction_id', $request['trans_id'])->first();

        if (empty($order)) {
            return redirect()->route('fail_tbc');
        }

        $payment = new TbcPayProcessor(base_path().'/tbc/cert.pem', 'mulgazanzari', $_SERVER['REMOTE_ADDR']);

        $tbc_result = $payment->get_transaction_result($request['trans_id']);

        // ტრანზაქციის შენახვა ფაილში
        // $txt = $request['trans_id'].'|'.$request->ip().'|'.$user->first_name . ' ' .
        // $user->last_name.'|'.$user->phone.'|'.$tbc_result['CARD_NUMBER'].'|'.$tbc_result['RESULT'] ."\n";

        // შეავსო ინფორმაცია და ბანკიდანაც მოვიდა პასუხი
        // file_put_contents('../public_html/uploads/tbc/finished_transactions.txt', $txt, FILE_APPEND);
        // ტრანზაქციის შენახვა ფაილში

        // წარუმატებელი გადახდა
        if ($tbc_result['RESULT'] != 'OK') {
            $order->pay_status = 2;
            $order->update();

            return redirect('/cart')->with('order_sent', false);
        } else { // წარმატებული გადახდა
            $order->pay_status = 1;
            $order->update();

            Session::forget('cart');

            return redirect('/profile')->with('order_sent', true);
        }
    }

    public function fail_tbc()
    {
        return 'სისტემური შეცდომა ონლაინ-გადახდისას';
    }
}
