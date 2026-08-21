<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    @php
      $co = $c['company'];
      $seo = $c['seo'];
      $metaTitle = trim($__env->yieldContent('title', $seo['title']));
      $metaDescription = trim($__env->yieldContent('description', $seo['description']));
      $metaKeywords = trim($__env->yieldContent('keywords', $seo['keywords']));
      $canonical = trim($__env->yieldContent('canonical', url()->current()));
      $ogType = trim($__env->yieldContent('og_type', 'website'));
      $ogImage = trim($__env->yieldContent('og_image', url($co['ogImage'])));
      $ogImageAlt = trim($__env->yieldContent('og_image_alt', $co['name']));
    @endphp
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}" />
    <meta name="keywords" content="{{ $metaKeywords }}" />
    <meta name="author" content="{{ $co['name'] }}" />
    <meta name="robots" content="index,follow" />
    <meta name="theme-color" content="#2f5a21" />
    <link rel="canonical" href="{{ $canonical }}" />
    <link rel="icon" href="{{ url('/favicon.svg') }}" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="{{ url('/apple-touch-icon.png') }}" />
    <meta property="og:site_name" content="{{ $co['name'] }}" />
    <meta property="og:type" content="{{ $ogType }}" />
    <meta property="og:locale" content="en_BD" />
    <meta property="og:title" content="{{ $metaTitle }}" />
    <meta property="og:description" content="{{ $metaDescription }}" />
    <meta property="og:url" content="{{ $canonical }}" />
    <meta property="og:image" content="{{ $ogImage }}" />
    <meta property="og:image:alt" content="{{ $ogImageAlt }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $metaTitle }}" />
    <meta name="twitter:description" content="{{ $metaDescription }}" />
    <meta name="twitter:image" content="{{ $ogImage }}" />
    <script type="application/ld+json">
      {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $co['name'],
        'url' => url('/'),
        'logo' => url($co['logo']),
        'foundingDate' => (string) $co['founded'],
        'description' => $seo['description'],
        'email' => $co['email'],
        'telephone' => $co['phone'],
        'address' => [
          '@type' => 'PostalAddress',
          'streetAddress' => $co['address'],
          'addressLocality' => 'Dhaka',
          'postalCode' => '1215',
          'addressCountry' => 'BD',
        ],
      ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
    </script>
    @stack('jsonld')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600&family=Montserrat:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body data-page="{{ $page }}" data-has-hero="{{ $hasHero ? '1' : '0' }}">
    @include('partials.nav')
    @yield('content')
    @include('partials.footer')
  </body>
</html>
