@extends('layouts.petugas-app')

@section('content')
<div class="history-page">
    <div class="history-header-row">
        <button type="button" class="history-back-btn" onclick="window.location.href='/scanqr'">
            <iconify-icon icon="mdi:chevron-left"></iconify-icon>
        </button>
        <div class="history-page-title">History Scan Saya</div>
        <div class="history-header-spacer"></div>
    </div>

    <div id="history-list" class="history-list"></div>

    <div class="history-footnote">
        <iconify-icon icon="mdi:information-outline"></iconify-icon>
        <span>Riwayat 30 hari terakhir</span>
    </div>
</div>
@endsection

@push('styles')
<style>
    .history-page {
        max-width: 380px;
        margin: 0 auto;
    }

    .history-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .history-back-btn {
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

    .history-back-btn iconify-icon {
        font-size: 24px;
    }

    .history-page-title {
        flex: 1;
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        color: #2d2135;
    }

    .history-header-spacer {
        width: 32px;
    }

    .history-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .history-card {
        background: #fff;
        border-radius: 16px;
        padding: 16px 14px;
        box-shadow: 0 4px 10px rgba(0,0,0,.12);
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .history-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #efe9ff;
        color: #5b38a6;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .history-icon iconify-icon {
        font-size: 24px;
    }

    .history-body {
        flex: 1;
        min-width: 0;
    }

    .history-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .history-train-title {
        font-size: 14px;
        font-weight: 700;
        color: #2d2135;
        line-height: 1.4;
    }

    .history-meta {
        font-size: 13px;
        color: #675f69;
        line-height: 1.45;
        margin-top: 2px;
    }

    .history-badge {
        border-radius: 10px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        margin-top: 4px;
    }

    .history-badge.success {
        background: #e9f8dc;
        color: #4b9f26;
        border: 1px solid #bbe59a;
    }

    .history-badge.pending {
        background: #fff5dd;
        color: #cc9500;
        border: 1px solid #f4d98b;
    }

    .history-bottom {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        font-size: 12px;
        color: #716879;
    }

    .history-bottom iconify-icon {
        font-size: 16px;
    }

    .history-empty {
        background: #fff;
        border-radius: 16px;
        padding: 30px 18px;
        text-align: center;
        color: #6f6672;
        box-shadow: 0 4px 10px rgba(0,0,0,.10);
        font-size: 14px;
        line-height: 1.6;
    }

    .history-footnote {
        margin-top: 16px;
        text-align: center;
        color: #7a7377;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .history-footnote iconify-icon {
        font-size: 16px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js"></script>
<script>
    ensurePetugasLogin();

    function renderHistory() {
        const petugas = JSON.parse(localStorage.getItem('petugas'));
        const historyList = document.getElementById('history-list');
        const allHistory = getPetugasHistory(petugas.nipp).filter(item => item.type === 'scan');
        const history30Days = allHistory.filter(item => {
            const itemDate = new Date(item.waktu || item.created_at || new Date());
            const diff = (new Date() - itemDate) / (1000 * 60 * 60 * 24);
            return diff <= 30;
        });

        if (!history30Days.length) {
            historyList.innerHTML = `
                <div class="history-empty">
                    Belum ada riwayat scan.<br>
                    History scan petugas akan tampil di sini.
                </div>
            `;
            return;
        }

        historyList.innerHTML = history30Days.map(item => {
            const badgeClass = item.status_laporan === 'Laporan Terkirim' ? 'success' : 'pending';
            return `
                <div class="history-card">
                    <div class="history-icon">
                        <iconify-icon icon="mdi:train"></iconify-icon>
                    </div>
                    <div class="history-body">
                        <div class="history-card-top">
                            <div>
                                <div class="history-train-title">${item.nama_ka || '-'}</div>
                                <div class="history-meta">Gerbong ${item.gerbong || '-'}</div>
                                <div class="history-meta">No Sarana ${item.nosarana || '-'}</div>
                            </div>
                            <div class="history-badge ${badgeClass}">${item.status_laporan || 'Belum Lapor'}</div>
                        </div>
                        <div class="history-bottom">
                            <iconify-icon icon="mdi:calendar-month-outline"></iconify-icon>
                            <span>${formatDateDisplay(item.waktu)}</span>
                            <span>•</span>
                            <span>${formatTimeHHMM(item.waktu)}</span>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    document.addEventListener('DOMContentLoaded', renderHistory);
</script>
@endpush
