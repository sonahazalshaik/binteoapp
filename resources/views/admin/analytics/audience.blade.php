@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-xl-6 col-md-6 mb-30">
        <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
            <h6 class="fw-black text-slate-800 uppercase tracking-tighter mb-4">Device Distribution</h6>
            <div id="device-chart"></div>
            <div class="mt-4">
                @foreach($devices as $d)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-slate-500 text-xs font-bold uppercase tracking-widest">{{ $d->device ?: 'Unknown' }}</span>
                    <span class="fw-bold">{{ $d->count }} views</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-30">
        <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
            <h6 class="fw-black text-slate-800 uppercase tracking-tighter mb-4">Top Geographic Locations</h6>
            <div id="country-chart"></div>
            <div class="mt-4">
                @foreach($countries as $c)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-slate-500 text-xs font-bold uppercase tracking-widest">{{ $c->country ?: 'Unknown' }}</span>
                    <span class="fw-bold">{{ $c->count }} views</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var deviceOptions = {
        series: @json($devices->pluck('count')),
        chart: {
            type: 'donut',
            height: 300
        },
        labels: @json($devices->pluck('device')->map(fn($d) => $d ?: 'Unknown')),
        colors: ['#F97316', '#3B82F6', '#10B981', '#6366F1'],
        legend: { position: 'bottom' }
    };
    var deviceChart = new ApexCharts(document.querySelector("#device-chart"), deviceOptions);
    deviceChart.render();

    var countryOptions = {
        series: [{
            data: @json($countries->pluck('count'))
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 4,
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: @json($countries->pluck('country')->map(fn($c) => $c ?: 'Unknown')),
        },
        colors: ['#8B5CF6']
    };
    var countryChart = new ApexCharts(document.querySelector("#country-chart"), countryOptions);
    countryChart.render();
</script>
@endpush
