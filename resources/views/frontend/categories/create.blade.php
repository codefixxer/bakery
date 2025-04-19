@extends('frontend.layouts.app')

@section('title', isset($category) ? 'Edit Category' : 'Add Category')

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
