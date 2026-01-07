<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('languages.supported')[app()->getLocale()]['direction'] ?? 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.brand') . ' - School Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            font-family: @if(app()->getLocale() === 'ar') 'Cairo', @endif 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: var(--neutral-bg);
            color: var(--text-dark);
            line-height: 1.7;
            font-weight: 400;
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
            text-align: right;
        }
        
        .hero-content {
            text-align: right;
        }
        
        .feature-card, .role-card {
            text-align: right;
        }
        
        .nav-link {
            text-align: right;
        }
        @endif

        .main-wrapper {
            background: var(--neutral-bg);
            min-height: 100vh;
            padding: 1rem;
        }

        @media (min-width: 768px) {
            .main-wrapper {
                padding: 2rem;
            }
        }
        .hero-section {
            background: var(--gradient-primary);
            border-radius: 32px;
            padding: 2rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
            color: var(--text-white);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .btn-hero {
            background: var(--text-white);
            color: var(--primary-blue);
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-card);
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
            color: var(--primary-blue);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
        }

        .feature-card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-card);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: var(--text-white);
        }

        .feature-icon-1 {
            background: var(--primary-blue);
        }

        .feature-icon-2 {
            background: var(--primary-teal);
        }

        .feature-icon-3 {
            background: var(--primary-purple);
            color: var(--text-white);
        }

        .feature-icon-4 {
            background: var(--gradient-secondary);
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .feature-description {
            color: var(--text-muted);
            line-height: 1.6;
        }
            overflow: hidden;
            color: white;
        }

        @media (min-width: 768px) {
            .hero-section {
                padding: 4rem;
                margin-bottom: 4rem;
            }
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
        }

        @media (min-width: 768px) {
            .hero-section::before {
                width: 300px;
                height: 300px;
            }
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
        }

        @media (min-width: 768px) {
            .hero-section::after {
                width: 200px;
                height: 200px;
            }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        @media (min-width: 768px) {
            .hero-title {
                font-size: 3rem;
            }
        }

        .hero-subtitle {
            font-size: 1rem;
            opacity: 0.95;
            margin-bottom: 2.5rem;
            line-height: 1.7;
            max-width: 500px;
        }

        @media (min-width: 768px) {
            .hero-subtitle {
                font-size: 1.2rem;
            }
        }

        .btn-hero {
            background: white;
            color: var(--primary-blue);
            border: none;
            padding: 1rem 2rem;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: var(--shadow-soft);
        }

        @media (min-width: 768px) {
            .btn-hero {
                padding: 1.2rem 2.5rem;
                font-size: 1.1rem;
            }
        }

        .btn-hero:hover {
            background: var(--bg-cool);
            color: var(--primary-purple);
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .section-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2rem;
        }

        @media (min-width: 768px) {
            .section-title {
                font-size: 2rem;
                margin-bottom: 3rem;
            }
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 768px) {
            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
                margin-bottom: 3rem;
            }
        }

        @media (min-width: 1024px) {
            .feature-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            }
        }

        .feature-card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: var(--shadow-card);
            transition: all 0.3s ease;
            border: 2px solid var(--primary-blue);
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .feature-card {
                padding: 2.5rem;
            }
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-purple);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 768px) {
            .feature-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }
        }

        .feature-icon.purple {
            background: var(--gradient-primary);
            color: white;
        }

        .feature-icon.blue {
            background: var(--gradient-secondary);
            color: white;
        }

        .feature-icon.green {
            background: var(--primary-green);
            color: white;
        }

        .feature-icon.orange {
            background: var(--primary-teal);
            color: white;
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .feature-description {
            color: var(--text-muted);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .role-card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-card);
            transition: all 0.3s ease;
            border: 3px solid var(--primary-blue);
            height: 100%;
        }

        @media (min-width: 768px) {
            .role-card {
                padding: 2rem;
            }
        }

        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-purple);
        }

        .role-card.manager {
            border-color: var(--primary-purple);
        }

        .role-card.teacher {
            border-color: var(--primary-blue);
        }

        .role-card.parent {
            border-color: var(--primary-teal);
        }

        .role-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1.5rem;
        }

        @media (min-width: 768px) {
            .role-icon {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
        }

        .role-icon.manager {
            background: var(--gradient-accent);
            color: white;
        }

        .role-icon.teacher {
            background: var(--gradient-primary);
            color: white;
        }

        .role-icon.parent {
            background: var(--gradient-secondary);
            color: white;
        }

        .role-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        @media (min-width: 768px) {
            .role-title {
                font-size: 1.5rem;
            }
        }

        .role-description {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            line-height: 1.6;
            font-size: 0.9rem;
        }

        @media (min-width: 768px) {
            .role-description {
                font-size: 1rem;
            }
        }

        .role-features {
            text-align: left;
            margin-bottom: 2rem;
        }

        .role-feature {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.85rem;
        }

        @media (min-width: 768px) {
            .role-feature {
                font-size: 0.9rem;
            }
        }

        .role-feature-icon {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--primary-green);
            color: var(--primary-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-size: 0.6rem;
        }

        @media (min-width: 768px) {
            .role-feature-icon {
                width: 20px;
                height: 20px;
                font-size: 0.7rem;
            }
        }

        .role-btn {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: 16px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        @media (min-width: 768px) {
            .role-btn {
                padding: 1rem;
                font-size: 1rem;
            }
        }

        .role-btn.manager {
            background: var(--gradient-accent);
            color: white;
        }

        .role-btn.teacher {
            background: var(--gradient-primary);
            color: white;
        }

        .role-btn.parent {
            background: var(--gradient-secondary);
            color: white;
        }

        .role-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-card);
            color: white;
        }

        .role-btn.parent:hover {
            background: var(--primary-teal-dark);
            color: white;
        }

        .cta-section {
            background: var(--gradient-primary);
            border-radius: 24px;
            padding: 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .cta-section {
                padding: 3rem;
            }
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        @media (min-width: 768px) {
            .cta-title {
                font-size: 2rem;
            }
        }

        .cta-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        @media (min-width: 768px) {
            .cta-subtitle {
                font-size: 1.1rem;
            }
        }

        /* Navigation Responsive */
        .navbar-nav {
            flex-direction: column;
            gap: 0.5rem;
        }

        @media (min-width: 992px) {
            .navbar-nav {
                flex-direction: row;
                gap: 1rem;
            }
        }

        .navbar-brand {
            font-size: 1.3rem !important;
        }

        @media (min-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem !important;
            }
        }

        /* Mobile Responsive Adjustments */
        @media (max-width: 767px) {
            .hero-section {
                text-align: center;
            }

            .feature-card {
                text-align: center;
            }

            .role-features {
                text-align: center;
            }

            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        .role-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Language Selector & Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm position-fixed w-100" style="top: 0; z-index: 1050;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}" style="color: var(--primary-blue); font-size: 1.5rem;">
                <i class="fas fa-graduation-cap me-2"></i>
                {{ __('messages.brand') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">{{ __('messages.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">{{ __('messages.about') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">{{ __('messages.contact') }}</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Language Selector -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            {{ config('languages.supported')[app()->getLocale()]['flag'] }}
                            {{ config('languages.supported')[app()->getLocale()]['name'] }}
                        </button>
                        <ul class="dropdown-menu">
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
                    
                    @auth
                        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-outline-primary">
                            {{ __('messages.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">{{ __('messages.login') }}</a>
                        <a href="{{ route('role.selection') }}" class="btn btn-primary">{{ __('messages.register') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content with top padding for fixed navbar -->
    <div style="padding-top: 80px;">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>