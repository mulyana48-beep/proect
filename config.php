<?php
date_default_timezone_set("Asia/Jakarta");
$server   = "sql313.infinityfree.com";
$username = "if0_42098070";
$password = "t9CiuhUJkw2vKp";
$database = "if0_42098070_absensi";

// Perbaikan: Mengubah nama variabel menjadi $conn agar sinkron dengan login.php
$con = mysqli_connect($server, $username, $password, $database);

// Periksa koneksi
if(!$con){
    // Menggunakan mysqli (pakai huruf i) agar tidak crash di hosting
    die ("Koneksi dengan database gagal: ".mysqli_connect_errno().
    " - ".mysqli_connect_error());
}

?>
