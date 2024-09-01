<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line View CBD Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="../images/undipsolid.png">
	<link rel="stylesheet" href="../style/style1.css" />
	<link rel="stylesheet" href="../style/buttontopup.css">


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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 5 or $_COOKIE['level'] == 4)) {
				if ($_COOKIE['level'] == 5) {
					include "menu5.php";
				}
				if ($_COOKIE['level'] == 4) {
					include "menu4.php";
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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">NILAI CASE-BASED DISCUSSION (CBD)<br>(KASUS POLIKLIKNIK)<br>KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</h2>
						<br>
						<?php
						$id = $_GET['id'];
						$data_cbd = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `ika_nilai_cbd` WHERE `id`='$id'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_cbd[nim]'"));

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						$tgl_mulai = $_GET['mulai'];
						$tgl_selesai = $_GET['selesai'];
						$approval = $_GET['approval'];
						$mhsw = $_GET['mhsw'];
						echo "<input type=\"hidden\" name=\"tgl_mulai\" value=\"$tgl_mulai\" />";
						echo "<input type=\"hidden\" name=\"tgl_selesai\" value=\"$tgl_selesai\" />";
						echo "<input type=\"hidden\" name=\"approval\" value=\"$approval\" />";
						echo "<input type=\"hidden\" name=\"mhsw\" value=\"$mhsw\" />";

						echo "<center>";
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";

						//Nama dokter muda/koas
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Nama dahasiswa muda</strong></td>";
						echo "<td style=\"width:60%;font-weight:600; color:darkgreen\">$data_mhsw[nama]</td>";
						echo "</tr>";
						//NIM
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>NIM</strong></td>";
						echo "<td style=\" font-weight:600; color:red\">$data_mhsw[nim]</td>";
						echo "</tr>";
						//Nama Poliklinik
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Nama Poliklinik</strong></td>";
						echo "<td style=\"width:60%\"><textarea name=\"poliklinik\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" disabled>$data_cbd[poliklinik]</textarea></td>";
						echo "</tr>";
						//Tanggal Ujian
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						$tanggal_ujian = tanggal_indo($data_cbd['tgl_ujian']);
						echo "<td><strong>Tanggal Ujian</strong></td>";
						echo "<td style=\"font-weight:600;\">$tanggal_ujian</td>";
						echo "</tr>";
						//Dosen Penilai / DPJP
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Penilai / Mentor></strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
						echo "$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue\">$data_dosen[nip]</span>)";
						echo "</td>";
						echo "</tr>";
						//Situasi Ruangan
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Situasi Ruangan</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_cbd['situasi_ruangan'] == "Rawat Jalan")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked/>&nbsp;&nbsp;Rawat Jalan";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" disabled/>&nbsp;&nbsp;Rawat Jalan";
						echo "<br>";
						if ($data_cbd['situasi_ruangan'] == "Rawat Inap")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" checked/>&nbsp;&nbsp;Rawat Inap";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" disabled/>&nbsp;&nbsp;Rawat Inap";
						echo "<br>";
						if ($data_cbd['situasi_ruangan'] == "IRD")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" checked/>&nbsp;&nbsp;IRD";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" disabled/>&nbsp;&nbsp;IRD";
						echo "<br>";
						if ($data_cbd['situasi_ruangan'] == "Lain-lain")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" checked/>&nbsp;&nbsp;Lain-lain";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" disabled/>&nbsp;&nbsp;Lain-lain";
						echo "</td>";
						echo "</tr>";
						//Inisial Pasien
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Inisial Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_cbd[inisial_pasien]</td>";
						echo "</tr>";
						//Umur Pasien
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Umur Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_cbd[umur_pasien] tahun</td>";
						echo "</tr>";
						//Jenis Kelamin Pasien
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Jenis Kelamin Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_cbd['jk_pasien'] == "Laki-Laki")
							echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" checked/>&nbsp;&nbsp;Laki-Laki";
						else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" disabled/>&nbsp;&nbsp;Laki-Laki";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['jk_pasien'] == "Perempuan")
							echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" checked />&nbsp;&nbsp;Perempuan";
						else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" disabled/>&nbsp;&nbsp;Perempuan";
						echo "</td>";
						echo "</tr>";
						//Problem Pasien/Diagnosis
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Problem Pasien/Diagnosis</strong></td>";
						echo "<td><textarea name=\"diagnosis\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_cbd[diagnosis]\" disabled>$data_cbd[diagnosis]</textarea></td>";
						echo "</tr>";
						//Fokus Kasus
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Fokus Kasus</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_cbd['fokus_kasus'] == "Anamnesis")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Anamnesis\" checked/>&nbsp;&nbsp;Anamnesis<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Anamnesis\" disabled/>&nbsp;&nbsp;Anamnesis<br>";
						if ($data_cbd['fokus_kasus'] == "Pemeriksaan fisik")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pemeriksaan fisik\" checked/>&nbsp;&nbsp;Pemeriksaan fisik<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pemeriksaan fisik\" disabled/>&nbsp;&nbsp;Pemeriksaan fisik<br>";
						if ($data_cbd['fokus_kasus'] == "Diagnosis")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Diagnosis\" checked/>&nbsp;&nbsp;Diagnosis<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Diagnosis\" disabled/>&nbsp;&nbsp;Diagnosis<br>";
						if ($data_cbd['fokus_kasus'] == "Terapi")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Terapi\" checked/>&nbsp;&nbsp;Terapi<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Terapi\" disabled/>&nbsp;&nbsp;Terapi<br>";
						if ($data_cbd['fokus_kasus'] == "Konseling")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Konseling\" checked/>&nbsp;&nbsp;Konseling";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Konseling\" disabled/>&nbsp;&nbsp;Konseling";
						echo "</td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Form nilai
						echo "<table border=2 style=\"width:70%;  background:rgba(255, 243, 205, 1);\" >";
						echo "<tr><td style=\"text-align:center; font-size:1.1em;\"><strong >Form Penilaian:</strong></td></tr>";
						echo "</table>";
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<th style=\"width:5%;text-align:center;\">No</th>";
						echo "<th style=\"width:55%;text-align:center;\">Aspek Yang Dinilai</th>";
						echo "<th style=\"width:20%;text-align:center;\">Status Observasi</th>";
						echo "<th style=\"width:20%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//No 1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1</strong></td>";
						echo "<td><strong>Penulisan Rekam Medik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_1'] == "1")
							echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_1'] == "0")
							echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_1]</td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2</strong></td>";
						echo "<td><strong>Penilaian Kemampuan Klinis</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_2'] == "1")
							echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_2'] == "0")
							echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_2]</td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3</strong></td>";
						echo "<td><strong>Tatalaksana Kasus<strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_3'] == "1")
							echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_3'] == "0")
							echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_3]</td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>4</strong></td>";
						echo "<td><strong>Investigasi dan Rujukan</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_4'] == "1")
							echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_4'] == "0")
							echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_4]</td>";
						echo "</tr>";
						//No 5
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>5</strong></td>";
						echo "<td><strong>Pemantauan dan Rencana Tindak Lanjut</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_5'] == "1")
							echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_5'] == "0")
							echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_5]</td>";
						echo "</tr>";
						//No 6
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>6</strong></td>";
						echo "<td><strong>Profesionalisme</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_6'] == "1")
							echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_6'] == "0")
							echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_6]</td>";
						echo "</tr>";
						//No 7
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>7</strong></td>";
						echo "<td><strong>Kompetensi Klinis Keseluruhan</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_7'] == "1")
							echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_7'] == "0")
							echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_7]</td>";
						echo "</tr>";
						//Rata Nilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=right><strong>Rata-Rata Nilai <span class=\"text-danger\">(Jumlah Nilai / Jumlah Observasi)</span></strong></td>";
						echo "<td align=center style=\"font-weight:600; color:blue;\">$data_cbd[nilai_rata]</td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=4><font style=\"font-size:0.9em; font-weight:600;\">Keterangan: Nilai Batas Lulus (NBL) = <span class=\"text-danger\">70</span></font></td></tr>";
						echo "</table><br><br>";

						//Umpan Balik
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr  class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Umpan Balik:</strong><br>
						<textarea name=\"umpan_balik\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" disabled>$data_cbd[umpan_balik]</textarea></td>";
						echo "</tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Rencana tindak lanjut yang disetujui bersama:</strong><br>
						<textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" disabled>$data_cbd[saran]</textarea></td>";
						echo "</tr>";
						echo "</table><br>";

						//Catatan
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2 align=center><strong>Catatan:</strong></td></tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\"><td colspan=2><strong>1. Waktu Penilaian CBD:</td></strong></tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:30%;padding:5px 5px 5px 25px; font-weight:600;\">Observasi</td>";
						echo "<td style=\"width:70%;  font-weight:600;\">";
						echo ": $data_cbd[waktu_observasi]&nbsp;&nbsp;Menit<br>";
						echo "</td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:30%;padding:5px 5px 5px 25px; font-weight:600;\">Memberikan umpan balik</td>";
						echo "<td style=\"width:70%;  font-weight:600;\">";
						echo ": $data_cbd[waktu_ub]&nbsp;&nbsp;Menit<br>";
						echo "</td>";
						echo "</tr>";

						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\"><td colspan=2><strong>2. Kepuasan penilai terhadap pelaksanaan CBD:</strong></td></tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\"><td colspan=2 style=\"padding:5px 5px 5px 25px; font-weight:600;\">";
						if ($data_cbd['kepuasan_penilai'] == "Kurang sekali")
							echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang sekali\" disabled/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kepuasan_penilai'] == "Kurang")
							echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang\" checked/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang\" disabled/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kepuasan_penilai'] == "Cukup")
							echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Cukup\" checked/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Cukup\" disabled/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kepuasan_penilai'] == "Baik")
							echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik\" checked/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik\" disabled/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kepuasan_penilai'] == "Baik sekali")
							echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik sekali\" checked/>&nbsp;&nbsp;Baik sekali";
						else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik sekali\" disabled/>&nbsp;&nbsp;Baik sekali";
						echo "</td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\"><td colspan=2><strong>3. Kepuasan mahasiswa terhadap pelaksanaan CBD:</strong></td></tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\"><td colspan=2 style=\"padding:5px 5px 5px 25px; font-weight:600;\">";
						if ($data_cbd['kepuasan_residen'] == "Kurang sekali")
							echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang sekali\" disabled/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kepuasan_residen'] == "Kurang")
							echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang\" checked/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang\" disabled/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kepuasan_residen'] == "Cukup")
							echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Cukup\" checked/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Cukup\" disabled/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kepuasan_residen'] == "Baik")
							echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik\" checked/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik\" disabled/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kepuasan_residen'] == "Baik sekali")
							echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik sekali\" checked/>&nbsp;&nbsp;Baik sekali";
						else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik sekali\" disabled/>&nbsp;&nbsp;Baik sekali";
						echo "</td></tr>";
						$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2 align=right><br><span style=\"font-weight:600;\">Status:</span> <strong style=\"color:darkgreen;\" >DISETUJUI</strong><br><span style=\"font-weight:600;\">pada tanggal <span style=\"color:darkblue;\">$tanggal_approval</span></span><br>";
						echo "<span style=\"font-weight:600;\">Dosen Penilai / Mentor:<p><span style=\"font-weight:500;\">$data_dosen[nama]</span>, <span style=\"font-weight:500; color:red\">$data_dosen[gelar]</span><br>NIP. <span style=\"font-weight:600; color:blue;\">$data_dosen[nip]</span></span>";
						echo "</td></tr>";
						echo "</table><br>";
						echo "<br>
						<button type=\"submit\" class=\"btn btn-primary\" name=\"back\" value=\"BACK\">
            					<i class=\"fa-solid fa-backward me-2\"></i> BACK
        						</button>";
						echo "<br><br></form>";
						echo "</center>";

						if ($_POST['back'] == "BACK") {
							if ($_COOKIE['level'] == 5)
								echo "
					<script>
					window.location.href=\"penilaian_ika.php\";
					</script>
					";

							if ($_COOKIE['level'] == 4) {
								$tgl_mulai = $_POST['tgl_mulai'];
								$tgl_selesai = $_POST['tgl_selesai'];
								$approval = $_POST['approval'];
								$mhsw = $_POST['mhsw'];
								echo "
				<script>
					window.location.href=\"penilaian_ika_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
				</script>
				";
							}
						}
						?>

					</div>
			</main>
			<!-- Back to Top Button -->
			<button onclick="topFunction()" id="backToTopBtn" title="Go to top">
				<i class="fa-solid fa-arrow-up"></i>
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
	<script src="../javascript/buttontopup.js"></script>
	<script src="../jquery.min.js"></script>
</body>

</html>