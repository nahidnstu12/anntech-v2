@extends('layouts.app')

@section('title', $p['name'].' — '.$c['company']['name'])
@section('description', $p['shortDescription'])
@section('keywords', implode(', ', array_filter([$p['name'], $catName, 'Enovak', 'pharmaceutical equipment Bangladesh', $p['slug']])))
@section('canonical', url('/products/'.$p['slug']))
@section('og_type', 'product')
@section('og_image', url(($p['images'][0] ?? $c['company']['ogImage'])))
@section('og_image_alt', $p['name'])

@push('jsonld')
  <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'Product',
      'name' => $p['name'],
      'description' => $p['shortDescription'],
      'image' => collect($p['images'] ?? [])->map(fn ($src) => url($src))->all(),
      'brand' => ['@type' => 'Brand', 'name' => $c['company']['name']],
      'category' => $catName,
      'url' => url('/products/'.$p['slug']),
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
  </script>
@endpush

@section('content')
  @php
    $imgs = $p['images'] ?? [];
  @endphp
  <section class="page-hero" style="padding-bottom:24px">
    <div class="wrap">
      <p class="eyebrow">{{ $catName }}</p>
      <h1>{{ $p['name'] }}</h1>
    </div>
  </section>
  <section class="wrap detail">
    <div>
      @if ($imgs)
        <img id="hero-shot" src="{{ $imgs[0] }}" alt="{{ $p['name'] }}" />
        @if (count($imgs) > 1)
          <div class="thumbs">
            @foreach ($imgs as $i => $src)
              <button type="button" class="thumb{{ $i === 0 ? ' is-on' : '' }}" data-src="{{ $src }}">
                <img src="{{ $src }}" alt="" />
              </button>
            @endforeach
          </div>
        @endif
      @else
        <div class="ph" style="height:480px">{{ $p['name'] }}</div>
      @endif
    </div>
    <div>
      <p class="muted">{{ $p['shortDescription'] }}</p>
      @if (!empty($p['specs']))
        <dl class="specs">
          @foreach ($p['specs'] as $k => $v)
            <div>
              <dt>{{ $k }}</dt>
              <dd>{{ $v }}</dd>
            </div>
          @endforeach
        </dl>
      @endif
      <a class="btn btn-primary" href="{{ url('/#contact') }}">Enquire</a>
      <a class="btn btn-line" href="{{ url('/products') }}" style="margin-left:8px">All products</a>
    </div>
  </section>
@endsection
