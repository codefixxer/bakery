{{-- resources/views/frontend/equipment/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Equipments Showcase')

@section('content')
<div class="container py-5">

  <!-- Add Equipment Card -->
  <div class="card mb-5 border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-tools fs-4 me-2"></i>
      <h5 class="mb-0">Add New Equipment</h5>
    </div>
    <div class="card-body">
      <form 
        action="{{ route('equipment.store') }}" 
        method="POST" 
        class="row g-3 needs-validation"
        novalidate
      >
        @csrf
        <div class="col-md-8">
          <label for="Name" class="form-label fw-semibold">Equipment Name</label>
          <input 
            type="text" 
            id="Name" 
            name="name" 
            class="form-control form-control-lg" 
            placeholder="e.g. Mixer, Oven" 
            required 
            value="{{ old('name') }}"
          >
          <div class="invalid-feedback">
            Please provide an equipment name.
          </div>
        </div>
        <div class="col-md-4 text-end align-self-end">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-save2 me-1"></i> Save Equipment
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Equipments Table Card -->
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Equipments List</h5>
      
    </div>
    <div class="card-body table-responsive">
      <table
        id="equipmentTable"
        class="table table-striped table-hover table-bordered align-middle mb-0"
        data-page-length="10"
      >
        <thead class="table-primary">
          <tr>
            <th>Name</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($equipments as $equ)
            <tr>
              <td>{{ $equ->name }}</td>
              <td class="text-center">
                <!-- View Button -->
                <a 
                  href="{{ route('equipment.show', $equ) }}" 
                  class="btn btn-sm btn-outline-info me-1" 
                  title="View Equipment"
                >
                  <i class="bi bi-eye"></i>
                </a>
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
          @empty
            <tr>
              <td colspan="2" class="text-center text-muted">No equipment found.</td>
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
      $('#equipmentTable').DataTable({
        paging: true,
        ordering: true,
        responsive: true,
        pageLength: $('#equipmentTable').data('page-length'),
        columnDefs: [{ orderable: false, targets: 1 }]
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
