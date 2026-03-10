@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Tiket</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Tiket</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-ticket-detailed me-1"></i>
                Daftar Tiket
            </div>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Buat Tiket Baru
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        @if(Auth::user()->isAdmin())
                        <th>Pelapor</th>
                        @endif
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td>#{{ $ticket->id_ticket }}</td>
                        @if(Auth::user()->isAdmin())
                        <td>{{ $ticket->user->nama ?? 'N/A' }}</td>
                        @endif
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
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            @if(Auth::user()->isAdmin())
                            <a href="{{ route('tickets.edit', $ticket->id_ticket) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->isAdmin() ? 8 : 7 }}" class="text-center">
                            Belum ada tiket
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection