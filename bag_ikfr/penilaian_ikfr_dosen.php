<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Penilaian Bagian IKFR Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 4) {
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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) IKFR</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">PENILAIAN KEPANITERAAN (STASE) IKFR</h2>
						<br>
						<?php
						$tgl_mulai = $_GET['mulai'];
						$tgl_selesai = $_GET['selesai'];
						$approval = $_GET['approval'];
						$mhsw = $_GET['mhsw'];

						if ($approval == "all") {
							if ($mhsw == "all") {
								$filter_approval = "";
								$statusapproval = "Semua Status";
								$statusmhsw = "Semua Mahasiswa";
							} else {
								$nama_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$mhsw'"));
								$filter_approval = "AND `nim`='$mhsw'";
								$statusapproval = "Semua Status";
								$statusmhsw = "$nama_mhsw[nama] (NIM: $mhsw)";
							}
						} else {
							if ($mhsw == "all") {
								$filter_approval = "AND `status_approval`='$approval'";
								if ($approval == "0") $statusapproval = "Belum Disetujui";
								if ($approval == "1") $statusapproval = "Sudah Disetujui";
								$statusmhsw = "Semua Mahasiswa";
							} else {
								$nama_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$mhsw'"));
								$filter_approval = "AND `nim`='$mhsw' AND `status_approval`='$approval'";
								if ($approval == "0") $statusapproval = "Belum Disetujui";
								if ($approval == "1") $statusapproval = "Sudah Disetujui";
								$statusmhsw = "$nama_mhsw[nama] (NIM: $mhsw)";
							}
						}

						$mulai = tanggal_indo($tgl_mulai);
						$selesai = tanggal_indo($tgl_selesai);
						echo "<center>";
						echo "<table class=\"table table-bordered\" style=\"width:50%\" id=\"freeze\" >";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td style=\"width:50%;\"><strong>Periode Tanggal Pengisian</strong></td>
						<td  style=\"font-weight:600;\"><span style=\"color:darkblue;\">$mulai</span> s.d. <span style=\"color:darkblue;\">$selesai</span></td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<td><strong>Status Approval</strong></td>
						<td style=\"font-weight:600;\"><span style=\"color:darkgreen;\">$statusapproval</span></td></tr>";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td><strong>Mahasiswa</strong></td>
						<td style=\"font-weight:600;\">$statusmhsw</td></tr>";
						echo "</table><br>";

						$id_stase = "M094";
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
						echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk pengisian form/preview nilai</span>
								<br><br>
								<a href=\"#kasus\" class=\"btn btn-success me-3\"> Penilaian Diskusi Kasus</a>
								<a href=\"#minicex\" class=\"btn btn-primary me-3\"> Penilaian Ujian MINI-CEX</a>
								<br><br>
								";
						echo "</center>";
						echo "<br><br>";

						//Penilaian Diskusi Kasus
						echo "<a id=\"kasus\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Penilaian Diskusi Kasus</a><br><br>";
						$nilai_kasus = mysqli_query($con, "SELECT * FROM `ikfr_nilai_kasus` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
						echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
		      <th style=\"width:5%;text-align:center;\">No</th>
		      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
		      <th style=\"width:65%;text-align:center;\">Jenis Penilaian</th>
		      <th style=\"width:15%;text-align:center;\">Status Approval</th>
		      </thead>";
						$cek_nilai_kasus = mysqli_num_rows($nilai_kasus);
						if ($cek_nilai_kasus < 1) {
							echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
							<td colspan=4 align=center style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
						} else {
							$no = 1;
							$kelas = "ganjil";
							while ($data_kasus = mysqli_fetch_array($nilai_kasus)) {
								$tanggal_isi = tanggal_indo($data_kasus['tgl_isi']);
								$tanggal_approval = tanggal_indo($data_kasus['tgl_approval']);
								$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_kasus[nim]'"));
								echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
								echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
								echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
								echo "<td style=\"font-weight:600;\">Penilaian Diskusi Kasus<br><br>";
								echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
								echo "<span style=\"color:green\">Kasus: $data_kasus[kasus]</span><br>";
								echo "Nilai: $data_kasus[nilai_rata]</i></td>";
								echo "<td align=center>";
								if ($data_kasus['status_approval'] == '0') {
									echo "<font style=\"color:red; font-weight:600;\">BELUM DISETUJUI</font>";
									echo "<br><br>
										<a href=\"approve_kasus_dosen.php?id=$data_kasus[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\">
        										<button type=\"button\" class=\"btn btn-warning btn-sm\" name=\"approve_$data_kasus[id]\" value=\"VIEW & APPROVE\">
            								<i class=\"fas fa-eye me-2\"></i> VIEW & APPROVE
        									</button>
      										</a>";
								} else {
									echo "<font style=\"color:darkgreen; font-weight:600;\">DISETUJUI</font><br>";
									echo "<span style=\" font-weight:600;\">per tanggal</span><br>";
									echo "<span style=\"color:darkblue; font-weight:600;\">$tanggal_approval</span>";
									echo "<br><br>
										<a href=\"view_form_kasus.php?id=$data_kasus[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\">
        										<button type=\"button\" class=\"btn btn-success btn-sm\" name=\"view_$data_kasus[id]\" value=\"VIEW\">
            								<i class=\"fas fa-eye me-2\"></i> VIEW
        									</button>
      										</a>";

									echo "<br><br>
									<a href=\"unapprove_kasus_dosen.php?id=$data_kasus[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\">
									<button type=\"button\" class=\"btn btn-danger btn-sm\" name=\"unapprove\".\"$data_kasus[id]\"  value=\"UNAPPROVE\">
									<i class=\"fas fa-circle-xmark me-2\"></i> UNAPPROVE
									</button></a>";
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

						//Penilaian Ujian MINI-CEX
						echo "<a id=\"minicex\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Penilaian Ujian MINI-CEX</a><br><br>";
						$nilai_minicex = mysqli_query($con, "SELECT * FROM `ikfr_nilai_minicex` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
						echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze2\">";
						echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
		      <th style=\"width:5%;text-align:center;\">No</th>
		      <th style=\"width:15%;text-align:center;\">Tanggal Pengisian</th>
		      <th style=\"width:65%;text-align:center;\">Jenis Penilaian</th>
		      <th style=\"width:15%;text-align:center;\">Status Approval</th>
		      </thead>";
						$cek_nilai_minicex = mysqli_num_rows($nilai_minicex);
						if ($cek_nilai_minicex < 1) {
							echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
								<td colspan=4 align=center  style=\"color:red; font-weight:600;\"><<< E M P T Y >>></td></tr>";
						} else {
							$no = 1;
							$kelas = "ganjil";
							while ($data_minicex = mysqli_fetch_array($nilai_minicex)) {
								$tanggal_isi = tanggal_indo($data_minicex['tgl_isi']);
								$tanggal_approval = tanggal_indo($data_minicex['tgl_approval']);
								$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_minicex[nim]'"));
								echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
								echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
								echo "<td style=\"text-align:center;color:darkblue; font-weight:600;\">$tanggal_isi</td>";
								echo "<td style=\"font-weight:600;\">Penilaian Ujian MINI-CEX<br><br>";
								echo "<span style=\"color:purple;\">Nama Mahasiswa: $data_mhsw[nama]</span><br>
									<span style=\"color:red;\">NIM: $data_mhsw[nim]</span><br>";
								echo "Nilai: $data_minicex[nilai_rata]</td>";
								echo "<td align=center>";
								if ($data_minicex['status_approval'] == '0') {
									echo "<font style=\"color:red; font-weight:600;\">BELUM DISETUJUI</font>";
									echo "<br><br>
										<a href=\"approve_minicex_dosen.php?id=$data_minicex[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\">
        										<button type=\"button\" class=\"btn btn-warning btn-sm\" name=\"approve_$data_minicex[id]\" value=\"VIEW & APPROVE\">
            								<i class=\"fas fa-eye me-2\"></i> VIEW & APPROVE
        									</button>
      										</a>";
								} else {
									echo "<font style=\"color:darkgreen; font-weight:600;\">DISETUJUI</font><br>";
									echo "<span style=\" font-weight:600;\">per tanggal</span><br>";
									echo "<span style=\"color:darkblue; font-weight:600;\">$tanggal_approval</span>";
									echo "<br><br>
										<a href=\"view_form_minicex.php?id=$data_minicex[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\">
        										<button type=\"button\" class=\"btn btn-success btn-sm\" name=\"view_$data_minicex[id]\" value=\"VIEW\">
            								<i class=\"fas fa-eye me-2\"></i> VIEW
        									</button>
      										</a>";


									echo "<br><br>
									<a href=\"unapprove_minicex_dosen.php?id=$data_minicex[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\">
									<button type=\"button\" class=\"btn btn-danger btn-sm\" name=\"unapprove\".\"$data_minicex[id]\"  value=\"UNAPPROVE\">
									<i class=\"fas fa-circle-xmark me-2\"></i> UNAPPROVE
									</button></a>";
								}
								echo "</td>";
								echo "</tr>";
								$no++;
								if ($kelas == "ganjil") $kelas = "genap";
								else $kelas = "ganjil";
							}
						}
						echo "</table><br><br>";
						?>

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