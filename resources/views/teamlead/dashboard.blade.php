<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Team Lead</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($projects as $i => $project)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $project->project_name }}</td>
                        <td>{{ $project->deadline }}</td>
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
</body>
</html>
