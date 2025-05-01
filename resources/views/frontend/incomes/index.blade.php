@extends('frontend.layouts.app')
@section('title','All Incomes')

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




<div class="container py-5">
  <div class="d-flex justify-content-between mb-3">
    <h3>Recorded Incomes</h3>
    {{-- <a href="{{ route('incomes.create') }}" class="btn btn-success">
      <i class="bi bi-plus-lg me-1"></i> Add Income
    </a> --}}
  </div>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>Date</th>
        <th class="text-end">Amount ($)</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($incomes as $inc)
        <tr>
          <td>{{ $inc->date->format('Y-m-d') }}</td>
          <td class="text-end">{{ number_format($inc->amount,2) }}</td>
          <td class="text-center">
            <a href="{{ route('incomes.edit',$inc) }}" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('incomes.destroy',$inc) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Delete this income?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="3" class="text-center">No incomes recorded.</td></tr>
      @endforelse
    </tbody>
  </table>

  {{ $incomes->links() }}
</div>
@endsection
