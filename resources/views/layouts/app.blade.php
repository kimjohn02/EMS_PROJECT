<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS Dashboard - @yield('title')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-bg: #f4f7fb;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f1f5f9;
            --text-dark: #334155;
            --text-muted: #64748b;
            --accent-color: #2563eb;
            --active-text: #ffffff;
            --active-icon: #ffffff;
            --brand-text: #0f172a;
            --white: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        #wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .sidebar-backdrop {
            display: none;
        }

        #sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
            border-right: 1px solid #e2e8f0;
        }

        .mobile-sidebar-toggle {
            display: none;
            width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            color: #475569;
        }

        .sidebar-header {
            padding: 20px 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .sidebar-header .logo-icon-large {
            margin-bottom: 5px;
        }
        
        .sidebar-header .logo-icon-large i {
            font-size: 2.8rem;
            color: #2563eb;
        }

        .sidebar-header .brand-title {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 4px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 5px;
        }

        .sidebar-header .brand-subtitle {
            font-weight: 500;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 15px;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-menu .nav-title {
            font-size: 0.70rem;
            text-transform: uppercase;
            color: #94a3b8;
            margin: 20px 0 8px 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 13px 18px;
            color: #475569;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 600;
            font-size: 1.05rem;
            margin-bottom: 4px;
        }

        .sidebar-link i {
            width: 30px;
            font-size: 1.15rem;
            color: #2563eb;
            font-weight: 900;
            transition: color 0.2s ease;
            text-align: center;
            margin-right: 8px;
        }

        .sidebar-link:hover {
            background-color: var(--sidebar-hover);
            color: #0f172a;
        }
        
        .sidebar-link:hover i {
            color: #2563eb;
            font-weight: 900;
        }

        .sidebar-link.active {
            background-color: var(--accent-color);
            color: var(--active-text);
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.4);
        }
        
        .sidebar-link.active i {
            color: var(--active-icon);
            font-weight: 900;
        }

        .sidebar-link.logout-link {
            width: 100%;
            border: none;
            background: transparent;
            color: #ef4444;
            text-align: left;
        }

        .sidebar-link.logout-link i {
            color: #ef4444;
        }

        .sidebar-link.logout-link:hover {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .sidebar-link.logout-link:hover i {
            color: #dc2626;
        }
        
        .sidebar-profile {
            padding: 15px 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .sidebar-profile:hover {
            background-color: var(--sidebar-hover);
        }
        
        .sidebar-profile .user-avatar {
            width: 38px;
            height: 38px;
            background-color: #eff6ff;
            color: #2563eb;
            border: 2px solid var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1rem;
        }
        
        .sidebar-profile .profile-info {
            flex: 1;
            line-height: 1.2;
        }
        
        .sidebar-profile .profile-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--brand-text);
        }
        
        .sidebar-profile .profile-role {
            font-weight: 500;
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: capitalize;
        }
        
        .sidebar-profile-caret {
            color: var(--brand-text);
            font-size: 0.8rem;
        }

        #content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .topbar {
            background-color: var(--white);
            height: 70px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 10;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto;
        }

        .topbar-notification {
            margin-right: 2px;
        }

        .notification-trigger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            padding: 6px;
            color: #64748b;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .notification-trigger:hover,
        .notification-trigger:focus {
            background-color: #f1f5f9;
            color: #475569;
        }

        .topbar-user-dropdown {
            padding: 6px 10px;
            border-radius: 12px;
            gap: 6px;
        }

        .topbar-user-dropdown .user-meta {
            padding-right: 4px;
            gap: 1px;
        }

        @media (max-width: 991.98px) {
            #wrapper {
                height: auto;
                min-height: 100vh;
            }

            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: min(72vw, 240px);
                transform: translateX(-100%);
                z-index: 1045;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
            }

            .sidebar-header {
                padding: 14px 12px;
                gap: 4px;
            }

            .sidebar-header .logo-icon-large i {
                font-size: 2.2rem;
            }

            .sidebar-header .brand-title {
                font-size: 1.25rem;
                letter-spacing: 3px;
            }

            .sidebar-menu {
                padding: 10px 10px;
            }

            .sidebar-link {
                padding: 10px 12px;
                font-size: 0.95rem;
                margin-bottom: 2px;
            }

            .sidebar-link i {
                width: 24px;
                font-size: 1rem;
                margin-right: 6px;
            }

            body.sidebar-open #sidebar {
                transform: translateX(0);
            }

            .sidebar-backdrop {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.42);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.25s ease;
                z-index: 1040;
            }

            body.sidebar-open .sidebar-backdrop {
                opacity: 1;
                pointer-events: auto;
            }

            #content-wrapper {
                width: 100%;
                min-width: 0;
            }

            .mobile-sidebar-toggle {
                display: inline-flex;
            }

            .topbar {
                padding: 0 14px;
            }

            .topbar-actions {
                gap: 8px;
            }

            .topbar-user-dropdown {
                padding: 6px 8px;
            }

            .topbar-user-dropdown .user-meta {
                max-width: 104px;
            }

            .topbar-user-dropdown .user-meta span {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
            }

            .topbar-notification .dropdown-menu {
                width: min(92vw, 320px) !important;
            }

            .page-content {
                padding: 18px 14px;
            }
        }

        @media (max-width: 575.98px) {
            #sidebar {
                width: min(76vw, 228px);
            }

            .topbar-user-dropdown .user-meta {
                display: none !important;
            }
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--accent-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .page-content {
            padding: 30px;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--text-dark);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .card-header {
            background-color: var(--white);
            border-bottom: 1px solid #f1f5f9;
            padding: 15px 20px;
            font-weight: 600;
            border-radius: 12px 12px 0 0 !important;
        }

        .table-responsive {
            background: white;
            border-radius: 12px;
            padding: 6px;
        }
        
        .table th {
            color: #1e293b;
            font-weight: 700;
            font-size: 1.03rem;
            text-transform: uppercase;
            letter-spacing: 0.01em;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.95rem 0.6rem;
            white-space: nowrap;
        }
        
        .table td {
            vertical-align: middle;
            color: #0f172a;
            font-size: 1.02rem;
            padding: 0.95rem 0.6rem;
        }

        .table > :not(caption) > * > * {
            border-bottom-color: #e2e8f0;
        }

        .table.table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        .app-search-group {
            width: 100%;
            max-width: 350px;
        }

        .app-search-group .form-control,
        .app-search-group .btn {
            height: 50px;
            font-size: 1.1rem;
        }

        .app-search-group .form-control::placeholder {
            font-size: 1rem;
        }

        @media (max-width: 575.98px) {
            .app-search-group {
                max-width: 100%;
            }

            .app-search-group .form-control,
            .app-search-group .btn {
                height: 44px;
                font-size: 1rem;
            }
        }

        .badge-status {
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            min-width: 85px;
            text-align: center;
        }
        .badge-present { background-color: #dcfce7; color: #166534; }
        .badge-absent { background-color: #fee2e2; color: #991b1b; }
        .badge-late { background-color: #fef9c3; color: #854d0e; }
        .badge-active { background-color: #e0e7ff; color: #3730a3; }
        .badge-admin { background-color: #ffedd5; color: #9a3412; }
    </style>
</head>
<body>

    <div id="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <div class="logo-icon-large">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <div class="brand-title">EMS</div>
            </div>

            <ul class="sidebar-menu">
                @php $role = auth()->user()->role; @endphp

                <li>
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i> Dashboard
                    </a>
                </li>

                @if($role === 'admin' || $role === 'hr')

                    <li>
                        <a href="{{ route('employees.index') }}" class="sidebar-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users"></i> Employees
                        </a>
                    </li>
                    
                    @if($role === 'admin')
                    <li>
                        <a href="{{ route('departments.index') }}" class="sidebar-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-building"></i> Departments
                        </a>
                    </li>
                    @endif

                    <li>
                        <a href="{{ route('attendance.index') }}" class="sidebar-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-calendar-check"></i> Monitor Attendance
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('leaves.index') }}" class="sidebar-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-envelope-open-text"></i> Leave Management
                        </a>
                    </li>
                @endif

                @if($role === 'admin')

                    <li>
                        <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> Reports
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-shield"></i> Users
                        </a>
                    </li>
                @endif

                @if($role === 'employee')
                    <li>
                        <a href="{{ route('attendance.index') }}" class="sidebar-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-clock"></i> My Attendance
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('leaves.index') }}" class="sidebar-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-plane-departure"></i> Leave Requests
                        </a>
                    </li>
                @endif
            </ul>

            <div class="mt-auto px-3 py-3 w-100 border-top border-secondary border-opacity-25" style="background-color: transparent;">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="sidebar-link logout-link">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <div id="content-wrapper">
@php
    $user = auth()->user();
    $notifications = collect();
    $unreadCount = 0;

    if ($user) {
        if ($user->role === 'admin' || $user->role === 'hr') {
            $recentPending = \App\Models\LeaveRequest::with('user')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
            
            $unreadCount = \App\Models\LeaveRequest::where('status', 'pending')->count();
            
            foreach($recentPending as $req) {
                $notifications->push((object)[
                    'id' => $req->id,
                    'title' => 'New Leave Request',
                    'message' => $req->user->name . ' submitted a new ' . $req->type . ' leave request.',
                    'time' => $req->created_at->diffForHumans(),
                    'type' => 'info',
                    'color' => 'primary',
                    'icon' => 'fa-envelope-open-text'
                ]);
            }
        } else {
            $recentUpdates = \App\Models\LeaveRequest::where('user_id', $user->id)
                ->whereIn('status', ['approved', 'rejected'])
                ->orderBy('updated_at', 'desc')
                ->take(3)
                ->get();
                
            $unreadCount = $recentUpdates->count();
            
            foreach($recentUpdates as $req) {
                $isApproved = $req->status === 'approved';
                $notifications->push((object)[
                    'id' => $req->id,
                    'title' => 'Leave Request ' . ucfirst($req->status),
                    'message' => 'Your ' . $req->type . ' leave request for ' . \Carbon\Carbon::parse($req->start_date)->format('M d') . ' was ' . $req->status . '.',
                    'time' => $req->updated_at->diffForHumans(),
                    'type' => $isApproved ? 'success' : 'danger',
                    'color' => $isApproved ? 'success' : 'danger',
                    'icon' => $isApproved ? 'fa-check' : 'fa-xmark'
                ]);
            }
        }
    }
@endphp

            <div class="topbar" style="height: 60px; background: #fff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center;">
                <button type="button" class="mobile-sidebar-toggle" id="mobileSidebarToggle" aria-label="Open menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-actions">
                
                <div class="dropdown topbar-notification">
                    <a class="notification-trigger position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 1.3rem;">
                        <i class="fa-solid fa-bell"></i>
                        @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.55rem; padding: 0.25em 0.4em;">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="width: 320px; padding: 0; background-color: #2b3445; color: #fff; z-index: 9999;">
                        <div class="p-3 border-bottom border-secondary">
                            <h6 class="m-0 text-white fw-bold">Notifications</h6>
                        </div>
                        
                        <div style="max-height: 350px; overflow-y: auto;">
                            @forelse($notifications as $notif)
                            <div class="dropdown-item d-flex p-3 border-bottom border-secondary" style="background-color: transparent; white-space: normal; cursor: pointer;" onmouseover="this.style.backgroundColor='#323d52'" onmouseout="this.style.backgroundColor='transparent'">
                                <div class="me-3 mt-1">
                                    <div class="rounded-circle bg-{{ $notif->color }} d-flex justify-content-center align-items-center" style="width: 24px; height: 24px;">
                                        <i class="fa-solid {{ $notif->icon }} text-white" style="font-size: 0.65rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-white fw-bold" style="font-size: 0.85rem;">{{ $notif->title }}</h6>
                                    <p class="mb-1 text-light" style="font-size: 0.75rem; opacity: 0.8; line-height: 1.3;">{{ $notif->message }}</p>
                                    <small class="text-secondary" style="font-size: 0.70rem;">{{ $notif->time }}</small>
                                </div>
                                <div class="ms-2 mt-2">
                                    <div class="rounded-circle bg-primary" style="width: 6px; height: 6px;"></div>
                                </div>
                            </div>
                            @empty
                            <div class="p-4 text-center">
                                <i class="fa-regular fa-bell-slash fa-2x mb-2 text-secondary" style="opacity: 0.5;"></i>
                                <p class="mb-0 text-secondary" style="font-size: 0.85rem;">No new notifications</p>
                            </div>
                            @endforelse
                        </div>
                        
                        <div class="p-2 text-center border-top border-secondary">
                            <a href="#" class="text-decoration-none text-light" style="font-size: 0.8rem;">Mark all as read</a>
                        </div>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="d-flex align-items-center topbar-user-dropdown" style="cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle d-flex justify-content-center align-items-center text-white me-2 shadow-sm" style="width: 42px; height: 42px; background-color: #1e3a8a; font-weight: 600; font-size: 1.05rem;">
                            {{ $user->name === 'System Admin' ? 'AU' : strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="d-flex flex-column justify-content-center user-meta">
                            <span class="fw-bold" style="color: #1e3a8a; font-size: 0.95rem; line-height: 1.2;">{{ $user->name === 'System Admin' ? 'Admin User' : $user->name }}</span>
                            <span class="text-muted" style="font-size: 0.78rem; text-transform: capitalize; line-height: 1;">{{ $user->role }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-muted" style="font-size: 0.8rem;"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item py-2" href="{{ route('profile.index') }}"><i class="fa-solid fa-gear me-2 text-muted"></i> Settings</a></li>
                    </ul>
                </div>
                </div>

            </div>

            <div class="page-content">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const body = document.body;
            const toggleBtn = document.getElementById('mobileSidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');
            const sidebarLinks = document.querySelectorAll('#sidebar .sidebar-link');

            if (!toggleBtn || !backdrop) {
                return;
            }

            const closeSidebar = function () {
                body.classList.remove('sidebar-open');
            };

            toggleBtn.addEventListener('click', function () {
                body.classList.toggle('sidebar-open');
            });

            backdrop.addEventListener('click', closeSidebar);

            sidebarLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 991.98) {
                        closeSidebar();
                    }
                });
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 991.98) {
                    closeSidebar();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });
        })();
    </script>
    
    @yield('scripts')
</body>
</html>
