@extends('layouts.app')

@section('title', 'Products — '.$c['company']['name'])
@section('description', 'Pharmaceutical production, packaging, clean room, HVAC, laboratory and water-system equipment supplied by Enovak for plants in Bangladesh.')
@section('keywords', 'Enovak products, pharmaceutical machinery Bangladesh, clean room panel, AHU, tablet press, blister line, stability chamber, WFI, granulator')
@section('canonical', url('/products'))

@section('content')
  <section class="page-hero">
    <div class="wrap">
      <p class="eyebrow">Catalog</p>
      <h1>Products</h1>
      <p class="muted" style="margin-top:16px;max-width:54ch">Production, packaging, clean room, laboratory and water-system equipment sourced for pharmaceutical plants in Bangladesh.</p>
      <div class="filters">
        <a class="chip{{ $activeCategory === null ? ' is-on' : '' }}" href="{{ url('/products') }}">All</a>
        @foreach ($c['productCategories'] as $cat)
          <a class="chip{{ $activeCategory === $cat['slug'] ? ' is-on' : '' }}" href="{{ url('/products?category='.$cat['slug']) }}">{{ $cat['name'] }}</a>
        @endforeach
      </div>
    </div>
  </section>
  <section class="section" style="padding-top:32px">
    <div class="wrap">
      <div class="products" id="catalog">
        @forelse ($products as $p)
          @include('partials.product-card', ['p' => $p, 'categories' => $categories])
        @empty
          <p class="muted">No products in this category yet.</p>
        @endforelse
      </div>
    </div>
  </section>
@endsection
