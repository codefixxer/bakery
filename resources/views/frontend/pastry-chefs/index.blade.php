{{-- resources/views/pastry-chefs/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Pastry-chefs Showcase')

@section('content')
<div class="container py-5">

  <!-- Add / Edit Chef Form -->
  <div class="card mb-4 border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-egg-fried fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($pastryChef) ? 'Edit Chef' : 'Add Chef' }}</h5>
    </div>
    <div class="card-body">
      <form 
        action="{{ isset($pastryChef) ? route('pastry-chefs.update', $pastryChef) : route('pastry-chefs.store') }}" 
        method="POST" 
        class="row g-3 needs-validation" 
        novalidate
      >
        @csrf
        @if(isset($pastryChef)) @method('PUT') @endif

        <div class="col-md-4">
          <label for="Name" class="form-label fw-semibold">Chef Name</label>
          <input 
            type="text"
            id="Name"
            name="name"
            class="form-control form-control-lg"
            value="{{ old('name', $pastryChef->name ?? '') }}"
            placeholder="Add Chef Name"
            required
          >
          <div class="invalid-feedback">
            Please provide a Chef name.
          </div>
        </div>

        <div class="col-md-4">
          <label for="Email" class="form-label fw-semibold">Chef Email</label>
          <input 
            type="email"
            id="Email"
            name="email"
            class="form-control form-control-lg"
            value="{{ old('email', $pastryChef->email ?? '') }}"
            placeholder="Add Chef Email"
            required
          >
          <div class="invalid-feedback">
            Please provide a Chef email.
          </div>
        </div>

        <div class="col-md-4">
          <label for="Phone" class="form-label fw-semibold">Phone Number</label>
          <input 
            type="text"
            id="Phone"
            name="phone"
            class="form-control form-control-lg"
            value="{{ old('phone', $pastryChef->phone ?? '') }}"
            placeholder="Add Chef Phone"
            required
          >
          <div class="invalid-feedback">
            Please provide a Chef phone.
          </div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-save2 me-2"></i>
            {{ isset($pastryChef) ? 'Update Chef' : 'Save Chef' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Pastry-chefs Table -->
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-people fs-4 me-2"></i>
      <h5 class="mb-0">Pastry-chefs Showcase</h5>
    </div>
    <div class="card-body table-responsive">
      <table
        id="pastryChefsTable"
        class="table table-striped table-hover table-bordered align-middle mb-0"
        data-page-length="10"
      >
        <thead class="table-primary">
          <tr>
            <th>Created By</th> {{-- 👈 Add this --}}

            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Last Updated</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pastryChefs as $chef)
            <tr>
              <td>
                @if($chef->user?->created_by === null)
                  <span class="badge bg-light text-dark">Default</span>
                @else
                  <span class="badge bg-light text-dark">{{ $chef->user->name ?? '—' }}</span>
                @endif
              </td>
        
              <td>{{ $chef->name }}</td>
              <td>{{ $chef->email ?? '—' }}</td>
              <td>{{ $chef->phone ?? '—' }}</td>
              <td>{{ optional($chef->updated_at)->format('Y-m-d H:i') ?? '—' }}</td>
              <td class="text-center">
                <a href="{{ route('pastry-chefs.show', $chef) }}"
                   class="btn btn-sm btn-outline-info me-1"
                   title="View">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('pastry-chefs.edit', $chef) }}"
                   class="btn btn-sm btn-outline-primary me-1"
                   title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('pastry-chefs.destroy', $chef) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Delete this chef?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted">No chefs found.</td>
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
      $('#pastryChefsTable').DataTable({
        paging: true,
        ordering: true,
        responsive: true,
        pageLength: $('#pastryChefsTable').data('page-length'),
        columnDefs: [{ orderable: false, targets: 4 }]
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
