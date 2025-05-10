{{-- resources/views/frontend/incomes/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','All Incomes')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Add / Edit Income Card -->
  <div class="card mb-5 border-success shadow-sm">
    <div class="card-header d-flex align-items-center" style="background-color: #041930; color: #e2ae76;">
      <i class="bi bi-currency-dollar fs-4 me-2"></i>
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">
        {{ isset($income) ? 'Edit' : 'Add' }} Income
      </h5>
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
            <div class="invalid-feedback">
              {{ $errors->first('amount', 'Please enter a valid amount.') }}
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <label for="date" class="form-label fw-semibold">Date</label>
          <input
            type="date"
            name="date"
            id="date"
            class="form-control form-control-lg"
            value="{{ old('date', isset($income) ? $income->date->format('Y-m-d') : '') }}"
            required
          >
          <div class="invalid-feedback">
            {{ $errors->first('date', 'Please pick a date.') }}
          </div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-gold-save btn-lg">
            <i class="bi bi-save2 me-1"></i>
            {{ isset($income) ? 'Update Income' : 'Save Income' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Recorded Incomes Table Card -->
  <div class="card border-success shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #041930; color: #e2ae76;">
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">
        <i class="bi bi-list-ul me-2"></i>Recorded Incomes
      </h5>
    </div>
    <div class="card-body table-responsive">
      <table
        id="incomesTable"
        class="table table-bordered table-striped table-hover align-middle text-center mb-0"
        data-page-length="10"
      >
        <thead>
          <tr>
            <th>Date</th>
            <th>Amount ($)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($incomes as $inc)
            <tr>
              <td>{{ $inc->date->format('Y-m-d') }}</td>
              <td>${{ number_format($inc->amount,2) }}</td>
              <td>
                <a href="{{ route('incomes.show', $inc) }}" class="btn btn-sm btn-deepblue me-1" title="View Income">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('incomes.edit', $inc) }}" class="btn btn-sm btn-gold me-1" title="Edit Income">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('incomes.destroy', $inc) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this income?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-red" title="Delete Income">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td></td>
              <td></td>
              <td class="text-muted">No incomes recorded.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
      <div class="mt-3">
        {{ $incomes->links() }}
      </div>
    </div>
  </div>
</div>
@endsection

<style>
  table th {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    text-align: center;
    vertical-align: middle;
  }
  table td {
    text-align: center;
    vertical-align: middle;
  }
  .btn-gold {
    border: 1px solid #e2ae76 !important;
    color: #041930 !important;
    background-color: transparent !important;
  }
  .btn-gold:hover {
    background-color: #e2ae76 !important;
    color: white !important;
  }
  .btn-gold-save {
    border: 1px solid #e2ae76 !important;
    color: #041930 !important;
    background-color: #e2ae76 !important;
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
  .btn-red {
    border: 1px solid red !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: red !important;
    color: white !important;
  }
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  if (window.$ && $.fn.DataTable) {
    $('#incomesTable').DataTable({
      paging: true,
      ordering: true,
      responsive: true,
      pageLength: $('#incomesTable').data('page-length'),
      columnDefs: [{ orderable: false, targets: -1 }]
    });
  }

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
