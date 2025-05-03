{{-- resources/views/frontend/costs/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', $cost->cost_identifier ?? 'Cost #' . $cost->id)

@section('content')
<div class="container py-5">
  <div class="card border-warning shadow-lg rounded-3 overflow-hidden">
    <!-- Header with icon and title -->
    <div class="card-header bg-warning text-dark d-flex align-items-center">
      <i class="bi bi-currency-dollar fs-2 me-3"></i>
      <h4 class="mb-0">{{ $cost->cost_identifier ?? 'Cost #' . $cost->id }}</h4>
    </div>
    <div class="card-body">
      <!-- Details grid -->
      <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Supplier</h6>
          <p class="fs-5 mb-0">{{ $cost->supplier }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Amount</h6>
          <p class="fs-5 mb-0">${{ number_format($cost->amount, 2) }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Due Date</h6>
          <p class="fs-5 mb-0">{{ optional($cost->due_date)->format('Y-m-d') }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Category</h6>
          <p class="fs-5 mb-0">{{ $cost->category->name ?? '–' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Created At</h6>
          <p class="fs-5 mb-0">{{ optional($cost->created_at)->format('Y-m-d H:i') }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Last Updated</h6>
          <p class="fs-5 mb-0">{{ optional($cost->updated_at)->format('Y-m-d H:i') }}</p>
        </div>
      </div>

      <hr class="border-secondary">

      <!-- Action Buttons -->
      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('costs.edit', $cost) }}" class="btn btn-outline-primary btn-lg">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('costs.index') }}" class="btn btn-outline-secondary btn-lg">
          <i class="bi bi-arrow-left me-1"></i>Back to List
        </a>
        <form action="{{ route('costs.destroy', $cost) }}"
              method="POST"
              onsubmit="return confirm('Delete this cost?');">
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
