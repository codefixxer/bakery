{{-- resources/views/equipment/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Equipments Showcase')

@section('content')
<div class="container py-5">

  <!-- Add New Equipment Form -->
  <form 
    action="{{ route('equipment.store') }}" 
    method="POST" 
    class="row g-3 needs-validation" 
    novalidate
  >
    @csrf
    <div class="col-md-6">
      <label for="Name" class="form-label fw-semibold">Equipment Name</label>
      <input 
        type="text" 
        id="Name" 
        name="name" 
        class="form-control form-control-lg" 
        placeholder="Add Equipment Name" 
        required 
        value="{{ old('name') }}"
      >
      <div class="invalid-feedback">
        Please provide an equipment name.
      </div>
    </div>
    <div class="col-12 text-end">
      <button type="submit" class="btn btn-lg btn-success">
        <i class="bi bi-save2 me-2"></i> Save Equipment
      </button>
    </div>
  </form>
<br><br>
  <hr class="my-4">

  <!-- Equipment List -->
  <div class="card basic-data-table mb-4">
    <div class="card-body">
      <table
        id="equipmentTable"
        class="table bordered-table mb-0"
        data-page-length="10"
      >
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($equipments as $equ)
            <tr>
              <td>{{ $equ->id }}</td>
              <td>{{ $equ->name }}</td>
              <td class="text-center">
                <!-- Edit Button -->
                <a 
                  href="{{ route('equipment.edit', $equ) }}"
                  class="btn btn-sm btn-outline-primary me-1"
                  title="Edit Equipment"
                >
                  <i class="bi bi-pencil-square"></i>
                </a>
                
                <!-- Delete Button -->
                <form 
                  action="{{ route('equipment.destroy', $equ) }}" 
                  method="POST" 
                  class="d-inline" 
                  onsubmit="return confirm('Delete this equipment?');"
                >
                  @csrf
                  @method('DELETE')
                  <button 
                    type="submit" 
                    class="btn btn-sm btn-outline-danger"
                    title="Delete Equipment"
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
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.$ && $.fn.DataTable) {
      $('#equipmentTable').DataTable({
        pageLength: $('#equipmentTable').data('page-length'),
        responsive: true,
        scrollX: true,
        autoWidth: false,
        columnDefs: [
          { orderable: false, targets: 2 }
        ]
      });
    }
  });
</script>
@endsection
