<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Slider;

class HomeController extends Controller
{
    /** Landing page publik. */
    public function index()
    {
        $sliders = Slider::active()->take(5)->get();

        $news = News::published()->take(6)->get();

        $featuredNews = $news->first();
        $latestNews   = $news->skip(1);

        return view('welcome', compact('sliders', 'featuredNews', 'latestNews'));
    }

    /** Daftar semua berita (publik). */
    public function newsIndex()
    {
        $news = News::published()->paginate(9);
        return view('news.index', compact('news'));
    }

    /** Detail berita (publik, by slug). */
    public function newsShow(string $slug)
    {
        $news = News::where('slug', $slug)->published()->firstOrFail();
        $news->increment('views');

        $related = News::published()
            ->where('id', '!=', $news->id)
            ->when($news->category, fn ($q) => $q->where('category', $news->category))
            ->take(3)
            ->get();

        return view('news.show', compact('news', 'related'));
    }
}
