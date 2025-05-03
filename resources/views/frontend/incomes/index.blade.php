{{-- resources/views/frontend/incomes/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','All Incomes')

@section('content')
<div class="container py-5">

  <!-- Add / Edit Income Card -->
  <div class="card mb-5 border-success shadow-sm">
    <div class="card-header bg-success text-white d-flex align-items-center">
      <i class="bi bi-currency-dollar fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($income) ? 'Edit' : 'Add' }} Income</h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($income) ? route('incomes.update', $income) : route('incomes.store') }}"
        method="POST"
        class="row g-3 needs-validation"
        novalidate
      >
        @csrf
        @if(isset($income)) @method('PUT') @endif

        <div class="col-md-6">
          <label for="amount" class="form-label fw-semibold">Amount ($)</label>
          <div class="input-group input-group-lg has-validation">
            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
            <input
              type="number"
              step="0.01"
              name="amount"
              id="amount"
              class="form-control"
              value="{{ old('amount', $income->amount ?? '') }}"
              required
            >
            <div class="invalid-feedback">{{ $errors->first('amount', 'Please enter a valid amount.') }}</div>
          </div>
        </div>

        <div class="col-md-6">
          <label for="date" class="form-label fw-semibold">Date</label>
          <input
            type="date"
            name="date"
            id="date"
            class="form-control"
            value="{{ old('date', isset($income) ? $income->date->format('Y-m-d') : '') }}"
            required
          >
          <div class="invalid-feedback">{{ $errors->first('date', 'Please pick a date.') }}</div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-save2 me-1"></i>
            {{ isset($income) ? 'Update' : 'Save' }} Income
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Recorded Incomes Table Card -->
  <div class="card border-success shadow-sm">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Recorded Incomes</h5>
      
    </div>
    <div class="card-body table-responsive">
      <table
        id="incomesTable"
        class="table table-striped table-hover table-bordered align-middle mb-0"
        data-page-length="10"
      >
        <thead class="table-success">
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
              <td class="text-end">${{ number_format($inc->amount,2) }}</td>
              <td class="text-center">
                <a
                  href="{{ route('incomes.show', $inc) }}"
                  class="btn btn-sm btn-outline-info me-1"
                  title="View Income"
                >
                  <i class="bi bi-eye"></i>
                </a>
                <a
                  href="{{ route('incomes.edit', $inc) }}"
                  class="btn btn-sm btn-outline-primary me-1"
                  title="Edit Income"
                >
                  <i class="bi bi-pencil"></i>
                </a>
                <form
                  action="{{ route('incomes.destroy', $inc) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Delete this income?');"
                >
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" title="Delete Income">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center text-muted">No incomes recorded.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-3">
        {{ $incomes->links() }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // DataTable initialization
  if (window.$ && $.fn.DataTable) {
    $('#incomesTable').DataTable({
      paging: true,
      ordering: true,
      responsive: true,
      pageLength: $('#incomesTable').data('page-length'),
      columnDefs: [{ orderable: false, targets: 2 }]
    });
  }

  // Bootstrap form validation
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
});
</script>
@endsection
