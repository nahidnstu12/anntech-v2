<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $c = require resource_path('data/content.php');
        $category = $request->query('category');
        $products = collect($c['products']);

        if (is_string($category) && $category !== '') {
            $products = $products->where('category', $category)->values();
        }

        return view('products.index', [
            'page' => 'products',
            'hasHero' => false,
            'products' => $products,
            'categories' => collect($c['productCategories'])->keyBy('slug'),
            'activeCategory' => $category ?: null,
        ]);
    }

    public function show(string $slug)
    {
        $c = require resource_path('data/content.php');
        $product = collect($c['products'])->firstWhere('slug', $slug);

        abort_unless($product, 404);

        return view('products.show', [
            'page' => 'product',
            'hasHero' => false,
            'p' => $product,
            'catName' => collect($c['productCategories'])->firstWhere('slug', $product['category'])['name'] ?? $product['category'],
        ]);
    }
}
