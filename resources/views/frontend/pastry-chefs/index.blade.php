@extends('frontend.layouts.app')

@section('title','Pastry-chefs Showcase')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Add / Edit Chef Form -->
  <div class="card mb-4 border-primary shadow-sm">
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-egg-fried fs-4 me-2" style="color: #e2ae76;"></i>
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">{{ isset($pastryChef) ? 'Edit Chef' : 'Add Chef' }}</h5>
    </div>
    <div class="card-body">
      <form 
        action="{{ isset($pastryChef) ? route('pastry-chefs.update', $pastryChef) : route('pastry-chefs.store') }}" 
        method="POST" 
        class="row g-3 needs-validation" 
        novalidate>
        @csrf
        @if(isset($pastryChef)) @method('PUT') @endif

        <div class="col-md-4">
          <label for="Name" class="form-label fw-semibold">Chef Name</label>
          <input type="text" id="Name" name="name" class="form-control form-control-lg"
                 value="{{ old('name', $pastryChef->name ?? '') }}"
                 placeholder="Add Chef Name" required>
          <div class="invalid-feedback">Please provide a Chef name.</div>
        </div>

        <div class="col-md-4">
          <label for="Email" class="form-label fw-semibold">Chef Email</label>
          <input type="email" id="Email" name="email" class="form-control form-control-lg"
                 value="{{ old('email', $pastryChef->email ?? '') }}"
                 placeholder="Add Chef Email" required>
          <div class="invalid-feedback">Please provide a Chef email.</div>
        </div>

        <div class="col-md-4">
          <label for="Phone" class="form-label fw-semibold">Phone Number</label>
          <input type="text" id="Phone" name="phone" class="form-control form-control-lg"
                 value="{{ old('phone', $pastryChef->phone ?? '') }}"
                 placeholder="Add Chef Phone" required>
          <div class="invalid-feedback">Please provide a Chef phone.</div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-gold-filled btn-lg">
            <i class="bi bi-save2 me-2"></i>{{ isset($pastryChef) ? 'Update Chef' : 'Save Chef' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Pastry-chefs Table -->
  <div class="card border-primary shadow-sm">
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-people fs-4 me-2" style="color: #e2ae76;"></i>
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">Pastry-chefs Showcase</h5>
    </div>
    <div class="card-body table-responsive">
      <table
        id="pastryChefsTable"
        class="table table-bordered table-striped table-hover align-middle text-center mb-0"
        data-page-length="10">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Last Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pastryChefs as $chef)
            <tr>
              <td>{{ $chef->name }}</td>
              <td>{{ $chef->email ?? '—' }}</td>
              <td>{{ $chef->phone ?? '—' }}</td>
              <td>{{ optional($chef->updated_at)?->format('Y-m-d H:i') ?? '—' }}</td>
              <td>
                <a href="{{ route('pastry-chefs.show', $chef) }}" class="btn btn-sm btn-deepblue me-1" title="View">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('pastry-chefs.edit', $chef) }}" class="btn btn-sm btn-gold me-1" title="Edit">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('pastry-chefs.destroy', $chef) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Delete this chef?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-red" title="Delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-muted">No chefs found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

<style>
  table thead th {
  background-color: #e2ae76 !important;
  color: #041930 !important;
  text-align: center !important;
  vertical-align: middle !important;
}

table tbody td {
  text-align: center !important;
  vertical-align: middle !important;
}


  .btn-gold-filled {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    border: none !important;
    font-weight: 500;
    padding: 10px 24px;
    border-radius: 12px;
    transition: background-color 0.2s ease;
  }

  .btn-gold-filled:hover {
    background-color: #d89d5c !important;
    color: white !important;
  }

  .btn-gold, .btn-deepblue, .btn-red {
    border: 1px solid;
    font-weight: 500;
  }

  .btn-gold {
    border-color: #e2ae76 !important;
    color: #e2ae76 !important;
  }

  .btn-gold:hover {
    background-color: #e2ae76 !important;
    color: white !important;
  }

  .btn-deepblue {
    border-color: #041930 !important;
    color: #041930 !important;
  }

  .btn-deepblue:hover {
    background-color: #041930 !important;
    color: white !important;
  }

  .btn-red {
    border-color: #ff0000 !important;
    color: red !important;
  }

  .btn-red:hover {
    background-color: #ff0000 !important;
    color: white !important;
  }

  .btn-gold i,
  .btn-deepblue i,
  .btn-red i {
    color: inherit !important;
  }
</style>

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    $('#pastryChefsTable').DataTable({
      paging: true,
      ordering: true,
      responsive: true,
      pageLength: $('#pastryChefsTable').data('page-length'),
      columnDefs: [{ orderable: false, targets: 1 }]
    });

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
