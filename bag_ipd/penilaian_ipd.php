<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Penilaian Bagian IPD Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="../images/undipsolid.png">
	<link rel="stylesheet" href="../style/style1.css" />
	<link rel="stylesheet" href="../style/buttonotoup2.css">

	<!-- Link Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
	<!-- Link CDN Icon -->
	<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
	<div class="wrapper">
		<?php

		include "../config.php";
		include "../fungsi.php";

		error_reporting("E_ALL ^ E_NOTICE");

		if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
			echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
		} else {
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 5) {
				if ($_COOKIE['level'] == 5) {
					include "menu5.php";
				}

				$nama = isset($_COOKIE['nama']) ? $_COOKIE['nama'] : 'User';
				$gelar = isset($_COOKIE['gelar']) ? $_COOKIE['gelar'] : '';
			} else
				echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
		}
		?>
		<?php
		if ($_COOKIE['level'] != 5) {
			$data_nim = $_GET['nim'];
			$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_nim'"));
		} else {
			$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		}

		// Menentukan path gambar
		$foto_path = "../foto/" . $data_mhsw['foto'];
		$default_foto = "../foto/profil_blank.png";

		// Mengecek apakah file gambar ada
		if (!file_exists($foto_path) || empty($data_mhsw['foto'])) {
			$foto_path = $default_foto;
		}
		?>
		<!-- End Sidebar -->
		<div class="main">
			<!-- Start Navbar -->
			<nav class="navbar navbar-expand px-4 py-3" style="background-color: #006400; ">
				<form action="#" class="d-none d-sm-inline-block">
					<div class="input-group input-group-navbar">
						<img src="../images/undipsolid.png" alt="" style="width: 45px;">
					</div>
				</form>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item dropdown d-flex align-item-center">
							<span class="navbar-text me-2" style="color: white;">Halo, <?php echo $nama . ' , <span class="gelar" >' . $gelar . '</span>'; ?></span>
							<a href="#" class="nav-icon pe-md-0" data-bs-toggle="dropdown">
								<img src="<?php echo $foto_path; ?>" class="avatar img-fluid rounded-circle" alt="" style=" width:40px; height:40px" />
							</a>
							<div class="dropdown-menu dropdown-menu-end rounded">

								<div class="dropdown-menu dropdown-menu-end rounded"></div>
								<a href="../logout.php" class="dropdown-item">
									<i class="lni lni-exit"></i>
									<span>Logout</span>
								</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->

			<!-- Main Content -->
			<main class="content px-3 py-4" style="background-image: url('images/undip_watermark_color.png'), url('images/undip_watermark_color.png'); ">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) ILMU PENYAKIT DALAM</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">FORM PENILAIAN KEPANITERAAN (STASE) ILMU PENYAKIT DALAM</h2>
						<br>
						<?php
						$id_stase = "M091";
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
						$cek_stase = mysqli_num_rows($data_stase_mhsw);
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
						echo "<br>";
						echo "<center>";
						echo "<table class=\"table table-bordered\" style=\"width:50%\" id=\"freeze\" >";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td style=\"width:50%;\"><strong>Kepaniteraan (STASE)</strong></td>
						<td style=\"width:50%;font-weight:600; color:darkgreen;\">: $data_stase[kepaniteraan]</td></tr>";
						if ($cek_stase >= 1) {
							$tgl_mulai = tanggal_indo($datastase_mhsw['tgl_mulai']);
							$mulai = date_create($datastase_mhsw['tgl_mulai']);
							$tgl_selesai = tanggal_indo($datastase_mhsw['tgl_selesai']);
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
							<td><strong>Tanggal mulai kepaniteraan (STASE)</strong></td>
							<td style=\"font-weight:600; color:darkblue;\">: $tgl_mulai</td></tr>";
							echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
							<td><strong>Tanggal selesai kepaniteraan (STASE)</strong></td>
							<td style=\"font-weight:600; color:darkblue;\">: $tgl_selesai</td></tr>";
						} else {
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
							<td><strong>Status Kepaniteraan (STASE)</strong></td>
							<td style=\"font-weight:600;\">: <font style=\"color:red\">BELUM AKTIF</font></td></tr>";
						}
						echo "</table><br><br>";
						if ($cek_stase >= 1) {
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#minicex\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Mini-Cex</a>
								<a href=\"#kasus\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Penyajian Kasus Besar</a>
								<a href=\"#ujian\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Ujian Akhir</a>
								<br><br>
								<a href=\"#test\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Preview Nilai Ujian Tulis MCQ</a>
								
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian MINI-CEX
							echo "<br><br>";
							echo "<center>";
							echo "<a id=\"minicex\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Mini-Cex</a><br><br>";
							echo "<div class=\"alert alert-warning\" style=\"width:60%; font-size:0.875em; text-align: left; \"><span class=\"text-danger\" style=\"font-weight:700;\">Catatan:</span><br><span style=\"font-weight:600;\">- Pengisian wajib untuk penilaian Mini-Cex adalah 7 (tujuh) kali.<br>- Nilai rata-rata adalah jumlah total nilai Mini-Cex dibagi 7 (tujuh).<br>- Untuk cetak, minimal 1 (satu) penilaian Mini-Cex telah disetujui Dosen Penilai.</span></div><br><br>";
							echo "</center>";
							$nilai_minicex = mysqli_query($con, "SELECT * FROM `ipd_nilai_minicex` WHERE `nim`='$_COOKIE[user]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			     				 <th style=\"width:5%;text-align:center;\">No</th>
			     				 <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			     				 <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			     				 <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			     				 <th style=\"width:15%;text-align:center;\">Status Approval</th>
			     				 </thead>";
							$cek_nilai_minicex = mysqli_num_rows($nilai_minicex);
							if ($cek_nilai_minicex < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_minicex = mysqli_fetch_array($nilai_minicex)) {
									$tanggal_isi = tanggal_indo($data_minicex['tgl_isi']);
									$tanggal_ujian = tanggal_indo($data_minicex['tgl_ujian']);
									$tanggal_approval = tanggal_indo($data_minicex['tgl_approval']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">";
									echo "Penilaian Mini-Cex<br>";
									echo "<span style=\"color:green;\">Ruangan/Bangsal: $data_minicex[ruangan]</span><br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>";
									echo "<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:blue;\">Tanggal Penilaian: $tanggal_ujian</span><br>";
									echo "Rata-Rata Nilai: $data_minicex[nilai_rata]</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";

									if ($data_minicex['status_approval'] == '0')
										echo " <a href=\"approve_minicex.php?id=$data_minicex[id]\">
   												<button type=\"button\"  class=\"btn btn-primary btn-sm\" name=\"approve_$data_minicex[id]\" >
    											<i class=\"fas fa-notes-medical me-2\"></i> APPROVE
												</button></a>";
									echo "</td>";
									echo "<td align=center>";
									if ($data_minicex['status_approval'] == '0') {
										echo "<font style=\"color:red; font-weight:600;\">BELUM DISETUJUI</font><br><br>";
										echo "<a href=\"edit_form_minicex.php?id=$data_minicex[id]\">
       										 <button type=\"button\" class=\"btn btn-warning btn-sm\" name=\"edit_$data_minicex[id]\" value=\"EDIT\">
            								<i class=\"fas fa-edit me-2\"></i> EDIT
       										</button>
      										</a><p><br>";
										echo "<a href=\"preview_form_minicex.php?id=$data_minicex[id]\">
        										<button type=\"button\" class=\"btn btn-primary btn-sm\" name=\"preview_$data_minicex[id]\" value=\"PREVIEW\">
            								<i class=\"fas fa-eye me-2\"></i> PREVIEW
        									</button>
      										</a><p>";
										echo "<a href=\"hapus_form_minicex.php?id=$data_minicex[id]\">
        									<button type=\"button\" class=\"btn btn-danger btn-sm\" name=\"hapus_$data_minicex[id]\" value=\"HAPUS\">
            								<i class=\"fas fa-trash-alt me-2\"></i> HAPUS
        									</button>
      										</a>";
									} else {
										echo "<font style=\"color:darkgreen; font-weight:600;\">DISETUJUI</font><br>";
										echo "<span style=\" font-weight:600;\">per tanggal</span><br>";
										echo "<span style=\"color:darkblue; font-weight:600;\">$tanggal_approval</span>";
										echo "<br><br>
										<a href=\"view_form_minicex.php?id=$data_minicex[id]\">
        										<button type=\"button\" class=\"btn btn-success btn-sm\" name=\"view_$data_minicex[id]\" value=\"VIEW\">
            								<i class=\"fas fa-eye me-2\"></i> VIEW
        									</button>
      										</a>";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table>";
							$cek_all_minicex = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `ipd_nilai_minicex` WHERE `nim`='$_COOKIE[user]'"));
							$cek_approved_minicex = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `ipd_nilai_minicex` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));

							if ($cek_all_minicex < 7) {
								echo "<br><center><a href=\"tambah_minicex.php\">
								<button type=\"button\" style=\"margin-right: 10px;\" class=\"btn btn-success\" name=\"tambah\" value=\"TAMBAH\"><i class=\"fas fa-plus\"></i> TAMBAH</button>
								</a>";

								if ($cek_approved_minicex > 0) {
									echo "<a href=\"cetak_minicex.php\" target=\"_BLANK\">";
									echo "<button type=\"button\" style=\"margin-right: 10px;\" class=\"btn btn-primary\" name=\"cetak\" value=\"CETAK\"><i class=\"fas fa-print\"></i> CETAK</button>";
									echo "</a>";
								}
							} else {
								if ($cek_approved_minicex > 0) {
									echo "<br><center><a href=\"cetak_minicex.php\" target=\"_BLANK\">";
									echo "<button type=\"button\" style=\"margin-right: 10px;\" class=\"btn btn-primary\" name=\"cetak\" value=\"CETAK\"><i class=\"fas fa-print\"></i> CETAK</button>";
									echo "</a>";
								}
							}
							echo "</center><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";

							echo "<br>";
							//Pengisian Formulir Penilaian Penyajian Kasus Besar
							echo "<a id=\"kasus\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Penyajian Kasus Besar</a><br><br>";
							$nilai_kasus = mysqli_query($con, "SELECT * FROM `ipd_nilai_kasus` WHERE `nim`='$_COOKIE[user]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      					<th style=\"width:5%;text-align:center;\">No</th>
			      					<th style=\"width:15%;text-align:center;\">Tanggal Penyajian</th>
			      					<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      					<th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      					<th style=\"width:15%;text-align:center;\">Status Approval</th>
			     				 	</thead>";
							$cek_nilai_kasus = mysqli_num_rows($nilai_kasus);
							if ($cek_nilai_kasus < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_kasus = mysqli_fetch_array($nilai_kasus)) {
									$tanggal_penyajian = tanggal_indo($data_kasus['tgl_penyajian']);
									$tanggal_approval = tanggal_indo($data_kasus['tgl_approval']);
									$awal_periode = tanggal_indo($data_kasus['tgl_awal']);
									$akhir_periode = tanggal_indo($data_kasus['tgl_akhir']);
									$periode = $awal_periode . " s.d. " . $akhir_periode;

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">
									Penilaian Penyajian Kasus Besar<br>
									<span style=\"color:blue;\">Periode Stase: $periode</span><br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:green;\">Kasus: $data_kasus[kasus]</span><br>";
									echo "Nilai: $data_kasus[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									if ($data_kasus['status_approval'] == '0')
										echo " <a href=\"approve_kasus.php?id=$data_kasus[id]\">
   												<button type=\"button\"  class=\"btn btn-primary btn-sm\" name=\"approve_$data_kasus[id]\" >
    											<i class=\"fas fa-notes-medical me-2\"></i> APPROVE
												</button></a>";
									echo "</td>";
									echo "<td align=center>";
									if ($data_kasus['status_approval'] == '0') {
										echo "<font style=\"color:red; font-weight:600;\">BELUM DISETUJUI</font><br><br>";
										echo "<a href=\"edit_form_kasus.php?id=$data_kasus[id]\">
       										 <button type=\"button\" class=\"btn btn-warning btn-sm\" name=\"edit_$data_kasus[id]\" value=\"EDIT\">
            								<i class=\"fas fa-edit me-2\"></i> EDIT
       										</button>
      										</a><p><br>";
										echo "<a href=\"preview_form_kasus.php?id=$data_kasus[id]\">
        										<button type=\"button\" class=\"btn btn-primary btn-sm\" name=\"preview_$data_kasus[id]\" value=\"PREVIEW\">
            								<i class=\"fas fa-eye me-2\"></i> PREVIEW
        									</button>
      										</a><p>";
										echo "<a href=\"hapus_form_kasus.php?id=$data_kasus[id]\">
        									<button type=\"button\" class=\"btn btn-danger btn-sm\" name=\"hapus_$data_kasus[id]\" value=\"HAPUS\">
            								<i class=\"fas fa-trash-alt me-2\"></i> HAPUS
        									</button>
      										</a>";
									} else {
										echo "<font style=\"color:darkgreen; font-weight:600;\">DISETUJUI</font><br>";
										echo "<span style=\" font-weight:600;\">per tanggal</span><br>";
										echo "<span style=\"color:darkblue; font-weight:600;\">$tanggal_approval</span>";
										echo "<br><br>
										<a href=\"view_form_kasus.php?id=$data_kasus[id]\">
        										<button type=\"button\" class=\"btn btn-success btn-sm\" name=\"view_$data_kasus[id]\" value=\"VIEW\">
            								<i class=\"fas fa-eye me-2\"></i> VIEW
        									</button>
      										</a><p>";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table>";
							echo "<br>";
							echo "<center>";
							if ($cek_nilai_kasus < 1)
								echo "<br><a href=\"tambah_kasus.php\">
								<button type=\"button\" style=\"margin-right: 10px;\" class=\"btn btn-success\" name=\"tambah\" value=\"TAMBAH\"><i class=\"fas fa-plus\"></i> TAMBAH</button>
								</a>";

							$cek_approved_kasus = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `ipd_nilai_kasus` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
							if ($cek_approved_kasus > 0)
								echo "<br><a href=\"cetak_kasus.php\" target=\"_BLANK\">";
							echo "<button type=\"button\" style=\"margin-right: 10px;\" class=\"btn btn-primary\" name=\"cetak\" value=\"CETAK\"><i class=\"fas fa-print\"></i> CETAK</button>";
							echo "</a>";
							echo "</center><br>";

							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//Pengisian Formulir Penilaian Ujian Akhir
							echo "<a id=\"ujian\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian Akhir</a><br><br>";
							$nilai_ujian = mysqli_query($con, "SELECT * FROM `ipd_nilai_ujian` WHERE `nim`='$_COOKIE[user]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      				<th style=\"width:5%;text-align:center;\">No</th>
			      				<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      				<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      				<th style=\"width:25%;text-align:center;\">Supervisor</th>
			      				<th style=\"width:15%;text-align:center;\">Status Approval</th>
			      				</thead>";
							$cek_nilai_ujian = mysqli_num_rows($nilai_ujian);
							if ($cek_nilai_ujian < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_ujian = mysqli_fetch_array($nilai_ujian)) {
									$tanggal_isi = tanggal_indo($data_ujian['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_ujian['tgl_approval']);
									$awal_periode = tanggal_indo($data_ujian['tgl_awal']);
									$akhir_periode = tanggal_indo($data_ujian['tgl_akhir']);
									$periode = $awal_periode . " s.d. " . $akhir_periode;

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_ujian[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Ujian Akhir<br><span style=\"color:blue;\">Periode Stase: $periode</span><br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai Ujian: $data_ujian[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									if ($data_ujian['status_approval'] == '0')
										echo " <a href=\"approve_ujian.php?id=$data_ujian[id]\">
   												<button type=\"button\"  class=\"btn btn-primary btn-sm\" name=\"approve_$data_ujian[id]\" >
    											<i class=\"fas fa-notes-medical me-2\"></i> APPROVE
												</button></a>";
									echo "</td>";
									echo "<td align=center>";
									if ($data_ujian['status_approval'] == '0') {
										echo "<font style=\"color:red; font-weight:600;\">BELUM DISETUJUI</font><br><br>";
										echo "<a href=\"edit_form_ujian.php?id=$data_ujian[id]\">
       										 <button type=\"button\" class=\"btn btn-warning btn-sm\" name=\"edit_$data_ujian[id]\" value=\"EDIT\">
            								<i class=\"fas fa-edit me-2\"></i> EDIT
       										</button>
      										</a><p><br>";
										echo "<a href=\"preview_form_ujian.php?id=$data_ujian[id]\">
        										<button type=\"button\" class=\"btn btn-primary btn-sm\" name=\"preview_$data_ujian[id]\" value=\"PREVIEW\">
            								<i class=\"fas fa-eye me-2\"></i> PREVIEW
        									</button>
      										</a><p>";
										echo "<a href=\"hapus_form_ujian.php?id=$data_ujian[id]\">
        									<button type=\"button\" class=\"btn btn-danger btn-sm\" name=\"hapus_$data_ujian[id]\" value=\"HAPUS\">
            								<i class=\"fas fa-trash-alt me-2\"></i> HAPUS
        									</button>
      										</a>";
									} else {
										echo "<font style=\"color:darkgreen; font-weight:600;\">DISETUJUI</font><br>";
										echo "<span style=\" font-weight:600;\">per tanggal</span><br>";
										echo "<span style=\"color:darkblue; font-weight:600;\">$tanggal_approval</span>";
										echo "<br><br>
										<a href=\"view_form_ujian.php?id=$data_ujian[id]\">
        										<button type=\"button\" class=\"btn btn-success btn-sm\" name=\"view_$data_ujian[id]\" value=\"VIEW\">
            								<i class=\"fas fa-eye me-2\"></i> VIEW
        									</button>
      										</a><p>";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table>";
							echo "<center>";
							if ($cek_nilai_ujian < 1)
								echo "<br><a href=\"tambah_ujian.php\">
								<button type=\"button\" style=\"margin-right: 10px;\" class=\"btn btn-success\" name=\"tambah\" value=\"TAMBAH\"><i class=\"fas fa-plus\"></i> TAMBAH</button>
								</a>";
							$cek_approved_ujian = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `ipd_nilai_ujian` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
							if ($cek_approved_ujian > 0)
								echo "<br><a href=\"cetak_ujian.php\" target=\"_BLANK\">";
							echo "<button type=\"button\" style=\"margin-right: 10px;\" class=\"btn btn-primary\" name=\"cetak\" value=\"CETAK\"><i class=\"fas fa-print\"></i> CETAK</button>";
							echo "</a>";
							echo "</center><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//Preview Nilai Ujian/Test (id test lihat tabel jenis_test)
							echo "<a id=\"test\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai Ujian/Test</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `ipd_nilai_test` WHERE `nim`='$_COOKIE[user]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      					<th style=\"width:5%; text-align: center;\">No</th>
			      					<th style=\"width:15%; text-align: center;\">Tanggal Ujian</th>
			      					<th style=\"width:40%; text-align: center;\">Jenis Penilaian</th>
			      					<th style=\"width:25%; text-align: center;\">Kordik Kepaniteraan</th>
			      					<th style=\"width:15%; text-align: center;\">Nilai</th>
			      					</thead>";
							$cek_nilai_test = mysqli_num_rows($nilai_test);
							if ($cek_nilai_test < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_test = mysqli_fetch_array($nilai_test)) {
									$jenis_test = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `jenis_test` WHERE `id`='$data_test[jenis_test]'"));
									$status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$data_test[status_ujian]'"));
									$tanggal_test = tanggal_indo($data_test['tgl_test']);
									$tanggal_approval = tanggal_indo($data_test['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_test[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"color:darkblue; font-weight:600; text-align:center;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\">Ilmu Penyakit Dalam</span><br><br>";
									echo "Status Ujian/Test:<span style=\"color:purple;\"> $status_ujian[status_ujian]</span><br>";
									echo "<span>Catatan: $data_test[catatan]</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "Tanggal Yudisium: <span style=\"color:blue\">$tanggal_approval</span>";
									echo "</td>";
									$nilai = number_format($data_test['nilai'], 2);
									echo "<td align=center style=\"font-weight:600;\">$nilai</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table>";
							echo "<center>";
							$cek_approved_test = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `ipd_nilai_test` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
							if ($cek_approved_test > 0)
								echo "<br><center><a href=\"cetak_nilai_test.php\" target=\"_BLANK\">
								<button type=\"button\" style=\"margin-right: 10px;\" class=\"btn btn-primary\" name=\"cetak\" value=\"CETAK\"><i class=\"fas fa-print\"></i> CETAK</button>
								</a>";
							echo "<br><br><br><a href=\"cetak_nilai_ipd.php\" target=\"_BLANK\">";
							echo "<button type=\"button\" style=\"margin-right: 10px;\" class=\"btn btn-success\" id=\"cetak_nilai\" name=\"cetak_nilai\" value=\"CETAK NILAI\"><i class=\"fas fa-print\"></i> CETAK NILAI</button>";
							echo "</a>";
							echo "</center>";
						}

						?>

					</div>
			</main>
			<!-- Back to Top Button -->
			<button id="back-to-top" title="Back to Top">
				<i class="fa-solid fa-arrow-up" style="margin-bottom: 2px;"></i>
				<div>Top</div>
			</button>

			<!-- Start Footer -->
			<footer class="footer py-3" style="background-color: #0e2238;; color: #fff;">
				<div class="container-fluid">
					<div class="row text-white">
						<div class="col-12 col-md-6 text-start mb-3 mb-md-0">
							<a href="#" class="text-white">
								<strong>Program Studi Pendidikan Profesi Dokter <br>
									Universitas Diponegoro
									Jl.Prof. H. Soedarto, SH. Tembalang Semarang
								</strong>
								<br>
								<strong>
									Kode Pos: 50275 |
								</strong>
								<strong>
									<i class="lni lni-phone" style="color: inherit;"></i>
									:024 – 76928010 |
								</strong>
								<strong>
									Kotak Pos: 1269
								</strong>
								<br>
								<strong>
									Fax.: 024 – 76928011 |
								</strong>
								<strong>
									<i class="lni lni-envelope" style="color: inherit;"></i>
									:dean@fk.undip.ac.id
								</strong>
							</a>
						</div>
						<div class="col-12 col-md-6 text-end text-white mb-3 mb-md-0">
							<a href="#" class="text-white">
								<strong>Ketua Prodi Pendidikan Profesi Dokter <br>
									Fakultas Kedokteran UNDIP - Gd A Lt. 2
								</strong>
								<br>
								<strong>
									<i class="lni lni-phone" style="color: inherit;"></i>
									:+62 812-2868-576 |
								</strong>
								<strong>
									<i class="lni lni-envelope" style="color: inherit;"></i>
									:cnawangsih@yahoo.com
								</strong>
								<br>
								<strong style="color: inherit;">
									Build since @2024
								</strong>
							</a>
						</div>
						<div class="col-12 text-center mt-3 mt-md-0" style="color: white;">
							<a href="https://play.google.com/store/apps/details?id=logbook.koas.logbookkoas&hl=in" target="blank" style="color: inherit; text-decoration: underline;">
								<strong>
									<<< Install Aplikasi Android di Playstore>>>
								</strong>
							</a>
						</div>
					</div>
				</div>
			</footer>
			<!-- End Footer -->

		</div>

	</div>

	<!-- Script Bootstrap -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

	<script src="../javascript/script1.js"></script>
	<script src="../javascript/buttontopup2.js"></script>
	<script type="text/javascript" src="../jquery.min.js"></script>
	<script type="text/javascript" src="../freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze1").freezeHeader();
			$("#freeze2").freezeHeader();
			$("#freeze3").freezeHeader();
			$("#freeze4").freezeHeader();
		});
	</script>
</body>

</html>