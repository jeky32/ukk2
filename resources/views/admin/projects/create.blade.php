<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buat Proyek Baru</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg rounded-4 w-100" style="max-width: 500px;">
    <div class="card-body p-4">
      <h1 class="h4 mb-4 text-center fw-bold text-primary">Buat Proyek Baru</h1>

      <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        <div class="mb-3">
          <label for="project_name" class="form-label">Nama Proyek</label>
          <input type="text" class="form-control rounded-3" id="project_name" name="project_name" placeholder="Masukkan nama proyek">
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Deskripsi</label>
          <textarea class="form-control rounded-3" id="description" name="description" rows="3" placeholder="Tulis deskripsi proyek..."></textarea>
        </div>

        <div class="mb-3">
          <label for="deadline" class="form-label">Deadline</label>
          <input type="date" class="form-control rounded-3" id="deadline" name="deadline">
        </div>

        <button type="submit" class="btn btn-primary w-100 rounded-3">Simpan</button>
      </form>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
