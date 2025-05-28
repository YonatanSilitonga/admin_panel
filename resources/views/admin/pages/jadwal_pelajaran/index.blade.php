@extends('layouts.admin-layout')

@section('title', 'Manajemen Jadwal Pelajaran')

@section('styles')
@endsection

@section('content')
<div class="container-fluid">
    <link rel="stylesheet" href="{{ asset('css/jadwal.css') }}">

    <main class="main-content">
        <div class="isi">
            <!-- Header Judul -->
            <header class="judul">
                <h1 class="mb-3">Manajemen Jadwal Pelajaran</h1>
                <p class="mb-2">Kelola jadwal pelajaran untuk semua kelas dengan tampilan grid mingguan yang mudah dipahami</p>
            </header>

            <div class="data">
                <!-- Filter dan Kontrol -->
                    <div class="col-md-12 text-end">
                        <div class="d-flex justify-content-end align-items-end h-100">
                            <button type="button" class="btn btn-primary btn-lg" id="btnManageSchedule">
                                <i class="bi bi-calendar-week me-2"></i> Kelola Jadwal
                            </button>
                        </div>
                    </div>

                <!-- Tampilan Grid Jadwal Mingguan -->
                @if($kelasId)
                    @php
                        $selectedKelas = $kelasList->firstWhere('id_kelas', $kelasId);
                        $jadwalKelas = $jadwalByHariKelas;
                    @endphp
                    
      <div class="schedule-grid-container">
    <div class="schedule-grid-header">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-outline-light me-3" onclick="goBack()">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </button>
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week me-2"></i>
                    Jadwal Mingguan - {{ $selectedKelas->nama_kelas }}
                </h5>
            </div>
            <div class="schedule-legend">
                <span class="legend-item">
                    <span class="legend-dot filled"></span> Terisi
                </span>
                <span class="legend-item">
                    <span class="legend-dot empty"></span> Kosong
                </span>
            </div>
        </div>
    </div>


                        <div class="schedule-grid-wrapper">
                            <table class="schedule-grid-table">
                                <thead>
                                    <tr>
                                        <th class="session-header">Sesi</th>
                                        <th class="time-header">Waktu</th>
                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                                            <th class="day-header">{{ $day }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($sesi = 1; $sesi <= 6; $sesi++)
                                        @php
                                            $sesiData = [
                                                1 => ['start' => '07:45', 'end' => '08:30'],
                                                2 => ['start' => '08:30', 'end' => '09:15'],
                                                3 => ['start' => '09:15', 'end' => '10:00'],
                                                4 => ['start' => '10:15', 'end' => '11:00'],
                                                5 => ['start' => '11:00', 'end' => '11:45'],
                                                6 => ['start' => '11:45', 'end' => '12:30']
                                            ][$sesi];
                                        @endphp
                                        <tr>
                                            <td class="session-cell">
                                                <div class="session-number">{{ $sesi }}</div>
                                            </td>
                                            <td class="time-cell">
                                                <div class="time-range">
                                                    <span class="time-start">{{ $sesiData['start'] }}</span>
                                                    <span class="time-separator">-</span>
                                                    <span class="time-end">{{ $sesiData['end'] }}</span>
                                                </div>
                                            </td>
                                            @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $hari)
                                                @php
                                                    $jadwalHari = $jadwalKelas[$hari][$kelasId] ?? collect();
                                                    $jadwalSesi = $jadwalHari->first(function($jadwal) use ($sesiData) {
                                                        return $jadwal->waktu_mulai == $sesiData['start'].':00';
                                                    });
                                                @endphp
                                                <td class="schedule-grid-cell {{ $jadwalSesi ? 'filled' : 'empty' }}">
                                                    @if($jadwalSesi)
                                                        <div class="schedule-item">
                                                            <div class="subject-name">{{ $jadwalSesi->mataPelajaran->nama }}</div>
                                                            <div class="teacher-name">{{ $jadwalSesi->guru->nama_lengkap }}</div>
                                                            <div class="schedule-actions">
                                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                                        onclick="viewJadwal({{ $jadwalSesi->id_jadwal }})"
                                                                        data-bs-toggle="tooltip" title="Lihat Detail">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                        onclick="editJadwalQuick({{ $jadwalSesi->id_jadwal }})"
                                                                        data-bs-toggle="tooltip" title="Edit Cepat">
                                                                    <i class="bi bi-pencil"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="empty-slot">
                                                            <i class="bi bi-plus-circle"></i>
                                                            <span>Kosong</span>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- Tampilan Semua Kelas -->
                    <div class="all-classes-view">
                        <div class="row">
                            @foreach($kelasList->where('tahunAjaran.aktif', true) as $kelas)
                                @php
                                    $totalJadwal = 0;
                                    foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $hari) {
                                        $totalJadwal += count($jadwalByHariKelas[$hari][$kelas->id_kelas] ?? []);
                                    }
                                    $maxJadwal = 36; // 6 hari x 6 sesi
                                    $percentage = $maxJadwal > 0 ? round(($totalJadwal / $maxJadwal) * 100) : 0;
                                @endphp
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="class-card">
                                        <div class="class-card-header">
                                            <h6 class="class-name">{{ $kelas->nama_kelas }}</h6>
                                            <div class="class-progress">
                                                <div class="progress-circle-small">
                                                    <span class="progress-text">{{ $percentage }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="class-card-body">
                                            <div class="class-stats">
                                                <div class="stat-item">
                                                    <span class="stat-label">Total Jadwal</span>
                                                    <span class="stat-value">{{ $totalJadwal }}/{{ $maxJadwal }}</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-label">Tahun Ajaran</span>
                                                    <span class="stat-value">{{ $kelas->tahunAjaran->nama_tahun_ajaran ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="class-actions">
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        onclick="viewClassSchedule({{ $kelas->id_kelas }})">
                                                    <i class="bi bi-eye me-1"></i> Lihat Jadwal
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm" 
                                                        onclick="manageClassSchedule({{ $kelas->id_kelas }})">
                                                    <i class="bi bi-pencil me-1"></i> Kelola
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

<!-- Modal Detail Jadwal -->
<div class="modal fade" id="modalDetailJadwal" tabindex="-1" aria-labelledby="modalDetailJadwalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalDetailJadwalLabel">Detail Jadwal Pelajaran</h5>
                <div class="d-flex align-items-center">
                  
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Kelas</dt>
                    <dd class="col-sm-8" id="detail-kelas">-</dd>
                    
                    <dt class="col-sm-4">Hari</dt>
                    <dd class="col-sm-8" id="detail-hari">-</dd>
                    
                    <dt class="col-sm-4">Mata Pelajaran</dt>
                    <dd class="col-sm-8" id="detail-mapel">-</dd>
                    
                    <dt class="col-sm-4">Guru</dt>
                    <dd class="col-sm-8" id="detail-guru">-</dd>
                    
                    <dt class="col-sm-4">Waktu</dt>
                    <dd class="col-sm-8" id="detail-waktu">-</dd>
                    
                    <dt class="col-sm-4">Sesi</dt>
                    <dd class="col-sm-8" id="detail-sesi">-</dd>
                    
                    <dt class="col-sm-4">Tahun Ajaran</dt>
                    <dd class="col-sm-8" id="detail-tahun-ajaran">-</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" onclick="editFromDetail()">
                    <i class="bi bi-pencil me-1"></i> Edit Jadwal
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteFromDetail()">
                    <i class="bi bi-trash me-1"></i> Hapus Jadwal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Manajemen Jadwal -->
<div class="modal fade" id="modalJadwalMassal" tabindex="-1" aria-labelledby="modalJadwalMassalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <!-- Enhanced Header -->
            <div class="modal-header schedule-modal-header">
                <div class="d-flex align-items-center">
                    <div class="modal-icon">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <div class="modal-title-group">
                        <h5 class="modal-title mb-0" id="modalJadwalMassalLabel">Manajemen Jadwal Pelajaran</h5>
                        <small class="modal-subtitle">Kelola jadwal untuk seluruh minggu dalam satu kelas</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <form id="formJadwalMassal">
                <div class="modal-body schedule-modal-body">
                    
                    <!-- Control Panel Section -->
                    <div class="schedule-control-section">
                        <div class="row g-4">
                            <!-- Class Selection Card -->
                            <div class="col-lg-4">
                                <div class="control-card class-selection-card">
                                    <div class="control-card-header">
                                        <i class="bi bi-building"></i>
                                        <span>Pilih Kelas</span>
                                    </div>
                                    <div class="control-card-body">
                                        <select name="id_kelas" id="id_kelas_massal" class="form-select schedule-select" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($kelasList->where('tahunAjaran.aktif', true) as $kelas)
                                                <option value="{{ $kelas->id_kelas }}">
                                                     {{ $kelas->nama_kelas }} - {{ $kelas->tahunAjaran->nama_tahun_ajaran ?? 'Tidak ada tahun ajaran' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="selected-class-info" id="selectedClassInfo" style="display: none;">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            <span id="selectedClassName">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions Card -->
                            <div class="col-lg-4">
                                <div class="control-card actions-card">
                                    <div class="control-card-header">
                                        <i class="bi bi-tools"></i>
                                        <span>Aksi Cepat</span>
                                    </div>
                                    <div class="control-card-body">
                                        <div class="action-buttons">
                                            <button type="button" class="action-btn clear-btn" id="btnClearJadwalTable" disabled>
                                                <i class="bi bi-eraser"></i>
                                                <span>Bersihkan Semua</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics Card -->
                            <div class="col-lg-4">
                                <div class="control-card stats-card">
                                    <div class="control-card-header">
                                        <i class="bi bi-graph-up"></i>
                                        <span>Progress Jadwal</span>
                                    </div>
                                    <div class="control-card-body">
                                        <div class="stats-container">
                                            <div class="progress-circle">
                                                <div class="progress-value" id="progressPercent">0%</div>
                                            </div>
                                            <div class="stats-details">
                                                <div class="stat-item filled">
                                                    <span class="stat-number" id="filledCount">0</span>
                                                    <span class="stat-label">Terisi</span>
                                                </div>
                                                <div class="stat-item empty">
                                                    <span class="stat-number" id="emptyCount">36</span>
                                                    <span class="stat-label">Kosong</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Table Section -->
                    <div id="jadwalTableContainer" class="schedule-table-section" style="display: none;">
                        
                        <!-- Table Header -->
                        <div class="schedule-table-header">
                            <div class="table-title">
                                <h6><i class="bi bi-table me-2"></i>Jadwal Mingguan</h6>
                                <div class="class-badge" id="kelasNameBadge">-</div>
                            </div>
                            <div class="table-legend">
                                <div class="legend-item">
                                    <div class="legend-color filled"></div>
                                    <span>Terisi</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color empty"></div>
                                    <span>Kosong</span>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Schedule Table -->
                        <div class="schedule-table-wrapper">
                            <table class="schedule-table" id="jadwalMassalTable">
                                <thead>
                                    <tr class="table-header-row">
                                        <th class="session-column">
                                            <div class="header-content">
                                                <i class="bi bi-clock"></i>
                                                <span>Sesi</span>
                                            </div>
                                        </th>
                                        <th class="time-column">
                                            <div class="header-content">
                                                <i class="bi bi-stopwatch"></i>
                                                <span>Waktu</span>
                                            </div>
                                        </th>
                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                                            <th class="day-column">
                                                <div class="header-content">
                                                    <i class="bi bi-calendar-day"></i>
                                                    <span>{{ $day }}</span>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($sesi = 1; $sesi <= 6; $sesi++)
                                        @php
                                            $sesiData = [
                                                1 => ['start' => '07:45', 'end' => '08:30'],
                                                2 => ['start' => '08:30', 'end' => '09:15'],
                                                3 => ['start' => '09:15', 'end' => '10:00'],
                                                4 => ['start' => '10:15', 'end' => '11:00'],
                                                5 => ['start' => '11:00', 'end' => '11:45'],
                                                6 => ['start' => '11:45', 'end' => '12:30']
                                            ][$sesi];
                                        @endphp
                                        <tr class="schedule-row">
                                            <!-- Session Column -->
                                            <td class="session-cell">
                                                <div class="session-info">
                                                    <div class="session-number">
                                                        {{ $sesi }}
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Time Column -->
                                            <td class="time-cell">
                                                <div class="time-info">
                                                    <div class="time-start">{{ $sesiData['start'] }}</div>
                                                    <div class="time-separator">—</div>
                                                    <div class="time-end">{{ $sesiData['end'] }}</div>
                                                </div>
                                            </td>

                                            <!-- Day Columns -->
                                            @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $hari)
                                                <td class="schedule-cell">
                                                    <div class="jadwal-cell" data-hari="{{ $hari }}" data-sesi="{{ $sesi }}">
                                                        <!-- Hidden field for existing jadwal ID -->
                                                        <input type="hidden" name="jadwal[{{ $hari }}][{{ $sesi }}][id_jadwal]" 
                                                               class="existing-jadwal-id" value="">
                                                        
                                                        <!-- Subject Selection -->
                                                        <div class="input-group">
                                                            <select name="jadwal[{{ $hari }}][{{ $sesi }}][id_mata_pelajaran]" 
                                                                    class="form-select subject-select mapel-select" 
                                                                    data-hari="{{ $hari }}" 
                                                                    data-sesi="{{ $sesi }}">
                                                                <option value="">Pilih Mata Pelajaran</option>
                                                                @foreach($mataPelajaranList as $mapel)
                                                                    <option value="{{ $mapel->id_mata_pelajaran }}">{{ $mapel->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Teacher Selection -->
                                                        <div class="input-group mt-2">
                                                            <select name="jadwal[{{ $hari }}][{{ $sesi }}][id_guru]" 
                                                                    class="form-select teacher-select guru-select" 
                                                                    data-hari="{{ $hari }}" 
                                                                    data-sesi="{{ $sesi }}">
                                                                <option value="">Pilih Guru</option>
                                                            </select>
                                                        </div>

                                                        <!-- Clear Selection Button -->
                                                        <div class="clear-selection-btn" style="display: none;">
                                                            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2 btn-clear-cell" 
                                                                    data-hari="{{ $hari }}" data-sesi="{{ $sesi }}">
                                                                <i class="bi bi-x-circle me-1"></i> Hapus Pilihan
                                                            </button>
                                                        </div>

                                                        <!-- Action Buttons for Existing Schedule -->
                                                        <div class="existing-schedule-actions" style="display: none;">
                                                            <div class="btn-group mt-2" role="group">
                                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-schedule" 
                                                                        data-hari="{{ $hari }}" data-sesi="{{ $sesi }}">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <!-- Status Indicator -->
                                                        <div class="cell-status">
                                                            <div class="status-indicator complete" style="display: none;">
                                                                <i class="bi bi-check-circle-fill"></i>
                                                            </div>
                                                            <div class="status-indicator existing" style="display: none;">
                                                                <i class="bi bi-pencil-square text-warning"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Footer -->
                <div class="modal-footer schedule-modal-footer">
                    <div class="footer-info">
                        <div class="save-info" id="saveInfo" style="display: none;">
                            <i class="bi bi-info-circle"></i>
                            <span>Akan menyimpan <strong id="saveCount">0</strong> jadwal</span>
                        </div>
                    </div>
                    <div class="footer-actions">
                        <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i>
                            <span>Batal</span>
                        </button>
                        <button type="submit" class="btn btn-primary btn-save" id="btnSubmitMassal" disabled>
                            <i class="bi bi-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentDetailJadwalId = null;

$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Debug: Log data yang tersedia
    console.log('Kelas ID:', '{{ $kelasId }}');
    console.log('Tahun Ajaran ID:', '{{ $tahunAjaranId }}');
    console.log('Jadwal Data:', @json($jadwalByHariKelas));

    // Filter functionality
    $('#filterKelas, #filterTahunAjaran').on('change', function() {
        const kelas = $('#filterKelas').val();
        const tahunAjaran = $('#filterTahunAjaran').val();
        
        const params = new URLSearchParams();
        if (kelas) params.append('kelas', kelas);
        if (tahunAjaran) params.append('tahun_ajaran', tahunAjaran);
        
        window.location.href = '{{ route("jadwal-pelajaran.index") }}?' + params.toString();
    });

    // Manage schedule button - reset to default
    $('#btnManageSchedule').on('click', function() {
        $('#modalJadwalMassal').modal('show');
        // Don't auto-select class, let user choose
    });

    // Mass Schedule functionality - updated
    $('#id_kelas_massal').on('change', function() {
        const kelasId = $(this).val();
        if (kelasId) {
            $('#jadwalTableContainer').show();
            $('#btnSubmitMassal').prop('disabled', false);
            $('#btnClearJadwalTable').prop('disabled', false);
            
            // Update kelas name
            const kelasText = $(this).find('option:selected').text();
            $('#kelasNameBadge').text(kelasText);
            $('#selectedClassInfo').show();
            $('#selectedClassName').text(kelasText);
            
            // Clear existing data first
            clearJadwalTable();
            
            // Load existing jadwal with confirmation
            loadExistingJadwal(kelasId);
        } else {
            $('#jadwalTableContainer').hide();
            $('#btnSubmitMassal').prop('disabled', true);
            $('#btnClearJadwalTable').prop('disabled', true);
            $('#selectedClassInfo').hide();
        }
    });

    // Function to load existing jadwal
    function loadExistingJadwal(kelasId) {
        // Check if class has existing schedules first
        $.ajax({
            url: `/jadwal-pelajaran/kelas/${kelasId}`,
            method: 'GET',
            success: function(response) {
                console.log('Load jadwal response:', response);
                
                if (response.success && Object.keys(response.data).length > 0) {
                    // Has existing schedules, show confirmation
                    Swal.fire({
                        title: 'Mode Edit Jadwal',
                        html: `
                            <div class="text-start">
                                <p>Kelas ini sudah memiliki jadwal yang tersimpan.</p>
                                <p><strong>Anda akan masuk ke mode edit</strong> dimana Anda dapat:</p>
                                <ul>
                                    <li>Mengedit jadwal yang sudah ada</li>
                                    <li>Menambah jadwal baru</li>
                                    <li>Menghapus jadwal tertentu</li>
                                </ul>
                                <p class="text-muted">Lanjutkan untuk mengedit jadwal?</p>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Edit Jadwal',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#174d38'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            populateExistingSchedule(response.data);
                            showToast('info', 'Mode edit aktif - Anda dapat mengedit jadwal existing');
                        } else {
                            $('#id_kelas_massal').val('');
                            $('#jadwalTableContainer').hide();
                            $('#btnSubmitMassal').prop('disabled', true);
                            $('#selectedClassInfo').hide();
                        }
                    });
                } else {
                    // No existing schedules, proceed normally
                    populateExistingSchedule({});
                    showToast('info', 'Kelas belum memiliki jadwal - Anda dapat membuat jadwal baru');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading jadwal:', error);
                showToast('error', 'Gagal memuat jadwal: ' + error);
            }
        });
    }

    function populateExistingSchedule(jadwalData) {
        console.log('Populating schedule with data:', jadwalData);
        
        // Add loading overlay
        const loadingOverlay = `
            <div class="loading-overlay">
                <div class="spinner-container">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-text">Memuat jadwal...</div>
                </div>
            </div>
        `;
        
        $('#jadwalTableContainer').append(loadingOverlay);

        // Populate table with existing data
        for (const hari in jadwalData) {
            for (const sesi in jadwalData[hari]) {
                const data = jadwalData[hari][sesi];
                
                console.log(`Setting data for ${hari} sesi ${sesi}:`, data);
                
                // Set existing jadwal ID
                $(`.existing-jadwal-id[name="jadwal[${hari}][${sesi}][id_jadwal]"]`).val(data.id_jadwal || '');
                
                // Set mata pelajaran
                const mapelSelect = $(`.mapel-select[data-hari="${hari}"][data-sesi="${sesi}"]`);
                mapelSelect.val(data.id_mata_pelajaran);
                
                // Load and set guru
                loadGuruForMapel(data.id_mata_pelajaran, hari, sesi, data.id_guru);
                
                // Show existing schedule indicator
                const cell = $(`.jadwal-cell[data-hari="${hari}"][data-sesi="${sesi}"]`);
                cell.find('.existing-schedule-actions').show();
                cell.find('.status-indicator.existing').show();
                cell.find('.clear-selection-btn').show();
            }
        }
        
        updateCounters();
        
        // Remove loading overlay
        setTimeout(() => {
            $('.loading-overlay').remove();
        }, 500);
    }

    // Clear jadwal table
    $('#btnClearJadwalTable').on('click', function() {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin membersihkan semua jadwal?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bersihkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                clearJadwalTable();
                updateCounters();
                showToast('success', 'Jadwal berhasil dibersihkan');
            }
        });
    });

    // Delete individual schedule
    $(document).on('click', '.btn-delete-schedule', function() {
        const hari = $(this).data('hari');
        const sesi = $(this).data('sesi');
        const cell = $(`.jadwal-cell[data-hari="${hari}"][data-sesi="${sesi}"]`);
        
        Swal.fire({
            title: 'Hapus Jadwal',
            text: 'Apakah Anda yakin ingin menghapus jadwal ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Clear the cell
                cell.find('.existing-jadwal-id').val('');
                cell.find('.mapel-select').val('');
                cell.find('.guru-select').html('<option value="">Pilih Guru</option>');
                cell.find('.existing-schedule-actions').hide();
                cell.find('.status-indicator').hide();
                cell.find('.clear-selection-btn').hide();
                cell.removeClass('filled');
                
                updateCounters();
                showToast('success', 'Jadwal dihapus dari form');
            }
        });
    });

    // Clear selection from cell
    $(document).on('click', '.btn-clear-cell', function() {
        const hari = $(this).data('hari');
        const sesi = $(this).data('sesi');
        const cell = $(`.jadwal-cell[data-hari="${hari}"][data-sesi="${sesi}"]`);
    
        Swal.fire({
            title: 'Hapus Pilihan',
            text: 'Apakah Anda yakin ingin menghapus pilihan mata pelajaran dan guru?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Clear selections
                cell.find('.mapel-select').val('');
                cell.find('.guru-select').html('<option value="">Pilih Guru</option>');
                cell.find('.clear-selection-btn').hide();
                cell.find('.existing-schedule-actions').hide();
                cell.find('.status-indicator').hide();
                cell.removeClass('filled');
                
                updateCounters();
                showToast('success', 'Pilihan berhasil dihapus');
            }
        });
    });
    
    // Show/hide clear button when selections are made
    $(document).on('change', '.mapel-select, .guru-select', function() {
        const cell = $(this).closest('.jadwal-cell');
        const mapelVal = cell.find('.mapel-select').val();
        const guruVal = cell.find('.guru-select').val();
        
        if (mapelVal || guruVal) {
            cell.find('.clear-selection-btn').show();
        } else {
            cell.find('.clear-selection-btn').hide();
        }
    });

    function clearJadwalTable() {
        $('.existing-jadwal-id').val('');
        $('.mapel-select').val('');
        $('.guru-select').html('<option value="">Pilih Guru</option>');
        $('.jadwal-cell').removeClass('filled');
        $('.status-indicator').hide();
        $('.existing-schedule-actions').hide();
        $('.clear-selection-btn').hide();
    }

    function updateCounters() {
        let filledCount = 0;
        let totalCount = 36; // 6 sesi x 6 hari

        $('.jadwal-cell').each(function() {
            const mapelVal = $(this).find('.mapel-select').val();
            const guruVal = $(this).find('.guru-select').val();
            
            if (mapelVal && guruVal) {
                filledCount++;
                $(this).addClass('filled');
                $(this).find('.status-indicator.complete').show();
                $(this).find('.clear-selection-btn').show();
            } else {
                $(this).removeClass('filled');
                $(this).find('.status-indicator.complete').hide();
                $(this).find('.clear-selection-btn').hide();
            }
        });

        const percentage = Math.round((filledCount / totalCount) * 100);

        $('#filledCount').text(filledCount);
        $('#emptyCount').text(totalCount - filledCount);
        $('#progressPercent').text(percentage + '%');
        $('#saveCount').text(filledCount);
        
        // Update progress circle
        const progressCircle = $('.progress-circle');
        const degree = (percentage / 100) * 360;
        progressCircle.css('background', `conic-gradient(#28a745 ${degree}deg, #e9ecef ${degree}deg)`);
        
        // Show/hide save info
        if (filledCount > 0) {
            $('#saveInfo').show();
        } else {
            $('#saveInfo').hide();
        }
    }

    // Handle mata pelajaran change in mass schedule
    $(document).on('change', '.mapel-select', function() {
        const mataPelajaranId = $(this).val();
        const hari = $(this).data('hari');
        const sesi = $(this).data('sesi');
        
        loadGuruForMapel(mataPelajaranId, hari, sesi);
        updateCounters();
    });

    // Handle guru change
    $(document).on('change', '.guru-select', function() {
        updateCounters();
    });

    function loadGuruForMapel(mataPelajaranId, hari, sesi, selectedGuruId = null) {
        const guruSelect = $(`.guru-select[data-hari="${hari}"][data-sesi="${sesi}"]`);
        
        if (mataPelajaranId) {
            guruSelect.html('<option value="">Loading...</option>');
            
            $.ajax({
                url: `/jadwal-pelajaran/guru-by-mapel/${mataPelajaranId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        let options = '<option value="">Pilih Guru</option>';
                        response.data.forEach(function(guru) {
                            const selected = selectedGuruId && guru.id_guru == selectedGuruId ? 'selected' : '';
                            options += `<option value="${guru.id_guru}" ${selected}>${guru.nama_lengkap}</option>`;
                        });
                        guruSelect.html(options);
                        
                        // Update counters after loading guru
                        setTimeout(updateCounters, 100);
                    } else {
                        guruSelect.html('<option value="">Tidak ada guru tersedia</option>');
                    }
                },
                error: function() {
                    guruSelect.html('<option value="">Error loading guru</option>');
                }
            });
        } else {
            guruSelect.html('<option value="">Pilih Guru</option>');
        }
    }

    // Submit jadwal massal
    $('#formJadwalMassal').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            id_kelas: $('#id_kelas_massal').val(),
            jadwal: {},
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        // Organize form data
        for (let [key, value] of formData) {
            if (key.startsWith('jadwal[')) {
                // Parse jadwal[hari][sesi][field] format
                const matches = key.match(/jadwal\[(\w+)\]\[(\d+)\]\[(\w+)\]/);
                if (matches) {
                    const [, hari, sesi, field] = matches;
                    
                    if (!data.jadwal[hari]) data.jadwal[hari] = {};
                    if (!data.jadwal[hari][sesi]) data.jadwal[hari][sesi] = {};
                    
                    data.jadwal[hari][sesi][field] = value;
                }
            }
        }
        
        // Count filled entries
        let filledCount = 0;
        for (const hari in data.jadwal) {
            for (const sesi in data.jadwal[hari]) {
                const entry = data.jadwal[hari][sesi];
                if (entry.id_mata_pelajaran && entry.id_guru) {
                    filledCount++;
                }
            }
        }
        
        if (filledCount === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Tidak ada jadwal yang diisi!'
            });
            return;
        }
        
        // Confirm submission
        Swal.fire({
            title: 'Konfirmasi Penyimpanan',
            html: `
                <div class="text-start">
                    <p>Akan menyimpan <strong>${filledCount}</strong> jadwal.</p>
                    <p class="text-muted">Jadwal yang sudah ada akan diperbarui. Lanjutkan?</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#174d38'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                $('#btnSubmitMassal').prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i><span>Menyimpan...</span>');
                
                $.ajax({
                    url: '/jadwal-pelajaran/store-massal',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(() => {
                                $('#modalJadwalMassal').modal('hide');
                                location.reload();
                            });
                        } else {
                            if (response.conflicts) {
                                let conflictMessage = 'Terdapat konflik jadwal:\n';
                                response.conflicts.forEach(function(conflict) {
                                    conflictMessage += '- ' + conflict + '\n';
                                });
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Konflik Jadwal',
                                    text: conflictMessage
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    },
                    complete: function() {
                        $('#btnSubmitMassal').prop('disabled', false).html('<i class="bi bi-save"></i><span>Simpan Perubahan</span>');
                    }
                });
            }
        });
    });

    // Reset modal when closed - ensure clean state
    $('#modalJadwalMassal').on('hidden.bs.modal', function() {
        $('#formJadwalMassal')[0].reset();
        $('#id_kelas_massal').val(''); // Explicitly reset to empty
        $('#jadwalTableContainer').hide();
        $('#btnSubmitMassal').prop('disabled', true);
        $('#btnClearJadwalTable').prop('disabled', true);
        $('#selectedClassInfo').hide();
        clearJadwalTable();
        updateCounters();
    });

    // Toast notification function
    function showToast(type, message) {
        const bgClass = type === 'success' ? 'bg-success' : type === 'info' ? 'bg-info' : 'bg-danger';
        const icon = type === 'success' ? 'check-circle' : type === 'info' ? 'info-circle' : 'exclamation-triangle';
        
        const toast = `
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi bi-${icon} me-2"></i> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(toast);
        $('.toast').toast('show');
        
        setTimeout(() => {
            $('.toast').remove();
        }, 3000);
    }
});

// Go back function
function goBack() {
    if (document.referrer) {
        window.history.back();
    } else {
        window.location.href = '{{ route("admin.beranda") }}';
    }
}

// Global functions for grid actions
function viewClassSchedule(kelasId) {
    window.location.href = `{{ route("jadwal-pelajaran.index") }}?kelas=${kelasId}`;
}

function manageClassSchedule(kelasId) {
    $('#id_kelas_massal').val(kelasId).trigger('change');
    $('#modalJadwalMassal').modal('show');
}

function editJadwalQuick(id) {
    // Quick edit - open the management modal with the specific class
    $.ajax({
        url: `/jadwal-pelajaran/${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const jadwal = response.data;
                $('#id_kelas_massal').val(jadwal.id_kelas).trigger('change');
                $('#modalJadwalMassal').modal('show');
            }
        },
        error: function() {
            showToast('error', 'Gagal memuat data jadwal');
        }
    });
}

function viewJadwal(id) {
    currentDetailJadwalId = id;
    $.ajax({
        url: `/jadwal-pelajaran/${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const jadwal = response.data;
                
                $('#detail-kelas').text(`${jadwal.kelas.tingkat} ${jadwal.kelas.nama_kelas}`);
                $('#detail-hari').text(jadwal.hari.charAt(0).toUpperCase() + jadwal.hari.slice(1));
                $('#detail-mapel').text(jadwal.mata_pelajaran.nama);
                $('#detail-guru').text(jadwal.guru.nama_lengkap);
                
                // Format time and sessions
                if (response.selected_sessions && response.selected_sessions.length > 0) {
                    const sessions = response.selected_sessions;
                    const firstSession = Math.min(...sessions);
                    const lastSession = Math.max(...sessions);
                    
                    $('#detail-sesi').text(firstSession === lastSession ? `Sesi ${firstSession}` : `Sesi ${firstSession} - ${lastSession}`);
                    
                    // Calculate time range
                    const sesiWaktu = {
                        1: {start: '07:45', end: '08:30'},
                        2: {start: '08:30', end: '09:15'},
                        3: {start: '09:15', end: '10:00'},
                        4: {start: '10:15', end: '11:00'},
                        5: {start: '11:00', end: '11:45'},
                        6: {start: '11:45', end: '12:30'}
                    };
                    
                    const startTime = sesiWaktu[firstSession].start;
                    const endTime = sesiWaktu[lastSession].end;
                    $('#detail-waktu').text(`${startTime} - ${endTime}`);
                } else {
                    $('#detail-sesi').text('-');
                    $('#detail-waktu').text('-');
                }
                
                $('#detail-tahun-ajaran').text(jadwal.tahun_ajaran ? jadwal.tahun_ajaran.nama_tahun_ajaran : '-');
                
                $('#modalDetailJadwal').modal('show');
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat data jadwal'
            });
        }
    });
}

function editFromDetail() {
    if (currentDetailJadwalId) {
        $('#modalDetailJadwal').modal('hide');
        editJadwalQuick(currentDetailJadwalId);
    }
}

function deleteFromDetail() {
    if (currentDetailJadwalId) {
        deleteJadwal(currentDetailJadwalId);
    }
}

function deleteJadwal(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus jadwal ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/jadwal-pelajaran/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            $('#modalDetailJadwal').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal menghapus jadwal'
                    });
                }
            });
        }
    });
}
</script>
@endsection
