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
  <aside id="sidebar" class="expand" style="position: fixed; height:100vh">
    <div class="d-flex">
      <div class="sidebar-logo">
        <a href="#">E-LOGBOOK KOAS KEDOKTERAN</a>
      </div>
    </div>
    <ul class="sidebar-nav">
      <li class="sidebar-item">
        <a href="../menu_awal.php" class="sidebar-link">
          <i class="lni lni-home"></i>
          <span>Beranda</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="../informasi.php" class="sidebar-link">
          <i class="lni lni-information"></i>
          <span>Informasi</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="../biodata.php" class="sidebar-link">
          <i class="lni lni-user"></i>
          <span>Biodata</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="../rotasi_internal.php" class="sidebar-link">
          <i class="lni lni-clipboard"></i>
          <span>Rotasi Stase</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="../rotasi_internal_stase.php" class="sidebar-link">
          <i class="lni lni-book"></i>
          <span>Rotasi Internal</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#logbook-jurnal" aria-expanded="false" aria-controls="logbook-jurnal">
          <i class="lni lni-library"></i>
          <span>Logbook Jurnal</span>
        </a>
        <ul id="logbook-jurnal" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="../cek_logbook.php" class="sidebar-link">Cek Jurnal</a>
          </li>
          <li class="sidebar-item">
            <a href="../isi_logbook.php" class="sidebar-link">Isi Jurnal</a>
          </li>
          <li class="sidebar-item">
            <a href="../rekap_individu.php" class="sidebar-link">Rekap Jurnal</a>
          </li>
        </ul>
      </li>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#nilai-bagian" aria-expanded="false" aria-controls="nilai-bagian">
          <i class="lni lni-star-fill"></i>
          <span>Nilai Bagian</span>
        </a>
        <ul id="nilai-bagian" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="../penilaian_bagian.php" class="sidebar-link">Penilaian Bagian</a>
          </li>
          <li class="sidebar-item">
            <a href="../nilai_akhir_search.php" class="sidebar-link">Nilai Akhir</a>
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
            <a href="../edit_usermhsw_action.php" class="sidebar-link">Update Profil</a>
          </li>
        </ul>
      </li>

    </ul>

    <div class="sidebar-footer">
      <a href="../logout.php" class="sidebar-link">
        <i class="lni lni-exit"></i>
        <span>Logout</span>
      </a>
    </div>
  </aside>

  <!-- Script Javascript -->

  <script src="javascript/script1.js"></script>
  <!-- <script>
    document.addEventListener('contextmenu', function(e) {
      e.preventDefault();
    });
  </script>
  <script>
    document.addEventListener('keydown', function(e) {
      if (e.key === 'F12' ||
        (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'C' || e.key === 'J')) ||
        (e.ctrlKey && e.key === 'U')) {
        e.preventDefault();
      }
    });
  </script> -->
</body>


</html>