<?php
include 'config.php'; 
// Cek apakah tombol daftar sudah diklik
if (isset($_POST['register'])) {
    // Ambil data dari formulir dan bersihkan dari spasi berlebih
    $username = trim($_POST['username']);
    $password_asli = $_POST['password'];
    $level = $_POST['level'];
    $nama_user = trim($_POST['nama_user']);

    // Validasi sederhana: pastikan tidak ada field yang kosong
    if (!empty($username) && !empty($password_asli) && !empty($nama_user)) {
        
        // 1. Amankan password dengan HASHING sebelum masuk database
        $password_terhash = password_hash($password_asli, PASSWORD_DEFAULT);

        // 2. Gunakan Prepared Statement untuk mencegah SQL Injection
        $stmt = mysqli_prepare($con, "INSERT INTO admin (username, password, level, nama_user) VALUES (?, ?, ?, ?)");
        
        if ($stmt) {
            // "ssss" berarti ada 4 parameter berjenis string (s)
            mysqli_stmt_bind_param($stmt, "ssss", $username, $password_terhash, $level, $nama_user);
            
            // 3. Jalankan kueri
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('Gagal mendaftar. Username mungkin sudah digunakan.');</script>";
            }
            
            // Tutup statement
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "<script>alert('Semua data wajib diisi!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Akun Admin Baru</title>
</head>
<body>
    <h2>Form Registrasi Admin</h2>
    <form action="" method="POST">
        <label>Nama Lengkap:</label><br>
        <input type="text" name="nama_user" required><br><br>

        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Level/Hak Akses:</label><br>
        <select name="level">
            <option value="admin">Admin</option>
            <option value="piket">Piket</option>
        </select><br><br>

        <button type="submit" name="register">Daftar Akun</button>
    </form>
</body>
</html>
