@extends('frontend.layouts.app')

@section('title', 'Monthly Costs & Income Dashboard')

@section('content')

@php use \Carbon\Carbon; @endphp

<div class="container py-5">

  <h3>{{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</h3>

  {{-- Year Selector --}}
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <label for="yearSelector" class="form-label">Select Year</label>
      <select id="yearSelector" name="year" class="form-select">
        @foreach($availableYears as $availableYear)
          <option value="{{ $availableYear }}"
            {{ $availableYear == $year ? 'selected' : '' }}>
            {{ $availableYear }}
          </option>
        @endforeach
      </select>
      
    
    </div>
  </div>

  {{-- Month Tabs --}}
  <ul class="nav nav-tabs mb-4">
      @for($m = 1; $m <= 12; $m++)
        <li class="nav-item">
          <a class="nav-link {{ $m == $month ? 'active' : '' }}"
            href="{{ route('costs.dashboard', ['m' => $m, 'y' => $year]) }}">
            {{ \Carbon\Carbon::create($year, $m, 1)->format('M') }}
          </a>
        </li>
      @endfor
  </ul>

  {{-- 1) Per-category costs for this month --}}
  <div class="row g-3 mb-5">
    @foreach($categories as $cat)
      <div class="col-md-4">
        <label class="form-label">{{ $cat->name }}</label>
        <input class="form-control" readonly
               value="€{{ number_format($raw->get($cat->id, 0), 2) }}">
      </div>
    @endforeach
  </div>
  

  <hr class="my-5">

  {{-- 2) Yearly cost comparison --}}

  <hr class="my-5">
  
  <h4>Monthly Comparison</h4>
  <div class="alert alert-info">
    Best month ({{ $year }}): <strong>{{ Carbon::create($year,$bestMonth,1)->format('F') }}</strong>
     &nbsp;Net €{{ number_format($bestNet,2) }}<br>
    Worst month ({{ $year }}): <strong>{{ Carbon::create($year,$worstMonth,1)->format('F') }}</strong>
     &nbsp;Net €{{ number_format($worstNet,2) }}
  </div>
  
  <table class="table table-bordered mb-5">
    <thead class="table-light text-center">
      <tr>
        <th rowspan="2">Month</th>
        <th colspan="3">Cost &amp; Income ({{ $year }})</th>
        <th colspan="3">Cost &amp; Income ({{ $lastYear }})</th>
      </tr>
      <tr class="text-nowrap">
        <th>Cost (€)</th><th>Income (€)</th><th>Net (€)</th>
        <th>Cost (€)</th><th>Income (€)</th><th>Net (€)</th>
      </tr>
    </thead>
    <tbody>
      @for($m=1; $m<=12; $m++)
        @php
          $c1 = $costsThisYear->get($m,0);
          $i1 = $incomeThisYearMonthly->get($m,0);
          $n1 = $i1 - $c1;
          $c2 = $costsLastYear->get($m,0);
          $i2 = $incomeLastYearMonthly->get($m,0);
          $n2 = $i2 - $c2;
        @endphp
        <tr>
          <td>{{ Carbon::create($year,$m,1)->format('F') }}</td>
          <td class="text-end">{{ number_format($c1,2) }}</td>
          <td class="text-end">{{ number_format($i1,2) }}</td>
          <td class="text-end {{ $n1>=0?'text-success':'text-danger' }}">
            {{ number_format($n1,2) }}
          </td>
          <td class="text-end">{{ number_format($c2,2) }}</td>
          <td class="text-end">{{ number_format($i2,2) }}</td>
          <td class="text-end {{ $n2>=0?'text-success':'text-danger' }}">
            {{ number_format($n2,2) }}
          </td>
        </tr>
      @endfor
      <tr class="fw-bold bg-light">
        <td>Total</td>
        <td class="text-end">{{ number_format($totalCostYear,2) }}</td>
        <td class="text-end">{{ number_format($totalIncomeYear,2) }}</td>
        <td class="text-end {{ $netYear>=0?'text-success':'text-danger' }}">
          {{ number_format($netYear,2) }}
        </td>
        <td class="text-end">{{ number_format($totalCostLastYear,2) }}</td>
        <td class="text-end">{{ number_format($totalIncomeLastYear,2) }}</td>
        <td class="text-end {{ $netLastYear>=0?'text-success':'text-danger' }}">
          {{ number_format($netLastYear,2) }}
        </td>
      </tr>
    </tbody>
  </table>
  

  <hr class="my-5">

  {{-- 3) Income for this month vs last year --}}
  <h4>This Month’s Income</h4>
  <div class="row g-3 mb-5">
    <div class="col-md-4">
      <label class="form-label">
        Income ({{ $year }}, {{ \Carbon\Carbon::create($year, $month, 1)->format('F') }})
      </label>
      <input class="form-control" readonly
             value="${{ number_format($incomeThisMonth, 2) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">
        Income ({{ $lastYear }}, same month)
      </label>
      <input class="form-control" readonly
             value="${{ number_format($incomeLastYearSame, 2) }}">
    </div>
  </div>

  <hr class="my-5">

  {{-- 4) NEW: Year‐to‐date totals --}}
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Total Costs ({{ $year }})</label>
      <input class="form-control fw-bold" readonly
             value="${{ number_format($totalCostYear, 2) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">Total Income ({{ $year }})</label>
      <input class="form-control fw-bold" readonly
             value="${{ number_format($totalIncomeYear, 2) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">Net ({{ $year }})</label>
      @php
        $net = $totalIncomeYear - $totalCostYear;
      @endphp
      <input class="form-control {{ $net >= 0 ? 'text-success' : 'text-danger' }}" readonly
             value="${{ number_format($net, 2) }}">
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const yearSelector = document.getElementById('yearSelector');
    yearSelector.addEventListener('change', function () {
      const y = this.value;
      const m = {{ $month }};
      window.location.href = `?y=${y}&m=${m}`;
    });
  });
</script>
@endsection
