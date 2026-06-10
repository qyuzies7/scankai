@extends('layouts.petugas-app')

@section('content')
<div class="detail-page">
    <a href="/teknisi?tab=menunggu" class="back-link"><iconify-icon icon="mdi:arrow-left"></iconify-icon> Kembali ke beranda</a>
    <div id="detailContent"><div class="card-box">Memuat detail kendala...</div></div>
    <button id="startBtn" class="btn-start" type="button" style="display:none;"><span class="btn-text">Mulai Perbaikan</span></button>
</div>
@endsection

@push('styles')
<style>
    .petugas-page { padding: 18px 14px 22px; }
    .detail-page { width: 100%; max-width: 420px; margin: 0 auto; padding-bottom: 78px; }
    .back-link { display: inline-flex; align-items: center; gap: 8px; color: #4014d1; font-size: 13px; font-weight: 800; text-decoration: none; margin-bottom: 16px; }
    .back-link iconify-icon { font-size: 21px; }
    .card-box { background: #fff; border: 1px solid #eef0f5; border-radius: 14px; padding: 14px; box-shadow: 0 7px 18px rgba(20,20,40,.08); margin-bottom: 14px; }
    .top-card { padding: 15px 14px; }
    .top-grid { display: grid; grid-template-columns: 60px 1fr auto; gap: 12px; align-items: start; }
    .train-icon { width: 54px; height: 54px; border-radius: 50%; background:#4613bb; color:#fff; display:flex; align-items:center; justify-content:center; }
    .train-icon iconify-icon { font-size: 32px; }
    .title { font-size: 17px; font-weight: 800; color: #050914; line-height: 1.25; }
    .sub { font-size: 13px; color: #263654; font-weight: 500; margin-top: 6px; }
    .badge { padding: 6px 10px; border-radius: 7px; font-size: 11px; font-weight: 800; white-space: nowrap; }
    .badge.new { background: #eaf3ff; color:#1267e4; }
    .badge.priority { background: #ffe8e8; color:#ff263e; }
    .info-area { border-top: 1px solid #eaedf4; margin-top: 14px; padding-top: 12px; }
    .info-row { display:grid; grid-template-columns: 24px 1fr 1.15fr; gap: 8px; align-items:center; margin: 13px 0; font-size: 13px; color:#243453; font-weight:500; }
    .info-row iconify-icon { font-size: 19px; color:#31415e; }
    .info-row .value { text-align:right; color:#060a13; font-weight:500; }
    .section-title { font-size: 15px; font-weight: 800; color:#050914; margin-bottom: 12px; }
    .note { color:#263654; font-size: 13px; line-height: 1.8; margin:0; font-weight: 500; }
    .photo-grid { display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .photo-grid img { width:100%; aspect-ratio: 1.58 / 1; object-fit: cover; border-radius: 8px; display:block; background:#edf0f5; }
    .btn-start { position: fixed; left: 50%; bottom: 18px; transform: translateX(-50%); width: calc(100% - 28px); max-width: 420px; height: 52px; border:0; border-radius: 8px; background: linear-gradient(180deg, #ff5a0b 0%, #ff3600 100%); color:#fff; font-family:'Poppins', sans-serif; font-size:16px; font-weight:800; cursor:pointer; box-shadow: 0 8px 16px rgba(255, 61, 0, .18); }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js"></script>
<script>
    ensureTeknisiLogin();
    const petugas = safeJsonParse(localStorage.getItem('petugas'), {});
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id') || localStorage.getItem('teknisi_selected_kendala');
    let currentItem = null;
    function authParams() { return new URLSearchParams({ id_user: petugas.id_user || '', nipp: petugas.nipp || '' }); }
    function escapeHtml(value) { return String(value ?? '-').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c])); }
    function statusBadge(item) {
        if (item.is_prioritas) return '<span class="badge priority">Prioritas</span>';
        if (item.is_baru) return '<span class="badge new">Baru</span>';
        return '';
    }
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

    // simple image modal: opens clicked image in an overlay
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
        if (!id) { window.location.href = '/teknisi?tab=menunggu'; return; }
        try {
            const response = await fetch(`/api/teknisi/laporan-kendala/${id}?${authParams().toString()}`, { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Gagal memuat detail');
            currentItem = result.data;
            renderDetail(currentItem);
            document.getElementById('startBtn').style.display = 'block';
        } catch (error) { showGlobalError(error.message); }
    }
    function renderDetail(item) {
        document.getElementById('detailContent').innerHTML = `<div class="card-box top-card">
            <div class="top-grid">
                <div class="train-icon"><iconify-icon icon="mdi:train"></iconify-icon></div>
                <div><div class="title">${escapeHtml(item.nama_ka)}</div><div class="sub">${escapeHtml(item.gerbong_ka)}</div><div class="sub"> ${escapeHtml(item.nosarana)}</div></div>
                ${statusBadge(item)}
            </div>
            <div class="info-area">
                <div class="info-row"><iconify-icon icon="mdi:wrench"></iconify-icon><span>Jenis Masalah</span><span class="value">${escapeHtml(item.jenis_kendala)}</span></div>
                <div class="info-row"><iconify-icon icon="mdi:clock-outline"></iconify-icon><span>Waktu Dilaporkan</span><span class="value">${escapeHtml(item.waktu_lapor_label)}</span></div>
                <div class="info-row"><iconify-icon icon="mdi:account"></iconify-icon><span>Pelapor</span><span class="value">${escapeHtml(item.pelapor)}</span></div>
            </div>
        </div>
        <div class="card-box"><div class="section-title">Keterangan Masalah</div><p class="note">${escapeHtml(item.catatan)}</p></div>
        <div class="card-box"><div class="section-title">Foto Masalah</div>${photoPair(item.foto_url)}</div>`;
    }
    document.getElementById('startBtn').addEventListener('click', async function () {
        clearGlobalMessages();
        const form = new FormData();
        form.append('id_user', petugas.id_user || '');
        form.append('nipp', petugas.nipp || '');
        setButtonLoading(this, true, 'Memulai...');
        try {
            const response = await fetch(`/api/teknisi/laporan-kendala/${id}/ambil`, { method: 'POST', headers: { 'Accept': 'application/json' }, body: form });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Gagal mulai perbaikan');
            localStorage.setItem('teknisi_selected_kendala', String(id));
            window.location.href = '/teknisi?tab=diproses';
        } catch (error) { showGlobalError(error.message); setButtonLoading(this, false); }
    });
    document.addEventListener('DOMContentLoaded', loadDetail);
</script>
@endpush
