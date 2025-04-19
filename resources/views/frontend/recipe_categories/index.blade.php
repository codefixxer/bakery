@extends('frontend.layouts.app')

@section('title','Recipe‑Category Manager')

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
                  <input  type="text"
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
  <div class="card basic-data-table mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">Recipe‑Category List</h5>
    </div>
    <div class="table-responsive">
      <table id="categoryTable"
             class="table bordered-table mb-0"
             data-page-length="10">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Last&nbsp;Updated</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categories as $cat)
            <tr>
              <td>{{ $cat->id }}</td>
              <td>{{ $cat->name }}</td>
              <td>{{ $cat->updated_at->format('Y-m-d H:i') }}</td>
              <td class="text-center">
                <a href="{{ route('recipe-categories.edit',$cat) }}"
                   class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                   title="Edit">
                  <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                <form action="{{ route('recipe-categories.destroy',$cat) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Delete this category?');">
                  @csrf @method('DELETE')
                  <button type="submit"
                          class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                          title="Delete">
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
/* ---------- Bootstrap validation ---------- */
(()=>{ 'use strict';
const forms=document.querySelectorAll('.needs-validation');
Array.from(forms).forEach(form=>{
  form.addEventListener('submit',e=>{
    if(!form.checkValidity()){e.preventDefault();e.stopPropagation();}
    form.classList.add('was-validated');
  },false);
})();})();

/* ---------- DataTable ---------- */
document.addEventListener('DOMContentLoaded',function(){
  if(window.$ && $.fn.DataTable){
    $('#categoryTable').DataTable({
      pageLength: $('#categoryTable').data('page-length'),
      responsive:true,
      scrollX:true,
      autoWidth:false,
      columnDefs:[{orderable:false,targets:3}]
    });
  }
});
</script>
@endsection
