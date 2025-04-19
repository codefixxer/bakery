@extends('frontend.layouts.app')

@section('title', isset($newss) ? 'Edit News' : 'Add News')

@section('content')
<div class="container py-5">
  <div class="card border-info shadow-sm">
    <div class="card-header bg-info text-white d-flex align-items-center">
      <i class="bi bi-newspaper fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($newss) ? 'Edit News' : 'Add News' }}</h5>
    </div>
    <div class="card-body">

      <form
        action="{{ isset($newss) ? route('newss.update', $newss->id) : route('newss.store') }}"
        method="POST"
        class="needs-validation"
        novalidate
      >
        @csrf
        @if(isset($newss))
          @method('PUT')
        @endif

        <div class="mb-3">
          <label for="title" class="form-label fw-semibold">Title</label>
          <input
            type="text"
            name="title"
            id="title"
            class="form-control form-control-lg"
            placeholder="News headline..."
            value="{{ old('title', $newss->title ?? '') }}"
            required
          >
          <div class="invalid-feedback">
            Please enter a title.
          </div>
        </div>

        <div class="mb-3">
          <label for="content" class="form-label fw-semibold">Content</label>
          <textarea
            name="content"
            id="content"
            rows="6"
            class="form-control"
            placeholder="Write your news content here..."
            required
          >{{ old('content', $newss->content ?? '') }}</textarea>
          <div class="invalid-feedback">
            Please enter the content.
          </div>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-info">
            {{ isset($newss) ? 'Update News' : 'Publish News' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
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
