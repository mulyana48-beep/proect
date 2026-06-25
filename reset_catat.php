<?php
// 1. Mulai session dan proteksi halaman (Hanya Admin yang bisa mengosongkan)
session_start();

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "<script>
            alert('Akses ditolak! Anda tidak memiliki izin untuk mengosongkan tabel.');
            window.location.href = 'index.php'; 
          </script>";
    exit();
}

// 2. Sertakan file koneksi database Anda
include 'config.php';

// 3. Proses Kosongkan Data jika tombol konfirmasi ditekan
if (isset($_POST['kosongkan_data'])) {
    $nama_tabel = "catat";
    $query_reset = "TRUNCATE TABLE $nama_tabel";
    
    if (mysqli_query($con, $query_reset)) {
        echo "<script>
                alert('Sukses! Semua data pada tabel catat berhasil dikosongkan dan di-reset kembali ke awal.');
                window.location.href = 'absentercatat.php'; // Alihkan ke halaman daftar catatan
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Gagal mengosongkan tabel: " . mysqli_error($con) . "');
                window.history.back();
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Tabel Catat</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f9f9f9; }
        .box-warning { max-width: 500px; margin: auto; padding: 25px; border: 2px solid #ff4d4d; border-radius: 8px; background-color: #fff; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); }
        .title { color: #cc0000; font-size: 20px; font-weight: bold; margin-bottom: 15px; }
        .desc { font-size: 14px; color: #555; line-height: 1.5; margin-bottom: 20px; }
        .btn-danger { background-color: #d9534f; color: white; border: none; padding: 10px 20px; font-size: 14px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-danger:hover { background-color: #c9302c; }
        .btn-batal { display: inline-block; margin-left: 10px; color: #333; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="box-warning">
    <div class="title">⚠️ PERINGATAN KRUSIAL!</div>
    <p class="desc">
        Anda akan mengosongkan <strong>SELURUH DATA PADA TABEL CATAT</strong>.<br>
        Tindakan ini akan menghapus semua riwayat catatan absensi secara permanen dan tidak dapat dibatalkan (Undo). Hitungan ID Rekap/Catat akan dimulai kembali dari angka 1.
    </p>
    
    <!-- Form untuk memproses perintah TRUNCATE via POST -->
    <form action="" method="POST" onsubmit="return confirm('Apakah Anda BENAR-BENAR YAKIN ingin menghapus semua data di tabel catat? Tindakan ini permanen!');">
        <button type="submit" name="kosongkan_data" class="btn-danger">Ya, Kosongkan Semua Data</button>
        <a href="admin.php" class="btn-batal">Batal</a>
    </form>
</div>

</body>
</html>
