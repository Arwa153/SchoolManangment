@extends('layouts.app')

@section('title', __('messages.teacher') . ' ' . __('messages.register') . ' - ' . __('messages.brand'))

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: var(--gradient-primary);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0" style="border-radius: 32px; box-shadow: var(--shadow-soft);">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                                 style="width: 80px; height: 80px; background: var(--gradient-secondary);">
                                <i class="fas fa-chalkboard-teacher text-white" style="font-size: 2.5rem;"></i>
                            </div>
                            <h2 class="mt-3 mb-2" style="color: var(--text-primary); font-weight: 700;">{{ __('messages.teacher') }} {{ __('messages.register') }}</h2>
                            <p class="text-muted">{{ __('messages.create_teacher_account') }}</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.teacher') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label" style="color: var(--text-primary); font-weight: 600;">{{ __('messages.name') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: var(--bg-cool); border: 2px solid var(--primary-blue); border-right: none; border-radius: 16px 0 0 16px;">
                                        <i class="fas fa-user" style="color: var(--primary-blue);"></i>
                                    </span>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name') }}" required autofocus style="border: 2px solid var(--primary-blue); border-left: none; border-radius: 0 16px 16px 0;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label" style="color: var(--text-primary); font-weight: 600;">{{ __('messages.email') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: var(--bg-cool); border: 2px solid var(--primary-blue); border-right: none; border-radius: 16px 0 0 16px;">
                                        <i class="fas fa-envelope" style="color: var(--primary-blue);"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email') }}" required style="border: 2px solid var(--primary-blue); border-left: none; border-radius: 0 16px 16px 0;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label" style="color: var(--text-primary); font-weight: 600;">{{ __('messages.subject') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: var(--bg-cool); border: 2px solid var(--primary-blue); border-right: none; border-radius: 16px 0 0 16px;">
                                        <i class="fas fa-book" style="color: var(--primary-blue);"></i>
                                    </span>
                                    <input type="text" class="form-control" id="subject" name="subject" 
                                           value="{{ old('subject') }}" placeholder="{{ __('messages.subject_placeholder') }}" required style="border: 2px solid var(--primary-blue); border-left: none; border-radius: 0 16px 16px 0;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label" style="color: var(--text-primary); font-weight: 600;">{{ __('messages.password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: var(--bg-cool); border: 2px solid var(--primary-blue); border-right: none; border-radius: 16px 0 0 16px;">
                                        <i class="fas fa-lock" style="color: var(--primary-blue);"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" required style="border: 2px solid var(--primary-blue); border-left: none; border-radius: 0 16px 16px 0;">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label" style="color: var(--text-primary); font-weight: 600;">{{ __('messages.confirm_password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: var(--bg-cool); border: 2px solid var(--primary-blue); border-right: none; border-radius: 16px 0 0 16px;">
                                        <i class="fas fa-lock" style="color: var(--primary-blue);"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password_confirmation" 
                                           name="password_confirmation" required style="border: 2px solid var(--primary-blue); border-left: none; border-radius: 0 16px 16px 0;">
                                </div>
                            </div>

                            <button type="submit" class="btn w-100 py-3 mb-3" style="background: var(--primary-blue); border: none; border-radius: 16px; color: white; font-weight: 600; transition: all 0.3s ease;">
                                <i class="fas fa-user-plus me-2"></i>
                                {{ __('messages.create') }} {{ __('messages.teacher') }} {{ __('messages.account') }}
                            </button>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                {{ __('messages.already_account') }}
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--primary-blue);">
                                    {{ __('messages.login_here') }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('role.selection') }}" class="text-white text-decoration-none" style="font-weight: 500;">
                        <i class="fas fa-arrow-left me-2"></i>
                        {{ __('messages.back_to_role_selection') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection