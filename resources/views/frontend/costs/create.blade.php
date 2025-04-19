@extends('frontend.layouts.app')
@section('title', isset($cost) ? 'Edit Cost' : 'Add Cost')
@section('content')
<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
      <h5 class="mb-0">
        <i class="bi bi-plus-circle me-2"></i>
        {{ isset($cost) ? 'Edit' : 'Add' }} Cost
      </h5>
    </div>
    <div class="card-body">
      <form
        method="POST"
        action="{{ isset($cost) ? route('costs.update',$cost) : route('costs.store') }}"
        class="needs-validation row g-3"
        novalidate
      >
        @csrf
        @isset($cost) @method('PUT') @endisset

        {{-- Supplier --}}
        <div class="col-md-6">
          <label class="form-label">Supplier</label>
          <input type="text"
                 name="supplier"
                 value="{{ old('supplier',$cost->supplier??'') }}"
                 class="form-control @error('supplier') is-invalid @enderror"
                 required>
          @error('supplier')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Amount --}}
        <div class="col-md-6">
          <label class="form-label">Amount ($)</label>
          <input type="number" step="0.01"
                 name="amount"
                 value="{{ old('amount',$cost->amount??'') }}"
                 class="form-control @error('amount') is-invalid @enderror"
                 required>
          @error('amount')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Due Date --}}
        <div class="col-md-6">
          <label class="form-label">Due Date</label>
          <input type="date"
                 name="due_date"
                 value="{{ old('due_date',$cost->due_date??'') }}"
                 class="form-control @error('due_date') is-invalid @enderror"
                 required>
          @error('due_date')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Category --}}
        <div class="col-md-6">
          <label class="form-label">Category</label>
          <select name="category_id" id="category_id"
                  class="form-select @error('category_id') is-invalid @enderror"
                  required>
            <option value="">Choose…</option>
            @foreach($categories as $cat)
              <option
                value="{{ $cat->id }}"
                {{ old('category_id',$cost->category_id??'') == $cat->id ? 'selected':'' }}
              >
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
          @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Other Category (only if “Other” exists and is selected) --}}
        @php
          $otherCat = $categories->firstWhere('name','Other');
          // if your "other" category is named differently, adjust above
        @endphp
        <div class="col-12" id="otherCatBox" style="display:none">
          <label class="form-label">Other Category Name</label>
          <input type="text"
                 name="other_category"
                 value="{{ old('other_category',$cost->other_category??'') }}"
                 class="form-control">
        </div>

        {{-- Submit --}}
        <div class="col-12 text-end">
          <button class="btn btn-warning">
            <i class="bi bi-save me-1"></i>
            {{ isset($cost) ? 'Update Cost' : 'Save Cost' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  // 1) Determine the ID of your “Other” category (if it exists)
  const otherId = {{ $otherCat ? $otherCat->id : 'null' }};

  // 2) Grab the select & other‑box
  const catSelect   = document.getElementById('category_id'),
        otherBox    = document.getElementById('otherCatBox');

  // 3) Show/hide logic
  function toggleOther(){
    if(String(catSelect.value) === String(otherId)) {
      otherBox.style.display = 'block';
    } else {
      otherBox.style.display = 'none';
    }
  }
  catSelect.addEventListener('change', toggleOther);

  // 4) On page load, in case edit‑mode already selected “Other”
  toggleOther();

  // 5) Bootstrap form validation
  (function(){
    'use strict';
    document.querySelectorAll('.needs-validation').forEach(form=>{
      form.addEventListener('submit', function(e){
        if(!form.checkValidity()){
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      });
    });
  })();
});
</script>
@endsection
