@extends('layouts.app')

@section('title', 'Manajemen Komentar - Admin JTIK')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
<style>
    .comments-section {
        background: white; 
        border-radius: 16px; 
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05); 
        border: 1px solid #f1f5f9;
    }
    
    .section-header {
        padding: 1.5rem; 
        background: #f8fafc; 
        border-bottom: 1px solid #f1f5f9;
        display: flex; 
        justify-content: space-between; 
        align-items: center;
    }
    
    .table-custom { 
        width: 100%; 
        border-collapse: collapse; 
    }
    
    .table-custom th {
        background: #f8fafc; 
        padding: 1rem; 
        text-align: left; 
        font-size: 0.8rem;
        color: #64748b; 
        font-weight: 700; 
        text-transform: uppercase; 
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table-custom td { 
        padding: 1rem; 
        border-bottom: 1px solid #f1f5f9; 
        vertical-align: top; 
    }
    
    /* Badges */
    .badge-anon { 
        background: #475569; 
        color: white; 
        font-size: 0.7rem; 
        padding: 2px 6px; 
        border-radius: 4px; 
    }
    
    .type-badge { 
        padding: 4px 10px; 
        border-radius: 20px; 
        font-size: 0.7rem; 
        font-weight: 700; 
        text-transform: uppercase; 
    }
    
    .t-report { 
        background: #fee2e2; 
        color: #991b1b; 
    }
    
    .t-general { 
        background: #eff6ff; 
        color: #1e40af; 
    }
    
    .st-open { 
        background: #fef3c7; 
        color: #b45309; 
        padding: 4px 8px; 
        border-radius: 6px; 
        font-size: 0.75rem; 
        font-weight: 600; 
    }
    
    .st-resolved { 
        background: #dcfce7; 
        color: #166534; 
        padding: 4px 8px; 
        border-radius: 6px; 
        font-size: 0.75rem; 
        font-weight: 600; 
    }
</style>

@section('content')
<div class="content-wrapper">
    
    <!-- HEADER -->
    <div class="admin-header mb-4">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-comments me-2"></i>Manajemen Komentar & Laporan</h1>
                <p class="text-muted">Kelola komentar dan laporan dari pengguna.</p>
            </div>
        </div>
    </div>

    <!-- KOMENTAR & LAPORAN SAJA -->
    <div class="comments-section">
        <div class="section-header">
            <h3 class="m-0 h5 fw-bold text-secondary">
                <i class="fas fa-inbox me-2 text-primary"></i> Kotak Masuk Laporan
            </h3>
            <div class="text-muted small">
                Total: {{ $comments->total() }} komentar
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Ruangan</th>
                        <th>Pengirim</th>
                        <th width="40%">Isi Pesan</th>
                        <th>Tipe/Status</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                    <tr>
                        <td>
                            <strong>{{ $comment->room->name ?? 'Ruangan Dihapus' }}</strong><br>
                            <span class="text-muted small">{{ $comment->room->location ?? '-' }}</span>
                        </td>
                       {{-- Di bagian pengirim --}}
<td>
    @if($comment->is_anonymous)
        <i class="fas fa-user-secret me-1"></i> Anonim
        <span class="badge-anon">Hidden</span>
    @else
        <i class="fas fa-user me-1"></i> 
        {{-- GANTI: $comment->user jadi $comment->kelas --}}
        @if($comment->kelas)
            {{ $comment->kelas->name ?? $comment->kelas->username }}
        @else
            <span class="text-muted">Kelas Dihapus</span>
        @endif
    @endif
</td>
                        <td>
                            <p class="mb-0 text-secondary" style="line-height: 1.4;">{{ $comment->body }}</p>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="type-badge {{ $comment->type == 'report' ? 't-report' : 't-general' }}">
                                    {{ $comment->type == 'report' ? 'Laporan' : 'Umum' }}
                                </span>
                                @if($comment->status == 'open')
                                    <span class="st-open">Menunggu</span>
                                @else
                                    <span class="st-resolved">Selesai</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <small class="fw-bold">{{ $comment->created_at->format('d M Y') }}</small><br>
                            <small class="text-muted">{{ $comment->created_at->format('H:i') }} WITA</small>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($comment->type == 'report' && $comment->status == 'open')
                                <form action="{{ route('admin.comments.resolve', $comment->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success" title="Tandai Selesai">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" 
                                      onsubmit="return confirm('Hapus komentar ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                            Belum ada komentar masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- PAGINATION -->
        <div class="p-3">
            {{ $comments->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection