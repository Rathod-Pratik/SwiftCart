@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>My Wishlist</h1>
        {{-- @if ($wishlistItems->isEmpty())
            <p>Your wishlist is empty.</p>
        @else --}}
        <ul class="list-group">
            {{-- @foreach ($wishlistItems as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item->product->name }}
                        <form action="{{ route('wishlist.remove', $item->id) }}" method="POST" class="ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </li>
                @endforeach --}}
        </ul>
        {{-- @endif --}}
    </div>
    @push('scripts')
        <script></script>
    @endpush
@endsection
