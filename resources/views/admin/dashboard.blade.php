@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard Admin</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Welcome, {{ Auth::user()->nama }}</li>
    </ol>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Tiket</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTickets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-ticket-detailed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Open</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $openTickets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-envelope-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inProgressTickets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-repeat fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Resolved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resolvedTickets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Closed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $closedTickets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-archive fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-pie-chart me-1"></i>
                    Tiket per Kategori
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-bar-chart me-1"></i>
                    Status Tiket
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tickets Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-table me-1"></i>
            Tiket Terbaru
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelapor</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTickets as $ticket)
                    <tr>
                        <td>#{{ $ticket->id_ticket }}</td>
                        <td>{{ $ticket->user->nama ?? 'N/A' }}</td>
                        <td>{{ $ticket->judul_masalah }}</td>
                        <td>{{ $ticket->kategori }}</td>
                        <td>
                            @if($ticket->prioritas == 'Low')
                                <span class="badge bg-info">Low</span>
                            @elseif($ticket->prioritas == 'Medium')
                                <span class="badge bg-warning">Medium</span>
                            @else
                                <span class="badge bg-danger">High</span>
                            @endif
                        </td>
                        <td>
                            @if($ticket->status == 'Open')
                                <span class="badge bg-primary">Open</span>
                            @elseif($ticket->status == 'In Progress')
                                <span class="badge bg-warning">In Progress</span>
                            @elseif($ticket->status == 'Resolved')
                                <span class="badge bg-success">Resolved</span>
                            @else
                                <span class="badge bg-secondary">Closed</span>
                            @endif
                        </td>
                        <td>{{ $ticket->tanggal_lapor->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket->id_ticket) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada tiket</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk chart kategori
    const categoryData = @json($ticketsByKategori);
    const categoryLabels = categoryData.map(item => item.kategori);
    const categoryCounts = categoryData.map(item => item.total);

    // Category Chart
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'pie',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryCounts,
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b',
                    '#858796'
                ]
            }]
        }
    });

    // Status Chart
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'bar',
        data: {
            labels: ['Open', 'In Progress', 'Resolved', 'Closed'],
            datasets: [{
                label: 'Jumlah Tiket',
                data: [{{ $openTickets }}, {{ $inProgressTickets }}, {{ $resolvedTickets }}, {{ $closedTickets }}],
                backgroundColor: [
                    '#4e73df',
                    '#f6c23e',
                    '#1cc88a',
                    '#858796'
                ]
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