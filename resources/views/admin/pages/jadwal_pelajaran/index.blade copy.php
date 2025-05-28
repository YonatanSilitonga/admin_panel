@extends('layouts.admin-layout')

@section('title', 'Manajemen Jadwal Pelajaran')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <!-- Header Judul -->
            <header class="judul">
                <h1 class="mb-3">Manajemen Jadwal Pelajaran</h1>
                <p class="mb-2">Staff dapat menambah, melihat, dan mengubah data jadwal pelajaran untuk semua kelas</p>
            </header>

            <div class="data">
                <!-- Filter dan Tombol Tambah -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select id="filterKelas" class="form-select">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id_kelas }}" {{ $kelasId == $kelas->id_kelas ? 'selected' : '' }}>
                                             {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="filterTahunAjaran" class="form-select">
                                    @foreach($tahunAjaranList as $ta)
                                        <option value="{{ $ta->id_tahun_ajaran }}" {{ $tahunAjaranId == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                            {{ $ta->nama_tahun_ajaran }} {{ $ta->aktif ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalJadwalMassal">
                                <i class="bi bi-calendar-week me-1"></i> Manajemen Jadwal
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Tampilan Jadwal -->
                <div class="table-responsive">
                    @if($jadwalList->isEmpty())
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i> Tidak ada jadwal pelajaran yang ditemukan.
                        </div>
                    @else
                        <!-- Tabs untuk hari -->
                        <ul class="nav nav-tabs mb-4" id="jadwalTab" role="tablist">
                            @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $index => $hariTab)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                            id="{{ $hariTab }}-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#{{ $hariTab }}-content" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="{{ $hariTab }}-content" 
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                        {{ ucfirst($hariTab) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="jadwalTabContent">
                            @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $index => $hariTab)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                                     id="{{ $hariTab }}-content" 
                                     role="tabpanel" 
                                     aria-labelledby="{{ $hariTab }}-tab">
                                    
                                    @php
                                        $hasJadwal = false;
                                        foreach($kelasList as $kelas) {
                                            if(count($jadwalByHariKelas[$hariTab][$kelas->id_kelas]) > 0) {
                                                $hasJadwal = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if(!$hasJadwal)
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i> Tidak ada jadwal untuk hari {{ ucfirst($hariTab) }}.
                                        </div>
                                    @else
                                        <div class="accordion" id="accordion{{ ucfirst($hariTab) }}">
                                            @foreach($kelasList as $kelas)
                                                @if(count($jadwalByHariKelas[$hariTab][$kelas->id_kelas]) > 0)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading{{ $hariTab }}{{ $kelas->id_kelas }}">
                                                            <button class="accordion-button collapsed" type="button" 
                                                                    data-bs-toggle="collapse" 
                                                                    data-bs-target="#collapse{{ $hariTab }}{{ $kelas->id_kelas }}" 
                                                                    aria-expanded="false" 
                                                                    aria-controls="collapse{{ $hariTab }}{{ $kelas->id_kelas }}">
                                                                <strong>Kelas  {{ $kelas->nama_kelas }}</strong>
                                                                <span class="badge bg-primary ms-2">{{ count($jadwalByHariKelas[$hariTab][$kelas->id_kelas]) }} Jadwal</span>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse{{ $hariTab }}{{ $kelas->id_kelas }}" 
                                                             class="accordion-collapse collapse" 
                                                             aria-labelledby="heading{{ $hariTab }}{{ $kelas->id_kelas }}" 
                                                             data-bs-parent="#accordion{{ ucfirst($hariTab) }}">
                                                            <div class="accordion-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped table-bordered table-sm">
                                                                        <thead class="bg-success text-white">
                                                                            <tr>
                                                                                <th>No</th>
                                                                                <th>Waktu</th>
                                                                                <th>Mata Pelajaran</th>
                                                                                <th>Guru</th>
                                                                                <th class="text-center">Aksi</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $groupedJadwal = [];
                                                                                foreach($jadwalByHariKelas[$hariTab][$kelas->id_kelas] as $jadwal) {
                                                                                    $key = $jadwal->id_mata_pelajaran . '_' . $jadwal->id_guru . '_' . $jadwal->hari;
                                                                                    if (!isset($groupedJadwal[$key])) {
                                                                                        $groupedJadwal[$key] = [
                                                                                            'jadwal' => $jadwal,
                                                                                            'sessions' => []
                                                                                        ];
                                                                                    }
                                                                                    $groupedJadwal[$key]['sessions'][] = $jadwal;
                                                                                }
                                                                            @endphp
                                                                            
                                                                            @foreach($groupedJadwal as $index => $group)
                                                                                @php
                                                                                    $firstJadwal = $group['jadwal'];
                                                                                    $sessions = $group['sessions'];
                                                                                    $waktuMulai = $sessions[0]->waktu_mulai;
                                                                                    $waktuSelesai = end($sessions)->waktu_selesai;
                                                                                @endphp
                                                                                <tr>
                                                                                    <td>{{ $loop->iteration }}</td>
                                                                                    <td>{{ date('H:i', strtotime($waktuMulai)) }} - {{ date('H:i', strtotime($waktuSelesai)) }}</td>
                                                                                    <td>{{ $firstJadwal->mataPelajaran->nama }}</td>
                                                                                    <td>{{ $firstJadwal->guru->nama_lengkap }}</td>
                                                                                    <td class="text-center">
                                                                                        <div class="d-flex justify-content-center gap-2">
                                                                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                                                                    onclick="viewJadwal({{ $firstJadwal->id_jadwal }})"
                                                                                                    data-bs-toggle="tooltip" title="Lihat Detail">
                                                                                                <i class="bi bi-eye"></i>
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                                                    onclick="editJadwal({{ $firstJadwal->id_jadwal }})"
                                                                                                    data-bs-toggle="tooltip" title="Edit">
                                                                                                <i class="bi bi-pencil"></i>
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                                                    onclick="deleteJadwal({{ $firstJadwal->id_jadwal }})"
                                                                                                    data-bs-toggle="tooltip" title="Hapus">
                                                                                                <i class="bi bi-trash"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formJadwalMassal">
                <div class="modal-body schedule-modal-body">
                    
                    <!-- Control Panel Section -->
                    <div class="schedule-control-section">
                        <div class="row g-4">
                            <!-- Class Selection Card -->
                            <div class="col-lg-6">
                                <div class="control-card class-selection-card">
                                    <div class="control-card-header">
                                        <i class="bi bi-building"></i>
                                        <span>Pilih Kelas</span>
                                    </div>
                                    <div class="control-card-body">
                                        <select name="id_kelas" id="id_kelas_massal" class="form-select schedule-select" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($kelasList as $kelas)
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

                            <!-- Quick Actions Card -->
                            <!-- <div class="col-lg-3">
                                <div class="control-card actions-card">
                                    <div class="control-card-header">
                                        <i class="bi bi-lightning-charge"></i>
                                        <span>Aksi Cepat</span>
                                    </div>
                                    <div class="control-card-body">
                                        <div class="action-buttons">
                                            <button type="button" id="btnClearJadwalTable" class="action-btn clear-btn">
                                                <i class="bi bi-trash3"></i>
                                                <span>Bersihkan Semua</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Statistics Card -->
                            <div class="col-lg-3">
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

                                                        <!-- Status Indicator -->
                                                        <div class="cell-status">
                                                            <div class="status-indicator complete" style="display: none;">
                                                                <i class="bi bi-check-circle-fill"></i>
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

                        <!-- Instructions Panel -->
                        <!-- <div class="instructions-panel">
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="instruction-card">
                                        <div class="instruction-header">
                                            <i class="bi bi-lightbulb"></i>
                                            <span>Panduan Penggunaan</span>
                                        </div>
                                        <div class="instruction-content">
                                            <div class="instruction-steps">
                                                <div class="step-item">
                                                    <div class="step-number">1</div>
                                                    <div class="step-text">Pilih kelas untuk menampilkan jadwal yang sudah ada (jika ada)</div>
                                                </div>
                                                <div class="step-item">
                                                    <div class="step-number">2</div>
                                                    <div class="step-text">Pilih mata pelajaran terlebih dahulu pada dropdown atas</div>
                                                </div>
                                                <div class="step-item">
                                                    <div class="step-number">3</div>
                                                    <div class="step-text">Guru akan otomatis terfilter berdasarkan mata pelajaran</div>
                                                </div>
                                                <div class="step-item">
                                                    <div class="step-number">4</div>
                                                    <div class="step-text">Sel kosong tidak akan disimpan sebagai jadwal</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="time-reference-card">
                                        <div class="time-reference-header">
                                            <i class="bi bi-clock-history"></i>
                                            <span>Referensi Waktu</span>
                                        </div>
                                        <div class="time-reference-content">
                                            @php
                                                $times = [
                                                    1 => '07:45 - 08:30',
                                                    2 => '08:30 - 09:15',
                                                    3 => '09:15 - 10:00',
                                                    4 => '10:15 - 11:00',
                                                    5 => '11:00 - 11:45',
                                                    6 => '11:45 - 12:30'
                                                ];
                                            @endphp
                                            
                                            @for($i = 1; $i <= 6; $i++)
                                                <div class="time-ref-item">
                                                    <div class="time-ref-session">{{ $i }}</div>
                                                    <div class="time-ref-period">{{ $times[$i] }}</div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

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

<style>
/* ===== MODAL JADWAL MASSAL STYLES ===== */

/* Modal Header */
.schedule-modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-bottom: none;
}

.modal-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
}

.modal-title-group .modal-title {
    font-size: 1.5rem;
    font-weight: 600;
}

.modal-subtitle {
    opacity: 0.9;
    font-size: 0.9rem;
}

/* Modal Body */
.schedule-modal-body {
    padding: 2rem;
    background: #f8f9fa;
}

/* Control Section */
.schedule-control-section {
    margin-bottom: 2rem;
}

.control-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.control-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.control-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #495057;
}

.control-card-header i {
    margin-right: 0.75rem;
    font-size: 1.1rem;
    color: #6c757d;
}

.control-card-body {
    padding: 1.5rem;
}

/* Class Selection Card */
.class-selection-card .control-card-header {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1976d2;
}

.class-selection-card .control-card-header i {
    color: #1976d2;
}

.schedule-select {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.schedule-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.selected-class-info {
    margin-top: 1rem;
    padding: 0.75rem;
    background: #d4edda;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    color: #155724;
}

/* Actions Card */
.actions-card .control-card-header {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    color: #f57c00;
}

.actions-card .control-card-header i {
    color: #f57c00;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    border: 2px solid;
    border-radius: 12px;
    background: white;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
}

.clear-btn {
    border-color: #ffc107;
    color: #ffc107;
}

.clear-btn:hover:not(:disabled) {
    background: #ffc107;
    color: #212529;
    transform: translateY(-1px);
}

.action-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Stats Card */
.stats-card .control-card-header {
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
    color: #7b1fa2;
}

.stats-card .control-card-header i {
    color: #7b1fa2;
}

.stats-container {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.progress-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: conic-gradient(#28a745 0deg, #e9ecef 0deg);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.progress-circle::before {
    content: '';
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 50%;
    position: absolute;
}

.progress-value {
    font-size: 1rem;
    font-weight: 700;
    color: #495057;
    z-index: 1;
}

.stats-details {
    flex: 1;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-number {
    font-size: 1.25rem;
    font-weight: 700;
}

.stat-item.filled .stat-number {
    color: #28a745;
}

.stat-item.empty .stat-number {
    color: #6c757d;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Schedule Table Section */
.schedule-table-section {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.schedule-table-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.table-title h6 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.class-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.9rem;
}

.table-legend {
    display: flex;
    gap: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 3px;
}

.legend-color.filled {
    background: #28a745;
}

.legend-color.empty {
    background: #e9ecef;
}


.schedule-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.9rem;
}

.table-header-row {
    background: #343a40;
    color: white;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table-header-row th {
    padding: 1rem 0.75rem;
    text-align: center;
    border: none;
    font-weight: 600;
    white-space: nowrap;
}

.header-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.header-content i {
    font-size: 1.1rem;
    opacity: 0.8;
}

.session-column {
    width: 100px;
    background: #495057;
}

.time-column {
    width: 120px;
    background: #495057;
}

.day-column {
    min-width: 220px;
    background: #343a40;
}

/* Schedule Rows */
.schedule-row {
    transition: background-color 0.3s ease;
}

.schedule-row:hover {
    background: rgba(102, 126, 234, 0.05);
}

/* Session Cell */
.session-cell {
    background: #f8f9fa;
    text-align: center;
    padding: 1rem 0.75rem;
    border-right: 2px solid #dee2e6;
    vertical-align: middle;
}

.session-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.session-number {
    width: 40px;
    height: 40px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
}

/* Time Cell */
.time-cell {
    background: #f8f9fa;
    text-align: center;
    padding: 1rem 0.75rem;
    border-right: 2px solid #dee2e6;
    vertical-align: middle;
}

.time-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.time-start {
    font-weight: 700;
    color: #495057;
    font-size: 0.95rem;
}

.time-separator {
    color: #6c757d;
    font-weight: 300;
}

.time-end {
    color: #6c757d;
    font-size: 0.85rem;
}

/* Schedule Cell */
.schedule-cell {
    padding: 1rem;
    vertical-align: top;
    border-bottom: 1px solid #dee2e6;
    position: relative;
}

.jadwal-cell {
    position: relative;
    min-height: 100px;
    border-radius: 12px;
    padding: 0.75rem;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.jadwal-cell:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.jadwal-cell.filled {
    background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
    border-color: #28a745;
}

.subject-select, .teacher-select {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    background: white;
    transition: all 0.3s ease;
}

.subject-select:focus, .teacher-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.1rem rgba(102, 126, 234, 0.25);
}

.cell-status {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
}

.status-indicator.complete {
    color: #28a745;
    font-size: 1.2rem;
}

/* Instructions Panel */
.instructions-panel {
    padding: 2rem;
    background: #f8f9fa;
}

.instruction-card, .time-reference-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.instruction-header, .time-reference-header {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
}

.instruction-content, .time-reference-content {
    padding: 1.5rem;
}

.instruction-steps {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.step-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid #17a2b8;
}

.step-number {
    width: 32px;
    height: 32px;
    background: #17a2b8;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.step-text {
    color: #495057;
    line-height: 1.5;
}

/* Time Reference */
.time-ref-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.time-ref-item:last-child {
    border-bottom: none;
}

.time-ref-session {
    width: 28px;
    height: 28px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
}

.time-ref-period {
    font-family: 'Courier New', monospace;
    font-weight: 500;
    color: #495057;
}

/* Modal Footer */
.schedule-modal-footer {
    background: white;
    border-top: 1px solid #dee2e6;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.footer-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6c757d;
    font-size: 0.9rem;
}

.footer-actions {
    display: flex;
    gap: 1rem;
}

.btn-cancel, .btn-save {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-cancel {
    background: #6c757d;
    border-color: #6c757d;
}

.btn-cancel:hover {
    background: #5a6268;
    border-color: #545b62;
    transform: translateY(-1px);
}

.btn-save {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-save:hover:not(:disabled) {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-save:disabled {
    background: #6c757d;
    opacity: 0.6;
    cursor: not-allowed;
}

/* Loading Indicator */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
    border-radius: 12px;
}

.spinner-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.spinner-text {
    font-weight: 500;
    color: #495057;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .day-column {
        min-width: 180px;
    }
    
    .jadwal-cell {
        min-height: 80px;
        padding: 0.5rem;
    }
}

@media (max-width: 768px) {
    .schedule-modal-body {
        padding: 1rem;
    }
    
    .schedule-control-section .row {
        flex-direction: column;
    }
    
    .stats-container {
        flex-direction: column;
        text-align: center;
    }
    
    .table-legend {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .day-column {
        min-width: 150px;
    }
    
    .jadwal-cell {
        min-height: 70px;
        padding: 0.5rem;
    }
    
    .subject-select, .teacher-select {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
}

@media (max-width: 576px) {
    .modal-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .modal-title {
        font-size: 1.2rem;
    }
    
    .schedule-table-wrapper {
        max-height: 60vh;
    }
    
    .instructions-panel {
        padding: 1rem;
    }
    
    .footer-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-cancel, .btn-save {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Filter functionality
    $('#filterKelas, #filterTahunAjaran').on('change', function() {
        const kelas = $('#filterKelas').val();
        const tahunAjaran = $('#filterTahunAjaran').val();
        
        const params = new URLSearchParams();
        if (kelas) params.append('kelas', kelas);
        if (tahunAjaran) params.append('tahun_ajaran', tahunAjaran);
        
        window.location.href = '{{ route("jadwal-pelajaran.index") }}?' + params.toString();
    });

    // Show modal and load first class automatically
    $('#modalJadwalMassal').on('shown.bs.modal', function() {
        // Auto-select first class if available
        const firstClass = $('#id_kelas_massal option:not([value=""])').first();
        if (firstClass.length > 0) {
            $('#id_kelas_massal').val(firstClass.val()).trigger('change');
        }
    });

    // Mass Schedule functionality
    $('#id_kelas_massal').on('change', function() {
        const kelasId = $(this).val();
        if (kelasId) {
            $('#jadwalTableContainer').show();
            $('#btnSubmitMassal').prop('disabled', false);
            
            // Update kelas name
            const kelasText = $(this).find('option:selected').text();
            $('#kelasNameBadge').text(kelasText);
            $('#selectedClassInfo').show();
            $('#selectedClassName').text(kelasText);
            
            // Clear existing data
            clearJadwalTable();
            
            // Automatically load existing jadwal
            loadExistingJadwal(kelasId);
        } else {
            $('#jadwalTableContainer').hide();
            $('#btnSubmitMassal').prop('disabled', true);
            $('#selectedClassInfo').hide();
        }
    });

    // Function to load existing jadwal
    function loadExistingJadwal(kelasId) {
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

        $.ajax({
            url: `/jadwal-pelajaran/kelas/${kelasId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    // Populate table with existing data
                    const jadwalData = response.data;
                    for (const hari in jadwalData) {
                        for (const sesi in jadwalData[hari]) {
                            const data = jadwalData[hari][sesi];
                            
                            // Set mata pelajaran
                            const mapelSelect = $(`.mapel-select[data-hari="${hari}"][data-sesi="${sesi}"]`);
                            mapelSelect.val(data.id_mata_pelajaran);
                            
                            // Load and set guru
                            loadGuruForMapel(data.id_mata_pelajaran, hari, sesi, data.id_guru);
                        }
                    }
                    
                    updateCounters();
                    
                    // Show success toast
                    showToast('success', 'Jadwal berhasil dimuat');
                }
            },
            error: function() {
                showToast('error', 'Gagal memuat jadwal');
            },
            complete: function() {
                // Remove loading overlay
                $('.loading-overlay').remove();
            }
        });
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

    function clearJadwalTable() {
        $('.mapel-select').val('');
        $('.guru-select').html('<option value="">Pilih Guru</option>');
        $('.jadwal-cell').removeClass('filled');
        $('.status-indicator').hide();
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
                $(this).find('.status-indicator').show();
            } else {
                $(this).removeClass('filled');
                $(this).find('.status-indicator').hide();
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
            confirmButtonColor: '#28a745'
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

    // Reset modal when closed
    $('#modalJadwalMassal').on('hidden.bs.modal', function() {
        $('#formJadwalMassal')[0].reset();
        $('#jadwalTableContainer').hide();
        $('#btnSubmitMassal').prop('disabled', true);
        $('#selectedClassInfo').hide();
        clearJadwalTable();
        updateCounters();
    });

    // Toast notification function
    function showToast(type, message) {
        const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';
        
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

function editJadwal(id) {
    $.ajax({
        url: `/jadwal-pelajaran/${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const jadwal = response.data;
                
                $('#modalJadwalLabel').text('Edit Jadwal Pelajaran');
                $('#isEdit').val('1');
                $('#jadwalId').val(id);
                
                // Fill form
                $('#id_kelas').val(jadwal.id_kelas);
                $('#hari').val(jadwal.hari);
                $('#id_mata_pelajaran').val(jadwal.id_mata_pelajaran);
                
                // Load guru for selected mata pelajaran
                $.ajax({
                    url: `/jadwal-pelajaran/guru-by-mapel/${jadwal.id_mata_pelajaran}`,
                    method: 'GET',
                    success: function(guruResponse) {
                        if (guruResponse.success) {
                            let options = '<option value="">-- Pilih Guru --</option>';
                            guruResponse.data.forEach(function(guru) {
                                const selected = guru.id_guru == jadwal.id_guru ? 'selected' : '';
                                options += `<option value="${guru.id_guru}" ${selected}>${guru.nama_lengkap}</option>`;
                            });
                            $('#id_guru').html(options);
                        }
                    }
                });
                
                // Check selected sessions
                $('input[name="sesi[]"]').prop('checked', false);
                if (response.selected_sessions) {
                    response.selected_sessions.forEach(function(sesi) {
                        $(`#sesi${sesi}`).prop('checked', true);
                    });
                }
                
                $('#modalJadwal').modal('show');
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

function viewJadwal(id) {
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