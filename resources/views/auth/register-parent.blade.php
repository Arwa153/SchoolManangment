@extends('layouts.app')

@section('title', __('messages.parent') . ' ' . __('messages.access') . ' - ' . __('messages.brand'))

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
                                <i class="fas fa-heart text-white" style="font-size: 2.5rem;"></i>
                            </div>
                            <h2 class="mt-3 mb-2" style="color: var(--text-primary); font-weight: 700;">{{ __('messages.parent') }} {{ __('messages.access') }}</h2>
                            <p class="text-muted">{{ __('messages.parent_access_desc') }}</p>
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

                        <div class="alert" style="background: var(--info-light); border: 1px solid var(--primary-blue); border-radius: 16px; color: var(--text-primary);">
                            <i class="fas fa-info-circle me-2" style="color: var(--primary-blue);"></i>
                            <strong>{{ __('messages.how_to_access') }}:</strong><br>
                            {{ __('messages.parent_access_instructions') }}
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="student_code" class="form-label" style="color: var(--text-primary); font-weight: 600;">{{ __('messages.student_code') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: var(--bg-teal-light); border: 2px solid var(--primary-teal); border-right: none; border-radius: 16px 0 0 16px;">
                                        <i class="fas fa-id-card" style="color: var(--primary-teal);"></i>
                                    </span>
                                    <input type="text" class="form-control" id="student_code" name="student_code" 
                                           value="{{ old('student_code') }}" placeholder="e.g., STU123ABC" 
                                           style="text-transform: uppercase; border: 2px solid var(--primary-teal); border-left: none; border-radius: 0 16px 16px 0;" required autofocus>
                                </div>
                                <div class="form-text" style="color: var(--text-muted);">
                                    {{ __('messages.student_code_exact') }}
                                </div>
                            </div>

                            <button type="submit" class="btn w-100 py-3 mb-3" style="background: var(--primary-teal); border: none; border-radius: 16px; color: white; font-weight: 600; transition: all 0.3s ease;">
                                <i class="fas fa-heart me-2"></i>
                                {{ __('messages.access_parent_dashboard') }}
                            </button>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                {{ __('messages.staff_member') }}
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--primary-blue);">
                                    {{ __('messages.staff_login') }}
                                </a>
                            </p>
                        </div>

                        <hr class="my-4" style="border-color: var(--border-soft);">

                        <div class="alert" style="background: var(--warning-light); border: 1px solid var(--warning-color); border-radius: 16px; color: #92400E;">
                            <i class="fas fa-exclamation-triangle me-2" style="color: var(--warning-color);"></i>
                            <strong>{{ __('messages.no_student_code') }}?</strong><br>
                            {{ __('messages.contact_school_admin') }}
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

<script>
document.getElementById('student_code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>
@endsection