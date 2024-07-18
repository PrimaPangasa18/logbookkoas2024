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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 1) {
				if ($_COOKIE['level'] == 1) {
					include "menu1.php";
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

			echo "<div class=\"text_header\">ROTASI KEPANITERAAN (STASE)</div>";

			echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI KELOMPOK KEPANITERAAN (STASE) - TAMBAHAN/PENGGANTI</font></h4><br>";
			echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">";

			if (empty($_POST[submit])) {
				$stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
			}
			?>
			<table border="0">
				<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px;width:300px;">
						<font style="font-size:1.0em">Kepaniteraan (Stase)</font>
					</td>
					<td style="padding:5px 5px 5px 5px">
						<?php
						echo "<select class=\"select_artwide\" name=\"stase\" id=\"stase\" required>";
						echo "<option value=\"\">< Pilihan Kepaniteraan (Stase) ></option>";
						while ($dat_stase = mysqli_fetch_array($stase)) {
							echo "<option value=\"$dat_stase[id]\">$dat_stase[kepaniteraan] - (Semester: $dat_stase[semester] | Periode: $dat_stase[hari_stase] hari)</option>";
						}
						echo "</select>";
						?>
					</td>
				</tr>

				<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px">
						<font style="font-size:1.0em">Rencana Tanggal Mulai (<i>yyyy-mm-dd</i>)</font>
					</td>
					<td style="padding:5px 5px 5px 5px">
						<?php
						echo "<input type=\"text\" id=\"input-tanggal\" class=\"select_art\" name=\"tgl_mulai\" required>";
						echo "<div id=\"tanggal\"></div>";
						echo "<div id=\"input_selesai\">";
						echo "<i>Edit Tanggal Selesai (yyyy-mm-dd):</i><p>";
						echo "<input type=\"text\" id=\"input-selesai\" class=\"select_art\" name=\"tgl_selesai\" placeholder=\"Kosongi jika tidak ada perubahan!\">";
						echo "</div>";
						?>
					</td>
				</tr>

				<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px">
						<font style="font-size:1.0em">Import Data Koas</font>
					</td>
					<td style="padding:5px 5px 5px 5px">
						<?php
						echo "<input type=\"file\" id=\"daftar_koas\" name=\"daftar_koas\" accept=\".csv\" required><br><br>";
						echo "<font style=\"font-size:0.75em\"><i>Import file dalam format <i>csv</i> (*.csv) dengan separator ( , ) atau ( ; ) => no - nim - nama</i></font>";
						?>
					</td>
				</tr>

				<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px">
						<font style="font-size:1.0em">Separator file csv:<br><i>koma --> ( , ) atau titik koma --> ( ; )</i></font>
					</td>
					<td style="padding:5px 5px 5px 5px">
						<?php
						echo "<select class=\"select_art\" id=\"separator\" name=\"separator\" required>";
						echo "<option value=\"\">< Pilihan Separator ></option>";
						echo "<option value=\",\">Koma --> ( , )</option>";
						echo "<option value=\";\">Titik Koma --> ( ; )</option>";
						echo "</select>";
						?>
					</td>
				</tr>
			</table><br><br>

			<?php
			echo "<input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\">";


			if (!empty($_POST[submit])) {
				$delete_dummy_mhsw = mysqli_query($con, "DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
				$file = $_FILES['daftar_koas']['tmp_name'];
				$handle = fopen($file, "r");
				$separator = $_POST[separator];
				$id = 0;
				while (($filesop = fgetcsv($handle, 1000, $separator)) !== false) {
					if ($id > 0 and $filesop[0] != "") {
						$nim = $filesop[1];
						$nama = $filesop[2];
						$insert_temp = mysqli_query($con, "INSERT INTO `daftar_koas_temp`
						(`id`, `nim`, `nama`,`username`)
						VALUES ('$id','$nim','$nama','$_COOKIE[user]')");
					}
					if ($filesop[0] != "") $id++;
				}

				echo "<table border=\"0\" style=\"width:75%\">";
				echo "<tr class=\"ganjil\">";
				echo "<td style=\"padding:5px 5px 5px 5px;width:40%;\"><font style=\"font-size:1.0em\">Kepaniteraan (Stase)</font></td>";
				$stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$_POST[stase]'"));
				echo "<td style=\"padding:5px 5px 5px 5px;width:60%;\">$stase[kepaniteraan] (Semester: $stase[semester] | Periode: $stase[hari_stase] hari)</td>";
				echo "</tr>";
				$tanggal_mulai = tanggal_indo($_POST[tgl_mulai]);
				$hari_tambah = $stase['hari_stase'] - 1;
				$tambah_hari = '+' . $hari_tambah . ' days';
				$tgl_selesai = date('Y-m-d', strtotime($tambah_hari, strtotime($_POST[tgl_mulai])));
				if (!empty($_POST['tgl_selesai'])) $tgl_selesai = $_POST['tgl_selesai'];
				$tanggal_selesai = tanggal_indo($tgl_selesai);
				echo "<tr class=\"ganjil\">";
				echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Tanggal Mulai Kepaniteraan (Stase)</font></td>";
				echo "<td style=\"padding:5px 5px 5px 5px\">$tanggal_mulai</td>";
				echo "</tr>";
				echo "<tr class=\"ganjil\">";
				echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Tanggal Selesai Kepaniteraan (Stase)</font></td>";
				echo "<td style=\"padding:5px 5px 5px 5px\">$tanggal_selesai</td>";
				echo "</tr>";
				echo "</table><br><br>";

				$daftar_mhsw = mysqli_query($con, "SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]' ORDER BY `nama`");
				echo "<h5>Daftar Mahasiswa Peserta Kepaniteraan (Stase)</h5>";
				echo "<table style=\"width:100%\" id=\"freeze\">";
				echo "<thead>";
				echo "<th style=\"width:7%\">No</th>";
				echo "<th style=\"width:13%\">NIM</th>";
				echo "<th style=\"width:55%\">Nama</th>";
				echo "<th style=\"width:25%\">Status</th>";
				echo "</thead>";

				$no = 1;
				$kelas = "ganjil";
				while ($data_mhsw = mysqli_fetch_array($daftar_mhsw)) {
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$data_mhsw[nim]</td>";
					echo "<td>$data_mhsw[nama]</td>";

					$stase_id = "stase_" . $_POST[stase];
					$jml_akun = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `admin` WHERE `username`='$data_mhsw[nim]'"));
					$jml_mhsw = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
					if ($jml_akun >= 1) {
						echo "<td align=center><font style=\"color:green\">Terdaftar</font></td>";
						if ($jml_mhsw >= 1) {
							$status_stase_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `status` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
							if ($status_stase_mhsw[status] == '0') {
								$update_stase = mysqli_query($con, "UPDATE `$stase_id`
								SET
								`rotasi`='9',`tgl_mulai`='$_POST[tgl_mulai]',`tgl_selesai`='$tgl_selesai',`status`='0'
								WHERE `nim`='$data_mhsw[nim]'");
							} else {
								if ($_POST[tgl_mulai] <= $tgl) {
									$update_stase = mysqli_query($con, "UPDATE `$stase_id`
								SET
								`rotasi`='9',`tgl_mulai`='$_POST[tgl_mulai]',`tgl_selesai`='$tgl_selesai',`status`='1'
								WHERE `nim`='$data_mhsw[nim]'");
								} else {
									$update_stase = mysqli_query($con, "UPDATE `$stase_id`
								SET
								`rotasi`='9',`tgl_mulai`='$_POST[tgl_mulai]',`tgl_selesai`='$tgl_selesai',`status`='0'
								WHERE `nim`='$data_mhsw[nim]'");
								}
							}
						} else {
							$insert_stase = mysqli_query($con, "INSERT INTO `$stase_id`
							( `nim`, `rotasi`,
								`tgl_mulai`, `tgl_selesai`, `status`)
							VALUES
							( '$data_mhsw[nim]','9',
								'$_POST[tgl_mulai]','$tgl_selesai','0')");
						}
					} else echo "<td align=center><font style=\"color:red\">Belum punya akun</font></td>";
					echo "</tr>";
					if ($kelas == "ganjil") $kelas = "genap";
					else $kelas = "ganjil";
					$no++;
				}

				$delete_dummy_mhsw = mysqli_query($con, "DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]' WHERE `username`='$_COOKIE[user]'");
			}

			echo "</form>";
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
	<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
	<!-- <script type="text/javascript">
		$(document).ready(function() {
			$('#input_selesai').hide();
			$('#input-tanggal').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-tanggal').change(function() {
				var tgl = $(this).val();
				var stase = $('#stase').val();
				$.ajax({
					type: 'POST',
					url: 'tanggal_view.php',
					data: {
						'tgl_mulai': tgl,
						'stase': stase
					},
					success: function(response) {
						$('#tanggal').html(response);
						$('#input_selesai').show();
					}
				});
			});
			$('#input-selesai').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$("#stase").select2({
				placeholder: "< Pilihan Kepaniteraan (Stase) >"
			});
			$("#separator").select2({
				placeholder: "< Pilihan Separator >",
				allowClear: true
			});
			$("#freeze").freezeHeader();



		});
	</script> -->
</body>

</HTML>