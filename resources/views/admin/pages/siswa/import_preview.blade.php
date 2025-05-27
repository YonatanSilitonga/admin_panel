@extends('layouts.admin-layout')

@section('title', 'Import Data Siswa')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ route('siswa.index') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Data Siswa
                        </a>
                        <span class="fs-5 text-muted">/ Import Data Siswa</span>
                    </h1>
                    <p class="mb-2">Import data siswa dari file Excel dengan validasi otomatis</p>
                </header>

                <div class="data">
                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Import Errors -->
                    @if(session('import_errors') && count(session('import_errors')) > 0)
                        <div class="alert alert-danger">
                            <h6><i class="bi bi-exclamation-triangle-fill me-2"></i>Error Import:</h6>
                            <ul class="mb-0">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Import Failures -->
                    @if(session('import_failures') && count(session('import_failures')) > 0)
                        <div class="alert alert-warning">
                            <h6><i class="bi bi-exclamation-triangle-fill me-2"></i>Validasi Gagal:</h6>
                            @foreach(session('import_failures') as $failure)
                                <div class="mb-2">
                                    <strong>Baris {{ $failure['row'] }}:</strong>
                                    <ul class="mb-0">
                                        @foreach($failure['errors'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Main Content -->
                    <div class="row">
                        <!-- Upload Section -->
                        <div class="col-lg-8">
                            <!-- Upload Form -->
                            <div class="p-4 pt-1 rounded-4 bg-white shadow-sm mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success rounded-circle p-2 me-3">
                                        <i class="bi bi-upload text-white fs-5"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Upload File Import</h5>
                                        <p class="text-muted mb-0">Pilih file Excel untuk mengimport data siswa</p>
                                    </div>
                                </div>

                                <form id="importForm" action="{{ route('siswa.import.process') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="file" class="form-label">Pilih File Excel</label>
                                        <input type="file" name="file" id="file" 
                                            class="form-control @error('file') is-invalid @enderror" 
                                            accept=".xlsx,.xls,.csv" required>
                                        @error('file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Format yang didukung: .xlsx, .xls, .csv (Maksimal 2MB)
                                        </div>
                                    </div>

                                    <!-- File Validation Result -->
                                    <div id="fileValidationResult" class="mb-3" style="display: none;"></div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-3 flex-wrap">
                                        <!-- <button type="button" id="validateBtn" class="btn btn-outline-primary">
                                            <i class="bi bi-check-circle me-1"></i> Validasi File
                                        </button> -->
                                        <button type="submit" id="importBtn" class="btn btn-success">
                                            <i class="bi bi-upload me-1"></i> Import Data
                                        </button>
                                        <a href="{{ route('siswa.import.template') }}" class="btn btn-outline-success">
                                            <i class="bi bi-download me-1"></i> Download Template
                                        </a>
                                    </div>
                                </form>
                            </div>

                            <!-- Import Instructions -->
                            <div class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info rounded-circle p-2 me-3">
                                        <i class="bi bi-info-circle text-white fs-5"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Petunjuk Import</h5>
                                        <p class="text-muted mb-0">Panduan untuk mengimport data siswa</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="border-start border-success border-3 ps-3 mb-3">
                                            <h6 class="text-success mb-2">Kolom Wajib:</h6>
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-1"><i class="bi bi-check-circle text-success me-2"></i><strong>nama</strong> - Nama lengkap siswa</li>
                                                <li class="mb-1"><i class="bi bi-check-circle text-success me-2"></i><strong>nis</strong> - Nomor Induk Siswa (unik)</li>
                                                <li class="mb-1"><i class="bi bi-check-circle text-success me-2"></i><strong>kelas</strong> - Nama kelas yang tersedia</li>
                                                <li class="mb-1"><i class="bi bi-check-circle text-success me-2"></i><strong>nama_orang_tua</strong> - Nama orang tua terdaftar</li>
                                                <li class="mb-1"><i class="bi bi-check-circle text-success me-2"></i><strong>jenis_kelamin</strong> - Laki-laki/Perempuan</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border-start border-warning border-3 ps-3 mb-3">
                                            <h6 class="text-warning mb-2">Kolom Opsional:</h6>
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-1"><i class="bi bi-dash-circle text-muted me-2"></i><strong>tempat_lahir</strong> - Tempat lahir</li>
                                                <li class="mb-1"><i class="bi bi-dash-circle text-muted me-2"></i><strong>tanggal_lahir</strong> - Format: YYYY-MM-DD</li>
                                                <li class="mb-1"><i class="bi bi-dash-circle text-muted me-2"></i><strong>alamat</strong> - Alamat lengkap</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-3">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>Penting:</strong> Pastikan nama kelas dan nama orang tua sesuai dengan data yang tersedia di sistem.
                                    Status siswa akan otomatis ditentukan berdasarkan status tahun ajaran kelas.
                                </div>
                            </div>
                        </div>

                        <!-- Available Data Info -->
                        <div class="col-lg-4">
                            <!-- Academic Year Info -->
                            @if($tahunAjaranAktif)
                                <div class="p-4 pt-1 rounded-4 bg-white shadow-sm mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary rounded-circle p-2 me-3">
                                            <i class="bi bi-calendar-check text-white fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Tahun Ajaran Aktif</h6>
                                            <p class="text-muted mb-0 small">Tahun ajaran yang sedang berjalan</p>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h5 class="text-primary mb-1">{{ $tahunAjaranAktif->nama_tahun_ajaran }}</h5>
                                        <small class="text-muted">{{ $tahunAjaranAktif->tanggal_mulai->format('d/m/Y') }} - {{ $tahunAjaranAktif->tanggal_selesai->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>Peringatan:</strong> Tidak ada tahun ajaran aktif
                                </div>
                            @endif

                            <!-- Available Classes -->
                            <div class="p-4 pt-1 rounded-4 bg-white shadow-sm mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle p-2 me-3">
                                            <i class="bi bi-building text-white fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Kelas Tersedia</h6>
                                            <p class="text-muted mb-0 small">{{ count($availableKelas) }} kelas</p>
                                        </div>
                                    </div>
                                    <span class="badge bg-success">{{ count($availableKelas) }}</span>
                                </div>
                                
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="border-0">Kelas</th>
                                                <th class="border-0">Status</th>
                                                <th class="border-0">Siswa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($availableKelas as $kelas)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <strong>{{ $kelas['nama_kelas'] }}</strong>
                                                            <br><small class="text-muted">{{ $kelas['tahun_ajaran'] }}</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($kelas['status'] == 'Aktif')
                                                            <span class="badge bg-success">{{ $kelas['status'] }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $kelas['status'] }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $kelas['jumlah_siswa'] }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">Tidak ada kelas tersedia</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Available Parents -->
                            <div class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning rounded-circle p-2 me-3">
                                            <i class="bi bi-people text-white fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Orang Tua Tersedia</h6>
                                            <p class="text-muted mb-0 small">{{ count($availableOrangTua) }} orang tua</p>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning">{{ count($availableOrangTua) }}</span>
                                </div>
                                
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="border-0">Nama</th>
                                                <th class="border-0">Status</th>
                                                <th class="border-0">Anak</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($availableOrangTua as $orangTua)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <strong>{{ $orangTua['nama_lengkap'] }}</strong>
                                                            @if($orangTua['nomor_telepon'] != '-')
                                                                <br><small class="text-muted">{{ $orangTua['nomor_telepon'] }}</small>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($orangTua['status'] == 'Aktif')
                                                            <span class="badge bg-success">{{ $orangTua['status'] }}</span>
                                                        @elseif($orangTua['status'] == 'Pending')
                                                            <span class="badge bg-warning">{{ $orangTua['status'] }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $orangTua['status'] }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $orangTua['jumlah_anak'] }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">Tidak ada orang tua tersedia</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@section('js')
<!-- Pastikan jQuery dimuat terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Pastikan SweetAlert2 dimuat -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    console.log('Document ready - Import preview page loaded');
    
    const fileInput = $('#file');
    const validateBtn = $('#validateBtn');
    const importBtn = $('#importBtn');
    const validationResult = $('#fileValidationResult');
    
    // Debug: Check if elements exist
    console.log('File input:', fileInput.length);
    console.log('Validate button:', validateBtn.length);
    console.log('Import button:', importBtn.length);
    
    // Initially disable import button only
    importBtn.prop('disabled', true);
    
    // Enable validate button when file is selected
    fileInput.on('change', function() {
        console.log('File input changed');
        console.log('Files selected:', this.files.length);
        
        if (this.files.length > 0) {
            const file = this.files[0];
            console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);
            
            // Enable validate button
            validateBtn.prop('disabled', false);
            validateBtn.removeClass('btn-secondary').addClass('btn-outline-primary');
            
            // Disable import button until validation
            importBtn.prop('disabled', true);
            importBtn.removeClass('btn-success').addClass('btn-secondary');
            
            // Hide previous validation result
            validationResult.hide();
            
            console.log('Validate button enabled');
        } else {
            // No file selected
            validateBtn.prop('disabled', true);
            validateBtn.removeClass('btn-outline-primary').addClass('btn-secondary');
            
            importBtn.prop('disabled', true);
            importBtn.removeClass('btn-success').addClass('btn-secondary');
            
            validationResult.hide();
            
            console.log('No file selected - buttons disabled');
        }
    });
    
    // Validate file
    validateBtn.on('click', function() {
        console.log('Validate button clicked');
        
        const file = fileInput[0].files[0];
        if (!file) {
            Swal.fire('Error', 'Pilih file terlebih dahulu', 'error');
            return;
        }
        
        console.log('Starting validation for file:', file.name);
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Show loading state
        validateBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i> Validating...');
        
        $.ajax({
            url: '{{ route("siswa.import.validate") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Validation response:', response);
                
                if (response.success) {
                    validationResult.html(`
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>File Valid!</strong><br>
                            Jumlah baris data: ${response.row_count}<br>
                            Header ditemukan: ${response.headers.join(', ')}
                        </div>
                    `).show();
                    
                    // Enable import button
                    importBtn.prop('disabled', false);
                    importBtn.removeClass('btn-secondary').addClass('btn-success');
                    
                    console.log('File validation successful - import button enabled');
                } else {
                    validationResult.html(`
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle-fill me-2"></i>
                            <strong>File Tidak Valid!</strong><br>
                            ${response.message}
                        </div>
                    `).show();
                    
                    // Keep import button disabled
                    importBtn.prop('disabled', true);
                    importBtn.removeClass('btn-success').addClass('btn-secondary');
                    
                    console.log('File validation failed');
                }
            },
            error: function(xhr, status, error) {
                console.error('Validation error:', xhr.responseText);
                
                validationResult.html(`
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle-fill me-2"></i>
                        <strong>Error!</strong><br>
                        Gagal memvalidasi file: ${error}
                    </div>
                `).show();
                
                importBtn.prop('disabled', true);
                importBtn.removeClass('btn-success').addClass('btn-secondary');
            },
            complete: function() {
                // Reset validate button
                validateBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Validasi File');
                console.log('Validation complete');
            }
        });
    });
    
    // Import form submission
    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        console.log('Import form submitted');
        
        Swal.fire({
            title: 'Konfirmasi Import',
            text: 'Apakah Anda yakin ingin mengimport data siswa?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Import!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Import confirmed');
                importBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i> Importing...');
                this.submit();
            }
        });
    });
    
    // Test button functionality
    console.log('Initial button states:');
    console.log('Validate button disabled:', validateBtn.prop('disabled'));
    console.log('Import button disabled:', importBtn.prop('disabled'));
});
</script>
@endsection
