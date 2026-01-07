@extends('layouts.app')

@section('title', __('messages.login') . ' - ' . __('messages.brand'))

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
                                <i class="fas fa-graduation-cap text-white" style="font-size: 2.5rem;"></i>
                            </div>
                            <h2 class="mt-3 mb-2" style="color: var(--text-primary); font-weight: 700;">{{ __('messages.welcome_back') }}</h2>
                            <p class="text-muted">{{ __('messages.login_desc') }}</p>
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

                        <!-- Login Type Selector -->
                        <div class="mb-4">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="loginType" id="staffLogin" value="staff" checked>
                                <label class="btn btn-outline-primary" for="staffLogin" style="border-radius: 16px 0 0 16px; border-color: var(--primary-blue); color: var(--primary-blue);">
                                    <i class="fas fa-user-tie me-2"></i>{{ __('messages.staff_login') }}
                                </label>
                                
                                <input type="radio" class="btn-check" name="loginType" id="parentLogin" value="parent">
                                <label class="btn btn-outline-primary" for="parentLogin" style="border-radius: 0 16px 16px 0; border-color: var(--primary-teal); color: var(--primary-teal);">
                                    <i class="fas fa-heart me-2"></i>{{ __('messages.parent_login') }}
                                </label>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <!-- Staff Login Fields -->
                            <div id="staffLoginFields">
                                <div class="mb-3">
                                    <label for="email" class="form-label" style="color: var(--text-primary); font-weight: 600;">{{ __('messages.email') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: var(--bg-cool); border: 2px solid var(--primary-blue); border-right: none; border-radius: 16px 0 0 16px;">
                                            <i class="fas fa-envelope" style="color: var(--primary-blue);"></i>
                                        </span>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ old('email') }}" style="border: 2px solid var(--primary-blue); border-left: none; border-radius: 0 16px 16px 0;">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label" style="color: var(--text-primary); font-weight: 600;">{{ __('messages.password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: var(--bg-cool); border: 2px solid var(--primary-blue); border-right: none; border-radius: 16px 0 0 16px;">
                                            <i class="fas fa-lock" style="color: var(--primary-blue);"></i>
                                        </span>
                                        <input type="password" class="form-control" id="password" name="password" style="border: 2px solid var(--primary-blue); border-left: none; border-radius: 0 16px 16px 0;">
                                    </div>
                                </div>
                            </div>

                            <!-- Parent Login Fields -->
                            <div id="parentLoginFields" style="display: none;">
                                <div class="mb-4">
                                    <label for="student_code" class="form-label" style="color: var(--text-primary); font-weight: 600;">{{ __('messages.student_code') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: var(--bg-teal-light); border: 2px solid var(--primary-teal); border-right: none; border-radius: 16px 0 0 16px;">
                                            <i class="fas fa-id-card" style="color: var(--primary-teal);"></i>
                                        </span>
                                        <input type="text" class="form-control" id="student_code" name="student_code" 
                                               value="{{ old('student_code') }}" placeholder="e.g., STU123ABC"
                                               style="text-transform: uppercase; border: 2px solid var(--primary-teal); border-left: none; border-radius: 0 16px 16px 0;">
                                    </div>
                                    <div class="form-text" style="color: var(--text-muted);">
                                        {{ __('messages.student_code_desc') }}
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn w-100 py-3 mb-3" id="loginButton" 
                                    style="background: var(--primary-blue); border: none; border-radius: 16px; color: white; font-weight: 600; transition: all 0.3s ease;">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                {{ __('messages.login') }}
                            </button>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                {{ __('messages.no_account') }}
                                <a href="{{ route('role.selection') }}" class="text-decoration-none fw-bold" style="color: var(--primary-teal);">
                                    {{ __('messages.register_here') }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="text-white text-decoration-none" style="font-weight: 500;">
                        <i class="fas fa-arrow-left me-2"></i>
                        {{ __('messages.back_to_home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const staffRadio = document.getElementById('staffLogin');
    const parentRadio = document.getElementById('parentLogin');
    const staffFields = document.getElementById('staffLoginFields');
    const parentFields = document.getElementById('parentLoginFields');
    const loginButton = document.getElementById('loginButton');
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    const studentCodeField = document.getElementById('student_code');

    function toggleLoginType() {
        if (parentRadio.checked) {
            staffFields.style.display = 'none';
            parentFields.style.display = 'block';
            loginButton.innerHTML = '<i class="fas fa-heart me-2"></i>' + '{{ __("messages.parent_login") }}';
            loginButton.style.background = 'var(--primary-teal)';
            
            // Clear and disable staff fields
            emailField.value = '';
            passwordField.value = '';
            emailField.removeAttribute('required');
            passwordField.removeAttribute('required');
            
            // Enable student code field
            studentCodeField.setAttribute('required', 'required');
            studentCodeField.focus();
        } else {
            staffFields.style.display = 'block';
            parentFields.style.display = 'none';
            loginButton.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>' + '{{ __("messages.staff_login") }}';
            loginButton.style.background = 'var(--primary-blue)';
            
            // Clear and disable student code field
            studentCodeField.value = '';
            studentCodeField.removeAttribute('required');
            
            // Enable staff fields
            emailField.setAttribute('required', 'required');
            passwordField.setAttribute('required', 'required');
            emailField.focus();
        }
    }

    staffRadio.addEventListener('change', toggleLoginType);
    parentRadio.addEventListener('change', toggleLoginType);

    // Auto-uppercase student code
    studentCodeField.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });

    // Initialize
    toggleLoginType();
});
</script>
@endsection