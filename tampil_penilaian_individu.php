<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Tampil Penilaian Individu Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttonotoup2.css">


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
						<h3 class="fw-bold fs-4 mb-3">DATA PENILAIAN BAGIAN / KEPANITERAAN (STASE) PER INDIVIDU</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">PENILAIAN BAGIAN / KEPANITERAAN (STASE) PER INDIVIDU</h2>
						<br>
						<?php
						$id_stase = $_GET['stase'];
						$stase_id = "stase_" . $id_stase;

						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama`,`nim` FROM `biodata_mhsw` WHERE `nim`='$_GET[nim]'"));
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
						$nama_stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
						$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
						$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;
						echo "<center>";
						echo "<table class=\"table table-bordered\" style=\"width:50%\" >";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td style=\"width:50%;\"><strong>Nama Mahasiswa</strong></td>
						<td  style=\"font-weight:600; color:red;\"> $data_mhsw[nama]</td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<td><strong>Kepaniteraan (Stase)</strong></td>
						<td style=\"font-weight:600;\"><span style=\"color:darkgreen;\"> $nama_stase[kepaniteraan]</span></td></tr>";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td><strong>Periode Kepaniteraan (Stase)</strong></td>
						<td style=\"font-weight:600;\">$periode</td></tr>";
						echo "</table><br><br>";

						//-------------------------------------
						//Kepaniteraan (Stase) Ilmu Kesehatan Penyakit Dalam
						//-------------------------------------
						if ($id_stase == "M091") {
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#minicex_M091\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Mini-Cex</a>
								<a href=\"#kasus_M091\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Penyajian Kasus Besar</a>
								<a href=\"#ujian_M091\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Ujian Akhir</a>
								<br><br>
								<a href=\"#test_M091\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Preview Nilai Ujian Tulis MCQ</a>
								
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian MINI-CEX
							echo "<br><br>";
							echo "<center>";
							echo "<a id=\"minicex_M091\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Mini-Cex</a><br><br>";
							echo "<div class=\"alert alert-warning\" style=\"width:60%; font-size:0.875em; text-align: left; \"><span class=\"text-danger\" style=\"font-weight:700;\">Catatan:</span><br><span style=\"font-weight:600;\">- Pengisian wajib untuk penilaian Mini-Cex adalah 7 (tujuh) kali.<br>- Nilai rata-rata adalah jumlah total nilai Mini-Cex dibagi 7 (tujuh).<br>- Untuk cetak, minimal 1 (satu) penilaian Mini-Cex telah disetujui Dosen Penilai.</span></div><br><br>";
							echo "</center>";
							$nilai_minicex = mysqli_query($con, "SELECT * FROM `ipd_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M091\">";
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

									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_minicex['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//Pengisian Formulir Penilaian Penyajian Kasus Besar
							echo "<a id=\"kasus_M091\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Penyajian Kasus Besar</a><br><br>";
							$nilai_kasus = mysqli_query($con, "SELECT * FROM `ipd_nilai_kasus` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M091\">";
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
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_minicex['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//Pengisian Formulir Penilaian Ujian Akhir
							echo "<a id=\"ujian_M091\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian Akhir</a><br><br>";
							$nilai_ujian = mysqli_query($con, "SELECT * FROM `ipd_nilai_ujian` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M091\">";
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
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//Preview Nilai-Nilai Test dan Perilaku
							echo "<a id=\"test_M091\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai Ujian/Test</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `ipd_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4__M091\">";
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
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Ilmu Penyakit Dalam
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) Neurologi
						//-------------------------------------
						if ($id_stase == "M092") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#cbd_M092\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Kasus CBD</a>
								<a href=\"#jurnal_M092\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Journal Reading</a>
								<a href=\"#spv_M092\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Ujian SPV</a>
								<br><br>
								<a href=\"#minicex_M092\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Pengisian Formulir Penilaian Ujian Minicex</a>
								<a href=\"#test_M092\" class=\"btn me-3\" style=\"background-color: #00008B; color: white;\">Preview Nilai Penugasan dan Test</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian Kasus CBD
							echo "<br><br>";
							echo "<a id=\"cbd_M092\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Kasus CBD</a><br><br>";
							$nilai_cbd = mysqli_query($con, "SELECT * FROM `neuro_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M092\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      					<th style=\"width:5%;text-align:center;\">No</th>
			      					<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      					<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      					<th style=\"width:25%;text-align:center;\">Dosen Penilai/Penguji</th>
			      					<th style=\"width:15%;text-align:center;\">Status Approval</th>
			      				</thead>";
							$cek_nilai_cbd = mysqli_num_rows($nilai_cbd);
							if ($cek_nilai_cbd < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_cbd = mysqli_fetch_array($nilai_cbd)) {
									$tanggal_isi = tanggal_indo($data_cbd['tgl_isi']);
									$tanggal_ujian = tanggal_indo($data_cbd['tgl_ujian']);
									$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
									$awal_periode = tanggal_indo($data_cbd['tgl_awal']);
									$akhir_periode = tanggal_indo($data_cbd['tgl_akhir']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Kasus CBD<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Kasus $data_cbd[kasus_ke] - Tanggal Ujian: <span style=\"color:blue;\">$tanggal_ujian</span><br>";
									echo "<span style=\"color:darkgreen;\">Fokus Kasus: $data_cbd[fokus_kasus]</span><br>";
									echo "Nilai: $data_cbd[nilai_rata]";
									echo "</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_cbd['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Journal Reading
							echo "<a id=\"jurnal_M092\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Journal Reading</a><br><br>";
							$nilai_jurnal = mysqli_query($con, "SELECT * FROM `neuro_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M092\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      					<th style=\"width:5%;text-align:center;\">No</th>
			      					<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      					<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      					<th style=\"width:25%;text-align:center;\">Dosen Penguji (Tutor)</th>
			      					<th style=\"width:15%;text-align:center;\">Status Approval</th>
			      				</thead>";
							$cek_nilai_jurnal = mysqli_num_rows($nilai_jurnal);
							if ($cek_nilai_jurnal < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_jurnal = mysqli_fetch_array($nilai_jurnal)) {
									$tanggal_isi = tanggal_indo($data_jurnal['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_jurnal['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">
									Penilaian Journal Reading<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:green;\">Jurnal: $data_jurnal[nama_jurnal]</span><br>";
									echo "<span style=\"color:blue;\">Judul Artikel: $data_jurnal[judul_paper]</span><br>Nilai: $data_jurnal[nilai_total]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_jurnal['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Ujian SPV
							echo "<a id=\"spv_M092\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian SPV</a><br><br>";
							$nilai_spv = mysqli_query($con, "SELECT * FROM `neuro_nilai_spv` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M092\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      					<th style=\"width:5%;text-align:center;\">No</th>
			      					<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      					<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			     					<th style=\"width:25%;text-align:center;\">Dosen Penguji</th>
			      					<th style=\"width:15%;text-align:center;\">Status Approval</th>
			      				</thead>";
							$cek_nilai_spv = mysqli_num_rows($nilai_spv);
							if ($cek_nilai_spv < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_spv = mysqli_fetch_array($nilai_spv)) {
									$tanggal_isi = tanggal_indo($data_spv['tgl_isi']);
									$tanggal_ujian = tanggal_indo($data_spv['tgl_ujian']);
									$tanggal_approval = tanggal_indo($data_spv['tgl_approval']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_spv[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Ujian SPV<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Tanggal Ujian: <span style=\"color:blue;\">$tanggal_ujian</span><br>";
									echo "Nilai Ujian: $data_spv[nilai]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_spv['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//Pengisian Formulir Penilaian Ujian MINI-CEX
							echo "<a id=\"minicex_M092\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian MINI-CEX</a><br><br>";
							$nilai_minicex = mysqli_query($con, "SELECT * FROM `neuro_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_M092\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      				<th style=\"width:5%;text-align:center;\">No</th>
			      				<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      				<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      				<th style=\"width:25%;text-align:center;\">Dosen Penguji</th>
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
									$awal_periode = tanggal_indo($data_minicex['tgl_awal']);
									$akhir_periode = tanggal_indo($data_minicex['tgl_akhir']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Ujian MINI-CEX<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Tanggal Ujian:<span style=\"color:blue;\"> $tanggal_ujian</span><br>";
									echo "Nilai Ujian: $data_minicex[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_minicex['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai Ujian/Test (id test lihat tabel jenis_test)
							echo "<a id=\"test_M092\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai Ujian/Test</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `neuro_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze5_M092\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      					<th style=\"width:5%;text-align:center;\">No</th>
			      					<th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      					<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      					<th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      					<th style=\"width:15%;text-align:center;\">Nilai</th>
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
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\">Neurologi</span><br><br>";
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
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Neurologi
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) Psikiatri
						//-------------------------------------
						if ($id_stase == "M093") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#jurnal_M093\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Journal Reading</a>
								<a href=\"#cbd_M093\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian CBD</a>
								<a href=\"#minicex_M093\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Ujian MINI-CEX</a>
								<br><br>
								<a href=\"#osce_M093\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Pengisian Formulir Penilaian Ujian OSCE</a>
								<a href=\"#test_M093\" class=\"btn me-3\" style=\"background-color: #00008B; color: white;\">Preview Nilai-Nilai Test</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian Journal Reading
							echo "<br><br>";
							echo "<a id=\"jurnal_M093\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Journal Reading</a><br><br>";
							$nilai_jurnal = mysqli_query($con, "SELECT * FROM `psikiatri_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M093\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      					<th style=\"width:5%;text-align:center;\">No</th>
			      					<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      					<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      					<th style=\"width:25%;text-align:center;\">Dosen Penguji (Tutor)</th>
			      					<th style=\"width:15%;text-align:center;\">Status Approval</th>
			      				</thead>";
							$cek_nilai_jurnal = mysqli_num_rows($nilai_jurnal);
							if ($cek_nilai_jurnal < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_jurnal = mysqli_fetch_array($nilai_jurnal)) {
									$tanggal_isi = tanggal_indo($data_jurnal['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_jurnal['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Journal Reading<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:green\">Jurnal: $data_jurnal[nama_jurnal]</span><br>";
									echo "<span style=\"color:blue\">Judul Artikel: $data_jurnal[judul_paper]</span><br>";
									echo "Nilai: $data_jurnal[nilai_rata]";
									echo "</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_jurnal['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//Pengisian Formulir Penilaian CBD
							echo "<a id=\"cbd_M093\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian CBD</a><br><br>";
							$nilai_cbd = mysqli_query($con, "SELECT * FROM `psikiatri_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M093\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      					<th style=\"width:5%;text-align:center;\">No</th>
			      					<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      					<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      					<th style=\"width:25%;text-align:center;\">Dosen Penilai/Penguji</th>
			      					<th style=\"width:15%;text-align:center;\">Status Approval</th>
			      					</thead>";
							$cek_nilai_cbd = mysqli_num_rows($nilai_cbd);
							if ($cek_nilai_cbd < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_cbd = mysqli_fetch_array($nilai_cbd)) {
									$tanggal_isi = tanggal_indo($data_cbd['tgl_isi']);
									$tanggal_ujian = tanggal_indo($data_cbd['tgl_ujian']);
									$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
									$awal_periode = tanggal_indo($data_cbd['tgl_awal']);
									$akhir_periode = tanggal_indo($data_cbd['tgl_akhir']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">
									Penilaian Kasus CBD<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:darkgreen;\">Fokus Pertemuan Klinik: $data_cbd[fokus_pertemuan]</span><br>";
									echo "<span style=\"color:blue;\">Tanggal Ujian: $tanggal_ujian</span><br>Nilai: $data_cbd[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_cbd['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian MINI-CEX
							echo "<a id=\"minicex_M093\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian MINI-CEX</a><br><br>";
							$nilai_minicex = mysqli_query($con, "SELECT * FROM `psikiatri_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M093\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      					<th style=\"width:5%;text-align:center;\">No</th>
			      					<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      					<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      					<th style=\"width:25%;text-align:center;\">Dosen Penguji</th>
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
									$awal_periode = tanggal_indo($data_minicex['tgl_awal']);
									$akhir_periode = tanggal_indo($data_minicex['tgl_akhir']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Ujian MINI-CEX<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Tanggal Ujian:<span style=\"color:blue;\"> $tanggal_ujian</span><br>";
									echo "Nilai Ujian: $data_minicex[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_minicex['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//Pengisian Formulir Penilaian Ujian OSCE
							echo "<a id=\"osce_M093\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian OSCE</a><br><br>";
							$nilai_osce = mysqli_query($con, "SELECT * FROM `psikiatri_nilai_osce` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_M093\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      					<th style=\"width:5%;text-align:center;\">No</th>
			      					<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      					<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      					<th style=\"width:25%;text-align:center;\">Dosen Penguji</th>
			      					<th style=\"width:15%;text-align:center;\">Status Approval</th>
			      				</thead>";
							$cek_nilai_osce = mysqli_num_rows($nilai_osce);
							if ($cek_nilai_osce < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_osce = mysqli_fetch_array($nilai_osce)) {
									$tanggal_isi = tanggal_indo($data_osce['tgl_isi']);
									$tanggal_ujian = tanggal_indo($data_osce['tgl_ujian']);
									$tanggal_approval = tanggal_indo($data_osce['tgl_approval']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_osce[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Ujian OSCE<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Tanggal Ujian:<span style=\"color:blue;\"> $tanggal_ujian</span><br>";
									echo "Nilai: $data_osce[nilai_rata]</i></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_osce['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai Test (id test lihat tabel jenis_test)
							echo "<a id=\"test_M093\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai-Nilai Test</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `psikiatri_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze5_M093\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      				<th style=\"width:5%;text-align:center;\">No</th>
			      				<th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      				<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      				<th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      				<th style=\"width:15%;text-align:center;\">Nilai</th>
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
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\">Ilmu Kesehatan Jiwa</span><br><br>";
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
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Psikiatri
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) THTKL
						//-------------------------------------
						if ($id_stase == "M105") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#presentasi_M105\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Presentasi Kasus</a>
								<a href=\"#responsi_M105\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Responsi Kasus Kecil</a>
								<a href=\"#jurnal_M105\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Journal Reading</a>
								<br><br>
								<a href=\"#test_M105\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Preview Nilai Test</a>
								<a href=\"#osce_M105\" class=\"btn me-3\" style=\"background-color: #00008B; color: white;\">Pengisian Formulir Penilaian Ujian OSCE</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian Presentasi Kasus
							echo "<br><br>";
							echo "<a id=\"presentasi_M105\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Presentasi Kasus</a><br><br>";
							$nilai_presentasi = mysqli_query($con, "SELECT * FROM `thtkl_nilai_presentasi` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M105\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Pembimbing/Penguji</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_presentasi = mysqli_num_rows($nilai_presentasi);
							if ($cek_nilai_presentasi < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_presentasi = mysqli_fetch_array($nilai_presentasi)) {
									$tanggal_isi = tanggal_indo($data_presentasi['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_presentasi['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_presentasi[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Presentasi Kasus<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_presentasi[nilai_rata]";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_presentasi['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Responsi Kasus Kecil
							echo "<a id=\"responsi_M105\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Responsi Kasus Kecil</a><br><br>";
							$nilai_responsi = mysqli_query($con, "SELECT * FROM `thtkl_nilai_responsi` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M105\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Pembimbing/Penguji</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_responsi = mysqli_num_rows($nilai_responsi);
							if ($cek_nilai_responsi < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_responsi = mysqli_fetch_array($nilai_responsi)) {
									$tanggal_isi = tanggal_indo($data_responsi['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_responsi['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_responsi[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Responsi Kasus Kecil Ke-<span class=\"text-danger\">$data_responsi[kasus_ke]</span><br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_responsi[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_responsi['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Journal Reading
							echo "<a id=\"jurnal_M105\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Journal Reading</a><br><br>";
							$nilai_jurnal = mysqli_query($con, "SELECT * FROM `thtkl_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M105\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Pembimbing/Penguji</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_jurnal = mysqli_num_rows($nilai_jurnal);
							if ($cek_nilai_jurnal < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_jurnal = mysqli_fetch_array($nilai_jurnal)) {
									$tanggal_isi = tanggal_indo($data_jurnal['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_jurnal['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Jounal Reading Ke-<spam class=\"text-danger\">$data_jurnal[jurnal_ke]</span><br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:green;\">Jurnal: $data_jurnal[nama_jurnal]</span><br>";
									echo "<span style=\"color:blue;\">Judul Artikel: $data_jurnal[judul_paper]</span><br>";
									echo "<span style=\"color:black\">Nilai: $data_jurnal[nilai_rata]</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_jurnal['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//Preview Nilai Test (id Test lihat tabel jenis_test)
							echo "<a id=\"test_M105\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai Test</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `thtkl_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_M105\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      				<th style=\"width:5%;text-align:center;\">No</th>
			      				<th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      				<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      				<th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      				<th style=\"width:15%;text-align:center;\">Nilai</th>
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
									$tanggal_test = tanggal_indo($data_test['tgl_test']);
									$status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$data_test[status_ujian]'"));
									$tanggal_approval = tanggal_indo($data_test['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_test[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span>  Kepaniteraan <span style=\"color:darkgreen;\">THT-KL</span><br><br>";
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
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Ujian OSCE
							echo "<a id=\"osce_M105\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian OSCE</a><br><br>";
							$nilai_osce = mysqli_query($con, "SELECT * FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' ORDER BY `tgl_isi`,`tgl_ujian`");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze5_M105\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penguji</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_osce = mysqli_num_rows($nilai_osce);
							if ($cek_nilai_osce < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_osce = mysqli_fetch_array($nilai_osce)) {
									$tanggal_isi = tanggal_indo($data_osce['tgl_isi']);
									$tanggal_ujian = tanggal_indo($data_osce['tgl_ujian']);
									$tanggal_approval = tanggal_indo($data_osce['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_osce[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Ujian OSCE $data_osce[jenis_ujian]<br><br>";
									echo "Tanggal Ujian:<span style=\"color:blue;\"> $tanggal_ujian</span><br>";
									echo "Nilai Ujian: $data_osce[nilai]";
									echo "</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_osce['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) THTKL
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) IKM-KP
						//-------------------------------------
						if ($id_stase == "M095") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#pkbi_M095\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Kegiatan di PKBI</a>
								<a href=\"#p2ukm__M095\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Kegiatan di P2UKM</a>
								<a href=\"#test_M095\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Preview Nilai Penugasan dan Test</a>
								<br><br>
								<a href=\"#komprehensip_M095\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Pengisian Formulir Penilaian Ujian Komprehensip</a>
								
								<br><br>
								";
							echo "</center>";
							echo "<br><br>";

							//Pengisian Formulir Penilaian Kegiatan di PKBI
							echo "<a id=\"pkbi_M095\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Kegiatan di PKBI</a><br><br>";
							$nilai_pkbi = mysqli_query($con, "SELECT * FROM `ikmkp_nilai_pkbi` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M095\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
						<th style=\"width:25%;text-align:center;\">Dosen Pembimbing</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";
							$cek_nilai_pkbi = mysqli_num_rows($nilai_pkbi);
							if ($cek_nilai_pkbi < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_pkbi = mysqli_fetch_array($nilai_pkbi)) {
									$tanggal_isi = tanggal_indo($data_pkbi['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_pkbi['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_pkbi[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Kegiatan di PKBI<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>";
									echo "<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_pkbi[nilai_total]</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_pkbi['status_approval'] == '0') echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//Pengisian Formulir Penilaian Kegiatan di P2UKM
							echo "<a id=\"p2ukm_M095\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Kegiatan di P2UKM</a><br><br>";
							$nilai_p2ukm = mysqli_query($con, "SELECT * FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M095\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
						<th style=\"width:25%;text-align:center;\">Dosen Pembimbing</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";
							$cek_nilai_p2ukm = mysqli_num_rows($nilai_p2ukm);
							if ($cek_nilai_p2ukm < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_p2ukm = mysqli_fetch_array($nilai_p2ukm)) {
									$tanggal_isi = tanggal_indo($data_p2ukm['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_p2ukm['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_p2ukm[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">
									Penilaian $data_p2ukm[jenis_penilaian]<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_p2ukm[nilai_total]</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_p2ukm['status_approval'] == '0') echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai Penugasan dan Test (id test lihat tabel jenis_test)
							echo "<a id=\"test_M095\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai Penugasan dan Test</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `ikmkp_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M095\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
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
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\">IKM-KP</span><br><br>";
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
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Ujian Komprehensip
							echo "<a id=\"komprehensip_M095\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian Komprehensip</a><br><br>";;
							$nilai_komprehensip = mysqli_query($con, "SELECT * FROM `ikmkp_nilai_komprehensip` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_M095\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%; text-align: center;\">No</th>
						<th style=\"width:15%; text-align: center;\">Tanggal Pengisian</th>
						<th style=\"width:40%; text-align: center;\">Jenis Penilaian</th>
						<th style=\"width:25%; text-align: center;\">Dosen Pembimbing</th>
						<th style=\"width:15%; text-align: center;\">Status Approval</th>
						</thead>";

							$cek_nilai_komprehensip = mysqli_num_rows($nilai_komprehensip);
							if ($cek_nilai_komprehensip < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_komprehensip = mysqli_fetch_array($nilai_komprehensip)) {
									$tanggal_isi = tanggal_indo($data_komprehensip['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_komprehensip['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_komprehensip[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Ujian Komprehensip<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_komprehensip[nilai_total]</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_komprehensip['status_approval'] == '0') echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) IKM-KP
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) Ilmu Bedah
						//-------------------------------------
						if ($id_stase == "M101") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#mentor_M101\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Mentoring</a>
								<a href=\"#test_M101\" class=\"btn btn-primary me-3\">Preview Nilai-Nilai Pre-Test, Post-Test, Skill Lab dan OSCE</a>					
								<br><br>
								";
							echo "</center>";
							echo "<br><br>";

							//Pengisian Formulir Penilaian Mentoring di RS Jejaring
							echo "<center>";
							echo "<a id=\"mentor_M101\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Mentoring</a><br><br>";
							echo "<div class=\"alert alert-warning\" style=\"width:60%; font-size:0.875em; text-align: left; \"><span class=\"text-danger\" style=\"font-weight:700;\">Catatan:</span><br><span style=\"font-weight:600;\">- Pengisian wajib untuk penilaian Mentoring adalah 2 (dua) kali (Penilaian Bulan ke-1 dan Penilaian Bulan ke-2).<br>- Nilai rata-rata adalah jumlah total penilaian dibagi 2 (dua).<br>- Untuk cetak, minimal 1 (satu) penilaian telah disetujui Dosen Penilai (Mentor).</span></div><br><br>";
							echo "</center>";
							$nilai_mentor = mysqli_query($con, "SELECT * FROM `bedah_nilai_mentor` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M101\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
						<th style=\"width:25%;text-align:center;\">Dokter Penilai</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_mentor = mysqli_num_rows($nilai_mentor);
							if ($cek_nilai_mentor < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_mentor = mysqli_fetch_array($nilai_mentor)) {
									$tanggal_isi = tanggal_indo($data_mentor['tgl_isi']);
									$tanggal_awal = tanggal_indo($data_mentor['tgl_awal']);
									$tanggal_akhir = tanggal_indo($data_mentor['tgl_akhir']);
									$tanggal_approval = tanggal_indo($data_mentor['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_mentor[dosen]'"));
									$jam_isi = $data_mentor['jam_isi'];
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Mentoring<br><br>";
									echo "Mentoring Bulan ke-<span style=\"color:red\">$data_mentor[bulan_ke]</span><br>
										Periode Tanggal: <span style=\"color:darkblue\">$tanggal_awal</span> s.d. <span style=\"color:darkblue\">$tanggal_akhir</span><br>
										Nilai: $data_mentor[nilai_rata]
										</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_mentor['status_approval'] == '0') {
										echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
									} else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table>";
							echo "</center><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai-Nilai Pre-Test, Post-Test, Skill Lab dan OSCE
							echo "<a id=\"test_M101\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai-Nilai Pre-Test, Post-Test, Skill Lab dan OSCE</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `bedah_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M101\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
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
									$tanggal_test = tanggal_indo($data_test['tgl_test']);
									$status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$data_test[status_ujian]'"));
									$tanggal_approval = tanggal_indo($data_test['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_test[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"color:darkblue; font-weight:600; text-align:center;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:green;\">Ilmu Bedah</span><br><br>";
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
							echo "</center><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Ilmu Bedah
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) Anestesi
						//-------------------------------------
						if ($id_stase == "M102") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#dops_M102\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian DOPS</a>
								<a href=\"#osce_M102\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Ujian OSCE</a>
								<a href=\"#test_M102\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Preview Nilai-Nilai Test dan Perilaku</a>
								<br><br>
								";
							echo "</center>";
							echo "<br><br>";

							//Pengisian Formulir Penilaian DOPS
							echo "<a id=\"dops_M102\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian DOPS</a><br><br>";
							$nilai_dops = mysqli_query($con, "SELECT * FROM `anestesi_nilai_dops` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M102\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_dops = mysqli_num_rows($nilai_dops);
							if ($cek_nilai_dops < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_dops = mysqli_fetch_array($nilai_dops)) {
									$tanggal_isi = tanggal_indo($data_dops['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_dops['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian DOPS<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>";
									echo "<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_dops[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_dops['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Ujian OSCE
							echo "<a id=\"osce_M102\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian OSCE</a><br><br>";
							$nilai_osce = mysqli_query($con, "SELECT * FROM `anestesi_nilai_osce` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M102\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penguji (Tutor)</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_osce = mysqli_num_rows($nilai_osce);
							if ($cek_nilai_osce < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_osce = mysqli_fetch_array($nilai_osce)) {
									$tanggal_isi = tanggal_indo($data_osce['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_osce['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_osce[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Ujian OSCE<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_osce[nilai_total]</i></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_osce['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai-Nilai Test dan Perilaku
							echo "<a id=\"test_M102\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai-Nilai Test dan Perilaku</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M102\">";
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
									$tanggal_test = tanggal_indo($data_test['tgl_test']);
									$status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$data_test[status_ujian]'"));
									$tanggal_approval = tanggal_indo($data_test['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_test[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"color:darkblue; font-weight:600; text-align:center;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\"> Anestesi dan Intensive Care</span><br><br>";
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
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Anestesi
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) Radiologi
						//-------------------------------------
						if ($id_stase == "M103") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#cbd1_M103\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Kasus CBD - Radiodiagnostik</a>
								<a href=\"#cbd2_M103\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Kasus CBD - Radioterapi</a>
								<a href=\"#jurnal_M103\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Journal Reading</a>
								<br><br>
								<a href=\"#test_M103\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Preview Nilai Test dan Sikap/Perilaku</a>
								
								<br><br>
								";
							echo "</center>";


							//Pengisian Formulir Penilaian Kasus CBD - Radiodiagnostik
							echo "<br><br>";
							echo "<a id=\"cbd1_M103\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Kasus CBD - Radiodiagnostik</a><br><br>";
							$kasus = "Radiodiagnostik";
							$nilai_cbd = mysqli_query($con, "SELECT * FROM `radiologi_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='$kasus'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M103\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Pembimbing</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_cbd = mysqli_num_rows($nilai_cbd);
							if ($cek_nilai_cbd < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_cbd = mysqli_fetch_array($nilai_cbd)) {
									$tanggal_isi = tanggal_indo($data_cbd['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Kasus CBD<br>
									<span style=\"color:green;\">Jenis Kasus: $data_cbd[kasus]</span><br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>
									Nilai: $data_cbd[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_cbd['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Kasus CBD - Radioterapi
							echo "<a id=\"cbd2_M103\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Kasus CBD - Radioterapi</a><br><br>";
							$kasus = "Radioterapi";
							$nilai_cbd = mysqli_query($con, "SELECT * FROM `radiologi_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='$kasus'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M103\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Pembimbing</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_cbd = mysqli_num_rows($nilai_cbd);
							if ($cek_nilai_cbd < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_cbd = mysqli_fetch_array($nilai_cbd)) {
									$tanggal_isi = tanggal_indo($data_cbd['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Kasus CBD<br>
									<span style=\"color:green;\">Jenis Kasus: $data_cbd[kasus]</span><br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>
									Nilai: $data_cbd[nilai_rata]</i></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_cbd['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Journal Reading
							echo "<a id=\"jurnal_M103\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Journal Reading</a><br><br>";
							$nilai_jurnal = mysqli_query($con, "SELECT * FROM `radiologi_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M103\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penguji (Tutor)</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_jurnal = mysqli_num_rows($nilai_jurnal);
							if ($cek_nilai_jurnal < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_jurnal = mysqli_fetch_array($nilai_jurnal)) {
									$tanggal_isi = tanggal_indo($data_jurnal['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_jurnal['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Journal Reading<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:green\">Jurnal: $data_jurnal[nama_jurnal]</span><br>";
									echo "<span style=\"color:blue\">Judul Artikel: $data_jurnal[judul_paper]</span><br>
									Nilai: $data_jurnal[nilai_rata]<br></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_jurnal['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai Test dan Sikap/Perilaku
							echo "<a id=\"test_M103\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai Test dan Sikap/Perilaku</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `radiologi_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_M103\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
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
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan <span style=\"color:darkgreen;\">Radiologi</span><br><br>";
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
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Radiologi
						//-------------------------------------


						//-------------------------------------
						//Kepaniteraan (Stase) Ilmu Kesehatan Mata
						//-------------------------------------
						if ($id_stase == "M104") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#presentasi_M104\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Presentasi Kasus Besar</a>
								<a href=\"#jurnal_M104\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Presentasi Journal Reading</a>
								<a href=\"#penyanggah_M104\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Penyanggah Presentasi</a>
								<br><br>
								<a href=\"#minicex_M104\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Pengisian Formulir Penilaian Ujian Mini-Cex</a>
								<a href=\"#test_M104\" class=\"btn me-3\" style=\"background-color: #00008B; color: white;\">Preview Nilai-Nilai Test</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian Presentasi Kasus Besar
							echo "<br><br>";
							echo "<a id=\"presentasi_M104\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Presentasi Kasus Besar</a><br><br>";
							$nilai_presentasi = mysqli_query($con, "SELECT * FROM `mata_nilai_presentasi` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M104\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_presentasi = mysqli_num_rows($nilai_presentasi);
							if ($cek_nilai_presentasi < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_presentasi = mysqli_fetch_array($nilai_presentasi)) {
									$tanggal_isi = tanggal_indo($data_presentasi['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_presentasi['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_presentasi[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Presentasi Kasus Besar<br><br>";
									echo "<span style=\"color:green\">Judul Kasus: $data_presentasi[judul_presentasi]</span><br>
									Nilai: $data_presentasi[nilai_total]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_presentasi['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Presentasi Journal Reading
							echo "<a id=\"jurnal_M104\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Presentasi Journal Reading</a><br><br>";
							$nilai_jurnal = mysqli_query($con, "SELECT * FROM `mata_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M104\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_jurnal = mysqli_num_rows($nilai_jurnal);
							if ($cek_nilai_jurnal < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_jurnal = mysqli_fetch_array($nilai_jurnal)) {
									$tanggal_isi = tanggal_indo($data_jurnal['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_jurnal['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Presentasi Journal Reading<br><br>";
									echo "<span style=\"color:green\">Judul Artikel: $data_jurnal[judul_presentasi]</span><br>
									Nilai: $data_jurnal[nilai_total]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_jurnal['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai Penyanggah Presentasi
							echo "<a id=\"penyanggah_M104\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai Penyanggah Presentasi</a><br><br>";
							$nilai_penyanggah = mysqli_query($con, "SELECT * FROM `mata_nilai_penyanggah` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M104\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Presentasi</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
			      </thead>";

							$cek_nilai_penyanggah = mysqli_num_rows($nilai_penyanggah);
							if ($cek_nilai_penyanggah < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_penyanggah = mysqli_fetch_array($nilai_penyanggah)) {
									$tanggal_presentasi = tanggal_indo($data_penyanggah['tgl_presentasi']);
									$tanggal_approval = tanggal_indo($data_penyanggah['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_penyanggah[dosen]'"));
									$mhsw_presenter = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_penyanggah[presenter]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_presentasi</td>";
									echo "<td style=\"font-weight:600;\">Nilai Penyanggah Presentasi $data_penyanggah[jenis_presentasi] dari mahasiswa:<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:green;\">Judul: $data_penyanggah[judul_presentasi]</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<span style=\"font-weight:600;\">Tanggal Penilaian: $tanggal_approval</span>";
									echo "</td>";
									echo "<td align=center style=\"font-weight:600;\">$data_penyanggah[nilai]</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Ujian Mini-Cex
							echo "<a id=\"minicex_M104\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian Mini-Cex</a><br><br>";
							$nilai_minicex = mysqli_query($con, "SELECT * FROM `mata_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' ORDER BY `tgl_isi`,`tgl_ujian`");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_M104\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penguji</th>
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
									echo "<td style=\"font-weight:600;\">Penilaian Ujian Mini-Cex<br>";
									echo "Tanggal Ujian: $tanggal_ujian<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>
									Nilai: $data_minicex[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_minicex['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai-Nilai Test (id Test lihat tabel jenis_test)
							echo "<a id=\"test_M104\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai-Nilai Test</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `mata_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze5_M104\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
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
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\">Ilmu Kesehatan Mata</span><br><br>";
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
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Ilmu Kesehatan Mata
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) IKFR
						//-------------------------------------
						if ($id_stase == "M094") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#kasus_M094\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Diskusi Kasus</a>
								<a href=\"#minicex_M094\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Ujian MINI-CEX</a>
								<a href=\"#test_M094\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Preview Nilai-Nilai Test dan Perilaku</a>
								<br><br>
								";
							echo "</center>";
							echo "<br><br>";

							//Pengisian Formulir Penilaian Diskusi Kasus
							echo "<a id=\"kasus_M094\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Diskusi Kasus</a><br><br>";
							$nilai_kasus = mysqli_query($con, "SELECT * FROM `ikfr_nilai_kasus` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M094\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
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
									$tanggal_isi = tanggal_indo($data_kasus['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_kasus['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Kegiatan Diskusi Kasus<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:green\">Kasus: $data_kasus[kasus]</span><br>";
									echo "Nilai: $data_kasus[nilai_rata]</i></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_kasus['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Ujian MINI-CEX
							echo "<a id=\"minicex_M094\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian MINI-CEX</a><br><br>";
							$nilai_minicex = mysqli_query($con, "SELECT * FROM `ikfr_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M094\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
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
									$tanggal_approval = tanggal_indo($data_minicex['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Ujian MINI-CEX<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_minicex[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_minicex['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai-Nilai Test dan Perilaku
							echo "<a id=\"test_M094\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai-Nilai Test dan Perilaku</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `ikfr_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M094\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
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
									$tanggal_test = tanggal_indo($data_test['tgl_test']);
									$status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$data_test[status_ujian]'"));
									$tanggal_approval = tanggal_indo($data_test['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_test[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\">IKFR</span><br><br>";
									echo "Status Ujian/Test:<span style=\"color:purple;\"> $status_ujian[status_ujian]</span><br>";
									echo "<span>Catatan: $data_test[catatan]</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "Tanggal Yudisium: <span style=\"color:blue\">$tanggal_approval</span>";
									echo "</td>";
									$nilai = number_format($data_test['nilai'], 2);
									echo "<td align=center style=\"font-weight:600;\">$nilai</td>";
									echo "</tr>";;
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) IKFR
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) IKGM
						//-------------------------------------
						if ($id_stase == "M106") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#kasus_M106\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Laporan Kasus</a>
								<a href=\"#jurnal_M106\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Journal Reading</a>
								<a href=\"#responsi_M106\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Responsi Kasus Kecil</a>
								<br><br>
								<a href=\"#test_M106\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Preview Nilai-Nilai Test dan Perilaku</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian Laporan Kasus
							echo "<br><br>";
							echo "<a id=\"kasus_M106\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Laporan Kasus</a><br><br>";
							$nilai_kasus = mysqli_query($con, "SELECT * FROM `ikgm_nilai_kasus` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M106\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_kasus = mysqli_num_rows($nilai_kasus);
							if ($cek_nilai_kasus < 1) {
								echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_kasus = mysqli_fetch_array($nilai_kasus)) {
									$tanggal_isi = tanggal_indo($data_kasus['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_kasus['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Kegiatan Laporan Kasus<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:green;\">Nama/Judul Kasus: $data_kasus[nama_kasus]</span><br>";
									echo "Nilai: $data_kasus[nilai_total]</i></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_kasus['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Journal Reading
							echo "<a id=\"jurnal_M106\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Journal Reading</a><br><br>";
							$nilai_jurnal = mysqli_query($con, "SELECT * FROM `ikgm_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M106\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penguji (Tutor)</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_jurnal = mysqli_num_rows($nilai_jurnal);
							if ($cek_nilai_jurnal < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_jurnal = mysqli_fetch_array($nilai_jurnal)) {
									$tanggal_isi = tanggal_indo($data_jurnal['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_jurnal['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">
									Penilaian Journal Reading<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:green;\">Jurnal: $data_jurnal[nama_jurnal]</span><br>";
									echo "<span style=\"color:blue;\">Judul Artikel: $data_jurnal[judul_paper]</span><br>Nilai: $data_jurnal[nilai_total]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center>";
									if ($data_jurnal['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Responsi Kasus Kecil
							echo "<a id=\"responsi_M106\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Responsi Kasus Kecil</a><br><br>";
							$nilai_responsi = mysqli_query($con, "SELECT * FROM `ikgm_nilai_responsi` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M106\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Pembimbing/Penguji</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_responsi = mysqli_num_rows($nilai_responsi);
							if ($cek_nilai_responsi < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_responsi = mysqli_fetch_array($nilai_responsi)) {
									$tanggal_isi = tanggal_indo($data_responsi['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_responsi['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_responsi[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Responsi Kasus Kecil Ke-<span style=\"color:red;\">$data_responsi[kasus_ke]</span><br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_responsi[nilai_rata]</i></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_responsi['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai-Nilai Test dan Perilaku
							echo "<a id=\"test_M106\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai-Nilai Test dan Perilaku</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `ikgm_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_M106\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
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
									$tanggal_test = tanggal_indo($data_test['tgl_test']);
									$status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$data_test[status_ujian]'"));
									$tanggal_approval = tanggal_indo($data_test['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_test[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\">IKGM</span><br><br>";
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
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) IKGM
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan
						//-------------------------------------
						if ($id_stase == "M111") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#minicex_M111\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)</a>
								<a href=\"#cbd_M111\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Case-Based Discussion (CBD)</a>
								<a href=\"#jurnal_M111\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Journal Reading</a>
								<br><br>
								<a href=\"#test_M111\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Preview Nilai Penugasan dan Test (DOPS/OSCE, MCQ, dan MINI-PAT)</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)
							echo "<br><br>";
							echo "<a id=\"minicex_M111\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)</a><br><br>";
							$nilai_minicex = mysqli_query($con, "SELECT * FROM `obsgyn_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M111\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penguji</th>
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
									echo "<td style=\"font-weight:600;\">Penilaian Ujian MINI-CEX <span class=\"text-danger\">(Mini Clinical Examination)</span><br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Tanggal Ujian: <span style=\"color:darkblue\">$tanggal_ujian</span><br>";
									echo "Nilai Ujian: $data_minicex[nilai_rata]</i></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_minicex['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table>";
							echo "</center><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian CBD
							echo "<center>";
							echo "<a id=\"cbd_M111\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian CBD</a><br><br>";
							$nilai_cbd = mysqli_query($con, "SELECT * FROM `obsgyn_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
							echo "<div class=\"alert alert-warning\" style=\"width:60%; font-size:0.875em; text-align: left; \"><span class=\"text-danger\" style=\"font-weight:700;\">Catatan:</span><br><span style=\"font-weight:600;\">- Pengisian wajib untuk penilaian CBD adalah 4 (empat) kali.<br>- Nilai rata-rata adalah jumlah total nilai CBD dibagi 4 (empat).<br>- Untuk cetak, minimal 1 (satu) penilaian CBD telah disetujui Dosen Penilai.</span></div><br><br>";
							echo "</center>";
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M111\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai/Penguji</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_cbd = mysqli_num_rows($nilai_cbd);
							if ($cek_nilai_cbd < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_cbd = mysqli_fetch_array($nilai_cbd)) {
									$tanggal_isi = tanggal_indo($data_cbd['tgl_isi']);
									$tanggal_ujian = tanggal_indo($data_cbd['tgl_ujian']);
									$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
									$awal_periode = tanggal_indo($data_cbd['tgl_awal']);
									$akhir_periode = tanggal_indo($data_cbd['tgl_akhir']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Case-Based Discussion (CBD)<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Kasus $data_cbd[kasus_ke] - Tanggal Ujian: $tanggal_ujian<br>";
									echo "Fokus Kasus: $data_cbd[fokus_kasus]<br>";
									echo "Nilai: $data_cbd[nilai_rata]";
									echo "</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_cbd['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table>";
							echo "</center><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Journal Reading
							echo "<a id=\"jurnal_M111\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Journal Reading</a><br><br>";
							$nilai_jurnal = mysqli_query($con, "SELECT * FROM `obsgyn_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M111\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_jurnal = mysqli_num_rows($nilai_jurnal);
							if ($cek_nilai_jurnal < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_jurnal = mysqli_fetch_array($nilai_jurnal)) {
									$tanggal_isi = tanggal_indo($data_jurnal['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_jurnal['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Journal Reading<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "<span style=\"color:green;\">Jurnal: $data_jurnal[nama_jurnal]</span><br>";
									echo "<span style=\"color:blue;\">Judul Artikel: $data_jurnal[judul_paper]</span><br>";
									echo "Nilai: $data_jurnal[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_jurnal['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table>";
							echo "</center><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai Penugasan dan Test (DOPS/OSCE, MCQ, dan MINI-PAT)
							echo "<a id=\"test_M111\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai Penugasan dan Test (DOPS/OSCE, MCQ, dan MINI-PAT)</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `obsgyn_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_M111\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
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
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span><br><br>";
									echo "<td>Penilaian $jenis_test[jenis_test]<br><br>";
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
							echo "</center><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) Forensik
						//-------------------------------------
						if ($id_stase == "M112") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#visum_M112\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Visum Bayangan</a>
								<a href=\"#jaga_M112\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Kegiatan Jaga</a>
								<a href=\"#substase_M112\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Substase</a>
								<br><br>
								<a href=\"#referat_M112\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Pengisian Formulir Penilaian Referat</a>
								<a href=\"#test_M112\" class=\"btn me-3\" style=\"background-color: #00008B; color: white;\">Preview Nilai-Nilai Test dan Perilaku</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian Visum Bayangan
							echo "<br><br>";
							echo "<a id=\"visum_M112\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Visum Bayangan</a><br><br>";
							$nilai_visum = mysqli_query($con, "SELECT * FROM `forensik_nilai_visum` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M112\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_visum = mysqli_num_rows($nilai_visum);
							if ($cek_nilai_visum < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_visum = mysqli_fetch_array($nilai_visum)) {
									$tanggal_isi = tanggal_indo($data_visum['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_visum['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_visum[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Visum Bayangan Dosen Ke-$data_visum[dosen_ke]<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_visum[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_visum['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Kegiatan Jaga
							echo "<a id=\"jaga_M112\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Kegiatan Jaga</a><br><br>";
							$nilai_jaga = mysqli_query($con, "SELECT * FROM `forensik_nilai_jaga` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M112\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_jaga = mysqli_num_rows($nilai_jaga);
							if ($cek_nilai_jaga < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_jaga = mysqli_fetch_array($nilai_jaga)) {
									$tanggal_isi = tanggal_indo($data_jaga['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_jaga['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_jaga[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Kegiatan Jaga Dosen Ke-$data_jaga[dosen_ke]<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_jaga[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_jaga['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Substase
							echo "<a id=\"substase_M112\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Substase</a><br><br>";
							$nilai_substase = mysqli_query($con, "SELECT * FROM `forensik_nilai_substase` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M112\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_substase = mysqli_num_rows($nilai_substase);
							if ($cek_nilai_substase < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_substase = mysqli_fetch_array($nilai_substase)) {
									$tanggal_isi = tanggal_indo($data_substase['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_substase['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_substase[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Substase Dosen Ke-$data_substase[dosen_ke]<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_substase[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_substase['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Referat
							echo "<a id=\"referat_M112\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Referat</a><br><br>";
							$nilai_referat = mysqli_query($con, "SELECT * FROM `forensik_nilai_referat` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_M112\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_referat = mysqli_num_rows($nilai_referat);
							if ($cek_nilai_referat < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_referat = mysqli_fetch_array($nilai_referat)) {
									$tanggal_isi = tanggal_indo($data_referat['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_referat['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_referat[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Referat<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nilai: $data_referat[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_referat['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai-Nilai Test dan Perilaku
							echo "<a id=\"test_M112\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai-Nilai Test dan Perilaku</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze5_M112\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
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
									$tanggal_test = tanggal_indo($data_test['tgl_test']);
									$status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$data_test[status_ujian]'"));
									$tanggal_approval = tanggal_indo($data_test['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_test[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\">Kedokteran Forensik dan Medikolegal</span><br><br>";
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
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Forensik
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) Ilmu Kesehatan Anak
						//-------------------------------------
						if ($id_stase == "M113") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#minicex_M113\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)</a>
								<a href=\"#dops_M113\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Direct Observation of Procedural Skill (DOPS)</a>
								<br><br>
								<a href=\"#cbd_M113\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Case-Based Discussion (CBD) - Kasus Poliklinik</a>
								<a href=\"#kasus_M113\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Pengisian Formulir Penilaian Penyajian Kasus Besar</a>
								<br><br>
								<a href=\"#jurnal_M113\" class=\"btn me-3\" style=\"background-color: #D2691E; color: white;\">Pengisian Formulir Penilaian Penyajian Journal Reading</a>
								<a href=\"#minipat_M113\" class=\"btn me-3\" style=\"background-color: #FF8C00; color: white;\">Pengisian Formulir Penilaian Mini Peer Assesment Tool (Mini-PAT)</a>
								<br><br>
								<a href=\"#ujian_M113\" class=\"btn me-3\" style=\"background-color: #4B0082; color: white;\">Pengisian Formulir Penilaian Ujian Akhir Kepaniteraan</a>
								<a href=\"#test_M113\" class=\"btn me-3\" style=\"background-color: #00008B; color: white;\">Preview Nilai Ujian/Test (Pre-Test, Post-Test, dan Ujian OSCE)</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)
							echo "<br><br>";
							echo "<center>";
							echo "<a id=\"minicex_M113\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)</a><br><br>";
							echo "<div class=\"alert alert-warning\" style=\"width:60%; font-size:0.875em; text-align: left; \"><span class=\"text-danger\" style=\"font-weight:700;\">Catatan:</span><br><span style=\"font-weight:600;\">- Pengisian wajib untuk penilaian Mini-Cex adalah 14 (empat belas) kali.<br>- Nilai total Ujian Mini-Cex dihitung berdasarkan rasio penilaian jenis evaluasi masing-masing Mini-Cex (Infeksi: #1-#4, Non Infeksi: #5-#11, ERIA: #12, Perinatologi: #13, dan Kasus RS Jejaring: #14).<br>- Untuk cetak, minimal 1 (satu) penilaian Mini-Cex telah disetujui Dosen Penilai / DPJP.</span></div><br><br>";
							echo "</center>";
							$nilai_minicex = mysqli_query($con, "SELECT * FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M113\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai / DPJP</th>
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
									echo "<td style=\"font-weight:600;\">Penilaian Ujian MINI-CEX (Mini Clinical Examination)<br>";
									$evaluasi = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `ika_evaluasi_ref` WHERE `id`='$data_minicex[evaluasi]'"));
									echo "Jenis Evaluasi: $evaluasi[evaluasi] (#$evaluasi[id])<br>";
									echo "Tanggal Ujian: $tanggal_ujian<br><br>";
									echo "Nilai Ujian: $data_minicex[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_minicex['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Direct Observation of Procedural Skill (DOPS)
							echo "<a id=\"dops_M113\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Direct Observation of Procedural Skill (DOPS)</a><br><br>";
							$nilai_dops = mysqli_query($con, "SELECT * FROM `ika_nilai_dops` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M113\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_dops = mysqli_num_rows($nilai_dops);
							if ($cek_nilai_dops < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_dops = mysqli_fetch_array($nilai_dops)) {
									$tanggal_isi = tanggal_indo($data_dops['tgl_isi']);
									$tanggal_ujian = tanggal_indo($data_dops['tgl_ujian']);
									$tanggal_approval = tanggal_indo($data_dops['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Direct Observation of Procedural Skill (DOPS)<br>(Penilaian Ketrampilan Klinis)<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Tanggal Ujian: $tanggal_ujian<br>";
									echo "Nilai: $data_dops[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_dops['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian CBD
							echo "<a id=\"cbd_M113\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian CBD  - Kasus Poliklinik</a><br><br>";
							$nilai_cbd = mysqli_query($con, "SELECT * FROM `ika_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_M113\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai / Mentor</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_cbd = mysqli_num_rows($nilai_cbd);
							if ($cek_nilai_cbd < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_cbd = mysqli_fetch_array($nilai_cbd)) {
									$tanggal_isi = tanggal_indo($data_cbd['tgl_isi']);
									$tanggal_ujian = tanggal_indo($data_cbd['tgl_ujian']);
									$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
									$awal_periode = tanggal_indo($data_cbd['tgl_awal']);
									$akhir_periode = tanggal_indo($data_cbd['tgl_akhir']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Case-Based Discussion (CBD)<br>(Kasus Poliklinik)<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Nama Poliklinik: $data_cbd[poliklinik]<br>";
									echo "Tanggal Ujian: $tanggal_ujian<br>";
									echo "Nilai: $data_cbd[nilai_rata]";
									echo "</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_cbd['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Penyajian Kasus Besar
							echo "<a id=\"kasus_M113\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Penyajian Kasus Besar</a><br><br>";
							$nilai_kasus = mysqli_query($con, "SELECT * FROM `ika_nilai_kasus` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_M113\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
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
									$tanggal_isi = tanggal_indo($data_kasus['tgl_isi']);
									$tanggal_penyajian = tanggal_indo($data_kasus['tgl_penyajian']);
									$tanggal_approval = tanggal_indo($data_kasus['tgl_approval']);
									$awal_periode = tanggal_indo($data_kasus['tgl_awal']);
									$akhir_periode = tanggal_indo($data_kasus['tgl_akhir']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Penyajian Kasus Besar<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Judul Kasus: $data_kasus[kasus]<br>";
									echo "Tanggal Penyajian: $tanggal_penyajian<br>";
									echo "Nilai: $data_kasus[nilai_rata]";
									echo "</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_kasus['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Penyajian Journal Reading
							echo "<a id=\"jurnal_M113\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Penyajian Journal Reading</a><br><br>";
							$nilai_jurnal = mysqli_query($con, "SELECT * FROM `ika_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze5_M113\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_jurnal = mysqli_num_rows($nilai_jurnal);
							if ($cek_nilai_jurnal < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_jurnal = mysqli_fetch_array($nilai_jurnal)) {
									$tanggal_isi = tanggal_indo($data_jurnal['tgl_isi']);
									$tanggal_penyajian = tanggal_indo($data_jurnal['tgl_penyajian']);
									$tanggal_approval = tanggal_indo($data_jurnal['tgl_approval']);
									$awal_periode = tanggal_indo($data_jurnal['tgl_awal']);
									$akhir_periode = tanggal_indo($data_jurnal['tgl_akhir']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Penyajian Journal Reading<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Judul Paper/Makalah: $data_jurnal[jurnal]<br>";
									echo "Tanggal Penyajian: $tanggal_penyajian<br>";
									echo "Nilai: $data_jurnal[nilai_rata]";
									echo "</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_jurnal['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Mini Peer Assesment Tool (Mini-PAT)
							echo "<a id=\"minipat_M113\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Mini Peer Assesment Tool (Mini-PAT)</a><br><br>";
							$nilai_minipat = mysqli_query($con, "SELECT * FROM `ika_nilai_minipat` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze6_M113\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penilai</th>
			      <th style=\"width:15%;text-align:center;\">Status Approval</th>
			      </thead>";

							$cek_nilai_minipat = mysqli_num_rows($nilai_minipat);
							if ($cek_nilai_minipat < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_minipat = mysqli_fetch_array($nilai_minipat)) {
									$tanggal_isi = tanggal_indo($data_minipat['tgl_isi']);
									$tanggal_penilaian = tanggal_indo($data_minipat['tgl_penilaian']);
									$tanggal_approval = tanggal_indo($data_minipat['tgl_approval']);
									$awal_periode = tanggal_indo($data_minipat['tgl_awal']);
									$akhir_periode = tanggal_indo($data_minipat['tgl_akhir']);

									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_minipat[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Mini Peer Assesment Tool (Mini-PAT)<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Tanggal Penilaian: $tanggal_penilaian<br>";
									echo "Nilai: $data_minipat[nilai_rata]";
									echo "</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_minipat['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Ujian Akhir Kepaniteraan
							echo "<center>";
							echo "<a id=\"ujian_M113\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian Akhir Kepaniteraan</a><br><br>";
							echo "<div class=\"alert alert-warning\" style=\"width:60%; font-size:0.875em; text-align: left; \"><span class=\"text-danger\" style=\"font-weight:700;\">Catatan:</span><br><span style=\"font-weight:600;\">- Ujian Akhir maksimal dilakukan 3 (tiga) kali (perbaikan).<br>- Nilai Akhir diambil yang terbaik dari semua ujian yang diikuti.</span></div><br><br>";
							echo "</center>";
							$nilai_ujian = mysqli_query($con, "SELECT * FROM `ika_nilai_ujian` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze7_M113\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Dosen Penguji</th>
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
									$tanggal_ujian = tanggal_indo($data_ujian['tgl_ujian']);
									$tanggal_approval = tanggal_indo($data_ujian['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_ujian[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Penilaian Ujian Akhir Kepaniteraan (Stase) Ilmu Kesehatan Anak<br><br>";
									echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br><span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
									echo "Tanggal Ujian: $tanggal_ujian<br>";
									echo "Nilai: $data_ujian[nilai_rata]";
									echo "</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_ujian['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai Ujian/Test (Pre-Test, Post-Test, dan Ujian OSCE)
							echo "<a id=\"test_M113\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai Ujian/Test (Pre-Test, Post-Test, dan Ujian OSCE)</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `ika_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze8_M113\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
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
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\">Ilmu Kesehatan Anak</span><br><br>";
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
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Ilmu Kesehatan Anak
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin
						//-------------------------------------
						if ($id_stase == "M114") {
							echo "<center>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#cbd_M114\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Ujian Kasus</a>
								<a href=\"#test_M114\" class=\"btn btn-primary me-3\">Preview Nilai-Nilai OSCE, Ujian Teori dan Perilaku</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian CBD
							echo "<br><br>";
							echo "<a id=\"cbd_M114\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Ujian Kasus</a><br><br>";
							$nilai_cbd = mysqli_query($con, "SELECT * FROM `kulit_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_M114\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal/Jam Pengisian</th>
						<th style=\"width:40%;text-align:center;\">Judul Kasus</th>
						<th style=\"width:25%;text-align:center;\">Dokter Pembimbing Lapangan</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_cbd = mysqli_num_rows($nilai_cbd);
							if ($cek_nilai_cbd < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_cbd = mysqli_fetch_array($nilai_cbd)) {
									$tanggal_isi = tanggal_indo($data_cbd['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
									$jam_isi = $data_cbd['jam_isi'];
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"font-weight:600;\"><span style=\"color:darkblue\">$tanggal_isi</span><br>Pukul $jam_isi</td>";
									echo "<td style=\"font-weight:600;\"><span style=\"color:green;\">Judul: $data_cbd[kasus]</span><br><br>";
									echo "Nilai: $data_cbd[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_cbd['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Preview Nilai-Nilai Test dan Perilaku
							echo "<a id=\"test_M114\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Preview Nilai-Nilai OSCE, Ujian Teori dan Perilaku</a><br><br>";
							$nilai_test = mysqli_query($con, "SELECT * FROM `kulit_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_M114\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
			      <th style=\"width:5%;text-align:center;\">No</th>
			      <th style=\"width:15%;text-align:center;\">Tanggal Ujian</th>
			      <th style=\"width:40%;text-align:center;\">Jenis Penilaian</th>
			      <th style=\"width:25%;text-align:center;\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%;text-align:center;\">Nilai</th>
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
									$tanggal_test = tanggal_indo($data_test['tgl_test']);
									$status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$data_test[status_ujian]'"));
									$tanggal_approval = tanggal_indo($data_test['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_test[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_test</td>";
									echo "<td style=\"font-weight:600;\">Penilaian <span style=\"color:red;\">$jenis_test[jenis_test]</span> Kepaniteraan (Stase) <span style=\"color:darkgreen;\">Ilmu Kesehatan dan Kelamin</span><br><br>";
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
							echo "</table><br><br>";
						}
						echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
						echo "<br>";
						//-------------------------------------
						//End of Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin
						//-------------------------------------

						//-------------------------------------
						//Kepaniteraan (Stase) Komprehensip dan Kedokteran Keluarga
						//-------------------------------------
						if ($id_stase == "M121") {
							//Komprehensip
							//------------------------------------
							echo "<center>";
							echo "<h4 style=\"font-family:'Poppins', sans-serif;\">Kepaniteraan Komprehensip</h4>";
							echo "<br><br>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian formulir</span>
								<br><br>
								<a href=\"#laporan_kompre\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Laporan</a>
								<a href=\"#sikap_kompre\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Sikap/Perilaku</a>
								<a href=\"#cbd_kompre\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir Penilaian Case Based Discussion (CBD)</a>
								<br><br>
								<a href=\"#presensi_kompre\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Pengisian Formulir Penilaian Presensi / Kehadiran</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian Laporan
							echo "<br><br>";
							echo "<a id=\"laporan_kompre\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Laporan</a><br><br>";
							$nilai_laporan = mysqli_query($con, "SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_kompre\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:25%;text-align:center;\">Instansi / Lokasi</th>
						<th style=\"width:15%;text-align:center;\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%;text-align:center;\">Dosen Pembimbing Lapangan</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_laporan = mysqli_num_rows($nilai_laporan);
							if ($cek_nilai_laporan < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=6 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_laporan = mysqli_fetch_array($nilai_laporan)) {
									$tanggal_isi = tanggal_indo($data_laporan['tgl_isi']);
									$tanggal_mulai = tanggal_indo($data_laporan['tgl_mulai']);
									$tanggal_selesai = tanggal_indo($data_laporan['tgl_selesai']);
									$tanggal_approval = tanggal_indo($data_laporan['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_laporan[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Instansi: $data_laporan[instansi]<br>Lokasi: $data_laporan[lokasi]<br><br>";
									echo "Nilai Individu: $data_laporan[nilai_rata_ind]<br>";
									echo "Nilai Kelompok: $data_laporan[nilai_rata_kelp]</td>";
									echo "<td align=center style=\"font-weight:600;\">$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_laporan['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Sikap/Perilaku
							echo "<a id=\"sikap_kompre\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Sikap/Perilaku</a><br><br>";
							$nilai_sikap = mysqli_query($con, "SELECT * FROM `kompre_nilai_sikap` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_kompre\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:25%;text-align:center;\">Instansi / Lokasi</th>
						<th style=\"width:15%;text-align:center;\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%;text-align:center;\">Dokter Pembimbing</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_sikap = mysqli_num_rows($nilai_sikap);
							if ($cek_nilai_sikap < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=6 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_sikap = mysqli_fetch_array($nilai_sikap)) {
									$tanggal_isi = tanggal_indo($data_sikap['tgl_isi']);
									$tanggal_mulai = tanggal_indo($data_sikap['tgl_mulai']);
									$tanggal_selesai = tanggal_indo($data_sikap['tgl_selesai']);
									$tanggal_approval = tanggal_indo($data_sikap['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_sikap[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Instansi: $data_sikap[instansi]<br>Lokasi: $data_sikap[lokasi]<br><br>";
									echo "Nilai: $data_sikap[nilai_rata]</td>";
									echo "<td align=center style=\"font-weight:600;\">$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_sikap['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian CBD
							echo "<a id=\"cbd_kompre\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Case Based Discussion (CBD)</a><br><br>";
							$nilai_cbd = mysqli_query($con, "SELECT * FROM `kompre_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_kompre\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal/Jam Pengisian</th>
						<th style=\"width:40%;text-align:center;\">Judul Kasus</th>
						<th style=\"width:25%;text-align:center;\">Dokter Pembimbing Lapangan</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_cbd = mysqli_num_rows($nilai_cbd);
							if ($cek_nilai_cbd < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=5 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_cbd = mysqli_fetch_array($nilai_cbd)) {
									$tanggal_isi = tanggal_indo($data_cbd['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
									$jam_isi = $data_cbd['jam_isi'];
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center; font-weight:600;\">$tanggal_isi<br>Pukul $jam_isi</td>";
									echo "<td style=\"font-weight:600;\">Judul: $data_cbd[kasus]<br><br>";
									echo "Nilai: $data_cbd[nilai_rata]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_cbd['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Presensi / Kehadiran
							echo "<a id=\"presensi_kompre\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Presensi / Kehadiran</a><br><br>";
							$nilai_presensi = mysqli_query($con, "SELECT * FROM `kompre_nilai_presensi` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_kompre\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:25%;text-align:center;\">Instansi / Lokasi</th>
						<th style=\"width:15%;text-align:center;\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%;text-align:center;\">Dokter Pembimbing</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_presensi = mysqli_num_rows($nilai_presensi);
							if ($cek_nilai_presensi < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=6 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_presensi = mysqli_fetch_array($nilai_presensi)) {
									$tanggal_isi = tanggal_indo($data_presensi['tgl_isi']);
									$tanggal_mulai = tanggal_indo($data_presensi['tgl_mulai']);
									$tanggal_selesai = tanggal_indo($data_presensi['tgl_selesai']);
									$tanggal_approval = tanggal_indo($data_presensi['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_presensi[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Instansi: $data_presensi[instansi]<br>Lokasi: $data_presensi[lokasi]<br><br>Nilai: $data_presensi[nilai_total]</td>";
									echo "<td align=center style=\"font-weight:600;\">$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_presensi['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";
							//------------------------------------

							//Kedokteran Keluarga
							//------------------------------------
							echo "<center>";
							echo "<h4 style=\"font-family:'Poppins', sans-serif;\">Kepaniteraan Kedokteran Keluarga</h4>";
							echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/cetak nilai</span>
								<br><br>
								<a href=\"#lap_kasus_kdk\" class=\"btn btn-success me-3\">Pengisian Formulir Penilaian Portofolio Laporan Kasus</a>
								<a href=\"#sikap_kdk\" class=\"btn btn-primary me-3\">Pengisian Formulir Penilaian Sikap/Perilaku</a>
								<a href=\"#dops_kdk\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Pengisian Formulir DOPS</a>
								<br><br>
								<a href=\"#minicex_kdk\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Pengisian Formulir Penilaian MINI-CEX</a>
								<a href=\"#presensi_kdk\" class=\"btn me-3\" style=\"background-color: #00008B; color: white;\">Pengisian Formulir Penilaian Presensi / Kehadiran</a>
								<br><br>
								";
							echo "</center>";

							//Pengisian Formulir Penilaian Portofolio Laporan Kasus
							echo "<br><br>";
							echo "<a id=\"lap_kasus_kdk\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Portofolio Laporan Kasus</a><br><br>";
							$nilai_kasus = mysqli_query($con, "SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1_kdk\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:25%;text-align:center;\">Lokasi / Kasus</th>
						<th style=\"width:15%;text-align:center;\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%;text-align:center;\">Dosen Pembimbing Lapangan</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_kasus = mysqli_num_rows($nilai_kasus);
							if ($cek_nilai_kasus < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=6 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_kasus = mysqli_fetch_array($nilai_kasus)) {
									$tanggal_isi = tanggal_indo($data_kasus['tgl_isi']);
									$tanggal_mulai = tanggal_indo($data_kasus['tgl_mulai']);
									$tanggal_selesai = tanggal_indo($data_kasus['tgl_selesai']);
									$tanggal_approval = tanggal_indo($data_kasus['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">$data_kasus[lokasi]<br>Kasus: $data_kasus[kasus]</i><br><br>Nilai: $data_kasus[nilai_rata]</i></td>";
									echo "<td align=center style=\"font-weight:600;\"><span style=\"color:darkblue;\">$tanggal_mulai</span><br>s.d.<br><span style=\"color:darkblue;\">$tanggal_selesai</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_kasus['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Sikap/Perilaku
							echo "<a id=\"sikap_kdk\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Sikap/Perilaku</a><br><br>";
							$nilai_sikap = mysqli_query($con, "SELECT * FROM `kdk_nilai_sikap` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2_kdk\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:25%;text-align:center;\">Instansi / Lokasi</th>
						<th style=\"width:15%;text-align:center;\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%;text-align:center;\">Dokter Pembimbing</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_sikap = mysqli_num_rows($nilai_sikap);
							if ($cek_nilai_sikap < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=6 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_sikap = mysqli_fetch_array($nilai_sikap)) {
									$tanggal_isi = tanggal_indo($data_sikap['tgl_isi']);
									$tanggal_mulai = tanggal_indo($data_sikap['tgl_mulai']);
									$tanggal_selesai = tanggal_indo($data_sikap['tgl_selesai']);
									$tanggal_approval = tanggal_indo($data_sikap['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_sikap[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Instansi: $data_sikap[instansi]<br>Lokasi: $data_sikap[lokasi]<br><br>Nilai: $data_sikap[nilai_rata]</td>";
									echo "<td align=center style=\"font-weight:600;\"><span style=\"color:darkblue;\">$tanggal_mulai</span><br>s.d.<br><span style=\"color:darkblue;\">$tanggal_selesai</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_sikap['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir DOPS
							echo "<a id=\"dops_kdk\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir DOPS</a><br><br>";
							$nilai_dops = mysqli_query($con, "SELECT * FROM `kdk_nilai_dops` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze3_kdk\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:25%;text-align:center;\">Instansi / Lokasi</th>
						<th style=\"width:15%;text-align:center;\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%;text-align:center;\">Dokter Pembimbing</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_dops = mysqli_num_rows($nilai_dops);
							if ($cek_nilai_dops < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=6 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_dops = mysqli_fetch_array($nilai_dops)) {
									$tanggal_isi = tanggal_indo($data_dops['tgl_isi']);
									$tanggal_mulai = tanggal_indo($data_dops['tgl_mulai']);
									$tanggal_selesai = tanggal_indo($data_dops['tgl_selesai']);
									$tanggal_approval = tanggal_indo($data_dops['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Instansi: $data_dops[instansi]<br>Lokasi: $data_dops[lokasi]<br><br>Nilai: $data_dops[nilai_rata]</td>";
									echo "<td align=center style=\"font-weight:600;\"><span style=\"color:darkblue;\">$tanggal_mulai</span><br>s.d.<br><span style=\"color:darkblue;\">$tanggal_selesai</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_dops['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian MINI-CEX
							echo "<a id=\"minicex_kdk\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian MINI-CEX</a><br><br>";
							$nilai_minicex = mysqli_query($con, "SELECT * FROM `kdk_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze4_kdk\">";
							echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:20%;text-align:center;\">Instansi / Lokasi</th>
						<th style=\"width:5%;text-align:center;\">No Ujian</th>
						<th style=\"width:15%;text-align:center;\">Problem Pasien/Diagnosis</th>
						<th style=\"width:25%;text-align:center;\">Penilai/DPJP</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_minicex = mysqli_num_rows($nilai_minicex);
							if ($cek_nilai_minicex < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=7 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_minicex = mysqli_fetch_array($nilai_minicex)) {
									$tanggal_isi = tanggal_indo($data_minicex['tgl_isi']);
									$tanggal_approval = tanggal_indo($data_minicex['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Instansi: $data_minicex[instansi]<br>Lokasi: $data_minicex[lokasi]<br><br>Nilai: $data_minicex[nilai_rata]</td>";
									echo "<td align=center style=\"font-weight:600;\">$data_minicex[no_ujian]</td>";
									echo "<td style=\"font-weight:600;\">$data_minicex[diagnosis]</td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_minicex['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";
							echo "<hr style=\"border: 2px solid ; color:blue; margin: 20px 0;\">";
							echo "<br>";

							//Pengisian Formulir Penilaian Presensi / Kehadiran
							echo "<a id=\"presensi_kdk\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Pengisian Formulir Penilaian Presensi / Kehadiran</a><br><br>";
							$nilai_presensi = mysqli_query($con, "SELECT * FROM `kdk_nilai_presensi` WHERE `nim`='$data_mhsw[nim]'");
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze5_kdk\">";
							echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<th style=\"width:5%;text-align:center;\">No</th>
						<th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
						<th style=\"width:25%;text-align:center;\">Instansi / Lokasi</th>
						<th style=\"width:15%;text-align:center;\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%;text-align:center;\">Dokter Pembimbing</th>
						<th style=\"width:15%;text-align:center;\">Status Approval</th>
						</thead>";

							$cek_nilai_presensi = mysqli_num_rows($nilai_presensi);
							if ($cek_nilai_presensi < 1) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=6 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
							} else {
								$no = 1;
								$kelas = "ganjil";
								while ($data_presensi = mysqli_fetch_array($nilai_presensi)) {
									$tanggal_isi = tanggal_indo($data_presensi['tgl_isi']);
									$tanggal_mulai = tanggal_indo($data_presensi['tgl_mulai']);
									$tanggal_selesai = tanggal_indo($data_presensi['tgl_selesai']);
									$tanggal_approval = tanggal_indo($data_presensi['tgl_approval']);
									$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_presensi[dosen]'"));
									echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
									echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
									echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
									echo "<td style=\"font-weight:600;\">Instansi: $data_presensi[instansi]<br>Lokasi: $data_presensi[lokasi]<br><br>Nilai: $data_presensi[nilai_total]</td>";
									echo "<td align=center style=\"font-weight:600;\"><span style=\"color:darkblue;\">$tanggal_mulai</span><br>s.d.<br><span style=\"color:darkblue;\">$tanggal_selesai</span></td>";
									echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span> (NIP. <span style=\"color:blue;\">$data_dosen[nip]</span>)<br><br>";
									echo "<td align=center style=\"font-weight:600;\">";
									if ($data_presensi['status_approval'] == '0')
										echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
									else {
										echo "<font style=\"color:green\">DISETUJUI</font><br>";
										echo "per tanggal<br>";
										echo "$tanggal_approval";
									}
									echo "</td>";
									echo "</tr>";
									$no++;
									if ($kelas == "ganjil") $kelas = "genap";
									else $kelas = "ganjil";
								}
							}
							echo "</table><br><br>";

							//------------------------------------
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

	<script src="/javascript/script1.js"></script>
	<script src="/javascript/buttontopup2.js"></script>
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze1_M091").freezeHeader();
			$("#freeze2_M091").freezeHeader();
			$("#freeze3_M091").freezeHeader();
			$("#freeze4_M091").freezeHeader();

			$("#freeze1_M092").freezeHeader();
			$("#freeze2_M092").freezeHeader();
			$("#freeze3_M092").freezeHeader();
			$("#freeze4_M092").freezeHeader();
			$("#freeze5_M092").freezeHeader();

			$("#freeze1_M093").freezeHeader();
			$("#freeze2_M093").freezeHeader();
			$("#freeze3_M093").freezeHeader();
			$("#freeze4_M093").freezeHeader();
			$("#freeze5_M093").freezeHeader();

			$("#freeze1_M105").freezeHeader();
			$("#freeze2_M105").freezeHeader();
			$("#freeze3_M105").freezeHeader();
			$("#freeze4_M105").freezeHeader();
			$("#freeze5_M105").freezeHeader();

			$("#freeze1_M095").freezeHeader();
			$("#freeze2_M095").freezeHeader();
			$("#freeze3_M095").freezeHeader();
			$("#freeze4_M095").freezeHeader();
			$("#freeze5_M095").freezeHeader();

			$("#freeze1_M101").freezeHeader();
			$("#freeze2_M101").freezeHeader();

			$("#freeze1_M102").freezeHeader();
			$("#freeze2_M102").freezeHeader();
			$("#freeze3_M102").freezeHeader();

			$("#freeze1_M103").freezeHeader();
			$("#freeze2_M103").freezeHeader();
			$("#freeze3_M103").freezeHeader();
			$("#freeze4_M103").freezeHeader();
			$("#freeze5_M103").freezeHeader();

			$("#freeze1_M104").freezeHeader();
			$("#freeze2_M104").freezeHeader();
			$("#freeze3_M104").freezeHeader();
			$("#freeze4_M104").freezeHeader();
			$("#freeze5_M104").freezeHeader();

			$("#freeze1_M094").freezeHeader();
			$("#freeze2_M094").freezeHeader();
			$("#freeze3_M094").freezeHeader();
			$("#freeze4_M094").freezeHeader();

			$("#freeze1_M106").freezeHeader();
			$("#freeze2_M106").freezeHeader();
			$("#freeze3_M106").freezeHeader();
			$("#freeze4_M106").freezeHeader();

			$("#freeze1_M111").freezeHeader();
			$("#freeze2_M111").freezeHeader();
			$("#freeze3_M111").freezeHeader();
			$("#freeze4_M111").freezeHeader();

			$("#freeze1_M112").freezeHeader();
			$("#freeze2_M112").freezeHeader();
			$("#freeze3_M112").freezeHeader();
			$("#freeze4_M112").freezeHeader();
			$("#freeze5_M112").freezeHeader();

			$("#freeze1_M113").freezeHeader();
			$("#freeze2_M113").freezeHeader();
			$("#freeze3_M113").freezeHeader();
			$("#freeze4_M113").freezeHeader();
			$("#freeze5_M113").freezeHeader();
			$("#freeze6_M113").freezeHeader();
			$("#freeze7_M113").freezeHeader();
			$("#freeze8_M113").freezeHeader();

			$("#freeze1_M114").freezeHeader();
			$("#freeze2_M114").freezeHeader();

			$("#freeze1_kompre").freezeHeader();
			$("#freeze2_kompre").freezeHeader();
			$("#freeze3_kompre").freezeHeader();
			$("#freeze4_kompre").freezeHeader();
			$("#freeze5_kompre").freezeHeader();

			$("#freeze1_kdk").freezeHeader();
			$("#freeze2_kdk").freezeHeader();
			$("#freeze3_kdk").freezeHeader();
			$("#freeze4_kdk").freezeHeader();
			$("#freeze5_kdk").freezeHeader();
		});
	</script>

</body>

</html>