<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>System Integrity Check</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h3 class="mb-4">🔐 System Integrity Check</h3>

    <div class="alert alert-{{ $status }}">
        <strong>Status:</strong> {{ $message }}
    </div>

    @if ($status === 'success')
        <div class="alert alert-info">
            ✅ Tidak ditemukan perubahan data ilegal.<br>
            🔗 Hash setiap blok sesuai dengan blok sebelumnya.<br>
            🛡️ Sistem siap mendeteksi manipulasi data.
        </div>
    @elseif ($status === 'danger')
        <div class="alert alert-warning">
            ⚠️ Terdeteksi perubahan data langsung di database.<br>
            ❌ Integritas blockchain tidak valid.
        </div>
    @else
        <div class="alert alert-secondary">
            ℹ️ Data blockchain belum cukup untuk diverifikasi.
        </div>
    @endif

    <a href="/admin/dashboard" class="btn btn-secondary mt-3">
        ← Kembali ke Dashboard
    </a>

</div>

</body>
</html>
