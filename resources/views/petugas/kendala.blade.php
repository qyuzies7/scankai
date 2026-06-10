@extends('layouts.petugas-app')

@section('content')
<div class="kendala-page">
    <div class="kendala-header-row">
        <button type="button" class="kendala-back-btn" onclick="window.location.href='/scanqr'">
            <iconify-icon icon="mdi:chevron-left"></iconify-icon>
        </button>
        <div class="kendala-page-title">Lapor Kendala</div>
        <div class="kendala-header-spacer"></div>
    </div>

    <div class="kendala-info-box">
        <iconify-icon icon="mdi:information-outline"></iconify-icon>
        <span>Gunakan halaman ini jika QR tidak tersedia atau scan gagal.</span>
    </div>

    <form id="kendalaForm" class="kendala-form-card">
        <div class="form-group">
            <label class="form-label">Jenis Kendala</label>
            <div class="select-wrap">
                <select id="jenis_kendala" class="form-control-app" required>
                    <option value="">Pilih jenis kendala</option>
                    <option value="QR Tidak Ada">QR Tidak Ada</option>
                    <option value="QR Rusak">QR Rusak</option>
                    <option value="QR Tidak Bisa Dipindai">QR Tidak Bisa Dipindai</option>
                    <option value="Kamera / Aplikasi Bermasalah">Kamera / Aplikasi Bermasalah</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
                <span class="select-arrow" aria-hidden="true">
                    <svg viewBox="0 0 20 20" fill="none">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">No Sarana</label>
            <input type="text" id="kendala_nosarana" class="form-control-app" placeholder="Masukkan nomor sarana">
        </div>

        <div class="form-group">
            <label class="form-label">Nama Kereta</label>
            <input type="text" id="kendala_nama_ka" class="form-control-app" placeholder="Masukkan nama kereta">
        </div>

        <div class="form-group">
            <label class="form-label">Gerbong</label>
            <input type="text" id="kendala_gerbong" class="form-control-app" placeholder="Masukkan gerbong">
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea id="kendala_catatan" class="form-control-app textarea-app" placeholder="Tuliskan kendala yang ditemukan"></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Ambil Foto (Opsional)</label>
            <label for="kendala_foto" class="camera-upload-box" id="kendala-upload-box">
                <div class="camera-preview-top" id="kendala-preview-top" style="display:none;">Tambahkan foto lain</div>
                <div class="camera-upload-placeholder" id="kendala-upload-placeholder">
                    <div class="camera-icon">
                        <iconify-icon icon="typcn:camera"></iconify-icon>
                    </div>
                    <div class="camera-text">Klik untuk Ambil Gambar</div>
                </div>
            </label>
            <input type="file" id="kendala_foto" class="hidden-file-input" accept="image/*" capture="environment" multiple>
            <canvas id="kendalaPhotoCanvas" style="display:none;"></canvas>
            <div id="kendalaPhotoGallery" class="kendala-photo-gallery"></div>
        </div>

        <button type="submit" class="btn-submit-app btn-submit-full" id="kendalaSubmitBtn">
            <span class="btn-text">Kirim Laporan</span>
        </button>
    </form>
</div>

