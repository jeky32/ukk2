<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Proyek</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border: none;
      border-radius: 1rem;
    }
    .list-group-item {
      transition: background 0.3s;
    }
    .list-group-item:hover {
      background-color: #f1f1f1;
    }
    .form-control, .form-select {
      border-radius: 0.5rem;
    }
    .btn-modern {
      border-radius: 0.5rem;
      font-weight: 500;
      transition: transform 0.2s;
    }
    .btn-modern:hover {
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

<div class="container py-5">

  <!-- ✅ Notifikasi -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Detail Project -->
  <div class="card shadow-lg mb-4">
    <div class="card-body">
      <h1 class="card-title text-primary fw-bold">{{ $project->project_name }}</h1>
      <p class="card-text text-muted">{{ $project->description }}</p>
      <small class="text-secondary">Deadline: {{ $project->deadline }}</small>
    </div>
  </div>

  <!-- Boards -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white fw-semibold">Boards</div>
    <ul class="list-group list-group-flush">
      @forelse($project->boards as $board)
        <li class="list-group-item">{{ $board->board_name }}</li>
      @empty
        <li class="list-group-item text-muted">Belum ada board</li>
      @endforelse
    </ul>
  </div>

  <!-- Anggota -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white fw-semibold">Anggota</div>
    <ul class="list-group list-group-flush">
      @forelse($project->members as $member)
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span>{{ $member->user->full_name }}</span>
          <span class="badge bg-secondary">{{ ucfirst($member->role) }}</span>
        </li>
      @empty
        <li class="list-group-item text-muted">Belum ada anggota</li>
      @endforelse
    </ul>
  </div>

  <!-- Tambah Anggota -->
  <div class="card shadow-sm">
    <div class="card-header bg-info text-white fw-semibold">Tambah Anggota</div>
    <div class="card-body">
      <form method="POST" action="{{ route('projects.members.add', $project->project_id) }}" class="row g-3">
        @csrf
        <div class="col-md-6">
          <input type="text" name="username" class="form-control" placeholder="Username anggota" required>
        </div>
        <div class="col-md-4">
          <select name="role" class="form-select" required>
            <option value="">Pilih role</option>
            <option value="team_lead">Team Lead</option>
            <option value="developer">Developer</option>
            <option value="designer">Designer</option>
          </select>
        </div>
        <div class="col-md-2 d-grid">
          <button type="submit" class="btn btn-info text-white btn-modern">Tambah</button>
        </div>
      </form>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
