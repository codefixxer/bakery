@extends('frontend.layouts.app')

@section('title','Ingredients Showcase')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Add/Edit Ingredient Form -->
  <div class="card border-primary shadow-sm mb-5">
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-box-seam fs-4 me-2" style="color: #e2ae76;"></i>
      <h5 class="mb-0" style="color: #e2ae76;">
        {{ isset($ingredient) ? 'Edit Ingredient' : 'Add Ingredient' }}
      </h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($ingredient) ? route('ingredients.update', $ingredient) : route('ingredients.store') }}"
        method="POST"
        class="row g-3 needs-validation"
        novalidate
      >
        @csrf
        @if (isset($ingredient)) @method('PUT') @endif

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
          <div class="invalid-feedback">Please provide an ingredient name.</div>
        </div>

        <div class="col-md-6">
          <label for="pricePerKg" class="form-label fw-semibold">Price per kg</label>
          <div class="input-group input-group-lg has-validation w-100">
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
            <div class="invalid-feedback">Please provide a valid price.</div>
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

  <!-- Ingredients Table -->
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Ingredients Showcase</h5>
    </div>
    <div class="card-body px-4">
      <div class="table-responsive p-3">
        <table
          id="ingredientsTable"
          class="table table-bordered table-striped table-hover align-middle mb-0"
          data-page-length="10"
        >
          <thead class="table-primary">
            <tr>
                      {{-- New column --}}
              <th>Name</th>
              <th>Price / kg</th>
              <th>Last Updated</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($ingredients as $ingredient)
              <tr>
                {{-- Created By --}}
               
                <td>{{ $ingredient->ingredient_name }}</td>
                <td>€{{ number_format($ingredient->price_per_kg, 2) }}</td>
                <td>{{ $ingredient->updated_at->format('Y-m-d H:i') }}</td>
                <td class="text-center">
                  <!-- Edit -->
                  <a
                    href="{{ route('ingredients.edit', $ingredient) }}"
                    class="btn btn-sm btn-outline-success me-1"
                    title="Edit"
                  ><i class="bi bi-pencil"></i></a>
                  <!-- View -->
                  <a
                    href="{{ route('ingredients.show', $ingredient) }}"
                    class="btn btn-sm btn-outline-primary me-1"
                    title="View"
                  ><i class="bi bi-eye"></i></a>
                  <!-- Delete -->
                  <form
                    action="{{ route('ingredients.destroy', $ingredient) }}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Delete this ingredient?');"
                  >
                    @csrf @method('DELETE')
                    <button
                      type="submit"
                      class="btn btn-sm btn-outline-danger"
                      title="Delete"
                    ><i class="bi bi-trash"></i></button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted">No ingredients found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Bootstrap validation
  (function(){
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

  // DataTable
 
</script>
@endsection
