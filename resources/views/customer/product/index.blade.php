@extends('layouts.app')
@section('title', 'Products')
@section('content')
    <x-header>Products</x-header>
    @include('product.components.product-list')
@endsection