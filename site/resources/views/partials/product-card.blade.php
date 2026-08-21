@php
  $cat = $categories[$p['category']]['name'] ?? $p['category'];
  $img = $p['images'][0] ?? null;
@endphp
<a class="card product-card" href="{{ url('/products/'.$p['slug']) }}">
  @if ($img)
    <img src="{{ $img }}" alt="{{ $p['name'] }}" />
  @else
    <div class="ph">{{ $p['name'] }}</div>
  @endif
  <div class="body">
    <div class="cat">{{ $cat }}</div>
    <h3>{{ $p['name'] }}</h3>
    <p>{{ $p['shortDescription'] }}</p>
    <div class="more">View details →</div>
  </div>
</a>
