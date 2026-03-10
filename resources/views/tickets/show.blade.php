@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Tiket #{{ $ticket->id_ticket }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Tiket</a></li>
        <li class="breadcrumb-item active">Detail Tiket</li>
    </ol>

    <div class="row">
        <div class="col-md-8">
            <!-- Informasi Tiket -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-info-circle me-1"></i>
                        Informasi Tiket
                    </div>
                    @if(Auth::user()->isAdmin())
                    <div>
                        <a href="{{ route('tickets.edit', $ticket->id_ticket) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <h4>{{ $ticket->judul_masalah }}</h4>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="150">ID Tiket</td>
                                    <td>: <strong>#{{ $ticket->id_ticket }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Kategori</td>
                                    <td>: {{ $ticket->kategori }}</td>
                                </tr>
                                <tr>
                                    <td>Prioritas</td>
                                    <td>: 
                                        @if($ticket->prioritas == 'Low')
                                            <span class="badge bg-info">Low</span>
                                        @elseif($ticket->prioritas == 'Medium')
                                            <span class="badge bg-warning">Medium</span>
                                        @else
                                            <span class="badge bg-danger">High</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>: 
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
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="150">Pelapor</td>
                                    <td>: {{ $ticket->user->nama }}</td>
                                </tr>
                                <tr>
                                    <td>Departemen</td>
                                    <td>: {{ $ticket->user->departemen ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Lapor</td>
                                    <td>: {{ $ticket->tanggal_lapor->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>Terakhir Update</td>
                                    <td>: {{ $ticket->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h6>Deskripsi Masalah:</h6>
                        <div class="p-3 bg-light rounded">
                            {{ $ticket->deskripsi }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Respon Tiket -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-chat-dots me-1"></i>
                    Respon ({{ $ticket->responses->count() }})
                </div>
                <div class="card-body">
                    @forelse($ticket->responses as $response)
                        <div class="mb-3 p-3 {{ $response->admin->id_user == Auth::id() ? 'bg-light' : 'bg-white' }} border rounded">
                            <div class="d-flex justify-content-between">
                                <strong>
                                    <i class="bi bi-person-circle"></i> 
                                    {{ $response->admin->nama }}
                                    @if($response->admin->isAdmin())
                                        <span class="badge bg-danger">Admin</span>
                                    @endif
                                </strong>
                                <small class="text-muted">{{ $response->tanggal_respon->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="mt-2 mb-0">{{ $response->pesan_respon }}</p>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada respon</p>
                    @endforelse

                    @if($ticket->status != 'Closed')
                    <form action="{{ route('tickets.add-response', $ticket->id_ticket) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="pesan_respon" class="form-label">Tambah Respon</label>
                            <textarea class="form-control" id="pesan_respon" name="pesan_respon" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Kirim Respon
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Status Update (untuk admin) -->
            @if(Auth::user()->isAdmin())
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-arrow-repeat me-1"></i>
                    Update Status
                </div>
                <div class="card-body">
                    <form action="{{ route('tickets.update-status', $ticket->id_ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Tiket</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-arrow-repeat"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Riwayat Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-clock-history me-1"></i>
                    Riwayat Status
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @forelse($ticket->statusLogs as $log)
                        <div class="mb-2 pb-2 border-bottom">
                            <small>
                                <strong>{{ $log->status_lama }} → {{ $log->status_baru }}</strong><br>
                                <span class="text-muted">
                                    <i class="bi bi-clock"></i> {{ $log->tanggal_update->format('d/m/Y H:i') }}
                                </span>
                            </small>
                        </div>
                    @empty
                        <p class="text-muted text-center">Belum ada riwayat status</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection