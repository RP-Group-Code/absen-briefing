@extends('layouts.app')

@section('title', 'EyeForce E-Channel')

@section('content')
    @include('partials.coming-soon', [
        'productName' => 'EyeForce E-Channel',
        'icon' => 'bi-eye-fill',
        'accent' => '#38bdf8',
        'accentRgb' => '56, 189, 248',
    ])
@endsection

@push('styles')
    @include('partials.coming-soon-styles')
@endpush
