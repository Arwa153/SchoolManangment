<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('languages.supported')[app()->getLocale()]['direction'] ?? 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.dashboard') . ' - ' . __('messages.brand'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if(app()->getLocale() === 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @endif
    <style>
        :root {
            /* Cool Color Palette */
            --primary-blue: #4A90E2;
            --primary-teal: #50E3C2;
            --primary-purple: #9013FE;
            --primary-green: #4CAF50;
            --primary-indigo: #3F51B5;
            
            /* Derived Cool Colors */
            --primary-blue-light: #6BA3E8;
            --primary-blue-dark: #357ABD;
            --primary-teal-light: #70E8CE;
            --primary-teal-dark: #3DDAB7;
            --primary-purple-light: #A855F7;
            --primary-purple-dark: #7C3AED;
            --primary-green-light: #66BB6A;
            --primary-green-dark: #388E3C;
            --primary-indigo-light: #5C6BC0;
            --primary-indigo-dark: #303F9F;
            
            /* Background Colors */
            --neutral-bg: #F8FAFC;
            --card-bg: #FFFFFF;
            --bg-white: #FFFFFF;
            --bg-light: #F1F5F9;
            --bg-cool: #E0F2FE;
            --bg-teal-light: #F0FDFA;
            --bg-purple-light: #FAF5FF;
            
            /* Text Colors */
            --text-dark: #1E293B;
            --text-primary: #334155;
            --text-muted: #64748B;
            --text-light: #94A3B8;
            --text-white: #FFFFFF;
            
            /* Border Colors */
            --border-soft: #E2E8F0;
            --border-gray: #CBD5E1;
            --border-light: #F1F5F9;
            --border-primary: var(--primary-blue);
            --border-teal: var(--primary-teal);
            
            /* Interactive Colors */
            --primary-hover: var(--primary-blue-dark);
            --secondary-hover: var(--primary-teal-dark);
            --accent-hover: var(--primary-purple-dark);
            --success-hover: var(--primary-green-dark);
            
            /* Shadows */
            --shadow-soft: 0 8px 32px rgba(74, 144, 226, 0.12);
            --shadow-card: 0 4px 20px rgba(74, 144, 226, 0.08);
            --shadow-hover: 0 12px 40px rgba(74, 144, 226, 0.15);
            --shadow-xl: 0 20px 60px rgba(74, 144, 226, 0.20);
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-indigo) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--primary-teal) 0%, var(--primary-green) 100%);
            --gradient-accent: linear-gradient(135deg, var(--primary-purple) 0%, var(--primary-indigo) 100%);
            --gradient-soft: linear-gradient(135deg, var(--bg-cool) 0%, var(--bg-teal-light) 100%);
            
            /* Status Colors */
            --success-color: var(--primary-green);
            --success-light: #E8F5E8;
            --warning-color: #FF9800;
            --warning-light: #FFF3E0;
            --danger-color: #F44336;
            --danger-light: #FFEBEE;
            --info-color: var(--primary-blue);
            --info-light: var(--bg-cool);
        }

        * {
            font-family: @if(app()->getLocale() === 'ar') 'Cairo', @endif 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: var(--neutral-bg);
            min-height: 100vh;
            color: var(--text-dark);
            font-size: 16px;
        }

        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }
        }

        @if(app()->getLocale() === 'ar')
        body {
            direction: rtl;
        }
        
        .sidebar {
            left: auto;
            right: 0;
        }
        
        .main-container {
            margin-left: 0;
            margin-right: 280px;
            border-radius: 0 32px 0 0;
        }

        @media (max-width: 768px) {
            .main-container {
                margin-right: 0;
                border-radius: 0;
            }
        }
        
        .nav-link {
            text-align: right;
        }
        
        .sidebar-brand {
            flex-direction: row-reverse;
        }
        
        .sidebar-brand i {
            margin-right: 0;
            margin-left: 0.75rem;
        }
        
        .nav-link i {
            margin-right: 0;
            margin-left: 0.75rem;
        }
        
        .nav-link:hover {
            transform: translateX(-6px);
        }
        @endif

        .main-container {
            background: var(--card-bg);
            min-height: 100vh;
            border-radius: 32px 0 0 0;
            margin-left: 280px;
            box-shadow: var(--shadow-soft);
        }

        @media (max-width: 768px) {
            .main-container {
                margin-left: 0;
                border-radius: 0;
            }
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: var(--gradient-primary);
            padding: 2rem 1.5rem;
            z-index: 1000;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }

        .sidebar-brand {
            color: var(--text-white);
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .sidebar-brand:hover {
            color: var(--text-white);
        }

        .sidebar-brand i {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.75rem;
            border-radius: 16px;
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 1rem 1.25rem;
            border-radius: 16px;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: var(--text-white) !important;
            transform: translateX(6px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
            color: var(--text-white) !important;
            box-shadow: var(--shadow-card);
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .user-info {
            position: absolute;
            bottom: 2rem;
            left: 1.5rem;
            right: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            backdrop-filter: blur(10px);
        }

        .user-info .user-name {
            color: var(--text-white);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .user-info .user-role {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: var(--text-white);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
            color: var(--text-white);
            transform: translateY(-1px);
        }

        .content-wrapper {
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
        }

        .card {
            border: none;
            border-radius: 24px;
            box-shadow: var(--shadow-card);
            background: var(--card-bg);
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-light);
            padding: 2rem;
            border-radius: 24px 24px 0 0 !important;
        }

        .card-body {
            padding: 2rem;
        }

        .stats-card {
            background: var(--primary-blue);
            color: var(--text-white);
            border-radius: 24px;
            border: none;
            overflow: hidden;
            position: relative;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -30%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .stats-card-2 {
            background: var(--primary-teal);
            color: var(--text-white);
            border-radius: 24px;
            border: none;
            overflow: hidden;
            position: relative;
        }

        .stats-card-2::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -30%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .stats-card-3 {
            background: var(--primary-purple);
            color: var(--text-white);
            border-radius: 24px;
            border: none;
            overflow: hidden;
            position: relative;
        }

        .stats-card-3::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -30%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .stats-card-4 {
            background: var(--primary-green);
            color: var(--text-white);
            border-radius: 24px;
            border: none;
            overflow: hidden;
            position: relative;
        }

        .stats-card-4::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -30%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .stats-card .card-body {
            position: relative;
            z-index: 1;
        }

        .btn-primary {
            background: var(--primary-blue);
            border: none;
            border-radius: 16px;
            padding: 0.875rem 1.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: var(--text-white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background: var(--primary-hover);
            color: var(--text-white);
        }

        .btn-secondary {
            background: var(--primary-teal);
            border: none;
            border-radius: 16px;
            padding: 0.875rem 1.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: var(--text-white);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background: var(--secondary-hover);
            color: var(--text-white);
        }

        .btn-success {
            background: var(--success-color);
            border: none;
            border-radius: 16px;
            padding: 0.875rem 1.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: var(--text-white);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background: var(--success-hover);
            color: var(--text-white);
        }

        .btn-warning {
            background: var(--warning-color);
            border: none;
            border-radius: 16px;
            padding: 0.875rem 1.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: var(--text-white);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background: #F57C00;
            color: var(--text-white);
        }

        .btn-danger {
            background: var(--danger-color);
            border: none;
            border-radius: 16px;
            padding: 0.875rem 1.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: var(--text-white);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background: #D32F2F;
            color: var(--text-white);
        }

        .btn-info {
            background: var(--info-color);
            border: none;
            border-radius: 16px;
            padding: 0.875rem 1.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: var(--text-white);
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background: var(--primary-hover);
            color: var(--text-white);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            border-radius: 16px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: var(--text-white);
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            border: 2px solid var(--primary-teal);
            color: var(--primary-teal);
            border-radius: 16px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: var(--primary-teal);
            border-color: var(--primary-teal);
            color: var(--text-white);
            transform: translateY(-1px);
        }

        .btn-outline-success {
            border: 2px solid var(--success-color);
            color: var(--success-color);
            border-radius: 16px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-success:hover {
            background: var(--success-color);
            border-color: var(--success-color);
            color: var(--text-white);
            transform: translateY(-1px);
        }

        .btn-outline-warning {
            border: 2px solid var(--warning-color);
            color: var(--warning-color);
            border-radius: 16px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-warning:hover {
            background: var(--warning-color);
            border-color: var(--warning-color);
            color: var(--text-white);
            transform: translateY(-1px);
        }

        .btn-outline-danger {
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
            border-radius: 16px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-danger:hover {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: var(--text-white);
            transform: translateY(-1px);
        }

        .btn-outline-info {
            border: 2px solid var(--info-color);
            color: var(--info-color);
            border-radius: 16px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-info:hover {
            background: var(--info-color);
            border-color: var(--info-color);
            color: var(--text-white);
            transform: translateY(-1px);
        }

        .table {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-card);
            background: var(--bg-white);
        }

        .table thead th {
            background: var(--primary-blue);
            border: none;
            font-weight: 600;
            color: var(--text-white);
            padding: 1.25rem;
        }

        .table tbody td {
            border: none;
            padding: 1.25rem;
            vertical-align: middle;
            background: var(--bg-white);
        }

        .table tbody tr {
            border-bottom: 1px solid var(--border-gray);
        }

        .table tbody tr:nth-child(even) {
            background: var(--bg-cool);
        }

        .table tbody tr:hover {
            background: var(--bg-teal-light);
        }

        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge.bg-primary {
            background-color: var(--primary-blue) !important;
            color: var(--text-white);
        }

        .badge.bg-secondary {
            background-color: var(--primary-teal) !important;
            color: var(--text-white);
        }

        .badge.bg-success {
            background-color: var(--success-color) !important;
            color: var(--text-white);
        }

        .badge.bg-warning {
            background-color: var(--warning-color) !important;
            color: var(--text-white);
        }

        .badge.bg-danger {
            background-color: var(--danger-color) !important;
            color: var(--text-white);
        }

        .badge.bg-info {
            background-color: var(--info-color) !important;
            color: var(--text-white);
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            background: var(--primary-teal);
            color: var(--text-white);
        }

        .avatar-xl {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 600;
            background: var(--primary-purple);
            color: var(--text-white);
        }

        .alert {
            border: none;
            border-radius: 20px;
            padding: 1.25rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: var(--success-light);
            color: #065F46;
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: var(--danger-light);
            color: #991B1B;
            border-left: 4px solid var(--danger-color);
        }

        .alert-warning {
            background: var(--warning-light);
            color: #92400E;
            border-left: 4px solid var(--warning-color);
        }

        .alert-info {
            background: var(--info-light);
            color: var(--text-primary);
            border-left: 4px solid var(--info-color);
        }

        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-light);
            padding: 1.5rem;
            border-radius: 20px 20px 0 0;
            background: var(--bg-light);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-light);
            padding: 1.5rem;
            border-radius: 0 0 20px 20px;
            background: var(--bg-light);
        }

        .form-control {
            border: 2px solid var(--border-gray);
            border-radius: 16px;
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
            background: var(--bg-white);
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
            background: var(--bg-white);
            color: var(--text-dark);
        }

        .form-select {
            border: 2px solid var(--border-gray);
            border-radius: 16px;
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
            background: var(--bg-white);
            color: var(--text-dark);
        }

        .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
            background: var(--bg-white);
            color: var(--text-dark);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background: var(--bg-light);
            border: 2px solid var(--border-gray);
            color: var(--text-primary);
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: var(--border-light);
            border-radius: 2px;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: -4px;
            top: 1rem;
            width: 11px;
            height: 11px;
            background: var(--primary-teal);
            border-radius: 50%;
        }

        /* Links */
        a {
            color: var(--primary-blue);
            text-decoration: none;
        }

        a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* Text colors */
        .text-primary {
            color: var(--primary-blue) !important;
        }

        .text-secondary {
            color: var(--primary-teal) !important;
        }

        .text-success {
            color: var(--success-color) !important;
        }

        .text-warning {
            color: var(--warning-color) !important;
        }

        .text-danger {
            color: var(--danger-color) !important;
        }

        .text-info {
            color: var(--info-color) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        /* Background colors */
        .bg-primary {
            background-color: var(--primary-blue) !important;
            color: var(--text-white) !important;
        }

        .bg-secondary {
            background-color: var(--primary-teal) !important;
            color: var(--text-white) !important;
        }

        .bg-light {
            background-color: var(--bg-light) !important;
            color: var(--text-primary) !important;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-container {
                margin-left: 0;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="#" class="sidebar-brand">
            <i class="fas fa-graduation-cap"></i>
            {{ __('messages.brand') }}
        </a>
        
        <div class="nav flex-column">
            @yield('sidebar')
        </div>

        <div class="user-info">
            <!-- Language Selector -->
            <div class="mb-3">
                <div class="dropdown">
                    <button class="btn btn-logout dropdown-toggle w-100 mb-2" type="button" data-bs-toggle="dropdown">
                        {{ config('languages.supported')[app()->getLocale()]['flag'] }}
                        {{ config('languages.supported')[app()->getLocale()]['name'] }}
                    </button>
                    <ul class="dropdown-menu w-100">
                        @foreach(config('languages.supported') as $code => $language)
                            <li>
                                <form method="POST" action="{{ route('language.switch') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="language" value="{{ $code }}">
                                    <button type="submit" class="dropdown-item {{ app()->getLocale() === $code ? 'active' : '' }}">
                                        {{ $language['flag'] }} {{ $language['name'] }}
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="user-name">
                <i class="fas fa-user me-2"></i>
                {{ auth()->user()->name }}
            </div>
            <div class="user-role">
                <i class="fas fa-tag me-2"></i>
                {{ ucfirst(auth()->user()->role) }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-logout w-100">
                    <i class="fas fa-sign-out-alt me-1"></i>
                    {{ __('messages.logout') }}
                </button>
            </form>
        </div>
    </div>

    <div class="main-container">
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>