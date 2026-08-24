@extends('layouts.app')

@section('title', 'Analytics BDS')

@section('content')
    <div class="analytics-empty-main" aria-label="Area utama Analytics BDS"></div>
@endsection

@push('styles')
    <style>
        .analytics-empty-main {
            min-height: calc(100vh - 118px);
        }
    </style>
@endpush
