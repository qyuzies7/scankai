@extends('layouts.petugas-app')

@section('content')
<div class="tech-page">
    <div class="tech-tabs three">
        <button class="tab active" data-status="menunggu">Menunggu</button>
        <button class="tab" data-status="diproses">Diproses</button>
        <button class="tab" data-status="selesai">Selesai</button>
    </div>

    <div id="tech-tools" class="tech-tools">
        <div class="search-box">
            <iconify-icon icon="mdi:magnify"></iconify-icon>
            <input id="searchInput" type="text" placeholder="Cari kereta, masalah, atau pelapor">
        </div>
        <button id="priorityFilter" type="button" class="filter-button">
            <iconify-icon icon="mdi:filter-outline"></iconify-icon>
            <span>Prioritas</span>
            <iconify-icon icon="mdi:menu-down"></iconify-icon>
        </button>
    </div>

    <div id="selesai-summary" class="done-summary" style="display:none;">
        <iconify-icon icon="mdi:check-circle"></iconify-icon>
        <span id="doneSummaryText">0 perbaikan selesai hari ini</span>
        <iconify-icon icon="mdi:calendar-month-outline" class="right-icon"></iconify-icon>
    </div>

    <div id="tech-list" class="tech-list"></div>
</div>
@endsection

@push('styles')
<style>
    .petugas-page { padding: 18px 14px 24px; }
    .tech-page { width: 100%; max-width: 420px; margin: 0 auto; }
    .tech-tabs { display: grid; grid-template-columns: repeat(3, 1fr); background: #fff; border: 1px solid #dfe4ef; border-radius: 10px; overflow: hidden; box-shadow: 0 8px 20px rgba(20, 20, 40, .04); margin-bottom: 14px; }
    .tech-tabs .tab { position: relative; height: 50px; border: 0; background: #fff; color: #273250; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; }
    .tech-tabs .tab:not(:last-child) { border-right: 1px solid #e6e8ee; }
    .tech-tabs .tab.active { color: #3413d8; }
    .tech-tabs .tab.active::after { content: ''; position: absolute; left: 0; right: 0; bottom: 0; height: 3px; background: #4d17d5; }
    .tech-tools { display: flex; gap: 10px; margin-bottom: 14px; }
    .search-box { flex: 1; height: 48px; border: 1px solid #dfe4ef; background: #fff; border-radius: 10px; display: flex; align-items: center; gap: 10px; padding: 0 13px; min-width: 0; }
    .search-box iconify-icon { font-size: 22px; color: #31415e; }
    .search-box input { flex: 1; border: 0; outline: none; min-width: 0; color: #14203a; font-size: 12px; font-family: 'Poppins', sans-serif; font-weight: 500; }
    .search-box input::placeholder { color: #172647; opacity: .85; }
    .filter-button { height: 48px; border: 1px solid #dfe4ef; background: #fff; border-radius: 10px; display: flex; align-items: center; gap: 8px; padding: 0 12px; color: #101729; font-size: 12px; font-weight: 800; font-family: 'Poppins', sans-serif; white-space: nowrap; }
    .filter-button span { font-weight: 500; }
    .filter-button iconify-icon { color: #31415e; font-size: 19px; }
    .done-summary { height: 50px; border-radius: 10px; background: #f0fff2; border: 1px solid #cbeed0; display: flex; align-items: center; gap: 10px; padding: 0 14px; color: #0d812b; font-size: 14px; font-weight: 800; margin-bottom: 14px; }
    .done-summary iconify-icon { font-size: 24px; }
    .done-summary .right-icon { margin-left: auto; color: #586070; font-size: 19px; }
    .tech-list { display: flex; flex-direction: column; gap: 14px; padding-bottom: 18px; }
    .tech-card { background: #fff; border: 1px solid #eef0f5; border-radius: 14px; overflow: hidden; box-shadow: 0 7px 18px rgba(20, 20, 40, .08); }
    .tech-head { display: grid; grid-template-columns: 60px minmax(0, 1fr) auto; gap: 12px; align-items: center; padding: 16px 14px 13px; border-bottom: 1px solid #eaedf4; }
    .train-icon { width: 54px; height: 54px; border-radius: 50%; background: #4613bb; color: #fff; display: flex; align-items: center; justify-content: center; }
    .train-icon iconify-icon { font-size: 32px; }
    .train-title { font-size: 16px; font-weight: 800; color: #030711; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .train-sub { font-size: 12px; color: #263654; margin-top: 6px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .badge { padding: 6px 10px; border-radius: 7px; font-size: 11px; font-weight: 800; white-space: nowrap; align-self: start; }
    .badge.new { background: #eaf3ff; color: #1267e4; }
    .badge.priority { background: #ffe8e8; color: #ff263e; }
    .badge.process { background: #ffefe7; color: #ff3d13; font-weight:500; }
    .badge.done { background: #e2f6e6; color: #16822e; }
    .card-body { padding: 13px 14px 16px; }
    .info-row { display: grid; grid-template-columns: 24px 1fr 1.1fr; gap: 8px; align-items: center; margin: 11px 0; font-size: 12px; color: #243453; font-weight: 500; }
    .info-row iconify-icon { font-size: 18px; color: #31415e; }
    .info-row .value { text-align: right; color: #060a13; font-weight: 500; word-break: break-word; }
    .action-row { display: flex; justify-content: flex-end; margin-top: 14px; }
    .btn-orange { border: 0; background: linear-gradient(180deg, #ff5a0b 0%, #ff3600 100%); color: #fff; height: 39px; min-width: 100px; padding: 0 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 800; cursor: pointer; }
    .btn-outline { border: 1.5px solid #5516e6; background: #fff; color: #4312d5; height: 37px; min-width: 150px; padding: 0 16px; border-radius: 5px; font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; }
    .btn-continue { border: 1.5px solid #5516e6; background: #fff; color: #4312d5; height: 37px; min-width: 150px; padding: 0 16px; border-radius: 5px; font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; }
    .card-chevron { margin-left: auto; color: #3c455b; font-size: 26px; align-self: center; }
    .done-card { cursor: pointer; }
    .empty-card { background: #fff; border-radius: 14px; padding: 24px 18px; text-align: center; color: #6a6470; box-shadow: 0 7px 18px rgba(20, 20, 40, .08); font-size: 14px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js"></script>
<script>
    ensureTeknisiLogin();
    const petugas = safeJsonParse(localStorage.getItem('petugas'), {});
    const initialStatus = new URLSearchParams(window.location.search).get('tab') || 'menunggu';
    let activeStatus = ['menunggu', 'diproses', 'selesai'].includes(initialStatus) ? initialStatus : 'menunggu';
    let allItems = [];
    let priorityFilterMode = 'semua'; // 'semua' | 'prioritas' | 'baru'

    function apiUrl(status) {
        const params = new URLSearchParams({ status, id_user: petugas.id_user || '', nipp: petugas.nipp || '' });
        return `/api/teknisi/laporan-kendala?${params.toString()}`;
    }

    function escapeHtml(value) {
        return String(value ?? '-').replace(/[&<>'"]/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[char]));
    }

    function normalizeText(value) { return String(value || '').toLowerCase(); }
    function isPriority(item) { return Boolean(item.is_prioritas); }
    function isNew(item) {
        // tag baru hanya muncul dalam waktu 24 jam
        if (!item || !item.is_baru) return false;
        const dateKeys = ['waktu', 'waktu_lapor', 'waktu_lapor_label', 'created_at'];
        let ts = NaN;
        for (const k of dateKeys) {
            if (item[k]) {
                ts = Date.parse(item[k]);
                if (!isNaN(ts)) break;
            }
        }
        if (isNaN(ts)) return Boolean(item.is_baru);
        const age = Date.now() - ts;
        return age < (24 * 60 * 60 * 1000); // 24 hours
    }

    async function loadItems(status = activeStatus) {
        clearGlobalMessages();
        const list = document.getElementById('tech-list');
        list.innerHTML = '<div class="empty-card">Memuat data...</div>';
        try {
            const response = await fetch(apiUrl(status), { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Gagal memuat data');
            allItems = result.data || [];
            renderItems();
        } catch (error) {
            showGlobalError(error.message || 'Tidak bisa terhubung ke server backend');
            list.innerHTML = '<div class="empty-card">Data belum bisa dimuat.</div>';
        }
    }

    function renderItems() {
        const keyword = normalizeText(document.getElementById('searchInput').value);
        const list = document.getElementById('tech-list');
        let items = allItems.filter(item => {
            const text = `${item.nama_ka} ${item.jenis_kendala} ${item.pelapor} ${item.nosarana} ${item.nama_teknisi}`.toLowerCase();
            return text.includes(keyword);
        });
        // apply priority filter only on 'menunggu' tab
        if (activeStatus === 'menunggu') {
            if (priorityFilterMode === 'prioritas') items = items.filter(isPriority);
            else if (priorityFilterMode === 'baru') items = items.filter(isNew);
        }

        // always move priority items to top of the list
        items.sort((a, b) => {
            if ((a.is_prioritas || false) === (b.is_prioritas || false)) return 0;
            return (a.is_prioritas ? -1 : 1);
        });

        document.getElementById('selesai-summary').style.display = activeStatus === 'selesai' ? 'flex' : 'none';
        document.getElementById('tech-tools').style.display = activeStatus === 'selesai' ? 'none' : 'flex';
        // hide priority filter button only on 'diproses'
        document.getElementById('priorityFilter').style.display = activeStatus === 'diproses' ? 'none' : 'inline-flex';
        if (activeStatus === 'selesai') document.getElementById('doneSummaryText').textContent = `${items.length} perbaikan selesai hari ini`;

        if (!items.length) {
            list.innerHTML = '<div class="empty-card">Belum ada data.</div>';
            return;
        }

        const cardFn = activeStatus === 'selesai' ? doneCard : (activeStatus === 'diproses' ? processCard : waitingCard);
        list.innerHTML = items.map(cardFn).join('');
    }

    function waitingCard(item) {
        const statusBadge = isPriority(item)
            ? '<span class="badge priority">Prioritas</span>'
            : (isNew(item) ? '<span class="badge new">Baru</span>' : '');
        return `<div class="tech-card">
            <div class="tech-head">
                <div class="train-icon"><iconify-icon icon="mdi:train"></iconify-icon></div>
                <div><div class="train-title">${escapeHtml(item.nama_ka)}</div><div class="train-sub">${escapeHtml(item.gerbong_ka)} •  ${escapeHtml(item.nosarana)}</div></div>
                ${statusBadge}
            </div>
            <div class="card-body">
                <div class="info-row"><iconify-icon icon="mdi:wrench"></iconify-icon><span>Jenis Masalah</span><span class="value">${escapeHtml(item.jenis_kendala)}</span></div>
                <div class="info-row"><iconify-icon icon="mdi:clock-outline"></iconify-icon><span>Waktu Dilaporkan</span><span class="value">${escapeHtml(item.waktu_lapor_label)}</span></div>
                <div class="info-row"><iconify-icon icon="mdi:account"></iconify-icon><span>Pelapor</span><span class="value">${escapeHtml(item.pelapor)}</span></div>
                <div class="action-row"><button class="btn-orange" onclick="tinjau(${item.id_laporan_kendala})">Tinjau</button></div>
            </div>
        </div>`;
    }

    function processCard(item) {
        return `<div class="tech-card">
            <div class="tech-head">
                <div class="train-icon"><iconify-icon icon="mdi:train"></iconify-icon></div>
                <div><div class="train-title">${escapeHtml(item.nama_ka)}</div><div class="train-sub">${escapeHtml(item.gerbong_ka)} •  ${escapeHtml(item.nosarana)}</div></div>
                <span class="badge process">Diproses</span>
            </div>
            <div class="card-body">
                <div class="info-row"><iconify-icon icon="mdi:wrench"></iconify-icon><span>Jenis Masalah</span><span class="value">${escapeHtml(item.jenis_kendala)}</span></div>
                <div class="info-row"><iconify-icon icon="mdi:clock-outline"></iconify-icon><span>Waktu Dilaporkan</span><span class="value">${escapeHtml(item.waktu_lapor_label)}</span></div>
                <div class="info-row"><iconify-icon icon="mdi:account"></iconify-icon><span>Pelapor</span><span class="value">${escapeHtml(item.pelapor)}</span></div>
                <div class="info-row"><iconify-icon icon="mdi:account-hard-hat"></iconify-icon><span>Teknisi</span><span class="value">${escapeHtml(item.nama_teknisi)}</span></div>
                <div class="info-row"><iconify-icon icon="mdi:clock-outline"></iconify-icon><span>Waktu Diproses</span><span class="value">${escapeHtml(item.diproses_label)}</span></div>
                <div class="action-row"><button class="btn-continue" onclick="lanjutkan(${item.id_laporan_kendala})">Lanjutkan Perbaikan <iconify-icon icon="mdi:chevron-right"></iconify-icon></button></div>
            </div>
        </div>`;
    }

    function doneCard(item) {
        return `<div class="tech-card done-card" onclick="openDetail(${item.id_laporan_kendala})">
            <div class="tech-head">
                <div class="train-icon"><iconify-icon icon="mdi:train"></iconify-icon></div>
                <div><div class="train-title">${escapeHtml(item.nama_ka)}</div><div class="train-sub">${escapeHtml(item.gerbong_ka)} •  ${escapeHtml(item.nosarana)}</div></div>
                <span class="badge done">Selesai</span>
            </div>
            <div class="card-body" style="display:grid; grid-template-columns:1fr 28px; gap:8px; align-items:center;">
                <div>
                    <div class="info-row"><iconify-icon icon="mdi:wrench"></iconify-icon><span>Jenis Masalah</span><span class="value">${escapeHtml(item.jenis_kendala)}</span></div>
                    <div class="info-row"><iconify-icon icon="mdi:clock-outline"></iconify-icon><span>Waktu Selesai</span><span class="value">${escapeHtml(item.selesai_label)}</span></div>
                    <div class="info-row"><iconify-icon icon="mdi:account"></iconify-icon><span>Teknisi</span><span class="value">${escapeHtml(item.nama_teknisi)}</span></div>
                </div>
                <iconify-icon class="card-chevron" icon="mdi:chevron-right"></iconify-icon>
            </div>
        </div>`;
    }

    function tinjau(id) { localStorage.setItem('teknisi_selected_kendala', String(id)); window.location.href = `/teknisi/detail-kendala?id=${id}`; }
    function lanjutkan(id) { localStorage.setItem('teknisi_selected_kendala', String(id)); window.location.href = `/teknisi/proses?id=${id}`; }
    function openDetail(id) { localStorage.setItem('teknisi_selected_kendala', String(id)); window.location.href = `/teknisi/detail-selesai?id=${id}`; }

    function setActiveTab(status) {
        activeStatus = status;
        document.querySelectorAll('.tech-tabs .tab').forEach(b => b.classList.toggle('active', b.dataset.status === status));
        const url = new URL(window.location.href);
        url.searchParams.set('tab', status);
        window.history.replaceState({}, '', url.toString());
        loadItems(status);
    }

    document.querySelectorAll('.tech-tabs .tab').forEach(btn => btn.addEventListener('click', () => setActiveTab(btn.dataset.status)));
    document.getElementById('searchInput').addEventListener('input', renderItems);
    // priority filter dropdown for 'menunggu' tab
    document.getElementById('priorityFilter').addEventListener('click', function (e) {
        if (activeStatus !== 'menunggu') return; // only active on menunggu
        // build dropdown
        let menu = document.getElementById('priorityFilterMenu');
        if (menu) { menu.remove(); return; }
        menu = document.createElement('div'); menu.id = 'priorityFilterMenu';
        menu.style.cssText = 'position:absolute;background:#fff;border:1px solid #e6e8ee;border-radius:8px;box-shadow:0 8px 20px rgba(20,20,40,.08);padding:6px;z-index:9999;';
        const options = [ ['semua','Semua'], ['prioritas','Prioritas'], ['baru','Baru'] ];
        options.forEach(([val,label]) => {
            const item = document.createElement('div');
            item.style.cssText = 'padding:8px 12px;cursor:pointer;font-weight:500;font-size:13px;color:#101729;';
            item.textContent = label;
            item.addEventListener('click', () => {
                priorityFilterMode = val;
                document.getElementById('priorityFilter').querySelector('span').textContent = label;
                menu.remove();
                renderItems();
            });
            menu.appendChild(item);
        });
        document.body.appendChild(menu);
        // position menu under button
        const rect = e.currentTarget.getBoundingClientRect();
        menu.style.left = (rect.left) + 'px';
        menu.style.top = (rect.bottom + 8) + 'px';
    });
    document.addEventListener('DOMContentLoaded', () => {
        // initialize priority label
        document.getElementById('priorityFilter').querySelector('span').textContent = 'Semua';
        setActiveTab(activeStatus);
    });
</script>
@endpush
