<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'show'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/sitemap.xml', function () {
    $c = require resource_path('data/content.php');
    $urls = collect([url('/'), url('/products')])
        ->merge(collect($c['products'])->map(fn (array $p) => url('/products/'.$p['slug'])))
        ->all();

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
