@extends('layouts.petugas')

@section('content')
<div class="login-page">
    <div class="login-card">
        <div class="logo-wrap">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo KAI">
            <div class="logo-subtitle">Aplikasi Scan</div>
        </div>

        <div id="login-error" class="alert-error" style="display:none;"></div>

        <form id="loginForm">
            <div class="form-group">
                <input type="text" id="username" class="form-control" placeholder="Username" required>
            </div>

            <div class="form-group">
                <input type="password" id="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-login" id="loginButton">
                <span class="btn-text">Login</span>
            </button>
        </form>
    </div>

    <div class="login-footer">Copyright © 2026 - IT DAOP 6</div>
</div>
@endsection

@push('scripts')
<script>
    function setLoginLoading(button, isLoading) {
        if (!button) return;
        if (isLoading) {
            button.classList.add('btn-loading');
            button.disabled = true;
        } else {
            button.classList.remove('btn-loading');
            button.disabled = false;
        }
    }

    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const errorBox = document.getElementById('login-error');
        const loginButton = document.getElementById('loginButton');
        errorBox.style.display = 'none';
        setLoginLoading(loginButton, true);

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('/api/scan-daop6', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    username: username,
                    password: password
                })
            });

            const result = await response.json();

            if (!response.ok) {
                errorBox.style.display = 'block';
                errorBox.innerText = result.message ?? 'Login gagal';
                return;
            }

            localStorage.setItem('petugas', JSON.stringify(result.data));
            const role = String(result.data.role || 'petugas').toLowerCase();
            window.location.href = role === 'teknisi' ? '/teknisi' : '/scanqr';
        } catch (error) {
            errorBox.style.display = 'block';
            errorBox.innerText = 'Tidak bisa terhubung ke server backend';
            console.error(error);
        } finally {
            setLoginLoading(loginButton, false);
        }
    });
</script>
@endpush
