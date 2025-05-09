@extends('frontend.layouts.app')
@section('title', isset($income) ? 'Edit Income' : 'Add Income')

@section('content')
<div class="container py-5 px-md-5">
  <div class="card shadow-sm border-primary">
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-currency-dollar fs-4 me-2" style="color: #e2ae76;"></i>
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">
        {{ isset($income) ? 'Edit Income' : 'Add Income' }}
      </h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($income) ? route('incomes.update', $income) : route('incomes.store') }}"
        method="POST"
        class="row g-3 needs-validation"
        novalidate>
        @csrf
        @if(isset($income)) @method('PUT') @endif

        <!-- Amount -->
        <div class="col-md-6">
          <label for="amount" class="form-label fw-semibold">Amount ($)</label>
          <div class="input-group input-group-lg has-validation">
            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
            <input
              type="number"
              step="0.01"
              name="amount"
              id="amount"
              class="form-control @error('amount') is-invalid @enderror"
              value="{{ old('amount', $income->amount ?? '') }}"
              required>
            <div class="invalid-feedback">{{ $errors->first('amount', 'Please enter a valid amount.') }}</div>
          </div>
        </div>

        <!-- Date -->
        <div class="col-md-6">
          <label for="date" class="form-label fw-semibold">Date</label>
          <input
            type="date"
            name="date"
            id="date"
            class="form-control form-control-lg @error('date') is-invalid @enderror"
            value="{{ old('date', isset($income) ? $income->date->format('Y-m-d') : '') }}"
            required>
          <div class="invalid-feedback">{{ $errors->first('date', 'Please pick a date.') }}</div>
        </div>

        <!-- Submit Button -->
        <div class="col-12 text-end">
          <button class="btn btn-gold-filled btn-lg">
            <i class="bi bi-save2 me-2"></i>
            {{ isset($income) ? 'Update Income' : 'Save Income' }}
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
    font-weight: 600;
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
