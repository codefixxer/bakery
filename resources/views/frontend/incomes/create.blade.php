@extends('frontend.layouts.app')
@section('title', isset($income) ? 'Edit Income' : 'Add Income')

@section('content')
<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-header bg-success text-white">
      <h5 class="mb-0">{{ isset($income) ? 'Edit' : 'Add' }} Income</h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($income) ? route('incomes.update',$income) : route('incomes.store') }}"
        method="POST"
        class="row g-3 needs-validation" novalidate
      >
        @csrf
        @if(isset($income)) @method('PUT') @endif

        <div class="col-md-6">
          <label for="amount" class="form-label">Amount ($)</label>
          <div class="input-group has-validation">
            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
            <input
              type="number"
              step="0.01"
              name="amount"
              id="amount"
              class="form-control @error('amount') is-invalid @enderror"
              value="{{ old('amount', $income->amount ?? '') }}"
              required
            >
            <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
          </div>
        </div>

        <div class="col-md-6">
          <label for="date" class="form-label">Date</label>
          <input
            type="date"
            name="date"
            id="date"
            class="form-control @error('date') is-invalid @enderror"
            value="{{ old('date', isset($income) ? $income->date->format('Y-m-d') : '') }}"
            required
          >
          <div class="invalid-feedback">{{ $errors->first('date') }}</div>
        </div>

        <div class="col-12 text-end">
          <button class="btn btn-success">
            <i class="bi bi-save2 me-1"></i>
            {{ isset($income) ? 'Update' : 'Save' }} Income
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
// Bootstrap form validation
(() => {
  'use strict';
  document.querySelectorAll('.needs-validation').forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });
})();
</script>
@endsection
