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
    $poin_penghargaan  = isset($_POST['poin_penghargaan']) ? (int)$_POST['poin_penghargaan'] : 0;    
    $pelanggaran       = isset($_POST['pelanggaran']) ? (int)$_POST['pelanggaran'] : 0;
    $jenis_pelanggaran = isset($_POST['jenis_pelanggaran']) ? $_POST['jenis_pelanggaran'] : '';
    $poin_pelanggaran  = isset($_POST['poin_pelanggaran']) ? (int)$_POST['poin_pelanggaran'] : 0;        
    $menit             = (isset($_POST['menit']) && $_POST['menit'] !== '') ? (int)$_POST['menit'] : 0;

    if (empty($id_siswa)) { echo "kosong_siswa"; exit; }

    $total_input = $sakit + $ijin + $alfa + $bolos + $dispen + $dispen2 + $kesiangan + $pelanggaran + $penghargaan;
    if ($total_input == 0) { echo "kosong_record"; exit; }
    
    $nama_siswa_aman = mysqli_real_escape_string($con, $nama_siswa);
    $kode_form_individu = 2; // Kunci kode angka 2 untuk form_catat.php

    // PERBAIKAN: Menyebutkan nama kolom secara spesifik agar tidak gagal atau bergeser urutannya di MySQL
    $query_catat = "INSERT INTO catat (id_siswa, nama_siswa, nis, kelas, sakit, ijin, alfa, bolos, penghargaan, jenis_penghargaan, poin_penghargaan, pelanggaran, jenis_pelanggaran, poin_pelanggaran, tgl, dispen, dispen2, kesiangan, menit, pengirim) 
                    VALUES ('$id_siswa', '$nama_siswa_aman', '$nis', '$kelas', '$sakit', '$ijin', '$alfa', '$bolos', '$penghargaan', '$jenis_penghargaan', '$poin_penghargaan', '$pelanggaran', '$jenis_pelanggaran', '$poin_pelanggaran', '$waktu_lokal', '$dispen', '$dispen2', '$kesiangan', '$menit', '$kode_form_individu')";
    
    $input = mysqli_query($con, $query_catat);  
    
    if($input){
        $query_update = "UPDATE data_siswa SET sakit=sakit+$sakit, ijin=ijin+$ijin, alfa=alfa+$alfa, bolos=bolos+$bolos, pelanggaran=pelanggaran+$pelanggaran, poin_pelanggaran=poin_pelanggaran+$poin_pelanggaran, penghargaan=penghargaan+$penghargaan, poin_penghargaan=poin_penghargaan+$poin_penghargaan, dispen=dispen+$dispen, dispen2=dispen2+$dispen2, kesiangan=kesiangan+$kesiangan, menit=menit+$menit WHERE nis='$nis'";
        mysqli_query($con, $query_update);
        echo "sukses";
    } else {
        echo "gagal";
    }
    exit;
}

