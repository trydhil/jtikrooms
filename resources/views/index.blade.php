@extends('layouts.app')

@section('title', 'Dasher - Dashboard Ruangan')

@section('content')
<div class="header">
    <h2><i class="fas fa-th-list me-2"></i>Dashboard Ruangan</h2>
    <div class="user-info">
        <div class="user-avatar-small"><i class="fas fa-user"></i></div>
    </div>
</div>

<div class="search-bar">
    <input type="text" class="search-input" placeholder="Cari ruangan..." />
    <button class="search-button"><i class="fas fa-search"></i></button>
</div>

<div class="filter-bar">
    <button class="filter-button active" data-filter="kelas">Ruangan Kelas</button>
    <button class="filter-button" data-filter="lab">Laboratorium</button>
    <button class="filter-button" data-filter="other">Ruangan Lainnya</button>
</div>

<div class="room-list">
    <!-- Room cards will be inserted here by JS -->
</div>

<div class="card mt-4">
    <div class="card-header bg-white py-3">
        <h3 class="card-title mb-0" style="color: var(--oranye)">
            <i class="fas fa-map-marked-alt me-2"></i>Peta Ruangan
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="map-container position-relative">
            <div class="position-absolute top-0 end-0 m-3 z-1">
                <div class="btn-group">
                    <button class="btn btn-light active" onclick="changeFloor(1)">Lantai 1</button>
                    <button class="btn btn-light" onclick="changeFloor(2)">Lantai 2</button>
                </div>
            </div>
            <img id="floorMap" src="{{ asset('img/lantai1.png') }}" alt="Floor Plan" class="w-100" onload="adjustMapContainer(this)" />
        </div>
    </div>
</div>
@endsection
