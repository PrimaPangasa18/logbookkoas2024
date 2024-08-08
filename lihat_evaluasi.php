<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Informasi Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">
	<link rel="stylesheet" href="style/informasi.css">

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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2 or $_COOKIE['level'] == 3 or $_COOKIE['level'] == 5)) {
				if ($_COOKIE['level'] == 1) {
					include "menu1.php";
				}
				if ($_COOKIE['level'] == 2) {
					include "menu2.php";
				}
				if ($_COOKIE['level'] == 3) {
					include "menu3.php";
				}
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
		if ($_COOKIE['level'] != 1) {
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
			<nav class="navbar navbar-expand px-4 py-3">
				<form action="#" class="d-none d-sm-inline-block">
					<div class="input-group input-group-navbar">
						<img src="images/undipsolid.png" alt="" style="width: 45px;">
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
			<main class="content px-3 py-4">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">EVALUASI KEPANITERAAN (STASE)</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">HASIL EVALUASI KEPANITERAAN (STASE)</h2>
						<br>
						<?php
						$id_stase = $_GET['id'];
						$nim_mhsw = $_GET['nim'];
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"nim\" value=\"$nim_mhsw\" />";
						echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\" />";
						echo "<input type=\"hidden\" name=\"menu\" value=\"$_GET[menu]\" />";

						$kepaniteraan = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$data_evaluasi = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw' AND `stase`='$id_stase'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw'"));
						echo "<center>";
						echo "<table  class=\" table table-warning\"; style=\"width:60%; border\">";
						echo "<tr>";
						echo "<td align=center><img src=\"images/evaluasi_header.jpg\" style=\"width:100%;height:auto\"></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td align=center><font style=\"font-family:Poppins;font-size:1.25em\">Evaluasi Pelaksanaan Kepaniteraan (STASE) $kepaniteraan[kepaniteraan]</font></td>";
						echo "</tr>";
						if ($_COOKIE['level'] != 5) {
							echo "<tr>";
							echo "<td align=center><br><font style=\"font-size:1em; font-weight:600;\">Nama Mahasiswa [NIM]<br><span style=\"color:darkgreen;\">$data_mhsw[nama]</span> <span style=\"color:red;\">[$nim_mhsw]</span><br><br></font></td>";
							echo "</tr>";
						}
						echo "<tr><td>&nbsp;</td></tr>";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700;\">1. SISTEM PEMBELAJARAN DALAM STASE</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700; color:red;\">Penilaian Materi Pembelajaran</font></td>";
						echo "</tr>";

						//1. Pembelajaran
						$eval_pemb = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Pembelajaran' ORDER BY `id`");
						$no = 1;
						while ($data_pemb = mysqli_fetch_array($eval_pemb)) {
							echo "<tr>";
							echo "<td>";
							echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr>";
							$radio_name = "input_11" . $no;
							echo "<td style=\"width:52%\"><font style=\"font-size:0.9em; font-weight:700;\">$data_pemb[pertanyaan]<br><span>Jawaban: ";
							if ($data_evaluasi[$radio_name] == '1') {
								echo "<span style=\"color:darkred;\">Sangat tidak setuju</span>";
							} elseif ($data_evaluasi[$radio_name] == '2') {
								echo "<span style=\"color:red;\">Tidak setuju</span>";
							} elseif ($data_evaluasi[$radio_name] == '3') {
								echo "<span style=\"color:green;\">Setuju</span>";
							} elseif ($data_evaluasi[$radio_name] == '4') {
								echo "<span style=\"color:darkgreen;\">Sangat setuju</span>";
							}
							echo "</span></font></td>";

							echo "</tr>";
							echo "</table>";
							echo "</td>";
							echo "</tr>";
							$no++;
						}
						//Refleksi diri
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em; font-weight: 700;\">Tuliskan refleksi diri anda setelah menjalankan stase kepaniteraan ini: </td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>";
						echo "<table style=\"background-color:white;border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td><font style=\"font-family:Poppins;font-size:0.9em\"><span>$data_evaluasi[refleksi]</span></font></td>";
						echo "</tr>";
						echo "</table>";
						echo "</td>";
						echo "</tr>";
						//Komentar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em; font-weight: 700;\">Silakan tulis dalam kolom di bawah ini komentar, usul, saran, atau kritik dalam bahasa yang santun: </font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>";
						echo "<table style=\"background-color:white;border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td><font style=\"font-family:Poppins;font-size:0.9em\"><span>$data_evaluasi[komentar]</span></font></td>";
						echo "</tr>";
						echo "</table>";
						echo "</td>";
						echo "</tr>";
						//Pencapaian
						echo "<tr>";
						echo "<td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						$radio_name = "input_12";
						echo "<td style=\"width:52%\"><font style=\"font-size:0.9em; font-weight: 700;\">Menurut Anda, seberapa banyak pencapaian kompetensi level 3A, 3B, 4A yang Anda capat dalam kepaniteraan Bagian ini (termasuk dengan stase luar kepaniteraan ini)?<br>";
						echo "<span>Jawaban: ";
						if ($data_evaluasi[$radio_name] == '1') {
							echo "<span style=\"color:darkred;\">< 25%</span>";
						} elseif ($data_evaluasi[$radio_name] == '2') {
							echo "<span style=\"color:red;\">25-50%</span>";
						} elseif ($data_evaluasi[$radio_name] == '3') {
							echo "<span style=\"color:blue;\">50-75%</span>";
						} elseif ($data_evaluasi[$radio_name] == '4') {
							echo "<span style=\"color:green;\">> 75%</span>";
						}
						echo "</span></font></td>";

						echo "</tr>";
						echo "</table>";
						echo "</td>";
						echo "</tr>";
						//Kepuasan
						echo "<tr>";
						echo "<td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						$radio_name = "input_13";
						echo "<td style=\"width:52%\"><font style=\"font-size:0.9em; font-weight: 700;\">Seberapa besar kepuasan Anda terhadap keseluruhan program stase di Bagian ini (termasuk program stase luar)?<br>";
						echo "<span>Jawaban: ";
						if ($data_evaluasi[$radio_name] == '1') {
							echo "<span style=\"color:darkred;\">< 25%</span>";
						} elseif ($data_evaluasi[$radio_name] == '2') {
							echo "<span style=\"color:red;\">25-50%</span>";
						} elseif ($data_evaluasi[$radio_name] == '3') {
							echo "<span style=\"color:blue;\">50-75%</span>";
						} elseif ($data_evaluasi[$radio_name] == '4') {
							echo "<span style=\"color:green;\">> 75%</span>";
						}
						echo "</span></font></td>";

						echo "</tr>";
						echo "</table>";
						echo "</td>";
						echo "</tr>";
						echo "<tr><td>&nbsp;</td></tr>";
						//2. Dosen
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em; font-weight: 700;\">2. DOSEN <br>";
						echo "Berikan evaluasi Anda bagi minimal <span style=\"color:red;\" > 3 (tiga)  dosen </span> dalam kepaniteraan ini yang melakukan minimal <span style=\"color:red;\" > 2 (dua) kali </span> tatap muka dengan Anda. Bila tidak ada, pilih 3 dosen dengan kuantitas dan/atau kualitas pertemuan paling banyak.";
						echo "<br>Aspek yang tidak bisa dinilai <span style=\"color:red;\" > (misalnya, karena mahasiswa tidak tahu pasti)  </span>  tidak perlu dinilai <span style=\"color:red;\" > (kolom dikosongkan)</span>.</font></td>";
						echo "</tr>";
						for ($x = 1; $x <= 3; $x++) {
							echo "<tr><td>&nbsp;</td></tr>";
							//Pilihan Dosen x
							echo "<tr>";
							$dosenx = "dosen" . $x;
							$dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_evaluasi[$dosenx]'"));
							echo "<td><font style=\"font-size:0.85em; font-weight: 700;\">2.$x. Nama Dosen: <span style=\"color:blue;\">$dosen[nama]</span>, <span style=\"color:red;\">$dosen[gelar]</span></span></font>";
							echo "</td>";
							echo "</tr>";
							//Jumlah Jam Tatap Muka Dosen x
							echo "<tr>";
							$tatap_mukax = "tatap_muka" . $x;
							echo "<td><font style=\"font-size:0.85em; font-weight: 700;\">Jumlah jam tatap muka: <span style=\"color:red;\" >$data_evaluasi[$tatap_mukax] jam</span></font>";
							echo "</td>";
							echo "</tr>";
							//Dosen x Umum
							echo "<tr>";
							echo "<td><font style=\"font-size:0.9em; font-weight:700; color:red;\">A. Umum </font></td>";
							echo "</tr>";
							$dosen_umum = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_umum' ORDER BY `id`");
							$no = 1;
							while ($data_umum = mysqli_fetch_array($dosen_umum)) {
								echo "<tr>";
								echo "<td>";
								echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
								echo "<tr>";
								$radio_name = "input_2" . $x . "A" . $no;
								echo "<td style=\"width:52%\"><font style=\"font-size:0.9em; font-weight:700;\">$data_umum[pertanyaan]<br><span>Jawaban: ";
								if ($data_evaluasi[$radio_name] == '1') {
									echo "<span style=\"color:darkred;\">Sangat tidak setuju</span>";
								} elseif ($data_evaluasi[$radio_name] == '2') {
									echo "<span style=\"color:red;\">Tidak setuju</span>";
								} elseif ($data_evaluasi[$radio_name] == '3') {
									echo "<span style=\"color:green;\">Setuju</span>";
								} elseif ($data_evaluasi[$radio_name] == '4') {
									echo "<span style=\"color:darkgreen;\">Sangat setuju</span>";
								}
								echo "</span></font></td>";
								echo "</tr>";
								echo "</table>";
								echo "</td>";
								echo "</tr>";
								$no++;
							}
							//Dosen x Pengajar
							echo "<tr>";
							echo "<td><font style=\"font-size:0.9em; font-weight:700; color:red;\">B. Dosen sebagai pengajar</font></td>";
							echo "</tr>";
							$dosen_pengajar = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_pengajar' ORDER BY `id`");
							$no = 1;
							while ($data_pengajar = mysqli_fetch_array($dosen_pengajar)) {
								echo "<tr>";
								echo "<td>";
								echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
								echo "<tr>";
								$radio_name = "input_2" . $x . "B" . $no;
								echo "<td style=\"width:52%\"><font style=\"font-size:0.9em; font-weight:700;\">$data_pengajar[pertanyaan]<br><span>Jawaban: ";
								if ($data_evaluasi[$radio_name] == '1') {
									echo "<span style=\"color:darkred;\">Sangat tidak setuju</span>";
								} elseif ($data_evaluasi[$radio_name] == '2') {
									echo "<span style=\"color:red;\">Tidak setuju</span>";
								} elseif ($data_evaluasi[$radio_name] == '3') {
									echo "<span style=\"color:green;\">Setuju</span>";
								} elseif ($data_evaluasi[$radio_name] == '4') {
									echo "<span style=\"color:darkgreen;\">Sangat setuju</span>";
								}
								echo "</span></font></td>";
								echo "</tr>";
								echo "</table>";
								echo "</td>";
								echo "</tr>";
								$no++;
							}
							//Dosen x Penguji
							echo "<tr>";
							echo "<td><font style=\"font-size:0.9em; font-weight:700; color:red;\">C. Dosen sebagai penguji</font></td>";
							echo "</tr>";
							$dosen_penguji = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_penguji' ORDER BY `id`");
							$no = 1;
							while ($data_penguji = mysqli_fetch_array($dosen_penguji)) {
								echo "<tr>";
								echo "<td>";
								echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
								echo "<tr>";
								$radio_name = "input_2" . $x . "C" . $no;
								echo "<td style=\"width:52%\"><font style=\"font-size:0.9em; font-weight:700;\">$data_penguji[pertanyaan]<br><span>Jawaban: ";
								if ($data_evaluasi[$radio_name] == '1') {
									echo "<span style=\"color:darkred;\">Sangat tidak setuju</span>";
								} elseif ($data_evaluasi[$radio_name] == '2') {
									echo "<span style=\"color:red;\">Tidak setuju</span>";
								} elseif ($data_evaluasi[$radio_name] == '3') {
									echo "<span style=\"color:green;\">Setuju</span>";
								} elseif ($data_evaluasi[$radio_name] == '4') {
									echo "<span style=\"color:darkgreen;\">Sangat setuju</span>";
								}
								echo "</span></font></td>";
								echo "</tr>";
								echo "</table>";
								echo "</td>";
								echo "</tr>";
								$no++;
							}
							//Komentar dosen x
							echo "<tr>";
							echo "<td><font style=\"font-size:0.9em; font-weight:700;\">Silakan mengisi pada kolom di bawah ini dengan bahasa yang santun komentar, usul, saran atau masukan: </font></td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>";
							echo "<table style=\"background-color:white;border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr>";
							$komentar_dosenx = "komentar_dosen" . $x;
							echo "<td><font style=\"font-family:Poppins;font-size:0.9em\"><span>$data_evaluasi[$komentar_dosenx]</span></font></td>";
							echo "</tr>";
							echo "</table>";
							echo "</td>";
							echo "</tr>";
						}
						echo "<tr><td>&nbsp;</td></tr>";
						//Evaluasi stase luar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700;\">EVALUASI STASE LUAR<br>
					Isilah kuesioner berikut apabila dalam stase yang Anda evaluasi ini ada stase luar.</font></td>";
						echo "</tr>";
						//Lokasi stase luar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700;\">Lokasi stase luar: <span style=\"color: blue\">$data_evaluasi[lokasi_luar]</span></font>";
						echo "</td>";
						echo "</tr>";
						//Lama stase luar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700;\">Lama stase (minggu): <span style=\"color: darkred\">$data_evaluasi[lama_luar] minggu</span></font>";
						echo "</td>";
						echo "</tr>";
						echo "<tr><td>&nbsp;</td></tr>";
						//A. Penilaian Materi Pembelajaran
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700; color:red;\">A. Penilaian Materi Pembelajaran </font></td>";
						echo "</tr>";
						echo "<tr>";
						$materi = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Stase_luar' ORDER BY `id`");
						$no = 1;
						while ($data_materi = mysqli_fetch_array($materi)) {
							echo "<tr>";
							echo "<td>";
							echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr>";
							$radio_name = "input_3A" . $no;
							echo "<td style=\"width:52%\"><font style=\"font-size:0.9em; font-weight:700;\">$data_materi[pertanyaan]<br><span>Jawaban: ";
							if ($data_evaluasi[$radio_name] == '1') {
								echo "<span style=\"color:darkred;\">Sangat tidak setuju</span>";
							} elseif ($data_evaluasi[$radio_name] == '2') {
								echo "<span style=\"color:red;\">Tidak setuju</span>";
							} elseif ($data_evaluasi[$radio_name] == '3') {
								echo "<span style=\"color:green;\">Setuju</span>";
							} elseif ($data_evaluasi[$radio_name] == '4') {
								echo "<span style=\"color:darkgreen;\">Sangat setuju</span>";
							}
							echo "</span></font></td>";
							echo "</tr>";
							echo "</table>";
							echo "</td>";
							echo "</tr>";
							$no++;
						}
						echo "<tr><td>&nbsp;</td></tr>";
						//Komentar disukai stase luar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em; font-weight:700;\">Tuliskan hal-hal yang Anda sukai dari stase luar ini: </font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>";
						echo "<table style=\"background-color:white;border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td><font style=\"font-family:Poppins;font-size:0.9em\"><span>$data_evaluasi[like_luar]</span></font></td>";
						echo "</tr>";
						echo "</table>";
						echo "</td>";
						echo "</tr>";
						//Komentar tidak disukai stase luar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em; font-weight:700;\">Tuliskan hal-hal yang tidak ada Anda sukai/perlu diperbaiki dari stase luar ini: </font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>";
						echo "<table style=\"background-color:white;border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td><font style=\"font-family:Poppins;font-size:0.9em\"><span>$data_evaluasi[unlike_luar]</span></font></td>";
						echo "</tr>";
						echo "</table>";
						echo "</td>";
						echo "</tr>";
						//Catatan
						echo "<tr>";
						echo '<td>
   							 <font style="font-size:0.85em; font-weight:700;">
       						 <span>Catatan:<br>
       						 <span style="color:darkblue;">Area kompetensi 1 : Profesionalitas yang luhur</span><br>
        					<span style="color:darkred;">Area kompetensi 2 : Mawas diri dan pengembangan diri</span><br>
       						 <span style="color:purple;">Area kompetensi 3 : Komunikasi efektif</span><br>
        					<span style="color:darkgreen;">Area kompetensi 4 : Pengelolaan informasi</span></span>
    						</font>
							</td>';

						echo "</tr>";

						echo "</table>";

						echo "<br><br><button type=\"submit\" class=\"btn btn-primary\" name=\"back\" value=\"BACK\">
       						 <i class=\"fa-solid fa-backward me-2\"></i> BACK
      							</button>";
						echo "<br><br></form>";

						if ($_POST['back'] == "BACK") {
							if ($_COOKIE['level'] != '5') {
								if ($_POST['menu'] == "rekap") {
									echo "
					<script>
						window.location.href=\"rekap_indstase_admin.php?id=\"+\"$_POST[id_stase]\"+\"&nim=\"+\"$_POST[nim]\";
					</script>
					";
								}
								if ($_POST['menu'] == "rotasi") {
									echo "
					<script>
						window.location.href=\"rotasi_internal.php?user_name=\"+\"$_POST[nim]\";
					</script>
					";
								}
							} else {
								echo "
				<script>
					window.location.href=\"rekap_indstase.php?id=\"+\"$_POST[id_stase]\";
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