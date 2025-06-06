<?php

namespace App\Http\Controllers;

use App;
use App\Models\Information;
use App\Models\Product;
use App\Models\Seo;
use Cache;
use DB;
use Illuminate\Http\Request;
use LaravelLocalization;
use Session;
use View;

class Controller
{

    protected $lang; // მიმდინარე ენა

    protected $seo_routes; // ის მარშრუტები, რომლებსაც ჭირდება SEO ტეგები

    protected $config; // json ფაილში შენახული კონფიგურაციული პარამეტრები : ინფორმაცია ქეშირებების შესახებ ...

    protected $available_langs; // ხელმისაწვდომი ენები

    public function __construct(Request $request)
    {
        $this->lang = App::getLocale();
        $this->seo_routes = ['index', 'news', 'products'];
        $this->available_langs = LaravelLocalization::getSupportedLocales();
        $this->config = file_exists(public_path('config.json')) ?
                json_decode(file_get_contents(public_path('config.json')), true) : false;

        View::share('lang', $this->lang);
        View::share('contact_info', Cache::has('informations') ? Cache::get('informations')[$this->lang] : Information::getItemInfo(3, $this->lang));
    }

    // თუ კალათში დამატებული პროდუქტი წაშალა ადმინმა
    public function remove_from_cart_deleted_products()
    {
        $cart = Session::get('cart');

        if ($cart) {
            foreach ($cart as $prod_id => $qty) {
                $product = Product::find($prod_id);

                if (! $product || ! $product->status || ! $product->available) {
                    unset($cart[$prod_id]);
                }
            }

            Session::put('cart', $cart);
        }
    }

    public function get_cart_prices($percent, $delivery_price)
    {
        if (Session::has('cart')) {
            $result = [];
            $cart_items_total = 0;

            foreach (Session::get('cart') as $prod_id => $item_data) {
                $product = DB::table('products')->select('price')->where('id', $prod_id)->first();

                if ($product) {
                    $result['prices'][$prod_id] = $product->price;
                    $cart_items_total += $item_data['qty'] * $product->price;
                } else {
                    continue;
                }
            }

            $discount = ($cart_items_total / 100) * $percent;
            $discounted_total = $cart_items_total - $discount;
            $result['total'] = $discounted_total + $delivery_price;

            return $result;
        }

        return false;
    }

    public function get_seo($route = null)
    {
        if (Cache::has('seos')) {
            $seo = Cache::get('seos')[$this->lang]->firstWhere('route', $route);
        } else {
            $seo = Seo::getItemInfo($route, $this->lang);
        }

        return $seo;
    }
}