include "header.php";
?>

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
  /* Reset dasar agar layout konsisten di PC & HP */
  * { box-sizing: border-box; margin: 0; padding: 0; }
  
  body {
    background-color: #f3f4f6;
    font-family: system-ui, -apple-system, sans-serif;
    color: #1f2937;
    padding: 20px 12px;
  }

  /* Container Utama Tengah */
  .app-container {
    max-width: 550px;
    margin: 40px auto;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
  }

  /* Header Form Premium dengan Gradasi */
  .app-header {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
    padding: 24px;
    text-align: center;
  }
  .app-header h5 { font-size: 1.25rem; font-weight: 700; letter-spacing: -0.5px; }

  .app-body { padding: 24px; }

  /* Grup Input */
  .form-group { margin-bottom: 20px; }
  .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
  
  .input-text {
    width: 100%;
    padding: 12px 16px;
    font-size: 0.95rem;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    outline: none;
    transition: all 0.2s ease;
    background-color: #fff;
  }
  .input-text:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12); }

  /* Kotak Profil Siswa Readonly (Rapi & Elegan) */
  .profile-box {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
  }
  .profile-row { display: flex; align-items: center; margin-bottom: 10px; font-size: 0.9rem; }
  .profile-row:last-child { margin-bottom: 0; }
  .profile-label { width: 70px; color: #6b7280; font-weight: 500; }
  .profile-value { flex: 1; font-weight: 600; color: #111827; background: transparent; border: none; pointer-events: none; width: 100%; }

  .section-title { font-size: 0.75rem; font-weight: 700; text-uppercase: true; color: #9ca3af; letter-spacing: 1px; margin-bottom: 12px; margin-top: 8px; }

  /* Area Grid Pilihan Tombol Radio Utama */
  .status-grid {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    background-color: #f3f4f6;
    padding: 8px;
    border-radius: 12px;
    margin-bottom: 24px;
  }

  /* Desain Kustom Kotak Radio (Bisa Di-klik Luas) */
  .custom-radio {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    color: #374151;
    user-select: none;
    transition: all 0.15s ease;
  }
  .custom-radio:hover { background-color: #f9fafb; border-color: #d1d5db; }
  .custom-radio input { scale: 1.15; cursor: pointer; }
  
  /* Saat Radio Aktif Terpilih */
  .custom-radio:has(input:checked) {
    background-color: #eff6ff;
    border-color: #3b82f6;
    color: #1d4ed8;
  }

  /* Kotak Form baris khusus Catatan / Poin */
  .sub-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    background: #fff;
    transition: border-color 0.2s;
  }
  .sub-card:has(input[type="radio"]:checked) { border-color: #10b981; }
  .sub-card.danger-zone:has(input[type="radio"]:checked) { border-color: #ef4444; }
  .sub-card.warning-zone:has(input[type="radio"]:checked) { border-color: #f59e0b; }

  .sub-card-header { display: flex; align-items: center; margin-bottom: 12px; font-weight: 700; font-size: 0.95rem; }
  .sub-card-header.success { color: #059669; }
  .sub-card-header.danger { color: #dc2626; }
  .sub-card-header.warning { color: #d97706; }
  .sub-card-header input { margin-right: 8px; scale: 1.15; }

.row-inputs { 
  display: flex; 
  gap: 12px; 
  width: 100%; 
}
.flex-main { 
  flex: 2; 
  min-width: 0; /* Memaksa input di dalamnya mengalah */
}
.flex-side { 
  flex: 1; 
  min-width: 0; /* Memaksa input di dalamnya mengalah */
}


  /* Input Kombinasi Group Akhir */
  .input-group-custom { display: flex; align-items: center; width: 100%; border: 1px solid #d1d5db; border-radius: 8px; overflow: hidden; }
  .input-group-custom span { background: #f3f4f6; color: #6b7280; padding: 10px 12px; font-size: 0.85rem; font-weight: 500; border-right: 1px solid #d1d5db; }
  .input-group-custom span.right-side { border-right: none; border-left: 1px solid #d1d5db; }
  .input-group-custom input { flex: 1; border: none; padding: 10px; outline: none; font-size: 0.9rem; text-align: center; }

  /* Tombol Simpan Besar Efek Hover Premium */
  .btn-submit {
    width: 100%;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    border: none;
    padding: 14px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 4px 6px -1px rgba(29, 78, 216, 0.2);
    transition: all 0.2s ease;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
  }
  .btn-submit:hover { opacity: 0.95; transform: translateY(-1px); box-shadow: 0 10px 15px -3px rgba(29, 78, 216, 0.25); }
  .btn-submit:active { transform: translateY(0); }
  .btn-submit:disabled { background: #9ca3af; cursor: not-allowed; transform: none; box-shadow: none; }

  /* Desain Alert Kustom (Sukses / Gagal) */
  .alert-custom {
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    animation: fadeIn 0.2s ease;
  }
  .alert-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
  .alert-success { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
  .alert-danger { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

  /* Loading Spinner murni tanpa library */
  .spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 0.8s linear infinite;
  }

  @keyframes spin { to { transform: rotate(360deg); } }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

  /* Aturan Khusus Layar HP Kompak */
  @media (max-width: 480px) {
    body { padding: 10px 6px; }
    .app-container { margin: 10px auto; border-radius: 12px; }
    .app-body { padding: 16px; }
    .status-grid { flex-direction: column; gap: 8px; }
    .row-inputs { flex-direction: column; gap: 8px; }
    .flex-side { width: 100%; }
  }
</style>

<div class="app-container">
    <!-- Header Form Modern -->
    <div class="app-header">
        <h5>Form Pencatatan Absensi & Poin Siswa</h5>
    </div>

    <div class="app-body">
        <form id="integerForm" method="POST" action="" onsubmit="simpanDataForm(event)">

            <!-- Kolom Pencarian Siswa -->
            <div class="form-group">
                <label class="form-label" for="pencarian_siswa">Cari Nama Siswa</label>
                <input type="text" class="input-text" id="pencarian_siswa" placeholder="Ketik nama atau kelas..." list="list_siswa" oninput="input_nama_datalist(this)" autocomplete="off">
                <input type="hidden" name="id_siswa" id="id_siswa_hidden">

                <datalist id="list_siswa">
                  <?php 
                  $result = mysqli_query($con, "SELECT * FROM data_siswa ORDER BY nama_siswa ASC");   
                  $script_buffer = ""; 

                  while ($row = mysqli_fetch_array($result)) {    
                      $tampilan_opsi = $row['nama_siswa'] . " - " . $row['kelas'];
                      echo '<option value="' . htmlspecialchars($tampilan_opsi) . '" data-id="' . $row['id_rekap'] . '">';    
                      
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

            <!-- Tampilan Ringkas Profil Siswa Terpilih (Readonly) -->
            <div class="profile-box">
                <div class="profile-row">
                    <div class="profile-label">Nama</div>
                    <input type="text" name="nama_siswa" class="profile-value" id="nama_siswa" readonly placeholder="-">
                </div>
                <div class="profile-row">
                    <div class="profile-label">NIS</div>
                    <input type="text" name="nis" class="profile-value" id="nis" readonly placeholder="-">
                </div>
                <div class="profile-row">
                    <div class="profile-label">Kelas</div>
                    <input type="text" name="kelas" class="profile-value" id="kelas" readonly placeholder="-">
                </div>
            </div>

            <div class="section-title">Kategori Kehadiran</div>
            
            <!-- Grid Pilihan Status Absensi Utama -->
            <div class="status-grid">
                <label class="custom-radio">
                    <input type="radio" name="bolos" value="1" class="bisa-batal"> Bolos
                </label>
                <label class="custom-radio">
                    <input type="radio" name="dispen" value="1" class="bisa-batal"> Dispen
                </label>
                <label class="custom-radio">
                    <input type="radio" name="dispen2" value="1" class="bisa-batal"> Izin Keluar
                </label>
            </div>

            <div class="section-title">Catatan Khusus & Poin</div>

            <!-- Kotak Opsi Penghargaan -->
            <div class="sub-card">
                <div class="sub-card-header success">
                    <input type="radio" name="penghargaan" value="1" id="opt_penghargaan" class="bisa-batal">
                    <label style="cursor:pointer;" for="opt_penghargaan">Penghargaan</label>
                </div>
                <div class="row-inputs">
                    <div class="flex-main">
                        <input id="jenis_penghargaan" type="text" name="jenis_penghargaan" class="input-text" style="padding: 9px 12px; font-size: 0.9rem;" placeholder="Nama / Jenis Penghargaan">
                    </div>
                    <div class="flex-side">
                        <div class="input-group-custom">
                            <span>Poin</span>
                            <input id="poin_penghargaan" type="number" name="poin_penghargaan" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kotak Opsi Pelanggaran -->
            <div class="sub-card danger-zone">
                <div class="sub-card-header danger">
                    <input type="radio" name="pelanggaran" value="1" id="opt_pelanggaran" class="bisa-batal">
                    <label style="cursor:pointer;" for="opt_pelanggaran">Pelanggaran</label>
                </div>
                <div class="row-inputs">
                    <div class="flex-main">
                        <input id="jenis_pelanggaran" type="text" name="jenis_pelanggaran" class="input-text" style="padding: 9px 12px; font-size: 0.9rem;" placeholder="Nama / Jenis Pelanggaran">
                    </div>
                    <div class="flex-side">
                        <div class="input-group-custom">
                            <span>Poin</span>
                            <input id="poin_pelanggaran" type="number" name="poin_pelanggaran" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kotak Opsi Kesiangan -->
            <div class="sub-card warning-zone" style="margin-bottom: 24px;">
                <div class="sub-card-header warning">
                    <input type="radio" name="kesiangan" value="1" id="opt_kesiangan" class="bisa-batal">
                    <label style="cursor:pointer;" for="opt_kesiangan">Kesiangan</label>
                </div>
                <div class="input-group-custom">
                    <span>Durasi Keterlambatan</span>
                    <input id="menit" type="number" name="menit" placeholder="0">
                    <span class="right-side">Menit</span>
                </div>
            </div>

            <!-- Wadah Notifikasi Hasil Kiriman Data -->
            <div id="notifikasi_ajax" style="margin-bottom: 16px;"></div>

            <!-- Tombol Kirim Form -->
            <button type="submit" class="btn-submit" id="submite">
                Simpan Rekam Data
            </button>

        </form>
    </div>
</div>
<script type="text/javascript">
  // Inisialisasi map global untuk pencarian siswa
  var dataSiswaMap = {};
  <?php echo $script_buffer; ?>

  // Fungsi mencocokkan data dari pilihan list_siswa
  function input_nama_datalist(inputElement) {
      var kataKunci = inputElement.value;
      var listOpsi = document.querySelectorAll('#list_siswa option');
      var idTerpilih = null;

      for (var i = 0; i < listOpsi.length; i++) {
          if (listOpsi[i].value === kataKunci) {
              idTerpilih = listOpsi[i].getAttribute('data-id');
              break;
          }
      }

      if (idTerpilih && dataSiswaMap[idTerpilih]) {
          var dataSiswa = dataSiswaMap[idTerpilih];
          document.getElementById('id_siswa_hidden').value = dataSiswa.id;
          document.getElementById('nama_siswa').value = dataSiswa.nama;
          document.getElementById('nis').value = dataSiswa.nis;
          document.getElementById('kelas').value = dataSiswa.kelas;
      } else {
          document.getElementById('id_siswa_hidden').value = "";
          document.getElementById('nama_siswa').value = "-";
          document.getElementById('nis').value = "-";
          document.getElementById('kelas').value = "-";
      }
  }

  // Fungsi helper untuk menampilkan alert kustom mandiri yang indah
  function tampilkanNotifikasi(pesan, tipe) {
      var notifElement = document.getElementById('notifikasi_ajax');
      notifElement.className = "alert-custom alert-" + tipe;
      notifElement.innerHTML = pesan;
  }

  // Fungsi AJAX Simpan Form Data
  function simpanDataForm(event) {
      event.preventDefault();
      var form = document.getElementById('integerForm');
      var submitBtn = document.getElementById('submite');
      var formData = new FormData(form);
      formData.append('simpan_ajax', '1');
      
      submitBtn.disabled = true;
      tampilkanNotifikasi('<div class="spinner"></div>&nbsp; Sedang memproses data...', 'warning');

      fetch("", { method: "POST", body: formData })
      .then(response => response.text())
      .then(data => {
          submitBtn.disabled = false;
          var responText = data.trim();

          if(responText === "sukses") {
              tampilkanNotifikasi('&#10004; Data siswa berhasil disimpan ke database!', 'success');
              form.reset();
              document.getElementById('id_siswa_hidden').value = "";
              document.getElementById('nama_siswa').value = "-";
              document.getElementById('nis').value = "-";
              document.getElementById('kelas').value = "-";
              
              document.querySelectorAll('.bisa-batal').forEach(el => {
                  el.checked = false;
                  el.previousChecked = false;
              });
              
              setTimeout(function() { 
                  var notif = document.getElementById('notifikasi_ajax');
                  notif.innerHTML = "";
                  notif.className = "";
              }, 4000);

          } else if(responText === "kosong_siswa") {
              tampilkanNotifikasi('&#9888; Gagal: Silakan cari dan pilih data siswa terlebih dahulu.', 'danger');
          } else if(responText === "kosong_record") {
              tampilkanNotifikasi('&#9888; Gagal: Pilih minimal satu kategori absensi atau catatan poin.', 'danger');
          } else {
              tampilkanNotifikasi('&#10006; Gagal menyimpan, periksa query atau koneksi database.', 'danger');
          }
      })
      .catch(error => {
          submitBtn.disabled = false;
          tampilkanNotifikasi('&#10006; Terjadi masalah jaringan internet Anda.', 'danger');
      });
  }

  // Logika pembatalan radio button (Bisa-Batal) saat diklik dua kali
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
