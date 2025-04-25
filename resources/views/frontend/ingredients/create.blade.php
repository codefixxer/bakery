@extends('frontend.layouts.app')

@section('title', 'Add Ingredient')

@section('content')
    <div class="container py-5">
        <div class="card border-success shadow-sm">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <i class="bi bi-box-seam fs-4 me-2"></i>
                <h5 class="mb-0">Add Ingredient</h5>
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
                        <input type="text" id="ingredientName" name="ingredient_name"
                            class="form-control form-control-lg" placeholder="e.g. All‑purpose Flour"
                            value="{{ old('ingredient_name', $ingredient->ingredient_name ?? '') }}" required>
                        <div class="invalid-feedback">
                            Please provide an ingredient name.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="pricePerKg" class="form-label fw-semibold">Price per kg</label>
                        <div class="input-group input-group-lg has-validation">
                            <span class="input-group-text">€</span>
                            <input type="number" id="pricePerKg" name="price_per_kg" class="form-control" step="0.01"
                                placeholder="0.00" value="{{ old('price_per_kg', $ingredient->price_per_kg ?? '') }}"
                                required>

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
@endsection

@section('scripts')
    <script>
        // Bootstrap form validation
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
@endsection
