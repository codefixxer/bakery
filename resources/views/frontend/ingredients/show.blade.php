@extends('frontend.layouts.app')

@section('title', 'View Ingredient')

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-lg rounded-3 overflow-hidden">
    <!-- Header with icon and title -->
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <iconify-icon icon="lucide:package" class="fs-2 me-3"></iconify-icon>
      <h4 class="mb-0">{{ $ingredient->ingredient_name }}</h4>
    </div>

    <div class="card-body">
      <!-- Details in a 2-column grid -->
      <div class="row g-4 mb-3">
        <div class="col-md-6">
          <h6 class="text-uppercase text-muted small">Price per kg</h6>
          <p class="fs-3 fw-semibold">€{{ number_format($ingredient->price_per_kg, 2) }}</p>
        </div>
        <div class="col-md-6">
          <h6 class="text-uppercase text-muted small">Created At</h6>
          <p class="fs-5">{{ $ingredient->created_at->format('Y-m-d H:i') }}</p>
        </div>
        <div class="col-md-6">
          <h6 class="text-uppercase text-muted small">Last Updated</h6>
          <p class="fs-5">{{ $ingredient->updated_at->format('Y-m-d H:i') }}</p>
        </div>
      </div>

      <hr class="border-secondary">

      <!-- Action buttons -->
      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('ingredients.edit', $ingredient) }}" class="btn btn-outline-primary btn-lg">
          <iconify-icon icon="lucide:edit" class="me-1"></iconify-icon>
          Edit
        </a>

        <a href="{{ route('ingredients.index') }}" class="btn btn-outline-secondary btn-lg">
          <iconify-icon icon="lucide:arrow-left" class="me-1"></iconify-icon>
          Back to List
        </a>

        <form action="{{ route('ingredients.destroy', $ingredient) }}"
              method="POST"
              onsubmit="return confirm('Delete this ingredient?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-lg">
            <iconify-icon icon="mingcute:delete-2-line" class="me-1"></iconify-icon>
            Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
