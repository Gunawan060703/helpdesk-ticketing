@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Laporan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan</li>
    </ol>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Tiket</h6>
                            <h2 class="mb-0">{{ $totalTickets }}</h2>
                        </div>
                        <i class="bi bi-ticket-detailed fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart by Status -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-pie-chart me-1"></i>
                    Tiket per Status
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart by Priority -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-bar-chart me-1"></i>
                    Tiket per Prioritas
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Table by Category -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-table me-1"></i>
                    Tiket per Kategori
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Jumlah Tiket</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ticketsByKategori as $kategori)
                            <tr>
                                <td>{{ $kategori->nama_kategori }}</td>
                                <td>{{ $kategori->tickets_count }}</td>
                                <td>
                                    @if($totalTickets > 0)
                                        {{ round(($kategori->tickets_count / $totalTickets) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Users -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-trophy me-1"></i>
                    Top 5 Pelapor
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Jumlah Tiket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topUsers as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->departemen ?? '-' }}</td>
                                <td>{{ $user->tickets_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Report -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-calendar me-1"></i>
            Laporan Bulanan
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" width="100%" height="40"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json($ticketsByStatus);
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: statusData.map(item => item.status),
            datasets: [{
                data: statusData.map(item => item.total),
                backgroundColor: ['#4e73df', '#f6c23e', '#1cc88a', '#858796']
            }]
        }
    });

    // Priority Chart
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    const priorityData = @json($ticketsByPriority);
    new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: priorityData.map(item => item.prioritas),
            datasets: [{
                label: 'Jumlah Tiket',
                data: priorityData.map(item => item.total),
                backgroundColor: ['#17a2b8', '#ffc107', '#dc3545']
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyTickets);
    const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => bulan[item.bulan - 1] + ' ' + item.tahun),
            datasets: [{
                label: 'Jumlah Tiket',
                data: monthlyData.map(item => item.total),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection