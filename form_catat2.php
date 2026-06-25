<?php
include "config.php";
date_default_timezone_set("Asia/Jakarta");
$waktu_lokal = date('Y-m-d H:i:s');

// Deteksi jika ada kiriman data via AJAX
if(isset($_POST['simpan_ajax'])){
    $id_siswa          = isset($_POST['id_siswa']) ? $_POST['id_siswa'] : '';
    $nama_siswa        = isset($_POST['nama_siswa']) ? $_POST['nama_siswa'] : '';
    $nis               = isset($_POST['nis']) ? $_POST['nis'] : '';
    $kelas             = isset($_POST['kelas']) ? $_POST['kelas'] : '';
    
    $sakit             = isset($_POST['sakit']) ? (int)$_POST['sakit'] : 0;
    $ijin              = isset($_POST['ijin']) ? (int)$_POST['ijin'] : 0;
    $alfa              = isset($_POST['alfa']) ? (int)$_POST['alfa'] : 0;
    $bolos             = isset($_POST['bolos']) ? (int)$_POST['bolos'] : 0;
    $dispen            = isset($_POST['dispen']) ? (int)$_POST['dispen'] : 0;
    $dispen2           = isset($_POST['dispen2']) ? (int)$_POST['dispen2'] : 0;
    $kesiangan         = isset($_POST['kesiangan']) ? (int)$_POST['kesiangan'] : 0;
    $penghargaan       = isset($_POST['penghargaan']) ? (int)$_POST['penghargaan'] : 0;
    $jenis_penghargaan = isset($_POST['jenis_penghargaan']) ? $_POST['jenis_penghargaan'] : '';
    $poin_penghargaan = isset($_POST['poin_penghargaan']) ? (int)$_POST['poin_penghargaan'] : '';    
    $pelanggaran       = isset($_POST['pelanggaran']) ? (int)$_POST['pelanggaran'] : 0;
    $jenis_pelanggaran = isset($_POST['jenis_pelanggaran']) ? $_POST['jenis_pelanggaran'] : '';
    $poin_pelanggaran = isset($_POST['poin_pelanggaran']) ? (int)$_POST['poin_pelanggaran'] : '';        
    $menit             = (isset($_POST['menit']) && $_POST['menit'] !== '') ? (int)$_POST['menit'] : 0;
if (empty($id_siswa)) {
        echo "kosong_siswa";
        exit;
    }

    // 2. Validasi: Hitung total semua record angka
    $total_input = $sakit + $ijin + $alfa + $bolos + $dispen + $dispen2 + $kesiangan + $pelanggaran  + $penghargaan;

    // Jika semua field bernilai 0, batalkan proses kirim
    if ($total_input == 0) {
        echo "kosong_record";
        exit;
    }
    // 1. Query INSERT
    $nama_siswa_aman = mysqli_real_escape_string($con, $nama_siswa);
    $input = mysqli_query($con,"INSERT INTO catat VALUES('', '$id_siswa', '$nama_siswa_aman', '$nis', '$kelas', '$sakit', '$ijin', '$alfa', '$bolos', '$penghargaan', '$jenis_penghargaan', '$poin_penghargaan', '$pelanggaran', '$jenis_pelanggaran', '$poin_pelanggaran', '$waktu_lokal', '$dispen', '$dispen2', '$kesiangan', '$menit')");  
    
    if($input){
        // 2. Query UPDATE
        $query_update = "UPDATE data_siswa 
                         SET sakit = sakit + $sakit,
                             ijin = ijin + $ijin,
                             alfa = alfa + $alfa,
                             bolos = bolos + $bolos,
                             pelanggaran = pelanggaran + $pelanggaran,
                             poin_pelanggaran = poin_pelanggaran + $poin_pelanggaran,
                             penghargaan = penghargaan + $penghargaan,
                             poin_penghargaan = poin_penghargaan + $poin_penghargaan,
                             dispen = dispen + $dispen,
                             dispen2 = dispen2 + $dispen2,
                             kesiangan = kesiangan + $kesiangan,
                             menit = menit + $menit
                         WHERE nis = '$nis'";
                         
        mysqli_query($con, $query_update);
        echo "sukses";
    } else {
        echo "gagal";
    }
    exit;
}

include "header.php";
?>
<style type="text/css">
  /* Kotak Utama Pembungkus Form (Menggantikan grid Bootstrap lama agar pas dengan Header Baru) */
  .main {
    max-width: 600px;
    margin: 5px auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    box-sizing: border-box;
  }
.app-main-form {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    margin: 5px auto;
    border-radius: 12px;
    padding: 10px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
}
  /* Baris Form (Row) */
  .form-group.row {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 15px;
    align-items: center;
  }

  /* Desain Label Kiri */
  .col-sm-3, .col-xs-3 {
    flex: 0 0 25%;
    max-width: 25%;
    font-weight: bold;
    color: #444;
  }

  /* Desain Kolom Input Kanan */
  .col-sm-9, .col-xs-9 {
    flex: 0 0 75%;
    max-width: 75%;
    width: 50%;
  }
  /* Desain Label Kiri */
  .col-sm-2, .col-xs-2 {
    flex: 0 0 25%;
    max-width: 25%;
    font-weight: bold;
    color: #444;
  }
    
  .col-sm-1, .col-xs-1 {
    flex: 0 0 10%;
    max-width: 10%;
    font-weight: bold;
    color: #444;
  }    

  /* Desain Kolom Input Kanan */
  .col-sm-4, .col-xs-4 {
    flex: 0 0 40%;
    max-width: 40%;
    width: 50%;
  }

  /* Elemen Form Input */
  .form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
    background-color: #fff;
  }
  .form-control:focus {
    border-color: #FF4500;
    outline: none;
  }
  .form-control[readonly] {
    background-color: #f0f0f0;
    color: #666;
  }

  /* Area Pilihan Radio Button */
  h4 {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #eee;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.8;
  }

  /* Jarak antar teks Radio Button */
  h4 input[type="radio"] {
    margin-right: 4px;
    transform: scale(1.1);
    vertical-align: middle;
  }

  /* Garis pemisah di dalam pilihan */
  h4 br {
    display: block;
    content: "";
    margin-top: 8px;
  }

  /* Pembungkus area tombol agar terkunci rapi di dalam box */
  #integerForm {
    display: flex;
    flex-direction: column;
  }

  /* Mengganti sistem float dengan flex alignment agar pas di pojok kanan */
  .action-container {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 15px;
    margin-top: 20px;
    width: 100%;
  }

  #submite {
    background-color: #FF4500;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
  }
  #submite:hover {
    background-color: #e03d00;
  }

  #notifikasi_ajax {
    font-weight: bold;
    font-size: 14px;
    margin: 0;
  }

  /* --- PERBAIKAN DI LAYAR HP (MOBILE RESPONSIVE) --- */
  @media (max-width: 768px) {
    /* ... kode media query kamu yang lain tetap biarkan ... */
    
    .action-container {
      flex-direction: column-reverse;
      align-items: stretch;
      gap: 5px;
    }
    #submite {
      width: 100%;
      text-align: center;
    }
    #notifikasi_ajax {
      text-align: center;
    }
  }
</style>


<script type="text/javascript">
  var dataSiswaMap = {};
</script>

<div class="col-sm-9 col-sm-offset-3 col-md-3 col-md-offset-2 main">
    <div class="app-main-form">
      <form id="integerForm" class="form" method="POST" action="" onsubmit="simpanDataForm(event)">

<div class="form-group row">
  <label for="smFormGroupInput" class="col-sm-3 col-form-label" id="input">Cari</label>
  <div class="col-sm-9">
    <!-- PERBAIKAN: Kirim 'this' (seluruh elemen input), bukan 'this.value' -->
    <input type="text" class="form-control" id="pencarian_siswa" placeholder="Ketik nama siswa..." list="list_siswa" oninput="input_nama_datalist(this)" autocomplete="off">
    <input type="hidden" name="id_siswa" id="id_siswa_hidden">
    
    <datalist id="list_siswa">
      <?php 
      $result = mysqli_query($con, "SELECT * FROM data_siswa ORDER BY nama_siswa ASC");   
      
      // Tampung script secara terpisah agar tidak merusak struktur datalist
      $script_buffer = ""; 

      while ($row = mysqli_fetch_array($result)) {    
          // Trik: Gabungkan nama & kelas pada opsi pilihan agar user bisa membedakan saat memilih
          $tampilan_opsi = $row['nama_siswa'] . " - " . $row['kelas'];

          // Masukkan id_rekap ke dalam atribut data-id
          echo '<option value="' . htmlspecialchars($tampilan_opsi) . '" data-id="' . $row['id_rekap'] . '">';    
          
          // PERBAIKAN: Gunakan id_rekap sebagai KEY objek agar tidak saling menimpa
          $script_buffer .= 'dataSiswaMap["' . $row['id_rekap'] . '"] = {
              id: "' . $row['id_rekap'] . '",
              nama: "' . addslashes($row['nama_siswa']) . '",
              nis: "' . $row['nis'] . '",
              kelas: "' . $row['kelas'] . '"
          };' . "\n";
      }      
      ?>   
    </datalist>
  </div>
