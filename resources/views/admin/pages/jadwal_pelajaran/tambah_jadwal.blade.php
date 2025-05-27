@extends('layouts.admin-layout')

@section('title', 'Tambah Jadwal Pelajaran')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <header class="judul">
                <h1 class="mb-3">
                    <a href="{{ route('jadwal-pelajaran.index') }}" class="text-decoration-none text-success">
                        Manajemen Jadwal Pelajaran
                    </a>
                    <span class="fs-5 text-muted">/ Tambah Jadwal</span>
                </h1>
                <p class="mb-2">Staff dapat menambahkan jadwal pelajaran baru dengan lebih efisien</p>
            </header>

            <div class="data">
                <!-- Tabs untuk metode pembuatan jadwal -->
                <ul class="nav nav-tabs mb-4" id="jadwalTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="kelas-tab" data-bs-toggle="tab" data-bs-target="#kelas-content" 
                                type="button" role="tab" aria-controls="kelas-content" aria-selected="true">
                            Per Kelas
                        </button>
                    </li>
                    <!-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="massal-tab" data-bs-toggle="tab" data-bs-target="#massal-content" 
                                type="button" role="tab" aria-controls="massal-content" aria-selected="false">
                            Pembuatan Massal
                        </button>
                    </li> -->
                </ul>

                <div class="tab-content" id="jadwalTabContent">
                    <!-- Tab Per Kelas -->
                    <div class="tab-pane fade show active" id="kelas-content" role="tabpanel" aria-labelledby="kelas-tab">
                        <form action="{{ route('jadwal-pelajaran.store') }}" method="POST" id="formTambahJadwalKelas">
                            @csrf
                            <input type="hidden" name="mode" value="kelas">

                            <div class="row mb-4">
                                <!-- Kelas -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select name="id_kelas" id="id_kelas" class="form-select @error('id_kelas') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelasList as $kelas)
                                            <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                                {{ $kelas->tingkat }} {{ $kelas->nama_kelas }} - {{ $kelas->tahunAjaran->nama_tahun_ajaran ?? 'Tidak ada tahun ajaran' }}
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
                                            <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>
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
                                            <option value="{{ $mapel->id_mata_pelajaran }}" {{ old('id_mata_pelajaran') == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
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
                                            <option value="{{ $guru->id_guru }}" {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}>
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
                                                @foreach($sesiList as $sesi)
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input sesi-checkbox" type="checkbox" 
                                                                name="sesi[]" value="{{ $sesi['sesi'] }}" 
                                                                id="sesi{{ $sesi['sesi'] }}" 
                                                                {{ in_array($sesi['sesi'], old('sesi', [])) ? 'checked' : '' }}>
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

                                <!-- Status -->
                                <!-- <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> -->

                                <!-- Tahun Ajaran -->
                                <!-- <div class="col-md-6 mb-3">
                                    <label for="id_tahun_ajaran" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                                    <select name="id_tahun_ajaran" id="id_tahun_ajaran" class="form-select @error('id_tahun_ajaran') is-invalid @enderror" required>
                                        <option value="">Pilih Tahun Ajaran</option>
                                        @foreach($tahunAjaranList as $ta)
                                            <option value="{{ $ta->id_tahun_ajaran }}" 
                                                {{ old('id_tahun_ajaran', $tahunAjaranAktif->id_tahun_ajaran ?? '') == $ta->id_tahun_ajaran ? 'selected' : '' }}
                                                {{ $ta->aktif ? 'class=fw-bold text-success' : '' }}>
                                                {{ $ta->nama_tahun_ajaran }} {{ $ta->aktif ? '(Aktif)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_tahun_ajaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> -->
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
                                <button type="submit" class="btn btn-success" id="btnSubmitKelas">
                                    <i class="bi bi-save me-1"></i> Simpan Jadwal
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Pembuatan Massal -->
                    <div class="tab-pane fade" id="massal-content" role="tabpanel" aria-labelledby="massal-tab">
                        <div class="alert alert-primary mb-4">
                            <i class="bi bi-info-circle me-2"></i> <strong>Pembuatan Jadwal Massal</strong>
                            <p class="mb-0 mt-2">Fitur ini memungkinkan Anda membuat jadwal untuk satu kelas sekaligus dengan menggunakan tabel jadwal mingguan. Pilih mata pelajaran dan guru untuk setiap sesi dan hari yang diinginkan.</p>
                        </div>
                        
                        <form action="{{ route('jadwal-pelajaran.store-massal') }}" method="POST" id="formTambahJadwalMassal">
                            @csrf
                            <input type="hidden" name="mode" value="massal">
                            
                            <div class="row mb-4">
                                <!-- Pilih Kelas -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_kelas_massal" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select name="id_kelas" id="id_kelas_massal" class="form-select @error('id_kelas') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelasList as $kelas)
                                            <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                                {{ $kelas->tingkat }} {{ $kelas->nama_kelas }} - {{ $kelas->tahunAjaran->nama_tahun_ajaran ?? 'Tidak ada tahun ajaran' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Tombol untuk memuat tabel jadwal -->
                                <div class="col-md-6 mb-3 d-flex align-items-end">
                                    <button type="button" id="btnLoadJadwalTable" class="btn btn-primary">
                                        <i class="bi bi-table me-1"></i> Muat Tabel Jadwal
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Tabel Jadwal Mingguan -->
                            <div id="jadwal_table_container" class="mb-4" style="display: none;">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">Jadwal Mingguan <span id="kelas_name"></span></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Sesi</th>
                                                        <th>Waktu</th>
                                                        @foreach($hariList as $hari)
                                                            <th>{{ ucfirst($hari) }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sesiList as $sesi)
                                                        <tr>
                                                            <td>Sesi {{ $sesi['sesi'] }}</td>
                                                            <td>{{ substr($sesi['waktu_mulai'], 0, 5) }} - {{ substr($sesi['waktu_selesai'], 0, 5) }}</td>
                                                            @foreach($hariList as $hari)
                                                                <td>
                                                                    <div class="jadwal-cell" data-sesi="{{ $sesi['sesi'] }}" data-hari="{{ $hari }}">
                                                                        <select name="jadwal[{{ $hari }}][{{ $sesi['sesi'] }}][id_mata_pelajaran]" class="form-select form-select-sm mb-1 mapel-select">
                                                                            <option value="">-- Mapel --</option>
                                                                            @foreach($mataPelajaranList as $mapel)
                                                                                <option value="{{ $mapel->id_mata_pelajaran }}">{{ $mapel->nama }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <select name="jadwal[{{ $hari }}][{{ $sesi['sesi'] }}][id_guru]" class="form-select form-select-sm guru-select">
                                                                            <option value="">-- Guru --</option>
                                                                            @foreach($guruList as $guru)
                                                                                <option value="{{ $guru->id_guru }}">{{ $guru->nama_lengkap }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="alert alert-info mt-3">
                                            <i class="bi bi-info-circle me-2"></i> <strong>Petunjuk:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Pilih mata pelajaran dan guru untuk setiap sesi dan hari yang diinginkan</li>
                                                <li>Sel kosong tidak akan dibuat jadwalnya</li>
                                                <li>Sistem akan memeriksa konflik jadwal sebelum menyimpan</li>
                                                <li>Jadwal yang sudah ada untuk kelas ini akan tetap dipertahankan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tombol Submit -->
                                <div class="text-end mt-4">
                                    <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success" id="btnSubmitMassal">
                                        <i class="bi bi-save me-1"></i> Simpan Semua Jadwal
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
