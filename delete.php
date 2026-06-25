<?php
// Pelacak error aktif
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "config.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Proteksi admin
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    echo "Akses ditolak! Anda bukan admin.";
    exit();
}

// Proses Hapus Data
if (isset($_GET['id'])) {
    // Amankan parameter ID dari URL
    $id = mysqli_real_escape_string($con, $_GET['id']);
    
    // QUERY SUDAH DISESUAIKAN: tabel 'catat' dan kolom 'id_catat'
    $query = "DELETE FROM catat WHERE id_catat = '$id'";
    $exec = mysqli_query($con, $query);
    
    if ($exec) {
        if (mysqli_affected_rows($con) > 0) {
            echo "<script>
                    alert('Data berhasil dihapus!');
                    window.location.href = 'absentercatat.php';
                  </script>";
        } else {
            echo "Query sukses, tapi tidak ada data yang terhapus. ID '$id' tidak ditemukan.";
            echo "<br><a href='absentercatat.php'>Kembali</a>";
        }
    } else {
        echo "Gagal eksekusi query. Error MySQL: " . mysqli_error($con);
    }
} else {
    echo "Error: Parameter ID tidak ditemukan di URL.";
}
exit();
?>