</div>

<div class="form-group row">
  <label class="col-xs-3 col-form-label" id="input">Nama Siswa</label>
  <div class="col-xs-9">
    <input type="text" name="nama_siswa" class="form-control" id="nama_siswa" readonly placeholder="Nama Siswa">
  </div>
</div>

<div class="form-group row">
  <label class="col-xs-3 col-form-label" id="input">NIS</label>
  <div class="col-xs-9">
    <input type="text" name="nis" class="form-control" id="nis" readonly placeholder="NIS">
  </div>
</div>

<div class="form-group row">
  <label class="col-xs-3 col-form-label" id="input">Kelas</label>
  <div class="col-xs-9">
    <input type="text" name="kelas" class="form-control" id="kelas" readonly placeholder="Kelas">
  </div>
</div>


<!-- Kontainer Utama: Diberi width 100% dan space-between agar melebar penuh -->
<div style="display: flex; justify-content: space-between; margin: 15px; align-items: center; width: 95%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 5px 0; box-sizing: border-box;">
    

    <!-- Pilihan Bolos -->
    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; color: #334155; cursor: pointer; white-space: nowrap;">
        <input type="radio" name="bolos" value="1" class="bisa-batal" style="width: 14px; height: 14px; cursor: pointer; margin: 0;">
        Bolos
    </label>

    <!-- Pilihan Dispensasi -->
    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; color: #334155; cursor: pointer; white-space: nowrap;">
        <input type="radio" name="dispen" value="1" class="bisa-batal" style="width: 14px; height: 14px; cursor: pointer; margin: 0;">
        Dispensasi
    </label>

    <!-- Pilihan Izin Keluar -->
    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; color: #334155; cursor: pointer; white-space: nowrap;">
        <input type="radio" name="dispen2" value="1" class="bisa-batal" style="width: 14px; height: 14px; cursor: pointer; margin: 0;">
        Izin Keluar
    </label>

</div>
<h4>
          <input type="radio" name="penghargaan" value="1" class="bisa-batal">&nbsp Penghargaan<br>                
<!-- Menggunakan display flex, width 100%, dan item sejajar di tengah -->
<div class="form-group row" style="display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 15px; margin: 0 auto 15px auto;">
    
    <!-- Bagian 1: Jenis Penghargaan (Label + Input) -->
    <div style="display: flex; align-items: center; flex: 2; gap: 10px; min-width: 320px;">
        <label for="jenis_penghargaan" style="font-size: 14px; font-weight: 600; color: #334155; white-space: nowrap; margin: 0;">
            Jenis Penghargaan
        </label>
        <input id="jenis_penghargaan" type="text" name="jenis_penghargaan" class="form-control" placeholder="Jenis Penghargaan" 
               style="flex: 1; width: 100%;">
    </div>

    <!-- Bagian 2: Poin Penghargaan (Label + Input) -->
    <div style="display: flex; align-items: center; flex: 0,5; gap: 2px; min-width: 100px; justify-content: flex-end;">
        <label for="poin_penghargaan" style="font-size: 14px; font-weight: 600; color: #334155; white-space: nowrap; margin: 0;">
            Poin
        </label>
        <input id="poin_penghargaan" type="text" name="poin_penghargaan" class="form-control" placeholder="Poin" 
               style="width: 80px; text-align: center;">
    </div>
</div>

          <input type="radio" name="pelanggaran" value="1" class="bisa-batal">&nbsp Pelanggaran<br>                
<!-- Menggunakan display flex, width 100%, dan item sejajar di tengah -->
<div class="form-group row" style="display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 15px; margin: 0 auto 15px auto;">
    
    <!-- Bagian 1: Jenis Penghargaan (Label + Input) -->
    <div style="display: flex; align-items: center; flex: 2; gap: 10px; min-width: 320px;">
        <label for="jenis_pelanggaran" style="font-size: 14px; font-weight: 600; color: #334155; white-space: nowrap; margin: 0;">
            Jenis Pelanggaran
        </label>
        <input id="jenis_pelanggaran" type="text" name="jenis_pelanggaran" class="form-control" placeholder="Jenis Pelanggaran" 
               style="flex: 1; width: 100%;">
    </div>

    <!-- Bagian 2: Poin Penghargaan (Label + Input) -->
    <div style="display: flex; align-items: center; flex: 0,5; gap: 2px; min-width: 100px; justify-content: flex-end;">
        <label for="poin_pelanggaran" style="font-size: 14px; font-weight: 600; color: #334155; white-space: nowrap; margin: 0;">
            Poin
        </label>
        <input id="poin_pelanggaran" type="text" name="poin_pelanggaran" class="form-control" placeholder="Poin" 
               style="width: 80px; text-align: center;">
    </div>
