@extends('layouts.app')

@section('title', 'Scan QR Code - Dasher')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-qrcode me-2"></i>Scan QR Code
                    </h4>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted">Arahkan kamera ke QR code di pintu ruangan</p>
                    
                    <div id="reader" class="border rounded" style="width: 100%; height: 400px; background: #f8f9fa;"></div>
                    
                    <div class="mt-3">
                        <button id="btn-start" class="btn btn-success">
                            <i class="fas fa-play me-2"></i>Start Camera
                        </button>
                        <button id="btn-stop" class="btn btn-danger" style="display: none;">
                            <i class="fas fa-stop me-2"></i>Stop Camera
                        </button>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('dashboard.kelas') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrcodeScanner = null;

document.getElementById('btn-start').addEventListener('click', function() {
    startScanner();
});

document.getElementById('btn-stop').addEventListener('click', function() {
    stopScanner();
});

function startScanner() {
    if (typeof Html5QrcodeScanner === 'undefined') {
        alert('ERROR: Library tidak terload!');
        return;
    }

    try {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 }
            },
            false
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        
        document.getElementById('btn-start').style.display = 'none';
        document.getElementById('btn-stop').style.display = 'inline-block';
        
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

function stopScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear().catch(error => {
            console.log("Scanner stopped");
        });
        html5QrcodeScanner = null;
    }
    document.getElementById('btn-start').style.display = 'inline-block';
    document.getElementById('btn-stop').style.display = 'none';
}

function onScanSuccess(decodedText, decodedResult) {
    console.log('QR Code scanned:', decodedText);
    
    // STOP SCANNER DAN REDIRECT LANGSUNG
    stopScanner();
    
    // Set session sebelum redirect (gunakan fetch API)
    fetch('/set-qr-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ from_qr: true })
    })
    .then(response => response.json())
    .then(data => {
        // Redirect setelah session diset
        window.location.href = decodedText;
    })
    .catch(error => {
        console.error('Error setting session:', error);
        // Fallback: redirect langsung
        window.location.href = decodedText;
    });
}

function onScanFailure(error) {
    // Ignore errors
}
</script>

<style>
#reader video {
    border-radius: 10px;
    width: 100% !important;
}
</style>
@endsection