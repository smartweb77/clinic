<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategories;
use App\Models\Tag;
use DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogsController extends Controller
{
    public function index(Request $request): View
    {
        $where_in = [];
        $cat_title = '';
        $tag_title = '';
        $query = News::where('status', 1);

        // კატეგორია
        if ($request->cat && $request->cat) {
            $query->where('category_id', (int) $request->cat);
            $cat_title = DB::table('news_categories_translates')->select('title')
                ->where('lang', $this->lang)->where('parent_id', $request->cat)->first()->title;
        }

        // ნიშნული
        if ($request->tag) {
            $tag_id = (int) $request->tag;
            $query->where('tag_ids', 'like', "%$tag_id%");

            $tag_title = DB::table('tags_translates')->select('title')
                ->where('lang', $this->lang)->where('parent_id', $request->tag)->first()->title;
        }

        $where_in = $query->pluck('id')->toArray();

        if (empty($where_in)) {
            $blogs = collect();
        } else {
            $blogs = News::allItems($this->lang, $status_on = true, $where_in);
        }

        return view('client.blogs.index', compact('blogs', 'cat_title', 'tag_title'));
    }

    public function in($id)
    {
        $blog = News::getItemInfo($id, $this->lang, $status_on = true);

        if (! $blog) {
            return abort(404);
        }

        $relateds = collect();

        if ($blog->category_id) {
            $related_blog_ids = DB::table('news')->select('id')->where('status', 1)
                ->where('id', '<>', $id)->where('category_id', $blog->category_id)
                ->inRandomOrder()->limit(4)->pluck('id')->toArray();

            if (count($related_blog_ids)) {
                $relateds = News::allItems($this->lang, $status_on = true, $related_blog_ids);
            }
        }

        $tags = Tag::allItems($this->lang, $status_on = true);
        $categories = NewsCategories::allItems($this->lang, $status_on = true);

        $blogs_total = 0;

        foreach ($categories as $category) {
            $blogs_total += $category->newses;
        }

        return view('client.blogs.in', compact('blog', 'relateds', 'tags', 'categories', 'blogs_total'));
    }
}
