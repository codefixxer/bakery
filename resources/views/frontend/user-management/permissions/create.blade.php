@extends('frontend.layouts.app')

@section('title', $isEdit ? 'Edit Permission' : 'Add Permission')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Header -->
  <div class="page-header d-flex align-items-center mb-4" style="background-color: #041930; border-radius: 0.75rem; padding: 1rem 2rem;">
    <i class="bi bi-shield-lock-fill me-2 fs-3" style="color: #e2ae76;"></i>
    <h2 class="mb-0 fw-bold" style="color: #e2ae76;">
      {{ $isEdit ? 'Edit Permission' : 'Add Permission' }}
    </h2>
  </div>

  <!-- Form -->
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body">
      <form action="{{ $isEdit ? route('permissions.update', $permission) : route('permissions.store') }}"
            method="POST">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="mb-4">
          <label class="form-label fw-semibold text-dark">Permission Name</label>
          <input type="text" name="name"
                 class="form-control"
                 value="{{ old('name', $permission->name ?? '') }}"
                 required>
        </div>

        <button class="btn btn-gold-blue">
          <i class="bi bi-check-circle me-1"></i>
          {{ $isEdit ? 'Update' : 'Create' }}
        </button>
      </form>
    </div>
  </div>
</div>
@endsection


<style>
  .btn-gold-blue {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    border: 1px solid #e2ae76;
  }

  .btn-gold-blue:hover {
    background-color: #d89d5c !important;
    color: white !important;
  }

  .page-header i {
    color: #e2ae76;
  }

  .page-header h2 {
    color: #e2ae76;
  }
</style>

