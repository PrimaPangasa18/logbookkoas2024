<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Daftar Kegiatan Dosen/Residen Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 2) {
				if ($_COOKIE['level'] == 2) {
					include "menu2.php";
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
						<h3 class="fw-bold fs-4 mb-3">DAFTAR KEGIATAN DOSEN/RESIDEN</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">DAFTAR KEGIATAN DOSEN/RESIDEN</h2>
						<br>
						<?php
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

						$user_dosen = $_GET['dosen'];
						$mhsw_filter = $_GET['mhsw'];
						$stase_filter = $_GET['stase'];
						$status_filter = $_GET['appstatus'];
						$tanggal_filter = $_GET['tgl_kegiatan'];

						if ($mhsw_filter == "all") $filtermhsw = "";
						else $filtermhsw = "AND `nim`=" . "'$mhsw_filter'";
						if ($stase_filter == "all") $filterstase = "";
						else $filterstase = "AND `stase`=" . "'$stase_filter'";
						if ($status_filter == "all") $filterstatus = "";
						else $filterstatus = "AND `status`=" . "'$status_filter'";
						if ($tanggal_filter == "Semua Tanggal") $filtertanggal = "";
						else $filtertanggal = "AND `tanggal`=" . "'$tanggal_filter'";
						$dosen_filter = "`dosen`=" . "'$user_dosen'";

						$filter_penyakit = "SELECT * FROM `jurnal_penyakit` WHERE $dosen_filter $filtermhsw $filterstase $filtertanggal $filterstatus ORDER BY `tanggal`,`jam_awal`";
						$filter_ketrampilan = "SELECT * FROM `jurnal_ketrampilan` WHERE $dosen_filter $filtermhsw $filterstase $filtertanggal $filterstatus ORDER BY `tanggal`,`jam_awal`";
						$biodata_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$user_dosen'"));
						$data_penyakit = mysqli_query($con, $filter_penyakit);
						$data_ketrampilan = mysqli_query($con, $filter_ketrampilan);
						$jum1 = mysqli_num_rows($data_penyakit);
						$jum2 = mysqli_num_rows($data_ketrampilan);
						?>
						<br>
						<center>
							<table class="table table-bordered" style="width: auto;">
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td style="width:300px"><strong>Nama Dosen/Residen</strong></td>
									<td style="width:500px;font-weight: 600;">: <span style="color: blue;"><?php echo $biodata_dosen['nama']; ?></span>, <span style="color: red;"><?php echo $biodata_dosen['gelar']; ?></span></td>
								</tr>
								<tr class="table-success" style="border-width: 1px; border-color: #000;">
									<td><strong>Username Dosen/Residen</strong></td>
									<td style="font-weight: 600;">: <span style="color: darkred;"><?php echo $biodata_dosen['nip']; ?></span></td>
								</tr>
							</table>
						</center>
						<br><br>
						<center>
							<span class="text-danger" style="font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600">Tekan tobol dibawah ini untuk melihat masing-masing cek</span>
							<br><br>
							<a href="#penyakit" class="btn btn-success me-3">Kegitan Jurnal Penyakit</a>
							<a href="#trampil" class="btn btn-primary me-3">Kegiatan Jurnal Ketrampilan</a>
							<br><br><br>
						</center>
						<a id="penyakit" style="font-size:1.0em;font-family:'Poppins', sans-serif;font-weight:800;">Kegiatan Terkait Jurnal Penyakit Mahasiswa Koas</a><br>
						<?php
						echo "<table class=\"table table-bordered\" id=\"freeze\" style=\"width:100%;\">";
						echo "<thead class=\"table-primary\">";
						echo "<tr style=\"border-width: 1px; border-color: #000;\">";
						echo "<th style=\"width:4%;text-align:center;\">No</th>";
						echo "<th style=\"width:21%;text-align:center;\">Nama Mahasiswa <span class=\"text-danger\">(NIM)</span>/<br>Tanggal/Waktu</th>";
						echo "<th style=\"width:25%;text-align:center;\">Kegiatan (Level Penguasaan)/<br>Lokasi</th>";
						echo "<th style=\"width:25%;text-align:center;\">Penyakit<br>(Level SKDI/Kepmenkes/<br>IPSG/Muatan Lokal)</th>";
						echo "<th style=\"width:25%;text-align:center;\">Kegiatan Dosen/Residen<br>Approval</th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tbody class=\"table-warning\">";
						if ($jum1 == 0) {
							echo "<tr style=\"border-width: 1px; border-color: #000;\"><td colspan=\"5\" class=\"text-center\" style=\"color: red; font-weight:600\"><br><span><< EMPTY >></i><br><br></td></tr>";
						} else {
							$no = 1;
							$kelas = "ganjil";
							while ($data1 = mysqli_fetch_array($data_penyakit)) {
								$nama_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$data1[nim]'"));
								$tanggal_keg = tanggal_indo($data1['tanggal']);
								$kegiatan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kegiatan` WHERE `id`='$data1[kegiatan]'"));
								$lokasi = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `lokasi` WHERE `id`='$data1[lokasi]'"));
								echo "<tr class=\"$kelas\" style=\"border-width: 1px; border-color: #000;\">";
								echo "<td class=\"td_up text-center\" style=\"font-weight: 600;\">$no</td>";
								echo "<td class=\"td_up\" style=\"font-weight: 600;\">
              						  <a href=\"biodata.php?nim=$data1[nim]\" target=\"_blank\" class=\"btn btn-outline-primary btn-sm\">$nama_mhsw[nama]</a><br>
                					(<span style=\"color: green;\">$data1[nim]</span>)
               						 <p>
                   					 Tanggal: <span style=\"color: red;\">$tanggal_keg</span><br>
                   					 Waktu: <span style=\"color: purple;\">$data1[jam_awal] - $data1[jam_akhir]</span>
                					</p>
              						</td>";
								echo "<td class=\"td_up\" style=\"font-weight: 600;\">
              						  <span style=\"color: darkblue;\">$kegiatan[kegiatan]</span> (<span style=\"color: red;\">$kegiatan[level]</span>).
               						 <p>
                   					 Lokasi: <span style=\"color: darkgreen;\">$lokasi[lokasi]</span>
                					</p>
              						</td>";
								echo "<td class=\"td_up\" style=\"font-weight: 600;\">";

								$id = 1;
								while ($id <= 4) {
									$penyakit_id = "penyakit" . $id;
									if ($data1[$penyakit_id] != "") {
										$penyakit = mysqli_fetch_array(mysqli_query($con, "SELECT `penyakit`,`skdi_level`,`k_level`,`ipsg_level`,`kml_level` FROM `daftar_penyakit` WHERE `id`='$data1[$penyakit_id]'"));
										$penyakit_kapital = strtoupper($penyakit['penyakit']);
										$warna = ($id == 1) ? "black" : (($id == 2) ? "brown" : (($id == 3) ? "blue" : "green"));
										echo "<font style=\"color:$warna\">$penyakit_kapital ($penyakit[skdi_level]/$penyakit[k_level]/$penyakit[ipsg_level]/$penyakit[kml_level]).</font><p>";
									}
									$id++;
								}
								echo "</td>";
								echo "<td class=\"td_up\" style=\"font-weight: 600;\">
                				<span style=\"color: darkblue;\">$kegiatan[kegiatan_dosen]</span> [<span style=\"color: darkgreen;\">$kegiatan[level]</span> - <span style=\"color: purple;\">Kategori $kegiatan[kategori]</span>]
               				 	<br><br>
                				<span>Status: ";
								if ($data1['status'] == 0) {
									echo "<font style=\"color:red\">Not-Approved</font>
                 				 &nbsp;&nbsp;<a href=\"dosen_approve.php?dosen=$user_dosen&mhsw=$mhsw_filter&stase=$stase_filter&tgl_kegiatan=$tanggal_filter&appstatus=$status_filter&jurnal=penyakit&id=$data1[id]&status=1\">
                  				<button type=\"button\" class=\"btn btn-success btn-sm\"><i class=\"fa fa-check me-1\"></i> APPROVE</button></a>";
								} else {
									echo "<font style=\"color:green\">Approved</font>
                  				&nbsp;&nbsp;<a href=\"dosen_approve.php?dosen=$user_dosen&mhsw=$mhsw_filter&stase=$stase_filter&tgl_kegiatan=$tanggal_filter&appstatus=$status_filter&jurnal=penyakit&id=$data1[id]&status=0\">
                  			<button type=\"button\" class=\"btn btn-danger btn-sm\"><i class=\"fa fa-times me-1\"></i> UNAPPROVE</button></a>";
								}
								echo "</span></td>";
								echo "</tr>";
								$no++;
								$kelas = ($kelas == "ganjil") ? "genap" : "ganjil";
							}
						}
						echo "</tbody>";
						echo "</table><br>";
						?>
						<center>
							<span class="text-danger" style="font-weight: 600;">Untuk Mengapporve Semua Kegiatan Mahasiswa Tekan Tombol Dibawah</span>
							<br><br>
							<a href="dosen_approve_all.php?dosen=<?php echo $user_dosen; ?>&mhsw=<?php echo $mhsw_filter; ?>&stase=<?php echo $stase_filter; ?>&tgl_kegiatan=<?php echo $tanggal_filter; ?>&appstatus=<?php echo $status_filter; ?>&jurnal=penyakit" class="btn btn-primary btn-sm">
								<i class="fa fa-check-circle me-2"></i> APPROVE ALL
							</a>
						</center>
						<br>
						<hr style="border: 2px solid ; margin: 20px 0; color:grey">
						<br>
						<a id="trampil" style="font-size:1.0em;font-family:'Poppins', sans-serif;font-weight:800;">Kegiatan Terkait Jurnal Ketrampilan Mahasiswa Koas</a><br>
						<?php
						echo "<table class=\"table table-bordered\" id=\"freeze1\" style=\"width:100%;\">";
						echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<th style=\"width:4%;text-align:center;\">No</th>";
						echo "<th style=\"width:21%;text-align:center;\">Nama Mahasiswa <span class=\"text-danger\">(NIM)</span>/<br>Tanggal/Waktu</th>";
						echo "<th style=\"width:25%;text-align:center;\">Kegiatan (Level Penguasaan)/<br>Lokasi</th>";
						echo "<th style=\"width:25%;text-align:center;\">Ketrampilan<br>(Level SKDI/Kepmenkes/<br>IPSG/Muatan Lokal)</th>";
						echo "<th style=\"width:25%;text-align:center;\">Kegiatan Dosen/Residen<br>Approval</th>";
						echo "</thead>";
						echo "<tbody class=\"table-warning\">";
						if ($jum2 == 0) {
							echo "<tr style=\"border-width: 1px; border-color: #000;\"><td colspan=5 align=\"center\" style=\"color: red; font-weight:600\"><br><span><<  E M P T Y  >></i><br><br></td></tr>";
						}

						$no = 1;
						$kelas = "ganjil";
						while ($data2 = mysqli_fetch_array($data_ketrampilan)) {
							$nama_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$data2[nim]'"));
							$tanggal_keg = tanggal_indo($data2['tanggal']);
							$kegiatan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kegiatan` WHERE `id`='$data2[kegiatan]'"));
							$lokasi = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `lokasi` WHERE `id`='$data2[lokasi]'"));
							echo "<tr class=\"$kelas\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td class=\"td_up text-center\" style=\"font-weight: 600;\">$no</td>";

							echo "<td class=\"td_up\"  style=\"font-weight: 600;\">
							  <a href=\"biodata.php?nim=$data1[nim]\" target=\"_blank\" class=\"btn btn-outline-primary btn-sm\">$nama_mhsw[nama]</a><br>
							  (<span style=\"color: green;\">$data2[nim]</span>)
							  <p>
							 Tanggal: <span style=\"color: red;\">$tanggal_keg</span><br>
							 Waktu: <span style=\"color: purple;\">$data2[jam_awal] - $data2[jam_akhir]</span>
							</p>
							</td>";
							echo "<td class=\"td_up\" style=\"font-weight: 600;\">
              						  <span style=\"color: darkblue;\">$kegiatan[kegiatan]</span> (<span style=\"color: red;\">$kegiatan[level]</span>).
               						 <p>
                   					 Lokasi: <span style=\"color: darkgreen;\">$lokasi[lokasi]</span>
                					</p>
              						</td>";
							echo "<td class=\"td_up\" style=\"font-weight: 600;\">";

							$id = 1;
							while ($id <= 4) {
								$ketrampilan_id = "ketrampilan" . $id;
								if ($data2[$ketrampilan_id] != "") {
									$ketrampilan = mysqli_fetch_array(mysqli_query($con, "SELECT `ketrampilan`,`skdi_level`,`k_level`,`ipsg_level`,`kml_level` FROM `daftar_ketrampilan` WHERE `id`='$data2[$ketrampilan_id]'"));
									$ketrampilan_kapital = strtoupper($ketrampilan['ketrampilan']);
									if ($id == 1) $warna = "black";
									if ($id == 2) $warna = "brown";
									if ($id == 3) $warna = "blue";
									if ($id == 4) $warna = "green";
									echo "<font style=\"color:$warna\">$ketrampilan_kapital ($ketrampilan[skdi_level]/$ketrampilan[k_level]/$ketrampilan[ipsg_level]/$ketrampilan[kml_level]).</font><p>";
								}
								$id++;
							}
							echo "</td>";
							echo "<td class=\"td_up\" style=\"font-weight: 600;\">
                				<span style=\"color: darkblue;\">$kegiatan[kegiatan_dosen]</span> [<span style=\"color: darkgreen;\">$kegiatan[level]</span> - <span style=\"color: purple;\">Kategori $kegiatan[kategori]</span>]
               				 	<br><br>
                				<span>Status: ";
							if ($data2['status'] == 0) {
								echo "<font style=\"color:red\">Not-Approved</font>
                 				 &nbsp;&nbsp;<a href=\"dosen_approve.php?dosen=$user_dosen&mhsw=$mhsw_filter&stase=$stase_filter&tgl_kegiatan=$tanggal_filter&appstatus=$status_filter&jurnal=ketrampilan&id=$data2[id]&status=1\">
                  				<button type=\"button\" class=\"btn btn-success btn-sm\" value=\"APPROVE\"><i class=\"fa fa-check me-1\"></i> APPROVE</button></a>";
							} else {
								echo "<font style=\"color:green\">Approved</font>
                  				&nbsp;&nbsp;<a href=\"dosen_approve.php?dosen=$user_dosen&mhsw=$mhsw_filter&stase=$stase_filter&tgl_kegiatan=$tanggal_filter&appstatus=$status_filter&jurnal=ketrampilan&id=$data2[id]&status=0\">
                  			<button type=\"button\" class=\"btn btn-danger btn-sm\"><i class=\"fa fa-times me-1\"></i> UNAPPROVE</button></a>";
								echo "</span></td>";
								echo "</tr>";
								$no++;
								if ($kelas == "ganjil") $kelas = "genap";
								else $kelas = "ganjil";
							}
						}
						echo "</tbody>";
						echo "</table><br>";

						?>
						<center>
							<span class="text-danger" style="font-weight: 600;">Untuk Mengapporve Semua Kegiatan Mahasiswa Tekan Tombol Dibawah</span>
							<br><br>
							<a href="dosen_approve_all.php?dosen=<?php echo $user_dosen; ?>&mhsw=<?php echo $mhsw_filter; ?>&stase=<?php echo $stase_filter; ?>&tgl_kegiatan=<?php echo $tanggal_filter; ?>&appstatus=<?php echo $status_filter; ?>&jurnal=ketrampilan" class="btn btn-primary btn-sm">
								<i class="fa fa-check-circle me-2"></i> APPROVE ALL
							</a>
						</center>
					</div>
			</main>
			<!-- Back to Top Button -->
			<button id="back-to-top" title="Back to Top">
				<i class="fa-solid fa-arrow-up" style="margin-bottom: 2px;"></i>
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
	<script src="javascript/buttontopup2.js"></script>
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze").freezeHeader();
			$("#freeze1").freezeHeader();
		});
	</script>
</body>

</html>