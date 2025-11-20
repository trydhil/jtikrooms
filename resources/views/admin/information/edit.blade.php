@extends('layouts.app')

@section('title', 'Edit Informasi JTIK - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
 

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-edit me-2"></i>Edit Informasi JTIK</h1>
                <p class="welcome-text">Perbarui informasi dan profil Jurusan Teknik Informatika & Komputer</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.information.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
        <div class="header-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="decoration-circle circle-3"></div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.information.update') }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Hero Stats Section -->
<div class="card mb-4">
    <div class="card-header">
        <h3><i class="fas fa-chart-line me-2"></i>Hero Section Stats</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Judul Hero</label>
                    <input type="text" class="form-control" name="hero_stats[title]" 
                           value="{{ $about->hero_stats['title'] ?? 'Jurusan Teknik Informatika dan Komputer' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Subjudul Hero</label>
                    <textarea class="form-control" name="hero_stats[subtitle]" rows="2">{{ $about->hero_stats['subtitle'] ?? 'Menciptakan generasi unggul di bidang teknologi informasi dan komputer yang siap bersaing di era digital' }}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Jumlah Mahasiswa</label>
                    <input type="text" class="form-control" name="hero_stats[students]" 
                           value="{{ $about->hero_stats['students'] ?? '500+' }}" placeholder="Contoh: 500+">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah Dosen</label>
                    <input type="text" class="form-control" name="hero_stats[lecturers]" 
                           value="{{ $about->hero_stats['lecturers'] ?? '25+' }}" placeholder="Contoh: 25+">
                </div>
                <div class="mb-3">
                    <label class="form-label">Badge Akreditasi</label>
                    <input type="text" class="form-control" name="hero_stats[accreditation_badge]" 
                           value="{{ $about->hero_stats['accreditation_badge'] ?? 'A' }}" placeholder="Contoh: A">
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- Informasi Umum -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-university me-2"></i>Informasi Umum</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="info[address]" rows="2">{{ $about->info['address'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" name="info[phone]" 
                                   value="{{ $about->info['phone'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="info[email]" 
                                   value="{{ $about->info['email'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">URL Google Maps</label>
                            <input type="url" class="form-control" name="info[maps_url]" 
                                   value="{{ $about->info['maps_url'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Akreditasi</label>
                            <input type="text" class="form-control" name="info[accreditation]" 
                                   value="{{ $about->info['accreditation'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jam Operasional -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-clock me-2"></i>Jam Operasional</h3>
            </div>
            <div class="card-body">
                <div id="operational-hours">
                    @foreach($about->info['operational_hours'] ?? [] as $index => $schedule)
                    <div class="row mb-2 operational-hour-row">
                        <div class="col-md-6">
                            <label class="form-label">Hari</label>
                            <input type="text" class="form-control" name="info[operational_hours][{{ $index }}][day]" 
                                   value="{{ $schedule['day'] ?? '' }}" placeholder="Contoh: Senin - Kamis">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Jam</label>
                            <input type="text" class="form-control" name="info[operational_hours][{{ $index }}][hours]" 
                                   value="{{ $schedule['hours'] ?? '' }}" placeholder="Contoh: 07:00 - 16:00">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm remove-hour w-100">×</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary btn-sm" id="add-hour">
                    <i class="fas fa-plus me-1"></i>Tambah Jadwal
                </button>
            </div>
        </div>

        <!-- Program Studi -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-book me-2"></i>Program Studi</h3>
            </div>
            <div class="card-body">
                <div id="study-programs">
                    @foreach($about->info['study_programs'] ?? [] as $index => $program)
                    <div class="input-group mb-2 program-row">
                        <input type="text" class="form-control" name="info[study_programs][{{ $index }}]" 
                               value="{{ $program }}" placeholder="Nama program studi">
                        <button type="button" class="btn btn-danger remove-program">×</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary btn-sm" id="add-program">
                    <i class="fas fa-plus me-1"></i>Tambah Program Studi
                </button>
            </div>
        </div>

        <!-- Sejarah & Visi Misi -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-history me-2"></i>Sejarah & Visi Misi</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Sejarah</label>
                    <textarea class="form-control" name="detail[history]" rows="4" placeholder="Tulis sejarah JTIK">{{ $about->detail['history'] ?? '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Visi</label>
                    <textarea class="form-control" name="detail[vision]" rows="3" placeholder="Tulis visi JTIK">{{ $about->detail['vision'] ?? '' }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Misi</label>
                    <div id="missions">
                        @foreach($about->detail['missions'] ?? [] as $index => $mission)
                        <div class="input-group mb-2 mission-row">
                            <input type="text" class="form-control" name="detail[missions][{{ $index }}]" 
                                   value="{{ $mission }}" placeholder="Teks misi">
                            <button type="button" class="btn btn-danger remove-mission">×</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" id="add-mission">
                        <i class="fas fa-plus me-1"></i>Tambah Misi
                    </button>
                </div>
            </div>
        </div>

        <!-- Pencapaian -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-trophy me-2"></i>Pencapaian & Penghargaan</h3>
            </div>
            <div class="card-body">
                <div id="achievements">
                    @foreach($about->detail['achievements'] ?? [] as $index => $achievement)
                    <div class="row mb-2 achievement-row">
                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>
                            <input type="text" class="form-control" name="detail[achievements][{{ $index }}][year]" 
                                   value="{{ $achievement['year'] ?? '' }}" placeholder="Tahun">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Judul Prestasi</label>
                            <input type="text" class="form-control" name="detail[achievements][{{ $index }}][title]" 
                                   value="{{ $achievement['title'] ?? '' }}" placeholder="Judul prestasi">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm remove-achievement w-100">×</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary btn-sm" id="add-achievement">
                    <i class="fas fa-plus me-1"></i>Tambah Pencapaian
                </button>
            </div>
        </div>

        <!-- Dosen & Staf -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-users me-2"></i>Dosen & Staf</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Dosen Tetap</h6>
                        <div id="lecturers">
                            @foreach($about->detail['lecturers'] ?? [] as $index => $lecturer)
                            <div class="input-group mb-2 lecturer-row">
                                <input type="text" class="form-control" name="detail[lecturers][{{ $index }}]" 
                                       value="{{ $lecturer }}" placeholder="Nama dosen">
                                <button type="button" class="btn btn-danger remove-lecturer">×</button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" id="add-lecturer">
                            <i class="fas fa-plus me-1"></i>Tambah Dosen
                        </button>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Staf Administrasi</h6>
                        <div id="staff">
                            @foreach($about->detail['staff'] ?? [] as $index => $staff)
                            <div class="input-group mb-2 staff-row">
                                <input type="text" class="form-control" name="detail[staff][{{ $index }}]" 
                                       value="{{ $staff }}" placeholder="Nama staf">
                                <button type="button" class="btn btn-danger remove-staff">×</button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" id="add-staff">
                            <i class="fas fa-plus me-1"></i>Tambah Staf
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic form fields functionality
    let hourIndex = {{ count($about->info['operational_hours'] ?? []) }};
    let programIndex = {{ count($about->info['study_programs'] ?? []) }};
    let missionIndex = {{ count($about->detail['missions'] ?? []) }};
    let achievementIndex = {{ count($about->detail['achievements'] ?? []) }};
    let lecturerIndex = {{ count($about->detail['lecturers'] ?? []) }};
    let staffIndex = {{ count($about->detail['staff'] ?? []) }};

    // Add operational hour
    document.getElementById('add-hour')?.addEventListener('click', function() {
        const container = document.getElementById('operational-hours');
        const newRow = document.createElement('div');
        newRow.className = 'row mb-2 operational-hour-row';
        newRow.innerHTML = `
            <div class="col-md-6">
                <label class="form-label">Hari</label>
                <input type="text" class="form-control" name="info[operational_hours][${hourIndex}][day]" placeholder="Contoh: Senin - Kamis">
            </div>
            <div class="col-md-5">
                <label class="form-label">Jam</label>
                <input type="text" class="form-control" name="info[operational_hours][${hourIndex}][hours]" placeholder="Contoh: 07:00 - 16:00">
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger btn-sm remove-hour w-100">×</button>
            </div>
        `;
        container.appendChild(newRow);
        hourIndex++;
    });

    // Add program study
    document.getElementById('add-program')?.addEventListener('click', function() {
        const container = document.getElementById('study-programs');
        const newRow = document.createElement('div');
        newRow.className = 'input-group mb-2 program-row';
        newRow.innerHTML = `
            <input type="text" class="form-control" name="info[study_programs][${programIndex}]" placeholder="Nama program studi">
            <button type="button" class="btn btn-danger remove-program">×</button>
        `;
        container.appendChild(newRow);
        programIndex++;
    });

    // Add mission
    document.getElementById('add-mission')?.addEventListener('click', function() {
        const container = document.getElementById('missions');
        const newRow = document.createElement('div');
        newRow.className = 'input-group mb-2 mission-row';
        newRow.innerHTML = `
            <input type="text" class="form-control" name="detail[missions][${missionIndex}]" placeholder="Teks misi">
            <button type="button" class="btn btn-danger remove-mission">×</button>
        `;
        container.appendChild(newRow);
        missionIndex++;
    });

    // Add achievement
    document.getElementById('add-achievement')?.addEventListener('click', function() {
        const container = document.getElementById('achievements');
        const newRow = document.createElement('div');
        newRow.className = 'row mb-2 achievement-row';
        newRow.innerHTML = `
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <input type="text" class="form-control" name="detail[achievements][${achievementIndex}][year]" placeholder="Tahun">
            </div>
            <div class="col-md-8">
                <label class="form-label">Judul Prestasi</label>
                <input type="text" class="form-control" name="detail[achievements][${achievementIndex}][title]" placeholder="Judul prestasi">
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger btn-sm remove-achievement w-100">×</button>
            </div>
        `;
        container.appendChild(newRow);
        achievementIndex++;
    });

    // Add lecturer
    document.getElementById('add-lecturer')?.addEventListener('click', function() {
        const container = document.getElementById('lecturers');
        const newRow = document.createElement('div');
        newRow.className = 'input-group mb-2 lecturer-row';
        newRow.innerHTML = `
            <input type="text" class="form-control" name="detail[lecturers][${lecturerIndex}]" placeholder="Nama dosen">
            <button type="button" class="btn btn-danger remove-lecturer">×</button>
        `;
        container.appendChild(newRow);
        lecturerIndex++;
    });

    // Add staff
    document.getElementById('add-staff')?.addEventListener('click', function() {
        const container = document.getElementById('staff');
        const newRow = document.createElement('div');
        newRow.className = 'input-group mb-2 staff-row';
        newRow.innerHTML = `
            <input type="text" class="form-control" name="detail[staff][${staffIndex}]" placeholder="Nama staf">
            <button type="button" class="btn btn-danger remove-staff">×</button>
        `;
        container.appendChild(newRow);
        staffIndex++;
    });

    // Remove buttons event delegation
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-hour')) {
            e.target.closest('.operational-hour-row').remove();
        }
        if (e.target.classList.contains('remove-program')) {
            e.target.closest('.program-row').remove();
        }
        if (e.target.classList.contains('remove-mission')) {
            e.target.closest('.mission-row').remove();
        }
        if (e.target.classList.contains('remove-achievement')) {
            e.target.closest('.achievement-row').remove();
        }
        if (e.target.classList.contains('remove-lecturer')) {
            e.target.closest('.lecturer-row').remove();
        }
        if (e.target.classList.contains('remove-staff')) {
            e.target.closest('.staff-row').remove();
        }
    });
});
</script>

<style>
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.input-group {
    display: flex;
    gap: 0.5rem;
}

.input-group .btn {
    flex-shrink: 0;
}

.operational-hour-row,
.achievement-row {
    align-items: end;
}

.btn-secondary {
    background: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background: #5a6268;
    border-color: #545b62;
}
</style>
@endsection