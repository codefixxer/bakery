{{-- resources/views/frontend/external-supplies/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','External Supplies & Returns')

@section('content')
<div class="container py-5">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">External Supplies & Returns</h4>
    <div>
      <a href="{{ route('external-supplies.create') }}" class="btn btn-primary me-2">
        <i class="bi bi-truck me-1"></i> Add Supply
      </a>
 
    </div>
  </div>

  {{-- Filters --}}
  <div class="row mb-3 gx-2">
    <div class="col-md-4">
      <input id="filterClient" type="text" class="form-control" placeholder="Filter by client…">
    </div>
    <div class="col-md-4">
      <input id="filterDate" type="date" class="form-control" placeholder="Filter by date…">
    </div>
  </div>

  {{-- Accordion --}}
  <div class="accordion" id="reportAccordion">
    @php $grp = 0; @endphp
    @foreach($all as $client => $byDates)
      @foreach($byDates as $date => $entries)
        @php
          $revenue    = $entries->sum('revenue');
          $cost       = $entries
                          ->flatMap(fn($e) => $e['lines'])
                          ->sum(fn($line) => ($line->recipe->production_cost_per_kg ?? 0)/1000 * $line->qty);
          $profit     = $revenue - $cost;
          $type       = $entries->first()['type'];   // 'supply' or 'return'
          $collapseId = "grp{$grp}";
        @endphp

        <div class="accordion-item client-accordion" data-client="{{ strtolower($client) }}" data-date="{{ $date }}">
          <h2 class="accordion-header" id="heading{{ $grp }}">
            <button class="accordion-button collapsed 
                           {{ $type==='supply' ? 'bg-primary text-white' : 'bg-warning text-dark' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                    aria-expanded="false">
              <div class="d-flex w-100 justify-content-between align-items-center">
                <div>
                  @if($type==='supply')
                    <i class="bi bi-truck me-1"></i>
                  @else
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                  @endif
                  <strong>{{ ucfirst($type) }}</strong> — {{ $client }} on {{ $date }}
                </div>
                <div class="text-end">
                  <div>Revenue: <span class="badge bg-light text-dark">€{{ number_format($revenue,2) }}</span></div>
                  <div>Profit: 
                    <span class="badge {{ $profit>=0 ? 'bg-success' : 'bg-danger' }}">
                      €{{ number_format($profit,2) }}
                    </span>
                  </div>
                  <div class="progress mt-1" style="height:6px;">
                    @php
                      $pct = $revenue>0 ? ($profit/$revenue)*100 : 0;
                      $bar = min(max($pct,0),100);
                    @endphp
                    <div class="progress-bar {{ $profit>=0?'bg-success':'bg-danger' }}"
                         role="progressbar"
                         style="width:{{ abs($bar) }}%;"></div>
                  </div>
                </div>
              </div>
            </button>
          </h2>

          <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $grp }}" data-bs-parent="#reportAccordion">
            <div class="accordion-body bg-light">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Recipe</th>
                    <th>Qty</th>
                    <th class="text-end">Line Rev (€)</th>
                    <th class="text-end">Line Cost (€)</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($entries as $entry)
                  <tr>
                    <td colspan="5">
                      {{-- Client Information --}}
                      <strong>{{ $entry['client'] }}</strong> — {{ $entry['date'] }}
                    </td>
                    <td class="text-end">
                      {{-- Return Button for Each Client's Supply --}}
                      <a href="{{ route('returned-goods.create', ['external_supply_id' => $entry['external_supply_id']]) }}" 
                         class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Return
                      </a>
                    </td>
                  </tr>
                  @foreach($entry['lines'] as $line)
                  @php
                    $lineRev  = ($entry['type'] === 'supply' ? 1 : -1) * $line->total_amount;
                    $lineCost = ($line->recipe->production_cost_per_kg ?? 0) / 1000 * $line->qty;
                  @endphp
                  <tr>
                    <td>{{ optional($line->recipe)->recipe_name ?? '—' }}</td>
                    <td>{{ $line->qty }}</td>
                    <td class="text-end">{{ number_format($lineRev, 2) }}</td>
                    <td class="text-end">{{ number_format($lineCost, 2) }}</td>
                    <td></td>
                  </tr>
                @endforeach
                
                @endforeach
                
                
                </tbody>
              </table>
            </div>
          </div>
        </div>

        @php $grp++; @endphp
      @endforeach
    @endforeach
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const clientFilter = document.getElementById('filterClient');
    const dateFilter   = document.getElementById('filterDate');
    const items        = document.querySelectorAll('.client-accordion');

    function applyFilters() {
      const clientVal = clientFilter.value.trim().toLowerCase();
      const dateVal   = dateFilter.value;
      items.forEach(item => {
        const matchClient = !clientVal || item.dataset.client.includes(clientVal);
        const matchDate   = !dateVal   || item.dataset.date === dateVal;
        item.style.display = (matchClient && matchDate) ? '' : 'none';
      });
    }

    [clientFilter, dateFilter].forEach(el => el.addEventListener('input', applyFilters));
  });
</script>
@endsection
