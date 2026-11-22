<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di PustakaSekolah</title>
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 text-center text-white" 
      style="background: linear-gradient(135deg, #0b1120 0%, #0f172a 50%, #1e293b 100%);">

    <div class="px-4" style="max-width: 650px;">
        <!-- Lencana -->
        <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 fs-6">
            <i class="bi bi-mortarboard-fill me-1"></i> Perpustakaan Digital
        </span>

        <!-- Judul Sambutan -->
        <h1 class="display-4 fw-bold mb-3">
            Selamat Datang di <span class="text-primary">PustakaSekolah</span>
        </h1>

        <!-- Deskripsi Singkat -->
        <p class="lead text-light opacity-75 mb-4 fs-6">
            Akses 74+ buku pelajaran resmi Kurikulum Merdeka dan novel pilihan secara gratis dan nyaman langsung di peramban
        </p>

        <!-- Tombol Mulai Baca -->
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow">
            <i class="bi bi-book-half me-2"></i>Yuk Mulai Baca
        </a>
    </div>

</body>
</html>
