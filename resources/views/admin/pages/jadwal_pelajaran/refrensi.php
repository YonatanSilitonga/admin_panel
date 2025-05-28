@extends('layouts.admin-layout')

@section('title', 'Manajemen Jadwal Pelajaran')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <!-- Enhanced Header -->
            <header class="schedule-header">
                <div class="header-content">
                    <div class="header-text">
                        <h1 class="header-title">
                            <i class="bi bi-calendar-week me-3"></i>
                            Manajemen Jadwal Pelajaran
                        </h1>
                        <p class="header-subtitle">Kelola jadwal pelajaran untuk semua kelas dengan mudah dan efisien</p>
                    </div>
                    <div class="header-stats">
                        <div class="stat-card">
                            <div class="stat-number">{{ $kelasList->count() }}</div>
                            <div class="stat-label">Total Kelas</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $jadwalList->count() }}</div>
                            <div class="stat-label">Total Jadwal</div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="data">
                <!-- Enhanced Filter and Controls -->
                <div class="control-panel">
                    <div class="filter-section">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="bi bi-building"></i>
                                Filter Kelas
                            </label>
                            <select id="filterKelas" class="form-select filter-select">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id_kelas }}" {{ ($kelasId ?? '') == $kelas->id_kelas ? 'selected' : '' }}>
                                         {{ $kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="bi bi-calendar-range"></i>
                                Tahun Ajaran
                            </label>
                            <select id="filterTahunAjaran" class="form-select filter-select">
                                @foreach($tahunAjaranList as $ta)
                                    <option value="{{ $ta->id_tahun_ajaran }}" {{ ($tahunAjaranId ?? '') == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                        {{ $ta->nama_tahun_ajaran }} {{ $ta->aktif ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="action-section">
                        <button type="button" class="btn btn-primary btn-manage" data-bs-toggle="modal" data-bs-target="#modalJadwalMassal">
                            <i class="bi bi-calendar-week me-2"></i>
                            Kelola Jadwal
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-export">
                            <i class="bi bi-download me-2"></i>
                            Export
                        </button>
                    </div>
                </div>
                
                <!-- Enhanced Schedule Display -->
                @if($jadwalList->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <h3>Belum Ada Jadwal</h3>
                        <p>Mulai buat jadwal pelajaran untuk kelas-kelas yang tersedia</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalJadwalMassal">
                            <i class="bi bi-plus-circle me-2"></i>
                            Buat Jadwal Pertama
                        </button>
                    </div>
                @else
                    <!-- Weekly Schedule Overview -->
                    <div class="schedule-overview">
                        <div class="overview-header">
                            <h3><i class="bi bi-grid-3x3-gap me-2"></i>Jadwal Mingguan</h3>
                            <div class="view-toggle">
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="viewMode" id="weekView" checked>
                                    <label class="btn btn-outline-primary" for="weekView">
                                        <i class="bi bi-grid-3x3-gap me-1"></i>Mingguan
                                    </label>
                                    <input type="radio" class="btn-check" name="viewMode" id="dayView">
                                    <label class="btn btn-outline-primary" for="dayView">
                                        <i class="bi bi-list-ul me-1"></i>Harian
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Weekly Grid View -->
                        <div id="weeklyView" class="weekly-schedule-grid">
                            <div class="schedule-grid">
                                <!-- Time Column -->
                                <div class="time-column">
                                    <div class="time-header">Waktu</div>
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
                                        <div class="time-slot">
                                            <div class="session-number">{{ $sesi }}</div>
                                            <div class="time-range">
                                                <span class="time-start">{{ $sesiData['start'] }}</span>
                                                <span class="time-end">{{ $sesiData['end'] }}</span>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                <!-- Day Columns -->
                                @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $hari)
                                    <div class="day-column">
                                        <div class="day-header">
                                            <i class="bi bi-calendar-day"></i>
                                            {{ ucfirst($hari) }}
                                        </div>
                                        @for($sesi = 1; $sesi <= 6; $sesi++)
                                            <div class="schedule-slot" data-hari="{{ $hari }}" data-sesi="{{ $sesi }}">
                                                @php
                                                    $hasSchedule = false;
                                                    $scheduleData = null;
                                                    
                                                    // Safe array access with null checks
                                                    if (isset($jadwalByHariKelas) && is_array($jadwalByHariKelas)) {
                                                        foreach($kelasList as $kelas) {
                                                            if (isset($jadwalByHariKelas[$hari]) && 
                                                                isset($jadwalByHariKelas[$hari][$kelas->id_kelas]) && 
                                                                is_array($jadwalByHariKelas[$hari][$kelas->id_kelas])) {
                                                                
                                                                foreach($jadwalByHariKelas[$hari][$kelas->id_kelas] as $jadwal) {
                                                                    if(isset($jadwal->sesi) && $jadwal->sesi == $sesi) {
                                                                        $hasSchedule = true;
                                                                        $scheduleData = $jadwal;
                                                                        break 2;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if($hasSchedule && $scheduleData)
                                                    <div class="schedule-item filled" onclick="viewScheduleDetail('{{ $scheduleData->id_jadwal }}')">
                                                        <div class="schedule-subject">{{ $scheduleData->mataPelajaran->nama ?? 'N/A' }}</div>
                                                        <div class="schedule-class">{{ $scheduleData->kelas->nama_kelas ?? 'N/A' }}</div>
                                                        <div class="schedule-teacher">{{ $scheduleData->guru->nama_lengkap ?? 'N/A' }}</div>
                                                        <div class="schedule-actions">
                                                            <button class="btn-action edit" onclick="event.stopPropagation(); editJadwal({{ $scheduleData->id_jadwal }})" title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button class="btn-action delete" onclick="event.stopPropagation(); deleteJadwal({{ $scheduleData->id_jadwal }})" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="schedule-item empty">
                                                        <div class="empty-slot">
                                                            <i class="bi bi-plus-circle"></i>
                                                            <span>Kosong</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endfor
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Daily List View -->
                        <div id="dailyView" class="daily-schedule-list" style="display: none;">
                            <ul class="nav nav-pills day-tabs" id="dayTabs" role="tablist">
                                @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $index => $hariTab)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                                id="{{ $hariTab }}-tab" 
                                                data-bs-toggle="pill" 
                                                data-bs-target="#{{ $hariTab }}-content" 
                                                type="button" 
                                                role="tab">
                                            {{ ucfirst($hariTab) }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content day-content" id="dayTabsContent">
                                @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $index => $hariTab)
                                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                                         id="{{ $hariTab }}-content" 
                                         role="tabpanel">
                                        
                                        @php
                                            $daySchedules = [];
                                            
                                            // Safe array access with proper null checks
                                            if (isset($jadwalByHariKelas) && 
                                                is_array($jadwalByHariKelas) && 
                                                isset($jadwalByHariKelas[$hariTab]) && 
                                                is_array($jadwalByHariKelas[$hariTab])) {
                                                
                                                foreach($kelasList as $kelas) {
                                                    if (isset($jadwalByHariKelas[$hariTab][$kelas->id_kelas]) && 
                                                        is_array($jadwalByHariKelas[$hariTab][$kelas->id_kelas]) && 
                                                        count($jadwalByHariKelas[$hariTab][$kelas->id_kelas]) > 0) {
                                                        
                                                        $daySchedules[$kelas->id_kelas] = $jadwalByHariKelas[$hariTab][$kelas->id_kelas];
                                                    }
                                                }
                                            }
                                        @endphp

                                        @if(empty($daySchedules))
                                            <div class="day-empty">
                                                <i class="bi bi-calendar-x"></i>
                                                <p>Tidak ada jadwal untuk hari {{ ucfirst($hariTab) }}</p>
                                            </div>
                                        @else
                                            <div class="class-schedules">
                                                @foreach($daySchedules as $kelasId => $schedules)
                                                    @php
                                                        $kelas = $kelasList->firstWhere('id_kelas', $kelasId);
                                                    @endphp
                                                    @if($kelas)
                                                        <div class="class-schedule-card">
                                                            <div class="class-header">
                                                                <h4>{{ $kelas->nama_kelas }}</h4>
                                                                <span class="schedule-count">{{ count($schedules) }} Jadwal</span>
                                                            </div>
                                                            <div class="schedule-timeline">
                                                                @if(is_array($schedules) || is_object($schedules))
                                                                    @foreach(collect($schedules)->sortBy('sesi') as $jadwal)
                                                                        <div class="timeline-item">
                                                                            <div class="timeline-time">
                                                                                @php
                                                                                    $sesiData = [
                                                                                        1 => ['start' => '07:45', 'end' => '08:30'],
                                                                                        2 => ['start' => '08:30', 'end' => '09:15'],
                                                                                        3 => ['start' => '09:15', 'end' => '10:00'],
                                                                                        4 => ['start' => '10:15', 'end' => '11:00'],
                                                                                        5 => ['start' => '11:00', 'end' => '11:45'],
                                                                                        6 => ['start' => '11:45', 'end' => '12:30']
                                                                                    ][$jadwal->sesi ?? 1];
                                                                                @endphp
                                                                                <div class="session-badge">{{ $jadwal->sesi ?? 1 }}</div>
                                                                                <div class="time-info">
                                                                                    <span>{{ $sesiData['start'] }}</span>
                                                                                    <span>{{ $sesiData['end'] }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="timeline-content">
                                                                                <h5>{{ $jadwal->mataPelajaran->nama ?? 'N/A' }}</h5>
                                                                                <p>{{ $jadwal->guru->nama_lengkap ?? 'N/A' }}</p>
                                                                                <div class="timeline-actions">
                                                                                    <button class="btn btn-sm btn-outline-info" onclick="viewJadwal({{ $jadwal->id_jadwal }})">
                                                                                        <i class="bi bi-eye"></i>
                                                                                    </button>
                                                                                    <button class="btn btn-sm btn-outline-warning" onclick="editJadwal({{ $jadwal->id_jadwal }})">
                                                                                        <i class="bi bi-pencil"></i>
                                                                                    </button>
                                                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteJadwal({{ $jadwal->id_jadwal }})">
                                                                                        <i class="bi bi-trash"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

<!-- Enhanced Modal for Schedule Management -->
<div class="modal fade" id="modalJadwalMassal" tabindex="-1" aria-labelledby="modalJadwalMassalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header schedule-modal-header">
                <div class="d-flex align-items-center">
                    <div class="modal-icon">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <div class="modal-title-group">
                        <h5 class="modal-title mb-0" id="modalJadwalMassalLabel">Kelola Jadwal Pelajaran</h5>
                        <small class="modal-subtitle">Tambah, edit, dan kelola jadwal untuk kelas yang dipilih</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formJadwalMassal">
                <div class="modal-body schedule-modal-body">
                    
                    <!-- Enhanced Control Panel -->
                    <div class="schedule-control-section">
                        <div class="row g-4">
                            <!-- Class Selection -->
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

                            <!-- Statistics -->
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

                            <!-- Quick Actions -->
                            <div class="col-lg-3">
                                <div class="control-card actions-card">
                                    <div class="control-card-header">
                                        <i class="bi bi-lightning"></i>
                                        <span>Aksi Cepat</span>
                                    </div>
                                    <div class="control-card-body">
                                        <div class="action-buttons">
                                            <button type="button" class="action-btn clear-btn" id="btnClearJadwalTable" disabled>
                                                <i class="bi bi-eraser"></i>
                                                <span>Bersihkan Semua</span>
                                            </button>
                                            <button type="button" class="action-btn copy-btn" id="btnCopyFromTemplate" disabled>
                                                <i class="bi bi-files"></i>
                                                <span>Salin Template</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Management Table -->
                    <div id="jadwalTableContainer" class="schedule-table-section" style="display: none;">
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
                                    <div class="legend-color modified"></div>
                                    <span>Diubah</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color empty"></div>
                                    <span>Kosong</span>
                                </div>
                            </div>
                        </div>

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
                                            <td class="session-cell">
                                                <div class="session-info">
                                                    <div class="session-number">{{ $sesi }}</div>
                                                </div>
                                            </td>
                                            <td class="time-cell">
                                                <div class="time-info">
                                                    <div class="time-start">{{ $sesiData['start'] }}</div>
                                                    <div class="time-separator">—</div>
                                                    <div class="time-end">{{ $sesiData['end'] }}</div>
                                                </div>
                                            </td>
                                            @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $hari)
                                                <td class="schedule-cell">
                                                    <div class="jadwal-cell" data-hari="{{ $hari }}" data-sesi="{{ $sesi }}">
                                                        <!-- Hidden field for existing jadwal ID -->
                                                        <input type="hidden" name="jadwal[{{ $hari }}][{{ $sesi }}][id_jadwal]" class="existing-id">
                                                        
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

                                                        <!-- Status Indicators -->
                                                        <div class="cell-status">
                                                            <div class="status-indicator complete" style="display: none;">
                                                                <i class="bi bi-check-circle-fill"></i>
                                                            </div>
                                                            <div class="status-indicator modified" style="display: none;">
                                                                <i class="bi bi-pencil-fill"></i>
                                                            </div>
                                                            <div class="status-indicator existing" style="display: none;">
                                                                <i class="bi bi-bookmark-fill"></i>
                                                            </div>
                                                        </div>

                                                        <!-- Quick Actions -->
                                                        <div class="cell-actions" style="display: none;">
                                                            <button type="button" class="btn-cell-action clear" title="Hapus">
                                                                <i class="bi bi-x"></i>
                                                            </button>
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
                            <span>Akan menyimpan <strong id="saveCount">0</strong> perubahan</span>
                        </div>
                        <div class="conflict-warning" id="conflictWarning" style="display: none;">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span>Terdapat konflik jadwal!</span>
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

<!-- Detail Modal (unchanged) -->
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

<!-- Include all the CSS styles from the previous version -->
<style>
/* All the previous CSS styles remain the same */
/* Enhanced Header Styles */
.schedule-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
}

.header-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0.5rem 0 0 0;
}

.header-stats {
    display: flex;
    gap: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.15);
    padding: 1.5rem;
    border-radius: 15px;
    text-align: center;
    backdrop-filter: blur(10px);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    display: block;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Enhanced Control Panel */
.control-panel {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
}

.filter-section {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.filter-select {
    min-width: 200px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.filter-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.action-section {
    display: flex;
    gap: 1rem;
}

.btn-manage, .btn-export {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-manage {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-manage:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.empty-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #495057;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 2rem;
}

/* Schedule Overview */
.schedule-overview {
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.overview-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.overview-header h3 {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.view-toggle .btn {
    border-radius: 10px;
    font-weight: 500;
}

/* Weekly Grid View */
.weekly-schedule-grid {
    padding: 2rem;
}

.schedule-grid {
    display: grid;
    grid-template-columns: 120px repeat(6, 1fr);
    gap: 1px;
    background: #dee2e6;
    border-radius: 15px;
    overflow: hidden;
}

.time-column, .day-column {
    background: white;
}

.time-header, .day-header {
    background: #343a40;
    color: white;
    padding: 1rem;
    text-align: center;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.time-slot {
    padding: 1rem;
    text-align: center;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    min-height: 100px;
    justify-content: center;
}

.session-number {
    width: 30px;
    height: 30px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.time-range {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    font-size: 0.8rem;
}

.time-start {
    font-weight: 600;
    color: #495057;
}

.time-end {
    color: #6c757d;
}

.schedule-slot {
    background: white;
    min-height: 100px;
    position: relative;
}

.schedule-item {
    height: 100%;
    padding: 0.75rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.schedule-item.filled {
    background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
    border-left: 4px solid #28a745;
}

.schedule-item.filled:hover {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    transform: scale(1.02);
}

.schedule-item.empty {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    color: #6c757d;
    text-align: center;
}

.schedule-subject {
    font-weight: 600;
    color: #155724;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.schedule-class {
    font-size: 0.8rem;
    color: #28a745;
    margin-bottom: 0.25rem;
}

.schedule-teacher {
    font-size: 0.75rem;
    color: #6c757d;
}

.schedule-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.schedule-item:hover .schedule-actions {
    opacity: 1;
}

.btn-action {
    width: 24px;
    height: 24px;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-action.edit {
    background: #ffc107;
    color: #212529;
}

.btn-action.delete {
    background: #dc3545;
    color: white;
}

.btn-action:hover {
    transform: scale(1.1);
}

.empty-slot {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
}

/* Daily List View */
.daily-schedule-list {
    padding: 2rem;
}

.day-tabs {
    margin-bottom: 2rem;
    border-bottom: 2px solid #e9ecef;
}

.day-tabs .nav-link {
    border: none;
    border-radius: 10px 10px 0 0;
    font-weight: 600;
    color: #6c757d;
    padding: 1rem 1.5rem;
}

.day-tabs .nav-link.active {
    background: #667eea;
    color: white;
}

.day-empty {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}

.day-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.class-schedules {
    display: grid;
    gap: 1.5rem;
}

.class-schedule-card {
    background: #f8f9fa;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.class-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.class-header h4 {
    margin: 0;
    font-weight: 600;
}

.schedule-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
}

.schedule-timeline {
    padding: 1.5rem;
}

.timeline-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
}

.timeline-item:last-child {
    border-bottom: none;
}

.timeline-time {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    min-width: 80px;
}

.session-badge {
    width: 35px;
    height: 35px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.time-info {
    display: flex;
    flex-direction: column;
    font-size: 0.75rem;
    color: #6c757d;
    text-align: center;
}

.timeline-content {
    flex: 1;
}

.timeline-content h5 {
    margin: 0 0 0.25rem 0;
    color: #495057;
    font-weight: 600;
}

.timeline-content p {
    margin: 0 0 0.5rem 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.timeline-actions {
    display: flex;
    gap: 0.5rem;
}

/* Enhanced Modal Styles */
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

.schedule-modal-body {
    padding: 2rem;
    background: #f8f9fa;
}

/* Control Cards */
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

.copy-btn {
    border-color: #17a2b8;
    color: #17a2b8;
}

.copy-btn:hover:not(:disabled) {
    background: #17a2b8;
    color: white;
    transform: translateY(-1px);
}

.action-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Schedule Table */
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

.legend-color.modified {
    background: #ffc107;
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

.schedule-row {
    transition: background-color 0.3s ease;
}

.schedule-row:hover {
    background: rgba(102, 126, 234, 0.05);
}

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

.jadwal-cell.modified {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-color: #ffc107;
}

.jadwal-cell.existing {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-color: #2196f3;
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

.status-indicator {
    font-size: 1.2rem;
}

.status-indicator.complete {
    color: #28a745;
}

.status-indicator.modified {
    color: #ffc107;
}

.status-indicator.existing {
    color: #2196f3;
}

.cell-actions {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
}

.btn-cell-action {
    width: 24px;
    height: 24px;
    border: none;
    border-radius: 50%;
    background: #dc3545;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cell-action:hover {
    background: #c82333;
    transform: scale(1.1);
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
    gap: 1rem;
}

.save-info, .conflict-warning {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.save-info {
    color: #6c757d;
}

.conflict-warning {
    color: #dc3545;
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

/* Responsive Design */
@media (max-width: 1200px) {
    .header-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .header-stats {
        justify-content: center;
    }
    
    .control-panel {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-section {
        justify-content: center;
    }
    
    .day-column {
        min-width: 180px;
    }
    
    .jadwal-cell {
        min-height: 80px;
        padding: 0.5rem;
    }
}

@media (max-width: 768px) {
    .schedule-header {
        padding: 1.5rem;
    }
    
    .header-title {
        font-size: 2rem;
    }
    
    .header-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .control-panel {
        padding: 1.5rem;
    }
    
    .filter-section {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filter-select {
        min-width: auto;
    }
    
    .action-section {
        flex-direction: column;
    }
    
    .schedule-grid {
        grid-template-columns: 100px repeat(6, 1fr);
        font-size: 0.8rem;
    }
    
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
    
    .class-schedules {
        grid-template-columns: 1fr;
    }
    
    .timeline-item {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .timeline-time {
        flex-direction: row;
        min-width: auto;
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
        overflow-x: auto;
    }
    
    .footer-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-cancel, .btn-save {
        width: 100%;
        justify-content: center;
    }
    
    .schedule-grid {
        grid-template-columns: 80px repeat(6, minmax(120px, 1fr));
    }
    
    .time-slot, .schedule-slot {
        min-height: 60px;
    }
    
    .session-number {
        width: 25px;
        height: 25px;
        font-size: 0.8rem;
    }
}

/* Loading States */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
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

/* Animation Classes */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    from { transform: translateX(-100%); }
    to { transform: translateX(0); }
}

.fade-in {
    animation: fadeIn 0.3s ease-out;
}

.slide-in {
    animation: slideIn 0.3s ease-out;
}

/* Conflict Highlighting */
.conflict-highlight {
    border-color: #dc3545 !important;
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%) !important;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}
</style>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // View mode toggle
    $('input[name="viewMode"]').on('change', function() {
        if ($(this).attr('id') === 'weekView') {
            $('#weeklyView').show();
            $('#dailyView').hide();
        } else {
            $('#weeklyView').hide();
            $('#dailyView').show();
        }
    });

    // Filter functionality
    $('#filterKelas, #filterTahunAjaran').on('change', function() {
        const kelas = $('#filterKelas').val();
        const tahunAjaran = $('#filterTahunAjaran').val();
        
        const params = new URLSearchParams();
        if (kelas) params.append('kelas', kelas);
        if (tahunAjaran) params.append('tahun_ajaran', tahunAjaran);
        
        window.location.href = '{{ route("jadwal-pelajaran.index") }}?' + params.toString();
    });

    // Enhanced modal functionality
    $('#modalJadwalMassal').on('shown.bs.modal', function() {
        // Auto-select first class if available
        const firstClass = $('#id_kelas_massal option:not([value=""])').first();
        if (firstClass.length > 0) {
            $('#id_kelas_massal').val(firstClass.val()).trigger('change');
        }
    });

    // Enhanced class selection with existing schedule loading
    $('#id_kelas_massal').on('change', function() {
        const kelasId = $(this).val();
        if (kelasId) {
            $('#jadwalTableContainer').show();
            $('#btnSubmitMassal').prop('disabled', false);
            $('#btnClearJadwalTable, #btnCopyFromTemplate').prop('disabled', false);
            
            // Update UI
            const kelasText = $(this).find('option:selected').text();
            $('#kelasNameBadge').text(kelasText);
            $('#selectedClassInfo').show();
            $('#selectedClassName').text(kelasText);
            
            // Clear and load existing data
            clearJadwalTable();
            loadExistingJadwal(kelasId);
        } else {
            $('#jadwalTableContainer').hide();
            $('#btnSubmitMassal').prop('disabled', true);
            $('#btnClearJadwalTable, #btnCopyFromTemplate').prop('disabled', true);
            $('#selectedClassInfo').hide();
        }
    });

    // Enhanced function to load existing jadwal with better UI feedback
    function loadExistingJadwal(kelasId) {
        const loadingOverlay = `
            <div class="loading-overlay">
                <div class="spinner-container">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-text">Memuat jadwal yang ada...</div>
                </div>
            </div>
        `;
        
        $('#jadwalTableContainer').append(loadingOverlay);

        $.ajax({
            url: `/jadwal-pelajaran/kelas/${kelasId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const jadwalData = response.data;
                    let loadedCount = 0;
                    
                    // Populate table with existing data
                    for (const hari in jadwalData) {
                        for (const sesi in jadwalData[hari]) {
                            const data = jadwalData[hari][sesi];
                            const cell = $(`.jadwal-cell[data-hari="${hari}"][data-sesi="${sesi}"]`);
                            
                            // Set existing ID
                            cell.find('.existing-id').val(data.id_jadwal);
                            
                            // Set mata pelajaran
                            const mapelSelect = cell.find('.mapel-select');
                            mapelSelect.val(data.id_mata_pelajaran);
                            
                            // Load and set guru
                            loadGuruForMapel(data.id_mata_pelajaran, hari, sesi, data.id_guru);
                            
                            // Mark as existing
                            cell.addClass('existing');
                            cell.find('.status-indicator.existing').show();
                            cell.find('.cell-actions').show();
                            
                            loadedCount++;
                        }
                    }
                    
                    updateCounters();
                    showToast('success', `${loadedCount} jadwal berhasil dimuat`);
                } else {
                    showToast('info', 'Tidak ada jadwal yang ditemukan untuk kelas ini');
                }
            },
            error: function() {
                showToast('error', 'Gagal memuat jadwal');
            },
            complete: function() {
                $('.loading-overlay').remove();
            }
        });
    }

    // Enhanced clear function
    $('#btnClearJadwalTable').on('click', function() {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin membersihkan semua jadwal? Data yang belum disimpan akan hilang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bersihkan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ffc107'
        }).then((result) => {
            if (result.isConfirmed) {
                clearJadwalTable();
                updateCounters();
                showToast('success', 'Jadwal berhasil dibersihkan');
            }
        });
    });

    // Enhanced clear function
    function clearJadwalTable() {
        $('.mapel-select').val('');
        $('.guru-select').html('<option value="">Pilih Guru</option>');
        $('.existing-id').val('');
        $('.jadwal-cell').removeClass('filled existing modified');
        $('.status-indicator').hide();
        $('.cell-actions').hide();
    }

    // Enhanced counter update with better visual feedback
    function updateCounters() {
        let filledCount = 0;
        let modifiedCount = 0;
        let existingCount = 0;
        const totalCount = 36; // 6 sesi x 6 hari

        $('.jadwal-cell').each(function() {
            const mapelVal = $(this).find('.mapel-select').val();
            const guruVal = $(this).find('.guru-select').val();
            const existingId = $(this).find('.existing-id').val();
            
            if (mapelVal && guruVal) {
                filledCount++;
                
                if (existingId) {
                    // Check if modified
                    const originalMapel = $(this).data('original-mapel');
                    const originalGuru = $(this).data('original-guru');
                    
                    if (originalMapel && originalGuru && 
                        (originalMapel != mapelVal || originalGuru != guruVal)) {
                        $(this).removeClass('existing').addClass('modified');
                        $(this).find('.status-indicator.existing').hide();
                        $(this).find('.status-indicator.modified').show();
                        modifiedCount++;
                    } else {
                        $(this).removeClass('modified').addClass('existing');
                        $(this).find('.status-indicator.modified').hide();
                        $(this).find('.status-indicator.existing').show();
                        existingCount++;
                    }
                } else {
                    $(this).removeClass('existing modified').addClass('filled');
                    $(this).find('.status-indicator.existing, .status-indicator.modified').hide();
                    $(this).find('.status-indicator.complete').show();
                }
                
                $(this).find('.cell-actions').show();
            } else {
                $(this).removeClass('filled existing modified');
                $(this).find('.status-indicator').hide();
                $(this).find('.cell-actions').hide();
            }
        });

        const percentage = Math.round((filledCount / totalCount) * 100);

        $('#filledCount').text(filledCount);
        $('#emptyCount').text(totalCount - filledCount);
        $('#progressPercent').text(percentage + '%');
        $('#saveCount').text(filledCount - existingCount + modifiedCount);
        
        // Update progress circle
        const progressCircle = $('.progress-circle');
        const degree = (percentage / 100) * 360;
        progressCircle.css('background', `conic-gradient(#28a745 ${degree}deg, #e9ecef ${degree}deg)`);
        
        // Show/hide save info
        const changesCount = filledCount - existingCount + modifiedCount;
        if (changesCount > 0) {
            $('#saveInfo').show();
        } else {
            $('#saveInfo').hide();
        }
        
        // Check for conflicts
        checkScheduleConflicts();
    }

    // Enhanced conflict detection
    function checkScheduleConflicts() {
        let hasConflicts = false;
        const conflicts = [];
        
        // Check guru conflicts across different classes at same time
        $('.jadwal-cell').each(function() {
            const hari = $(this).data('hari');
            const sesi = $(this).data('sesi');
            const guruId = $(this).find('.guru-select').val();
            
            if (guruId) {
                // This would need server-side validation for real conflict detection
                // For now, just visual indication
                $(this).removeClass('conflict-highlight');
            }
        });
        
        if (hasConflicts) {
            $('#conflictWarning').show();
        } else {
            $('#conflictWarning').hide();
        }
    }

    // Enhanced mata pelajaran change handler
    $(document).on('change', '.mapel-select', function() {
        const mataPelajaranId = $(this).val();
        const hari = $(this).data('hari');
        const sesi = $(this).data('sesi');
        const cell = $(this).closest('.jadwal-cell');
        
        // Store original values for modification detection
        if (!cell.data('original-mapel')) {
            cell.data('original-mapel', mataPelajaranId);
        }
        
        loadGuruForMapel(mataPelajaranId, hari, sesi);
        updateCounters();
    });

    // Enhanced guru change handler
    $(document).on('change', '.guru-select', function() {
        const guruId = $(this).val();
        const cell = $(this).closest('.jadwal-cell');
        
        // Store original values for modification detection
        if (!cell.data('original-guru')) {
            cell.data('original-guru', guruId);
        }
        
        updateCounters();
    });

    // Enhanced guru loading with better error handling
    function loadGuruForMapel(mataPelajaranId, hari, sesi, selectedGuruId = null) {
        const guruSelect = $(`.guru-select[data-hari="${hari}"][data-sesi="${sesi}"]`);
        
        if (mataPelajaranId) {
            guruSelect.html('<option value="">Loading...</option>').prop('disabled', true);
            
            $.ajax({
                url: `/jadwal-pelajaran/guru-by-mapel/${mataPelajaranId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data.length > 0) {
                        let options = '<option value="">Pilih Guru</option>';
                        response.data.forEach(function(guru) {
                            const selected = selectedGuruId && guru.id_guru == selectedGuruId ? 'selected' : '';
                            options += `<option value="${guru.id_guru}" ${selected}>${guru.nama_lengkap}</option>`;
                        });
                        guruSelect.html(options);
                    } else {
                        guruSelect.html('<option value="">Tidak ada guru tersedia</option>');
                        showToast('warning', 'Tidak ada guru yang mengajar mata pelajaran ini');
                    }
                },
                error: function() {
                    guruSelect.html('<option value="">Error loading guru</option>');
                    showToast('error', 'Gagal memuat data guru');
                },
                complete: function() {
                    guruSelect.prop('disabled', false);
                    setTimeout(updateCounters, 100);
                }
            });
        } else {
            guruSelect.html('<option value="">Pilih Guru</option>').prop('disabled', false);
        }
    }

    // Cell action handlers
    $(document).on('click', '.btn-cell-action.clear', function() {
        const cell = $(this).closest('.jadwal-cell');
        const hari = cell.data('hari');
        const sesi = cell.data('sesi');
        
        Swal.fire({
            title: 'Hapus Jadwal',
            text: 'Apakah Anda yakin ingin menghapus jadwal ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                cell.find('.mapel-select').val('');
                cell.find('.guru-select').html('<option value="">Pilih Guru</option>');
                cell.find('.existing-id').val('');
                cell.removeClass('filled existing modified');
                cell.find('.status-indicator').hide();
                cell.find('.cell-actions').hide();
                
                updateCounters();
                showToast('success', `Jadwal ${hari} sesi ${sesi} berhasil dihapus`);
            }
        });
    });

    // Enhanced form submission with better validation and feedback
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
                const matches = key.match(/jadwal\[(\w+)\]\[(\d+)\]\[(\w+)\]/);
                if (matches) {
                    const [, hari, sesi, field] = matches;
                    
                    if (!data.jadwal[hari]) data.jadwal[hari] = {};
                    if (!data.jadwal[hari][sesi]) data.jadwal[hari][sesi] = {};
                    
                    data.jadwal[hari][sesi][field] = value;
                }
            }
        }
        
        // Count changes
        let newCount = 0;
        let updateCount = 0;
        let deleteCount = 0;
        
        for (const hari in data.jadwal) {
            for (const sesi in data.jadwal[hari]) {
                const entry = data.jadwal[hari][sesi];
                if (entry.id_mata_pelajaran && entry.id_guru) {
                    if (entry.id_jadwal) {
                        updateCount++;
                    } else {
                        newCount++;
                    }
                } else if (entry.id_jadwal) {
                    deleteCount++;
                }
            }
        }
        
        const totalChanges = newCount + updateCount + deleteCount;
        
        if (totalChanges === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Tidak Ada Perubahan',
                text: 'Tidak ada perubahan yang perlu disimpan!'
            });
            return;
        }
        
        // Enhanced confirmation dialog
        let confirmText = `
            <div class="text-start">
                <p><strong>Ringkasan Perubahan:</strong></p>
                <ul class="list-unstyled">
        `;
        
        if (newCount > 0) {
            confirmText += `<li><i class="bi bi-plus-circle text-success me-2"></i>${newCount} jadwal baru</li>`;
        }
        if (updateCount > 0) {
            confirmText += `<li><i class="bi bi-pencil text-warning me-2"></i>${updateCount} jadwal diperbarui</li>`;
        }
        if (deleteCount > 0) {
            confirmText += `<li><i class="bi bi-trash text-danger me-2"></i>${deleteCount} jadwal dihapus</li>`;
        }
        
        confirmText += `
                </ul>
                <p class="text-muted">Lanjutkan penyimpanan?</p>
            </div>
        `;
        
        Swal.fire({
            title: 'Konfirmasi Penyimpanan',
            html: confirmText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                submitScheduleData(data);
            }
        });
    });

    // Enhanced submission function
    function submitScheduleData(data) {
        const submitBtn = $('#btnSubmitMassal');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i><span>Menyimpan...</span>');
        
        $.ajax({
            url: '/jadwal-pelajaran/store-massal',
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `
                            <div class="text-start">
                                <p>${response.message}</p>
                                ${response.summary ? `<small class="text-muted">${response.summary}</small>` : ''}
                            </div>
                        `,
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        $('#modalJadwalMassal').modal('hide');
                        location.reload();
                    });
                } else {
                    handleSubmissionError(response);
                }
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat menyimpan';
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
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }

    // Handle submission errors
    function handleSubmissionError(response) {
        if (response.conflicts && response.conflicts.length > 0) {
            let conflictMessage = '<div class="text-start"><p><strong>Terdapat konflik jadwal:</strong></p><ul>';
            response.conflicts.forEach(function(conflict) {
                conflictMessage += `<li>${conflict}</li>`;
            });
            conflictMessage += '</ul></div>';
            
            Swal.fire({
                icon: 'error',
                title: 'Konflik Jadwal',
                html: conflictMessage,
                width: '600px'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Terjadi kesalahan'
            });
        }
    }

    // Reset modal when closed
    $('#modalJadwalMassal').on('hidden.bs.modal', function() {
        $('#formJadwalMassal')[0].reset();
        $('#jadwalTableContainer').hide();
        $('#btnSubmitMassal').prop('disabled', true);
        $('#btnClearJadwalTable, #btnCopyFromTemplate').prop('disabled', true);
        $('#selectedClassInfo').hide();
        clearJadwalTable();
        updateCounters();
    });

    // Enhanced toast notification
    function showToast(type, message, duration = 3000) {
        const bgClass = {
            'success': 'bg-success',
            'error': 'bg-danger',
            'warning': 'bg-warning',
            'info': 'bg-info'
        }[type] || 'bg-secondary';
        
        const icon = {
            'success': 'check-circle',
            'error': 'exclamation-triangle',
            'warning': 'exclamation-triangle',
            'info': 'info-circle'
        }[type] || 'info-circle';
        
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
        }, duration);
    }
});

// Enhanced view functions
function viewScheduleDetail(id) {
    viewJadwal(id);
}

function editJadwal(id) {
    $.ajax({
        url: `/jadwal-pelajaran/${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const jadwal = response.data;
                
                // Open management modal with this class selected
                $('#modalJadwalMassal').modal('show');
                
                // Wait for modal to be shown, then select the class
                $('#modalJadwalMassal').on('shown.bs.modal', function() {
                    $('#id_kelas_massal').val(jadwal.id_kelas).trigger('change');
                    
                    // After class is loaded, highlight the specific schedule
                    setTimeout(() => {
                        const cell = $(`.jadwal-cell[data-hari="${jadwal.hari}"][data-sesi="${jadwal.sesi}"]`);
                        cell.addClass('fade-in');
                        
                        // Scroll to the cell
                        cell[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        // Highlight temporarily
                        cell.css('box-shadow', '0 0 20px rgba(102, 126, 234, 0.5)');
                        setTimeout(() => {
                            cell.css('box-shadow', '');
                        }, 2000);
                    }, 1000);
                });
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
                
                $('#detail-kelas').text(`${jadwal.kelas.tingkat || ''} ${jadwal.kelas.nama_kelas || ''}`);
                $('#detail-hari').text(jadwal.hari ? jadwal.hari.charAt(0).toUpperCase() + jadwal.hari.slice(1) : '-');
                $('#detail-mapel').text(jadwal.mata_pelajaran ? jadwal.mata_pelajaran.nama : '-');
                $('#detail-guru').text(jadwal.guru ? jadwal.guru.nama_lengkap : '-');
                
                // Format time and sessions
                const sesiWaktu = {
                    1: {start: '07:45', end: '08:30'},
                    2: {start: '08:30', end: '09:15'},
                    3: {start: '09:15', end: '10:00'},
                    4: {start: '10:15', end: '11:00'},
                    5: {start: '11:00', end: '11:45'},
                    6: {start: '11:45', end: '12:30'}
                };
                
                const sesi = jadwal.sesi || 1;
                $('#detail-sesi').text(`Sesi ${sesi}`);
                $('#detail-waktu').text(`${sesiWaktu[sesi].start} - ${sesiWaktu[sesi].end}`);
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
        text: 'Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.',
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
                            text: response.message,
                            timer: 2000,
                            timerProgressBar: true
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
