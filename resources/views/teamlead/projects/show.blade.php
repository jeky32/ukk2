<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Proyek - Team Lead</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">

  <!-- Info Proyek -->
  <div class="mb-4">
    <h1 class="text-primary">{{ $project->project_name }}</h1>
    <p>{{ $project->description }}</p>
    <p><strong>Deadline:</strong> {{ $project->deadline }}</p>
  </div>

  <!-- Boards -->
  <div class="row">
    @foreach($project->boards as $board)
      <div class="col-md-3">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span>{{ $board->board_name }}</span>

            <!-- Team Lead bisa tambah card -->
            @if(auth()->user()->role == 'team_lead' && $board->board_name == 'To Do')
              <a href="{{ route('teamlead.cards.create', $board->board_id) }}" class="btn btn-sm btn-success">
                + Card
              </a>
            @endif
          </div>
          <ul class="list-group list-group-flush">
            @forelse($board->cards as $card)
              <li class="list-group-item">
                <strong>{{ $card->card_title }}</strong><br>
                <small>Priority: {{ $card->priority }} | Status: {{ $card->status }}</small><br>

                <!-- Link ke halaman detail cards.index -->
                <a href="{{ route('teamlead.cards.index', $board->board_id) }}" class="btn btn-sm btn-info mt-1">
                  Kelola Cards
                </a>
              </li>
            @empty
              <li class="list-group-item text-muted">Belum ada tugas</li>
            @endforelse
          </ul>
        </div>
      </div>
    @endforeach
  </div>

</div>
</body>
</html>
