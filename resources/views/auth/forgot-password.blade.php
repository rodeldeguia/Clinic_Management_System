@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg">
            <div class="card-header bg-warning text-white text-center">
                <h4><i class="fas fa-key"></i> Reset Password</h4>
                <small>Enter your email to receive reset link</small>
            </div>
            <div class="card-body">
                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-paper-plane"></i> Send Password Reset Link
                    </button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection