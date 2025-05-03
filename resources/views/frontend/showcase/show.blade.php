{{-- resources/views/frontend/showcase/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', $showcase->showcase_date)

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-lg rounded-3 overflow-hidden">
    <!-- Header with icon and date -->
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-calendar-event fs-2 me-3"></i>
      <h4 class="mb-0">Showcase: {{ $showcase->showcase_date }}</h4>
    </div>
    <div class="card-body">
      <!-- Details grid -->
      <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Break-even (€)</h6>
          <p class="fs-4 fw-bold mb-0">{{ number_format($showcase->break_even, 2) }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Total Revenue (€)</h6>
          <p class="fs-4 fw-bold mb-0">{{ number_format($showcase->total_revenue, 2) }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Potential Avg (€)</h6>
          <p class="fs-4 fw-bold mb-0">{{ number_format($showcase->potential_income_average, 2) }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Plus (€)</h6>
          <p class="fs-4 fw-bold mb-0">{{ number_format($showcase->plus, 2) }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Real Margin (%)</h6>
          <p class="fs-4 fw-bold mb-0">
            @if($showcase->real_margin >= 0)
              <span class="text-success">{{ $showcase->real_margin }}%</span>
            @else
              <span class="text-danger">{{ $showcase->real_margin }}%</span>
            @endif
          </p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Created At</h6>
          <p class="fs-5 mb-0">{{ optional($showcase->created_at)->format('Y-m-d H:i') ?? '—' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Last Updated</h6>
          <p class="fs-5 mb-0">{{ optional($showcase->updated_at)->format('Y-m-d H:i') ?? '—' }}</p>
        </div>
      </div>

      <hr class="border-secondary">

      <!-- Action Buttons -->
      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('showcase.edit', $showcase) }}" class="btn btn-outline-primary btn-lg">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('showcase.index') }}" class="btn btn-outline-secondary btn-lg">
          <i class="bi bi-arrow-left me-1"></i>Back to List
        </a>
        <form action="{{ route('showcase.destroy', $showcase) }}"
              method="POST"
              onsubmit="return confirm('Delete this showcase?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-lg">
            <i class="bi bi-trash me-1"></i>Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
