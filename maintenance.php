<?php
error_reporting("E_ALL ^ E_NOTICE");
if ($_COOKIE["user_login"] == "" or empty($_COOKIE["user_login"]) or $_GET["st"] == "new") {
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-line Logbook Koas Pendidikan Dokter FK-UNDIP</title>
    <link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style/maintenance.css">
  </head>

  <body>
    <div class="login-card">
      <img src="images/undipsolid.png" alt="Universitas Diponegoro Logo">
      <h4>
        E-LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER<br>
        FAKULTAS KEDOKTERAN<br>
        UNIVERSITAS DIPONEGORO<br>
        TAHUN <?php include "config.php";
              echo htmlspecialchars($tahun, ENT_QUOTES, 'UTF-8'); ?>
      </h4>
      <h1><i class="fas fa-exclamation-triangle"></i> PERHATIAN</h1>
      <h4>
        E-LOGBOOK SEDANG DALAM PROSES MAINTENANCE<br>
        <span style="color:#34495e ;">MULAI</span> <span class="maintenance-dates">26 DESEMBER 2023</span> <span style="color: #34495e;">HINGGA</span> <span class="maintenance-dates">31 DESEMBER 2023</span><br>
        SISTEM AKAN NORMAL KEMBALI MULAI <span class="maintenance-dates">1 JANUARI 2024</span>
      </h4>
      <h1 class="thanks">TERIMA KASIH</h1>
    </div>
  </body>

  </html>
<?php
}
?>