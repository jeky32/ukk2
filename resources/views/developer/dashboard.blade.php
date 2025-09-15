<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Developer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <h1 class="mb-4">👨‍💻 Dashboard Developer</h1>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($cards->isEmpty())
      <div class="alert alert-info">Belum ada tugas untuk Anda.</div>
    @else
      <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-light">
          <tr>
            <th>Proyek</th>
            <th>Board</th>
            <th>Judul</th>
            <th>Prioritas</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cards as $card)
            <tr>
              <td>{{ $card->board->project->project_name }}</td>
              <td>{{ $card->board->board_name }}</td>
              <td>{{ $card->card_title }}</td>
              <td>
                @if($card->priority == 'high')
                  <span class="badge bg-danger">High</span>
                @elseif($card->priority == 'medium')
                  <span class="badge bg-warning text-dark">Medium</span>
                @else
                  <span class="badge bg-secondary">Low</span>
                @endif
              </td>
              <td>
                @if($card->status == 'todo')
                  <span class="badge bg-secondary">To Do</span>
                @elseif($card->status == 'in_progress')
                  <span class="badge bg-primary">In Progress</span>
                @elseif($card->status == 'done')
                  <span class="badge bg-success">Done</span>
                @endif
              </td>
              <td>
                @if($card->status == 'todo')
                  <form action="{{ route('developer.cards.start', $card->card_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">🚀 Mulai</button>
                  </form>
                @elseif($card->status == 'in_progress')
                  <form action="{{ route('developer.cards.complete', $card->card_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">✅ Selesai</button>
                  </form>
                @else
                  <span class="text-muted">✔️ Selesai</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</body>
</html>
