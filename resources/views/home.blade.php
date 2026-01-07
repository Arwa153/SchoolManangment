@extends('layouts.app')

@section('title', __('messages.brand') . ' - ' . __('messages.hero_title'))

@section('content')
<div class="main-wrapper">
    <div class="container">
        <!-- Hero Section -->
        <div class="hero-section" id="home">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1 class="hero-title">{{ __('messages.hero_title') }}</h1>
                        <p class="hero-subtitle">
                            {{ __('messages.hero_subtitle') }}
                        </p>
                        <a href="{{ route('role.selection') }}" class="btn-hero">
                            {{ __('messages.get_started') }}
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="character-illustration"></div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="section-title" id="features">{{ __('messages.features_title') }}</div>
        
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon orange">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="feature-title">{{ __('messages.complete_management') }}</div>
                <div class="feature-description">
                    {{ __('messages.complete_management_desc') }}
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon blue">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="feature-title">{{ __('messages.progress_tracking') }}</div>
                <div class="feature-description">
                    {{ __('messages.progress_tracking_desc') }}
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon green">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="feature-title">{{ __('messages.behavior_support') }}</div>
                <div class="feature-description">
                    {{ __('messages.behavior_support_desc') }}
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon purple">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="feature-title">{{ __('messages.smart_scheduling') }}</div>
                <div class="feature-description">
                    {{ __('messages.smart_scheduling_desc') }}
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="cta-section" id="contact">
            <div class="cta-content">
                <div class="row text-center mb-4">
                    <div class="col-md-3">
                        <h3 class="display-4 fw-bold">2,500+</h3>
                        <p class="mb-0">{{ __('messages.happy_students') }}</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="display-4 fw-bold">100+</h3>
                        <p class="mb-0">{{ __('messages.expert_teachers') }}</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="display-4 fw-bold">6,500+</h3>
                        <p class="mb-0">{{ __('messages.successful_graduates') }}</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="display-4 fw-bold">15+</h3>
                        <p class="mb-0">{{ __('messages.years_experience') }}</p>
                    </div>
                </div>
                <h2 class="cta-title">{{ __('messages.ready_to_start') }}</h2>
                <p class="cta-subtitle">
                    {{ __('messages.join_families') }}
                </p>
                <a href="{{ route('role.selection') }}" class="btn-hero">{{ __('messages.start_journey') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection