@extends('layouts.admin-layout')

@section('title', 'Edit Jadwal Pelajaran')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <!-- Header Judul -->
            <header class="judul">
                <h1 class="mb-3">
                    <a href="{{ route('jadwal-pelajaran.index') }}" class="text-decoration-none text-success">
                        Manajemen Jadwal Pelajaran
                    </a>
                    <span class="fs-5 text-muted">/ Edit Jadwal</span>
                </h1>
                <p class="mb-2">Staff dapat mengubah informasi jadwal pelajaran dengan lebih efisien</p>
            </header>

            <div class="data">
                <!-- Form Edit Jadwal -->
                <form action="{{ route('jadwal-pelajaran.update', $jadwal->id_jadwal) }}" method="POST" id="formEditJadwal">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <!-- Kelas -->
                        <div class="col-md-6 mb-3">
                            <label for="id_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select name="id_kelas" id="id_kelas" class="form-select @error('id_kelas') is-invalid @enderror" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas', $jadwal->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}>
                                     {{ $kelas->nama_kelas }} - {{ $kelas->tahunAjaran->nama_tahun_ajaran ?? 'Tidak ada tahun ajaran' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hari -->
                        <div class="col-md-6 mb-3">
                            <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                            <select name="hari" id="hari" class="form-select @error('hari') is-invalid @enderror" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach($hariList as $hari)
                                    <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>
                                        {{ ucfirst($hari) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hari')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="col-md-6 mb-3">
                            <label for="id_mata_pelajaran" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="id_mata_pelajaran" id="id_mata_pelajaran" class="form-select @error('id_mata_pelajaran') is-invalid @enderror" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mataPelajaranList as $mapel)
                                    <option value="{{ $mapel->id_mata_pelajaran }}" {{ old('id_mata_pelajaran', $jadwal->id_mata_pelajaran) == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
                                        {{ $mapel->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_mata_pelajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Guru -->
                        <div class="col-md-6 mb-3">
                            <label for="id_guru" class="form-label">Guru <span class="text-danger">*</span></label>
                            <select name="id_guru" id="id_guru" class="form-select @error('id_guru') is-invalid @enderror" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($guruList as $guru)
                                    <option value="{{ $guru->id_guru }}" {{ old('id_guru', $jadwal->id_guru) == $guru->id_guru ? 'selected' : '' }}>
                                        {{ $guru->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_guru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sesi Waktu -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Sesi Waktu <span class="text-danger">*</span></label>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center">Pilih</th>
                                            <th>Sesi</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Menentukan sesi yang sudah dipilih sebelumnya
                                            $selectedSessions = [];
                                            $sessionStart = 0;
                                            $sessionEnd = 0;
                                            
                                            // Cari sesi mulai berdasarkan waktu mulai
                                            foreach($sesiList as $sesi) {
                                                if(substr($jadwal->waktu_mulai, 0, 5) == substr($sesi['waktu_mulai'], 0, 5)) {
                                                    $sessionStart = $sesi['sesi'];
                                                    break;
                                                }
                                            }
                                            
                                            // Cari sesi selesai berdasarkan waktu selesai
                                            foreach($sesiList as $sesi) {
                                                if(substr($jadwal->waktu_selesai, 0, 5) == substr($sesi['waktu_selesai'], 0, 5)) {
                                                    $sessionEnd = $sesi['sesi'];
                                                    break;
                                                }
                                            }
                                            
                                            // Buat array sesi yang dipilih
                                            if($sessionStart > 0 && $sessionEnd > 0) {
                                                for($i = $sessionStart; $i <= $sessionEnd; $i++) {
                                                    $selectedSessions[] = $i;
                                                }
                                            }
                                        @endphp
                                        
                                        @foreach($sesiList as $sesi)
                                        <tr>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input sesi-checkbox" type="checkbox" 
                                                        name="sesi[]" value="{{ $sesi['sesi'] }}" 
                                                        id="sesi{{ $sesi['sesi'] }}" 
                                                        {{ in_array($sesi['sesi'], old('sesi', $selectedSessions)) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>Sesi {{ $sesi['sesi'] }}</td>
                                            <td>{{ substr($sesi['waktu_mulai'], 0, 5) }} - {{ substr($sesi['waktu_selesai'], 0, 5) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @error('sesi')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Informasi Jadwal -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i> <strong>Informasi Jadwal:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Jadwal dimulai pukul 07:45 pagi</li>
                            <li>Setiap sesi pelajaran berdurasi 45 menit</li>
                            <li>Istirahat 15 menit setelah sesi ketiga (10:00 - 10:15)</li>
                            <li>Anda dapat memilih beberapa sesi berurutan untuk satu mata pelajaran</li>
                            <li>Sistem akan otomatis memeriksa konflik jadwal</li>
                        </ul>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="text-end mt-4">
                        <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success" id="btnSubmit">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
