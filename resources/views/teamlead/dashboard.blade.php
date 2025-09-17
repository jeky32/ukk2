<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Team Lead</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .progress {
            height: 20px;
            background-color: #e9ecef;
            border-radius: 4px;
        }
        .progress-bar {
            transition: width 0.5s ease;
        }
        .progress-text {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            text-shadow: 0 0 2px #fff;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .progress-details {
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3 vh-100" style="width:220px;">
        <h4 class="text-center">Team Lead</h4>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('teamlead.dashboard') }}" class="nav-link text-white">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            @if(isset($project) && $project->boards->count())
                <li class="nav-item mt-2">
                    <span class="text-white small fw-bold">Boards</span>
                </li>
                @foreach($project->boards as $board)
                    <li class="nav-item">
                        <a href="{{ route('teamlead.cards.index', $board->board_id) }}" class="nav-link text-white ps-4">
                            <i class="bi bi-kanban"></i> {{ $board->board_name }}
                        </a>
                    </li>
                @endforeach
            @endif

            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="nav-link text-danger btn btn-link w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <!-- Topbar -->
        <nav class="navbar navbar-light bg-light shadow-sm">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h4">Dashboard Team Lead</span>
            </div>
        </nav>

        <!-- Content -->
        <div class="p-4">
            <h2>Proyek yang Anda Pimpin</h2>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Proyek</th>
                        <th>Deadline</th>
                        <th>Progress</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($projects as $i => $project)
                    @php
                        // Hitung progress proyek berdasarkan status kartu dengan bobot
                        $totalCards = 0;
                        $progressValue = 0;
                        
                        // Bobot untuk setiap status
                        $statusWeights = [
                            'todo' => 0,
                            'in_progress' => 0.5,  // 50% selesai
                            'review' => 0.8,       // 80% selesai
                            'done' => 1            // 100% selesai
                        ];
                        
                        foreach ($project->boards as $board) {
                            foreach ($board->cards as $card) {
                                $totalCards++;
                                $status = strtolower($card->status);
                                $statusKey = str_replace(' ', '_', $status);
                                
                                // Tambahkan bobot berdasarkan status
                                if (array_key_exists($statusKey, $statusWeights)) {
                                    $progressValue += $statusWeights[$statusKey];
                                } else {
                                    // Default untuk status yang tidak dikenal
                                    $progressValue += 0;
                                }
                            }
                        }
                        
                        // Hitung persentase progress
                        $progress = $totalCards > 0 ? round(($progressValue / $totalCards) * 100) : 0;
                        
                        // Batasi progress antara 0-100
                        $progress = min(100, max(0, $progress));
                        
                        // Tentukan warna progress bar berdasarkan persentase
                        if ($progress < 30) {
                            $progressClass = 'bg-danger';
                        } elseif ($progress < 70) {
                            $progressClass = 'bg-warning';
                        } else {
                            $progressClass = 'bg-success';
                        }
                        
                        // Hitung jumlah kartu per status untuk tooltip
                        $statusCounts = [
                            'todo' => 0,
                            'in_progress' => 0,
                            'review' => 0,
                            'done' => 0
                        ];
                        
                        foreach ($project->boards as $board) {
                            foreach ($board->cards as $card) {
                                $status = strtolower($card->status);
                                $statusKey = str_replace(' ', '_', $status);
                                
                                if (array_key_exists($statusKey, $statusCounts)) {
                                    $statusCounts[$statusKey]++;
                                }
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $project->project_name }}</td>
                        <td>{{ $project->deadline }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" 
                                     data-bs-toggle="tooltip" 
                                     title="To Do: {{ $statusCounts['todo'] }} | In Progress: {{ $statusCounts['in_progress'] }} | Review: {{ $statusCounts['review'] }} | Done: {{ $statusCounts['done'] }}">
                                    <div class="progress-bar {{ $progressClass }}" 
                                         role="progressbar" 
                                         style="width: {{ $progress }}%;" 
                                         aria-valuenow="{{ $progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <span class="progress-text">{{ $progress }}%</span>
                                    </div>
                                </div>
                                <small class="progress-details">{{ $totalCards }} tugas</small>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('teamlead.projects.show',$project->project_id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Inisialisasi tooltip
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
</body>
</html>