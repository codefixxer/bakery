{{-- resources/views/pastry-chefs/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', $pastryChef->name)

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-lg rounded-3 overflow-hidden">
    <!-- Header with icon and title -->
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <iconify-icon icon="lucide:user-chef" class="fs-2 me-3"></iconify-icon>
      <h4 class="mb-0">{{ $pastryChef->name }}</h4>
    </div>
    <div class="card-body">
      <!-- Details grid -->
      <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Chef Name</h6>
          <p class="fs-3 fw-bold mb-0">{{ $pastryChef->name }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Email</h6>
          <p class="fs-5 mb-0">{{ $pastryChef->email ?? '—' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Phone</h6>
          <p class="fs-5 mb-0">{{ $pastryChef->phone ?? '—' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Created At</h6>
          <p class="fs-5 mb-0">{{ optional($pastryChef->created_at)->format('Y-m-d H:i') ?? '—' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Last Updated</h6>
          <p class="fs-5 mb-0">{{ optional($pastryChef->updated_at)->format('Y-m-d H:i') ?? '—' }}</p>
        </div>
      </div>

      <hr class="border-secondary">

      <!-- Action buttons -->
      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('pastry-chefs.edit', $pastryChef) }}" class="btn btn-outline-primary btn-lg">
          <iconify-icon icon="lucide:edit" class="me-1"></iconify-icon>Edit
        </a>
        <a href="{{ route('pastry-chefs.index') }}" class="btn btn-outline-secondary btn-lg">
          <iconify-icon icon="lucide:arrow-left" class="me-1"></iconify-icon>Back to List
        </a>
        <form action="{{ route('pastry-chefs.destroy', $pastryChef) }}"
              method="POST"
              onsubmit="return confirm('Delete this chef?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-lg">
            <iconify-icon icon="mingcute:delete-2-line" class="me-1"></iconify-icon>Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
