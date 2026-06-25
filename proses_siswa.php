<?php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "<script>alert('Akses ilegal!'); window.location.href = 'home.php';</script>";
    exit();
}

include 'config.php'; 

// =========================================================================
// BLOK PROSES OPSI 1: JIKA TOMBOL KOSONGKAN DATA DATA_SISWA DITEKAN
// =========================================================================
if (isset($_POST['aksi_kosongkan'])) {
    $query_reset = "TRUNCATE TABLE data_siswa";
    if (mysqli_query($con, $query_reset)) {
        echo "<script>
                alert('Sukses! Tabel data_siswa berhasil dikosongkan dan di-reset kembali ke awal.');
                window.location.href = 'upload_siswa.php';
              </script>";
        exit();
    } else {
        die("Gagal mengosongkan tabel: " . mysqli_error($con));
    }
}

// =========================================================================
// BLOK PROSES OPSI 3: JIKA TOMBOL SETEL SEMUA KOLOM MENJADI 0 DITEKAN
// =========================================================================
if (isset($_POST['aksi_setel_nol'])) {
    // Perintah UPDATE untuk mengubah semua kolom yang diminta menjadi 0 secara massal
    $query_setel_nol = "UPDATE data_siswa SET 
                        sakit = 0, 
                        ijin = 0, 
                        alfa = 0, 
                        bolos = 0, 
                        penghargaan = 0, 
                        poin_penghargaan = 0, 
                        pelanggaran = 0, 
                        poin_pelanggaran = 0, 
                        dispen = 0, 
                        dispen2 = 0, 
                        kesiangan = 0, 
                        menit = 0";
                        
    if (mysqli_query($con, $query_setel_nol)) {
        echo "<script>
                alert('Sukses! Semua nilai kolom absensi dan poin siswa berhasil di-reset menjadi 0.');
                window.location.href = 'upload_siswa.php';
              </script>";
        exit();
    } else {
        // Tampilkan pesan error jika query gagal dieksekusi di database
        die("Gagal memperbarui data tabel: " . mysqli_error($con));
    }
}

// =========================================================================
// BLOK PROSES OPSI 2: JIKA FORMS UPLOAD & TAMBAH DATA DITEKAN
// =========================================================================
if (isset($_POST['aksi_upload'])) {
    $file_nama = $_FILES['file_csv']['tmp_name'];
    $file_ukuran = $_FILES['file_csv']['size'];

    if ($file_ukuran > 0) {
        $file = fopen($file_nama, "r");
        
        // Ubah ke ";" jika membuat CSV dari Microsoft Excel regional Indonesia
        $delimiter = ";"; 
        
        // Lewati baris pertama (header)
        fgetcsv($file, 1000, $delimiter); 

        $jumlah_sukses = 0;

        while (($data = fgetcsv($file, 1000, $delimiter)) !== FALSE) {
            if (count($data) < 3) {
                continue;
            }

            $nama_siswa = mysqli_real_escape_string($con, $data[0]);
            $nis        = mysqli_real_escape_string($con, $data[1]);
            $kelas      = mysqli_real_escape_string($con, $data[2]);

            if (empty($nama_siswa)) {
                continue;
            }

            // INSERT langsung tanpa TRUNCATE (fungsi menambah/append)
            $query = "INSERT INTO data_siswa (
                        nama_siswa, nis, kelas, id_siswa,
                        sakit, ijin, alfa, bolos, 
                        penghargaan, poin_penghargaan, 
                        pelanggaran, poin_pelanggaran, 
                        dispen, dispen2, kesiangan, menit
                      ) VALUES (
                        '$nama_siswa', '$nis', '$kelas', 0,
                        0, 0, 0, 0, 
                        0, 0, 
                        0, 0, 
                        0, 0, 0, 0
                      )";
            
            if (mysqli_query($con, $query)) {
                // Sinkronisasi id_siswa agar kembar dengan id_rekap
                $id_baru = mysqli_insert_id($con);
                $query_update_id = "UPDATE data_siswa SET id_siswa = $id_baru WHERE id_rekap = $id_baru";
                mysqli_query($con, $query_update_id);

                $jumlah_sukses++;
            }
        }
        fclose($file);

        echo "<script>
                alert('Berhasil! $jumlah_sukses data siswa baru telah ditambahkan ke database.');
                window.location.href='upload_siswa.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('File kosong atau tidak valid.'); window.history.back();</script>";
        exit();
    }
}
?>
