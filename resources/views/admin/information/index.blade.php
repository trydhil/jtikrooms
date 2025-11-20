@extends('layouts.app')

@section('title', 'Informasi JTIK - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
 

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-info-circle me-2"></i>Informasi JTIK</h1>
                <p class="welcome-text">Kelola informasi dan profil Jurusan Teknik Informatika & Komputer</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.information.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Informasi
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

    <!-- Informasi Utama -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><i class="fas fa-university me-2"></i>Informasi Umum</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <label class="info-label"><i class="fas fa-map-marker-alt me-2"></i>Alamat</label>
                        <p class="info-value">{{ $about->info['address'] ?? '-' }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <label class="info-label"><i class="fas fa-phone me-2"></i>Telepon</label>
                        <p class="info-value">{{ $about->info['phone'] ?? '-' }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <label class="info-label"><i class="fas fa-envelope me-2"></i>Email</label>
                        <p class="info-value">{{ $about->info['email'] ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <label class="info-label"><i class="fas fa-graduation-cap me-2"></i>Akreditasi</label>
                        <p class="info-value">
                            <span class="badge bg-success">{{ $about->info['accreditation'] ?? '-' }}</span>
                        </p>
                    </div>
                    <div class="info-item mb-3">
                        <label class="info-label"><i class="fas fa-map me-2"></i>Google Maps</label>
                        <p class="info-value">
                            @if(isset($about->info['maps_url']))
                                <a href="{{ $about->info['maps_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>Lihat di Maps
                                </a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Jam Operasional -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3><i class="fas fa-clock me-2"></i>Jam Operasional</h3>
                </div>
                <div class="card-body">
                    @if(isset($about->info['operational_hours']) && count($about->info['operational_hours']) > 0)
                        @foreach($about->info['operational_hours'] as $schedule)
                            <div class="schedule-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="schedule-day">{{ $schedule['day'] }}</span>
                                <span class="schedule-hours {{ $schedule['hours'] == 'Libur' ? 'text-muted' : 'text-primary fw-bold' }}">
                                    {{ $schedule['hours'] }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada data jam operasional</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Program Studi -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3><i class="fas fa-book me-2"></i>Program Studi</h3>
                </div>
                <div class="card-body">
                    @if(isset($about->info['study_programs']) && count($about->info['study_programs']) > 0)
                        <div class="program-list">
                            @foreach($about->info['study_programs'] as $program)
                                <div class="program-item d-flex align-items-center py-2">
                                    <i class="fas fa-check text-success me-3"></i>
                                    <span>{{ $program }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Belum ada data program studi</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sejarah & Visi Misi -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-history me-2"></i>Sejarah & Profil</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="section-title">Sejarah</h5>
                            <p class="text-muted">{{ $about->detail['history'] ?? 'Belum ada data sejarah' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="section-title">Visi</h5>
                            <p class="text-primary fw-semibold">{{ $about->detail['vision'] ?? 'Belum ada data visi' }}</p>
                            
                            <h5 class="section-title mt-4">Misi</h5>
                            @if(isset($about->detail['missions']) && count($about->detail['missions']) > 0)
                                <ul class="mission-list">
                                    @foreach($about->detail['missions'] as $mission)
                                        <li class="mission-item">{{ $mission }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">Belum ada data misi</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pencapaian -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3><i class="fas fa-trophy me-2"></i>Pencapaian</h3>
                </div>
                <div class="card-body">
                    @if(isset($about->detail['achievements']) && count($about->detail['achievements']) > 0)
                        <div class="achievement-list">
                            @foreach($about->detail['achievements'] as $achievement)
                                <div class="achievement-item d-flex align-items-start py-2 border-bottom">
                                    <div class="achievement-year bg-primary text-white rounded px-2 py-1 me-3">
                                        {{ $achievement['year'] }}
                                    </div>
                                    <div class="achievement-title flex-grow-1">
                                        {{ $achievement['title'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Belum ada data pencapaian</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dosen & Staf -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3><i class="fas fa-users me-2"></i>Dosen & Staf</h3>
                </div>
                <div class="card-body">
                    <h6 class="text-primary mb-3">Dosen</h6>
                    @if(isset($about->detail['lecturers']) && count($about->detail['lecturers']) > 0)
                        @foreach($about->detail['lecturers'] as $lecturer)
                            <div class="staff-item py-1">
                                <i class="fas fa-user-graduate me-2 text-muted"></i>
                                {{ $lecturer }}
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada data dosen</p>
                    @endif

                    <hr class="my-3">

                    <h6 class="text-primary mb-3">Staf Administrasi</h6>
                    @if(isset($about->detail['staff']) && count($about->detail['staff']) > 0)
                        @foreach($about->detail['staff'] as $staff)
                            <div class="staff-item py-1">
                                <i class="fas fa-user-tie me-2 text-muted"></i>
                                {{ $staff }}
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada data staf</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-item {
    padding: 0.5rem 0;
}

.info-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
    display: block;
}

.info-value {
    color: #6b7280;
    margin: 0;
}

.section-title {
    color: #374151;
    font-weight: 600;
    margin-bottom: 1rem;
    border-bottom: 2px solid #3b82f6;
    padding-bottom: 0.5rem;
}

.mission-list {
    list-style: none;
    padding-left: 0;
}

.mission-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
    position: relative;
    padding-left: 1.5rem;
}

.mission-item:before {
    content: "•";
    color: #3b82f6;
    font-weight: bold;
    position: absolute;
    left: 0;
}

.achievement-year {
    font-size: 0.8rem;
    font-weight: 600;
    min-width: 50px;
    text-align: center;
}

.staff-item {
    font-size: 0.9rem;
    color: #6b7280;
}

.program-item {
    border-bottom: 1px solid #f3f4f6;
}

.schedule-item:last-child,
.program-item:last-child,
.achievement-item:last-child {
    border-bottom: none;
}
</style>
@endsection