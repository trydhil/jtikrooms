@extends('layouts.app')

@section('title', 'Dashboard Ruangan')

@section('content')
<div class="header">
    <h2><i class="fas fa-th-list me-2"></i>Dashboard List Ruangan</h2>
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
    @forelse($rooms as $room)
        <div class="room-card" 
             x-data="roomCard()"
             x-on:mouseenter="isHovered = true"
             x-on:mouseleave="isHovered = false"
             :class="{ 'ring-2 ring-indigo-500': isHovered }"
             data-status="{{ $room->activeBooking ? 'occupied' : 'available' }}">
            
            <div class="room-image-container">
                <img :src="`/img/ruangan.jpg`" 
                     alt="Room"
                     class="room-image"
                     loading="lazy">
                
                {{-- Badge Status Otomatis dari Relationship --}}
                <div class="room-status-badge {{ $room->activeBooking ? 'status-occupied' : 'status-available' }}">
                    <i class="fas {{ $room->activeBooking ? 'fa-user-lock' : 'fa-check-circle' }}"></i>
                    {{ $room->activeBooking ? 'Terpakai' : 'Tersedia' }}
                </div>
            </div>
            
            <div class="room-info">
                <h3 class="room-name">{{ $room->name }}</h3>
                <p class="room-code text-sm text-gray-500">{{ $room->display_name }}</p>
                
                <div class="room-meta-grid">
                    <div class="meta-item">
                        <i class="fas fa-users"></i> {{ $room->capacity }} Kursi
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-layer-group"></i> Lantai {{ $room->lantai ?? '1' }}
                    </div>
                </div>
                
                <button class="btn-info-detail"
                        x-on:click="checkAvailability('{{ $room->name }}')"
                        :disabled="isBooking">
                    <i class="fas fa-info-circle"></i>
                    <span x-text="isBooking ? 'Checking...' : 'Info Selengkapnya'"></span>
                </button>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-20">
            <p class="text-gray-500">Tidak ada ruangan ditemukan.</p>
        </div>
    @endforelse
</div>
@endsection