<footer class="footer">
  <div class="wrap">
    <div class="footer-top">
      @include('partials.logo')
      <nav class="footer-nav">
        @foreach ($c['nav'] as $item)
          <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
        @endforeach
      </nav>
    </div>
    <p class="footer-copy">© {{ now()->year }} {{ $c['company']['name'] }} — established {{ $c['company']['founded'] }}. {{ $c['company']['tagline'] }}</p>
  </div>
</footer>
