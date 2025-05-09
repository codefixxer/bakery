@extends('frontend.layouts.app')

@section('title', isset($category) ? 'Edit Category' : 'Add Category')

@section('content')
<div class="container py-5 px-md-5">
  <div class="card border-primary shadow-sm">
    <!-- Header -->
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-tags fs-4 me-2" style="color: #e2ae76;"></i>
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">
        {{ isset($category) ? 'Edit' : 'Add' }} Cost Category
      </h5>
    </div>

    <div class="card-body">
      <form
        action="{{ isset($category) ? route('cost_categories.update', $category->id) : route('cost_categories.store') }}"
        method="POST"
        class="needs-validation row g-3"
        novalidate
      >
        @csrf
        @if (isset($category)) @method('PUT') @endif

        <!-- Category Name -->
        <div class="col-md-8">
          <label for="name" class="form-label fw-semibold">Category Name</label>
          <input
            type="text"
            name="name"
            id="name"
            class="form-control form-control-lg"
            placeholder="e.g. Utilities, Rent, Packaging"
            value="{{ old('name', $category->name ?? '') }}"
            required
          >
          <div class="invalid-feedback">Please enter a category name.</div>
        </div>

        <!-- Submit Button -->
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-gold btn-lg">
            <i class="bi bi-save2 me-2"></i>
            {{ isset($category) ? 'Update' : 'Save Category' }}
          </button>
          <a href="{{ route('cost_categories.index') }}" class="btn btn-deepblue btn-lg ms-2">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


<style>
  .btn-gold {
    border: 1px solid #e2ae76 !important;
    color: #e2ae76 !important;
    background-color: transparent !important;
  }
  .btn-gold:hover {
    background-color: #e2ae76 !important;
    color: white !important;
  }

  .btn-deepblue {
    border: 1px solid #041930 !important;
    color: #041930 !important;
    background-color: transparent !important;
  }
  .btn-deepblue:hover {
    background-color: #041930 !important;
    color: white !important;
  }

  .btn-gold i,
  .btn-deepblue i {
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
