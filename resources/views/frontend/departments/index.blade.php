@extends('frontend.layouts.app')

@section('title', 'All Departments')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Add / Edit Department Form -->
  <div class="card mb-5 border-primary shadow-sm">
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-building fs-4 me-2" style="color: #e2ae76;"></i>
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">
        {{ isset($department) ? 'Edit Department' : 'Add Department' }}
      </h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($department) ? route('departments.update', $department) : route('departments.store') }}"
        method="POST"
        class="needs-validation row g-3"
        novalidate>
        @csrf
        @if(isset($department)) @method('PUT') @endif

        <div class="col-md-8">
          <label for="name" class="form-label fw-semibold">Department Name</label>
          <input
            type="text"
            name="name"
            id="name"
            class="form-control form-control-lg"
            placeholder="e.g. Production, Logistics"
            value="{{ old('name', $department->name ?? '') }}"
            required>
          <div class="invalid-feedback">Please enter a department name.</div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-gold-filled btn-lg">
            <i class="bi bi-save2 me-2"></i>
            {{ isset($department) ? 'Update Department' : 'Save Department' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Departments Table -->
  <div class="card border-primary shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #041930;">
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">
        <i class="bi bi-building me-2"></i> All Departments
      </h5>
      <a href="{{ route('departments.create') }}" class="btn btn-gold btn-sm">
        <i class="bi bi-plus-circle me-1"></i> New Department
      </a>
    </div>
    <div class="card-body table-responsive">
      <table
        id="departmentsTable"
        class="table table-bordered table-striped table-hover align-middle text-center mb-0"
        data-page-length="10">
        <thead>
          <tr>
            <th class="text-center">Department Name</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($departments as $department)
            <tr>
              <td>{{ $department->name ?? '—' }}</td>
              <td>
                <a href="{{ route('departments.show', $department) }}" class="btn btn-sm btn-deepblue me-1" title="View">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-gold me-1" title="Edit">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <form
                  action="{{ route('departments.destroy', $department) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Delete this department?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-red" title="Delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="2" class="text-center text-muted">No departments found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection


<style>
  .btn-gold {
    border: 1px solid #e2ae76 !important;
    color: #e2ae76 !important;
    background: transparent !important;
    transition: all 0.2s;
  }
  .btn-gold:hover {
    background: #e2ae76 !important;
    color: #fff !important;
  }

  .btn-deepblue {
    border: 1px solid #041930 !important;
    color: #041930 !important;
    background: transparent !important;
    transition: all 0.2s;
  }
  .btn-deepblue:hover {
    background: #041930 !important;
    color: #fff !important;
  }

  .btn-red {
    border: 1px solid #ff0000 !important;
    color: red !important;
    background: transparent !important;
    transition: all 0.2s;
  }
  .btn-red:hover {
    background: #ff0000 !important;
    color: #fff !important;
  }

  .btn-gold-filled {
    background: #e2ae76 !important;
    color: #041930 !important;
    border: none !important;
    font-weight: 500;
    padding: 10px 24px;
    border-radius: 12px;
    transition: background 0.2s;
  }
  .btn-gold-filled:hover {
    background: #d89d5c !important;
    color: #fff !important;
  }

  table thead th {
    background: #e2ae76 !important;
    color: #041930 !important;
  }
  table td {
    vertical-align: middle !important;
  }
</style>


@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.$ && $.fn.DataTable) {
      // disable DataTables alert popups
      $.fn.dataTable.ext.errMode = 'none';

      $('#departmentsTable').DataTable({
        paging:     true,
        ordering:   true,
        responsive: true,
        pageLength: $('#departmentsTable').data('page-length') || 10,
        columnDefs: [
          { orderable: false, targets: -1 } // only Actions column
        ]
      });
    }

    // Bootstrap client-side validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        this.classList.add('was-validated');
      }, false);
    });
  });
</script>
@endsection
