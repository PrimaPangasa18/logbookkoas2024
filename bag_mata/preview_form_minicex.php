<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Preview MiniCex Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN MATA</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">PREVIEW NILAI UJIAN MINI-CEX<br>KEPANITERAAN (STASE) ILMU KESEHATAN MATA</h2>
						<br>
						<?php
						$id_stase = "M104";
						$id = $_GET['id'];
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
						$data_minicex = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `mata_nilai_minicex` WHERE `id`='$id'"));

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
						echo "<td class=\"td_mid\" style=\"font-weight:600;\"><span style=\"color:darkblue\">$mulai_stase</span> s.d. <span style=\"color:darkblue\">$selesai_stase</span></td>";
						echo "</tr>";
						//Tanggal Ujian
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tanggal Ujian</strong></td>";
						$tanggal_ujian = tanggal_indo($data_minicex['tgl_ujian']);
						echo "<td style=\"font-weight:600;\">$tanggal_ujian</td>";
						echo "</tr>";
						//Waktu Observasi
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Waktu Observasi</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_minicex[waktu_obs]&nbsp;&nbsp;Menit</td>";
						echo "</tr>";
						//Waktu Umpan Balik
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Waktu Umpan Balik</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_minicex[waktu_ub]&nbsp;&nbsp;menit</td>";
						echo "</tr>";
						//Dosen Penguji
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Penguji</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
						echo "$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue\">$data_dosen[nip]</span>)";
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
						echo "<th style=\"width:58%;text-align:center;\">Komponen Penilaian Ketrampilan</th>";
						echo "<th style=\"width:22%;text-align:center;\">Observasi</th>";
						echo "<th style=\"width:20%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//KOMUNIKASI
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>1. KOMUNIKASI</strong></td>";
						echo "</tr>";
						//No 1.1.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.1.</strong></td>";
						echo "<td><strong>Memperkenalkan diri dan menjelaskan perannya kepada pasien</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_11'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_11]</td>";
						echo "</tr>";
						//No 1.2.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.2.</strong></td>";
						echo "<td><strong>Memperlihatkan kontak mata yang baik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_12'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_12]</td>";
						echo "</tr>";
						//No 1.3.
						echo "<tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.3.</strong></td>";
						echo "<td><strong>Mendengarkan pasien tanpa menginterupsi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_13'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_13]</td>";
						echo "</tr>";
						//No 1.4.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.4.</strong></td>";
						echo "<td><strong>Mengekspresikan perhatiannya kepada pasien</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_14'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_14]</td>";
						echo "</tr>";
						//No 1.5.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.5.</strong></td>";
						echo "<td><strong>Bertanya dengan pertanyaan terbuka</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_15'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_15]</td>";
						echo "</tr>";
						//No 1.6.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.6.</strong></td>";
						echo "<td><strong>Memberi kesempatan kepada pasien untuk bertanya</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_16'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_16]</td>";
						echo "</tr>";
						//No 1.7.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.7.</strong></td>";
						echo "<td><strong>Menjelaskan perencanaan selanjutnya dengan baik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_17'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_17]</td>";
						echo "</tr>";

						//2. PROFESIONALISME
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>2. PROFESIONALISME</strong></td>";
						echo "</tr>";
						//No 2.1.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2.1.</strong></td>";
						echo "<td><strong>Mengenakan pakaian yang pantas</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_21'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_21]</td>";
						echo "</tr>";
						//No 2.2.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2.2.</strong></td>";
						echo "<td><strong>Sopan / hormat pada pasien</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_22'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_22]</td>";
						echo "</tr>";
						//No 2.3.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2.3.</strong></td>";
						echo "<td><strong>Memperlihatkan sikap profesional <span class=\"text-danger\">(memanggil nama pasien, memperlihatkan keseriusan dan kompeten)</span><strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_23'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_23]</td>";
						echo "</tr>";
						//No 2.4.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2.4.</strong></td>";
						echo "<td><strong>Menghargai kebebasan / kerahasiaan pribadi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_24'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_24]</td>";
						echo "</tr>";

						//KETRAMPILAN KLINIK
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>3. KETRAMPILAN KLINIK</strong></td>";
						echo "</tr>";
						//No 3.1.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.1.</strong></td>";
						echo "<td><strong>Mencuci tangan sebelum dan sesudah  memeriksa pasien</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_31'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_31]</td>";
						echo "</tr>";
						//No 3.2.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.2.</strong></td>";
						echo "<td><strong>Pemeriksaan visus</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_32'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_32]</td>";
						echo "</tr>";
						//No 3.3.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.3.</strong></td>";
						echo "<td><strong>Pemeriksaan segmen anterior</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_33'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_33]</td>";
						echo "</tr>";
						//No 3.4.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.4.</strong></td>";
						echo "<td><strong>Pemeriksaan funduskopi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_34'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_34]</td>";
						echo "</tr>";
						//No 3.5.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.5.</strong></td>";
						echo "<td><strong>Pemeriksaan tonometri</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_35'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_35]</td>";
						echo "</tr>";
						//No 3.6.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.6.</strong></td>";
						echo "<td><strong>Pemeriksaan lapang pandang</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_36'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_36]</td>";
						echo "</tr>";

						//CLINICAL REASONING
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>4. CLINICAL REASONING</strong></td>";
						echo "</tr>";
						//No 4.1.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>4.1.</strong></td>";
						echo "<td><strong>Diagnosis</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_41'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_41]</td>";
						echo "</tr>";
						//No 4.2.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>4.2.</strong></td>";
						echo "<td><strong>Tatalaksana</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_42'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_42]</td>";
						echo "</tr>";
						//No 4.3.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>5.3.</strong></td>";
						echo "<td><strong>Edukasi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_43'] == "1") echo "Ya";
						else echo "Tidak";
						echo "</td>";
						echo "<td align=center style=\"font-weight:600;\">$data_minicex[aspek_43]</td>";
						echo "</tr>";

						//Nilai Total
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=right><strong>Nilai Rata-Rata <span class=\"text-danger\">(Jumlah Nilai / Jumlah Observasi)</span></strong></td>";
						echo "<td align=center style=\"font-weight:600; color:blue;\">$data_minicex[nilai_rata]</td>";
						echo "</tr>";

						//Komentar / Saran
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4><strong>Komentar / Saran:</strong><br>
						<textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" disabled>$data_minicex[saran]</textarea></td>";
						echo "</tr>";

						echo "<tr  class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=4 align=right><br><span style=\"font-weight:600;\">Status: </span><strong class=\"text-danger\" >BELUM DISETUJUI</strong><br>";
						echo "<span style=\"font-weight:600;\">Dosen Penguji:<p><span style=\"font-weight:500;\">$data_dosen[nama]</span>, <span style=\"font-weight:500; color:red\">$data_dosen[gelar]</span><br>NIP. <span style=\"font-weight:600; color:blue;\">$data_dosen[nip]</span></span>";
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
					window.location.href=\"penilaian_mata.php\";
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