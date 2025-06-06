<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Product;
use Cache;
use Cookie;
use DB;
use Illuminate\Http\Request;
use Session;

class ProductsController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->request = $request;
    }

    public function shop($cat = null)
    {
        $cat_ids = []; // კატეგორის id-ები
        $where_in = []; // პროდიქციის id-ები
        $current_category = null;
        $query = Product::where('status', 1);

        // თუ არჩეულია კატეგორიის id
        if ($cat) {
            // მიმდინარე კატეგორია
            $current_category = $this->categories->firstWhere('id', (int) $cat);

            if (! $current_category) {
                return redirect('/');
            }

            // თუ არჩეული კატეგორია არის პირველი დონის
            if ($current_category->level == 1) {
                foreach ($current_category->childs as $child) {
                    array_push($cat_ids, $child->id); // შევაგროვოთ შვილი კატეგორიების id-ები
                }
            } else { // თუ არჩეული კატეგორია არის მეორე დონის
                $cat_ids = [$current_category->id];
            }

            if (count($cat_ids)) {
                $query = Product::whereIn('category_id', $cat_ids);
            }
        }

        // რეიტინგი
        if ($this->request->rate) {
            $query->where('rate', (int) $this->request->rate);
        }

        // ბრენდი
        if ($this->request->brand_ids) {
            $query->whereIn('brand_id', $this->request->brand_ids);
        }

        // ფასები
        if ($this->request->has('min') && $this->request->max) {
            if ($this->request->min == $this->request->max) {
                $query->where('price', $this->request->min);
            } else {
                $query->whereBetween('price', [$this->request->min, $this->request->max]);
            }
        }

        $where_in = $query->pluck('id')->toArray();

        if (empty($where_in)) {
            $products = collect();
        } else {
            $products = Product::allItems($this->lang, $status_on = true, $where_in, $where_in_cat = false, $paginate = true, $get = false);
        }

        $prices = DB::table('products')->select('price')->where('status', 1)
            ->orderBy('price')->pluck('price');

        $min = $prices->first();
        $max = $prices->last();

        return view('client.products.shop', compact('products', 'current_category', 'min', 'max'));
    }

    public function in($id)
    {
        if (Cache::has('products')) {
            $product = Cache::get('products')[$this->lang]->firstWhere('id', $id);
        } else {
            $product = Product::getItemInfo($id, $this->lang, $status_on = true);
        }

        if (! $product) {
            return abort(404);
        }

        if (Cache::has('products')) {
            $relateds = Cache::get('products')[$this->lang]->where('id', '<>', $id)
                ->where('category_id', $product->category_id);
        } else {
            $related_prod_ids = DB::table('products')->select('id')->where('status', 1)
                ->where('id', '<>', $id)->where('category_id', $product->category_id)
                ->inRandomOrder()->limit(4)->pluck('id')->toArray();

            $relateds = Product::allItems($this->lang, true, $related_prod_ids, false, false, true);
        }

        return view('client.products.in', compact('product', 'relateds'));
    }

    public function rate_product(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|numeric',
            'stars' => 'required|numeric|min:1|max:5',
        ]);

        if (! DB::table('products')->find($request->product_id)) {
            return redirect()->back();
        }

        DB::table('product_reviews')->insert(
            [
                'product_id' => $request->product_id,
                'stars' => $request->stars,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'comment' => $request->comment,
            ]
        );

        $stars = 0;
        $reviews = DB::table('product_reviews')->where('product_id', $request->product_id)->get();

        foreach ($reviews as $review) {
            $stars += $review->stars;
        }

        DB::table('products')->where('id', $request->product_id)->update([
            'rate' => round($stars / $reviews->count()),
        ]);

        return redirect()->back()->with('review_added', true);
    }

    /*
    |--------------------------------------------------------------------------
    | კალათი
    |--------------------------------------------------------------------------
    */
    public function cart()
    {
        $this->remove_from_cart_deleted_products();
        $districts = District::allItems($this->lang, $status_on = true);

        return view('client.products.cart', compact('districts'));
    }

    public function add_to_cart(Request $request)
    {
        if ($request->ajax() && $request->qty && $request->id) {
            $product = Product::find($request->id);

            if (! $product || ! $request->qty || ! $product->available || ! $product->status) {
                return response()->json(['status' => 0]);
            }

            $cart = session()->get('cart');

            // თუ კალათა ცარიელია ეს იქნება პირველი პროდუქტი კალათში
            if (! $cart) {
                $cart = [
                    $request->id => ['qty' => $request->qty],
                ];

                session()->put('cart', $cart);

                return response()->json([
                    'status' => 1,
                    'cart_items_count' => $this->getCartItemsCount(),
                ]);
            }

            /* თუ კალათა არ არის ცარიელი და ეს პროდუქტი უკვე არის მასში, მაშინ წავშალოთ იგი */
            if (isset($cart[$request->id])) {
                if ($cart[$request->id]['qty'] > 1) {
                    $cart[$request->id]['qty'] = --$cart[$request->id]['qty'];
                } else {
                    unset($cart[$request->id]);
                }

                session()->put('cart', $cart);

                return response()->json([
                    'status' => 1,
                    'cart_items_count' => $this->getCartItemsCount(),
                ]);
            }

            // თუ პროდუქტი არ არის კალათში დავამატოთ შესაბამისი რაოდენობით
            $cart[$request->id] = ['qty' => $request->qty];

            session()->put('cart', $cart);

            return response()->json([
                'status' => 1,
                'cart_items_count' => $this->getCartItemsCount(),
            ]);
        }
    }

    public function remove_from_cart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        if (! $this->getCartItemsCount()) {
            session()->forget('cart');
        }

        return redirect()->back();
    }

    public function update_cart(Request $request)
    {
        $cart = session()->get('cart');

        if (count($cart) !== count($request->prod_id) || count($cart) !== count($request->qty) || count($request->prod_id) !== count($request->qty)) {
            return redirect()->back();
        }

        foreach ($request->prod_id as $key => $prod_id) {
            if (isset($cart[$prod_id])) {
                if ($request->qty[$key]) {
                    $cart[$prod_id]['qty'] = $request->qty[$key];
                } else {
                    unset($cart[$prod_id]);
                }
            }
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('cart_updated', true);
    }

    public function move_wishlist_to_cart(Request $request)
    {
        $cart = session()->get('cart');

        if (! $cart) {
            $cart = [];

            foreach ($request->prod_id as $key => $prod_id) {
                $product = Product::find($prod_id);

                if (! $product || ! $product->available || ! $product->status) {
                    continue;
                }

                $cart[$prod_id] = [
                    'qty' => 1,
                    'price' => $product->price,
                    'old_price' => $product->old_price,
                ];
            }
        } else {
            foreach ($request->prod_id as $key => $prod_id) {
                $product = Product::find($prod_id);

                if (! $product || ! $product->available || ! $product->status) {
                    continue;
                }

                if (array_key_exists($prod_id, $cart)) {
                    $cart[$prod_id]['qty'] = $cart[$prod_id]['qty'] + 1;
                } else {
                    $cart[$prod_id] = [
                        'qty' => 1,
                        'price' => $product->price,
                        'old_price' => $product->old_price,
                    ];
                }
            }
        }

        /*
         * აქ ჩასასწორებელია ერთი რამე : თუ სურვილების სიაში ისეთი პროდუქტია
         * რომლის available == fase, მაშინ ეს პროდუქტი კალათში არ ემატება,
         * მაგრამ სურვილებიდან მაინც იშლება
        */
        session()->put('cart', $cart);
        Cookie::queue(Cookie::forget('wishlist'));

        return redirect()->route('cart')->with('cart_updated', true);
    }

    public function clear_cart()
    {
        if (Session::has('cart')) {
            Session::forget('cart');
        }

        return redirect()->back();
    }

    public function getCartItemsCount()
    {
        $cart_items_count = 0;

        if (Session::has('cart')) {
            foreach (Session::get('cart') as $prod_id => $item_data) {
                $cart_items_count += $item_data['qty'];
            }
        }

        return $cart_items_count;
    }

    public function getCartItemsTotal()
    {
        $cart_items_total = 0;

        if (Session::has('cart')) {
            foreach (Session::get('cart') as $prod_id => $item_data) {
                $product = DB::table('products')->select('price')->where('id', $prod_id)->first();

                if ($product) {
                    $cart_items_total += $item_data['qty'] * $product->price;
                }
            }
        }

        return $cart_items_total;
    }

    /*
    |--------------------------------------------------------------------------
    | სურვილების სია
    |--------------------------------------------------------------------------
    */
    public function wishlist()
    {
        return view('client.products.wishlist');
    }

    public function add_to_wishlist(Request $request)
    {
        if ($request->ajax()) {
            $wishlist = explode(',', Cookie::get('wishlist'));

            // თუ არის უკვე, წავშალოთ
            if (in_array($request->id, $wishlist)) {
                $added = false;
                unset($wishlist[array_search($request->id, $wishlist)]);
            } else {
                $added = true;
                array_push($wishlist, $request->id);
            }

            // შენახვა ხუთი წლით :))
            Cookie::queue('wishlist', implode(',', $wishlist), 2628000);

            return response()->json(['status' => 1, 'added' => $added]);
        }
    }

    public function remove_from_wishlist($id)
    {
        $wishlist = explode(',', Cookie::get('wishlist'));

        if (in_array($id, $wishlist)) {
            unset($wishlist[array_search($id, $wishlist)]);
        }

        if (count($wishlist) == 1) {
            Cookie::queue(Cookie::forget('wishlist'));
        } else {
            // შენახვა ხუთი წლით :))
            Cookie::queue('wishlist', implode(',', $wishlist), 2628000);
        }

        return redirect()->back();
    }

    public function clear_wishlist()
    {
        Cookie::queue(Cookie::forget('wishlist'));

        return redirect()->back();
    }

    /*
    |--------------------------------------------------------------------------
    | ბუღალტერია :))
    |--------------------------------------------------------------------------
    */
    public function check_code(Request $request)
    {
        if ($request->ajax()) {
            $sale = $this->get_sale($request->code);

            if ($sale) {
                return response()->json([
                    'status' => 1,
                    'total' => $this->calculateDiscountedTotal($request->total, $sale->percent),
                ]);
            } else {
                return response()->json(['status' => 0]);
            }
        }
    }

    public function get_sale($code)
    {
        return DB::table('coupons')->where('code', $code)->first();
    }

    public function calculateDiscountedTotal($total, $percent)
    {
        $discount = ($total / 100) * $percent;

        return $total - $discount;
    }
}
