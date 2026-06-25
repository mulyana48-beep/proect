<?php
// 1. Jalankan session di paling atas SEBELUM ada output HTML apa pun
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Jika user sudah login, langsung alihkan ke home.php
if (isset($_SESSION['username'])) {
    header("Location: form_catat.php");
    exit();
}

// 3. Sertakan file konfigurasi database
include "config.php";

// 4. Baca file konfigurasi JSON untuk identitas sekolah
$file_json = file_get_contents('pengaturan.json');
$data_sekolah = json_decode($file_json, true); 

if (!$data_sekolah) {
    $data_sekolah = [
        'nama_sekolah' => '-', // Nilai default jika json kosong
        'tahun' => '-',
        'kontak' => '-'
    ];
}

// 5. Proses Logika Login POST
$error_message = "";
if (isset($_POST['username'])){
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($con, $username);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);
    
    $query = "SELECT * FROM `admin` WHERE username='$username' AND password='$password'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    $rows = mysqli_num_rows($result);
    
    if($rows == 1){
        $user_data = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        $_SESSION['level'] = $user_data['level']; 
        
        header("Location: form_catat.php");
        exit();
    } else {
        $error_message = "<div class='alert-danger-custom'>
                <h4 style='margin-top:0;'>Username atau password salah!</h4>
                Silakan <a href='index.php'>Coba Lagi</a>
              </div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico?v=2">
  
  <!-- PERBAIKAN: Memperbaiki sintaks kurung htmlspecialchars -->
  <title>Apdis <?php echo htmlspecialchars($data_sekolah['nama_sekolah']); ?></title>
  
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/AdminLTE.min.css">

  

  <style>
    body.login-page {
      background: linear-gradient(0deg, #525252 30%, #000000 100%) !important;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }
    .login-box {
      margin: 0 auto;
      width: 100%;
      max-width: 400px;
      padding: 10px;
    }
    .login-logo a {
      color: #ffffff !important;
      font-weight: 300;
      letter-spacing: 0.5px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .login-logo b {
      font-weight: 700;
      display: block;
    }
    .login-logo small {
      font-size: 16px;
      display: block;
      margin-top: 5px;
      opacity: 0.8;
    }
    .login-box-body {
      background: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      border: none;
    }
    .form-group-custom {
      margin-bottom: 20px;
    }
    .form-control-custom {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ff6112;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }
    .form-control-custom:focus {
      border-color: #2a5298;
      box-shadow: 0 0 8px rgba(42, 82, 152, 0.2);
      outline: none;
    }
    .btn-custom {
      width: 100%;
      background: #2a5298;
      color: #ffffff;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .btn-custom:hover {
      background: #FF4500; 
      box-shadow: 0 4px 12px rgba(255, 69, 0, 0.3);
    }
    .alert-danger-custom {
      background-color: #fce8e6;
      color: #a8071a;
      padding: 15px;
      border-radius: 8px;
      border: 1px solid #ffccc7;
      text-align: center;
      margin-bottom: 20px;
    }
    .alert-danger-custom a {
      color: #1890ff;
      font-weight: bold;
      text-decoration: none;
    }
    .login-footer {
      text-align: center;
      margin-top: 20px;
      color: rgba(255, 255, 255, 0.6);
      font-size: 13px;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  
  <div class="login-logo">
    <img src="images/logo1.png" style="max-width: 90px; height: auto; display: block; margin: 0 auto 10px auto;">
    <a href="index.php">
      <b>APLIKASI DISIPLIN</b>
      <?php echo htmlspecialchars($data_sekolah['nama_sekolah']); ?>
    </a>
  </div>
  
  <div class="login-box-body">
    <?php 
    // Tampilkan pesan error jika login gagal
    if (!empty($error_message)) { 
        echo $error_message; 
    } 
    ?>

    <form action="" method="post" name="login">
      <div class="form-group-custom">
        <input type="text" name="username" class="form-control-custom" placeholder="Username" required autocomplete="off" />
      </div>
      <div class="form-group-custom">
        <input type="password" name="password" class="form-control-custom" placeholder="Password" required />
      </div>
      <button name="submit" type="submit" class="btn-custom">Masuk Ke Aplikasi</button>
    </form>
  </div>

  <div class="login-footer">
    &copy; <?php echo date('Y');?> <?php echo htmlspecialchars($data_sekolah['kontak']); ?>
  </div>

</div>

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