<div class="success-overlay" id="kendala-success-overlay">
    <div class="success-modal">
        <div class="success-icon">
            <iconify-icon icon="ic:round-done-all"></iconify-icon>
        </div>
        <div class="success-title">Laporan Terkirim!</div>
        <div class="success-text">Laporan kendala berhasil dikirim</div>
        <div class="success-action">
            <button type="button" id="kendala-success-ok-btn" class="success-ok-btn">OK</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .kendala-page {
        max-width: 380px;
        margin: 0 auto;
    }

    .kendala-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .kendala-back-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        color: #36254f;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .kendala-back-btn iconify-icon {
        font-size: 24px;
    }

    .kendala-page-title {
        flex: 1;
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        color: #2d2135;
    }

    .kendala-header-spacer {
        width: 32px;
    }

    .kendala-info-box {
        display: flex;
        gap: 10px;
        background: #eee7fb;
        color: #4d3e82;
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 12px;
        font-size: 13px;
        line-height: 1.5;
        align-items: flex-start;
    }

    .kendala-info-box iconify-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    .kendala-form-card {
        background: #fff;
        border-radius: 14px;
        padding: 14px;
        box-shadow: 0 4px 10px rgba(0,0,0,.12);
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
        border: 1.8px solid #d4d2d8;
        padding: 12px 14px;
        font-size: 14px;
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
        padding-right: 46px;
    }

    .select-arrow {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: #6e6377;
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
        min-height: 88px;
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

    .kendala-photo-gallery {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
    }

    .kendala-photo-item {
        position: relative;
        width: 100%;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        border-radius: 10px;
        background: #f3f4f7;
    }

    .kendala-photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .camera-upload-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #5f38b2;
        font-weight: 600;
    }

    .camera-icon {
        font-size: 22px;
        line-height: 1;
        display: flex;
        align-items: center;
    }

    .camera-text {
        font-size: 13px;
        line-height: 1.4;
    }

    .camera-preview-name {
        display: none;
        margin-top: 8px;
        font-size: 11px;
        color: #4A3A8F;
        font-weight: 600;
        word-break: break-word;
    }

    .kendala-remove-photo {
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

    .btn-submit-app {
        width: 100%;
        height: 48px;
        border: none;
        border-radius: 10px;
        background: #332f86;
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        margin-top: 8px;
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
    
    ensurePetugasLogin();

    const kendalaFotoInput = document.getElementById('kendala_foto');
    const kendalaPhotoCanvas = document.getElementById('kendalaPhotoCanvas');
    const kendalaUploadPlaceholder = document.getElementById('kendala-upload-placeholder');
    const kendalaPreviewTop = document.getElementById('kendala-preview-top');
    const kendalaPhotoGallery = document.getElementById('kendalaPhotoGallery');
    const kendalaSubmitBtn = document.getElementById('kendalaSubmitBtn');
    let kendalaFiles = [];

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

    function setKendalaPhotoState(hasPhoto) {
        document.getElementById('kendala-upload-box').classList.toggle('has-photo', hasPhoto);
        kendalaPreviewTop.style.display = hasPhoto ? 'block' : 'none';
        kendalaUploadPlaceholder.style.display = hasPhoto ? 'none' : 'flex';
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
        const ctx = kendalaPhotoCanvas.getContext('2d');

        kendalaPhotoCanvas.width = img.width;
        kendalaPhotoCanvas.height = img.height;
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
            kendalaPhotoCanvas.toBlob((blob) => {
                resolve(blob);
            }, 'image/jpeg', 0.92);
        });
    }

    function renderKendalaPhotoGallery() {
        kendalaPhotoGallery.innerHTML = '';
        kendalaFiles.forEach((item, index) => {
            const wrap = document.createElement('div');
            wrap.className = 'kendala-photo-item';

            const img = document.createElement('img');
            img.src = item.previewUrl;
            img.alt = 'Preview foto';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'kendala-remove-photo';
            removeBtn.textContent = 'Hapus';
            removeBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                removeKendalaPhoto(index);
            });

            wrap.appendChild(img);
            wrap.appendChild(removeBtn);
            kendalaPhotoGallery.appendChild(wrap);
        });

        setKendalaPhotoState(kendalaFiles.length > 0);
    }

    function removeKendalaPhoto(index) {
        if (kendalaFiles[index]) {
            URL.revokeObjectURL(kendalaFiles[index].previewUrl);
        }
        kendalaFiles.splice(index, 1);
        renderKendalaPhotoGallery();
    }

    kendalaFotoInput.addEventListener('change', async function () {
        const files = Array.from(this.files || []);
        if (!files.length) return;

        for (const file of files) {
            const blob = await stampPhoto(file);
            kendalaFiles.push({
                blob,
                previewUrl: URL.createObjectURL(blob),
            });
        }

        this.value = '';
        renderKendalaPhotoGallery();
    });

    function showKendalaSuccessModal() {
        document.getElementById('kendala-success-overlay').classList.add('show');
    }

    function hideKendalaSuccessModal() {
        document.getElementById('kendala-success-overlay').classList.remove('show');
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
                    console.warn('Lokasi tidak bisa diambil saat lapor kendala:', error);
                    resolve(emptyLocation);
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        });
    }

    document.getElementById('kendalaForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        clearGlobalMessages();
        const petugas = JSON.parse(localStorage.getItem('petugas'));
        if (!petugas) {
            showGlobalError('Data petugas tidak ditemukan.');
            return;
        }

        const jenisKendala = document.getElementById('jenis_kendala').value;
        if (!jenisKendala) {
            showGlobalError('Pilih jenis kendala terlebih dahulu.');
            return;
        }

        const currentLocation = await getCurrentLocation();

        const formData = new FormData();
        formData.append('nipp', petugas.nipp);
        formData.append('nama', petugas.nama ?? '');
        formData.append('dinasan', petugas.dinasan ?? '');
        formData.append('jenis_kendala', jenisKendala);
        formData.append('nosarana', document.getElementById('kendala_nosarana').value.trim());
        formData.append('nama_ka', document.getElementById('kendala_nama_ka').value.trim());
        const gerbongManual = document.getElementById('kendala_gerbong').value.trim();
        formData.append('stanformasi', gerbongManual);
        formData.append('gerbong_ka', gerbongManual);
        formData.append('tingkat_kendala', 'rusak');
        formData.append('catatan', document.getElementById('kendala_catatan').value.trim());
        formData.append('gps_lat', currentLocation.gps_lat);
        formData.append('gps_lng', currentLocation.gps_lng);
        formData.append('nama_lokasi', currentLocation.nama_lokasi);

        if (kendalaFiles.length) {
            kendalaFiles.forEach((item, index) => {
                formData.append('foto[]', item.blob, `kendala_${Date.now()}_${index}.jpg`);
            });
        }

        setButtonLoading(kendalaSubmitBtn, true, 'Mengirim...');
        try {
            const response = await fetch('/api/laporan-kendala', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json().catch(() => ({}));

            if (response.status === 404) {
                throw new Error('ENDPOINT_KENDALA_NOT_FOUND');
            }

            if (!response.ok || (Object.keys(result).length && result.success === false)) {
                throw new Error(result.message || 'Gagal mengirim laporan kendala');
            }

            showKendalaSuccessModal();
        } catch (error) {
            if (error.message === 'ENDPOINT_KENDALA_NOT_FOUND') {
                showGlobalError('Frontend sudah dibuat, tetapi endpoint /api/laporan-kendala belum tersedia di backend.');
            } else {
                showGlobalError(error.message || 'Tidak bisa terhubung ke server backend');
            }
            console.error(error);
        } finally {
            setButtonLoading(kendalaSubmitBtn, false);
        }
    });

    document.getElementById('kendala-success-ok-btn').addEventListener('click', function () {
        hideKendalaSuccessModal();
        window.location.href = '/scanqr';
    });
</script>
@endpush
