{{-- resources/views/pastry-chefs/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Pastry-chefs Showcase')

@section('content')











<div class="container py-5">


   <form 
        action="{{ isset($pastryChef) ? route('pastry-chefs.update', $pastryChef->id) : route('pastry-chefs.store') }}" 
        method="POST" 
        class="row g-3 needs-validation" 
        novalidate
      >
        @csrf
        @if(isset($pastryChef))
          @method('PUT')
        @endif

        <!-- Chef Name -->
        <div class="col-md-6">
          <label for="Name" class="form-label fw-semibold">Chef Name</label>
          <input type="text"
                 id="Name"
                 name="name"
                 class="form-control form-control-lg"
                 value="{{ old('name', $pastryChef->name ?? '') }}"
                 placeholder="Add Chef Name"
                 required>
          <div class="invalid-feedback">
            Please provide a Chef name.
          </div>
        </div>

        <!-- Email -->
        <div class="col-md-6">
          <label for="Email" class="form-label fw-semibold">Chef Email</label>
          <input type="email"
                 id="Email"
                 name="email"
                 class="form-control form-control-lg"
                 value="{{ old('email', $pastryChef->email ?? '') }}"
                 placeholder="Add Chef Email"
                 required>
          <div class="invalid-feedback">
            Please provide a Chef Email.
          </div>
        </div>

        <!-- Phone -->
        <div class="col-md-6">
          <label for="phone" class="form-label fw-semibold">Chef Phone Number</label>
          <input type="number"
                 id="phone"
                 name="phone"
                 class="form-control form-control-lg"
                 value="{{ old('phone', $pastryChef->phone ?? '') }}"
                 placeholder="Add Chef Phone Number"
                 required>
          <div class="invalid-feedback">
            Please provide a Chef Phone Number.
          </div>
        </div>

        <!-- Submit Button -->
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-lg btn-success">
            <i class="bi bi-save2 me-2"></i> {{ isset($pastryChef) ? 'Update Chef' : 'Save Chef' }}
          </button>
        </div>
      </form>
  <div class="card basic-data-table mb-4">
   
    </div>
    <div class="card-body">
      <table
        id="pastryChefsTable"
        class="table bordered-table mb-0"
        data-page-length="10"
      >
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Last Updated</th>
            <th scope="col" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @if($pastryChefs->isEmpty())
  <tr>
    <td colspan="5" class="text-center text-muted">No chefs found.</td>
  </tr>
@endif
          @foreach($pastryChefs as $chef)
          
          <tr>
            <td>{{ $chef->name }}</td>
            <td>{{ $chef->email ?? '-' }}</td>
            <td>{{ $chef->phone ?? '-' }}</td>
            <td>{{ $chef->updated_at->format('Y-m-d H:i') }}</td>
            <td class="text-center">
              <a
                href="{{ route('pastry-chefs.edit', $chef) }}"
                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                title="Edit"
              >
                <iconify-icon icon="lucide:edit"></iconify-icon>
              </a>
              <form
                action="{{ route('pastry-chefs.destroy', $chef) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Delete this chef?');"
              >
                @csrf
                @method('DELETE')
                <button
                  type="submit"
                  class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                  title="Delete"
                >
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
document.addEventListener('DOMContentLoaded', function() {
  if (window.$ && $.fn.DataTable) {
    $('#pastryChefsTable').DataTable({
      pageLength: $('#pastryChefsTable').data('page-length'),
      responsive: true,
      scrollX: true,
   autoWidth: false,
      columnDefs: [
        { orderable: false, targets: 4 }      ]
    });
  }
});
</script>
@endsection
