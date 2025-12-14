@extends('Admin.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Laporan</h3>
        <p class="text-muted">Rekap per bulan dalam tahun terpilih.</p>
    </div>

    <div class="page-content">
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ url('laporan') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Tahun</label>
                        <select name="year" class="form-select">
                            @foreach ($years as $y)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Bulan (opsional)</label>
                        <select name="month" class="form-select">
                            <option value="">Semua Bulan</option>
                            @foreach ($labels as $i => $label)
                                @php $mval = $i + 1; @endphp
                                <option value="{{ $mval }}" {{ ($month ?? null) == $mval ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" class="btn btn-primary">Terapkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Perusahaan Terdaftar</h6>
                        <div class="display-6 fw-bold">{{ $totals['companies'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Lowongan Dipublikasikan</h6>
                        <div class="display-6 fw-bold">{{ $totals['jobs'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">User Terdaftar</h6>
                        <div class="display-6 fw-bold">{{ $totals['users'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0">Tren Bulanan</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ url('laporan/export-pdf?year=' . $year . ($month ? '&month=' . $month : '')) }}" class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf me-2"></i> Export PDF
                        </a>
                        <a href="{{ url('laporan/export-csv?year=' . $year . ($month ? '&month=' . $month : '')) }}" class="btn btn-info">
                            <i class="bi bi-file-earmark-text me-2"></i> Export CSV
                        </a>
                        <a href="{{ url('laporan/export-excel?year=' . $year . ($month ? '&month=' . $month : '')) }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel me-2"></i> Export Excel
                        </a>
                    </div>
                </div>
                <div id="report-chart"></div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Perusahaan Terdaftar</th>
                                <th>Lowongan Dipublikasikan</th>
                                <th>User Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($month)
                                @php $i = $month - 1; @endphp
                                <tr>
                                    <td>{{ $labels[$i] }}</td>
                                    <td>{{ $companiesMonthly[$i] }}</td>
                                    <td>{{ $jobsMonthly[$i] }}</td>
                                    <td>{{ $usersMonthly[$i] }}</td>
                                </tr>
                            @else
                                @foreach ($labels as $i => $label)
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>{{ $companiesMonthly[$i] }}</td>
                                        <td>{{ $jobsMonthly[$i] }}</td>
                                        <td>{{ $usersMonthly[$i] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load', function () {
            if (!window.ApexCharts) return;

            const labels = @json($chartLabels);
            const companiesMonthly = @json($companiesChart);
            const jobsMonthly = @json($jobsChart);
            const usersMonthly = @json($usersChart);

            const options = {
                chart: {
                    type: 'line',
                    height: 360,
                    toolbar: { show: false }
                },
                series: [
                    { name: 'Perusahaan', type: 'column', data: companiesMonthly },
                    { name: 'User', type: 'column', data: usersMonthly },
                    { name: 'Lowongan', type: 'area', data: jobsMonthly },
                ],
                xaxis: {
                    categories: labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                stroke: {
                    curve: 'smooth',
                    width: [0, 0, 3]
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: 4,
                    strokeColors: '#fff',
                    strokeWidth: 2
                },
                fill: {
                    type: ['solid', 'solid', 'gradient'],
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        gradientToColors: ['#27ae60'],
                        inverseColors: false,
                        opacityFrom: 0.5,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                plotOptions: {
                    bar: {
                        columnWidth: '40%',
                        borderRadius: 6
                    }
                },
                grid: {
                    strokeDashArray: 4
                },
                yaxis: {
                    min: 0,
                    labels: {
                        formatter: (val) => Math.round(val)
                    }
                },
                colors: ['#435ebe', '#e17055', '#00b894'],
                legend: { position: 'top' },
                tooltip: {
                    shared: true,
                    intersect: false
                }
            };

            const el = document.querySelector("#report-chart");
            if (!el) return;
            const chart = new ApexCharts(el, options);
            chart.render();
        });
    </script>
@endsection
