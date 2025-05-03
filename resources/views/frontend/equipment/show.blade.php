{{-- resources/views/frontend/equipment/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', $equipment->name)

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-lg rounded-3 overflow-hidden">
    <!-- Header with icon and title -->
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-tools fs-2 me-3"></i>
      <h4 class="mb-0">{{ $equipment->name }}</h4>
    </div>
    <div class="card-body">
      <!-- Details grid -->
      <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Equipment Name</h6>
          <p class="fs-3 fw-bold mb-0">{{ $equipment->name }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Equipment ID</h6>
          <p class="fs-5 mb-0">{{ $equipment->id }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Created At</h6>
          <p class="fs-5 mb-0">{{ optional($equipment->created_at)->format('Y-m-d H:i') ?? '—' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Last Updated</h6>
          <p class="fs-5 mb-0">{{ optional($equipment->updated_at)->format('Y-m-d H:i') ?? '—' }}</p>
        </div>
      </div>

      <hr class="border-secondary">

      <!-- Action buttons -->
      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-outline-primary btn-lg">
          <i class="bi bi-pencil-square me-1"></i>Edit
        </a>
        <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary btn-lg">
          <i class="bi bi-arrow-left me-1"></i>Back to List
        </a>
        <form action="{{ route('equipment.destroy', $equipment) }}"
              method="POST"
              onsubmit="return confirm('Delete this equipment?');">
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
