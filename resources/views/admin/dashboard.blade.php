<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Manajemen Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
            min-height: 100vh;
            color: #e0e7ff;
        }

        .main-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(30, 27, 75, 0.6);
            backdrop-filter: blur(10px);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(139, 92, 246, 0.15);
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 3rem;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.5);
            position: relative;
            overflow: hidden;
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .logo-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #a78bfa 0%, #c4b5fd 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.25rem;
        }

        .logo-subtitle {
            font-size: 0.875rem;
            color: #a78bfa;
        }

        .nav-menu {
            flex: 1;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: #a5b4fc;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .nav-link i {
            margin-right: 0.875rem;
            font-size: 1.25rem;
        }

        .nav-link:hover {
            background: rgba(99, 102, 241, 0.15);
            color: #c4b5fd;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.25) 0%, rgba(76, 29, 149, 0.25) 100%);
            color: #c4b5fd;
            border-left: 3px solid #6366f1;
        }

        .logout-section {
            padding-top: 1.5rem;
            border-top: 1px solid rgba(165, 180, 252, 0.15);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.875rem 1rem;
            background: transparent;
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
        }

        .logout-btn i {
            margin-right: 0.875rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .topbar h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #a5b4fc;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
        }

        /* Projects Section */
        .projects-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .projects-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #a5b4fc;
        }

        .project-count {
            background: rgba(99, 102, 241, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #c4b5fd;
            font-weight: 600;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .tab-btn {
            padding: 0.625rem 1.25rem;
            background: rgba(30, 27, 75, 0.5);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 10px;
            color: #a5b4fc;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .tab-btn:hover, .tab-btn.active {
            background: rgba(99, 102, 241, 0.3);
            border-color: #8b5cf6;
            color: #c4b5fd;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }

        .page-nav {
            display: flex;
            gap: 0.5rem;
        }

        .page-btn {
            padding: 0.5rem 0.875rem;
            background: rgba(30, 27, 75, 0.5);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 8px;
            color: #a5b4fc;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .page-btn:hover:not(:disabled) {
            background: rgba(99, 102, 241, 0.3);
            color: #c4b5fd;
            border-color: #8b5cf6;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Project Cards Grid */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .project-card {
            background: rgba(30, 27, 75, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 16px;
            padding: 1.75rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(99, 102, 241, 0.3);
            border-color: rgba(139, 92, 246, 0.5);
        }

        .project-card:hover::before {
            opacity: 1;
        }

        .project-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .project-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #e0e7ff;
        }

        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-completed {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-progress {
            background: rgba(251, 146, 60, 0.2);
            color: #fb923c;
            border: 1px solid rgba(251, 146, 60, 0.3);
        }

        .status-low {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .project-description {
            color: #a5b4fc;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1.25rem;
        }

        .project-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #a5b4fc;
        }

        .meta-item i {
            color: #a78bfa;
        }

        .progress-section {
            margin-bottom: 1.25rem;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .progress-label {
            color: #a5b4fc;
        }

        .progress-value {
            font-weight: 600;
        }

        .progress-bar-container {
            height: 8px;
            background: rgba(30, 27, 75, 0.8);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        .progress-100 {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
        }

        .progress-low {
            background: linear-gradient(90deg, #4b5563, #6b7280);
        }

        .progress-medium {
            background: linear-gradient(90deg, #f59e0b, #fb923c);
        }

        .project-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn-action {
            flex: 1;
            padding: 0.625rem;
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 10px;
            background: rgba(30, 27, 75, 0.5);
            color: #a5b4fc;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            text-decoration: none;
        }

        .btn-action:hover {
            background: rgba(99, 102, 241, 0.3);
            color: #c4b5fd;
            border-color: #8b5cf6;
        }

        .btn-comment {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .btn-comment:hover {
            background: rgba(16, 185, 129, 0.25);
            color: #10b981;
            border-color: #10b981;
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }

            .projects-grid {
                grid-template-columns: 1fr;
            }

            .filter-tabs {
                flex-direction: column;
            }

            .pagination-controls {
                margin-left: 0;
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="bi bi-layers"></i>
                </div>
                <div class="logo-title">Project PRO</div>
                <div class="logo-subtitle">Admin Panel</div>
            </div>

            <nav class="nav-menu">
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link active">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-graph-up-arrow"></i>
                        Monitoring
                    </a>
                </div>
            </nav>

            <div class="logout-section">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="topbar">
                <div>
                    <h1>Dashboard Admin</h1>
                </div>
                <div>

                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="projects-header">
                <h2>Daftar Proyek</h2>
                <span class="project-count">{{ count($projects) }} Proyek</span>
            </div>

            <div class="filter-tabs">
                <button class="tab-btn active">Tampil Semua</button>

                <div class="pagination-controls">
                    <span style="color: #ae75ff; font-size: 0.875rem;">Halaman 1 dari 1</span>
                    <div class="page-nav">
                        <button class="page-btn" disabled>
                            <i class="bi bi-chevron-left"></i>
                            Sebelumnya
                        </button>
                        <button class="page-btn">
                            Selanjutnya
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="projects-grid">
                @forelse($projects as $project)
                    @php
                        // Hitung progress berdasarkan cards yang selesai
                        $totalCards = 0;
                        $doneCards = 0;
                        foreach($project->boards as $board) {
                            $totalCards += $board->cards->count();
                            $doneCards += $board->cards->where('status', 'done')->count();
                        }
                        $progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;

                        // Tentukan status
                        $statusClass = 'status-low';
                        $statusText = 'Low Progress';
                        $progressColor = '#f87171';
                        $progressClass = 'progress-low';

                        if($progress == 100) {
                            $statusClass = 'status-completed';
                            $statusText = 'Completed';
                            $progressColor = '#10b981';
                            $progressClass = 'progress-100';
                        } elseif($progress >= 30) {
                            $statusClass = 'status-progress';
                            $statusText = 'In Progress';
                            $progressColor = '#fb923c';
                            $progressClass = 'progress-medium';
                        }

                        // Deadline (bisa ditambahkan ke database jika diperlukan)
                        $deadline = \Carbon\Carbon::parse($project->created_at)->addMonths(3)->format('d M Y');
                        $createdDate = \Carbon\Carbon::parse($project->created_at)->format('d M Y');
                    @endphp

                    <div class="project-card">
                        <div class="project-header">
                            <div>
                                <h3 class="project-title">{{ $project->project_name }}</h3>
                            </div>
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </div>

                        <p class="project-description">
                            {{ Str::limit($project->description, 80) }}
                        </p>

                        <div class="project-meta">
                            <div class="meta-item">
                                <i class="bi bi-calendar-event"></i>
                                <span>Deadline: {{ $deadline }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-clock"></i>
                                <span>Dibuat: {{ $createdDate }}</span>
                            </div>
                        </div>

                        <div class="progress-section">
                            <div class="progress-header">
                                <span class="progress-label">Progress</span>
                                <span class="progress-value" style="color: {{ $progressColor }};">
                                    {{ $progress }}% Complete
                                </span>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar {{ $progressClass }}" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <div class="project-actions">
                            <a href="#" class="btn-action">
                                <i class="bi bi-eye"></i>
                                Detail
                            </a>
                            <a href="#" class="btn-action btn-comment">
                                <i class="bi bi-chat-dots"></i>
                                Komentar
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-folder-x" style="font-size: 3rem; color: #a5b4fc; opacity: 0.5;"></i>
                        <p style="color: #a5b4fc; margin-top: 1rem;">Belum ada proyek. Silakan buat proyek baru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
