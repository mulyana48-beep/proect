<?php
    include "config.php";
    include 'header.php';

$file_name = 'pengaturan.json';

// Proses Simpan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_baru = [
        'nama_sekolah' => $_POST['nama_sekolah'],
        'slogan' => $_POST['slogan'],
        'tahun'        => $_POST['tahun'],
        'semester' => $_POST['semester'],
        'kontak'       => $_POST['kontak'],
        'kontak2' => $_POST['kontak2'],
        'petunjuk1'     => $_POST['petunjuk1'],
		'petunjuk2'     => $_POST['petunjuk2'],
		'petunjuk3'     => $_POST['petunjuk3'],
		'petunjuk4'     => $_POST['petunjuk4']
    ];
    
    file_put_contents($file_name, json_encode($data_baru, JSON_PRETTY_PRINT));
    $pesan = "Perubahan berhasil disimpan!";
}

// Ambil data terbaru untuk ditampilkan di form
$file_json = file_get_contents($file_name);
$data = json_decode($file_json, true);
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <style>
        /* Gaya Dasar Halaman */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 20px;
            padding: 0;
        }

        /* Kontainer Utama Form */
        .admin-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Judul Halaman */
        h2 {
            color: #2c3e50;
            margin-top: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 10px;
            font-size: 24px;
        }

        /* Grup Input */
        .form-group {
            margin-bottom: 20px;
        }

        /* Label Input */
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #4a5568;
            font-size: 14px;
        }

        /* Kotak Input dan Textarea */
/* KODE BARU YANG SUDAH DIPERBAIKI */
.form-group input[type="text"], 
.form-group textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #cbd5e0;
    border-radius: 6px;
    font-size: 15px;
    color: #2d3748;
    background-color: #fff; /* Sekarang warna putih ini hanya mengunci kotak form admin */
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
}

        /* Efek Fokus saat Input diklik */
        input[type="text"]:focus, 
        textarea:focus {
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
            outline: none;
        }

        /* Tombol Simpan */
        button[type="submit"] {
            background-color: #3182ce;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.2s;
        }

        button[type="submit"]:hover {
            background-color: #2b6cb0;
        }

        /* Notifikasi Sukses */
        .alert-success {
            background-color: #c6f6d5;
            color: #22543d;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            border-left: 4px solid #38a169;
        }

        /* Link Navigasi Bawah */
        .navigation-link {
            display: inline-block;
            margin-top: 20px;
            color: #495057;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .navigation-link:hover {
            color: #2b6cb0;
            text-decoration: underline;
        }
        
        header img, header input[type="image"] {
    background-color: transparent !important;
    background: transparent !important;
}

    </style>
</head>
<body>


    <div class="admin-container">
        <h2>Pengaturan Web</h2>

        <!-- Notifikasi jika data berhasil disimpan -->
        <?php if(isset($pesan)): ?>
            <div class="alert-success">
                <b>✓ Berhasil!</b> <?php echo $pesan; ?> <a href="admin.php" class="btn btn-default btn-sm">🏠 Home</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_sekolah">Nama Sekolah:</label>
                <input type="text" id="nama_sekolah" name="nama_sekolah" value="<?php echo htmlspecialchars($data['nama_sekolah']); ?>"  required>
            </div>
            
            <div class="form-group">
                <label for="nama_sekolah">Slogan:</label>
                <input type="text" id="slogan" name="slogan" value="<?php echo htmlspecialchars($data['slogan']); ?>"  required>
            </div>
            
            <div class="form-group">
                <label for="tahun">Tahun Pelajaran:</label>
                <input type="text" id="tahun" name="tahun" value="<?php echo htmlspecialchars($data['tahun']); ?>" placeholder="Contoh: 2026/2027" required>
            </div>

            <div class="form-group">
                <label for="semester">Semester:</label>
                <input type="text" id="semester" name="semester" value="<?php echo htmlspecialchars($data['semester']); ?>"  required>
            </div>

            <div class="form-group">
                <label for="kontak">Owner:</label>
                <input type="text" id="kontak" name="kontak" value="<?php echo htmlspecialchars($data['kontak']); ?>" required>
            </div>

            <div class="form-group">
                <label for="kontak2">Kontak / Website:</label>
                <input type="text" id="kontak2" name="kontak2" value="<?php echo htmlspecialchars($data['kontak2']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="petunjuk1">Teks Petunjuk:</label>
                <textarea id="petunjuk1" name="petunjuk1" rows="5"  required><?php echo htmlspecialchars($data['petunjuk1']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="petunjuk2">Teks Petunjuk:</label>
                <textarea id="petunjuk2" name="petunjuk2" rows="5"  required><?php echo htmlspecialchars($data['petunjuk2']); ?></textarea>
            </div>
 
            <div class="form-group">
                <label for="petunjuk3">Teks Petunjuk:</label>
                <textarea id="petunjuk3" name="petunjuk3" rows="5"  required><?php echo htmlspecialchars($data['petunjuk3']); ?></textarea>
            </div>
 
            <div class="form-group">
                <label for="petunjuk4">Teks Petunjuk:</label>
                <textarea id="petunjuk4" name="petunjuk4" rows="5"  required><?php echo htmlspecialchars($data['petunjuk4']); ?></textarea>
            </div>
             
            <button type="submit">Simpan Perubahan</button>
        </form>
        
            
    </div>

</body>



