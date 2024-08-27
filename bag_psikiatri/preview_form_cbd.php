<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Preview CBD Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
			<nav class="navbar navbar-expand px-4 py-3" style="background-color: #ff6f61; ">
				<form action="#" class="d-none d-sm-inline-block">
					<div class="input-group input-group-navbar">
						<img src="../images/undipsolid.png" alt="" style="width: 45px;">
					</div>
				</form>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item dropdown d-flex align-item-center">
							<span class="navbar-text me-2">Halo, <?php echo $nama . ' , <span class="gelar" style="color:red">' . $gelar . '</span>'; ?></span>
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
			<main class="content px-3 py-4">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN JIWA</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">PREVIEW NILAI CBD - LEMBAR PENILAIAN FORMATIF KOMPETENSI KLINIK<br>KEPANITERAAN (STASE) ILMU KESEHATAN JIWA</h2>
						<br>
						<?php
						$id_stase = "M093";
						$id = $_GET['id'];
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
						$data_cbd = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `psikiatri_nilai_cbd` WHERE `id`='$id'"));

						$tanggal_ujian = tanggal_indo($data_cbd['tgl_ujian']);
						$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
						$awal_periode = tanggal_indo($data_cbd['tgl_awal']);
						$akhir_periode = tanggal_indo($data_cbd['tgl_akhir']);

						echo "<center>";
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";

						//Nama mahasiswa
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Nama Mahasiswa Koas</strong></td>";
						echo "<td style=\"width:60%;font-weight:600; color:darkgreen\">$data_mhsw[nama]</td>";
						echo "</tr>";
						//NIM
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>NIM</strong></td>";
						echo "<td style=\" font-weight:600; color:red\">$data_mhsw[nim]</td>";
						echo "</tr>";
						//Periode Kegiatan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Periode Kegiatan</strong></td>";
						echo "<td style=\"font-weight:600;\"><span style=\"color:darkblue\">$awal_periode</span> s.d. <span style=\"color:darkblue\">$akhir_periode</span></td>";
						echo "</tr>";
						//Tanggal Ujian
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tanggal Ujian</strong></td>";
						echo "<td style=\"font-weight:600;color:darkblue\">$tanggal_ujian</td>";
						echo "</tr>";
						//Dosen Penilai/Penguji
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Penilai/Penguji</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
						echo "$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue\">$data_dosen[nip]</span>)";
						echo "</select>";
						echo "</td>";
						echo "</tr>";
						//Situasi Ruangan
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Situasi Ruangan</strong></td>";
						echo "<td style=\"font-weight:600;\"><span style=\"color:green\">$data_cbd[situasi_ruangan]</span></td>";
						echo "</tr>";
						//Nama Pasien
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_cbd[nama_pasien]</td>";
						echo "</tr>";
						//Nama Pasien
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Umur Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\"><span style=\"color:red\">$data_cbd[umur_pasien]</span> Tahun</td>";
						echo "</tr>";
						//Jenis Kelamin Pasien
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Jenis Kelamin Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_cbd[jk_pasien]</td>";
						echo "</tr>";
						//Status Kasus
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Status Kasus</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_cbd[status_kasus]</td>";
						echo "</tr>";
						//Tingkat Kerumitan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tingkat Kerumitan</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_cbd[tingkat_kerumitan]</td>";
						echo "</tr>";
						//Fokus Pertemuan Klinik
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Fokus Pertemuan Klinik</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_cbd[fokus_pertemuan]</td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Form nilai
						echo "<table border=2 style=\"width:70%;  background:rgba(255, 243, 205, 1);\" >";
						echo "<tr><td style=\"text-align:center; font-size:1.1em;\"><strong >Form Penilaian:</strong></td></tr>";
						echo "</table>";
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<th style=\"width:5%;text-align:center;\">No</th>";
						echo "<th style=\"width:55%;text-align:center;\">Komponen Kompetensi</th>";
						echo "<th style=\"width:20%;text-align:center;\">Status Observasi</th>";
						echo "<th style=\"width:20%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//No 1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1</strong></td>";
						echo "<td><strong>Kemampuan membuat catatan medis</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_1'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_1]</td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2<strong></td>";
						echo "<td><strong>Clinical assesment</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_2'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_2]</td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3</strong></td>";
						echo "<td><strong>Investigasi dan rujukan</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_3'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_3]</td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>4</strong></td>";
						echo "<td><strong>Terapi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_4'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_4]</td>";
						echo "</tr>";
						//No 5
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>5</strong></td>";
						echo "<td><strong>Follow up dan rencana pengelolaan selanjutnya</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_5'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_5]</td>";
						echo "</tr>";
						//No 6
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>6</strong></td>";
						echo "<td><strong>Profesionalisme</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_6'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_6]</td>";
						echo "</tr>";
						//No 7
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>7</strong></td>";
						echo "<td><strong>Penilaian klinik secara keseluruhan</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_7'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_cbd[aspek_7]</td>";
						echo "</tr>";
						//Rata Nilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=right colspan=3><strong>Rata-Rata Nilai <span class=\"text-danger\">(Jumlah Nilai / Jumlah Observasi)</span><strong></td>";
						echo "<td align=center style=\"font-weight:600;color:blue\">$data_cbd[nilai_rata]</td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Umpan Balik
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2 align=center><strong>UMPAN BALIK TERHADAP KINERJA PESERTA UJIAN</strong></td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center style=\"width:50%; font-weight:600;color:blue\">Aspek yang sudah bagus</td>";
						echo "<td align=center style=\"width:50%; font-weight:600;color:red\">Aspek yang perlu diperbaiki</td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Poppins;border:0.2px solid black;border-radius:3px;font-size:1em;\" disabled>$data_cbd[ub_bagus]</textarea></td>";
						echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Poppins;border:0.2px solid black;border-radius:3px;font-size:1em;\" disabled>$data_cbd[ub_perbaikan]</textarea></td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2><strong>Action plan yang disetujui bersama:</strong><br><textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;border:0.2px solid black;border-radius:3px;font-size:1em;\" disabled>$data_cbd[saran]</textarea></td>";
						echo "</tr>";

						//Kepuasaan penilai terhadap CBD
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2><strong>Catatan:</strong><br><br><strong>Kepuasaan penilai terhadap CBD: </strong><span style=\"font-weight:600; color:blue\">$data_cbd[kepuasan]</span><br><br></td>";
						echo "</tr>";
						echo "<tr  class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2 align=right><br><span style=\"font-weight:600;\">Status: </span><strong class=\"text-danger\" >BELUM DISETUJUI</strong><br>";
						echo "<span style=\"font-weight:600;\">Dosen Penilai/Penguji:<p><span style=\"font-weight:500;\">$data_dosen[nama]</span>, <span style=\"font-weight:500; color:red\">$data_dosen[gelar]</span><br>NIP. <span style=\"font-weight:600; color:blue;\">$data_dosen[nip]</span></span>";
						echo "</td></tr>";
						echo "</table><br>";

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<br>
						<button type=\"submit\" class=\"btn btn-primary\" name=\"back\" value=\"BACK\">
            					<i class=\"fa-solid fa-backward me-2\"></i> BACK
        						</button>";
						echo "<br><br></form>";
						echo "</center>";

						if ($_POST['back'] == "BACK") {
							echo "
					<script>
					window.location.href=\"penilaian_psikiatri.php\";
					</script>
					";
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
			<footer class="footer py-3">
				<div class="container-fluid">
					<div class="row text-body-secondary">
						<div class="col-12 col-md-6 text-start mb-3 mb-md-0">
							<a href="#" class="text-body-secondary">
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
						<div class="col-12 col-md-6 text-end text-body-secondary mb-3 mb-md-0">
							<a href="#" class="text-body-secondary">
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
								<strong style="color: #0A3967;">
									Build since @2024
								</strong>
							</a>
						</div>
						<div class="col-12 text-center mt-3 mt-md-0" style="color: #0A3967;">
							<a href="https://play.google.com/store/apps/details?id=logbook.koas.logbookkoas&hl=in" target="blank">
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