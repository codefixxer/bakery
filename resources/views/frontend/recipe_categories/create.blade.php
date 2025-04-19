@extends('frontend.layouts.app')

@section('title','Recipe‑Category Manager')

@section('content')
{{-- ─────────────────── Add / Edit form ─────────────────── --}}
<div class="container py-5">
  <div class="card border-primary shadow-sm">
      <div class="card-header bg-primary text-white d-flex align-items-center">
          <i class="bi bi-tags fs-4 me-2"></i>
          <h5 class="mb-0">{{ isset($category) ? 'Edit Recipe Category' : 'Add Recipe Category' }}</h5>
      </div>
      <div class="card-body">
          <form
              action="{{ isset($category)
                         ? route('recipe-categories.update', $category->id)
                         : route('recipe-categories.store') }}"
              method="POST"
              class="row g-3 needs-validation"
              novalidate>
              @csrf
              @if(isset($category)) @method('PUT') @endif

              <div class="col-md-8">
                  <label for="categoryName" class="form-label fw-semibold">Category Name</label>
                  <input  type="text"
                          id="categoryName"
                          name="name"
                          class="form-control form-control-lg"
                          placeholder="e.g. Dessert"
                          value="{{ old('name', $category->name ?? '') }}"
                          required>
                  <div class="invalid-feedback">
                      Please provide a category name.
                  </div>
              </div>

              <div class="col-12 text-end">
                  <button type="submit" class="btn btn-lg btn-primary">
                      <i class="bi bi-save2 me-2"></i>
                      {{ isset($category) ? 'Update Category' : 'Save Category' }}
                  </button>
              </div>
          </form>
      </div>
  </div>
</div>



@endsection

@section('scripts')
<script>
/* ---------- Bootstrap validation ---------- */
(()=>{ 'use strict';
const forms=document.querySelectorAll('.needs-validation');
Array.from(forms).forEach(form=>{
  form.addEventListener('submit',e=>{
    if(!form.checkValidity()){e.preventDefault();e.stopPropagation();}
    form.classList.add('was-validated');
  },false);
})();})();
</script>
@endsection
