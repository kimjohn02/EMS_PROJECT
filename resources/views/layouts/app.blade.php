<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS Dashboard - @yield('title')</title>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-bg: #f4f7fb;
            --sidebar-bg: #ffffff; /* Light theme sidebar */
            --sidebar-hover: #f1f5f9;
            --text-dark: #334155;
            --text-muted: #64748b;
            --accent-color: #2563eb; /* Blue matching the login logo and button */
            --active-text: #ffffff; /* White text on blue background */
            --active-icon: #ffffff;
            --brand-text: #0f172a; /* Dark navy/slate from 'Welcome Back' */
            --white: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Layout Structure */
        #wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
            border-right: 1px solid #e2e8f0;
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
            border-radius: 8px; /* matching pill shape */
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
        
        /* Bottom Profile Section */
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
            background-color: #eff6ff; /* light blue accent bg */
            color: #2563eb; /* bold blue text */
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

        /* Main Content Area */
        #content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* Top Navbar */
        .topbar {
            background-color: var(--white);
            height: 70px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 30px;
            z-index: 10;
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

        /* Page Content */
        .page-content {
            padding: 30px;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--text-dark);
        }

        /* Cards */
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

        /* Tables */
        .table-responsive {
            background: white;
            border-radius: 12px;
            padding: 10px;
        }
        
        .table th {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            border-bottom-width: 1px;
        }
        
        .table td {
            vertical-align: middle;
            font-size: 0.95rem;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
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
        <!-- Sidebar -->
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
                    <li class="nav-title">Personal</li>
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

            <div class="mt-auto px-4 py-4 w-100 border-top border-secondary border-opacity-25" style="background-color: transparent;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none text-start p-0 w-100 d-flex align-items-center" style="font-size: 0.95rem; border: none; outline: none; box-shadow: none; color: #ef4444;">
                        <i class="fa-solid fa-right-from-bracket me-3" style="font-size: 1.1rem; width: 20px; color: #ef4444;"></i>
                        <span class="fw-bold">Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <div id="content-wrapper">
            <!-- Topbar (Hidden or kept minimal based on need) -->
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

            <div class="topbar" style="height: 60px; background: #fff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; padding: 0 30px;">
                
                <!-- Notification Bell -->
                <div class="dropdown me-4">
                    <a class="text-secondary position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 1.3rem;">
                        <i class="fa-regular fa-bell"></i>
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

                <!-- User Profile info at topbar -->
                <div class="dropdown">
                    <div class="d-flex align-items-center" style="cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle d-flex justify-content-center align-items-center text-white me-2 shadow-sm" style="width: 42px; height: 42px; background-color: #1e3a8a; font-weight: 600; font-size: 1.05rem;">
                            {{ $user->name === 'System Admin' ? 'AU' : strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="d-flex flex-column justify-content-center pe-2">
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

            <!-- Page Content -->
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

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
