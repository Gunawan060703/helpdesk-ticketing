@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Buat Tiket Baru</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Tiket</a></li>
        <li class="breadcrumb-item active">Buat Tiket</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-plus-circle me-1"></i>
            Form Tiket Baru
        </div>
        <div class="card-body">
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="judul_masalah" class="form-label">Judul Masalah <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('judul_masalah') is-invalid @enderror" 
                           id="judul_masalah" name="judul_masalah" value="{{ old('judul_masalah') }}" 
                           placeholder="Contoh: Komputer tidak bisa menyala" required>
                    @error('judul_masalah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" 
                                id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->nama_kategori }}" {{ old('kategori') == $kat->nama_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="prioritas" class="form-label">Prioritas <span class="text-danger">*</span></label>
                        <select class="form-select @error('prioritas') is-invalid @enderror" 
                                id="prioritas" name="prioritas" required>
                            <option value="">Pilih Prioritas</option>
                            <option value="Low" {{ old('prioritas') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('prioritas') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('prioritas') == 'High' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('prioritas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Masalah <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="5" 
                              placeholder="Jelaskan masalah secara detail..." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Kirim Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection