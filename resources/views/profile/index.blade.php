@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Profil Saya</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Profil</li>
    </ol>

    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-person-circle me-1"></i>
                    Foto Profil
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="display-1 text-primary">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    </div>
                    <h4>{{ $user->nama }}</h4>
                    <p class="text-muted">
                        @if($user->role == 'admin')
                            <span class="badge bg-danger">Administrator</span>
                        @else
                            <span class="badge bg-info">Staff</span>
                        @endif
                    </p>
                    <p>{{ $user->email }}</p>
                    <p class="text-muted">{{ $user->departemen ?? 'Belum diisi' }}</p>
                    <p class="text-muted small">
                        <i class="bi bi-calendar"></i> 
                        Member sejak: {{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit Profil
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="departemen" class="form-label">Departemen</label>
                            <input type="text" class="form-control @error('departemen') is-invalid @enderror" 
                                   id="departemen" name="departemen" value="{{ old('departemen', $user->departemen) }}">
                            @error('departemen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Ubah Password (Kosongkan jika tidak ingin mengubah)</h5>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" name="new_password">
                            <small class="text-muted">Minimal 8 karakter</small>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" 
                                   id="new_password_confirmation" name="new_password_confirmation">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Tiket -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-ticket-detailed me-1"></i>
                    Statistik Tiket Saya
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Tiket</h5>
                                    <h2>{{ $user->tickets()->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Open</h5>
                                    <h2>{{ $user->tickets()->where('status', 'Open')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">In Progress</h5>
                                    <h2>{{ $user->tickets()->where('status', 'In Progress')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Resolved</h5>
                                    <h2>{{ $user->tickets()->where('status', 'Resolved')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiket Terbaru -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-clock-history me-1"></i>
                    Tiket Terbaru Saya
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
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
                            @forelse($user->tickets()->latest('tanggal_lapor')->take(5)->get() as $ticket)
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
                                        <i class="bi bi-eye"></i> Detail
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
</div>
@endsection