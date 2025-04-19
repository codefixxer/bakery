@extends('frontend.layouts.app')

@section('title', 'Add Pastry Chef')


@section('content')
<div class="container py-5">
  <div class="card border-success shadow-sm">
    <div class="card-header bg-success text-white d-flex align-items-center">
      <i class="bi bi-box-seam fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($pastryChef) ? 'Edit Pastry Chef' : 'Add Pastry Chef' }}</h5>
    </div>
    <div class="card-body">
      <form 
        action="{{ isset($pastryChef) ? route('pastry-chefs.update', $pastryChef->id) : route('pastry-chefs.store') }}" 
        method="POST" 
        class="row g-3 needs-validation" 
        novalidate
      >
        @csrf
        @if(isset($pastryChef))
          @method('PUT')
        @endif

        <!-- Chef Name -->
        <div class="col-md-6">
          <label for="Name" class="form-label fw-semibold">Chef Name</label>
          <input type="text"
                 id="Name"
                 name="name"
                 class="form-control form-control-lg"
                 value="{{ old('name', $pastryChef->name ?? '') }}"
                 placeholder="Add Chef Name"
                 required>
          <div class="invalid-feedback">
            Please provide a Chef name.
          </div>
        </div>

        <!-- Email -->
        <div class="col-md-6">
          <label for="Email" class="form-label fw-semibold">Chef Email</label>
          <input type="email"
                 id="Email"
                 name="email"
                 class="form-control form-control-lg"
                 value="{{ old('email', $pastryChef->email ?? '') }}"
                 placeholder="Add Chef Email"
                 required>
          <div class="invalid-feedback">
            Please provide a Chef Email.
          </div>
        </div>

        <!-- Phone -->
        <div class="col-md-6">
          <label for="phone" class="form-label fw-semibold">Chef Phone Number</label>
          <input type="number"
                 id="phone"
                 name="phone"
                 class="form-control form-control-lg"
                 value="{{ old('phone', $pastryChef->phone ?? '') }}"
                 placeholder="Add Chef Phone Number"
                 required>
          <div class="invalid-feedback">
            Please provide a Chef Phone Number.
          </div>
        </div>

        <!-- Submit Button -->
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-lg btn-success">
            <i class="bi bi-save2 me-2"></i> {{ isset($pastryChef) ? 'Update Chef' : 'Save Chef' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection




 
