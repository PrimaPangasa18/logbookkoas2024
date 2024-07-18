<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rotasi Stase Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />

	<!-- Link Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
	<!-- Link CDN Icon -->
	<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
	<div class="wrapper">
		<?php

		include "config.php";
		include "fungsi.php";

		error_reporting("E_ALL ^ E_NOTICE");

		if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
			echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
		} else {
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2 or $_COOKIE['level'] == 3)) {
				if ($_COOKIE['level'] == 1) {
					include "menu1.php";
				}
				if ($_COOKIE['level'] == 2) {
					include "menu2.php";
				}
				if ($_COOKIE['level'] == 3) {
					include "menu3.php";
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
		// Menentukan path gambar
		$foto_path = "foto/" . $data_mhsw['foto'];
		$default_foto = "images/account.png";

		// Mengecek apakah file gambar ada
		if (!file_exists($foto_path) || empty($data_mhsw['foto'])) {
			$foto_path = $default_foto;
		}
		?>
		<!-- End Sidebar -->
		<div class="main">
			<!-- Start Navbar -->
			<nav class="navbar navbar-expand px-4 py-3">
				<form action="#" class="d-none d-sm-inline-block">
					<div class="input-group input-group-navbar">

					</div>
				</form>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item dropdown d-flex align-item-center">
							<span class="navbar-text me-2">Halo, <?php echo $nama . ' , <span class="gelar" style="color:red">' . $gelar . '</span>'; ?></span>
							<a href="#" class="nav-icon pe-md-0" data-bs-toggle="dropdown">
								<img src="<?php echo $foto_path; ?>" class="avatar img-fluid rounded-circle" alt="" />
							</a>
							<div class="dropdown-menu dropdown-menu-end rounded">

								<div class="dropdown-menu dropdown-menu-end rounded"></div>
								<a href="logout.php" class="dropdown-item">
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
			<?php

			echo "<div class=\"text_header\">CETAK NILAI BAGIAN / KEPANITERAAN (STASE)</div>";
			echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">CETAK NILAI BAGIAN / KEPANITERAAN (STASE)</font></h4><br>";

			echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

			echo "<table border=\"0\">";
			echo "<tr class=\"ganjil\">";
			echo "<td class=\"td_mid\">Kepaniteraan (stase)</td>";
			echo "<td class=\"td_mid\">";
			echo "<select class=\"select_artwide\" name=\"bagian\" id=\"bagian\" required>";
			$data_bagian = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
			echo "<option value=\"\">< Pilihan Bagian / Kepaniteraan (Stase) ></option>";
			while ($bagian = mysqli_fetch_array($data_bagian))
				echo "<option value=\"$bagian[id]\">$bagian[kepaniteraan]</option>";
			echo "</select>";
			echo "</td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
			echo "<td class=\"td_mid\">Nama/NIM Mahasiswa</td>";
			echo "<td class=\"td_mid\">";
			echo "<select class=\"select_artwide\" name=\"mhsw\" id=\"mhsw\" required>";
			$mhsw = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
			echo "<option value=\"\">< Pilihan Mahasiswa ></option>";
			while ($data1 = mysqli_fetch_array($mhsw))
				echo "<option value=\"$data1[nim]\">$data1[nama] ($data1[nim])</option>";
			echo "</select>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
			echo "<br><input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\">";
			echo "</form>";

			if ($_POST[submit] == "SUBMIT") {

				echo "<table class=\"tabel_normal\" style=\"width:75%\">";
				echo "<tr>";
				echo "<td>";
				$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$_POST[mhsw]'"));
				echo "Nama Mahasiswa : $data_mhsw[nama] (NIM: $_POST[mhsw])";
				echo "</td>";
				echo "</tr>";

				if ($_POST[bagian] == "M091") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Penyakit Dalam:</b><br>";
					echo "<a href=\"bag_ipd/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Mini-Cex</a><br>";
					echo "<a href=\"bag_ipd/cetak_kasus.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Penyajian Kasus Besar</a><br>";
					echo "<a href=\"bag_ipd/cetak_ujian.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Akhir</a><br>";
					echo "<a href=\"bag_ipd/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test (Ujian MCQ)</a><br>";
					echo "<a href=\"bag_ipd/cetak_nilai_ipd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Penyakit Dalam</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M092") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Neurologi:</b><br>";
					echo "<a href=\"bag_neuro/cetak_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kasus CBD</a><br>";
					echo "<a href=\"bag_neuro/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
					echo "<a href=\"bag_neuro/cetak_spv.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian SPV</a><br>";
					echo "<a href=\"bag_neuro/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian MINI-CEX</a><br>";
					echo "<a href=\"bag_neuro/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test</a><br>";
					echo "<a href=\"bag_neuro/cetak_nilai_neuro.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Neurologi</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M093") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan Jiwa:</b><br>";
					echo "<a href=\"bag_psikiatri/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
					echo "<a href=\"bag_psikiatri/cetak_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai CBD</a><br>";
					echo "<a href=\"bag_psikiatri/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian MINI-CEX</a><br>";
					echo "<a href=\"bag_psikiatri/cetak_osce.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian OSCE</a><br>";
					echo "<a href=\"bag_psikiatri/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai-Nilai Test</a><br>";
					echo "<a href=\"bag_psikiatri/cetak_nilai_psikiatri.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M094") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) IKFR:</b><br>";
					echo "<a href=\"bag_ikfr/cetak_kasus.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Diskusi Kasus</a><br>";
					echo "<a href=\"bag_ikfr/cetak_minicex.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Mini-Cex</a><br>";
					echo "<a href=\"bag_ikfr/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Pre-Test, Post-Test, Sikap/Perilaku, dan Ujian OSCE</a><br>";
					echo "<a href=\"bag_ikfr/cetak_nilai_ikfr.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) IKFR</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M095") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) IKM-KP:</b><br>";
					echo "<a href=\"bag_ikmkp/cetak_pkbi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kegiatan di PKBI</a><br>";
					echo "<a href=\"bag_ikmkp/cetak_p2ukm.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kegiatan di P2UKM Mlonggo</a><br>";
					echo "<a href=\"bag_ikmkp/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test</a><br>";
					echo "<a href=\"bag_ikmkp/cetak_komprehensip.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Komprehensip</a><br>";
					echo "<a href=\"bag_ikmkp/cetak_nilai_ikmkp.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) IKM-KP</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M096") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Jantung dan Pembuluh Darah:</b><br>";
					echo "<i>(Masih dalam penyusunan !!)</i>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M101") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Bedah:</b><br>";
					echo "<a href=\"bag_bedah/cetak_nilai_mentor.php?cbd=1&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Mentoring di RS Jejaring</a><br>";
					echo "<a href=\"bag_bedah/cetak_nilai_test.php?cbd=2&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Pre-Test, Post-Test, Skill Lab dan OSCE</a><br>";
					echo "<a href=\"bag_bedah/cetak_nilai_bedah.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Bedah</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M102") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Anestesi dan Intensive Care:</b><br>";
					echo "<a href=\"bag_anestesi/cetak_dops.php?cbd=1&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai DOPS</a><br>";
					echo "<a href=\"bag_anestesi/cetak_osce.php?cbd=2&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian OSCE</a><br>";
					echo "<a href=\"bag_anestesi/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai-Nilai Test dan Sikap/Perilaku</a><br>";
					echo "<a href=\"bag_anestesi/cetak_nilai_anestesi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Anestesi dan Intensive Care</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M103") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Radiologi:</b><br>";
					echo "<a href=\"bag_radiologi/cetak_cbd.php?cbd=1&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kasus CBD-Radiodiagnostik</a><br>";
					echo "<a href=\"bag_radiologi/cetak_cbd.php?cbd=2&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kasus CBD-Radioterapi</a><br>";
					echo "<a href=\"bag_radiologi/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
					echo "<a href=\"bag_radiologi/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai-Nilai Test dan Sikap/Perilaku</a><br>";
					echo "<a href=\"bag_radiologi/cetak_nilai_radiologi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Radiologi</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M104") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan Mata:</b><br>";
					echo "<a href=\"bag_mata/cetak_presentasi.php?cbd=1&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Presentasi Kasus Besar</a><br>";
					echo "<a href=\"bag_mata/cetak_jurnal.php?cbd=2&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Presentasi Journal Reading</a><br>";
					echo "<a href=\"bag_mata/cetak_nilai_penyanggah.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Penyanggah</a><br>";
					echo "<a href=\"bag_mata/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Mini-Cex</a><br>";
					echo "<a href=\"bag_mata/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test-Test (MCQ dan OSCE/OSCA)</a><br>";
					echo "<a href=\"bag_mata/cetak_nilai_mata.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Mata</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M105") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan THT-KL:</b><br>";
					echo "<a href=\"bag_thtkl/cetak_presentasi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Presentasi Kasus</a><br>";
					echo "<a href=\"bag_thtkl/cetak_responsi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Responsi Kasus Kecil</a><br>";
					echo "<a href=\"bag_thtkl/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
					echo "<a href=\"bag_thtkl/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test</a><br>";
					echo "<a href=\"bag_thtkl/cetak_osce.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian OSCE</a><br>";
					echo "<a href=\"bag_thtkl/cetak_nilai_thtkl.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan THT-KL</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M106") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) IKGM:</b><br>";
					echo "<a href=\"bag_ikgm/cetak_kasus.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Laporan Kasus</a><br>";
					echo "<a href=\"bag_ikgm/cetak_jurnal.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
					echo "<a href=\"bag_ikgm/cetak_responsi.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Responsi Kasus Kecil</a><br>";
					echo "<a href=\"bag_ikgm/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian OSCA dan Nilai Sikap dan Perilaku</a><br>";
					echo "<a href=\"bag_ikgm/cetak_nilai_ikgm.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) IKGM</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M111") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan:</b><br>";
					echo "<a href=\"bag_obsgyn/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai MINI-CEX (Mini Clinical Examination)</a><br>";
					echo "<a href=\"bag_obsgyn/cetak_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Case-Based Discussion (CBD)</a><br>";
					echo "<a href=\"bag_obsgyn/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
					echo "<a href=\"bag_obsgyn/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test (DOPS/OSCE, MCQ, dan MINI-PAT)</a><br>";
					echo "<a href=\"bag_obsgyn/cetak_nilai_obsgyn.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M112") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal:</b><br>";
					echo "<a href=\"bag_forensik/cetak_visum.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Visum Bayangan</a><br>";
					echo "<a href=\"bag_forensik/cetak_jaga.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kegiatan Jaga</a><br>";
					echo "<a href=\"bag_forensik/cetak_substase.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Substase</a><br>";
					echo "<a href=\"bag_forensik/cetak_referat.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Referat</a><br>";
					echo "<a href=\"bag_forensik/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Pre-Test, Nilai Sikap dan Perilaku, dan Nilai Kompre Stasi I-V</a><br>";
					echo "<a href=\"bag_forensik/cetak_nilai_forensik.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M113") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan Anak:</b><br>";
					echo "<a href=\"bag_ika/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai MINI-CEX (Mini Clinical Examination)</a><br>";
					echo "<a href=\"bag_ika/cetak_dops.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Direct Observation of Procedural Skill (DOPS)</a><br>";
					echo "<a href=\"bag_ika/cetak_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Case-Based Discussion (CBD) - Kasus Poliklinik</a><br>";
					echo "<a href=\"bag_ika/cetak_kasus.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Penyajian Kasus Besar</a><br>";
					echo "<a href=\"bag_ika/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Penyajian Journal Reading</a><br>";
					echo "<a href=\"bag_ika/cetak_minipat.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Mini Peer Assesment Tool (Mini-PAT)</a><br>";
					echo "<a href=\"bag_ika/cetak_ujian.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Akhir Kepaniteraan</a><br>";
					echo "<a href=\"bag_ika/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian/Test (Pre-Test, Post-Test, dan Ujian OSCE)</a><br>";
					echo "<a href=\"bag_ika/cetak_nilai_ika.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Anak</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M114") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin:</b><br>";
					echo "<a href=\"bag_kulit/cetak_nilai_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Kasus</a><br>";
					echo "<a href=\"bag_kulit/cetak_nilai_test.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai-Nilai OSCE, Ujian Teori, dan Perilaku</a><br>";
					echo "<a href=\"bag_kulit/cetak_nilai_kulit.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin</a>";
					echo "</td>";
					echo "</tr>";
				}

				if ($_POST[bagian] == "M121") {
					echo "<tr>";
					echo "<td>";
					echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kepaniteraan Komprehensip:</b><br>";
					echo "<a href=\"bag_kompre/cetak_laporan.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Laporan</a><br>";
					echo "<a href=\"bag_kompre/cetak_nilai_sikap.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Sikap/Perilaku</a><br>";
					echo "<a href=\"bag_kompre/cetak_nilai_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Case Based Discussion (CBD)</a><br>";
					echo "<a href=\"bag_kompre/cetak_nilai_presensi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Presensi / Kehadiran </a><br>";
					echo "<a href=\"bag_kompre/cetak_nilai_kompre.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Akhir Kepaniteraan Komprehensip</a>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					echo "<b>Penilaian Kedokteran Keluarga:</b><br>";
					echo "<a href=\"bag_kdk/cetak_laporan_kasus.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Laporan Kasus (Nilai Dosen Pembimbing Lapangan)</a><br>";
					echo "<a href=\"bag_kdk/cetak_nilai_sikap.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Sikap di Puskesmas dan Klinik Pratama</a><br>";
					echo "<a href=\"bag_kdk/cetak_nilai_dops.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai DOPS di Puskesmas dan Klinik Pratama</a><br>";
					echo "<a href=\"bag_kdk/cetak_nilai_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Mini-CEX di Puskesmas dan Klinik Pratama</a><br>";
					echo "<a href=\"bag_kdk/cetak_nilai_presensi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Presensi / Kehadiran di Puskesmas dan Klinik Pratama</a><br>";
					echo "<a href=\"bag_kdk/cetak_nilai_kdk.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Akhir Kepaniteraan Kedokteran Keluarga</a>";
					echo "</td>";
					echo "</tr>";
				}
				echo "</table>";
			}

			echo "</fieldset>";
			?>
			<!-- End Content -->
			<!-- Back to Top Button -->
			<button onclick="topFunction()" id="backToTopBtn" title="Go to top">
				<i class="fa-solid fa-arrow-up"></i>
				<div>Top</div>
			</button>

			<!-- Start Footer -->
			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-body-secondary">
						<div class="col-6 text-start">
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
						<div class="col-6 text-end text-body-secondary d-none d-md-block">
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
						<div class="col-12 text-center  d-none d-md-block" style="color: #0A3967; ">
							<a href=" https://play.google.com/store/apps/details?id=logbook.koas.logbookkoas&hl=in" target="blank">
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

	<script src="javascript/script1.js"></script>
	<script src="javascript/buttontopup.js"></script>
	<script type="text/javascript" src="jquery.min.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<!-- <script type="text/javascript">
		$(document).ready(function() {
			$("#bagian").select2({
				placeholder: "< Pilihan Bagian / Kepaniteraan (Stase) >",
				allowClear: true
			});
			$("#mhsw").select2({
				placeholder: "< Pilihan Mahasiswa >",
				allowClear: true
			});

		});
	</script> -->

</body>

</HTML>