<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Import kelas untuk export Excel
use App\Exports\BookingExport;
// Import facade Excel dari library Maatwebsite
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman Laporan & Analytics
     */
    public function index(Request $request)
    {
        // 1. TANGKAP FILTER DARI URL (Default: 'total')
        // Contoh URL: /admin/reports?periode=hari_ini
        $periode = $request->get('periode', 'total');
        
        // 2. SIAPKAN QUERY DASAR
        $query = Booking::query();

        // 3. TERAPKAN FILTER WAKTU
        $labelPeriode = 'Keseluruhan Data';
        $now = Carbon::now();

        if ($periode == 'hari_ini') {
            // Filter data hanya hari ini
            $query->whereDate('created_at', $now->today());
            $labelPeriode = 'Hari Ini (' . $now->format('d M Y') . ')';
        } 
        elseif ($periode == 'minggu_ini') {
            // Filter data minggu ini (Senin - Minggu)
            $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
            $labelPeriode = 'Minggu Ini';
        }
        // Jika 'total', ambil semua data tanpa filter waktu

        // 4. HITUNG STATISTIK UTAMA (Sesuai Filter)
        // Gunakan clone $query agar filter tanggal tetap terbawa
        $totalBookings = (clone $query)->count();
        $successBookings = (clone $query)->where('status', '!=', 'cancelled')->count();
        $cancelledBookings = (clone $query)->where('status', 'cancelled')->count();
        
        // Hitung persentase keberhasilan
        $successRate = $totalBookings > 0 ? round(($successBookings / $totalBookings) * 100) : 0;

        // 5. DATA GRAFIK 1: Ruangan Terpopuler (Top 5)
        $roomStats = (clone $query)
            ->select('room_name', DB::raw('count(*) as total'))
            ->groupBy('room_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $roomLabels = $roomStats->pluck('room_name');
        $roomData = $roomStats->pluck('total');

        // 6. DATA GRAFIK 2: TREN (Timeline 7 Hari Terakhir)
        // Grafik tren selalu menampilkan 7 hari terakhir untuk konteks,
        // kecuali jika user memilih filter tertentu, bisa disesuaikan.
        // Di sini kita tetap tampilkan 7 hari terakhir secara global.
        $dates = collect();
        $trendData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            // Hitung booking pada tanggal tersebut
            $count = Booking::whereDate('created_at', $date->format('Y-m-d'))->count();
            
            $dates->push($date->format('d M'));
            $trendData->push($count);
        }

        // 7. TOP USER (Sesuai Filter)
        $topUser = (clone $query)
            ->select('username', DB::raw('count(*) as total'))
            ->groupBy('username')
            ->orderByDesc('total')
            ->first();

        // 8. RIWAYAT TERBARU (Sesuai Filter)
        $recentBookings = (clone $query)->orderBy('created_at', 'desc')->limit(10)->get();

        // Kirim semua data ke View
        return view('admin.reports.index', compact(
            'periode',
            'labelPeriode',
            'totalBookings',
            'cancelledBookings',
            'successRate',
            'roomLabels',
            'roomData',
            'dates',
            'trendData',
            'recentBookings',
            'topUser'
        ));
    }

    /**
     * Fungsi untuk Export data ke Excel
     */
    public function exportExcel(Request $request)
    {
        // Ambil periode dari request (sama seperti di index)
        $periode = $request->get('periode', 'total');
        
        // Buat nama file yang rapi
        $fileName = 'laporan-booking-' . $periode . '-' . date('Y-m-d') . '.xlsx';
        
        // Download file Excel menggunakan class BookingExport
        return Excel::download(new BookingExport($periode), $fileName);
    }
}