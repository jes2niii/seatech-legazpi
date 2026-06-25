<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;

class NewsController extends Controller
{
    public function index()
    {
        $posts = NewsPost::where('is_published', true)
            ->latest()
            ->paginate(9);

        return view('public.news', compact('posts'));
    }

    public function show($article)
    {
        $post = NewsPost::where('slug', $article)
            ->where('is_published', true)
            ->firstOrFail();

        return view('public.news-show', compact('post'));
    }
}
