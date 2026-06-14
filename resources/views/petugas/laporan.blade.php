@extends('layouts.petugas-app')

@section('content')
<div class="laporan-wrapper">
    <div class="laporan-shell">
        <div class="laporan-header-card">
            <div class="laporan-header-title" id="header_nama_ka">-</div>
            <div class="laporan-header-lastscan">
                Terakhir scan : <span id="header_terakhir_scan">-</span>
            </div>

            <div class="laporan-route-grid">
                <div class="route-side left">
                    <div class="route-city" id="header_asal">-</div>
                    <div class="route-time" id="header_jam_berangkat">-</div>
                    <div class="route-date" id="header_tanggal_kiri">-</div>
                </div>

                <div class="route-side right">
                    <div class="route-city" id="header_tujuan">-</div>
                    <div class="route-time" id="header_jam_tiba">-</div>
                    <div class="route-date" id="header_tanggal_kanan">-</div>
                </div>
            </div>
        </div>

        <form id="laporanForm">
            <input type="hidden" id="nama_ka">
            <input type="hidden" id="tanggal_ka">
            <input type="hidden" id="stanformasi">
            <input type="hidden" id="nosarana">
            <input type="hidden" id="waktu">
            <input type="hidden" id="terakhir_scan">
            <input type="hidden" id="history_id">

            <div class="form-group">
                <label class="form-label">Kondisi Gerbong</label>
                <div class="select-wrap">
                    <select id="status" class="form-control-app" required>
                        <option value="">Pilih kondisi gerbong</option>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                        <option value="darurat">Darurat</option>
                    </select>
                    <span class="select-arrow" aria-hidden="true">
                        <svg viewBox="0 0 20 20" fill="none">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Kerusakan</label>
                <textarea id="catatan" class="form-control-app textarea-app" placeholder="Tuliskan deskripsi kondisi asli kerusakan"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Ambil Foto</label>

                <label for="foto" class="camera-upload-box" id="laporan-upload-box">
                    <div class="camera-preview-top" id="laporan-preview-top" style="display:none;">Tambahkan foto lain</div>
                    <div class="camera-upload-placeholder" id="laporan-upload-placeholder">
                        <div class="camera-icon">
                            <iconify-icon icon="typcn:camera"></iconify-icon>
                        </div>
                        <div class="camera-text">Klik untuk Ambil Gambar</div>
                    </div>
                    <div class="camera-preview-name" id="camera-preview-name"></div>
                </label>
                <div class="photo-note" id="laporan-photo-note" style="margin-top:6px; margin-bottom:8px; font-size:11px; color:#6e6377">*tidak wajib mengambil foto jika tidak terjadi kerusakan</div>

                <input
                    type="file"
                    id="foto"
                    class="hidden-file-input"
                    accept="image/*"
                    capture="environment"
                    multiple
                >

                <div id="laporanPhotoGallery" class="laporan-photo-gallery"></div>
                <canvas id="photoCanvas" style="display:none;"></canvas>
            </div>

            <button type="submit" class="btn-submit-app" id="laporanSubmitBtn">
                <span class="btn-text">Kirim</span>
            </button>
        </form>
    </div>
</div>

