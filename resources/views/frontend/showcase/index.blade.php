{{-- resources/views/frontend/showcase/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Showcases')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Header Card with "New Showcase" button -->
  <div class="card mb-4 border-primary shadow-sm">
   <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #041930;">
  <h5 class="mb-0 fw-bold d-flex align-items-center" style="color: #e2ae76;">
    <!-- Showcase SVG icon -->
    <svg
      class="me-2"
      viewBox="0 0 512.005 512.005"
      xmlns="http://www.w3.org/2000/svg"
      style="width: 1.5em; height: 1.0em; color: #e2ae76; fill: currentColor;"
    >
      <g>
        <path d="M159.669,238.344c-26.601,0-48.166-21.564-48.166-48.166V21.609h96.331v168.57
                 C207.835,216.779,186.269,238.344,159.669,238.344z"/>
        <path d="M352.331,238.344c-26.601,0-48.166-21.564-48.166-48.166V21.609h96.331v168.57
                 C400.496,216.779,378.932,238.344,352.331,238.344z"/>
        <rect x="191.378" y="312.192" width="129.249" height="178.209"/>
      </g>
      <path d="M496.828,104.985c8.379,0,15.172-6.792,15.172-15.172V58.537c0-28.728-23.372-52.099-52.099-52.099
               h-59.404h-96.332h-96.331h-96.332H52.099C23.372,6.437,0,29.809,0,58.537V190.18
               c0,20.106,9.428,38.04,24.084,49.651v250.563c0,8.379,6.792,15.172,15.172,15.172h152.122h129.244h152.124
               c8.379,0,15.172-6.792,15.172-15.172V312.189c0-8.379-6.792-15.172-15.172-15.172
               c-8.379,0-15.172,6.792-15.172,15.172v163.032h-121.78V312.189c0-8.379-6.792-15.172-15.172-15.172
               H191.378c-8.379,0-15.172,6.792-15.172,15.172v163.032H54.428V252.878
               c2.913,0.413,5.885,0.639,8.91,0.639c19.267,0,36.54-8.659,48.166-22.275
               c11.626,13.617,28.899,22.275,48.166,22.275s36.54-8.659,48.166-22.275
               c11.626,13.617,28.899,22.275,48.166,22.275s36.54-8.659,48.166-22.275
               c11.626,13.617,28.899,22.275,48.166,22.275c19.267,0,36.54-8.659,48.166-22.275
               c11.626,13.617,28.899,22.275,48.166,22.275c34.924,0,63.338-28.414,63.338-63.338
               v-26.232c0-8.379-6.792-15.172-15.172-15.172s-15.172,6.792-15.172,15.172v26.232
               c0,18.193-14.8,32.994-32.994,32.994s-32.994-14.8-32.994-32.994V36.78h44.232
               c11.996,0,21.755,9.76,21.755,21.755v31.277C481.656,98.193,488.449,104.985,496.828,104.985z"/>
    </svg>
    Daily Showcases
  </h5>
  <a href="{{ route('showcase.create') }}" class="btn btn-gold d-flex align-items-center">
    <i class="bi bi-plus-circle me-1"></i> New Showcase
  </a>
</div>

    <div class="card-body">
      <p class="mb-0 text-muted">Browse and manage all your saved showcases below.</p>
    </div>
  </div>

  <!-- Showcases Table Card -->
  <div class="card border-primary shadow-sm">
    <div class="card-body table-responsive">
      <table
        id="showcasesTable"
        class="table table-bordered table-striped table-hover align-middle text-center mb-0"
        style="width:100%;"
      >
        <thead>
          <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Break-even (€)</th>
            <th>Total Revenue (€)</th>
            <th>Plus (€)</th>
            <th>Real Margin (€)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($showcases as $s)
            <tr>
              <td>{{ \Carbon\Carbon::parse($s->showcase_date)->format('Y-m-d') }}</td>
              <td>{{ $s->showcase_name }}</td>
              <td>€{{ number_format($s->break_even, 2) }}</td>
              <td>€{{ number_format($s->total_revenue, 2) }}</td>
              <td>€{{ number_format($s->plus, 2) }}</td>
              <td>
                @if($s->real_margin >= 0)
                  <span class="text-success">€{{ $s->real_margin }}</span>
                @else
                  <span class="text-danger">€{{ $s->real_margin }}</span>
                @endif
              </td>
              <td>
                <a href="{{ route('showcase.show', $s) }}" class="btn btn-sm btn-deepblue me-1" title="View">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('showcase.edit', $s) }}" class="btn btn-sm btn-gold me-1" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('showcase.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this showcase?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-red" title="Delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-muted">No showcases found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

<style>
  table th {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    text-align: center;
    vertical-align: middle;
  }
  table td {
    text-align: center;
    vertical-align: middle;
  }
  .btn-gold {
    border: 1px solid #e2ae76 !important;
    color: #e2ae76 !important;
    background-color: transparent !important;
  }
  .btn-gold:hover {
    background-color: #e2ae76 !important;
    color: white !important;
  }
  .btn-deepblue {
    border: 1px solid #041930 !important;
    color: #041930 !important;
    background-color: transparent !important;
  }
  .btn-deepblue:hover {
    background-color: #041930 !important;
    color: white !important;
  }
  .btn-red {
    border: 1px solid #ff0000 !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: #ff0000 !important;
    color: white !important;
  }
</style>

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (!window.$ || !$.fn.DataTable) return;

    $('#showcasesTable').DataTable({
      paging:     true,
      ordering:   true,
      responsive: true,
      pageLength: 10,
      order:      [[0, 'desc']],       // sort by Date descending
      columns: [
        null,  // Date
        null,  // Name
        null,  // Break-even
        null,  // Total Revenue
        null,  // Potential Avg
        null,  // Plus
        { orderable: false }  // Actions
      ],
      language: {
        search:      "Search:",
        lengthMenu: "Show _MENU_ entries per page",
        info:       "Showing _START_ to _END_ of _TOTAL_ showcases",
        paginate: {
          previous: "&laquo;",
          next:     "&raquo;"
        },
        zeroRecords: "No matching showcases found"
      }
    });
  });
</script>
@endsection
