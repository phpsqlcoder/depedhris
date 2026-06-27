<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS Professional Mega Menu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Smooth Professional Typography (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --brand-primary-blue: #0d6efd;
            --brand-dark-blue: #0a58ca;
            --brand-light-blue: #e3f2fd;
            --brand-surface-blue: #f0f7ff;
            --text-muted-light: #b0d4ff;
            --border-color: #e9ecef;
        }

        /* Smooth Typography Override */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #f8f9fa;
        }

        /* 1. Top Utility Header (Refactored to Blue) */
        .top-header {
            background-color: var(--brand-primary-blue);
            color: #ffffff;
            font-size: 0.825rem;
            font-weight: 500;
        }
        
        .top-header a {
            color: #ffffff;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }
        
        .top-header a:hover {
            opacity: 0.85;
        }

        .top-header .context-info {
            color: var(--text-muted-light);
        }

        /* Top Right User Profile Dropdown */
        .top-header .user-dropdown .dropdown-toggle {
            background: rgba(255, 255, 255, 0.15);
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 500;
        }

        .top-header .user-dropdown .dropdown-toggle::after {
            vertical-align: 0.15em;
        }

        .top-header .user-dropdown .dropdown-menu {
            font-size: 0.875rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-top: 5px !important;
        }

        /* 2. Main Navbar Customizations */
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--brand-primary-blue) !important;
        }

        .nav-link {
            font-weight: 500;
            color: #495057 !important;
            padding: 0.6rem 1rem !important;
            border-radius: 6px;
            font-size: 0.925rem;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active, .nav-item.show .nav-link {
            color: var(--brand-primary-blue) !important;
            background-color: var(--brand-surface-blue);
        }

        /* 3. Mega Menu Customizations */
        @media (min-width: 992px) {
            .navbar .has-mega-menu {
                position: static !important;
            }
            .navbar .mega-menu {
                left: 0;
                right: 0;
                width: 100%;
                margin-top: 0;
                border-top: 3px solid var(--brand-primary-blue);
                border-radius: 0 0 12px 12px;
                padding: 1.75rem;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            }
        }

        .mega-menu-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            color: var(--brand-primary-blue);
            padding-bottom: 0.5rem;
            margin-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }

        .mega-menu-link {
            display: flex;
            align-items: center;
            color: #495057;
            text-decoration: none;
            padding: 0.45rem 0.5rem;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }

        .mega-menu-link i {
            font-size: 1.05rem;
            width: 26px;
            color: #9ca3af;
            transition: color 0.15s ease;
        }

        .mega-menu-link:hover {
            background-color: var(--brand-surface-blue);
            color: var(--brand-dark-blue);
            padding-left: 0.75rem;
        }

        .mega-menu-link:hover i {
            color: var(--brand-primary-blue);
        }

        /* 4. Smooth & Minimalist Search Bar */
        .search-container {
            position: relative;
        }

        .search-container .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }

        .search-container .form-control {
            padding-left: 2.3rem;
            padding-right: 1rem;
            height: 38px;
            background-color: #f3f4f6;
            border: 1px solid transparent;
            border-radius: 20px; /* Fully rounded elegant pill shape */
            font-size: 0.875rem;
            font-weight: 400;
            transition: all 0.2s ease-in-out;
        }
        
        .search-container .form-control::placeholder {
            color: #9ca3af;
        }

        /* Soft glowing borderless transition on focus */
        .search-container .form-control:focus {
            background-color: #ffffff;
            border-color: var(--brand-primary-blue);
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12);
            color: #1f2937;
        }

        .search-container .form-control:focus ~ .search-icon {
            color: var(--brand-primary-blue);
        }
    </style>
</head>
<body>

    <!-- 1. TOP BLUE UTILITY HEADER WITH USER MENU -->
    <div class="top-header py-2 d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Context Info -->
            <div class="d-flex gap-4 align-items-center">
                <span><i class="bi bi-building me-2 context-info"></i>Manila Cluster</span>
                <span><i class="bi bi-clock-history me-2 context-info"></i>Next Payroll Run: June 30</span>
            </div>
            <!-- Right Utilities & Profile Dropdown -->
            <div class="d-flex gap-4 align-items-center">
                <div class="d-flex gap-3 align-items-center border-end pe-4 border-white border-opacity-25">
                    <a href="#">Helpdesk</a>
                    <span class="opacity-50">|</span>
                    <a href="#">Policy Handbook</a>
                </div>
                
                <!-- Profile Action Submenu -->
                <div class="dropdown user-dropdown">
                    <a class="dropdown-toggle text-white d-flex align-items-center gap-2 text-decoration-none" href="#" role="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-6"></i>
                        <span>Alex Mercer</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userMenuDropdown">
                        <li><div class="dropdown-header text-dark fw-bold pb-1">Account Operations</div></li>
                        <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person-gear me-2 text-muted"></i>Update Profile</a></li>
                        <li><a class="dropdown-item py-2" href="#"><i class="bi bi-shield-lock me-2 text-muted"></i>Security Settings</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MAIN NAVIGATION & MINIMALIST SEARCH -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <i class="bi bi-people-fill fs-3"></i>
                <span>DEPED <span class="text-dark">HRIS</span></span>
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links, Mega Menu & Search -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    
                    <!-- HRIS MEGA MENU TRIGGER -->
                    <li class="nav-item dropdown has-mega-menu">
                        <a class="nav-link dropdown-toggle" href="#" id="hrisMegaDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            HR Modules
                        </a>
                        <!-- Mega Menu Container -->
                        <div class="dropdown-menu mega-menu" aria-labelledby="hrisMegaDropdown">
                            <div class="row g-3">
                                
                                <!-- Column 1: Core HR & Employee Records -->
                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="mega-menu-heading">Core HR & Records</div>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="#" class="mega-menu-link"><i class="bi bi-person-lines-fill"></i> 201 Employee Files</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-diagram-3"></i> Org Architecture</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-file-earmark-text"></i> Contract Management</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-person-check"></i> Onboarding Workflows</a>
                                    </div>
                                </div>

                                <!-- Column 2: Time & Attendance -->
                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="mega-menu-heading">Time & Attendance</div>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="#" class="mega-menu-link"><i class="bi bi-calendar-check"></i> Biometric Logs</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-hourglass-split"></i> Shift Roster & Schedules</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-pigeon"></i> Leave Self-Service</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-alarm"></i> Overtime Authorizations</a>
                                    </div>
                                </div>

                                <!-- Column 3: Compensation & Benefits -->
                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="mega-menu-heading">Payroll & Benefits</div>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="#" class="mega-menu-link"><i class="bi bi-cash-stack"></i> Payroll Processing</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-receipt"></i> Payslip Archives</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-heart-pulse"></i> Health Insurance / HMO</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-piggy-bank"></i> Statutory Contributions</a>
                                    </div>
                                </div>

                                <!-- Column 4: Talent & Performance -->
                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="mega-menu-heading">Talent Management</div>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="#" class="mega-menu-link"><i class="bi bi-passport"></i> Applicant Tracking (ATS)</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-graph-up-arrow"></i> Performance Appraisals</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-journal-bookmark-fill"></i> Training & LMS</a>
                                        <a href="#" class="mega-menu-link"><i class="bi bi-award"></i> Merit & Succession Planning</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Approvals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Analytics</a>
                    </li>
                </ul>

                <!-- Smooth Modern Single-Element Pill Search Box -->
                <div class="col-12 col-lg-4">
                    <div class="search-container">
                        <input type="text" class="form-control" placeholder="Search records, files, assets..." aria-label="Search">
                        <i class="bi bi-search search-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-4">

    