<div class="success-overlay" id="laporan-success-overlay">
    <div class="success-modal">
        <div class="success-icon">
            <iconify-icon icon="ic:round-done-all"></iconify-icon>
        </div>
        <div class="success-title">Laporan Terkirim!</div>
        <div class="success-text">
            Laporan kondisi gerbong<br>berhasil dikirim
        </div>
        <div class="success-action">
            <button type="button" id="laporan-success-ok-btn" class="success-ok-btn">OK</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .laporan-wrapper {
        max-width: 380px;
        margin: 0 auto;
    }

    .laporan-shell {
        background: #f5f5f5;
        border-radius: 18px;
        padding: 12px 12px 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,.20);
    }

    .laporan-header-card {
        background: linear-gradient(180deg, #4A3A8F 0%, #782D6E 100%);
        border-radius: 10px;
        padding: 12px 12px 14px;
        color: #fff;
        margin-bottom: 16px;
        text-align: center;
    }

    .laporan-header-title {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .laporan-header-lastscan {
        font-size: 10px;
        font-weight: 500;
        margin-bottom: 10px;
        opacity: 0.95;
    }

    .laporan-route-grid {
        display: flex;
        justify-content: space-between;
        gap: 16px;
    }

    .route-side {
        width: 50%;
        font-size: 11px;
        line-height: 1.4;
    }

    .route-side.left {
        text-align: left;
    }

    .route-side.right {
        text-align: right;
    }

    .route-city {
        font-weight: 500;
    }

    .route-time,
    .route-date {
        font-weight: 400;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #5e5658;
    }

    .select-wrap {
        position: relative;
    }

    .form-control-app {
        width: 100%;
        border: 1.8px solid #8f8788;
        padding: 12px 14px;
        font-size: 12px;
        border-radius: 12px;
        background: #fff;
        outline: none;
        transition: .2s;
        font-family: 'Poppins', sans-serif;
        color: #4b4345;
    }

    .form-control-app:focus {
        border-color: #4A3A8F;
    }

    .select-wrap select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 44px;
    }

    .select-arrow {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: #7b7274;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .select-arrow svg {
        width: 18px;
        height: 18px;
        display: block;
    }

    .textarea-app {
        min-height: 98px;
        resize: none;
    }

    .camera-upload-box {
        width: 100%;
        min-height: 70px;
        border: 1.8px solid #8f8788;
        border-radius: 12px;
        background: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-align: center;
        padding: 12px;
        overflow: hidden;
        position: relative;
    }

    .camera-upload-box.has-photo {
        border-style: solid;
        border-color: #d9cdf8;
        background: #faf7ff;
    }

    .camera-preview-top {
        width: 100%;
        margin-bottom: 8px;
        padding: 9px 12px;
        border-radius: 10px;
        background: #f1ebff;
        color: #5f38b2;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        line-height: 1.3;
    }

    .camera-photo-wrap {
        width: 100%;
        position: relative;
        display: block;
    }

    .camera-upload-placeholder {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #5f38b2;
        font-weight: 600;
    }

    .camera-icon {
        font-size: 26px;
        line-height: 1;
        display: flex;
        align-items: center;
    }

    .camera-text {
        font-size: 12px;
        color: #5f38b2;
        line-height: 1.2;
        font-weight: 600;
        text-align: center;
    }

    .camera-subnote {
        font-size: 11px;
        color: #7a7475;
        margin-top: 6px;
        font-weight: 300;
        text-align: center;
    }

    .camera-preview-name {
        display: none;
        margin-top: 8px;
        font-size: 11px;
        color: #4A3A8F;
        font-weight: 600;
        word-break: break-word;
    }

    .camera-preview-image {
        width: 100%;
        max-height: 160px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 0;
        display: block;
    }

    .hidden-file-input {
        display: none;
    }

    .laporan-photo-gallery {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
    }

    .laporan-photo-item {
        position: relative;
        width: 100%;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        border-radius: 10px;
        background: #f3f4f7;
    }

    .laporan-photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .laporan-remove-photo {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 3;
        border: none;
        background: rgba(0, 0, 0, 0.56);
        color: #fff;
        font-size: 11px;
        font-weight: 500;
        padding: 6px 8px;
        border-radius: 6px;
        cursor: pointer;
        line-height: 1;
    }

    .btn-submit-app {
        width: 112px;
        height: 34px;
        border: none;
        border-radius: 8px;
        background: #332f86;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        display: block;
        margin-left: auto;
        margin-top: 8px;
        position: relative;
    }

    .btn-submit-app:hover {
        filter: brightness(1.05);
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
        .laporan-wrapper {
            max-width: 320px;
        }

        .laporan-shell {
            padding: 10px;
        }

        .laporan-header-title {
            font-size: 13px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js"></script>
<script>
    // Ensure global functions exist with fallbacks
    if (typeof clearGlobalMessages === 'undefined') window.clearGlobalMessages = () => {};
    if (typeof showGlobalError === 'undefined') window.showGlobalError = (msg) => console.error(msg);
    if (typeof showGlobalSuccess === 'undefined') window.showGlobalSuccess = (msg) => console.log(msg);
    if (typeof setButtonLoading === 'undefined') window.setButtonLoading = () => {};
    if (typeof updateHistoryItem === 'undefined') window.updateHistoryItem = () => {};
    if (typeof formatDateDisplay === 'undefined') window.formatDateDisplay = (v) => v;
    if (typeof formatTimeHHMM === 'undefined') window.formatTimeHHMM = (v) => v;
    
    ensurePetugasLogin();

    const API_BASE_URL = '';
    const fotoInput = document.getElementById('foto');
    const previewName = document.getElementById('camera-preview-name');
    const photoCanvas = document.getElementById('photoCanvas');
    const laporanPhotoGallery = document.getElementById('laporanPhotoGallery');
    const laporanUploadPlaceholder = document.getElementById('laporan-upload-placeholder');
    const laporanSubmitBtn = document.getElementById('laporanSubmitBtn');

    let stampedPhotos = [];
    let isLaporanSubmitting = false;

    function formatTanggalIndonesia(value) {
        if (!value || value === '-') return '-';
        return formatDateDisplay(value);
    }

    function formatWaktuLaporanCard(value) {
        return formatTimeHHMM(value);
    }

    function loadScanResult() {
        const raw = localStorage.getItem('scan_result');

        if (!raw) {
            window.location.href = '/scanqr';
            return null;
        }

        const data = JSON.parse(raw);

        document.getElementById('nama_ka').value = data.nama_ka ?? '';
        document.getElementById('tanggal_ka').value = data.tanggal_ka ?? '';
        document.getElementById('stanformasi').value = data.stanformasi ?? '';
        document.getElementById('nosarana').value = data.nosarana ?? '';
        document.getElementById('waktu').value = data.waktu ?? '';
        document.getElementById('terakhir_scan').value = data.terakhir_scan ?? '-';
        document.getElementById('history_id').value = data.history_id ?? '';

        document.getElementById('header_nama_ka').innerText =
            `${data.nama_ka ?? '-'} - ${data.stanformasi ?? '-'}`;

        document.getElementById('header_terakhir_scan').innerText =
            data.terakhir_scan && data.terakhir_scan !== '-' ? formatTimeHHMM(data.terakhir_scan) : '-';

        document.getElementById('header_asal').innerText = data.asal ?? '-';
        document.getElementById('header_jam_berangkat').innerText = formatWaktuLaporanCard(data.jam_berangkat ?? '-');
        document.getElementById('header_tanggal_kiri').innerText =
            data.tanggal_ka ? formatTanggalIndonesia(data.tanggal_ka) : '-';

        document.getElementById('header_tujuan').innerText = data.tujuan ?? '-';
        document.getElementById('header_jam_tiba').innerText = formatWaktuLaporanCard(data.jam_tiba ?? '-');
        document.getElementById('header_tanggal_kanan').innerText =
            data.tanggal_ka ? formatTanggalIndonesia(data.tanggal_ka) : '-';

        return data;
    }

    function getTimestampText() {
        const now = new Date();
        const dd = String(now.getDate()).padStart(2, '0');
        const mm = String(now.getMonth() + 1).padStart(2, '0');
        const yyyy = now.getFullYear();
        const hh = String(now.getHours()).padStart(2, '0');
        const mi = String(now.getMinutes()).padStart(2, '0');
        const ss = String(now.getSeconds()).padStart(2, '0');

        return `${dd}-${mm}-${yyyy} ${hh}:${mi}:${ss}`;
    }

    function fileToImage(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();

            reader.onload = () => {
                const img = new Image();
                img.onload = () => resolve(img);
                img.onerror = reject;
                img.src = reader.result;
            };

            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    async function stampPhoto(file) {
        const img = await fileToImage(file);
        const ctx = photoCanvas.getContext('2d');

        photoCanvas.width = img.width;
        photoCanvas.height = img.height;

        ctx.drawImage(img, 0, 0);

        const stamp = getTimestampText();
        const barHeight = Math.max(50, Math.floor(img.height * 0.08));
        const fontSize = Math.max(20, Math.floor(img.width * 0.035));
        const padding = Math.max(16, Math.floor(img.width * 0.02));

        ctx.fillStyle = 'rgba(0, 0, 0, 0.60)';
        ctx.fillRect(0, img.height - barHeight, img.width, barHeight);

        ctx.fillStyle = '#ffffff';
        ctx.font = `${fontSize}px Arial`;
        ctx.textBaseline = 'middle';
        ctx.fillText(stamp, padding, img.height - (barHeight / 2));

        return new Promise((resolve) => {
            photoCanvas.toBlob((blob) => resolve(blob), 'image/jpeg', 0.92);
        });
    }

    function renderLaporanPhotos() {
        laporanPhotoGallery.innerHTML = '';
        stampedPhotos.forEach((item, index) => {
            const wrap = document.createElement('div');
            wrap.className = 'laporan-photo-item';

            const img = document.createElement('img');
            img.src = item.previewUrl;
            img.alt = `Foto laporan ${index + 1}`;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'laporan-remove-photo';
            removeBtn.textContent = 'Hapus';
            removeBtn.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                URL.revokeObjectURL(item.previewUrl);
                stampedPhotos.splice(index, 1);
                renderLaporanPhotos();
            });

            wrap.appendChild(img);
            wrap.appendChild(removeBtn);
            laporanPhotoGallery.appendChild(wrap);
        });

        laporanUploadPlaceholder.style.display = stampedPhotos.length ? 'none' : 'flex';
        previewName.innerText = stampedPhotos.length ? `${stampedPhotos.length} foto berhasil ditambahkan` : '';

        const uploadBox = document.getElementById('laporan-upload-box');
        const previewTop = document.getElementById('laporan-preview-top');
        const photoNote = document.getElementById('laporan-photo-note');
        const gallery = document.getElementById('laporanPhotoGallery');

        if (uploadBox) uploadBox.classList.toggle('has-photo', stampedPhotos.length > 0);
        if (previewTop) previewTop.style.display = stampedPhotos.length ? 'block' : 'none';

        // move the small note so it appears below photos when photos exist
        if (photoNote && gallery && uploadBox) {
            if (stampedPhotos.length) {
                gallery.insertAdjacentElement('afterend', photoNote);
            } else {
                uploadBox.insertAdjacentElement('afterend', photoNote);
            }
        }
    }

    function showLaporanSuccessModal() {
        document.getElementById('laporan-success-overlay').classList.add('show');
    }

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

    function hideLaporanSuccessModal() {
        document.getElementById('laporan-success-overlay').classList.remove('show');
    }

    async function getCurrentLocation() {
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
                    console.warn('Lokasi tidak bisa diambil saat mengirim laporan:', error);
                    resolve(emptyLocation);
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        });
    }

    fotoInput.addEventListener('change', async function () {
        const files = Array.from(this.files || []);

        if (!files.length) {
            return;
        }

        for (const file of files) {
            const blob = await stampPhoto(file);
            const previewUrl = URL.createObjectURL(blob);
            stampedPhotos.push({ blob, previewUrl });
        }

        this.value = '';
        renderLaporanPhotos();
    });

    document.getElementById('laporanForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        if (isLaporanSubmitting) {
            return;
        }
        isLaporanSubmitting = true;

        clearGlobalMessages();

        const petugas = JSON.parse(localStorage.getItem('petugas'));
        const scanResult = JSON.parse(localStorage.getItem('scan_result'));

        if (!petugas || !scanResult) {
            showGlobalError('Data petugas atau hasil scan tidak ditemukan.');
            isLaporanSubmitting = false;
            return;
        }

        const status = document.getElementById('status').value;
        const catatan = document.getElementById('catatan').value.trim();

        if (!status) {
            showGlobalError('Pilih kondisi gerbong terlebih dahulu.');
            isLaporanSubmitting = false;
            return;
        }

        if ((status === 'rusak' || status === 'darurat') && !catatan) {
            showGlobalError('Deskripsi wajib untuk status rusak atau darurat');
            isLaporanSubmitting = false;
            return;
        }

        if ((status === 'rusak' || status === 'darurat') && !stampedPhotos.length) {
            showGlobalError('Foto wajib untuk status rusak atau darurat');
            isLaporanSubmitting = false;
            return;
        }

        const currentLocation = await getCurrentLocation();
        const gpsLat = currentLocation.gps_lat || scanResult.gps_lat || '';
        const gpsLng = currentLocation.gps_lng || scanResult.gps_lng || '';
        const namaLokasi = currentLocation.nama_lokasi || scanResult.nama_lokasi || '';

        const formData = new FormData();
        formData.append('nipp', petugas.nipp);
        formData.append('nama', petugas.nama);
        formData.append('dinasan', petugas.dinasan);
        const gerbongKa = scanResult.gerbong_ka ?? scanResult.stanformasi ?? '';
        const jenisKendala = status === 'baik' ? 'Tidak ada kendala' : 'Kerusakan sarana';

        formData.append('nama_ka', scanResult.nama_ka ?? '');
        formData.append('tanggal_ka', scanResult.tanggal_ka ?? '');
        formData.append('stanformasi', scanResult.stanformasi ?? '');
        formData.append('gerbong_ka', gerbongKa);
        formData.append('nosarana', scanResult.nosarana ?? '');
        formData.append('waktu', scanResult.waktu ?? '');
        formData.append('status', status);
        formData.append('jenis_kendala', jenisKendala);
        formData.append('tingkat_kendala', status);
        formData.append('catatan', catatan);
        formData.append('gps_lat', gpsLat);
        formData.append('gps_lng', gpsLng);
        formData.append('nama_lokasi', namaLokasi);

        stampedPhotos.forEach((item, index) => {
            formData.append('foto[]', item.blob, `laporan_${Date.now()}_${index + 1}.jpg`);
        });

        setButtonLoading(laporanSubmitBtn, true, 'Mengirim...');
        try {
            const response = await fetch(`${API_BASE_URL}/api/laporan`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                showGlobalError(result.message ?? 'Gagal mengirim laporan');
                isLaporanSubmitting = false;
                setButtonLoading(laporanSubmitBtn, false);
                return;
            }

            if (scanResult.history_id) {
                updateHistoryItem(scanResult.history_id, { status_laporan: 'Laporan Terkirim' });
            }

            localStorage.removeItem('scan_result');
            showLaporanSuccessModal();
        } catch (error) {
            showGlobalError('Tidak bisa terhubung ke server backend');
            console.error(error);
            isLaporanSubmitting = false;
            setButtonLoading(laporanSubmitBtn, false);
        }
    });

    document.getElementById('laporan-success-ok-btn').addEventListener('click', function () {
        hideLaporanSuccessModal();
        window.location.href = '/scanqr';
    });

    document.addEventListener('DOMContentLoaded', loadScanResult);
</script>
@endpush
