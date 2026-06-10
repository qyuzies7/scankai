@extends('layouts.petugas-app')

@section('content')
<div class="done-detail-page">
    <a href="/teknisi?tab=selesai" class="back-link"><iconify-icon icon="mdi:arrow-left"></iconify-icon> Kembali ke Halaman Selesai</a>
    <div id="detailContent"><div class="card-box">Memuat detail selesai...</div></div>
</div>
@endsection

@push('styles')
<style>
    .petugas-page { padding: 18px 14px 22px; }
    .done-detail-page { width:100%; max-width:420px; margin:0 auto; }
    .back-link { display:inline-flex; align-items:center; gap:8px; color:#4014d1; font-size:13px; font-weight:800; text-decoration:none; margin-bottom:16px; }
    .back-link iconify-icon { font-size:21px; }
    .card-box { background:#fff; border:1px solid #eef0f5; border-radius:14px; padding:14px; box-shadow:0 7px 18px rgba(20,20,40,.08); margin-bottom:14px; }
    .top-grid { display:grid; grid-template-columns:60px 1fr auto; gap:12px; align-items:start; }
    .train-icon { width:54px; height:54px; border-radius:50%; background:#4613bb; color:#fff; display:flex; align-items:center; justify-content:center; }
    .train-icon iconify-icon { font-size:32px; }
    .title { font-size:17px; font-weight:800; color:#050914; line-height:1.25; }
    .sub { font-size:13px; color:#263654; font-weight:500; margin-top:6px; }
    .status-wrap { text-align:right; }
    .badge { display:inline-block; padding:6px 10px; border-radius:7px; background:#e2f6e6; color:#16822e; font-size:11px; font-weight:800; }
    .time-label { color:#263654; font-size:11px; font-weight:600; margin-top:8px; }
    .time-value { color:#050914; font-size:12px; font-weight:800; margin-top:2px; }
    .detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px 12px; }
    .mini-info { display:grid; grid-template-columns:24px 1fr; gap:8px; align-items:start; }
    .mini-info iconify-icon { color:#31415e; font-size:19px; margin-top:2px; }
    .mini-label { color:#243453; font-size:12px; font-weight:600; margin-bottom:4px; }
    .mini-value { color:#050914; font-size:12px; font-weight:500; line-height:1.45; }
    .section-title { color:#050914; font-size:15px; font-weight:800; margin-bottom:12px; }
    .note { color:#263654; font-size:13px; line-height:1.75; margin:0; font-weight:500; }
    .photo-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
    .photo-grid img { width:100%; aspect-ratio:1.58 / 1; object-fit:cover; border-radius:8px; display:block; background:#edf0f5; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js"></script>
<script>
    ensureTeknisiLogin();
    const petugas = safeJsonParse(localStorage.getItem('petugas'), {});
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id') || localStorage.getItem('teknisi_selected_kendala');
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
        if (!id) { window.location.href = '/teknisi?tab=selesai'; return; }
        try {
            const response = await fetch(`/api/teknisi/laporan-kendala/${id}?${authParams().toString()}`, { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Gagal memuat detail');
            renderDetail(result.data);
        } catch (error) { showGlobalError(error.message); }
    }
    function renderDetail(item) {
        document.getElementById('detailContent').innerHTML = `<div class="card-box">
            <div class="top-grid">
                <div class="train-icon"><iconify-icon icon="mdi:train"></iconify-icon></div>
                <div><div class="title">${escapeHtml(item.nama_ka)}</div><div class="sub">${escapeHtml(item.gerbong_ka)}</div><div class="sub"> ${escapeHtml(item.nosarana)}</div></div>
                <div class="status-wrap"><span class="badge">Selesai</span><div class="time-label">Waktu Selesai</div><div class="time-value">${escapeHtml(item.selesai_label)}</div></div>
            </div>
        </div>
        <div class="card-box"><div class="detail-grid">
            <div class="mini-info"><iconify-icon icon="mdi:wrench"></iconify-icon><div><div class="mini-label">Jenis Masalah</div><div class="mini-value">${escapeHtml(item.jenis_kendala)}</div></div></div>
            <div class="mini-info"><iconify-icon icon="mdi:account"></iconify-icon><div><div class="mini-label">Pelapor</div><div class="mini-value">${escapeHtml(item.pelapor)}</div></div></div>
            <div class="mini-info"><iconify-icon icon="mdi:clock-outline"></iconify-icon><div><div class="mini-label">Waktu Dilaporkan</div><div class="mini-value">${escapeHtml(item.waktu_lapor_label)}</div></div></div>
            <div class="mini-info"><iconify-icon icon="mdi:account-hard-hat"></iconify-icon><div><div class="mini-label">Teknisi</div><div class="mini-value">${escapeHtml(item.nama_teknisi)}</div></div></div>
            <div class="mini-info"><iconify-icon icon="mdi:clock-outline"></iconify-icon><div><div class="mini-label">Waktu Diproses</div><div class="mini-value">${escapeHtml(item.diproses_label)}</div></div></div>
        </div></div>
        <div class="card-box"><div class="section-title">Keterangan Masalah</div><p class="note">${escapeHtml(item.catatan)}</p></div>
        <div class="card-box"><div class="section-title">Foto Masalah (Dari Pelapor)</div>${photoPair(item.foto_url, 'Foto Masalah')}</div>
        <div class="card-box"><div class="section-title">Keterangan Perbaikan</div><p class="note">${escapeHtml(item.keterangan_perbaikan)}</p></div>
        <div class="card-box"><div class="section-title">Foto Setelah Perbaikan</div>${photoPair(item.foto_perbaikan_url, 'Foto Setelah Perbaikan')}</div>`;
    }
    document.addEventListener('DOMContentLoaded', loadDetail);
</script>
@endpush
