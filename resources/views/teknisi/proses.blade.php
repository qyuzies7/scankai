@extends('layouts.petugas-app')

@section('content')
<div class="confirm-page">
    <a href="/teknisi?tab=diproses" class="back-link"><iconify-icon icon="mdi:arrow-left"></iconify-icon> Kembali ke Halaman Diproses</a>
    <div id="detailBox" class="card-box">Memuat detail laporan...</div>

    <form id="finishForm" class="finish-form" style="display:none;">
        <div class="card-box">
            <label class="section-title">Keterangan Perbaikan <span>*</span></label>
            <div class="field-help">Jelaskan tindakan perbaikan yang dilakukan.</div>
            <textarea id="keterangan_perbaikan" maxlength="500" placeholder="Contoh: Mengganti konektor CCTV, merapikan kabel, lalu melakukan pengecekan ulang. CCTV sudah kembali normal."></textarea>
            <div class="counter"><span id="ketCount">0</span>/500</div>
        </div>

        <div class="card-box">
            <label class="section-title">Foto Setelah Perbaikan <span>*</span></label>
            <div class="field-help">Unggah foto sebagai bukti bahwa perbaikan telah selesai dilakukan.</div>
            <div class="upload-row">
                <label for="foto_perbaikan" id="takePhotoBtn" class="upload-btn"><iconify-icon icon="mdi:camera"></iconify-icon><span>Ambil Foto</span></label>
            </div>
            <div class="min-text">Minimal 1 foto</div>
            <input id="foto_perbaikan" type="file" accept="image/*" capture="environment" style="display:none;" multiple>
            <div id="photoGallery" class="photo-gallery"></div>
        </div>

        <button type="submit" class="btn-confirm" id="submitBtn"><span class="btn-text">Konfirmasi Selesai</span></button>
    </form>
</div>
@endsection

