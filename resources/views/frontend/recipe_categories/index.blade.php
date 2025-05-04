{{-- resources/views/frontend/recipe-categories/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Recipe-Category Manager')

@section('content')
{{-- ─────────────────── Add / Edit form ─────────────────── --}}
<div class="container py-5">
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-tags fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($category) ? 'Edit Recipe Category' : 'Add Recipe Category' }}</h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($category)
                   ? route('recipe-categories.update', $category->id)
                   : route('recipe-categories.store') }}"
        method="POST"
        class="row g-3 needs-validation"
        novalidate>
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="col-md-8">
          <label for="categoryName" class="form-label fw-semibold">Category Name</label>
          <input
            type="text"
            id="categoryName"
            name="name"
            class="form-control form-control-lg"
            placeholder="e.g. Dessert"
            value="{{ old('name', $category->name ?? '') }}"
            required>
          <div class="invalid-feedback">
            Please provide a category name.
          </div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-lg btn-primary">
            <i class="bi bi-save2 me-2"></i>
            {{ isset($category) ? 'Update Category' : 'Save Category' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ─────────────────── Categories Table ─────────────────── --}}
<div class="container py-5">
  <div class="card mb-4 shadow-sm border-primary">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        <i class="bi bi-tags fs-4 me-2"></i>
        Recipe-Category List
      </h5>
      <a href="{{ route('recipe-categories.create') }}" class="btn btn-light">
        <i class="bi bi-plus-circle me-1 text-primary"></i> New Category
      </a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table
          id="categoryTable"
          class="table table-striped table-hover table-bordered align-middle mb-0"
          data-page-length="10"
        >
          <thead class="table-primary">
            <tr>
              <th>Created By</th>
              <th>Name</th>
              <th>Last Updated</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $cat)
              <tr>
                <td>
                  <span class="badge bg-light text-dark">
                    @if(optional($cat->user)->hasRole('super'))
                      Default
                    @else
                      {{ $cat->user->name ?? '—' }}
                    @endif
                  </span>
                </td>
                
                <td>{{ $cat->name }}</td>
                <td>{{ $cat->updated_at->format('Y-m-d H:i') }}</td>
                <td class="text-center">
                  <a
                    href="{{ route('recipe-categories.edit', $cat) }}"
                    class="btn btn-sm btn-outline-primary me-2"
                    title="Edit"
                  >
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a
                    href="{{ route('recipe-categories.show', $cat) }}"
                    class="btn btn-sm btn-outline-info me-2"
                    title="View"
                  >
                    <i class="bi bi-eye"></i>
                  </a>
                  <form
                    action="{{ route('recipe-categories.destroy', $cat) }}"
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
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.$ && $.fn.DataTable) {
      $('#categoryTable').DataTable({
        paging: true,
        ordering: true,
        responsive: true,
        pageLength: $('#categoryTable').data('page-length'),
        order: [[0, 'asc']],
        columnDefs: [
          { orderable: false, targets: 2 }
        ]
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
