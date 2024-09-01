<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line View Journal Reading Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) IKGM</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">NILAI JOURNAL READING<br>KEPANITERAAN (STASE) IKGM</h2>
						<br>
						<?php
						$id_stase = "M106";
						$id = $_GET['id'];
						$data_jurnal = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `ikgm_nilai_jurnal` WHERE `id`='$id'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_jurnal[nim]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$data_jurnal[nim]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

						$tanggal_ujian = tanggal_indo($data_jurnal['tgl_ujian']);
						$tanggal_approval = tanggal_indo($data_jurnal['tgl_approval']);

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
						//Periode Stase
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						$mulai_stase = tanggal_indo($datastase_mhsw['tgl_mulai']);
						$selesai_stase = tanggal_indo($datastase_mhsw['tgl_selesai']);
						echo "<td class=\"td_mid\"><strong>Periode Kepaniteraan (STASE)</strong></td>";
						echo "<td class=\"td_mid\" style=\"font-weight:600;\">
						<span style=\"color:darkblue\">$mulai_stase</span> s.d. <span style=\"color:darkblue\">$selesai_stase</span></td>";
						echo "</tr>";
						//Tanggal Ujian/Presentasi
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Tanggal Ujian/Presentasi</strong></td>";
						echo "<td class=\"td_mid\" style=\"font-weight:600; \">$tanggal_ujian</td>";
						echo "</tr>";
						//Dosen Penguji (Tutor)
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Penguji (Tutor)</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
						echo "$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue\">$data_dosen[nip]</span>)";
						echo "</td>";
						echo "</tr>";
						//Nama Jurnal
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama Jurnal</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_jurnal[nama_jurnal]</td>";
						echo "</tr>";
						//Judul Artikel
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Judul Artikel</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_jurnal[judul_paper]</td>";
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
						echo "<th style=\"width:40%;text-align:center;\">Penilaian</th>";
						echo "</thead>";
						//No 1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>1</strong></td>";
						echo "<td><strong>Kehadiran</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_jurnal['aspek_1'] == "0")
							echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" checked/>&nbsp;&nbsp;0 - Tidak Hadir<br>";
						else echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" disabled />&nbsp;&nbsp;0 - Tidak Hadir<br>";
						if ($data_jurnal['aspek_1'] == "2")
							echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" checked/>&nbsp;&nbsp;2 - Hadir, terlambat 11 – 15 menit<br>";
						else echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" disabled />&nbsp;&nbsp;2 - Hadir, terlambat 11 – 15 menit<br>";
						if ($data_jurnal['aspek_1'] == "3")
							echo "<input type=\"radio\" name=\"aspek_1\" value=\"3\" checked/>&nbsp;&nbsp;3 - Hadir, terlambat ≤ 10 menit<br>";
						else echo "<input type=\"radio\" name=\"aspek_1\" value=\"3\" disabled />&nbsp;&nbsp;3 - Hadir, terlambat ≤ 10 menit<br>";
						if ($data_jurnal['aspek_1'] == "4")
							echo "<input type=\"radio\" name=\"aspek_1\" value=\"4\" checked/>&nbsp;&nbsp;4 - Hadir tepat waktu";
						else echo "<input type=\"radio\" name=\"aspek_1\" value=\"4\" disabled />&nbsp;&nbsp;4 - Hadir tepat waktu";
						echo "</td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>2</strong></td>";
						echo "<td><strong>Tugas terjemahan, slide presentasi dan telaah jurnal</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_jurnal['aspek_2'] == "1")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" checked/>&nbsp;&nbsp;1 - Tidak membuat<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" disabled />&nbsp;&nbsp;1 - Tidak membuat<br>";
						if ($data_jurnal['aspek_2'] == "2")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" checked/>&nbsp;&nbsp;2 - Kurang lengkap<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" disabled />&nbsp;&nbsp;2 - Kurang lengkap<br>";
						if ($data_jurnal['aspek_2'] == "3")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" checked/>&nbsp;&nbsp;3 - Cukup lengkap<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" disabled />&nbsp;&nbsp;3 - Cukup lengkap<br>";
						if ($data_jurnal['aspek_2'] == "4")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" checked/>&nbsp;&nbsp;4 - Lengkap";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" disabled />&nbsp;&nbsp;4 - Lengkap";
						echo "</td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>3</strong></td>";
						echo "<td><strong>Aktifitas saat diskusi<br>(<span class=\"text-danger\">Dinilai dari frekuensi mengajukan masukan / komentar / pendapat / jawaban</span>)</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_jurnal['aspek_3'] == "1")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" checked/>&nbsp;&nbsp;1 - Pasif<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" disabled />&nbsp;&nbsp;1 - Pasif<br>";
						if ($data_jurnal['aspek_3'] == "2")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" checked/>&nbsp;&nbsp;2 - Kurang aktif<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" disabled />&nbsp;&nbsp;2 - Kurang aktif<br>";
						if ($data_jurnal['aspek_3'] == "3")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" checked/>&nbsp;&nbsp;3 - Cukup aktif<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" disabled />&nbsp;&nbsp;3 - Cukup aktif<br>";
						if ($data_jurnal['aspek_3'] == "4")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" checked/>&nbsp;&nbsp;4 - Sangat aktif";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" disabled />&nbsp;&nbsp;4 - Sangat aktif";
						echo "</td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>4</strong></td>";
						echo "<td><strong>Relevansi pembicaraan<br>(<span class=\"text-danger\">Dinilai dari relevansi dan penguasaaan terhadap materi diskusi</span>)</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_jurnal['aspek_4'] == "1")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" checked/>&nbsp;&nbsp;1 - Pembicaraan selalu tidak relevan<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" disabled />&nbsp;&nbsp;1 - Pembicaraan selalu tidak relevan<br>";
						if ($data_jurnal['aspek_4'] == "2")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" checked/>&nbsp;&nbsp;2 - Pembicaraan sering tidak relevan<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" disabled />&nbsp;&nbsp;2 - Pembicaraan sering tidak relevan<br>";
						if ($data_jurnal['aspek_4'] == "3")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" checked/>&nbsp;&nbsp;3 - Pembicaraan cukup relevan<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" disabled />&nbsp;&nbsp;3 - Pembicaraan cukup relevan<br>";
						if ($data_jurnal['aspek_4'] == "4")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" checked/>&nbsp;&nbsp;4 - Pembicaraan selalu relevan";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" disabled />&nbsp;&nbsp;4 - Pembicaraan selalu relevan";
						echo "</td>";
						echo "</tr>";
						//No 5
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>5</strong></td>";
						echo "<td><strong>Keterampilan presentasi/berkomunikasi<br>(<span class=\"text-danger\">Dinilai dari kemampuan berinteraksi dengan peserta lain.
								Tunjuk jari bila mau menyampaikan pendapat / bertanya;
								memperhatikan saat peserta lain berbicara;
								tidak emosional / tidak memotong pembicaraan orang lain / tidak mendominasi diskusi.</span>)</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_jurnal['aspek_5'] == "1")
							echo "<input type=\"radio\" name=\"aspek_5\" value=\"1\" checked/>&nbsp;&nbsp;1 - Kurang<br>";
						else echo "<input type=\"radio\" name=\"aspek_5\" value=\"1\" disabled />&nbsp;&nbsp;1 - Kurang<br>";
						if ($data_jurnal['aspek_5'] == "2")
							echo "<input type=\"radio\" name=\"aspek_5\" value=\"2\" checked/>&nbsp;&nbsp;2 - Cukup<br>";
						else echo "<input type=\"radio\" name=\"aspek_5\" value=\"2\" disabled />&nbsp;&nbsp;2 - Cukup<br>";
						if ($data_jurnal['aspek_5'] == "3")
							echo "<input type=\"radio\" name=\"aspek_5\" value=\"3\" checked/>&nbsp;&nbsp;3 - Baik<br>";
						else echo "<input type=\"radio\" name=\"aspek_5\" value=\"3\" disabled />&nbsp;&nbsp;3 - Baik<br>";
						if ($data_jurnal['aspek_5'] == "4")
							echo "<input type=\"radio\" name=\"aspek_5\" value=\"4\" checked/>&nbsp;&nbsp;4 - Sangat baik";
						else echo "<input type=\"radio\" name=\"aspek_5\" value=\"4\" disabled />&nbsp;&nbsp;4 - Sangat baik";
						echo "</td>";
						echo "</tr>";
						//Total Nilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2 align=right><strong>Total Nilai <span class=\"text-danger\">(10 x Jumlah Poin / 2)</span></strong></td>";
						echo "<td align=center style=\"font-weight:600; color:blue\">$data_jurnal[nilai_total]</td>";
						echo "</tr>";
						echo "</table><br><br>";

						echo "<tr>";
						echo "<td colspan=2 align=right>Total Nilai (10 x Jumlah Poin / 2)</td>";
						echo "<td>&nbsp;&nbsp;<b>$data_jurnal[nilai_total]</b></td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Catatan
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr  class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center ><strong>Catatan Dosen Penguji (Tutor) Terhadap Kegiatan:</strong><br></td>";
						echo "</tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Catatan:</strong><br>
						<textarea name=\"catatan\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" disabled>$data_jurnal[catatan]</textarea></td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
						<td  align=right><br><span style=\"font-weight:600;\">Status:</span> <strong style=\"color:darkgreen;\" >DISETUJUI</strong><br><span style=\"font-weight:600;\">pada tanggal <span style=\"color:darkblue;\">$tanggal_approval</span></span><br>";
						echo "<span style=\"font-weight:600;\">Dosen Penguji (Tutor):<p><span style=\"font-weight:500;\">$data_dosen[nama]</span>, <span style=\"font-weight:500; color:red\">$data_dosen[gelar]</span><br>NIP. <span style=\"font-weight:600; color:blue;\">$data_dosen[nip]</span></span>";
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
					window.location.href=\"penilaian_ikgm.php\";
					</script>
					";

							if ($_COOKIE['level'] == 4) {
								$tgl_mulai = $_POST['tgl_mulai'];
								$tgl_selesai = $_POST['tgl_selesai'];
								$approval = $_POST['approval'];
								$mhsw = $_POST['mhsw'];
								echo "
				<script>
					window.location.href=\"penilaian_ikgm_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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