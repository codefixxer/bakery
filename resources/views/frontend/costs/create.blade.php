{{-- resources/views/frontend/costs/create.blade.php --}}
@extends('frontend.layouts.app')
@section('title', isset($cost) ? 'Edit Cost' : 'Add Cost')

@section('content')
<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
      <h5 class="mb-0">{{ isset($cost) ? 'Edit Cost' : 'Add Cost' }}</h5>
    </div>
    <div class="card-body">
      <form method="POST"
            action="{{ isset($cost) ? route('costs.update',$cost) : route('costs.store') }}"
            class="row g-3 needs-validation"
            novalidate>
        @csrf
        @if(isset($cost)) @method('PUT') @endif

        {{-- Cost Identifier --}}
        <div class="col-md-6">
          <label for="cost_identifier" class="form-label">Cost Identifier <small class="text-muted">(optional)</small></label>
          <input type="text"
                 name="cost_identifier"
                 id="cost_identifier"
                 class="form-control"
                 placeholder="e.g. INV‑2025‑04‑001"
                 value="{{ old('cost_identifier',$cost->cost_identifier ?? '') }}">
        </div>

        {{-- Supplier --}}
        <div class="col-md-6">
          <label for="supplier" class="form-label">Supplier</label>
          <input type="text"
                 name="supplier"
                 id="supplier"
                 class="form-control"
                 placeholder="e.g. ABC Ltd."
                 value="{{ old('supplier',$cost->supplier ?? '') }}"
                 required>
          <div class="invalid-feedback">Please enter a supplier.</div>
        </div>

        {{-- Amount --}}
        <div class="col-md-6">
          <label for="amount" class="form-label">Amount</label>
          <div class="input-group has-validation">
            <span class="input-group-text">$</span>
            <input type="number" step="0.01"
                   name="amount"
                   id="amount"
                   class="form-control"
                   value="{{ old('amount',$cost->amount ?? '') }}"
                   required>
            <div class="invalid-feedback">Please enter a valid amount.</div>
          </div>
        </div>

        {{-- Due Date --}}
        <div class="col-md-6">
          <label for="due_date" class="form-label">Due Date</label>
          <input type="date"
                 name="due_date"
                 id="due_date"
                 class="form-control"
                 value="{{ old('due_date',$cost->due_date ?? '') }}"
                 required>
          <div class="invalid-feedback">Please pick a date.</div>
        </div>

        {{-- Category --}}
        <div class="col-md-6">
          <label for="category_id" class="form-label">Category</label>
          <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Select…</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}"
                {{ old('category_id',$cost->category_id??'')==$c->id?'selected':'' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
          <div class="invalid-feedback">Please select a category.</div>
        </div>

        <div class="col-12 text-end">
          <button class="btn btn-warning">
            <i class="bi bi-save2 me-1"></i>
            {{ isset($cost)?'Update':'Save' }} Cost
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
(() => {
  'use strict';
  document.querySelectorAll('.needs-validation').forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) {
        e.preventDefault(); e.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });
})();
</script>
@endsection
