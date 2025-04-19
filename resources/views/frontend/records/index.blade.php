@extends('frontend.layouts.app')

@section('title', 'Filter Records')

@section('content')
<div class="container py-5">
    <h2 class="mb-5 text-center">Showcase & External Supply Records</h2>

    {{-- Filters: Date Range + Recipe --}}
    <div class="row justify-content-center g-4 mb-5">
        <div class="col-sm-6 col-md-4">
            <label for="filter_from" class="form-label text-center d-block">From</label>
            <input type="date" id="filter_from" class="form-control mx-auto" value="{{ $from }}">
        </div>
        <div class="col-sm-6 col-md-4">
            <label for="filter_to" class="form-label text-center d-block">To</label>
            <input type="date" id="filter_to" class="form-control mx-auto" value="{{ $to }}">
        </div>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <label for="filter_recipe" class="form-label text-center d-block">Recipe Name</label>
            <input type="text" id="filter_recipe" class="form-control mx-auto" placeholder="Enter recipe..." value="{{ request('recipe') }}">
        </div>
    </div>

    <div id="noRecords" class="alert alert-info text-center" style="display: none;">
        No records found for the selected filters.
    </div>

    {{-- Summary Cards --}}
    <div id="summary" class="row justify-content-center mb-5 g-4" style="display: none;">
        <div class="col-sm-8 col-md-5 col-lg-4">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Total Showcase Revenue</h5>
                    <p class="display-5 fw-bold mb-1" id="totalShowRevenue">0.00</p>
                    <small class="text-muted" id="pctShow">0%</small>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-5 col-lg-4">
            <div class="card border-danger h-100">
                <div class="card-body text-center">
                    <i class="bi bi-currency-dollar display-4 text-danger mb-3"></i>
                    <h5 class="card-title">Total External Cost</h5>
                    <p class="display-5 fw-bold mb-1" id="totalExternalCost">0.00</p>
                    <small class="text-muted" id="pctExt">0%</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Records Tables --}}
    <div class="row gx-4 gy-5">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-list-ul me-2"></i> Showcase Records
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0 align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Date</th>
                                    <th class="text-start">Recipe</th>
                                    <th>Qty</th>
                                    <th>Sold</th>
                                    <th>Reuse</th>
                                    <th>Waste</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody id="showcaseBody" class="text-center"></tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="6" class="text-end">Total:</th>
                                    <th class="text-center" id="showcaseFooter">0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-box-seam me-2"></i> External Supply Records
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0 align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Date</th>
                                    <th class="text-start">Client</th>
                                    <th class="text-start">Recipe</th>
                                    <th>Qty</th>
                                    <th>Total ($)</th>
                                </tr>
                            </thead>
                            <tbody id="externalBody" class="text-center"></tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <th class="text-center" id="externalFooter">0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    @php
        $showData = $showcaseRecords->flatMap(function($sc) {
            return $sc->recipes->map(function($item) use ($sc) {
                return [
                    'date'        => $sc->showcase_date,
                    'recipe_name' => $item->recipe->recipe_name,
                    'quantity'    => $item->quantity,
                    'sold'        => $item->sold,
                    'reuse'       => $item->reuse,
                    'waste'       => $item->waste,
                    'revenue'     => $item->actual_revenue
                ];
            })->all();
        })->all();

        $extData = $externalRecords->flatMap(function($es) {
            return $es->recipes->map(function($item) use ($es) {
                return [
                    'date'        => $es->supply_date,
                    'client_name' => $es->client->name,
                    'recipe_name' => $item->recipe->recipe_name,
                    'qty'         => $item->qty,
                    'total'       => $item->total_amount
                ];
            })->all();
        })->all();
    @endphp

    const showcaseData = {!! json_encode($showData) !!};
    const externalData = {!! json_encode($extData) !!};

    function render() {
        const from   = document.getElementById('filter_from').value;
        const to     = document.getElementById('filter_to').value;
        const recipe = document.getElementById('filter_recipe').value.trim().toLowerCase();

        const filteredShow = showcaseData.filter(r =>
            (!from || r.date >= from) &&
            (!to   || r.date <= to) &&
            (!recipe || r.recipe_name.toLowerCase().includes(recipe))
        );

        const filteredExt = externalData.filter(r =>
            (!from || r.date >= from) &&
            (!to   || r.date <= to) &&
            (!recipe || r.recipe_name.toLowerCase().includes(recipe))
        );

        const hasRecords = filteredShow.length || filteredExt.length;
        document.getElementById('noRecords').style.display  = hasRecords ? 'none' : '';
        document.getElementById('summary').style.display    = hasRecords ? 'flex' : 'none';

        const totalShow = filteredShow.reduce((sum, r) => sum + parseFloat(r.revenue), 0);
        const totalExt  = filteredExt.reduce((sum, r) => sum + parseFloat(r.total), 0);
        const totalAll  = totalShow + totalExt;
        const pctShow   = totalAll ? ((totalShow / totalAll) * 100).toFixed(0) : 0;
        const pctExt    = totalAll ? ((totalExt  / totalAll) * 100).toFixed(0) : 0;

        document.getElementById('totalShowRevenue').textContent  = totalShow.toFixed(2);
        document.getElementById('pctShow').textContent          = pctShow + '%';
        document.getElementById('totalExternalCost').textContent = totalExt.toFixed(2);
        document.getElementById('pctExt').textContent           = pctExt + '%';
        document.getElementById('showcaseFooter').textContent    = totalShow.toFixed(2);
        document.getElementById('externalFooter').textContent    = totalExt.toFixed(2);

        document.getElementById('showcaseBody').innerHTML = filteredShow.map(r =>
            `<tr><td>${r.date}</td><td class="text-start">${r.recipe_name}</td>` +
            `<td>${r.quantity}</td><td>${r.sold}</td><td>${r.reuse}</td>` +
            `<td>${r.waste}</td><td>${parseFloat(r.revenue).toFixed(2)}</td></tr>`
        ).join('');

        document.getElementById('externalBody').innerHTML = filteredExt.map(r =>
            `<tr><td>${r.date}</td><td class="text-start">${r.client_name}</td>` +
            `<td class="text-start">${r.recipe_name}</td><td>${r.qty}</td>` +
            `<td>${parseFloat(r.total).toFixed(2)}</td></tr>`
        ).join('');
    }

    function debounce(fn, delay=300) {
        let t;
        return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
    }

    document.getElementById('filter_from').addEventListener('change', render);
    document.getElementById('filter_to').addEventListener('change', render);
    document.getElementById('filter_recipe').addEventListener('input', debounce(render, 300));

    render();
</script>
@endsection
