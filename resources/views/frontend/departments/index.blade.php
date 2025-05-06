@extends('frontend.layouts.app')

@section('title', 'All Departments')

@section('content')
<div class="container py-5">
  <!-- Add / Edit Department Form -->
  <div class="card mb-5 border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-building fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($department) ? 'Edit Department' : 'Add Department' }}</h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($department) ? route('departments.update', $department) : route('departments.store') }}"
        method="POST"
        class="needs-validation row g-3"
        novalidate
      >
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
            required
          >
          <div class="invalid-feedback">
            Please enter a department name.
          </div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-lg btn-primary">
            <i class="bi bi-save2 me-2"></i>{{ isset($department) ? 'Update' : 'Save Department' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Departments Table -->
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">All Departments</h5>
      <a href="{{ route('departments.create') }}" class="btn btn-light">
        <i class="bi bi-plus-circle me-1 text-primary"></i> New Department
      </a>
    </div>
    <div class="card-body table-responsive">
      <table
        id="departmentsTable"
        class="table table-striped table-hover table-bordered align-middle mb-0"
        data-page-length="10"
      >
        <thead class="table-primary">
          <tr>
            <th>Department Name</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($departments as $department)
            <tr>
              <td>{{ $department->name ?? '—' }}</td>
              <td class="text-center">
                <!-- View -->
                <a
                  href="{{ route('departments.show', $department) }}"
                  class="btn btn-sm btn-outline-info me-1"
                  title="View"
                >
                  <i class="bi bi-eye"></i>
                </a>
                <!-- Edit -->
                <a
                  href="{{ route('departments.edit', $department) }}"
                  class="btn btn-sm btn-outline-primary me-1"
                  title="Edit"
                >
                  <i class="bi bi-pencil"></i>
                </a>
                <!-- Delete -->
                <form
                  action="{{ route('departments.destroy', $department) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Delete this department?');"
                >
                  @csrf
                  @method('DELETE')
                  <button
                    type="submit"
                    class="btn btn-sm btn-outline-danger"
                    title="Delete"
                  >
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

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables safely
    if (window.$ && $.fn.DataTable) {
      $('#departmentsTable').DataTable({
        paging: true,
        ordering: true,
        responsive: true,
        pageLength: $('#departmentsTable').data('page-length'),
        columnDefs: [
          { orderable: false, targets: 1 } // Fix: Only 2 columns, so index 1 is the last column
        ]
      });
    }

    // Bootstrap validation logic
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', function (e) {
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
