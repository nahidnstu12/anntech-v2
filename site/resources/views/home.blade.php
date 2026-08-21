@extends('layouts.app')

@section('canonical', url('/'))

@section('content')
  @php
    $hero = $c['landing']['hero'];
    $about = $c['landing']['about'];
    $md = $about['leadership'];
    $services = $c['landing']['services'];
  @endphp

  <section class="hero" id="home">
    <div class="hero-media">
      <img src="{{ $hero['image'] }}" alt="" />
    </div>
    <div class="wrap hero-copy">
      <p class="eyebrow reveal">{{ $hero['eyebrow'] }}</p>
      <h1 class="reveal reveal-d1">{{ $hero['headline'] }}</h1>
      <p class="reveal reveal-d2">{{ $hero['sub'] }}</p>
      <div class="hero-actions reveal reveal-d3">
        <a class="btn btn-primary" href="{{ $hero['primaryCta']['href'] }}">{{ $hero['primaryCta']['label'] }}</a>
        <a class="btn btn-ghost" href="{{ $hero['secondaryCta']['href'] }}">{{ $hero['secondaryCta']['label'] }}</a>
      </div>
    </div>
  </section>

  <section class="section" id="about">
    <div class="wrap split">
      <div>
        <p class="eyebrow">About</p>
        <h2>{{ $about['heading'] }}</h2>
        @foreach ($about['body'] as $p)
          <p class="lead muted">{{ $p }}</p>
        @endforeach
      </div>
      <img src="https://images.unsplash.com/photo-1582719471384-894fbb16e074?auto=format&fit=crop&w=1200&q=80" alt="Laboratory and process environment" />
    </div>
    <div class="wrap">
      <article class="leader">
        <img src="{{ $md['image'] }}" alt="{{ $md['name'] }}, {{ $md['title'] }}" />
        <div>
          <span>{{ $md['title'] }}</span>
          <strong>{{ $md['name'] }}</strong>
          <p class="muted">{{ $md['bio'] }}</p>
        </div>
      </article>
    </div>
    <div class="wrap">
      <div class="values">
        @foreach ($about['values'] as $i => $v)
          <article class="card">
            <div class="index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
            <h3>{{ $v['title'] }}</h3>
            <p>{{ $v['text'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="section section-surface" id="services">
    <div class="wrap">
      <div class="section-head">
        <p class="eyebrow">Services</p>
        <h2>Design through validation</h2>
        <p class="muted">End-to-end engineering for regulated plants in Bangladesh — supply, install, commission, document, validate.</p>
      </div>
      <div class="services">
        @foreach ($services as $i => $s)
          <article class="card">
            <div class="index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
            <h3>{{ $s['name'] }}</h3>
            <p>{{ $s['summary'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="section" id="products-teaser">
    <div class="wrap">
      <div class="section-head">
        <p class="eyebrow">Products</p>
        <h2>Equipment we supply</h2>
        <p class="muted">Clean room and HVAC, process water, production machinery, packaging and QC — specified to the plant, not a catalogue default.</p>
      </div>
      <div class="products">
        @foreach ($featured as $p)
          @include('partials.product-card', ['p' => $p, 'categories' => $categories])
        @endforeach
      </div>
      <p style="margin-top:32px"><a class="btn btn-line" href="{{ url('/products') }}">View all products</a></p>
    </div>
  </section>

  <section class="section section-surface" id="showcase">
    <div class="wrap">
      <div class="section-head">
        <p class="eyebrow">Showcase</p>
        <h2>Project history & achievements</h2>
        <p class="muted">Selected process lines and plant work across solid, liquid, sterile and specialty dosage forms.</p>
      </div>
      <div class="showcase">
        @foreach (array_slice($c['showcase'], 0, 8) as $i => $s)
          <article class="card showcase-card">
            <div class="index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} · {{ $s['industry'] }}</div>
            <h3>{{ $s['title'] }}</h3>
            <p class="process">{{ $s['process'] }}</p>
            <p>{{ $s['summary'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="band">
    <div class="wrap band-inner">
      <h2>Looking for a reliable engineering partner?</h2>
      <a class="btn btn-ghost" href="#contact">Get in touch</a>
    </div>
  </section>

  <section class="section" id="contact">
    <div class="wrap contact-grid">
      <div>
        <p class="eyebrow">Contact</p>
        <h2>Discuss a project</h2>
        <form class="form" method="post" action="{{ url('/contact') }}">
          @csrf
          @if (session('status'))
            <p class="ok">{{ session('status') }}</p>
          @endif
          <label>Name
            <input name="name" value="{{ old('name') }}" required />
            @error('name')<span class="err">{{ $message }}</span>@enderror
          </label>
          <label>Email
            <input type="email" name="email" value="{{ old('email') }}" required />
            @error('email')<span class="err">{{ $message }}</span>@enderror
          </label>
          <label>Phone
            <input name="phone" value="{{ old('phone') }}" required />
            @error('phone')<span class="err">{{ $message }}</span>@enderror
          </label>
          <label>Message
            <textarea name="message" required>{{ old('message') }}</textarea>
            @error('message')<span class="err">{{ $message }}</span>@enderror
          </label>
          <button class="btn btn-primary" type="submit">Send</button>
        </form>
      </div>
      <div>
        <p class="eyebrow">Office</p>
        <h2>{{ $c['company']['name'] }}</h2>
        <ul class="info-list">
          <li><span>Address</span>{{ $c['company']['address'] }}</li>
          <li><span>Phone</span>{{ $c['company']['phone'] }}</li>
          <li><span>Email</span>{{ $c['company']['email'] }}</li>
        </ul>
        <iframe
          class="map"
          src="{{ $c['company']['mapEmbed'] }}"
          allowfullscreen
          loading="lazy"
          referrerpolicy="strict-origin-when-cross-origin"
          title="Enovak office on Google Maps"
        ></iframe>
        <p class="map-link"><a href="{{ $c['company']['mapsUrl'] }}" target="_blank" rel="noopener">Open in Google Maps →</a></p>
      </div>
    </div>
  </section>
@endsection
