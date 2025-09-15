<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cards - {{ $board->board_name }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

  <div class="container">
    <!-- Judul -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="text-primary">Board: {{ $board->board_name }}</h1>
      <a href="{{ route('teamlead.cards.create', $board->board_id) }}" class="btn btn-success">
        ➕ Tambah Card
      </a>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Daftar Cards -->
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white">
        Daftar Cards
      </div>
      <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 20%">Judul</th>
              <th style="width: 15%">Prioritas</th>
              <th style="width: 10%">Estimasi (jam)</th>
              <th style="width: 15%">Status</th>
              <th style="width: 20%">Anggota</th>
              <th style="width: 20%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($cards as $card)
              <tr>
                <!-- Judul + Deskripsi -->
                <td>
                  <strong>{{ $card->card_title }}</strong><br>
                  <small class="text-muted">{{ $card->description }}</small>
                </td>

                <!-- Prioritas -->
                <td>
                  @if($card->priority == 'high')
                    <span class="badge bg-danger">High</span>
                  @elseif($card->priority == 'medium')
                    <span class="badge bg-warning text-dark">Medium</span>
                  @else
                    <span class="badge bg-secondary">Low</span>
                  @endif
                </td>

                <!-- Estimasi Jam -->
                <td>{{ $card->estimated_hours ?? '-' }}</td>

                <!-- Status -->
                <td>
                  @if($card->status == 'todo')
                    <span class="badge bg-secondary">To Do</span>
                  @elseif($card->status == 'in_progress')
                    <span class="badge bg-primary">In Progress</span>
                  @elseif($card->status == 'review')
                    <span class="badge bg-info text-dark">Review</span>
                  @elseif($card->status == 'done')
                    <span class="badge bg-success">Done</span>
                  @endif
                </td>

                <!-- Anggota -->
                <td>
                  @if($card->assignments->isNotEmpty())
                    @foreach($card->assignments as $a)
                      <span class="badge bg-info text-dark">{{ $a->user->username }}</span>
                    @endforeach
                  @else
                    <span class="text-muted">Belum ada anggota</span>
                  @endif
                </td>

                <!-- Aksi -->
                <td>
                  <a href="{{ route('teamlead.cards.edit', [$board->board_id, $card->card_id]) }}" 
                     class="btn btn-sm btn-warning">✏️ Edit</a>
                  
                  <form method="POST" action="{{ route('teamlead.cards.destroy', [$board->board_id, $card->card_id]) }}" 
                        style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Yakin mau hapus card ini?')">🗑️ Hapus</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada card di board ini</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
