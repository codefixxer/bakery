{{-- resources/views/departments/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Departments')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Departments</h4>
    <a href="{{ route('departments.create') }}" class="btn btn-success">
      <i class="bi bi-plus-circle me-1"></i> Add New Department
    </a>
  </div>

  <div class="card basic-data-table mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">All Departments</h5>
    </div>
    <div class="card-body">
      <table
        id="departmentsTable"
        class="table bordered-table mb-0"
        data-page-length="10"
      >
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Department Name</th>
            <th scope="col" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($departments as $department)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $department->name }}</td>
            <td class="text-center">
              <a
                href="{{ route('departments.edit', $department->id) }}"
                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                title="Edit"
              >
                <iconify-icon icon="lucide:edit"></iconify-icon>
              </a>
              <form
                action="{{ route('departments.destroy', $department->id) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Are you sure you want to delete this department?');"
              >
                @csrf
                @method('DELETE')
                <button
                  type="submit"
                  class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                  title="Delete"
                >
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="3" class="text-center text-muted">No departments found.</td>
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
  if (window.$ && $.fn.DataTable) {
    $('#departmentsTable').DataTable({
      pageLength: $('#departmentsTable').data('page-length'),
      responsive: true,
      scrollX: true,
   autoWidth: false,
      columnDefs: [
        { orderable: false, targets: 2 }
      ]
    });
  }
});
</script>
@endsection
