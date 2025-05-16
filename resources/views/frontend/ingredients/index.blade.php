{{-- resources/views/frontend/ingredients/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Ingredients Showcase')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Add/Edit Ingredient Form -->
  <div class="card border-primary shadow-sm mb-5">
  <div class="card-header d-flex align-items-center"
     style="
       background-color: #041930;
       padding: .5rem;
       border-top-left-radius: .5rem;
       border-top-right-radius: .5rem;
     ">
  <!-- SVG Ingredient Icon with gold color -->
  <svg
    class="me-2"
    viewBox="0 0 512 512"
    xmlns="http://www.w3.org/2000/svg"
    style="
      width: 1.6vw;
      height: 1.6vw;
      color: #e2ae76;
      fill: currentColor;
    ">
    <path d="M479.605,91.769c-23.376,23.376-66.058,33.092-79.268,19.882
      c-13.21-13.21-3.494-55.892,19.883-79.268s85.999-26.614,85.999-26.614S502.982,68.393,479.605,91.769z"/>
    <g>
      <path d="M506.218,5.785L400.345,111.658c13.218,13.2,55.888,3.483,79.26-19.889
        C502.864,68.511,506.186,6.411,506.218,5.785z"/>
      <path d="M432.367,200.156c-33.059,0-70.11-23.311-70.11-41.992s37.052-41.992,70.11-41.992
        s79.629,41.992,79.629,41.992S465.426,200.156,432.367,200.156z"/>
    </g>
    <path d="M311.84,79.629c0,33.059,23.311,70.11,41.992,70.11s41.992-37.052,41.992-70.11S353.832,0,353.832,0
      S311.84,46.571,311.84,79.629z"/>
    <path d="M367.516,265.006c-33.059,0-70.11-23.311-70.11-41.992s37.052-41.992,70.11-41.992
      s79.629,41.992,79.629,41.992S400.575,265.006,367.516,265.006z"/>
    <path d="M246.99,144.48c0,33.059,23.311,70.11,41.992,70.11c18.681,0,41.992-37.052,41.992-70.11
      S288.982,64.85,288.982,64.85S246.99,111.421,246.99,144.48z"/>
    <path d="M302.666,329.857c-33.059,0-70.11-23.311-70.11-41.992c0-18.681,37.052-41.992,70.11-41.992
      s79.629,41.992,79.629,41.992S335.726,329.857,302.666,329.857z"/>
    <path d="M182.14,209.33c0,33.059,23.311,70.11,41.992,70.11s41.992-37.052,41.992-70.11
      s-41.992-79.629-41.992-79.629S182.14,176.27,182.14,209.33z"/>
    <path d="M237.025,395.498c-33.059,0-70.11-23.311-70.11-41.992c0-18.681,37.052-41.992,70.11-41.992
      s79.629,41.992,79.629,41.992S270.085,395.498,237.025,395.498z"/>
    <path d="M116.498,274.97c0,33.059,23.31,70.11,41.992,70.11s41.992-37.052,41.992-70.11
      s-41.992-79.629-41.992-79.629S116.498,241.912,116.498,274.97z"/>
    <path d="M170.438,462.084c-33.059,0-70.11-23.311-70.11-41.992c0-18.681,37.052-41.992,70.11-41.992
      s79.629,41.992,79.629,41.992S203.497,462.084,170.438,462.084z"/>
    <path d="M49.912,341.558c0,33.059,23.31,70.11,41.992,70.11s41.992-37.052,41.992-70.11
      s-41.992-79.629-41.992-79.629S49.912,308.499,49.912,341.558z"/>
    <path d="M4.917,507.087c-6.552-6.552-6.552-17.174,0-23.725L404.75,83.527c6.552-6.552,17.174-6.552,23.725,0
      c6.552,6.552,6.552,17.174,0,23.725L28.643,507.087C22.091,513.637,11.468,513.637,4.917,507.087z"/>
  </svg>

  <h5 class="mb-0 fw-bold" style="color: #e2ae76; font-size: 1.6vw;">
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
            <div class="invalid-feedback">Please provide a valid price.</div>
          </div>
        </div>

        <div class="col-12 text-end">
          <button type="submit"
                  class="btn btn-lg"
                  style="background-color: #e2ae76; color: #041930; border-color: #e2ae76;">
            <i class="bi bi-save2 me-2"></i>
            {{ isset($ingredient) ? 'Update Ingredient' : 'Save Ingredient' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Ingredients Table -->
  <div class="card border-primary shadow-sm">
    <div class="card-header" style="background-color: #041930;">
      <h5 class="mb-0" style="color: #e2ae76;">Ingredients Showcase</h5>
    </div>
    <div class="card-body px-4">
      <div class="table-responsive p-3">
        <table
          id="ingredientsTable"
          class="table table-bordered table-striped table-hover align-middle mb-0 text-center"
          data-page-length="10"
        >
          <thead>
            <tr>
              <th>Name</th>
              <th>Price / kg</th>
              <th>Last Updated</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($ingredients as $ing)
              <tr>
                <td>{{ $ing->ingredient_name }}</td>
                <td>€{{ number_format($ing->price_per_kg, 2) }}</td>
                <td>{{ $ing->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                  <a href="{{ route('ingredients.edit', $ing) }}" class="btn btn-sm btn-gold me-1" title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="{{ route('ingredients.show', $ing) }}" class="btn btn-sm btn-deepblue me-1" title="View">
                    <i class="bi bi-eye"></i>
                  </a>
                  <form action="{{ route('ingredients.destroy', $ing) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Delete this ingredient?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-red" title="Delete">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-muted">No ingredients found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection


<style>
  /* Table header */
  table.dataTable thead th {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    text-align: center;
    vertical-align: middle;
  }

  /* Table cells */
  table.dataTable tbody td {
    text-align: center;
    vertical-align: middle;
  }

  /* Buttons */
  .btn-gold {
    border: 1px solid #e2ae76 !important;
    color: #e2ae76 !important;
    background-color: transparent !important;
  }
  .btn-gold:hover {
    background-color: #e2ae76 !important;
    color: #fff !important;
  }
  .btn-deepblue {
    border: 1px solid #041930 !important;
    color: #041930 !important;
    background-color: transparent !important;
  }
  .btn-deepblue:hover {
    background-color: #041930 !important;
    color: #fff !important;
  }
  .btn-red {
    border: 1px solid #ff0000 !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: red !important;
    color: #fff !important;
  }

  /* DataTables length dropdown */
  .dataTables_length select {
    border: 1px solid #e2ae76;
    color: #041930;
    padding-right: 30px;
    background: #fff url('data:image/svg+xml;utf8,<svg fill="%23e2ae76" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>')
      no-repeat right 10px center;
    appearance: none;
  }
</style>


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize DataTable with explicit column defs
  if (window.$ && $.fn.DataTable) {
    $('#ingredientsTable').DataTable({
      responsive: true,
      columns: [
        null,
        null,
        null,
        { orderable: false }
      ],
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search ingredients...",
        lengthMenu: "Show _MENU_ entries",
        zeroRecords: "No matching ingredients found",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "No ingredients available",
        paginate: {
          first: "First",
          last: "Last",
          next: "→",
          previous: "←"
        }
      }
    });
  }

  // Bootstrap 5 tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function(el) {
    return new bootstrap.Tooltip(el);
  });

  // Bootstrap form validation
  var forms = document.querySelectorAll('.needs-validation');
  Array.prototype.slice.call(forms).forEach(function(form) {
    form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
});
</script>
@endsection
