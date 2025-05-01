{{-- resources/views/frontend/costs/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','All Costs')

@section('content')
<div class="container py-5">

  {{-- ──────────────────────── ➊ FORM (create / edit) ─────────────────────── --}}
  <div class="card shadow-sm mb-5">
    <div class="card-header bg-warning text-dark">
      <h5 class="mb-0">{{ isset($cost) ? 'Edit Cost' : 'Add Cost' }}</h5>
    </div>
    <div class="card-body">
      <form method="POST"
            action="{{ isset($cost) ? route('costs.update',$cost) : route('costs.store') }}"
            class="row g-3 needs-validation"
            novalidate>
        @csrf
        @isset($cost) @method('PUT') @endisset

        <div class="col-md-6">
          <label class="form-label">Cost Identifier <small class="text-muted">(optional)</small></label>
          <input type="text" name="cost_identifier" class="form-control"
                 value="{{ old('cost_identifier',$cost->cost_identifier ?? '') }}">
        </div>

        <div class="col-md-6">
          <label class="form-label">Supplier</label>
          <input type="text" name="supplier" class="form-control"
                 value="{{ old('supplier',$cost->supplier ?? '') }}" required>
          <div class="invalid-feedback">Please enter a supplier.</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Amount</label>
          <div class="input-group has-validation">
            <span class="input-group-text">$</span>
            <input type="number" step="0.01" name="amount" class="form-control"
                   value="{{ old('amount',$cost->amount ?? '') }}" required>
            <div class="invalid-feedback">Please enter a valid amount.</div>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Due Date</label>
          <input type="date" name="due_date" class="form-control"
                 value="{{ old('due_date',$cost->due_date ?? '') }}" required>
          <div class="invalid-feedback">Please pick a date.</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select" required>
            <option value="">Select…</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}"
                {{ old('category_id',$cost->category_id??'')==$c->id ? 'selected' : '' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
          <div class="invalid-feedback">Please select a category.</div>
        </div>

        <div class="col-12 text-end">
          <button class="btn btn-warning">
            <i class="bi bi-save2 me-1"></i>
            {{ isset($cost)?'Update':'Save' }} Cost
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- ──────────────────────── ➋ TABLE & FILTER ───────────────────────────── --}}
  {{-- <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">All Costs</h4>
    <a href="{{ route('costs.create') }}" class="btn btn-success">
      <i class="bi bi-plus-circle me-1"></i> Add Cost
    </a>
  </div> --}}

  <form method="GET" class="row g-2 align-items-end mb-4">
    <div class="col-auto">
      <label class="form-label" for="filterMonth">Show month</label>
      <input type="month" id="filterMonth" name="filter_month" class="form-control"
             value="{{ old('filter_month',$filter) }}" onchange="this.form.submit()">
    </div>
  </form>

  <div class="card basic-data-table">
    <div class="table-responsive">
      <table id="costTable" class="table mb-0" data-page-length="10">
        <thead class="table-light">
          <tr>
            <th>Cost Identifier</th>
            <th>Supplier</th>
            <th>Amount</th>
            <th>Due Date</th>
            <th>Category</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($costs as $item)
            <tr>
              <td>{{ $item->cost_identifier  }}</td>
              <td>{{ $item->supplier }}</td>
              <td>${{ number_format($item->amount,2) }}</td>
              <td>{{ \Carbon\Carbon::parse($item->due_date)->format('Y-m-d') }}</td>
              <td>{{ $item->category->name ?? '–' }}</td>
              <td class="text-center">
                <a href="{{ route('costs.edit',$item) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('costs.destroy',$item) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Delete this cost?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
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
document.addEventListener('DOMContentLoaded',()=>{
  if(window.$&&$.fn.DataTable){
    $('#costTable').DataTable({
      pageLength:$('#costTable').data('page-length'),
      responsive:true,
      scrollX:true,
      autoWidth:false,
      columnDefs:[{orderable:false,targets:5}]
    })
  }
})
</script>


@endsection
