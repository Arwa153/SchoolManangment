@extends('layouts.app')

@section('title', __('messages.choose_role') . ' - ' . __('messages.brand'))

@section('content')
<div class="main-wrapper">
    <div class="container">
        <div class="hero-section">
            <div class="text-center">
                <h1 class="hero-title">{{ __('messages.choose_role') }}</h1>
                <p class="hero-subtitle">{{ __('messages.select_role_desc') }}</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Manager Card -->
            <div class="col-lg-4 col-md-6">
                <div class="role-card manager h-100">
                    <div class="role-icon manager">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 class="role-title">{{ __('messages.school_manager') }}</h3>
                    <p class="role-description">
                        {{ __('messages.manager_desc') }}
                    </p>
                    <div class="role-features">
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.create_manage_classes') }}</span>
                        </div>
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.add_teachers_students') }}</span>
                        </div>
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.assign_students_classes') }}</span>
                        </div>
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.view_system_reports') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('register.manager') }}" class="role-btn manager">
                        <i class="fas fa-user-tie me-2"></i>
                        {{ __('messages.register_as_manager') }}
                    </a>
                </div>
            </div>

            <!-- Teacher Card -->
            <div class="col-lg-4 col-md-6">
                <div class="role-card teacher h-100">
                    <div class="role-icon teacher">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="role-title">{{ __('messages.teacher') }}</h3>
                    <p class="role-description">
                        {{ __('messages.teacher_desc') }}
                    </p>
                    <div class="role-features">
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.view_assigned_classes') }}</span>
                        </div>
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.manage_grades') }}</span>
                        </div>
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.record_behavior') }}</span>
                        </div>
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.track_performance') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('register.teacher') }}" class="role-btn teacher">
                        <i class="fas fa-graduation-cap me-2"></i>
                        {{ __('messages.register_as_teacher') }}
                    </a>
                </div>
            </div>

            <!-- Parent Card -->
            <div class="col-lg-4 col-md-6">
                <div class="role-card parent h-100">
                    <div class="role-icon parent">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="role-title">{{ __('messages.parent') }}</h3>
                    <p class="role-description">
                        {{ __('messages.parent_desc') }}
                    </p>
                    <div class="role-features">
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.view_child_grades') }}</span>
                        </div>
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.monitor_behavior') }}</span>
                        </div>
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.track_progress') }}</span>
                        </div>
                        <div class="role-feature">
                            <div class="role-feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ __('messages.stay_informed') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('register.parent') }}" class="role-btn parent">
                        <i class="fas fa-child me-2"></i>
                        {{ __('messages.register_as_parent') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <div class="cta-section">
                <div class="cta-content">
                    <p class="mb-0" style="font-size: 1.1rem;">
                        {{ __('messages.already_account') }}
                        <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: white;">{{ __('messages.login_here') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection