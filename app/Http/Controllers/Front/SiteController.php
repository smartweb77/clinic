<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $item = DB::table('subscribes')->select('id')->where('email', $request->email)->first();

            if ($item) {
                return response()->json([
                    'class' => 'alert-danger',
                    'message' => trans('site.you_are_subscriber_allready'),
                ]);
            }

            $insert = DB::table('subscribes')->insert(
                [
                    'email' => $request->email,
                ]
            );

            if ($insert) {
                return response()->json([
                    'class' => 'alert-success',
                    'message' => trans('site.you_are_subscriber_now'),
                ]);
            }
        }
    }

    public function search(Request $request)
    {
        if (strlen($request->keyword) < 2) {
            return redirect()->route('index');
        }

        $check = DB::table('searched_keywords')->where('keyword', $request->keyword)->first();

        if (! $check) {
            DB::table('searched_keywords')->insert(
                [
                    'keyword' => $request->keyword,
                    'search_count' => 1,
                ]
            );
        } else {
            DB::table('searched_keywords')->where('id', $check->id)->update([
                'search_count' => $check->search_count + 1,
            ]);
        }

        $product_ids_by_title = DB::table('products_translates')
            ->where('title', 'like', "%$request->keyword%")
            ->where('lang', $this->lang)->pluck('parent_id')->toArray();

        $products = collect();

        if (count($product_ids_by_title)) {
            $products = Product::allItems($this->lang, $status_on = true, $product_ids_by_title, $where_in_cat = false, $paginate = true, $get = false);
        }

        return view('client.products.search', compact('products'));
    }
}