@push('styles')
<style>
    .petugas-page { padding: 18px 14px 22px; }
    .confirm-page { width:100%; max-width: 420px; margin:0 auto; padding-bottom: 12px; }
    .back-link { display:inline-flex; align-items:center; gap:8px; color:#4014d1; font-size:13px; font-weight:800; text-decoration:none; margin-bottom:16px; }
    .back-link iconify-icon { font-size:21px; }
    .card-box { background:#fff; border:1px solid #eef0f5; border-radius:14px; padding:14px; box-shadow:0 7px 18px rgba(20,20,40,.08); margin-bottom:14px; }
    .summary-top { display:grid; grid-template-columns:60px 1fr auto; gap:12px; align-items:start; padding-bottom:14px; border-bottom:1px solid #eaedf4; }
    .train-icon { width:54px; height:54px; border-radius:50%; background:#4613bb; color:#fff; display:flex; align-items:center; justify-content:center; }
    .train-icon iconify-icon { font-size:32px; }
    .title { font-size:17px; font-weight:800; color:#050914; line-height:1.25; }
    .sub { font-size:13px; color:#263654; font-weight:500; margin-top:6px; }
    .status-wrap { text-align:right; }
    .badge { display:inline-block; padding:6px 10px; border-radius:7px; background:#ffefe7; color:#ff3d13; font-size:11px; font-weight:800; white-space:nowrap; }
    .processed-label { color:#263654; font-size:11px; font-weight:600; margin-top:8px; }
    .processed-time { color:#050914; font-size:12px; font-weight:500; margin-top:2px; }
    .detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px 12px; padding-top:14px; }
    .mini-info { display:grid; grid-template-columns:24px 1fr; gap:8px; align-items:start; }
    .mini-info iconify-icon { color:#31415e; font-size:19px; margin-top:2px; }
    .mini-label { color:#243453; font-size:12px; font-weight:600; margin-bottom:4px; }
    .mini-value { color:#050914; font-size:12px; font-weight:500; line-height:1.45; }
    .section-title { display:block; color:#050914; font-size:15px; font-weight:800; margin-bottom:6px; }
    .section-title span { color:#ff2a2a; }
    .field-help { color:#263654; font-size:12px; font-weight:400; margin-bottom:10px; line-height:1.45; }
    textarea { width:100%; min-height:130px; border:1.5px solid #aab2c5; border-radius:7px; padding:12px; font-family:'Poppins', sans-serif; color:#313a54; font-size:13px; font-weight:400; resize:none; outline:none; }
    textarea::placeholder { color:#66708c; }
    textarea:focus { border-color:#4b17d2; }
    .counter { text-align:right; color:#657088; font-size:12px; font-weight:500; margin-top:6px; }
    .upload-row { display:grid; grid-template-columns:1fr; gap:8px; align-items:stretch; }
    .upload-btn { min-height:70px; border:1.4px dashed #6b46d9; border-radius:7px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px; color:#4014d1; font-size:11px; font-weight:800; cursor:pointer; text-align:center; }
    .upload-btn iconify-icon { font-size:27px; color:#4014d1; }
    .preview-box { min-height:70px; border-radius:7px; background:linear-gradient(135deg,#f3f4f7,#e8ebf1); display:flex; align-items:center; justify-content:center; overflow:hidden; cursor:pointer; }
    .preview-box img { width:100%; height:100%; object-fit:cover; }
    .photo-thumb { position:relative; }
    .photo-thumb img { width:100%; height:100%; object-fit:cover; border-radius:8px; display:block; }
    .photo-thumb .remove-btn { position:absolute; top:8px; right:8px; background:rgba(0,0,0,0.6); color:#fff; border-radius:6px; padding:6px 8px; font-size:12px; cursor:pointer; }
    .photo-item .remove-btn { position:absolute; top:8px; right:8px; background:rgba(0,0,0,0.6); color:#fff; border-radius:6px; padding:6px 8px; font-size:12px; font-weight:500; cursor:pointer; z-index:5; }
    .photo-gallery { display:flex; flex-direction:column; gap:10px; margin-top:10px; }
    .photo-item { width:100%; aspect-ratio:3/4; position:relative; overflow:hidden; border-radius:8px; background:#f3f4f7; }
    .photo-item img { width:100%; height:100%; object-fit:cover; display:block; }
    #photoPlaceholder { color:#6b7285; text-align:center; font-size:12px; font-weight:500; }
    #photoPlaceholder iconify-icon { display:block; margin:0 auto 5px; color:#c4c8d2; font-size:31px; }
    .min-text { margin-top:7px; color:#657088; font-size:11px; font-weight:500; }
    .btn-confirm { width:100%; height:52px; border:0; border-radius:8px; background:linear-gradient(180deg,#ff5a0b 0%,#ff3600 100%); color:#fff; font-family:'Poppins', sans-serif; font-size:16px; font-weight:800; cursor:pointer; }
    .btn-confirm[disabled] { opacity:0.75; cursor:not-allowed; }
    .btn-confirm .spinner { display:inline-block; width:18px; height:18px; border:2px solid rgba(255,255,255,0.6); border-top-color:#fff; border-radius:50%; margin-right:8px; vertical-align:middle; animation:spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js"></script>
<script>
    ensureTeknisiLogin();
    const petugas = safeJsonParse(localStorage.getItem('petugas'), {});
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id') || localStorage.getItem('teknisi_selected_kendala');
    let isFinishSubmitting = false;
    function authParams() { return new URLSearchParams({ id_user: petugas.id_user || '', nipp: petugas.nipp || '' }); }
    function escapeHtml(value) { return String(value ?? '-').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c])); }
    function photoPair(value, label) {
        if (!value) return '<p class="note">Tidak ada foto.</p>';
        let urls = [];
        if (Array.isArray(value)) urls = value;
        else if (typeof value === 'string') {
            try { const p = JSON.parse(value); if (Array.isArray(p)) urls = p; else urls = value.split(',').map(s=>s.trim()).filter(Boolean); } catch(e) { urls = value.split(',').map(s=>s.trim()).filter(Boolean); }
        }
        if (!urls.length) return '<p class="note">Tidak ada foto.</p>';
        const items = urls.map(u => `<a href="${u}" class="photo-link" target="_blank" rel="noopener"><img src="${u}" alt="${label||'Foto'}"></a>`).join('');
        return `<div class="photo-grid">${items}</div>`;
    }

    function ensureImageModal() {
        if (document.getElementById('imageModal')) return;
        const div = document.createElement('div'); div.id = 'imageModal'; div.style.cssText = 'position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.8);z-index:9999;';
        div.innerHTML = `<div style="max-width:94%;max-height:94%;"><img id="imageModalImg" style="max-width:100%;max-height:100%;border-radius:8px;display:block;" alt="Preview"></div>`;
        div.addEventListener('click', () => { div.style.display = 'none'; document.getElementById('imageModalImg').src = ''; });
        document.body.appendChild(div);
        document.addEventListener('click', function (e) {
            const a = e.target.closest && e.target.closest('.photo-link');
            if (a) {
                e.preventDefault();
                ensureImageModal();
                const m = document.getElementById('imageModal');
                document.getElementById('imageModalImg').src = a.href;
                m.style.display = 'flex';
            }
        });
    }

    async function loadDetail() {
        if (!id) { window.location.href = '/teknisi?tab=diproses'; return; }
        try {
            const response = await fetch(`/api/teknisi/laporan-kendala/${id}?${authParams().toString()}`, { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Gagal memuat detail');
            renderDetail(result.data);
            document.getElementById('finishForm').style.display = 'block';
        } catch (error) { showGlobalError(error.message); }
    }

    function renderDetail(item) {
        document.getElementById('detailBox').innerHTML = `<div class="summary-top">
            <div class="train-icon"><iconify-icon icon="mdi:train"></iconify-icon></div>
            <div><div class="title">${escapeHtml(item.nama_ka)}</div><div class="sub">${escapeHtml(item.gerbong_ka)}</div><div class="sub"> ${escapeHtml(item.nosarana)}</div></div>
            <div class="status-wrap"><span class="badge">Diproses</span><div class="processed-label">Waktu Diproses</div><div class="processed-time">${escapeHtml(item.diproses_label)}</div></div>
        </div>
        <div class="detail-grid">
            <div class="mini-info"><iconify-icon icon="mdi:wrench"></iconify-icon><div><div class="mini-label">Jenis Kerusakan</div><div class="mini-value">${escapeHtml(item.jenis_kendala)}</div></div></div>
            <div class="mini-info"><iconify-icon icon="mdi:account"></iconify-icon><div><div class="mini-label">Pelapor</div><div class="mini-value">${escapeHtml(item.pelapor)}</div></div></div>
            <div class="mini-info"><iconify-icon icon="mdi:clock-outline"></iconify-icon><div><div class="mini-label">Waktu Dilaporkan</div><div class="mini-value">${escapeHtml(item.waktu_lapor_label)}</div></div></div>
            <div class="mini-info"><iconify-icon icon="mdi:account-hard-hat"></iconify-icon><div><div class="mini-label">Teknisi</div><div class="mini-value">${escapeHtml(item.nama_teknisi)}</div></div></div>
        </div>`;
        if (!document.getElementById('problemExtra')) {
            const div = document.createElement('div'); div.id = 'problemExtra'; div.innerHTML = `<div class="card-box"><div class="section-title">Keterangan Masalah</div><p class="field-help" style="font-size:13px;margin:0;line-height:1.75;">${escapeHtml(item.catatan)}</p></div><div class="card-box"><div class="section-title">Foto Masalah (Dari Pelapor)</div>${photoPair(item.foto_url)}</div>`;
            document.getElementById('detailBox').after(div);
        }
    }

    document.getElementById('keterangan_perbaikan').addEventListener('input', function () { document.getElementById('ketCount').textContent = this.value.length; });
    // multiple photo upload handling
    const fotoInput = document.getElementById('foto_perbaikan');
    const photoGallery = document.getElementById('photoGallery');
    const takePhotoBtn = document.getElementById('takePhotoBtn');
    let fotoFiles = [];

    function updateTakePhotoLabel() {
        const label = takePhotoBtn.querySelector('span');
        if (label) label.textContent = fotoFiles.length ? 'Tambahkan foto lain' : 'Ambil Foto';
    }

    function renderPhotoPreviews() {
        photoGallery.innerHTML = '';
        if (!fotoFiles.length) {
            // show nothing below; only Ambil Foto is visible
            return;
        }
        fotoFiles.forEach((f, idx) => {
            const wrap = document.createElement('div'); wrap.className = 'photo-item';
            const img = document.createElement('img'); img.src = f.previewUrl; img.alt = 'Foto';
            const del = document.createElement('div'); del.className = 'remove-btn'; del.innerText = 'Hapus';
            del.addEventListener('click', function (e) { e.stopPropagation(); removePhoto(idx); });
            wrap.appendChild(img); wrap.appendChild(del);
            // clicking image opens full preview
            wrap.addEventListener('click', () => { ensureImageModal(); document.getElementById('imageModalImg').src = f.previewUrl; document.getElementById('imageModal').style.display = 'flex'; });
            photoGallery.appendChild(wrap);
        });
        updateTakePhotoLabel();
    }

    function removePhoto(index) {
        if (fotoFiles[index]) { URL.revokeObjectURL(fotoFiles[index].previewUrl); }
        fotoFiles.splice(index, 1);
        renderPhotoPreviews();
    }

    fotoInput.addEventListener('change', function () {
        const files = Array.from(this.files || []);
        if (!files.length) return;
        files.forEach(f => { f.previewUrl = URL.createObjectURL(f); fotoFiles.push(f); });
        // clear input to allow re-uploading same files later
        this.value = '';
        renderPhotoPreviews();
    });

    function fileToImageTeknisi(file) {
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

    async function compressTeknisiPhoto(file) {
        const img = await fileToImageTeknisi(file);

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        const maxWidth = 1280;
        const maxHeight = 1600;

        let width = img.width;
        let height = img.height;

        if (width > maxWidth || height > maxHeight) {
            const ratio = Math.min(maxWidth / width, maxHeight / height);
            width = Math.round(width * ratio);
            height = Math.round(height * ratio);
        }

        canvas.width = width;
        canvas.height = height;

        ctx.drawImage(img, 0, 0, width, height);

        return new Promise((resolve) => {
            canvas.toBlob((blob) => {
                const compressedFile = new File(
                    [blob],
                    file.name.replace(/\.[^/.]+$/, '') + '.jpg',
                    {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    }
                );

                resolve(compressedFile);
            }, 'image/jpeg', 0.72);
        });
    }

    document.getElementById('finishForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        if (isFinishSubmitting) {
            return;
        }
        isFinishSubmitting = true;

        clearGlobalMessages();
        const ket = document.getElementById('keterangan_perbaikan').value.trim();
        if (!ket) { showGlobalError('Keterangan perbaikan wajib diisi.'); isFinishSubmitting = false; return; }
        if (!fotoFiles.length) { showGlobalError('Foto setelah perbaikan wajib diisi.'); isFinishSubmitting = false; return; }

        const btn = document.getElementById('submitBtn');
        setButtonLoading(btn, true, 'Mengirim...');

        const form = new FormData();
        form.append('id_user', petugas.id_user || ''); form.append('nipp', petugas.nipp || '');
        form.append('keterangan_perbaikan', ket);
        // append all photos as array with compression
        if (fotoFiles.length) {
            for (let i = 0; i < fotoFiles.length; i++) {
                try {
                    const compressed = await compressTeknisiPhoto(fotoFiles[i]);
                    form.append('foto_perbaikan[]', compressed, compressed.name);
                } catch (error) {
                    console.error('Gagal kompres foto, kirim file asli:', error);
                    form.append('foto_perbaikan[]', fotoFiles[i], fotoFiles[i].name || `foto_${Date.now()}_${i}.jpg`);
                }
            }
        }
        try {
            const response = await fetch(`/api/teknisi/laporan-kendala/${id}/selesai`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: form
            });

            const rawResponse = await response.text();

            let result;
            try {
                result = rawResponse ? JSON.parse(rawResponse) : {};
            } catch (error) {
                console.error('Response bukan JSON:', rawResponse);
                throw new Error('Server mengembalikan response tidak valid. Cek ukuran foto atau error backend.');
            }

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal konfirmasi selesai');
            }
            localStorage.setItem('teknisi_selected_kendala', String(id));
            window.location.href = `/teknisi/detail-selesai?id=${id}`;
        } catch (error) {
            showGlobalError(error.message);
            isFinishSubmitting = false;
            setButtonLoading(btn, false);
        }
    });
    
    // show loading state on buttons
    function setButtonLoading(btn, loading, text) {
        if (!btn) return;
        if (loading) {
            btn.disabled = true;
            btn.classList.add('loading');
            btn.innerHTML = `<span class="spinner" aria-hidden="true"></span><span class="btn-text">${text || 'Memproses...'}</span>`;
        } else {
            btn.disabled = false;
            btn.classList.remove('loading');
            btn.innerHTML = `<span class="btn-text">Konfirmasi Selesai</span>`;
        }
    }
    document.addEventListener('DOMContentLoaded', loadDetail);
</script>
<style>
    .photo-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
    .photo-grid img { width:100%; aspect-ratio:1.58 / 1; object-fit:cover; border-radius:8px; display:block; background:#edf0f5; }
</style>
@endpush
