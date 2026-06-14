@extends('layouts.petugas-app')

@section('content')
<div class="scan-wrapper">
    <div class="scan-top-action">
        <button type="button" class="history-btn" onclick="window.location.href='/history-scan'">
            <iconify-icon icon="mdi:history"></iconify-icon>
            <span>History</span>
        </button>
    </div>

    <div class="scan-title">Scan Barcode Gerbong</div>

    <div class="scan-camera-box">
        <div id="scan-loading" class="scan-loading show">
            <div class="scan-spinner"></div>
            <div class="scan-loading-text">Menyiapkan kamera...</div>
        </div>
        <div id="reader"></div>
    </div>

    <div class="scan-note">
        Arahkan barcode ke kamera untuk memulai pemeriksaan berkala.
    </div>

    <button type="button" class="kendala-btn" onclick="window.location.href='/lapor-kendala'">
        <iconify-icon icon="mdi:alert-outline"></iconify-icon>
        <span>Lapor Kendala</span>
    </button>

    <div class="kendala-note">
        Jika QR tidak ada atau gagal dipindai,<br>buat laporan manual.
    </div>
</div>

<div class="success-overlay" id="success-overlay">
    <div class="success-modal">
        <div class="success-icon">
            <iconify-icon icon="ic:round-done-all"></iconify-icon>
        </div>
        <div class="success-title">Scan Berhasil!</div>
        <div class="success-text">
            Silahkan masukkan laporan<br>kondisi gerbong
        </div>
        <div class="success-action">
            <button type="button" id="success-ok-btn" class="success-ok-btn">OK</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .scan-wrapper {
        max-width: 360px;
        margin: 0 auto;
        text-align: center;
    }

    .scan-top-action {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 10px;
    }

    .history-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1.5px solid #8e77d5;
        background: #fff;
        color: #4b2f91;
        border-radius: 10px;
        height: 40px;
        padding: 0 14px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(74, 58, 143, 0.08);
    }

    .history-btn iconify-icon {
        font-size: 18px;
    }

    .scan-title {
        font-size: 20px;
        font-weight: 600;
        color: #2b2b2b;
        margin: 8px 0 22px;
    }

    .scan-camera-box {
        position: relative;
        width: 100%;
        height: 300px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 8px rgba(0,0,0,.18);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #reader {
        width: 100%;
        height: 100%;
    }

    #reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
    }

    .scan-loading {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,.96);
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 4;
    }

    .scan-loading.show {
        display: flex;
    }

    .scan-spinner {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 3px solid rgba(74, 58, 143, 0.16);
        border-top-color: #5b45bf;
        animation: scanSpin .8s linear infinite;
        margin-bottom: 12px;
    }

    .scan-loading-text {
        font-size: 13px;
        color: #645a6b;
    }

    @keyframes scanSpin {
        to {
            transform: rotate(360deg);
        }
    }

    .scan-note {
        margin-top: 14px;
        font-size: 13px;
        color: #666;
        line-height: 1.5;
    }

    .kendala-btn {
        margin-top: 18px;
        width: 100%;
        height: 48px;
        border-radius: 12px;
        border: 1.8px solid #8b73d9;
        background: #fff;
        color: #4c32a1;
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
    }

    .kendala-btn iconify-icon {
        font-size: 22px;
    }

    .kendala-note {
        margin-top: 10px;
        font-size: 12px;
        color: #6d6669;
        line-height: 1.55;
    }

    .success-overlay {
        position: fixed;
        inset: 0;
        background: rgba(64, 46, 56, 0.42);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
    }

    .success-overlay.show {
        display: flex;
    }

    .success-modal {
        width: 100%;
        max-width: 360px;
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(0, 0, 0, 0.22);
        padding: 28px 24px 20px;
        text-align: center;
    }

    .success-icon {
        font-size: 82px;
        line-height: 1;
        color: #22c53d;
        margin-bottom: 10px;
    }

    .success-title {
        font-size: 20px;
        font-weight: 700;
        color: #2b1f28;
        margin-bottom: 8px;
    }

    .success-text {
        font-size: 15px;
        color: #4b3a43;
        line-height: 1.45;
    }

    .success-action {
        display: flex;
        justify-content: flex-end;
        margin-top: 22px;
    }

    .success-ok-btn {
        min-width: 78px;
        height: 40px;
        border: none;
        border-radius: 10px;
        background: #0f9d2f;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
    }

    .success-ok-btn:hover {
        filter: brightness(0.95);
    }

    @media (max-width: 480px) {
        .scan-wrapper {
            max-width: 340px;
        }

        .scan-camera-box {
            height: 280px;
        }

        .scan-title {
            font-size: 17px;
        }

        .success-modal {
            max-width: 290px;
            padding: 26px 18px 18px;
        }

        .success-icon {
            font-size: 72px;
        }

        .success-title {
            font-size: 18px;
        }

        .success-text {
            font-size: 14px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // Ensure global functions exist with fallbacks
    if (typeof clearGlobalMessages === 'undefined') window.clearGlobalMessages = () => {};
    if (typeof showGlobalError === 'undefined') window.showGlobalError = (msg) => console.error(msg);
    if (typeof showGlobalSuccess === 'undefined') window.showGlobalSuccess = (msg) => console.log(msg);
    if (typeof addHistoryItem === 'undefined') window.addHistoryItem = () => {};
    if (typeof getPreviousTodayScanTime === 'undefined') window.getPreviousTodayScanTime = () => '-';
    if (typeof saveTodayScanTime === 'undefined') window.saveTodayScanTime = () => {};
    
    ensurePetugasLogin();

    let html5QrCode = null;
    let isProcessing = false;

    async function getNamaLokasi(lat, lng) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`,
                { headers: { 'Accept': 'application/json' } }
            );

            const data = await response.json();

            return data.display_name || `${lat}, ${lng}`;
        } catch (error) {
            console.warn('Gagal mengambil nama lokasi:', error);
            return `${lat}, ${lng}`;
        }
    }

    async function getLocation() {
        return new Promise((resolve) => {
            const emptyLocation = { gps_lat: '', gps_lng: '', nama_lokasi: '' };

            if (!navigator.geolocation) {
                resolve(emptyLocation);
                return;
            }

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const namaLokasi = await getNamaLokasi(lat, lng);

                    resolve({
                        gps_lat: lat,
                        gps_lng: lng,
                        nama_lokasi: namaLokasi
                    });
                },
                (error) => {
                    console.warn('Lokasi tidak bisa diambil saat scan:', error);
                    resolve(emptyLocation);
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        });
    }

    function setScanLoading(isLoading, message = 'Menyiapkan kamera...') {
        const box = document.getElementById('scan-loading');
        if (!box) return;
        box.querySelector('.scan-loading-text').innerText = message;
        box.classList.toggle('show', isLoading);
    }

    function showSuccessModal() {
        document.getElementById('success-overlay').classList.add('show');
    }

    function hideSuccessModal() {
        document.getElementById('success-overlay').classList.remove('show');
    }

    async function stopScanner() {
        if (html5QrCode) {
            try {
                await html5QrCode.stop();
            } catch (error) {
                console.warn('Gagal menghentikan scanner', error);
            }
        }
    }

    async function processScan(barcodeValue) {
        clearGlobalMessages();
        const petugas = JSON.parse(localStorage.getItem('petugas'));

        if (!petugas) {
            showGlobalError('Data petugas tidak ditemukan.');
            window.location.href = '/scan';
            return;
        }

        const location = await getLocation();

        const response = await fetch('/api/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nipp: petugas.nipp,
                barcode_value: barcodeValue,
                gps_lat: location.gps_lat,
                gps_lng: location.gps_lng,
                nama_lokasi: location.nama_lokasi
            })
        });

        const result = await response.json().catch(() => ({}));

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Scan gagal. Periksa koneksi backend API.');
        }

        const scannedNoSarana = result.data.nosarana
            ?? result.data.no_sarana
            ?? result.data.noSarana
            ?? barcodeValue;
        const scannedIdSarana = result.data.id_sarana
            ?? result.data.idSarana
            ?? result.data.sarana_id
            ?? null;
        const scanIdentifiers = [
            barcodeValue,
            scannedNoSarana,
            scannedIdSarana,
            result.data.barcode_value,
            result.data.barcode,
            result.data.kode_barcode
        ];
        const previousScanDisplay = getPreviousTodayScanTime(scanIdentifiers);
        const historyId = `scan_${Date.now()}_${Math.random().toString(36).slice(2, 8)}`;
        const historyEntry = {
            id: historyId,
            type: 'scan',
            nipp: petugas.nipp,
            nama_petugas: petugas.nama ?? '',
            nama_ka: result.data.nama_ka ?? '-',
            gerbong: result.data.stanformasi ?? result.data.gerbong_ka ?? '-',
            barcode_value: barcodeValue,
            id_sarana: scannedIdSarana,
            nosarana: scannedNoSarana,
            waktu: result.data.waktu ?? new Date().toISOString(),
            status_laporan: 'Belum Lapor'
        };

        // Ambil terakhir scan dulu, baru simpan scan sekarang sebagai data terakhir untuk barcode/no sarana ini.
        addHistoryItem(historyEntry);
        saveTodayScanTime(scanIdentifiers, historyEntry.waktu);

        localStorage.setItem('scan_result', JSON.stringify({
            ...result.data,
            nosarana: scannedNoSarana,
            id_sarana: scannedIdSarana,
            terakhir_scan: previousScanDisplay,
            history_id: historyId
        }));

        await stopScanner();
        showSuccessModal();
    }

    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        isProcessing = true;

        processScan(decodedText).catch(error => {
            showGlobalError(error.message);
            console.error(error);
            isProcessing = false;
        });
    }

    function onScanFailure() {}

    document.getElementById('success-ok-btn').addEventListener('click', function () {
        hideSuccessModal();
        window.location.href = '/laporan';
    });

    document.addEventListener('DOMContentLoaded', function () {
        setScanLoading(true, 'Menyiapkan kamera...');
        html5QrCode = new Html5Qrcode('reader');
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const backCamera = devices.find(d => {
                    try {
                        return (d.label || '').toLowerCase().includes('back');
                    } catch (e) {
                        return false;
                    }
                }) || devices[0];

                html5QrCode.start(
                    backCamera.id,
                    { fps: 10, qrbox: 220 },
                    onScanSuccess,
                    onScanFailure
                ).then(() => {
                    setScanLoading(false);
                }).catch(err => {
                    setScanLoading(true, 'Scanner belum bisa digunakan');
                    showGlobalError('Error: ' + (err.message || 'Tidak bisa mengakses kamera'));
                    console.error('Scanner error:', err);
                });
            } else {
                setScanLoading(true, 'Kamera tidak ditemukan');
                showGlobalError('Kamera tidak ditemukan');
            }
        }).catch(err => {
            setScanLoading(true, 'Izin kamera dibutuhkan');
            showGlobalError('Tidak bisa mengakses kamera');
            console.error(err);
        });
    });
</script>
@endpush
