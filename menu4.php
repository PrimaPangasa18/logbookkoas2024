<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Logbook Koas Pendidikan Dokter FK-UNDIP</title>
  <link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
  <link rel="stylesheet" href="style1.css" />

  <!-- Link Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <!-- Link CDN Icon -->
  <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
  <?php
  include "clear_dummy.php";
  $pin = rand(1000, 9999);
  $qr = acakstring(9);
  $reset_pin_qr = mysqli_query($con, "UPDATE `dosen` SET `pin`='$pin',`qr`='$qr' WHERE `nip`=$_COOKIE[user]");
  ?>
  <aside id="sidebar">
    <div class="d-flex">
      <button id="toggle-btn" type="button">
        <i class="lni lni-grid-alt"></i>
      </button>
      <div class="sidebar-logo">
        <a href="#">E-LOGBOOK KOAS KEDOKTERAN</a>
      </div>
    </div>
    <ul class="sidebar-nav">
      <li class="sidebar-item">
        <a href="menu_awal.php" class="sidebar-link">
          <i class="lni lni-home"></i>
          <span>Beranda</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="informasi.php" class="sidebar-link">
          <i class="lni lni-information"></i>
          <span>Informasi</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="profil_dosen.php" class="sidebar-link">
          <i class="lni lni-user"></i>
          <span>Profil Diri</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="generate_pin.php" class="sidebar-link">
          <i class="lni lni-key"></i>
          <span>Generate OTP</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="generate_qr.php" class="sidebar-link">
          <i class="fa-solid fa-qrcode"></i>
          <span>Generate QR</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#kegiatan" aria-expanded="false" aria-controls="kegiatan">
          <i class="lni lni-pencil-alt"></i>
          <span>Kegiatan</span>
        </a>
        <ul id="kegiatan" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="filter_kegiatan_dosen.php" class="sidebar-link">Daftar Kegiatan</a>
          </li>
          <li class="sidebar-item">
            <a href="filter_rekap_kegiatan.php" class="sidebar-link">Rekap Kegiatan</a>
          </li>
          <li class="sidebar-item">
            <a href="penilaian_bag_dosen.php" class="sidebar-link">Penilaian Bagian</a>
          </li>
        </ul>
      </li>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#user-interface" aria-expanded="false" aria-controls="user-interface">
          <i class="lni lni-users"></i>
          <span>User Interface</span>
        </a>
        <ul id="user-interface" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="edit_userdosen_action.php" class="sidebar-link">Update Profil</a>
          </li>
        </ul>
      </li>
    </ul>

    <div class="sidebar-footer">
      <a href="logout.php" class="sidebar-link">
        <i class="lni lni-exit"></i>
        <span>Logout</span>
      </a>
    </div>
  </aside>

  <!-- Script Javascript -->

  <script src="javascript/script1.js"></script>
</body>


</html>