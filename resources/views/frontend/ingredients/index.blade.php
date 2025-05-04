@extends('frontend.layouts.app')

@section('title','Ingredients Showcase')

@section('content')
<div class="container py-5">
  <div class="card border-success shadow-sm">
    <div class="card-header bg-success text-white d-flex align-items-center">
      <i class="bi bi-box-seam fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($ingredient) ? 'Edit Ingredient' : 'Add Ingredient' }}</h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($ingredient) ? route('ingredients.update', $ingredient->id) : route('ingredients.store') }}"
        method="POST" class="row g-3 needs-validation" novalidate>
        @csrf
        @if (isset($ingredient))
          @method('PUT')
        @endif

        <div class="col-md-6">
          <label for="ingredientName" class="form-label fw-semibold">Ingredient Name</label>
          <input
            type="text"
            id="ingredientName"
            name="ingredient_name"
            class="form-control form-control-lg"
            placeholder="e.g. All-purpose Flour"
            value="{{ old('ingredient_name', $ingredient->ingredient_name ?? '') }}"
            required
          >
          <div class="invalid-feedback">
            Please provide an ingredient name.
          </div>
        </div>

        <div class="col-md-6">
          <label for="pricePerKg" class="form-label fw-semibold">Price per kg</label>
          <div class="input-group input-group-lg has-validation">
            <span class="input-group-text">€</span>
            <input
              type="number"
              id="pricePerKg"
              name="price_per_kg"
              class="form-control"
              step="0.01"
              placeholder="0.00"
              value="{{ old('price_per_kg', $ingredient->price_per_kg ?? '') }}"
              required
            >
            <span class="input-group-text">/kg</span>
            <div class="invalid-feedback">
              Please provide a valid price.
            </div>
          </div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-lg btn-success">
            <i class="bi bi-save2 me-2"></i>
            {{ isset($ingredient) ? 'Update Ingredient' : 'Save Ingredient' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="container py-5">
  <div class="card basic-data-table mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">Ingredients Showcase</h5>
    </div>
    <div class="table-responsive">
      <table
        id="ingredientsTable"
        class="table bordered-table mb-0"
        data-page-length="10"
      >
        <thead>
          <tr>
            <th scope="col">Created By</th>
            <th scope="col">Name</th>
            <th scope="col">Price / kg</th>
            <th scope="col">Last Updated</th>
            <th scope="col" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($ingredients as $ingredient)
            <tr>
              <td>{{ $ingredient->user->name ?? '—' }}</td>
              <td>{{ $ingredient->ingredient_name }}</td>
              <td>€{{ number_format($ingredient->price_per_kg, 2) }}</td>
              <td>{{ $ingredient->updated_at->format('Y-m-d H:i') }}</td>
              <td class="text-center">
                <!-- Edit -->
                <a
                  href="{{ route('ingredients.edit', $ingredient) }}"
                  class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                  title="Edit"
                >
                  <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>

                <!-- View -->
                <a
                  href="{{ route('ingredients.show', $ingredient) }}"
                  class="w-32-px h-32-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                  title="View"
                >
                  <iconify-icon icon="lucide:eye"></iconify-icon>
                </a>

                <!-- Delete -->
                <form
                  action="{{ route('ingredients.destroy', $ingredient) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Delete this ingredient?');"
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
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Bootstrap validation
  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
      form.addEventListener('submit', e => {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      });
    });
  })();

  document.addEventListener('DOMContentLoaded', function() {
    if (window.$ && $.fn.DataTable) {
      $('#ingredientsTable').DataTable({
        pageLength: $('#ingredientsTable').data('page-length'),
        responsive: true,
        scrollX: true,
        autoWidth: false,
        columnDefs: [
          // disable ordering on the 4th column (Actions), which is index 3
          { orderable: false, targets: 3 }
        ]
      });
    }
  });
</script>
@endsection
