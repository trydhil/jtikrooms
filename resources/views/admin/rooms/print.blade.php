<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label QR - {{ $room->name }}</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding: 20px;
        }
        .card {
            border: 3px solid #000;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 36px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .subtitle {
            font-size: 18px;
            color: #333;
            margin-top: 5px;
        }
        .qr-box {
            margin: 20px auto;
        }
        .qr-img {
            width: 350px; /* Ukuran QR Besar */
            height: 350px;
            object-fit: contain;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .scan-text {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="title">{{ $room->display_name }}</div>
            <div class="subtitle">{{ $room->location ?? 'Gedung JTIK' }}</div>
        </div>

        <div class="qr-box">
            @if($room->qr_code && file_exists(public_path($room->qr_code)))
                <img src="{{ public_path($room->qr_code) }}" class="qr-img">
            @else
                <p>QR Code Tidak Ditemukan</p>
            @endif
        </div>

        <div class="footer">
            <span class="scan-text">SCAN UNTUK BOOKING</span>
            Sistem Manajemen Ruangan - Dasher
        </div>
    </div>
</body>
</html>