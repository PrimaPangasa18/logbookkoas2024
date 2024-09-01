<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rotasi Stase Edit Evaluasi Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />

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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 5) {
				include "menu5.php";
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
		$foto_path = "foto/" . $data_mhsw['foto'];
		$default_foto = "foto/profil_blank.png";

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
						<img src="images/undipsolid.png" alt="" style="width: 45px;">
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
			<main class="content px-3 py-4" style="background-image: url('images/undip_watermark_color.png'), url('images/undip_watermark_color.png'); ">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">EDIT EVALUASI KEPANITERAAN (STASE)</h3>
						<br>
						<?php
						$id_stase = $_GET['id'];
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\" />";
						echo "<input type=\"hidden\" name=\"tgl_isi\" value=\"$tgl\" />";
						$kepaniteraan = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$data_evaluasi = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `evaluasi_stase` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'"));

						echo '<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">
    						Evaluasi Pelaksanaan Kepaniteraan (STASE) ' . htmlspecialchars($kepaniteraan['kepaniteraan']) . '
							</h2>';

						echo '<br><br>';
						echo "<center>";
						echo "<table class=\" table table-warning\"; style=\"width:60%; border\">";
						echo "<tr>";
						echo "<td align=center><img src=\"images/evaluasi_header.jpg\" style=\"width:100%;height:auto\"></td>";
						echo "</tr>";
						echo "<td >
						<span style=\"font-size:0.9em; font-weight:600;\">Berikan evaluasi anda mengenai materi dan sistem pembelajaran pada stase bagian yang telah anda jalani.<br><br></span>";
						echo "<font style=\"font-size:0.9em;color:red; font-weight:600;\">* Wajib isi</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:600;\"><span style=\"color:red;\" >1. SISTEM PEMBELAJARAN DALAM STASE</span></font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em; font-weight:600;\">Penilaian Materi Pembelajaran</font><font style=\"font-size:0.85em;color:red\"> *</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>";
						echo "<table style=\"border:0;width:100%;\">";
						echo "<tr>";
						echo "<td style=\"width:52%\">&nbsp;</td>";
						echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:darkred\">sangat tidak setuju</font></td>";
						echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em;font-weight:600; color:red\">tidak setuju</font></td>";
						echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em;font-weight:600; color:green;\">setuju</font></td>";
						echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em;font-weight:600; color:darkgreen;\">sangat setuju</font></td>";
						echo "</tr>";
						echo "</table>";
						echo "</td>";
						echo "</tr>";

						//1. Pembelajaran
						$eval_pemb = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Pembelajaran' ORDER BY `id`");
						$no = 1;
						while ($data_pemb = mysqli_fetch_array($eval_pemb)) {
							echo "<tr>";
							echo "<td>";
							echo "<table style=\"border:0.5px solid black;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr>";
							echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\">$data_pemb[pertanyaan]</font></td>";
							$radio_name = "input_11" . $no;
							$radio_id1 = "1input_11" . $no;
							$radio_id2 = "2input_11" . $no;
							$radio_id3 = "3input_11" . $no;
							$radio_id4 = "4input_11" . $no;
							echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
							if ($data_evaluasi[$radio_name] == "1") echo "<input type=\"radio\" style=\"width:20px; height:20px;\"  name=\"$radio_name\" value=\"1\" id=\"$radio_id1\" checked>";
							else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"1\" id=\"$radio_id1\">";
							echo "<label for=\"$radio_id1\"></label></div></div></td>";
							echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
							if ($data_evaluasi[$radio_name] == "2") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"2\" id=\"$radio_id2\" checked>";
							else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"2\" id=\"$radio_id2\">";
							echo "<label for=\"$radio_id2\"></label></div></div></td>";
							echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
							if ($data_evaluasi[$radio_name] == "3") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"3\" id=\"$radio_id3\" checked>";
							else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"3\" id=\"$radio_id3\">";
							echo "<label for=\"$radio_id3\"></label></div></div></td>";
							echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
							if ($data_evaluasi[$radio_name] == "4") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"4\" id=\"$radio_id4\" checked>";
							else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"4\" id=\"$radio_id4\">";
							echo "<label for=\"$radio_id4\"></label></div></div></td>";
							echo "</tr>";
							echo "</table>";
							echo "</td>";
							echo "</tr>";
							$no++;
						}
						//Refleksi diri
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:600;\">Tuliskan refleksi diri anda setelah menjalankan stase kepaniteraan ini: </font><font style=\"font-size:0.85em;color:red; font-weight:600; \"> *</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><textarea name=\"refleksi\" style=\"font-family:Poppins;font-size:0.85em;width:100%;border:0.5px solid grey;border-radius:5px;\" rows=5>$data_evaluasi[refleksi]</textarea></td>";
						echo "</tr>";
						//Komentar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:600;\">Silakan tulis dalam kolom di bawah ini komentar, usul, saran, atau kritik dalam bahasa yang santun: </font><font style=\"font-size:0.85em;color:red; font-weight:600; \"> *</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><textarea name=\"komentar\" style=\"font-family:Poppins;font-size:0.85em;width:100%;border:0.5px solid grey;border-radius:5px;\" rows=5>$data_evaluasi[komentar]</textarea></td>";
						echo "</tr>";
						//Pencapaian
						echo "<tr>";
						echo "<td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td colspan=2><font style=\"font-size:0.85em\">Menurut Anda, seberapa banyak pencapaian kompetensi level 3A, 3B, 4A yang Anda capat dalam kepaniteraan Bagian ini (termasuk dengan stase luar kepaniteraan ini)? </font><font style=\"font-size:0.85em;color:red; \"> *</font></td>";
						echo "</tr>";
						echo "<tr><td style=\"width:5%;padding:0 0 0 10px\"><div class=\"clearfix\"><div class=\"radio\">";
						if ($data_evaluasi['input_12'] == '1') echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_12\" value=\"1\" id=\"1input_12\" checked>";
						else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_12\" value=\"1\" id=\"1input_12\">";
						echo "<label for=\"1input_12\"></label></div></div></td>
						<td style=\"width:95%;vertical-align:middle;padding:0px\"><font style=\"font-size:0.85em\">&nbsp;&nbsp;< 25%</font>
						</td></tr>";
						echo "<tr><td style=\"width:5%;padding:0 0 0 10px\"><div class=\"clearfix\"><div class=\"radio\">";
						if ($data_evaluasi['input_12'] == '2') echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_12\" value=\"2\" id=\"2input_12\" checked>";
						else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_12\" value=\"2\" id=\"2input_12\">";
						echo "<label for=\"2input_12\"></label></div></div></td>
						<td style=\"width:95%;vertical-align:middle;padding:0px\"><font style=\"font-size:0.85em\">&nbsp;&nbsp;25-50%</font>
						</td></tr>";
						echo "<tr><td style=\"width:5%;padding:0 0 0 10px\"><div class=\"clearfix\"><div class=\"radio\">";
						if ($data_evaluasi['input_12'] == '3') echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_12\" value=\"3\" id=\"3input_12\" checked>";
						else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_12\" value=\"3\" id=\"3input_12\">";
						echo "<label for=\"3input_12\"></label></div></div></td>
						<td style=\"width:95%;vertical-align:middle;padding:0px\"><font style=\"font-size:0.85em\">&nbsp;&nbsp;50-75%</font>
						</td></tr>";
						echo "<tr><td style=\"width:5%;padding:0 0 0 10px\"><div class=\"clearfix\"><div class=\"radio\">";
						if ($data_evaluasi['input_12'] == '4') echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_12\" value=\"4\" id=\"4input_12\" checked>";
						else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_12\" value=\"4\" id=\"4input_12\">";
						echo "<label for=\"4input_12\"></label></div></div></td>
						<td style=\"width:95%;vertical-align:middle;padding:0px\"><font style=\"font-size:0.85em\">&nbsp;&nbsp;> 75%</font>
						</td></tr>";
						echo "</table>";
						echo "</td>";
						echo "</tr>";
						//Kepuasan
						echo "<tr>";
						echo "<td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td colspan=2><font style=\"font-size:0.85em\">Seberapa besar kepuasan Anda terhadap keseluruhan program stase di Bagian ini (termasuk program stase luar)? </font><font style=\"font-size:0.85em;color:red; \"> *</font></td>";
						echo "</tr>";
						echo "<tr><td style=\"width:5%;padding:0 0 0 10px\"><div class=\"clearfix\"><div class=\"radio\">";
						if ($data_evaluasi['input_13'] == '1') echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_13\" value=\"1\" id=\"1input_13\" checked>";
						else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_13\" value=\"1\" id=\"1input_13\">";
						echo "<label for=\"1input_13\"></label></div></div></td>
						<td style=\"width:95%;vertical-align:middle;padding:0px\"><font style=\"font-size:0.85em\">&nbsp;&nbsp;Sangat tidak puas</font>
						</td></tr>";
						echo "<tr><td style=\"width:5%;padding:0 0 0 10px\"><div class=\"clearfix\"><div class=\"radio\">";
						if ($data_evaluasi['input_13'] == '2') echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_13\" value=\"2\" id=\"2input_13\" checked>";
						else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_13\" value=\"2\" id=\"2input_13\">";
						echo "<label for=\"2input_13\"></label></div></div></td>
						<td style=\"width:95%;vertical-align:middle;padding:0px\"><font style=\"font-size:0.85em\">&nbsp;&nbsp;Kurang puas</font>
						</td></tr>";
						echo "<tr><td style=\"width:5%;padding:0 0 0 10px\"><div class=\"clearfix\"><div class=\"radio\">";
						if ($data_evaluasi['input_13'] == '3') echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_13\" value=\"3\" id=\"3input_13\" checked>";
						else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_13\" value=\"3\" id=\"3input_13\">";
						echo "<label for=\"3input_13\"></label></div></div></td>
						<td style=\"width:95%;vertical-align:middle;padding:0px\"><font style=\"font-size:0.85em\">&nbsp;&nbsp;Puas</font>
						</td></tr>";
						echo "<tr><td style=\"width:5%;padding:0 0 0 10px\"><div class=\"clearfix\"><div class=\"radio\">";
						if ($data_evaluasi['input_13'] == '4') echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_13\" value=\"4\" id=\"4input_13\" checked>";
						else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"input_13\" value=\"4\" id=\"4input_13\">";
						echo "<label for=\"4input_13\"></label></div></div></td>
						<td style=\"width:95%;vertical-align:middle;padding:0px\"><font style=\"font-size:0.85em\">&nbsp;&nbsp;Sangat puas</font>
						</td></tr>";
						echo "</table>";
						echo "</td>";
						echo "</tr>";
						echo "<tr><td>&nbsp;</td></tr>";

						//2. Dosen
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:600;\"><span style=\"color:red;\" >2. DOSEN</span><br>";
						echo "Berikan evaluasi Anda bagi minimal 3 (tiga)  dosen  dalam kepaniteraan ini yang melakukan minimal 2 (dua) kali tatap muka dengan Anda. Bila tidak ada, pilih 3 dosen dengan kuantitas dan/atau kualitas pertemuan paling banyak.";
						echo "<br>Aspek yang tidak bisa dinilai (misalnya, karena mahasiswa tidak tahu pasti) tidak perlu dinilai (kolom dikosongkan).</font></td>";
						echo "</tr>";

						for ($x = 1; $x <= 3; $x++) {
							//Pilihan Dosen x
							echo "<tr>";
							$dosenx = "dosen" . $x;
							echo "<td><font style=\"font-size:0.85em\">2.$x. Nama Dosen </font><font style=\"font-size:0.85em;color:red; \"> *</font> :<p>";
							echo "<label class=\"select_small\"><select name=\"$dosenx\" id=\"$dosenx\" required></label>";
							$dosen = mysqli_query($con, "SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
							$dosen_evalx = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_evaluasi[$dosenx]'"));
							echo "<option value=\"$dosen_evalx[nip]\">$dosen_evalx[nama], $dosen_evalx[gelar]</option>";
							while ($data = mysqli_fetch_array($dosen)) {
								$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data[username]'"));
								echo "<option value=\"$data[username]\">$data_dosen[nama], $data_dosen[gelar]</option>";
							}
							echo "</select></font>";
							echo "</td>";
							echo "</tr>";
							//Jumlah Jam Tatap Muka Dosen x
							echo "<tr>";
							$tatap_mukax = "tatap_muka" . $x;
							echo "<td><font style=\"font-size:0.9em; font-weight:600;\">Jumlah jam tatap muka </font><font style=\"font-size:0.85em;color:red; font-weight:600;\"> *</font> :<p>";
							echo "&nbsp;&nbsp;<textarea name=\"$tatap_mukax\" style=\"font-family:Poppins;font-size:0.85em;width:100%;border:0.5px solid grey;border-radius:5px;\" rows=2 >$data_evaluasi[$tatap_mukax]</textarea></td>";
							echo "</tr>";
							//Dosen x Umum
							echo "<tr>";
							echo "<td><font tyle=\"font-size:0.9em; font-weight:600;\">A. Umum </font><font style=\"font-size:0.85em;color:red;; font-weight:600; \"> *</font></td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>";
							echo "<table style=\"border:0;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr>";
							echo "<td style=\"width:52%\">&nbsp;</td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em;font-weight:600; color:darkred\">sangat tidak setuju</font></td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:red\">tidak setuju</font></td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:green\">setuju</font></td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:darkgreen\">sangat setuju</font></td>";
							echo "</tr>";
							echo "</table>";
							echo "</td>";
							echo "</tr>";
							$dosen_umum = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_umum' ORDER BY `id`");
							$no = 1;
							while ($data_umum = mysqli_fetch_array($dosen_umum)) {
								echo "<tr>";
								echo "<td>";
								echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
								echo "<tr>";
								echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\">$data_umum[pertanyaan]</font></td>";
								$radio_name = "input_2" . $x . "A" . $no;
								$radio_id1 = "1input_2" . $x . "A" . $no;
								$radio_id2 = "2input_2" . $x . "A" . $no;
								$radio_id3 = "3input_2" . $x . "A" . $no;
								$radio_id4 = "4input_2" . $x . "A" . $no;
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "1") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"1\" id=\"$radio_id1\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"1\" id=\"$radio_id1\">";
								echo "<label for=\"$radio_id1\"></label></div></div></td>";
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "2") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"2\" id=\"$radio_id2\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"2\" id=\"$radio_id2\">";
								echo "<label for=\"$radio_id2\"></label></div></div></td>";
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "3") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"3\" id=\"$radio_id3\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"3\" id=\"$radio_id3\">";
								echo "<label for=\"$radio_id3\"></label></div></div></td>";
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "4") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"4\" id=\"$radio_id4\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"4\" id=\"$radio_id4\">";
								echo "<label for=\"$radio_id4\"></label></div></div></td>";
								echo "</tr>";
								echo "</table>";
								echo "</td>";
								echo "</tr>";
								$no++;
							}
							echo "<tr><td>&nbsp;</td></tr>";
							//Dosen x Pengajar
							echo "<tr>";
							echo "<td><font style=\"font-size:0.85em; font-weight:600;\">B. Dosen sebagai pengajar </font><font style=\"font-size:0.85em;color:red; font-weight:600; \"> *</font></td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>";
							echo "<table style=\"border:0;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr>";
							echo "<td style=\"width:52%\">&nbsp;</td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:darkred\">sangat tidak setuju</font></td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:red\">tidak setuju</font></td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:green\">setuju</font></td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:darkgreen\">sangat setuju</font></td>";
							echo "</tr>";
							echo "</table>";
							echo "</td>";
							echo "</tr>";
							$dosen_pengajar = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_pengajar' ORDER BY `id`");
							$no = 1;
							while ($data_pengajar = mysqli_fetch_array($dosen_pengajar)) {
								echo "<tr>";
								echo "<td>";
								echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
								echo "<tr>";
								echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\">$data_pengajar[pertanyaan]</font></td>";
								$radio_name = "input_2" . $x . "B" . $no;
								$radio_id1 = "1input_2" . $x . "B" . $no;
								$radio_id2 = "2input_2" . $x . "B" . $no;
								$radio_id3 = "3input_2" . $x . "B" . $no;
								$radio_id4 = "4input_2" . $x . "B" . $no;
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "1") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"1\" id=\"$radio_id1\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"1\" id=\"$radio_id1\">";
								echo "<label for=\"$radio_id1\"></label></div></div></td>";
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "2") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"2\" id=\"$radio_id2\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"2\" id=\"$radio_id2\">";
								echo "<label for=\"$radio_id2\"></label></div></div></td>";
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "3") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"3\" id=\"$radio_id3\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"3\" id=\"$radio_id3\">";
								echo "<label for=\"$radio_id3\"></label></div></div></td>";
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "4") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"4\" id=\"$radio_id4\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"4\" id=\"$radio_id4\">";
								echo "<label for=\"$radio_id4\"></label></div></div></td>";
								echo "</tr>";
								echo "</table>";
								echo "</td>";
								echo "</tr>";
								$no++;
							}
							echo "<tr><td>&nbsp;</td></tr>";
							//Dosen x Penguji
							echo "<tr>";
							echo "<td><font style=\"font-size:0.85em; font-weight:600;\">C. Dosen sebagai penguji </font><font style=\"font-size:0.85em;color:red;; font-weight:600; \"> *</font></td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>";
							echo "<table style=\"border:0;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr>";
							echo "<td style=\"width:52%\">&nbsp;</td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:darkred\">sangat tidak setuju</font></td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:kred\">tidak setuju</font></td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:green\">setuju</font></td>";
							echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:darkgreen\">sangat setuju</font></td>";
							echo "</tr>";
							echo "</table>";
							echo "</td>";
							echo "</tr>";
							$dosen_penguji = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_penguji' ORDER BY `id`");
							$no = 1;
							while ($data_penguji = mysqli_fetch_array($dosen_penguji)) {
								echo "<tr>";
								echo "<td>";
								echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
								echo "<tr>";
								echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\">$data_penguji[pertanyaan]</font></td>";
								$radio_name = "input_2" . $x . "C" . $no;
								$radio_id1 = "1input_2" . $x . "C" . $no;
								$radio_id2 = "2input_2" . $x . "C" . $no;
								$radio_id3 = "3input_2" . $x . "C" . $no;
								$radio_id4 = "4input_2" . $x . "C" . $no;
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "1") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"1\" id=\"$radio_id1\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"1\" id=\"$radio_id1\">";
								echo "<label for=\"$radio_id1\"></label></div></div></td>";
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "2") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"2\" id=\"$radio_id2\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"2\" id=\"$radio_id2\">";
								echo "<label for=\"$radio_id2\"></label></div></div></td>";
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "3") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"3\" id=\"$radio_id3\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"3\" id=\"$radio_id3\">";
								echo "<label for=\"$radio_id3\"></label></div></div></td>";
								echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
								if ($data_evaluasi[$radio_name] == "4") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"4\" id=\"$radio_id4\" checked>";
								else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"4\" id=\"$radio_id4\">";
								echo "<label for=\"$radio_id4\"></label></div></div></td>";
								echo "</tr>";
								echo "</table>";
								echo "</td>";
								echo "</tr>";
								$no++;
							}
							echo "<tr><td>&nbsp;</td></tr>";
							//Komentar dosen x
							echo "<tr>";
							echo "<td><font style=\"font-size:0.85em; font-weight:600;\">Silakan mengisi pada kolom di bawah ini dengan bahasa yang santun komentar, usul, saran atau masukan: </font><font style=\"font-size:0.85em;color:red; font-weight:600;\"> *</font></td>";
							echo "</tr>";
							echo "<tr>";
							$komentar_dosenx = "komentar_dosen" . $x;
							echo "<td><textarea name=\"$komentar_dosenx\" style=\"font-family:Poppins;font-size:0.85em;width:100%;border:0.5px solid grey;border-radius:5px;\" rows=5>$data_evaluasi[$komentar_dosenx]</textarea></td>";
							echo "</tr>";

							echo "<tr><td>&nbsp;</td></tr>";
						}

						//Evaluasi stase luar
						echo "<tr>";
						echo "<td><font tyle=\"font-size:0.85em;font-weight:600;\"><b>EVALUASI STASE LUAR</b><br>
					Isilah kuesioner berikut apabila dalam stase yang Anda evaluasi ini ada stase luar.</font></td>";
						echo "</tr>";

						//Lokasi stase luar
						echo "<tr>";
						echo "<td><font tyle=\"font-size:0.85em;font-weight:600;\">Lokasi stase luar </font><font style=\"font-size:0.85em;color:red; font-weight:600; \"> *</font> :<p>";
						echo "<textarea name=\"lokasi_luar\" style=\"font-family:Tahoma;font-size:0.85em;width:100%;border:0.5px solid grey;border-radius:5px;\" rows=5>$data_evaluasi[lokasi_luar]</textarea></td>";
						echo "</tr>";
						//Lama stase luar
						echo "<tr>";
						echo "<td><font tyle=\"font-size:0.85em;font-weight:600;\">Lama stase (minggu) </font><font style=\"font-size:0.85em;color:red; font-weight:600;\"> *</font> :<p>";
						echo "<label class=\"select_small\"><select name=\"lama_luar\" id=\"lama_luar\" style=\"width:450px\">";
						echo "<option value=\"$data_evaluasi[lama_luar]\">$data_evaluasi[lama_luar] minggu</option>";
						for ($mg = 1; $mg <= 8; $mg++)
							echo "<option value=\"$mg\">$mg minggu</option>";
						echo "</select></label>";
						echo "</tr>";
						echo "<tr><td>&nbsp;</td></tr>";
						//A. Penilaian Materi Pembelajaran
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em; font-weight:600;\">A. Penilaian Materi Pembelajaran </font><font style=\"font-size:0.85em;color:red;font-weight:600; \"> *</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>";
						echo "<table style=\"border:0;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td style=\"width:52%\">&nbsp;</td>";
						echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:darkred\">sangat tidak setuju</font></td>";
						echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:kred\">tidak setuju</font></td>";
						echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:green\">setuju</font></td>";
						echo "<td align=center style=\"width:12%\"><font style=\"font-size:0.85em; font-weight:600; color:darkgreen\">sangat setuju</font></td>";
						echo "</tr>";
						echo "</table>";
						echo "</td>";
						echo "</tr>";
						$materi = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Stase_luar' ORDER BY `id`");
						$no = 1;
						while ($data_materi = mysqli_fetch_array($materi)) {
							echo "<tr>";
							echo "<td>";
							echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr>";
							echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\">$data_materi[pertanyaan]</font></td>";
							$radio_name = "input_3A" . $no;
							$radio_id1 = "1input_3A" . $no;
							$radio_id2 = "2input_3A" . $no;
							$radio_id3 = "3input_3A" . $no;
							$radio_id4 = "4input_3A" . $no;
							echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
							if ($data_evaluasi[$radio_name] == "1") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"1\" id=\"$radio_id1\" checked>";
							else echo "<input type=\"radio\" name=\"$radio_name\" style=\"width:20px; height:20px;\" value=\"1\" id=\"$radio_id1\">";
							echo "<label for=\"$radio_id1\"></label></div></div></td>";
							echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
							if ($data_evaluasi[$radio_name] == "2") echo "<input type=\"radio\" name=\"$radio_name\" value=\"2\" id=\"$radio_id2\" checked>";
							else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"2\" id=\"$radio_id2\">";
							echo "<label for=\"$radio_id2\"></label></div></div></td>";
							echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
							if ($data_evaluasi[$radio_name] == "3") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"3\" id=\"$radio_id3\" checked>";
							else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"3\" id=\"$radio_id3\">";
							echo "<label for=\"$radio_id3\"></label></div></div></td>";
							echo "<td align=center style=\"vertical-align:middle;width:12%\"><div class=\"clearfix\"><div class=\"radio\">";
							if ($data_evaluasi[$radio_name] == "4") echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"4\" id=\"$radio_id4\" checked>";
							else echo "<input type=\"radio\" style=\"width:20px; height:20px;\" name=\"$radio_name\" value=\"4\" id=\"$radio_id4\">";
							echo "<label for=\"$radio_id4\"></label></div></div></td>";
							echo "</tr>";
							echo "</table>";
							echo "</td>";
							echo "</tr>";
							$no++;
						}
						echo "<tr><td>&nbsp;</td></tr>";
						//Komentar disukai stase luar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em;font-weight:600;\">Tuliskan hal-hal yang Anda sukai dari stase luar ini: </font><font style=\"font-size:0.85em;color:red; font-weight:600; \"> *</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><textarea name=\"like_luar\" style=\"font-family:Poppins;font-size:0.85em;width:100%;border:0.5px solid grey;border-radius:5px;\" rows=5>$data_evaluasi[like_luar]</textarea></td>";
						echo "</tr>";
						//Komentar tidak disukai stase luar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em;font-weight:600;\">Tuliskan hal-hal yang tidak ada Anda sukai/perlu diperbaiki dari stase luar ini: </font><font style=\"font-size:0.85em;color:red;font-weight:600; \"> *</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><textarea name=\"unlike_luar\" style=\"font-family:Poppins;font-size:0.85em;width:100%;border:0.5px solid grey;border-radius:5px;\" rows=5>$data_evaluasi[unlike_luar]</textarea></td>";
						echo "</tr>";

						//Catatan
						echo "<tr>";
						echo '<td>
   							 <font style="font-size:0.85em; font-weight:600;">
       						 <span>Catatan:<br>
       						 <span style="color:darkblue;">Area kompetensi 1 : Profesionalitas yang luhur</span><br>
        					<span style="color:darkred;">Area kompetensi 2 : Mawas diri dan pengembangan diri</span><br>
       						 <span style="color:purple;">Area kompetensi 3 : Komunikasi efektif</span><br>
        					<span style="color:darkgreen;">Area kompetensi 4 : Pengelolaan informasi</span></span>
    						</font>
							</td>';

						echo "</tr>";


						echo "</table>";

						echo "<br><br><button type=\"submit\" class=\"btn btn-danger\" name=\"batal\" value=\"BATAL\">
       						 <i class=\"fa fa-circle-xmark me-2\"></i> BATAL
      							</button>";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;<button type=\"submit\" class=\"btn btn-success\" name=\"ubah\" value=\"UBAH\">
       						 <i class=\"fa fa-edit me-2\"></i> UBAH
      							</button>";
						echo "<br><br></form>";

						if ($_POST['batal'] == "BATAL") {
							echo "
				<script>
					window.location.href=\"rotasi_internal.php\";
				</script>
			";
						}

						if ($_POST['ubah'] == "UBAH") {
							$id_stase = $_POST['id_stase'];
							$jml_evaluasi = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `evaluasi_stase` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'"));
							if ($jml_evaluasi < 1) {
								$insert_evaluasi = mysqli_query($con, "INSERT INTO `evaluasi_stase`
					( `nim`, `stase`, `tgl_isi`,
						`input_111`, `input_112`, `input_113`, `input_114`, `input_115`, `input_116`, `input_117`, `input_118`, `input_119`, `input_1110`, `input_1111`, `input_1112`, `input_1113`, `input_1114`,
						`refleksi`, `komentar`,
						`input_12`, `input_13`,
						`dosen1`, `tatap_muka1`,
						`input_21A1`, `input_21A2`, `input_21A3`, `input_21A4`, `input_21A5`, `input_21A6`, `input_21A7`,
						`input_21B1`, `input_21B2`, `input_21B3`, `input_21B4`, `input_21B5`, `input_21B6`, `input_21B7`, `input_21B8`, `input_21B9`, `input_21B10`, `input_21B11`, `input_21B12`,
						`input_21C1`, `input_21C2`, `input_21C3`,
						`komentar_dosen1`,
						`dosen2`, `tatap_muka2`,
						`input_22A1`, `input_22A2`, `input_22A3`, `input_22A4`, `input_22A5`, `input_22A6`, `input_22A7`,
						`input_22B1`, `input_22B2`, `input_22B3`, `input_22B4`, `input_22B5`, `input_22B6`, `input_22B7`, `input_22B8`, `input_22B9`, `input_22B10`, `input_22B11`, `input_22B12`,
						`input_22C1`, `input_22C2`, `input_22C3`,
						`komentar_dosen2`,
						`dosen3`, `tatap_muka3`,
						`input_23A1`, `input_23A2`, `input_23A3`, `input_23A4`, `input_23A5`, `input_23A6`, `input_23A7`,
						`input_23B1`, `input_23B2`, `input_23B3`, `input_23B4`, `input_23B5`, `input_23B6`, `input_23B7`, `input_23B8`, `input_23B9`, `input_23B10`, `input_23B11`, `input_23B12`,
						`input_23C1`, `input_23C2`, `input_23C3`,
						`komentar_dosen3`,
						`lokasi_luar`, `lama_luar`,
						`input_3A1`, `input_3A2`, `input_3A3`, `input_3A4`, `input_3A5`, `input_3A6`, `input_3A7`, `input_3A8`, `input_3A9`, `input_3A10`,
						`like_luar`, `unlike_luar`)
						VALUES
						( '$_COOKIE[user]', '$id_stase', '$_POST[tgl_isi]',
						  '$_POST[input_111]', '$_POST[input_112]', '$_POST[input_113]', '$_POST[input_114]', '$_POST[input_115]', '$_POST[input_116]', '$_POST[input_117]', '$_POST[input_118]', '$_POST[input_119]', '$_POST[input_1110]', '$_POST[input_1111]', '$_POST[input_1112]', '$_POST[input_1113]', '$_POST[input_1114]',
						  '$_POST[refleksi]', '$_POST[komentar]',
						  '$_POST[input_12]', '$_POST[input_13]',
						  '$_POST[dosen1]', '$_POST[tatap_muka1]',
						  '$_POST[input_21A1]', '$_POST[input_21A2]', '$_POST[input_21A3]', '$_POST[input_21A4]', '$_POST[input_21A5]', '$_POST[input_21A6]', '$_POST[input_21A7]',
						  '$_POST[input_21B1]', '$_POST[input_21B2]', '$_POST[input_21B3]', '$_POST[input_21B4]', '$_POST[input_21B5]', '$_POST[input_21B6]', '$_POST[input_21B7]', '$_POST[input_21B8]', '$_POST[input_21B9]', '$_POST[input_21B10]', '$_POST[input_21B11]', '$_POST[input_21B12]',
						  '$_POST[input_21C1]', '$_POST[input_21C2]', '$_POST[input_21C3]',
						  '$_POST[komentar_dosen1]',
						  '$_POST[dosen2]', '$_POST[tatap_muka2]',
						  '$_POST[input_22A1]', '$_POST[input_22A2]', '$_POST[input_22A3]', '$_POST[input_22A4]', '$_POST[input_22A5]', '$_POST[input_22A6]', '$_POST[input_22A7]',
						  '$_POST[input_22B1]', '$_POST[input_22B2]', '$_POST[input_22B3]', '$_POST[input_22B4]', '$_POST[input_22B5]', '$_POST[input_22B6]', '$_POST[input_22B7]', '$_POST[input_22B8]', '$_POST[input_22B9]', '$_POST[input_22B10]', '$_POST[input_22B11]', '$_POST[input_22B12]',
						  '$_POST[input_22C1]', '$_POST[input_22C2]', '$_POST[input_22C3]',
						  '$_POST[komentar_dosen2]',
						  '$_POST[dosen3]', '$_POST[tatap_muka3]',
						  '$_POST[input_23A1]', '$_POST[input_23A2]', '$_POST[input_23A3]', '$_POST[input_23A4]', '$_POST[input_23A5]', '$_POST[input_23A6]', '$_POST[input_23A7]',
						  '$_POST[input_23B1]', '$_POST[input_23B2]', '$_POST[input_23B3]', '$_POST[input_23B4]', '$_POST[input_23B5]', '$_POST[input_23B6]', '$_POST[input_23B7]', '$_POST[input_23B8]', '$_POST[input_23B9]', '$_POST[input_23B10]', '$_POST[input_23B11]', '$_POST[input_23B12]',
						  '$_POST[input_23C1]', '$_POST[input_23C2]', '$_POST[input_23C3]',
						  '$_POST[komentar_dosen3]',
						  '$_POST[lokasi_luar]', '$_POST[lama_luar]',
						  '$_POST[input_3A1]', '$_POST[input_3A2]', '$_POST[input_3A3]', '$_POST[input_3A4]', '$_POST[input_3A5]', '$_POST[input_3A6]', '$_POST[input_3A7]', '$_POST[input_3A8]', '$_POST[input_3A9]', '$_POST[input_3A10]',
						  '$_POST[like_luar]', '$_POST[unlike_luar]')");

								/*if (!$insert_evaluasi) echo "Insert: Something wrong ...<br><br>";
					else echo "Insert: OK ...<br><br>";*/
							} else {
								$update_evaluasi = mysqli_query($con, "UPDATE `evaluasi_stase` SET
					`tgl_isi`='$_POST[tgl_isi]',
					`input_111`='$_POST[input_111]',`input_112`='$_POST[input_112]',`input_113`='$_POST[input_113]',`input_114`='$_POST[input_114]',`input_115`='$_POST[input_115]',`input_116`='$_POST[input_116]',`input_117`='$_POST[input_117]',`input_118`='$_POST[input_118]',`input_119`='$_POST[input_119]',`input_1110`='$_POST[input_1110]',`input_1111`='$_POST[input_1111]',`input_1112`='$_POST[input_1112]',`input_1113`='$_POST[input_1113]',`input_1114`='$_POST[input_1114]',
					`refleksi`='$_POST[refleksi]',`komentar`='$_POST[komentar]',
					`input_12`='$_POST[input_12]',`input_13`='$_POST[input_13]',
					`dosen1`='$_POST[dosen1]',`tatap_muka1`='$_POST[tatap_muka1]',
					`input_21A1`='$_POST[input_21A1]',`input_21A2`='$_POST[input_21A2]',`input_21A3`='$_POST[input_21A3]',`input_21A4`='$_POST[input_21A4]',`input_21A5`='$_POST[input_21A5]',`input_21A6`='$_POST[input_21A6]',`input_21A7`='$_POST[input_21A7]',
					`input_21B1`='$_POST[input_21B1]',`input_21B2`='$_POST[input_21B2]',`input_21B3`='$_POST[input_21B3]',`input_21B4`='$_POST[input_21B4]',`input_21B5`='$_POST[input_21B5]',`input_21B6`='$_POST[input_21B6]',`input_21B7`='$_POST[input_21B7]',`input_21B8`='$_POST[input_21B8]',`input_21B9`='$_POST[input_21B9]',`input_21B10`='$_POST[input_21B10]',`input_21B11`='$_POST[input_21B11]',`input_21B12`='$_POST[input_21B12]',
					`input_21C1`='$_POST[input_21C1]',`input_21C2`='$_POST[input_21C2]',`input_21C3`='$_POST[input_21C3]',
					`komentar_dosen1`='$_POST[komentar_dosen1]',
					`dosen2`='$_POST[dosen2]',`tatap_muka2`='$_POST[tatap_muka2]',
					`input_22A1`='$_POST[input_22A1]',`input_22A2`='$_POST[input_22A2]',`input_22A3`='$_POST[input_22A3]',`input_22A4`='$_POST[input_22A4]',`input_22A5`='$_POST[input_22A5]',`input_22A6`='$_POST[input_22A6]',`input_22A7`='$_POST[input_22A7]',
					`input_22B1`='$_POST[input_22B1]',`input_22B2`='$_POST[input_22B2]',`input_22B3`='$_POST[input_22B3]',`input_22B4`='$_POST[input_22B4]',`input_22B5`='$_POST[input_22B5]',`input_22B6`='$_POST[input_22B6]',`input_22B7`='$_POST[input_22B7]',`input_22B8`='$_POST[input_22B8]',`input_22B9`='$_POST[input_22B9]',`input_22B10`='$_POST[input_22B10]',`input_22B11`='$_POST[input_22B11]',`input_22B12`='$_POST[input_22B12]',
					`input_22C1`='$_POST[input_22C1]',`input_22C2`='$_POST[input_22C2]',`input_22C3`='$_POST[input_22C3]',
					`komentar_dosen2`='$_POST[komentar_dosen2]',
					`dosen3`='$_POST[dosen3]',`tatap_muka3`='$_POST[tatap_muka3]',
					`input_23A1`='$_POST[input_23A1]',`input_23A2`='$_POST[input_23A2]',`input_23A3`='$_POST[input_23A3]',`input_23A4`='$_POST[input_23A4]',`input_23A5`='$_POST[input_23A5]',`input_23A6`='$_POST[input_23A6]',`input_23A7`='$_POST[input_23A7]',
					`input_23B1`='$_POST[input_23B1]',`input_23B2`='$_POST[input_23B2]',`input_23B3`='$_POST[input_23B3]',`input_23B4`='$_POST[input_23B4]',`input_23B5`='$_POST[input_23B5]',`input_23B6`='$_POST[input_23B6]',`input_23B7`='$_POST[input_23B7]',`input_23B8`='$_POST[input_23B8]',`input_23B9`='$_POST[input_23B9]',`input_23B10`='$_POST[input_23B10]',`input_23B11`='$_POST[input_23B11]',`input_23B12`='$_POST[input_23B12]',
					`input_23C1`='$_POST[input_23C1]',`input_23C2`='$_POST[input_23C2]',`input_23C3`='$_POST[input_23C3]',
					`komentar_dosen3`='$_POST[komentar_dosen3]',
					`lokasi_luar`='$_POST[lokasi_luar]',`lama_luar`='$_POST[lama_luar]',
					`input_3A1`='$_POST[input_3A1]',`input_3A2`='$_POST[input_3A2]',`input_3A3`='$_POST[input_3A3]',`input_3A4`='$_POST[input_3A4]',`input_3A5`='$_POST[input_3A5]',`input_3A6`='$_POST[input_3A6]',`input_3A7`='$_POST[input_3A7]',`input_3A8`='$_POST[input_3A8]',`input_3A9`='$_POST[input_3A9]',`input_3A10`='$_POST[input_3A10]',
					`like_luar`='$_POST[like_luar]',`unlike_luar`='$_POST[unlike_luar]'
					WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'");

								/*if (!$update_evaluasi) echo "Update: Something wrong ...<br><br>";
					else echo "Update: OK ...<br><br>";*/
							}
							$stase_id = "stase_" . $id_stase;
							$update_stase = mysqli_query($con, "UPDATE `$stase_id` SET `evaluasi`='1' WHERE `nim`='$_COOKIE[user]'");

							echo "
				<script>
					window.location.href=\"rotasi_internal.php\";
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

	<script src="javascript/script1.js"></script>
	<script src="javascript/buttontopup.js"></script>
	<script src="jquery.min.js"></script>
	<script src="select2/dist/js/select2.js"></script>

	<script>
		$(document).ready(function() {
			$("#dosen1").select2({
				placeholder: "< Pilihan Dosen >",
				allowClear: true
			});
			$("#dosen2").select2({
				placeholder: "< Pilihan Dosen >",
				allowClear: true
			});
			$("#dosen3").select2({
				placeholder: "< Pilihan Dosen >",
				allowClear: true
			});
			$("#lama_luar").select2({
				placeholder: "< Pilihan Minggu >",
				allowClear: true
			});
		});
	</script>
</body>

</html>