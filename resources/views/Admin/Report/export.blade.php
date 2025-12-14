@extends('Admin.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Laporan Tahun {{ $year }}</h3>
        <p class="text-muted">Rekap per bulan.</p>
    </div>

    <div class="page-content">
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
                <div id="export-chart"></div>
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
                            @php $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; @endphp
                            @foreach ($labels as $i => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>{{ $companiesMonthly[$i] }}</td>
                                    <td>{{ $jobsMonthly[$i] }}</td>
                                    <td>{{ $usersMonthly[$i] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const labels = @json($labels);
        const companiesMonthly = @json($companiesMonthly);
        const jobsMonthly = @json($jobsMonthly);
        const usersMonthly = @json($usersMonthly);

        const options = {
            chart: {
                type: 'line',
                height: 320,
                toolbar: { show: false }
            },
            series: [
                { name: 'Perusahaan', data: companiesMonthly },
                { name: 'Lowongan', data: jobsMonthly },
                { name: 'User', data: usersMonthly },
            ],
            xaxis: {
                categories: labels
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 4
            },
            colors: ['#435ebe', '#00b894', '#e17055'],
            legend: { position: 'top' }
        };

        const chart = new ApexCharts(document.querySelector("#export-chart"), options);
        chart.render().then(() => {
            window.print();
        });
    </script>
@endsection

