@extends('frontend.layouts.app')

@section('title', isset($department) ? 'Edit Department' : 'Add Department')

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-building fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($department) ? 'Edit Department' : 'Add Department' }}</h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}"
        method="POST"
        class="needs-validation"
        novalidate
      >
        @csrf
        @if(isset($department))
          @method('PUT')
        @endif

        <div class="mb-3">
          <label for="name" class="form-label fw-semibold">Department Name</label>
          <input
            type="text"
            name="name"
            id="name"
            class="form-control form-control-lg"
            placeholder="e.g. Production, Logistics"
            value="{{ old('name', $department->name ?? '') }}"
            required
          >
          <div class="invalid-feedback">
            Please enter a department name.
          </div>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-lg btn-primary">
            <i class="bi bi-save2 me-2"></i> {{ isset($department) ? 'Update' : 'Save Department' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
// Bootstrap validation
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>
@endsection
