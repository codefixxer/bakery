{{-- resources/views/dashboard.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-5">
  <h1 class="mb-4">Dashboard</h1>
  <p>Welcome, {{ auth()->user()->name }}!</p>
  {{-- … put your dashboard widgets here … --}}
</div>
@endsection
