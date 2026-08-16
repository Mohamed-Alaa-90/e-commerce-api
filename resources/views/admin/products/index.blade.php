<h1>Products</h1>

@foreach ($products as $product)
    <h2>{{ $product->name }}</h2>
@endforeach