{{-- resources/views/frontend/costs/dashboard.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Monthly Costs & Income Dashboard')

@section('content')
@php use \Carbon\Carbon; @endphp

<div class="container py-5">

  {{-- Header with Year Selector --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="bi bi-speedometer2 me-2"></i>{{ Carbon::create($year, $month, 1)->format('F Y') }}</h3>
    <div class="w-auto">
      <label for="yearSelector" class="form-label visually-hidden">Select Year</label>
      <select id="yearSelector" name="year" class="form-select form-select-sm">
        @foreach($availableYears as $availableYear)
          <option value="{{ $availableYear }}" {{ $availableYear == $year ? 'selected' : '' }}>
            {{ $availableYear }}
          </option>
        @endforeach
      </select>
    </div>
  </div>

  {{-- Month Tabs --}}
  <ul class="nav nav-pills mb-5">
    @for($m = 1; $m <= 12; $m++)
      <li class="nav-item">
        <a class="nav-link {{ $m == $month ? 'active' : '' }}"
           href="{{ route('costs.dashboard', ['m' => $m, 'y' => $year]) }}">
          {{ Carbon::create($year, $m, 1)->format('M') }}
        </a>
      </li>
    @endfor
  </ul>

  {{-- 1) Per-category Costs --}}
  <div class="row mb-5">
    @foreach($categories as $cat)
      <div class="col-md-4">
        <div class="card border-warning shadow-sm mb-4">
          <div class="card-body text-center">
            <i class="bi bi-tag fs-3 text-warning mb-2"></i>
            <h6 class="text-uppercase text-muted">{{ $cat->name }}</h6>
            <h3 class="fw-bold">€{{ number_format($raw[$cat->id] ?? 0, 2) }}</h3>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- 2) Yearly Cost Comparison --}}
  <div class="card mb-5 shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-bar-chart-line me-2"></i>Monthly Comparison ({{ $year }})</h5>
    </div>
    <div class="card-body">
      <div class="alert alert-info">
        <strong>Best month:</strong> {{ Carbon::create($year,$bestMonth,1)->format('F') }} (€{{ number_format($bestNet,2) }}) &nbsp;&nbsp;
        <strong>Worst month:</strong> {{ Carbon::create($year,$worstMonth,1)->format('F') }} (€{{ number_format($worstNet,2) }})
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
          <thead class="table-light text-center">
            <tr>
              <th>Month</th>
              <th colspan="3">This Year ({{ $year }})</th>
              <th colspan="3">Last Year ({{ $lastYear }})</th>
            </tr>
            <tr class="text-nowrap text-center">
              <th></th>
              <th>Cost (€)</th><th>Income (€)</th><th>Net (€)</th>
              <th>Cost (€)</th><th>Income (€)</th><th>Net (€)</th>
            </tr>
          </thead>
          <tbody>
            @for($m=1; $m<=12; $m++)
              @php
                $c1 = $costsThisYear[$m] ?? 0; $i1 = $incomeThisYearMonthly[$m] ?? 0; $n1 = $i1 - $c1;
                $c2 = $costsLastYear[$m] ?? 0; $i2 = $incomeLastYearMonthly[$m] ?? 0; $n2 = $i2 - $c2;
              @endphp
              <tr class="text-center">
                <td class="text-start">{{ Carbon::create($year,$m,1)->format('F') }}</td>
                <td>€{{ number_format($c1,2) }}</td>
                <td>€{{ number_format($i1,2) }}</td>
                <td class="{{ $n1>=0?'text-success':'text-danger' }}">€{{ number_format($n1,2) }}</td>
                <td>€{{ number_format($c2,2) }}</td>
                <td>€{{ number_format($i2,2) }}</td>
                <td class="{{ $n2>=0?'text-success':'text-danger' }}">€{{ number_format($n2,2) }}</td>
              </tr>
            @endfor
            <tr class="fw-bold bg-light text-center">
              <td>Total</td>
              <td>€{{ number_format($totalCostYear,2) }}</td>
              <td>€{{ number_format($totalIncomeYear,2) }}</td>
              <td class="{{ $netYear>=0?'text-success':'text-danger' }}">€{{ number_format($netYear,2) }}</td>
              <td>€{{ number_format($totalCostLastYear,2) }}</td>
              <td>€{{ number_format($totalIncomeLastYear,2) }}</td>
              <td class="{{ $netLastYear>=0?'text-success':'text-danger' }}">€{{ number_format($netLastYear,2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- 3) This Month’s Income --}}
  <div class="row mb-5">
    <div class="col-md-6">
      <div class="card border-success shadow-sm">
        <div class="card-body text-center">
          <i class="bi bi-wallet2 fs-3 text-success mb-2"></i>
          <h6 class="text-uppercase text-muted">Income ({{ Carbon::create($year, $month, 1)->format('F') }} {{ $year }})</h6>
          <h3 class="fw-bold">€{{ number_format($incomeThisMonth, 2) }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border-secondary shadow-sm">
        <div class="card-body text-center">
          <i class="bi bi-wallet fs-3 text-secondary mb-2"></i>
          <h6 class="text-uppercase text-muted">Income ({{ Carbon::create($lastYear, $month, 1)->format('F') }} {{ $lastYear }})</h6>
          <h3 class="fw-bold">€{{ number_format($incomeLastYearSame, 2) }}</h3>
        </div>
      </div>
    </div>
  </div>

  {{-- 4) Year-to-date Totals --}}
  <div class="row">
    <div class="col-md-4">
      <div class="card border-primary shadow-sm mb-4">
        <div class="card-body text-center">
          <i class="bi bi-receipt fs-3 text-primary mb-2"></i>
          <h6 class="text-uppercase text-muted">Total Costs ({{ $year }})</h6>
          <h3 class="fw-bold">€{{ number_format($totalCostYear, 2) }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-success shadow-sm mb-4">
        <div class="card-body text-center">
          <i class="bi bi-cash-stack fs-3 text-success mb-2"></i>
          <h6 class="text-uppercase text-muted">Total Income ({{ $year }})</h6>
          <h3 class="fw-bold">€{{ number_format($totalIncomeYear, 2) }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-danger shadow-sm mb-4">
        <div class="card-body text-center">
          <i class="bi bi-percent fs-3 text-danger mb-2"></i>
          <h6 class="text-uppercase text-muted">Net ({{ $year }})</h6>
          @php $net = $totalIncomeYear - $totalCostYear; @endphp
          <h3 class="fw-bold {{ $net >= 0 ? 'text-success' : 'text-danger' }}">
            €{{ number_format($net, 2) }}
          </h3>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('yearSelector').addEventListener('change', function () {
      const y = this.value, m = {{ $month }};
      window.location.href = `?y=${y}&m=${m}`;
    });
  });
</script>
@endsection
