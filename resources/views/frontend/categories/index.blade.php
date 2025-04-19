@extends('frontend.layouts.app')

@section('title', 'All Categories')

@section('content')




<div class="container py-5">
  <div class="card border-primary shadow-sm">
      <div class="card-header bg-primary text-white d-flex align-items-center">
          <i class="bi bi-tags fs-4 me-2"></i>
          <h5 class="mb-0">{{ isset($category) ? 'Edit Category' : 'Add Category' }}</h5>
      </div>
      <div class="card-body">
          <form
              action="{{ isset($category) ? route('cost_categories.update', $category->id) : route('cost_categories.store') }}"
              method="POST" class="needs-validation" novalidate>
              @csrf
              @if (isset($category))
                  @method('PUT')
              @endif

              <div class="mb-3">
                  <label for="name" class="form-label fw-semibold">Category Name</label>
                  <input type="text" name="name" id="name" class="form-control form-control-lg"
                      placeholder="e.g. Utilities, Rent, Packaging" value="{{ old('name', $category->name ?? '') }}"
                      required>
                  <div class="invalid-feedback">
                      Please enter a category name.
                  </div>
              </div>

              <div class="text-end">
                  <button type="submit" class="btn btn-lg btn-primary">
                      <i class="bi bi-save2 me-2"></i> {{ isset($category) ? 'Update' : 'Save Category' }}
                  </button>
              </div>
          </form>
      </div>
  </div>
</div>




<div class="container py-5">


  

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
                <a href="{{ route('cost_categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>

                <form action="{{ route('cost_categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
