<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class BookingExport implements FromCollection, WithHeadings, WithMapping
{
    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        $query = Booking::query();
        $now = Carbon::now();

        if ($this->periode == 'hari_ini') {
            $query->whereDate('created_at', $now->today());
        } elseif ($this->periode == 'minggu_ini') {
            $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
        }
        
        // Urutkan dari yang terbaru
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Tanggal', 'User', 'Ruangan', 'Kegiatan', 'Dosen', 'Mulai', 'Selesai', 'Status'
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->created_at->format('d-m-Y H:i'),
            $booking->username,
            $booking->room_name,
            $booking->mata_kuliah,
            $booking->dosen,
            $booking->waktu_mulai,
            $booking->waktu_berakhir,
            $booking->status,
        ];
    }
}