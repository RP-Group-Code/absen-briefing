@extends('layouts.app')

@section('title', 'Analytics BDS')

@section('content')
    @include('partials.coming-soon', [
        'productName' => 'Analytics BDS',
        'icon' => 'bi-bar-chart-fill',
        'accent' => '#fbbf24',
        'accentRgb' => '251, 191, 36',
    ])
@endsection

@push('styles')
    @include('partials.coming-soon-styles')
@endpush
