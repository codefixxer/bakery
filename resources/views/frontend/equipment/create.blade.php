@extends('frontend.layouts.app')

@section('title', 'Add Equipment  ')


@section('content')
<div class="container py-5">
  <div class="card border-success shadow-sm">
    <div class="card-header bg-success text-white d-flex align-items-center">
      <i class="bi bi-box-seam fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($equipment) ? 'Edit Equipment' : 'Add Equipment' }}</h5>
    </div>
    <div class="card-body">
      <form 
        action="{{ isset($equipment) ? route('equipment.update', $equipment->id) : route('equipment.store') }}" 
        method="POST" 
        class="row g-3 needs-validation" 
        novalidate
      >
        @csrf
        @if(isset($equipment))
          @method('PUT')
        @endif

        <!-- Chef Name -->
        <div class="col-md-6">
          <label for="Name" class="form-label fw-semibold">Equipment Name</label>
          <input type="text"
                 id="Name"
                 name="name"
                 class="form-control form-control-lg"
                 value="{{ old('name', $equipment->name ?? '') }}"
                 placeholder="Add Equipment Name"
                 required>
          <div class="invalid-feedback">
            Please provide a Equipment name.
          </div>
        </div>

        <!-- Submit Button -->
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-lg btn-success">
            <i class="bi bi-save2 me-2"></i> {{ isset($equipment) ? 'Update Equipment' : 'Save Equipment' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection




 
