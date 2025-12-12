@extends("Company.Layouts.main")

@section("content")
    <div class="page-heading">
        <h3>Dashboard Perusahaan</h3>
        <p class="text-muted">Ringkasan aktivitas perusahaan Anda.</p>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Total Lowongan</h6>
                        <div class="d-flex align-items-center mt-2">
                            <div class="display-6 fw-bold me-3">{{ $dataJobCount }}</div>
                            {{-- <span class="badge bg-primary">+2 minggu ini</span> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Published</h6>
                        <div class="d-flex align-items-center mt-2">
                            <div class="display-6 fw-bold me-3">{{ $dataPublished }}</div>
                            <span class="badge bg-success">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Draft</h6>
                        <div class="d-flex align-items-center mt-2">
                            <div class="display-6 fw-bold me-3">{{ $dataDraft }}</div>
                            <span class="badge bg-secondary">Perlu review</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Kandidat Baru</h6>
                        <div class="d-flex align-items-center mt-2">
                            <div class="display-6 fw-bold me-3">{{ $dataApplyPending }}</div>
                            <span class="badge bg-warning">Yang masih pending</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Statistik Lowongan 6 Bulan</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-lowongan" style="min-height:280px"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Lowongan Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table">

                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @foreach ($dataJob5Latest as $item)
                                    <tbody>
                                        <tr>
                                            <td>{{ $item->title }}</td>

                                            <td><span
                                                    class="badge {{ $item->status == "draft" ? "bg-info" : " bg-success" }}">{{ $item->status }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @session("swal")
        <script src="{{ asset("assets/admindash/assets/extensions/sweetalert2/sweetalert2.min.js") }}"></script>
        <script>
            const swalData = @json(session("swal"));
            console.log("Testing 222");

            Swal.fire({
                icon: swalData.icon,
                title: swalData.title,
                text: swalData.text,
                timer: 3000
            });
        </script>
    @endsession

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var options = {
            chart: {
                type: 'area',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            series: [{
                    name: 'Published',
                    data: [5, 6, 7, 8, 9, 8]
                },
                {
                    name: 'Draft',
                    data: [3, 4, 5, 4, 3, 4]
                }
            ],
            xaxis: {
                categories: ['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov']
            },
            colors: ['#198754', '#6c757d']
        };
        var chart = new ApexCharts(document.querySelector('#chart-lowongan'), options);
        chart.render();
    });
</script>
