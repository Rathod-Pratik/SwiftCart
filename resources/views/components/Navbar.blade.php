@props([ 'variant' => 'default', ])

@switch($variant)

    @case('default')
        @include('components.Navbar.default')
        @break

    @case('admin')
        @include('components.Navbar.admin')
        @break

    @case('vendor')
        @include('components.Navbar.vendor')
        @break

    @default
        @include('components.Navbar.default')

@endswitch
