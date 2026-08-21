<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function show()
    {
        $c = require resource_path('data/content.php');
        $bySlug = collect($c['products'])->keyBy('slug');
        $featured = collect($c['landing']['featuredProductSlugs'])
            ->map(fn (string $slug) => $bySlug->get($slug))
            ->filter()
            ->values();

        return view('home', [
            'page' => 'landing',
            'hasHero' => true,
            'featured' => $featured,
            'categories' => collect($c['productCategories'])->keyBy('slug'),
        ]);
    }
}
