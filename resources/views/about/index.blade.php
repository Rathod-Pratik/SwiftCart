@extends('layouts.app')
@section('title', 'About SwiftCart')
@section('content')
    @include('about.components.hero')
    @include('about.components.why-choose-us')
    @include('about.components.team')
    @include('about.components.faq')
    @include('about.components.cta')
@endsection
