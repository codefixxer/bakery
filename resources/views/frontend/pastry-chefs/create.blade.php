@extends('frontend.layouts.app')

@section('title', isset($pastryChef) ? 'Edit Pastry Chef' : 'Add Pastry Chef')

@section('content')
<div class="container py-5 px-md-5">
  <div class="card border-primary shadow-sm">
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-egg-fried fs-4 me-2" style="color: #e2ae76;"></i>
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">
        {{ isset($pastryChef) ? 'Edit Pastry Chef' : 'Add Pastry Chef' }}
      </h5>
    </div>
    <div class="card-body">
      <form 
        action="{{ isset($pastryChef) ? route('pastry-chefs.update', $pastryChef->id) : route('pastry-chefs.store') }}" 
        method="POST" 
        class="row g-3 needs-validation" 
        novalidate
      >
        @csrf
        @if(isset($pastryChef)) @method('PUT') @endif

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

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-gold-filled btn-lg">
            <i class="bi bi-save2 me-2"></i> {{ isset($pastryChef) ? 'Update Chef' : 'Save Chef' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

<style>
  .btn-gold-filled {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    border: none !important;
    font-weight: 500;
    padding: 10px 24px;
    border-radius: 12px;
    transition: background-color 0.2s ease;
  }

  .btn-gold-filled:hover {
    background-color: #d89d5c !important;
    color: white !important;
  }

  .btn-gold-filled i {
    color: inherit !important;
  }
</style>


@section('scripts')
<script>
  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })();
</script>
@endsection
