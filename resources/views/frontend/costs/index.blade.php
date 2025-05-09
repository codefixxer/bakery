@extends('frontend.layouts.app')

@section('title','All Costs')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Add / Edit Cost Card -->
  <div class="card mb-5 border-warning shadow-sm">
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-currency-dollar fs-4 me-2" style="color: #e2ae76;"></i>
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">{{ isset($cost) ? 'Edit Cost' : 'Add Cost' }}</h5>
    </div>
    <div class="card-body">
      <form method="POST"
            action="{{ isset($cost) ? route('costs.update', $cost) : route('costs.store') }}"
            class="row g-3 needs-validation"
            novalidate>
        @csrf
        @isset($cost) @method('PUT') @endisset

        <div class="col-md-6">
          <label for="cost_identifier" class="form-label fw-semibold">Cost Identifier <small class="text-muted">(optional)</small></label>
          <input type="text"
                id="cost_identifier"
                name="cost_identifier"
                class="form-control form-control-lg"
                value="{{ old('cost_identifier', $cost->cost_identifier ?? '') }}">
        </div>

        <div class="col-md-6">
          <label for="supplier" class="form-label fw-semibold">Supplier</label>
          <input type="text"
                id="supplier"
                name="supplier"
                class="form-control form-control-lg"
                value="{{ old('supplier', $cost->supplier ?? '') }}"
                required>
          <div class="invalid-feedback">Please enter a supplier.</div>
        </div>

        <div class="col-md-6">
          <label for="amount" class="form-label fw-semibold">Amount</label>
          <div class="input-group input-group-lg has-validation">
            <span class="input-group-text">$</span>
            <input type="number"
                  step="0.01"
                  id="amount"
                  name="amount"
                  class="form-control"
                  value="{{ old('amount', $cost->amount ?? '') }}"
                  required>
            <div class="invalid-feedback">Please enter a valid amount.</div>
          </div>
        </div>

        <div class="col-md-6">
          <label for="due_date" class="form-label fw-semibold">Due Date</label>
          <input type="date"
                id="due_date"
                name="due_date"
                class="form-control form-control-lg"
                value="{{ old('due_date', $cost->due_date ?? '') }}"
                required>
          <div class="invalid-feedback">Please pick a date.</div>
        </div>

        <div class="col-md-6">
          <label for="category_id" class="form-label fw-semibold">Category</label>
          <select id="category_id"
                  name="category_id"
                  class="form-select form-select-lg"
                  required>
            <option value="">Select…</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}"
                {{ old('category_id', $cost->category_id ?? '') == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
          <div class="invalid-feedback">Please select a category.</div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-gold-filled btn-lg">
            <i class="bi bi-save2 me-1"></i>
            {{ isset($cost) ? 'Update Cost' : 'Save Cost' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Filter Month -->
  <div class="row g-2 align-items-end mb-4">
    <div class="col-auto">
      <label for="filterMonth" class="form-label fw-semibold">Show month</label>
      <input type="month"
            id="filterMonth"
            class="form-control form-control-lg"
            value="{{ now()->format('Y-m') }}">
    </div>
  </div>

  <!-- Costs Table -->
  <div class="card border-warning shadow-sm">
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;"><i class="bi bi-table me-2"></i>All Costs</h5>
    </div>
    <div class="card-body table-responsive">
      <table id="costTable" class="table table-bordered table-striped table-hover align-middle text-center mb-0" data-page-length="10">
        <thead>
          <tr>
            <th>Identifier</th>
            <th>Supplier</th>
            <th class="text-end">Amount</th>
            <th>Due Date</th>
            <th>Category</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($costs as $item)
            <tr>
              <td>{{ $item->cost_identifier }}</td>
              <td>{{ $item->supplier }}</td>
              <td class="text-end">${{ number_format($item->amount,2) }}</td>
              <td>{{ \Carbon\Carbon::parse($item->due_date)->format('Y-m-d') }}</td>
              <td>{{ $item->category->name ?? '–' }}</td>
              <td class="text-center">
                <a href="{{ route('costs.show', $item) }}" class="btn btn-sm btn-deepblue me-1" title="View Cost"><i class="bi bi-eye"></i></a>
                <a href="{{ route('costs.edit', $item) }}" class="btn btn-sm btn-gold me-1" title="Edit Cost"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('costs.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this cost?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-red" title="Delete Cost"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection


<style>
  .btn-gold-filled {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    border: none !important;
    font-weight: 500;
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

  .btn-gold, .btn-deepblue, .btn-red {
    border: 1px solid;
    background-color: transparent !important;
    font-weight: 500;
  }

  .btn-gold {
    border-color: #e2ae76 !important;
    color: #e2ae76 !important;
  }
  .btn-gold:hover {
    background-color: #e2ae76 !important;
    color: white !important;
  }

  .btn-deepblue {
    border-color: #041930 !important;
    color: #041930 !important;
  }
  .btn-deepblue:hover {
    background-color: #041930 !important;
    color: white !important;
  }

  .btn-red {
    border-color: #ff0000 !important;
    color: red !important;
  }
  .btn-red:hover {
    background-color: #ff0000 !important;
    color: white !important;
  }

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
</style>


@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.$ && $.fn.DataTable) {
      var table = $('#costTable').DataTable({
        paging: true,
        ordering: true,
        responsive: true,
        pageLength: $('#costTable').data('page-length'),
        columnDefs: [{ orderable: false, targets: 5 }]
      });

      $.fn.dataTable.ext.search.push(function(settings, data) {
        var selected = $('#filterMonth').val();
        if (!selected) return true;
        var dueDate = data[3];
        return dueDate.substr(0,7) === selected;
      });

      $('#filterMonth').on('change', function() {
        table.draw();
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
y