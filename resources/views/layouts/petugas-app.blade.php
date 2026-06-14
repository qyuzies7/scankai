<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Scan</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #e9e9e9;
            font-family: 'Poppins', sans-serif;
        }

        .petugas-topbar {
            height: 62px;
            background: linear-gradient(90deg, #401880 0%, #F64A4A 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px;
        }

        .petugas-topbar .logo {
            width: 60px;
            display: block;
        }

        .btn-akhiri {
            background: #F58220;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        .petugas-page {
            min-height: calc(100vh - 62px);
            padding: 22px 18px 24px;
        }

        .alert-error {
            background: #ffe3e3;
            color: #b00020;
            padding: 10px 12px;
            border-radius: 10px;
            margin: 0 auto 14px;
            max-width: 380px;
            font-size: 13px;
            text-align: left;
        }

        .alert-success {
            background: #e3ffe8;
            color: #137333;
            padding: 10px 12px;
            border-radius: 10px;
            margin: 0 auto 14px;
            max-width: 380px;
            font-size: 13px;
            text-align: left;
        }

        .confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(64, 46, 56, 0.42);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }

        .confirm-overlay.show {
            display: flex;
        }

        .confirm-modal {
            width: 100%;
            max-width: 360px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.22);
            padding: 28px 24px 20px;
            text-align: center;
        }

        .confirm-icon {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: #fff1e6;
            color: #f58220;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 54px;
            line-height: 1;
            margin: 0 auto 10px;
        }

        .confirm-title {
            font-size: 20px;
            font-weight: 700;
            color: #2b1f28;
            margin-bottom: 8px;
        }

        .confirm-text {
            font-size: 15px;
            color: #4b3a43;
            line-height: 1.45;
        }

        .confirm-action {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 22px;
        }

        .confirm-cancel-btn,
        .confirm-ok-btn {
            min-width: 78px;
            height: 40px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        .confirm-cancel-btn {
            background: #e9e6ef;
            color: #4b3a43;
        }

        .confirm-ok-btn {
            background: #f58220;
            color: #fff;
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.85;
        }

        .btn-loading .btn-text {
            opacity: 0;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-top: -8px;
            margin-left: -8px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: #fff;
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 480px) {
            .petugas-topbar {
                height: 60px;
                padding: 0 14px;
            }

            .petugas-topbar .logo {
                width: 56px;
            }

            .btn-akhiri {
                padding: 9px 14px;
                font-size: 12px;
            }

            .petugas-page {
                padding: 18px 16px 20px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="petugas-topbar">
        <img src="{{ asset('assets/img/logo_putih.png') }}" alt="Logo KAI" class="logo">
        <button type="button" class="btn-akhiri" onclick="logoutPetugas()">Akhiri Dinasan</button>
    </div>

    <div class="petugas-page">
        <div id="global-error" class="alert-error" style="display:none;"></div>
        <div id="global-success" class="alert-success" style="display:none;"></div>

        @yield('content')
    </div>

    <div class="confirm-overlay" id="logout-confirm-overlay" aria-hidden="true">
        <div class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="logoutConfirmTitle">
            <div class="confirm-icon">
                <iconify-icon icon="mdi:logout"></iconify-icon>
            </div>
            <div class="confirm-title" id="logoutConfirmTitle">Akhiri Dinasan?</div>
            <div class="confirm-text">Jika keluar sebelum kirim data, data yang belum dikirim tidak akan tersimpan.</div>
            <div class="confirm-action">
                <button type="button" class="confirm-cancel-btn" id="logout-cancel-btn">Batal</button>
                <button type="button" class="confirm-ok-btn" id="logout-ok-btn">Akhiri</button>
            </div>
        </div>
    </div>

    <script>
        function logoutPetugas() {
            const overlay = document.getElementById('logout-confirm-overlay');
            if (overlay) {
                overlay.classList.add('show');
                overlay.setAttribute('aria-hidden', 'false');
            }
        }

        function ensurePetugasLogin() {
            const petugas = localStorage.getItem('petugas');
            if (!petugas) {
                window.location.href = '/scan';
            }
        }

        function ensureTeknisiLogin() {
            const raw = localStorage.getItem('petugas');
            const user = raw ? safeJsonParse(raw, null) : null;
            if (!user) {
                window.location.href = '/scan';
                return;
            }
            if (String(user.role || 'petugas').toLowerCase() !== 'teknisi') {
                window.location.href = '/scanqr';
            }
        }

        function showGlobalError(message) {
            const box = document.getElementById('global-error');
            const successBox = document.getElementById('global-success');
            if (successBox) successBox.style.display = 'none';
            if (box) {
                box.style.display = 'block';
                box.innerText = message;
            }
        }

        function showGlobalSuccess(message) {
            const box = document.getElementById('global-success');
            const errorBox = document.getElementById('global-error');
            if (errorBox) errorBox.style.display = 'none';
            if (box) {
                box.style.display = 'block';
                box.innerText = message;
            }
        }

        function clearGlobalMessages() {
            const errorBox = document.getElementById('global-error');
            const successBox = document.getElementById('global-success');
            if (errorBox) errorBox.style.display = 'none';
            if (successBox) successBox.style.display = 'none';
        }

        function setButtonLoading(button, isLoading, loadingText = 'Memproses...') {
            if (!button) return;

            const textEl = button.querySelector('.btn-text');

            if (!button.dataset.originalText) {
                button.dataset.originalText = textEl ? textEl.textContent : button.textContent;
            }

            if (isLoading) {
                if (textEl) {
                    textEl.textContent = loadingText;
                } else {
                    button.textContent = loadingText;
                }

                button.classList.add('btn-loading');
                button.disabled = true;
            } else {
                if (textEl) {
                    textEl.textContent = button.dataset.originalText;
                } else {
                    button.textContent = button.dataset.originalText;
                }

                button.classList.remove('btn-loading');
                button.disabled = false;
            }
        }

        function safeJsonParse(value, fallback) {
            try {
                if (!value) return fallback;
                const parsed = JSON.parse(value);
                return parsed ?? fallback;
            } catch (error) {
                return fallback;
            }
        }

        document.getElementById('logout-cancel-btn')?.addEventListener('click', function () {
            const overlay = document.getElementById('logout-confirm-overlay');
            if (overlay) {
                overlay.classList.remove('show');
                overlay.setAttribute('aria-hidden', 'true');
            }
        });

        document.getElementById('logout-ok-btn')?.addEventListener('click', function () {
            localStorage.removeItem('petugas');
            localStorage.removeItem('scan_result');
            localStorage.removeItem('teknisi_selected_kendala');
            window.location.href = '/scan';
        });

        document.getElementById('logout-confirm-overlay')?.addEventListener('click', function (event) {
            if (event.target === this) {
                this.classList.remove('show');
                this.setAttribute('aria-hidden', 'true');
            }
        });

        function getHistoryStore() {
            const parsed = safeJsonParse(localStorage.getItem('petugas_scan_history'), []);
            return Array.isArray(parsed) ? parsed : [];
        }

        function saveHistoryStore(items) {
            localStorage.setItem('petugas_scan_history', JSON.stringify(items));
        }

        function getLocalDateKey(date = new Date()) {
            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        }

        function normalizeDateKey(value) {
            if (!value) return '';

            if (value instanceof Date && !Number.isNaN(value.getTime())) {
                return getLocalDateKey(value);
            }

            if (typeof value !== 'string') {
                value = String(value);
            }

            value = value.trim();

            // ISO dari browser biasanya berakhiran Z/offset timezone. Parse dulu supaya tanggalnya ikut timezone lokal HP/browser.
            if (/^\d{4}-\d{2}-\d{2}T/.test(value) && /(Z|[+-]\d{2}:?\d{2})$/.test(value)) {
                const date = new Date(value);
                if (!Number.isNaN(date.getTime())) {
                    return getLocalDateKey(date);
                }
            }

            if (/^\d{4}-\d{2}-\d{2}/.test(value)) {
                return value.slice(0, 10);
            }

            if (/^\d{2}-\d{2}-\d{4}/.test(value)) {
                const [dd, mm, yyyy] = value.slice(0, 10).split('-');
                return `${yyyy}-${mm}-${dd}`;
            }

            const date = new Date(value);
            if (!Number.isNaN(date.getTime())) {
                return getLocalDateKey(date);
            }

            return '';
        }

        function formatTimeHHMM(value) {
            if (!value || value === '-') return '-';

            if (typeof value !== 'string') {
                value = String(value);
            }

            if (/^\d{2}:\d{2}:\d{2}$/.test(value)) {
                return value.slice(0, 5);
            }

            if (/^\d{2}:\d{2}$/.test(value)) {
                return value;
            }

            const hhmmssMatch = value.match(/(\d{2}:\d{2}:\d{2})/);
            if (hhmmssMatch) {
                return hhmmssMatch[1].slice(0, 5);
            }

            const hhmmMatch = value.match(/(\d{2}:\d{2})/);
            if (hhmmMatch) {
                return hhmmMatch[1];
            }

            const date = new Date(value);
            if (!Number.isNaN(date.getTime())) {
                return `${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
            }

            return '-';
        }

        function formatDateDisplay(value) {
            if (!value) return '-';

            const dateKey = normalizeDateKey(value);
            if (!dateKey) return value;

            const [yyyy, mm, dd] = dateKey.split('-');
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            return `${dd} ${months[parseInt(mm, 10) - 1]} ${yyyy}`;
        }

        function sortHistoryDesc(items) {
            if (!Array.isArray(items)) return [];

            return [...items].sort((a, b) => {
                const timeA = new Date(a?.waktu || a?.created_at || 0).getTime();
                const timeB = new Date(b?.waktu || b?.created_at || 0).getTime();
                return timeB - timeA;
            });
        }

        function getPetugasHistory(nipp) {
            const items = getHistoryStore();

            return sortHistoryDesc(
                items.filter(item =>
                    item &&
                    String(item.nipp) === String(nipp)
                )
            );
        }

        function addHistoryItem(item) {
            const items = getHistoryStore();
            items.push(item);
            saveHistoryStore(items);
        }

        function updateHistoryItem(id, patch) {
            const items = getHistoryStore().map(item =>
                item.id === id ? { ...item, ...patch } : item
            );
            saveHistoryStore(items);
        }

        function normalizeScanIdentifier(value) {
            if (value === null || value === undefined) return '';

            return String(value)
                .trim()
                .toLowerCase()
                .replace(/\s+/g, '');
        }

        function getScanIdentifierCandidates(item) {
            if (!item) return [];

            return [
                item.barcode_value,
                item.barcode,
                item.kode_barcode,
                item.nosarana,
                item.no_sarana,
                item.id_sarana
            ].map(normalizeScanIdentifier).filter(Boolean);
        }

        function flattenScanIdentifiers(values) {
            return values
                .flatMap(value => Array.isArray(value) ? value : [value])
                .map(normalizeScanIdentifier)
                .filter(Boolean)
                .filter((value, index, self) => self.indexOf(value) === index);
        }

        function getLastScanStore() {
            const parsed = safeJsonParse(localStorage.getItem('petugas_last_scan_by_barcode'), {});
            return parsed && typeof parsed === 'object' && !Array.isArray(parsed) ? parsed : {};
        }

        function saveLastScanStore(store) {
            localStorage.setItem('petugas_last_scan_by_barcode', JSON.stringify(store));
        }

        function getPreviousTodayScanTime(...identifiers) {
            const todayKey = getLocalDateKey(new Date());
            const targetIdentifiers = flattenScanIdentifiers(identifiers);

            if (targetIdentifiers.length === 0) {
                return '-';
            }

            // Sumber utama: penyimpanan khusus per barcode/no sarana.
            // Ini lebih stabil daripada baca ulang history yang kadang format key-nya beda.
            const store = getLastScanStore();
            const savedMatches = targetIdentifiers
                .map(identifier => store[identifier])
                .filter(item => item && item.date === todayKey && item.time);

            if (savedMatches.length > 0) {
                savedMatches.sort((a, b) => new Date(b.time).getTime() - new Date(a.time).getTime());
                return formatTimeHHMM(savedMatches[0].time);
            }

            // Fallback: baca dari history lama kalau user sudah pernah scan sebelum storage khusus dibuat.
            const todayScans = sortHistoryDesc(getHistoryStore()).filter(item => {
                if (!item || item.type !== 'scan' || normalizeDateKey(item.waktu) !== todayKey) {
                    return false;
                }

                const itemIdentifiers = getScanIdentifierCandidates(item);
                return targetIdentifiers.some(identifier => itemIdentifiers.includes(identifier));
            });

            if (todayScans.length === 0) {
                return '-';
            }

            return formatTimeHHMM(todayScans[0].waktu);
        }

        function saveTodayScanTime(identifiers, waktu = null) {
            const normalizedIdentifiers = flattenScanIdentifiers([identifiers]);

            if (normalizedIdentifiers.length === 0) {
                return;
            }

            const timeValue = waktu || new Date().toISOString();
            const dateKey = normalizeDateKey(timeValue) || getLocalDateKey(new Date());
            const store = getLastScanStore();

            normalizedIdentifiers.forEach(identifier => {
                store[identifier] = {
                    date: dateKey,
                    time: timeValue
                };
            });

            saveLastScanStore(store);
        }
    </script>

    @stack('scripts')
</body>
</html>