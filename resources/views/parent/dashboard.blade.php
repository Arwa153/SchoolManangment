@extends('layouts.dashboard')

@section('title', __('messages.dashboard') . ' - ' . __('messages.parent'))

@section('sidebar')
<a href="{{ route('parent.dashboard') }}" class="nav-link active">
    <i class="fas fa-tachometer-alt me-2"></i>
    {{ __('messages.dashboard') }}
</a>
<a href="{{ route('parent.grades') }}" class="nav-link">
    <i class="fas fa-star me-2"></i>
    {{ __('messages.grades') }}
</a>
<a href="{{ route('parent.behaviors') }}" class="nav-link">
    <i class="fas fa-clipboard-list me-2"></i>
    {{ __('messages.behavior_reports') }}
</a>
@endsection

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ __('messages.welcome_back') }}, {{ auth()->user()->name }}! 👋</h1>
            <p class="page-subtitle">{{ __('messages.parent') }} • {{ now()->format('F j, Y') }} - {{ now()->format('l') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('parent.grades') }}" class="btn btn-outline-primary">
                <i class="fas fa-star me-2"></i>
                {{ __('messages.view') }} {{ __('messages.grades') }}
            </a>
            <a href="{{ route('parent.behaviors') }}" class="btn btn-outline-success">
                <i class="fas fa-clipboard-list me-2"></i>
                {{ __('messages.behavior_reports') }}
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_children'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.my_children') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-child fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_grades'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.total_grades') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['positive_behaviors'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.positive_reports') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-thumbs-up fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['negative_behaviors'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.areas_improve') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Children Overview -->
<div class="row g-4">
    @forelse($children as $child)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate me-2"></i>
                        {{ $child->name }}
                    </h5>
                    <a href="{{ route('parent.child.profile', $child->id) }}" class="btn btn-sm btn-outline-primary">
                        {{ __('messages.view') }} Profile
                    </a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">{{ __('messages.student_code') }}</h6>
                                <code class="fs-6">{{ $child->student_code }}</code>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">{{ __('messages.class') }}</h6>
                                @if($child->schoolClass)
                                    <span class="badge bg-info fs-6">{{ $child->schoolClass->name }}</span>
                                @else
                                    <span class="text-muted">{{ __('messages.no_data') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Recent Performance -->
                    <div class="row g-3">
                        <div class="col-4 text-center">
                            <h6 class="text-muted mb-1">{{ __('messages.recent_grades') }}</h6>
                            <h4 class="mb-0">{{ $child->grades->count() }}</h4>
                        </div>
                        <div class="col-4 text-center">
                            <h6 class="text-success mb-1">{{ __('messages.positive') }}</h6>
                            <h4 class="mb-0 text-success">{{ $child->behaviorRecords->where('type', 'positive')->count() }}</h4>
                        </div>
                        <div class="col-4 text-center">
                            <h6 class="text-warning mb-1">{{ __('messages.areas_improve') }}</h6>
                            <h4 class="mb-0 text-warning">{{ $child->behaviorRecords->where('type', 'negative')->count() }}</h4>
                        </div>
                    </div>

                    @if($child->grades->count() > 0)
                        <hr>
                        <div>
                            <h6 class="text-muted mb-2">{{ __('messages.recent_grades') }}</h6>
                            @foreach($child->grades->take(3) as $grade)
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small>{{ $grade->subject }}</small>
                                    <span class="badge bg-{{ $grade->grade >= 70 ? 'success' : ($grade->grade >= 50 ? 'warning' : 'danger') }}">
                                        {{ $grade->grade }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-child fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">{{ __('messages.my_children') }} {{ __('messages.no_data') }}</h4>
                    <p class="text-muted">It looks like no students are linked to your account yet.</p>
                    <p class="text-muted">Please contact the school administration if this seems incorrect.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($children->count() > 0)
    <!-- Quick Actions -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('parent.grades') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-star me-2"></i>
                                {{ __('messages.view') }} {{ __('messages.grades') }}
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('parent.behaviors') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-clipboard-list me-2"></i>
                                {{ __('messages.behavior_reports') }}
                            </a>
                        </div>
                        <div class="col-md-4">
                            @if($children->first())
                                <a href="{{ route('parent.child.profile', $children->first()->id) }}" class="btn btn-outline-info w-100 py-3">
                                    <i class="fas fa-user me-2"></i>
                                    Child Profile
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection