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
        <a href="profil_dosen.php" class="sidebar-link">
          <i class="lni lni-user"></i>
          <span>Profil Diri</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#multi" aria-expanded="false" aria-controls="multi">
          <i class="lni lni-files"></i>
          <span>Lihat Rotasi</span>
        </a>
        <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi-two" aria-expanded="false" aria-controls="multi-two">
              Rotasi Stase
            </a>
            <ul id="multi-two" class="sidebar-dropdown list-unstyled collapse">
              <li class="sidebar-item">
                <a href="view_rotasi_kelp.php" class="sidebar-link">Rotasi Angkatan</a>
              </li>
              <li class="sidebar-item">
                <a href="view_rotasi_individu.php" class="sidebar-link">Rotasi Individu</a>
              </li>
            </ul>
          <li class="sidebar-item">
            <a href="rotasi_internal_stase_search.php" class="sidebar-link">
              <span>Rotasi Internal</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#multi-three" aria-expanded="false" aria-controls="multi-three">
          <i class="lni lni-pencil"></i>
          <span>Edit Rotasi</span>
        </a>
        <ul id="multi-three" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi-four" aria-expanded="false" aria-controls="multi-four">
              Rekap Kelompok
            </a>
            <ul id="multi-four" class="sidebar-dropdown list-unstyled collapse">
              <li class="sidebar-item">
                <a href="rotasi_kelompok.php" class="sidebar-link">Rotasi Normal</a>
              </li>
              <li class="sidebar-item">
                <a href="rotasi_kelpmanual.php" class="sidebar-link">Rotasi Tambahan</a>
              </li>
              <li class="sidebar-item">
                <a href="rotasi_kelpdelete.php" class="sidebar-link">Hapus Rotasi</a>
              </li>
            </ul>
        </ul>
        <ul id="multi-three" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi-five" aria-expanded="false" aria-controls="multi-five">
              Rotasi Individu
            </a>
            <ul id="multi-five" class="sidebar-dropdown list-unstyled collapse">
              <li class="sidebar-item">
                <a href="rotasi_individu.php" class="sidebar-link">Rotasi Normal</a>
              </li>
              <li class="sidebar-item">
                <a href="rotasi_indmanual_search.php" class="sidebar-link">Rotasi Tambahan</a>
              </li>
              <li class="sidebar-item">
                <a href="rotasi_inddelete_search.php" class="sidebar-link">Hapus Rotasi</a>
              </li>
            </ul>
        </ul>
      </li>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#multi-six" aria-expanded="false" aria-controls="multi-six">
          <i class="lni lni-folder"></i>
          <span>Rekap</span>
        </a>
        <ul id="multi-six" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi-seven" aria-expanded="false" aria-controls="multi-seven">
              Rekap Umum
            </a>
            <ul id="multi-seven" class="sidebar-dropdown list-unstyled collapse">
              <li class="sidebar-item">
                <a href="rekap_umum_admin.php" class="sidebar-link">Rekap Stase</a>
              </li>
              <li class="sidebar-item">
                <a href="rekap_umumeval_admin.php" class="sidebar-link">Evaluasi Harian</a>
              </li>
              <li class="sidebar-item">
                <a href="rekap_umumeval_stase_search.php" class="sidebar-link">Evaluasi Stase</a>
              </li>
              <li class="sidebar-item">
                <a href="capaian_umum_search.php" class="sidebar-link">Ketuntasan/Grade</a>
              </li>
              <li class="sidebar-item">
                <a href="nilai_bag_umum_search.php" class="sidebar-link">Rekap Nilai Bagian</a>
              </li>
              <li class="sidebar-item">
                <a href="nilai_akhir_umum_search.php" class="sidebar-link">Rekap Nilai Akhir</a>
              </li>
            </ul>
        </ul>
        <ul id="multi-six" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi-eight" aria-expanded="false" aria-controls="multi-eight">
              Rekap Individu
            </a>
            <ul id="multi-eight" class="sidebar-dropdown list-unstyled collapse">
              <li class="sidebar-item">
                <a href="rekap_individu_search.php" class="sidebar-link">Rekap Stase</a>
              </li>
              <li class="sidebar-item">
                <a href="rekap_evaluasi_search.php" class="sidebar-link">Evaluasi Harian</a>
              </li>
              <li class="sidebar-item">
                <a href="rekap_evaluasi_stase_search.php" class="sidebar-link">Evaluasi Stase</a>
              </li>
              <li class="sidebar-item">
                <a href="capaian_individu_search.php" class="sidebar-link">Ketuntasan/Grade</a>
              </li>
              <li class="sidebar-item">
                <a href="nilai_bag_search.php" class="sidebar-link">Cetak Nilai Bagian</a>
              </li>
              <li class="sidebar-item">
                <a href="nilai_akhir_search.php" class="sidebar-link">Nilai Akhir Bagian</a>
              </li>
            </ul>
        </ul>
      </li>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#multi-nine" aria-expanded="false" aria-controls="multi-nine">
          <i class="fa-solid fa-people-roof"></i>
          <span>Manajemen User</span>
        </a>
        <ul id="multi-nine" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi-ten" aria-expanded="false" aria-controls="multi-ten">
              Tambah User
            </a>
            <ul id="multi-ten" class="sidebar-dropdown list-unstyled collapse">
              <li class="sidebar-item">
                <a href="tambah_user_mhsw.php" class="sidebar-link">User Mahasiswa</a>
              </li>
              <li class="sidebar-item">
                <a href="tambah_user_dosen.php" class="sidebar-link">User Dosen/Residen</a>
              </li>
            </ul>
        </ul>
        <ul id="multi-nine" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi-eleven" aria-expanded="false" aria-controls="multi-eleven">
              Edit User
            </a>
            <ul id="multi-eleven" class="sidebar-dropdown list-unstyled collapse">
              <li class="sidebar-item">
                <a href="edit_user_mhsw.php" class="sidebar-link">User Mahasiswa</a>
              </li>
              <li class="sidebar-item">
                <a href="edit_user_dosen.php" class="sidebar-link">User Dosen/Residen</a>
              </li>
            </ul>
        </ul>
        <ul id="multi-nine" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi-twelve" aria-expanded="false" aria-controls="multi-twelve">
              Import User
            </a>
            <ul id="multi-twelve" class="sidebar-dropdown list-unstyled collapse">
              <li class="sidebar-item">
                <a href="import_user_mhsw.php" class="sidebar-link">User Mahasiswa</a>
              </li>
              <li class="sidebar-item">
                <a href="import_user_dosen.php" class="sidebar-link">User Dosen/Residen</a>
              </li>
            </ul>
          <li class="sidebar-item">
            <a href="update_nim_koas.php" class="sidebar-link">
              <span>Update NIM Koas</span>
            </a>
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
            <a href="update_admin.php" class="sidebar-link">Update Profil</a>
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