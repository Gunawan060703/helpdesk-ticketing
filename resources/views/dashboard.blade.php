@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Welcome, {{ Auth::user()->nama }}</li>
    </ol>

    <div class="row">
        @if(Auth::user()->isAdmin())
            <!-- Admin Dashboard -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Total Tiket</h6>
                                <h2 class="mb-0">{{ $totalTickets ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-ticket-detailed fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Open</h6>
                                <h2 class="mb-0">{{ $openTickets ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-envelope-open fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">In Progress</h6>
                                <h2 class="mb-0">{{ $inProgressTickets ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-arrow-repeat fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Resolved</h6>
                                <h2 class="mb-0">{{ $resolvedTickets ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-check-circle fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- User Dashboard -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Tiket Saya</h6>
                                <h2 class="mb-0">{{ $myTickets ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-ticket-detailed fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Open</h6>
                                <h2 class="mb-0">{{ $openTickets ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-envelope-open fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">In Progress</h6>
                                <h2 class="mb-0">{{ $inProgressTickets ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-arrow-repeat fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Resolved</h6>
                                <h2 class="mb-0">{{ $resolvedTickets ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-check-circle fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Tickets -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Tiket Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Tiket</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTickets ?? [] as $ticket)
                        <tr>
                            <td>#{{ $ticket->id_ticket }}</td>
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
                            <td colspan="7" class="text-center">Belum ada tiket</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection