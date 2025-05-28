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