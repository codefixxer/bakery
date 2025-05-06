@extends('frontend.layouts.app')

@section('title', 'All Cost Categories')

@section('content')
<div class="container py-5">

  <!-- Add / Edit Category Card -->
  <div class="card mb-5 border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-list fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($category) ? 'Edit Category' : 'Add Category' }}</h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($category) ? route('cost_categories.update', $category) : route('cost_categories.store') }}"
        method="POST"
        class="needs-validation row g-3"
        novalidate
      >
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="col-md-8">
          <label for="name" class="form-label fw-semibold">Category Name</label>
          <input
            type="text"
            id="name"
            name="name"
            class="form-control form-control-lg"
            placeholder="e.g. Utilities, Rent, Packaging"
            value="{{ old('name', $category->name ?? '') }}"
            required
          >
          <div class="invalid-feedback">Please enter a category name.</div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-lg btn-primary">
            <i class="bi bi-save2 me-2"></i>
            {{ isset($category) ? 'Update' : 'Save Category' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Categories Table -->
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Cost Categories</h5>
      <a href="{{ route('cost_categories.create') }}" class="btn btn-light">
        <i class="bi bi-plus-circle me-1 text-primary"></i> New Category
      </a>
    </div>
    <div class="card-body table-responsive">
      <table
        id="categoriesTable"
        class="table table-striped table-hover table-bordered align-middle mb-0"
        data-page-length="10"
      >
        <thead class="table-primary">
          <tr>
           
            <th>Category Name</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $cat)
            <tr>
              
              <td>{{ $cat->name }}</td>
              <td class="text-center">
                <a
                  href="{{ route('cost_categories.show', $cat) }}"
                  class="btn btn-sm btn-outline-info me-1"
                  title="View"
                >
                  <i class="bi bi-eye"></i>
                </a>
                <a
                  href="{{ route('cost_categories.edit', $cat) }}"
                  class="btn btn-sm btn-outline-primary me-1"
                  title="Edit"
                >
                  <i class="bi bi-pencil-square"></i>
                </a>
                <form
                  action="{{ route('cost_categories.destroy', $cat) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Delete this category?');"
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
    // Initialize DataTable
    if (window.$ && $.fn.DataTable) {
      $('#categoriesTable').DataTable({
        paging: true,
        ordering: true,
        responsive: true,
        pageLength: $('#categoriesTable').data('page-length'),
        columnDefs: [
          { orderable: false, targets: 1 } // Fix: only two columns, index 0 and 1
        ]
      });
    }

    // Bootstrap form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', function(e) {
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

