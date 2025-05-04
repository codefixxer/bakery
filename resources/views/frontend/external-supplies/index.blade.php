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
          $type       = $entries->first()['type'];
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
                    <div class="progress-bar {{ $profit>=0 ? 'bg-success' : 'bg-danger' }}"
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
                    <th>Created_by</th>
                    <th>Recipe</th>
                    <th>Qty</th>
                    <th class="text-end">Line Rev (€)</th>
                    <th class="text-end">Line Cost (€)</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($entries as $entry)
                    <tr>
                      <td>
                        <span class="badge bg-light text-dark">
                          {{ $entry['created_by'] ?? '—' }}
                        </span>
                      </td>
                      
                      <td colspan="4">
                        <strong>{{ $entry['client'] }}</strong> — {{ $entry['date'] }}
                      </td>
                      <td class="text-end">
                        {{-- Return --}}
                        <a href="{{ route('returned-goods.create', ['external_supply_id' => $entry['external_supply_id']]) }}"
                           class="btn btn-sm btn-outline-warning me-1"
                           title="Return">
                          <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                        {{-- View --}}
                        <a href="{{ route('external-supplies.show', $entry['external_supply_id']) }}"
                           class="btn btn-sm btn-outline-info me-1"
                           title="View">
                          <i class="bi bi-eye"></i>
                        </a>
                        {{-- Edit --}}
                        <a href="{{ route('external-supplies.edit', $entry['external_supply_id']) }}"
                           class="btn btn-sm btn-outline-primary me-1"
                           title="Edit">
                          <i class="bi bi-pencil"></i>
                        </a>
                        {{-- Delete --}}
                        <form action="{{ route('external-supplies.destroy', $entry['external_supply_id']) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Delete this supply?');">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-sm btn-outline-danger" title="Delete">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>

                    @php
                      $groupedLines = collect($entry['lines'])
                          ->groupBy(fn($line) => $line->recipe->id ?? 'unknown')
                          ->map(function ($group) use ($entry) {
                              $first = $group->first();
                              $qty = $group->sum('qty');
                              $totalAmount = $group->sum('total_amount');
                              $costPerKg = $first->recipe->production_cost_per_kg ?? 0;
                              $cost = ($costPerKg / 1000) * $qty;

                              return (object)[
                                  'name' => $first->recipe->recipe_name ?? '—',
                                  'qty'  => $qty,
                                  'revenue' => ($entry['type'] === 'supply' ? 1 : -1) * $totalAmount,
                                  'cost' => $cost,
                              ];
                          });
                    @endphp

                    @foreach($groupedLines as $line)
                      <tr>
                        <td>{{ $line->name }}</td>
                        <td>{{ $line->qty }}</td>
                        <td class="text-end">{{ number_format($line->revenue, 2) }}</td>
                        <td class="text-end">{{ number_format($line->cost, 2) }}</td>
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
  document.addEventListener('DOMContentLoaded', function() {
    const clientFilter = document.getElementById('filterClient');
    const dateFilter   = document.getElementById('filterDate');
    const items        = document.querySelectorAll('.client-accordion');

    function applyFilters() {
      const c = clientFilter.value.trim().toLowerCase();
      const d = dateFilter.value;
      items.forEach(item => {
        const matchClient = !c || item.dataset.client.includes(c);
        const matchDate   = !d || item.dataset.date === d;
        item.style.display = (matchClient && matchDate) ? '' : 'none';
      });
    }

    clientFilter.addEventListener('input', applyFilters);
    dateFilter.addEventListener('input', applyFilters);
  });
</script>
@endsection
