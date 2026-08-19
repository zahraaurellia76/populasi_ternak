@extends('layouts.app')

@section('title', 'Login Petugas - Simnak Kediri')

@section('content')
<style>
    /* Card Container Centering */
    .login-container {
        min-height: 75vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-login {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        max-width: 420px;
        width: 100%;
    }

    /* Card Header Matching Main Theme Gradient */
    .card-login-header {
        background: linear-gradient(to right, #164e3b 0%, #226040 50%, #2d7a4c 100%);
        color: #ffffff;
        padding: 1.5rem;
        text-align: center;
    }

    /* Form Labels & Focus States */
    .form-label-login {
        color: #164e3b;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 0.4rem;
    }

    .form-control-login {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.95rem;
        transition: all 0.2s ease-in-out;
    }

    .form-control-login:focus {
        border-color: #2d7a4c !important;
        box-shadow: 0 0 0 0.25rem rgba(45, 122, 76, 0.15) !important;
        background-color: #ffffff;
    }

    /* Submit Button with Gradient & Smooth Hover */
    .btn-login-custom {
        background: linear-gradient(to right, #164e3b 0%, #2d7a4c 100%);
        color: #ffffff;
        border: none;
        font-weight: 700;
        padding: 10px 16px;
        border-radius: 8px;
        transition: all 0.3s ease-in-out;
    }

    .btn-login-custom:hover {
        background: linear-gradient(to right, #226040 0%, #38945c 100%);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 78, 59, 0.35) !important;
    }
</style>

<div class="container login-container py-4">
    <div class="card card-login bg-white">
        <!-- Header Card -->
        <div class="card-login-header">
            <h5 class="fw-bold mb-0">
                <i class="fa-solid fa-lock me-2 text-warning"></i>Login Petugas
            </h5>
        </div>

        <!-- Body Form -->
        <div class="card-body p-4">
            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label form-label-login">
                        <i class="fa-solid fa-user me-1"></i>Username
                    </label>
                    <input type="text" name="username" id="username" class="form-control form-control-login @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
                    @error('username')
                        <div class="invalid-feedback small">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label form-label-login">
                        <i class="fa-solid fa-key me-1"></i>Password
                    </label>
                    <input type="password" name="password" id="password" class="form-control form-control-login @error('password') is-invalid @enderror" placeholder="Masukkan password" required>
                    @error('password')
                        <div class="invalid-feedback small">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login-custom w-100 shadow-sm">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk
                </button>
            </form>
        </div>
    </div>
</div>
@endsection