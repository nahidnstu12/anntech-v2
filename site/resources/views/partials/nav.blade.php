<header class="nav{{ $hasHero ? '' : ' is-solid' }}" id="nav">
  <div class="wrap nav-inner">
    @include('partials.logo')
    <button class="nav-toggle" type="button" data-toggle aria-label="Open menu" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <ul class="nav-links">
      @foreach ($c['nav'] as $item)
        @php
          $isProducts = $page === 'products' || $page === 'product';
          $active = ($page === 'landing' && $item['label'] === 'Home')
            || ($isProducts && $item['label'] === 'Products');
          $cta = $item['label'] === 'Contact' ? ' btn btn-ghost nav-cta' : '';
        @endphp
        <li>
          <a class="{{ $cta }}{{ $active ? ' is-active' : '' }}" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
        </li>
      @endforeach
    </ul>
  </div>
</header>
