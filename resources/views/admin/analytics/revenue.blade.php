@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-xl-12 col-md-12 mb-30">
        <div class="card p-4 border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-black text-slate-800 uppercase tracking-tighter m-0">Revenue Overview</h5>
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm rounded-3 text-slate-500 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Filter Period
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 6 Months</a></li>
                        <li><a class="dropdown-item" href="#">Last Year</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="revenue-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-30">
        <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
            <h6 class="fw-black text-slate-800 uppercase tracking-tighter mb-4">Monthly Income Summary</h6>
            <div class="table-responsive--md">
                <table class="table table--light">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyRevenue as $rev)
                        <tr>
                            <td>{{ $rev->month }}</td>
                            <td class="fw-bold">{{ gs('cur_sym') }}{{ showAmount($rev->total) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-30">
        <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
            <h6 class="fw-black text-slate-800 uppercase tracking-tighter mb-4">Weekly Performance</h6>
            <div id="weekly-revenue-chart"></div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        series: [{
            name: 'Revenue',
            data: @json($monthlyRevenue->pluck('total'))
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#F97316'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: @json($monthlyRevenue->pluck('month')),
        }
    };
    var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
    chart.render();

    var weeklyOptions = {
        series: [{
            name: 'Weekly Revenue',
            data: @json($weeklyRevenue->pluck('total'))
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                columnWidth: '50%',
            }
        },
        colors: ['#3B82F6'],
        xaxis: {
            categories: @json($weeklyRevenue->pluck('week')),
        }
    };
    var weeklyChart = new ApexCharts(document.querySelector("#weekly-revenue-chart"), weeklyOptions);
    weeklyChart.render();
</script>
@endpush
