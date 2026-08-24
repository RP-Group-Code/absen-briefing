@extends('layouts.app')

@section('title', 'EyeForce E-Channel')

@section('content')
    <div class="eyeforce-empty-main" aria-label="Area utama EyeForce E-Channel"></div>
@endsection

@push('styles')
    <style>
        .eyeforce-empty-main {
            min-height: calc(100vh - 118px);
        }
    </style>
@endpush
