  {{-- resources/views/frontend/costs/index.blade.php --}}
  @extends('frontend.layouts.app')

  @section('title','All Costs')

  @section('content')
  <div class="container py-5">

    <!-- Add / Edit Cost Card -->
    <div class="card mb-5 border-warning shadow-sm">
      <div class="card-header bg-warning text-dark d-flex align-items-center">
        <i class="bi bi-currency-dollar fs-4 me-2"></i>
        <h5 class="mb-0">{{ isset($cost) ? 'Edit Cost' : 'Add Cost' }}</h5>
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
            <button type="submit" class="btn btn-warning btn-lg">
              <i class="bi bi-save2 me-1"></i>
              {{ isset($cost) ? 'Update Cost' : 'Save Cost' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Filter Month (client-side only) -->
    <div class="row g-2 align-items-end mb-4">
      <div class="col-auto">
        <label for="filterMonth" class="form-label fw-semibold">Show month</label>
        <input type="month"
              id="filterMonth"
              class="form-control form-control-lg"
              value="{{ now()->format('Y-m') }}">
      </div>
    </div>

    <!-- Costs Table Card -->
    <div class="card border-warning shadow-sm">
      <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-table me-2"></i>All Costs</h5>
      </div>
      <div class="card-body table-responsive">
        <table id="costTable"
              class="table table-striped table-hover table-bordered align-middle mb-0"
              data-page-length="10"
              style="width:100%">
          <thead class="table-warning">
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
                  <a href="{{ route('costs.show', $item) }}" class="btn btn-sm btn-outline-info me-1" title="View Cost"><i class="bi bi-eye"></i></a>
                  <a href="{{ route('costs.edit', $item) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit Cost"><i class="bi bi-pencil"></i></a>
                  <form action="{{ route('costs.destroy', $item) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Delete this cost?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Delete Cost"><i class="bi bi-trash"></i></button>
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

  @section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (window.$ && $.fn.DataTable) {
        // Initialize DataTable
        var table = $('#costTable').DataTable({
          paging:     true,
          ordering:   true,
          responsive: true,
          pageLength: $('#costTable').data('page-length'),
          columnDefs: [{ orderable: false, targets: 5 }]
        });

        // Custom filter: month picker
        $.fn.dataTable.ext.search.push(function(settings, data) {
          var selected = $('#filterMonth').val();
          if (!selected) return true;
          var dueDate = data[3]; // "YYYY-MM-DD"
          return dueDate.substr(0,7) === selected;
        });

        // Redraw table on month change
        $('#filterMonth').on('change', function() {
          table.draw();
        });
      }

      // Bootstrap validation
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
