@extends('frontend.layouts.app')

@section('title', 'All Categories')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Categories</h4>
    <a href="{{ route('categories.create') }}" class="btn btn-success">
      <i class="bi bi-plus-circle me-1"></i> Add New Category
    </a>
  </div>

  

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table id="categoriesTable" class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Category Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $category->name }}</td>
              <td>
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>

                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i> Delete
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center text-muted">No categories found.</td>
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
  // If DataTables is available, initialize it
  if (window.$ && $.fn.DataTable) {
    $('#categoriesTable').DataTable({
      pageLength: 10,
      responsive: true,
      columnDefs: [
        { orderable: false, targets: 4 }
      ]
    });
  }
});
</script>
@endsection
