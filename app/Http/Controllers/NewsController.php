<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::published()
            ->latest('published_at')
            ->paginate(12);

        return view('layouts.news.index', compact('news'));
    }

    public function show(string $slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();
        $news->incrementViews();

        $related = News::published()
            ->where('id', '!=', $news->id)
            ->when($news->category, fn($q) => $q->where('category', $news->category))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('layouts.news.show', compact('news', 'related'));
    }
}