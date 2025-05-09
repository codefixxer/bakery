@extends('frontend.layouts.app')

@section('title','External Supplies & Returns')

@section('content')
<div class="container py-5">
  <!-- Header Card -->
  <div class="card shadow-sm border-0 mb-4" style="background-color: #041930;">
    <div class="card-body d-flex justify-content-between align-items-center">
      <h3 class="mb-0 text-uppercase fw-bold" style="color: #e2ae76;">
        <i class="bi bi-box-seam me-2"></i>External Supplies & Returns
      </h3>
      <a href="{{ route('external-supplies.create') }}" class="btn btn-lg fw-semibold"
         style="background-color: #e2ae76; color: #041930;">
        <i class="bi bi-truck me-2" style="color: #041930;"></i> Add Supply
      </a>
    </div>
  </div>

  <!-- Filters -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
      <div class="row g-3 align-items-end">
        <div class="col-md-5">
          <label for="filterClient" class="form-label fw-semibold">Filter by Client</label>
          <input id="filterClient" type="text" class="form-control form-control-lg" placeholder="e.g. Steel Reilly">
        </div>
        <div class="col-md-5">
          <label for="filterDate" class="form-label fw-semibold">Filter by Date</label>
          <input id="filterDate" type="date" class="form-control form-control-lg">
        </div>
        <div class="col-md-2 text-end">
          <button class="btn btn-outline-secondary w-100" onclick="document.getElementById('filterClient').value=''; document.getElementById('filterDate').value=''; applyFilters();">
            <i class="bi bi-x-circle me-1"></i> Clear
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Accordion Display -->
  <div class="accordion" id="reportAccordion">
    @php $grp = 0; @endphp
    @foreach($all as $client => $byDates)
    @foreach($byDates->sortKeysDesc() as $date => $entries)
        @php
          $revenue    = $entries->sum('revenue');
          $cost       = $entries
                          ->flatMap(fn($e) => $e['lines'])
                          ->sum(fn($line) => ($line->recipe->production_cost_per_kg ?? 0)/1000 * $line->qty);
          $profit     = $revenue - $cost;
          $type       = $entries->first()['type'];
          $collapseId = 'grp' . $grp;
        @endphp

        <div class="accordion-item client-accordion shadow-sm rounded-3 border-0 mb-3"
             data-client="{{ strtolower($client) }}" data-date="{{ $date }}">
          <h2 class="accordion-header" id="heading{{ $grp }}">
            <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                    aria-expanded="false"
                    style="
                      background-color: {{ $type === 'supply' ? '#041930' : '#e2ae76' }};
                      color: {{ $type === 'supply' ? '#e2ae76' : '#041930' }};
                      font-weight: bold;
                      border-radius: .5rem;
                    ">
              <div class="d-flex w-100 justify-content-between align-items-center">
                <div>
                  @if($type === 'supply')
                    <i class="bi bi-truck me-1"></i>
                  @else
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                  @endif
                  {{ ucfirst($type) }} — {{ $client }} on {{ $date }}
                </div>
                <div class="text-end">
                  <div>Revenue:
                    <span class="badge bg-light text-dark">€{{ number_format($revenue, 2) }}</span>
                  </div>
                  <div>Profit:
                    <span class="badge {{ $profit >= 0 ? 'bg-success' : 'bg-danger' }}">
                      €{{ number_format($profit, 2) }}
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
              <table class="table table-sm table-hover">
                <thead class="table-light">
                  <tr>
                    
                    <th>Client</th>
                    <th>Recipe</th>
                    <th>Qty</th>
                    <th class="text-end">Line Rev (€)</th>
                    <th class="text-end">Line Cost (€)</th>
                    <th class="text-end">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($entries as $entry)
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
                     
                        <td>{{ $entry['client'] }}</td>
                        <td>{{ $line->name }}</td>
                        <td>{{ $line->qty }}</td>
                        <td class="text-end">{{ number_format($line->revenue, 2) }}</td>
                        <td class="text-end">{{ number_format($line->cost, 2) }}</td>
                        <td class="text-end">
                          <a href="{{ route('returned-goods.create', ['external_supply_id' => $entry['external_supply_id']]) }}"
                             class="btn btn-sm btn-outline-warning me-1" title="Return">
                            <i class="bi bi-arrow-counterclockwise"></i>
                          </a>
                          <a href="{{ route('external-supplies.show', $entry['external_supply_id']) }}"
                             class="btn btn-sm btn-outline-info me-1" title="View">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="{{ route('external-supplies.edit', $entry['external_supply_id']) }}"
                             class="btn btn-sm btn-outline-primary me-1" title="Edit">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <form action="{{ route('external-supplies.destroy', $entry['external_supply_id']) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Delete this supply?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        </td>
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

    window.applyFilters = function() {
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