</div>

          <input type="radio" name="kesiangan" value="1" class="bisa-batal">&nbsp Kesiangan<br> 
<!-- Menggunakan display flex, width 100%, dan item sejajar di tengah -->
<div class="form-group row" style="display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 15px; margin: 0 auto 15px auto;">
    
    <!-- Bagian 1: Jenis Penghargaan (Label + Input) -->
    <div style="display: flex; align-items: center; flex: 2; gap: 65px; min-width: 250px;">
        <label for="kesiangan" style="font-size: 14px; font-weight: 600; color: #334155; white-space: nowrap; margin: 0;">
            Kesiangan
        </label>
        <input id="menit" type="text" name="menit" class="form-control" placeholder="Menit" 
               style="width: 200px; text-align: center;">
    </div>

        </h4>

<!-- Cari bagian ini di paling bawah form kamu, lalu bungkus dengan div class="action-container" -->
<div class="action-container">
    <span id="notifikasi_ajax"></span>
    <button type="submit" class="btn btn-primary" id="submite">Simpan</button>
</div>
</div>

      </form>
  </div>
</div>

<script type="text/javascript">
  // Inisialisasi map global
  var dataSiswaMap = {};
  
  // Cetak semua data map siswa yang aman dari duplikasi di sini
  <?php echo $script_buffer; ?>

  // Fungsi penangkap data pencarian
  function input_nama_datalist(inputElement) {
      var kataKunci = inputElement.value;
      var listOpsi = document.querySelectorAll('#list_siswa option');
      var idTerpilih = null;

      // Cari option yang dicocokkan berdasarkan teks input
      for (var i = 0; i < listOpsi.length; i++) {
          if (listOpsi[i].value === kataKunci) {
              idTerpilih = listOpsi[i].getAttribute('data-id');
              break;
          }
      }

      // Jalankan mapping jika id_rekap ditemukan
      if (idTerpilih && dataSiswaMap[idTerpilih]) {
          var dataSiswa = dataSiswaMap[idTerpilih];
          
          document.getElementById('id_siswa_hidden').value = dataSiswa.id;
          document.getElementById('nama_siswa').value = dataSiswa.nama;
          document.getElementById('nis').value = dataSiswa.nis;
          document.getElementById('kelas').value = dataSiswa.kelas;
      } else {
          // Bersihkan form jika input dihapus atau tidak valid
          document.getElementById('id_siswa_hidden').value = "";
          document.getElementById('nama_siswa').value = "";
          document.getElementById('nis').value = "";
          document.getElementById('kelas').value = "";
      }
  }

function simpanDataForm(event) {
    event.preventDefault();
    var form = document.getElementById('integerForm');
    var formData = new FormData(form);
    formData.append('simpan_ajax', '1');
    
    var notifElement = document.getElementById('notifikasi_ajax');
    notifElement.style.color = "orange";
    notifElement.innerText = "Sedang menyimpan...";

    fetch("", { method: "POST", body: formData })
    .then(response => response.text())
    .then(data => {
        if(data.trim() === "sukses") {
            notifElement.style.color = "green";
            notifElement.innerText = "✓ Data berhasil disimpan!";
            form.reset();
            document.getElementById('id_siswa_hidden').value = "";
            document.querySelectorAll('.bisa-batal').forEach(el => {
                el.checked = false;
                el.previousChecked = false;
            });
            setTimeout(function() { notifElement.innerText = ""; }, 3000);
        } else {
            notifElement.style.color = "red";
            notifElement.innerText = "✗ Gagal menyimpan data.";
        }
    })
    .catch(error => {
        notifElement.style.color = "red";
        notifElement.innerText = "✗ Terjadi kesalahan koneksi.";
    });
}

document.querySelectorAll('.bisa-batal').forEach(function(radio) {
    radio.addEventListener('click', function() {
        if (this.previousChecked) {
            this.checked = false;
            this.previousChecked = false;
        } else {
            let namaGrup = ["sakit", "ijin", "alfa", "bolos", "dispen", "dispen2"];
            if(namaGrup.includes(this.name)){
                document.querySelectorAll('.bisa-batal').forEach(function(r) {
                    if(namaGrup.includes(r.name)) r.previousChecked = false;
                });
            } else {
                document.querySelectorAll('input[name="' + this.name + '"]').forEach(function(r) {
                    r.previousChecked = false;
                });
            }
            this.previousChecked = true;
        }
    });
});
</script>
