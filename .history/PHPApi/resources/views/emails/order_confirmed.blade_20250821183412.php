<h2>Sipariş Onayı #{{ $order->id }}</h2>
<p>Merhaba {{ $order->user->name }},</p>
<p>Siparişiniz onaylandı. Toplam Tutar: {{ number_format($order->total_amount, 2) }} TL</p>

<h3>Ürünler</h3>
<ul>
@foreach ($order->items as $item)
  <li>{{ $item->product->name }} x {{ $item->quantity }} - {{ number_format($item->price, 2) }} TL</li>
@endforeach
</ul>

<p>Teşekkürler.</p>


