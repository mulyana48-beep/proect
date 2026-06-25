<?php
// Pastikan file ini diakses melalui pengiriman form absensi
if (isset($_POST['simpan_absensi'])) {
    
    // 1. Hubungkan dengan file konfigurasi database Anda
    include "config.php";

    // 2. Tangkap data array yang dikirim dari form absen_kelas.php
    $daftar_id      = isset($_POST['id_siswa']) ? $_POST['id_siswa'] : []; 
    $absensi        = isset($_POST['absensi']) ? $_POST['absensi'] : [];   
    $arr_nis        = isset($_POST['nis']) ? $_POST['nis'] : [];       
    $arr_nama       = isset($_POST['nama_siswa']) ? $_POST['nama_siswa'] : []; 
    $arr_kelas      = isset($_POST['kelas']) ? $_POST['kelas'] : [];     
    $kelas_asal     = $_POST['kelas_aktif']; 
    $waktu_regional = date("Y-m-d H:i:s");
    
    // Jika tidak ada data siswa sama sekali, kembalikan ke halaman utama
    if (empty($daftar_id)) {
        header("Location: absen_kelas.php?filter_kelas=" . urlencode($kelas_asal));
        exit();
    }

    // 3. Mulai Transaksi Database
    mysqli_begin_transaction($con);

    try {
        // --- PROTEKSI JUJUR: Gunakan data asli siswa pertama agar tidak membuat baris hantu/kosong bernilai 0 ---
        $id_sampel     = $daftar_id[0];
        $kelas_sampel  = isset($arr_kelas[$id_sampel]) ? $arr_kelas[$id_sampel] : $kelas_asal;
        $nama_sampel   = isset($arr_nama[$id_sampel]) ? $arr_nama[$id_sampel] : 'Sistem';
        $nis_sampel    = isset($arr_nis[$id_sampel]) ? $arr_nis[$id_sampel] : '0';
        $kode_pengirim = 1; 

        // Query pengunci master menggunakan data siswa pertama yang valid (hadir/sakit/ijin/alfa diatur 0 semua untuk log master)
        $query_master = "INSERT INTO catat (id_siswa, nama_siswa, nis, kelas, tgl, sakit, ijin, alfa, pengirim) VALUES (?, ?, ?, ?, ?, 0, 0, 0, ?)";
        $stmt_master  = mysqli_prepare($con, $query_master);
        mysqli_stmt_bind_param($stmt_master, "issssi", $id_sampel, $nama_sampel, $nis_sampel, $kelas_sampel, $waktu_regional, $kode_pengirim);
        mysqli_stmt_execute($stmt_master);
        mysqli_stmt_close($stmt_master);
        // Lakukan perulangan untuk memeriksa data setiap siswa satu per satu
        foreach ($daftar_id as $key => $id_siswa) {
            
            // PENGAMAN MANDIRI: Lewati jika id kosong
            if (empty($id_siswa) || $id_siswa == '') {
                continue; 
            }
            
            // Ambil status pilihan siswa dari array absensi
            $status = isset($absensi[$id_siswa]) ? $absensi[$id_siswa] : 'hadir'; 
            
            // Sistem HANYA memproses data jika siswa tersebut TIDAK HADIR (Sakit/Ijin/Alfa)
            if ($status !== 'hadir') {
                
                $nis_siswa   = $arr_nis[$id_siswa];
                $nama_siswa  = $arr_nama[$id_siswa];
                $kelas_siswa = $arr_kelas[$id_siswa];

                $v_sakit = 0;
                $v_ijin  = 0;
                $v_alfa  = 0;

                // --- PROSES 1: UPDATE AKUMULASI PADA TABEL data_siswa ---
                $query_update = "UPDATE data_siswa SET $status = $status + 1 WHERE id_siswa = ?";
                $stmt_update  = mysqli_prepare($con, $query_update);
                mysqli_stmt_bind_param($stmt_update, "i", $id_siswa);
                mysqli_stmt_execute($stmt_update);
                mysqli_stmt_close($stmt_update);

                if ($status == 'sakit') $v_sakit = 1;
                if ($status == 'ijin')  $v_ijin  = 1;
                if ($status == 'alfa')  $v_alfa  = 1;

                // --- PROSES 2: INJECT DATA RIWAYAT KE TABEL catat ---
                $query_catat = "INSERT INTO catat (id_siswa, nama_siswa, nis, kelas, tgl, sakit, ijin, alfa, pengirim) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_catat  = mysqli_prepare($con, $query_catat);
                mysqli_stmt_bind_param($stmt_catat, "issssiiii", $id_siswa, $nama_siswa, $nis_siswa, $kelas_siswa, $waktu_regional, $v_sakit, $v_ijin, $v_alfa, $kode_pengirim);
                mysqli_stmt_execute($stmt_catat);
                mysqli_stmt_close($stmt_catat);
            }
        }

        // 4. Terapkan seluruh perubahan secara mutlak ke database
        mysqli_commit($con);
        
        echo "<script>
                alert('Data absensi kelas berhasil disimpan dan catatan riwayat telah dibuat!');
                window.location.href = 'absen_kelas.php?filter_kelas=" . urlencode($kelas_asal) . "';
              </script>";

    } catch (Exception $e) {
        // 5. Batalkan total seluruh perubahan jika di tengah jalan terjadi error server
        mysqli_rollback($con);
        echo "Gagal menyimpan data absensi: " . $e->getMessage();
    }

    // Tutup koneksi database setelah selesai digunakan
    mysqli_close($con);

} else {
    header("Location: absen_kelas.php");
    exit();
}
?>
