@extends('layouts.app')

@section('title', 'Login Petugas - Simnak Kediri')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white text-center py-3">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-lock me-2"></i>Login Petugas</h5>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger p-2 small mb-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Username</label>
                            <input type="text" name="username" class="form-control border-success" placeholder="Masukkan username" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-success">Password</label>
                            <input type="password" name="password" class="form-control border-success" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" class="btn btn-emerald w-100 fw-bold py-2">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection