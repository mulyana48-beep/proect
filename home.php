<?php 
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();}
    
include "header.php";

$file_json = file_get_contents('pengaturan.json');
$data = json_decode($file_json, true); 

if (!$data) {
    $data = [
        'nama_sekolah' => '-',
        'tahun' => '-',
        'kontak' => '-'
    ];
}
    include "config.php";

?>

<!-- 2. Tambahkan CSS khusus halaman konten di sini (Ringan & Tanpa Elemen Luar) -->
<style>
    /* Kotak Utama pembungkus konten */
    .main-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 20px;
        box-sizing: border-box;
    }

    /* Judul Halaman */
    .page-title {
        font-size: 24px;
        margin-bottom: 20px;
        border-bottom: 2px solid #FF4500;
        padding-bottom: 10px;
        color: #1a1a1a;
    }

    /* Grid Box untuk Menu/Informasi di Dashboard (Sangat Ringan) */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    /* Kartu/Box Informasi */
    .card {
        background: #fff;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border-left: 4px solid #FF4500;
    }

    .card h3 { margin-bottom: 10px; color: #222; }
    .card p { color: #666; font-size: 14px; line-height: 1.5; }

    /* Gaya Form Input yang Responsive */
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 14px;
    }
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
    }
    
    /* Tombol Utama */
    .btn-submit {
        background-color: #FF4500;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }
    .btn-submit:hover { background-color: #e03d00; }

    /* Pengaturan di Layar HP */
    @media (max-width: 768px) {
        .main-container { margin: 15px auto; }
        .page-title { font-size: 20px; }
    }
</style>

<!-- 3. ISI KONTEN HALAMAN -->
<div class="main-container">
    
    <h2 class="page-title">Petunjuk Penggunaan<br>Aplikasi Disiplin <?php echo htmlspecialchars($data['nama_sekolah']); ?></h2>
    
    <!-- Contoh Tampilan Dashboard / Ringkasan Data -->
    <div class="dashboard-grid">
        <div class="card">
<?php echo htmlspecialchars($data['petunjuk1']); ?>

        </div>
        <div class="card">
<?php echo htmlspecialchars($data['petunjuk2']); ?>

        </div>
    </div>
        <div class="dashboard-grid">
        <div class="card">
<?php echo htmlspecialchars($data['petunjuk3']); ?>

        </div>
        <div class="card">
<?php echo htmlspecialchars($data['petunjuk4']); ?>

        </div>
    </div>

</div>

<!-- Jangan panggil tag </body> atau </html> lagi karena sudah ditutup di header.php -->
