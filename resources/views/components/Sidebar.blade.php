@props([ 'variant' => 'default', ])

@switch($variant)

    @case('admin')
        @include('components.Sidebar.Admin')
        @break

    @case('vendor')
        @include('components.Sidebar.Vendor')
        @break

    @default
        @include('components.Sidebar.Admin')

@endswitch
