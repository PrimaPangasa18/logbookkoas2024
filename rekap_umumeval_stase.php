<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rekap Umum Evaluasi Stase Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">
	<script src="js/Chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



	<!-- Link Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
	<!-- Link CDN Icon -->
	<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<style type="text/css">
	#pie_container {
		width: 50%;
		margin: 15px auto;
	}

	canvas {
		width: 420px !important;
		/* Gunakan !important jika perlu untuk mengatasi pengaturan internal dari Chart.js */
		height: 420px !important;
	}
</style>

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
						<h3 class="fw-bold fs-4 mb-3">REKAP EVALUASI AKHIR STASE</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							REKAP EVALUASI AKHIR STASE - UMUM
						</h2>
						<br><br>
						<?php

						$id_stase = $_GET['stase'];
						$angkatan_filter = $_GET['angk'];
						$stase_id = "stase_" . $id_stase;
						$include_id = "include_" . $id_stase;
						$target_id = "target_" . $id_stase;
						$tgl_awal = $_GET['tglawal'];
						$tgl_akhir = $_GET['tglakhir'];

						$filterstase = "`stase`=" . "'$id_stase'";
						$filtertgl = " AND (`tanggal`>=" . "'$tgl_awal'" . " AND `tanggal`<=" . "'$tgl_akhir')";
						$filter = $filterstase . $filtertgl;

						$mhsw = mysqli_query($con, "SELECT `nim` FROM `$stase_id` WHERE `tgl_mulai`>='$tgl_awal' AND `tgl_selesai`<='$tgl_akhir' ORDER BY `nim`");
						$jml_mhsw = mysqli_num_rows($mhsw);
						$stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));

						//--------------------
						echo "<center>";
						echo "<table class=\"table table-bordered\" style=\"width:50%\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:30%\"><strong>Kepaniteraan (STASE)</strong></td>
						<td style=\"width:70%; font-weight:600; color:darkgreen\">: $stase[kepaniteraan]</td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td> <strong>Angkatan </strong></td>";
						if ($angkatan_filter == "all") echo "<td style=\"color:darkred; font-weight:600;\">: Semua Angkatan</td>";
						else echo "<td style=\"color:darkred; font-weight:600;\">: Angkatan $angkatan_filter</td>";
						echo "</tr>";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td> <strong>Jumlah Mahasiswa </strong></td>
						<td style=\"font-weight:600;\">: <span class=\"text-danger\">$jml_mhsw</span> Orang</td>";
						echo "</tr>";
						$tglawal = tanggal_indo($tgl_awal);
						$tglakhir = tanggal_indo($tgl_akhir);
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Periode Kegiatan</strong></td>
						<td style=\"font-weight:600;\">: <span style=\"color:darkblue\">$tglawal</span> s.d <span style=\"color:darkblue\">$tglakhir</span></td>";
						echo "</tr>";
						echo "</table><br><br>";

						//------------------

						$delete_dummy = mysqli_query($con, "DELETE FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'");

						$id = 1;
						while ($nim_mhsw = mysqli_fetch_array($mhsw)) {
							$data_evaluasi = mysqli_query($con, "SELECT * FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw[nim]' AND `stase`='$id_stase'");
							$jml_data_evaluasi = mysqli_num_rows($data_evaluasi);
							if ($jml_data_evaluasi > 0) {
								$data_dummy = mysqli_fetch_array($data_evaluasi);
								$insert_dummy = mysqli_query($con, "INSERT INTO `dummy_evaluasi_stase`
					( `id`, `nim`, `stase`, `tgl_isi`,
						`input_111`, `input_112`, `input_113`, `input_114`,
						`input_115`, `input_116`, `input_117`, `input_118`,
						`input_119`, `input_1110`, `input_1111`, `input_1112`,
						`input_1113`, `input_1114`, `refleksi`, `komentar`,
						`input_12`, `input_13`, `dosen1`, `tatap_muka1`,
						`input_21A1`, `input_21A2`, `input_21A3`, `input_21A4`,
						`input_21A5`, `input_21A6`, `input_21A7`, `input_21B1`,
						`input_21B2`, `input_21B3`, `input_21B4`, `input_21B5`,
						`input_21B6`, `input_21B7`, `input_21B8`, `input_21B9`,
						`input_21B10`, `input_21B11`, `input_21B12`, `input_21C1`,
						`input_21C2`, `input_21C3`, `komentar_dosen1`, `dosen2`,
						`tatap_muka2`, `input_22A1`, `input_22A2`, `input_22A3`,
						`input_22A4`, `input_22A5`, `input_22A6`, `input_22A7`,
						`input_22B1`, `input_22B2`, `input_22B3`, `input_22B4`,
						`input_22B5`, `input_22B6`, `input_22B7`, `input_22B8`,
						`input_22B9`, `input_22B10`, `input_22B11`, `input_22B12`,
						`input_22C1`, `input_22C2`, `input_22C3`, `komentar_dosen2`,
						`dosen3`, `tatap_muka3`, `input_23A1`, `input_23A2`,
						`input_23A3`, `input_23A4`, `input_23A5`, `input_23A6`,
						`input_23A7`, `input_23B1`, `input_23B2`, `input_23B3`,
						`input_23B4`, `input_23B5`, `input_23B6`, `input_23B7`,
						`input_23B8`, `input_23B9`, `input_23B10`, `input_23B11`,
						`input_23B12`, `input_23C1`, `input_23C2`, `input_23C3`,
						`komentar_dosen3`, `lokasi_luar`, `lama_luar`, `input_3A1`,
						`input_3A2`, `input_3A3`, `input_3A4`, `input_3A5`,
						`input_3A6`, `input_3A7`, `input_3A8`, `input_3A9`,
						`input_3A10`, `like_luar`, `unlike_luar`,`username`)
				VALUES
				( '$id', '$data_dummy[nim]', '$data_dummy[stase]', '$data_dummy[tgl_isi]',
					'$data_dummy[input_111]', '$data_dummy[input_112]', '$data_dummy[input_113]', '$data_dummy[input_114]',
					'$data_dummy[input_115]', '$data_dummy[input_116]', '$data_dummy[input_117]', '$data_dummy[input_118]',
					'$data_dummy[input_119]', '$data_dummy[input_1110]', '$data_dummy[input_1111]', '$data_dummy[input_1112]',
					'$data_dummy[input_1113]', '$data_dummy[input_1114]', '$data_dummy[refleksi]', '$data_dummy[komentar]',
					'$data_dummy[input_12]', '$data_dummy[input_13]', '$data_dummy[dosen1]', '$data_dummy[tatap_muka1]',
					'$data_dummy[input_21A1]', '$data_dummy[input_21A2]', '$data_dummy[input_21A3]', '$data_dummy[input_21A4]',
					'$data_dummy[input_21A5]', '$data_dummy[input_21A6]', '$data_dummy[input_21A7]', '$data_dummy[input_21B1]',
					'$data_dummy[input_21B2]', '$data_dummy[input_21B3]', '$data_dummy[input_21B4]', '$data_dummy[input_21B5]',
					'$data_dummy[input_21B6]', '$data_dummy[input_21B7]', '$data_dummy[input_21B8]', '$data_dummy[input_21B9]',
					'$data_dummy[input_21B10]', '$data_dummy[input_21B11]', '$data_dummy[input_21B12]', '$data_dummy[input_21C1]',
					'$data_dummy[input_21C2]', '$data_dummy[input_21C3]', '$data_dummy[komentar_dosen1]', '$data_dummy[dosen2]',
					'$data_dummy[tatap_muka2]', '$data_dummy[input_22A1]', '$data_dummy[input_22A2]', '$data_dummy[input_22A3]',
					'$data_dummy[input_22A4]', '$data_dummy[input_22A5]', '$data_dummy[input_22A6]', '$data_dummy[input_22A7]',
					'$data_dummy[input_22B1]', '$data_dummy[input_22B2]', '$data_dummy[input_22B3]', '$data_dummy[input_22B4]',
					'$data_dummy[input_22B5]', '$data_dummy[input_22B6]', '$data_dummy[input_22B7]', '$data_dummy[input_22B8]',
					'$data_dummy[input_22B9]', '$data_dummy[input_22B10]', '$data_dummy[input_22B11]', '$data_dummy[input_22B12]',
					'$data_dummy[input_22C1]', '$data_dummy[input_22C2]', '$data_dummy[input_22C3]', '$data_dummy[komentar_dosen2]',
					'$data_dummy[dosen3]', '$data_dummy[tatap_muka3]', '$data_dummy[input_23A1]', '$data_dummy[input_23A2]',
					'$data_dummy[input_23A3]', '$data_dummy[input_23A4]', '$data_dummy[input_23A5]', '$data_dummy[input_23A6]',
					'$data_dummy[input_23A7]', '$data_dummy[input_23B1]', '$data_dummy[input_23B2]', '$data_dummy[input_23B3]',
					'$data_dummy[input_23B4]', '$data_dummy[input_23B5]', '$data_dummy[input_23B6]', '$data_dummy[input_23B7]',
					'$data_dummy[input_23B8]', '$data_dummy[input_23B9]', '$data_dummy[input_23B10]', '$data_dummy[input_23B11]',
					'$data_dummy[input_23B12]', '$data_dummy[input_23C1]', '$data_dummy[input_23C2]', '$data_dummy[input_23C3]',
					'$data_dummy[komentar_dosen3]', '$data_dummy[lokasi_luar]', '$data_dummy[lama_luar]', '$data_dummy[input_3A1]',
					'$data_dummy[input_3A2]', '$data_dummy[input_3A3]', '$data_dummy[input_3A4]', '$data_dummy[input_3A5]',
					'$data_dummy[input_3A6]', '$data_dummy[input_3A7]', '$data_dummy[input_3A8]', '$data_dummy[input_3A9]',
					'$data_dummy[input_3A10]', '$data_dummy[like_luar]', '$data_dummy[unlike_luar]','$_COOKIE[user]')");
								$id++;
							}
						}

						echo "<table  class=\" table table-warning\"; style=\"width:60%; border\">";
						echo "<tr>";
						echo "<td align=center><img src=\"images/evaluasi_header.jpg\" style=\"width:100%;height:auto\"></td>";
						echo "</tr>";
						echo "<tr><td>&nbsp;</td></tr>";

						//1. Sistem Pembalajaran Dalam Satase
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700;\">1. SISTEM PEMBELAJARAN DALAM STASE</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700; color:red;\">Penilaian Materi Pembelajaran</font></td>";
						echo "</tr>";

						$eval_pemb = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Pembelajaran' ORDER BY `id`");
						$no = 1;
						while ($data_pemb = mysqli_fetch_array($eval_pemb)) {
							echo "<tr><td>";
							echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr><td><font style=\"font-size:0.9em; font-weight:600;\">$data_pemb[pertanyaan]</font><br>";
							$piechart = "piechart" . "$no";
							echo "<center>";
							echo "
			<div class=\"pie_container\" >
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
							echo "</center>";
						?>
							<script type="text/javascript">
								var ctx = document.getElementById("<?php echo $piechart; ?>").getContext("2d");
								var data = {
									labels: [
										<?php
										$input = "input_11" . $no;
										//Sangat Tidak Setuju
										$sts = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='1' AND `username`='$_COOKIE[user]'"));
										$sts_persen = number_format(($sts / $jml_mhsw) * 100, 2);
										$sts_label = "Sangat Tidak Setuju - $sts mhsw ($sts_persen%)";
										echo '"' . $sts_label . '",';
										//Tidak Setuju
										$ts = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='2' AND `username`='$_COOKIE[user]'"));
										$ts_persen = number_format(($ts / $jml_mhsw) * 100, 2);
										$ts_label = "Tidak Setuju - $ts mhsw ($ts_persen%)";
										echo '"' . $ts_label . '",';
										//Setuju
										$s = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='3' AND `username`='$_COOKIE[user]'"));
										$s_persen = number_format(($s / $jml_mhsw) * 100, 2);
										$s_label = "Setuju - $s mhsw ($s_persen%)";
										echo '"' . $s_label . '",';
										//Sangat Setuju
										$ss = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='4' AND `username`='$_COOKIE[user]'"));
										$ss_persen = number_format(($ss / $jml_mhsw) * 100, 2);
										$ss_label = "Sangat Setuju - $ss mhsw ($ss_persen%)";
										echo '"' . $ss_label . '",';
										//Belum Mengisi
										$bm = $jml_mhsw - ($sts + $ts + $s + $ss);
										$bm_persen = number_format(($bm / $jml_mhsw) * 100, 2);
										$bm_label = "Belum Mengisi - $bm mhsw ($bm_persen%)";
										echo '"' . $bm_label . '",';
										?>
									],
									datasets: [{
										label: "",
										data: [
											<?php
											echo '"' . $sts . '",';
											echo '"' . $ts . '",';
											echo '"' . $s . '",';
											echo '"' . $ss . '",';
											echo '"' . $bm . '",';
											?>
										],
										backgroundColor: [
											"rgba(255, 0, 0, 1)",
											"rgba(255, 153, 102, 1)",
											"rgba(200, 255, 0, 1)",
											"rgba(0, 255, 0, 1)",
											"rgba(224, 224, 235, 1)"
										]
									}]
								};

								var myBarChart = new Chart(ctx, {
									type: 'pie',
									data: data,
									options: {
										responsive: true,
										plugins: {
											legend: {
												labels: {
													font: {
														weight: '600'
													}
												}
											}
										}
									}
								});
							</script>
						<?php
							echo "</td></tr></table></td></tr>";
							$no++;
						}

						//Refleksi diri
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700;\">Refleksi Diri</font></td>";
						echo "</tr>";
						echo "<tr><td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:600;\">Tuliskan refleksi diri anda setelah menjalankan stase kepaniteraan ini: </font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:600;\">
    							Klik tombol untuk melihat rangkuman isian refleksi diri 
    							<a href=\"rekap_evaluasi_refleksi_diri.php?stase=$id_stase&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\" target=\"_blank\">
        						<button class=\"btn btn-primary btn-sm\" >
            					<i class=\"fa-solid fa-eye me-2\"></i> Klik
        						</button>
    							</a></font></td>";


						echo "</tr></table>";
						echo "</td></tr>";

						//Komentar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em;  font-weight:700;\">Kritik dan Saran</font></td>";
						echo "</tr>";
						echo "<tr><td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em;  font-weight:600;\">Silakan tulis dalam kolom di bawah ini komentar, usul, saran, atau kritik dalam bahasa yang santun: </font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:600;\"> 
						Klik untuk melihat rangkuman isian komentar, usul, saran, atau kritik 
       					 <a href=\"rekap_evaluasi_saran.php?stase=$id_stase&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\" target=\"_blank\">
           					 <button class=\"btn btn-primary btn-sm\">
                			<i class=\"fa-solid fa-eye me-2\"></i> Klik
            				</button>
        					</a>
    						</font></td>";
						echo "</tr></table>";
						echo "</td></tr>";

						//Pencapaian
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em;  font-weight:700;\">Pencapaian</></font></td>";
						echo "</tr>";
						echo "<tr><td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td colspan=2><font style=\"font-size:0.9em; font-weight:600;\">Menurut Anda, seberapa banyak pencapaian kompetensi level 3A, 3B, 4A yang Anda capai dalam kepaniteraan Bagian ini (termasuk dengan stase luar kepaniteraan ini)?<br>";

						$piechart = "piechart" . "$no";
						echo "<center>";
						echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
						echo "</center>";
						?>
						<script type="text/javascript">
							var ctx = document.getElementById("<?php echo $piechart; ?>").getContext("2d");
							var data = {
								labels: [
									<?php
									$input = "input_12";
									//< 25%
									$kurang25 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='1' AND `username`='$_COOKIE[user]'"));
									$kurang25_persen = number_format(($kurang25 / $jml_mhsw) * 100, 2);
									$kurang25_label = "< 25% - $kurang25 mhsw ($kurang25_persen%)";
									echo '"' . $kurang25_label . '",';
									//25-50%
									$antara25sd50 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='2' AND `username`='$_COOKIE[user]'"));
									$antara25sd50_persen = number_format(($antara25sd50 / $jml_mhsw) * 100, 2);
									$antara25sd50_label = "25-50% - $antara25sd50 mhsw ($antara25sd50_persen%)";
									echo '"' . $antara25sd50_label . '",';
									//50-75%
									$antara50sd75 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='3' AND `username`='$_COOKIE[user]'"));
									$antara50sd75_persen = number_format(($antara50sd75 / $jml_mhsw) * 100, 2);
									$antara50sd75_label = "50-75% - $antara50sd75 mhsw ($antara50sd75_persen%)";
									echo '"' . $antara50sd75_label . '",';
									//> 75%
									$lebih75 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='4' AND `username`='$_COOKIE[user]'"));
									$lebih75_persen = number_format(($lebih75 / $jml_mhsw) * 100, 2);
									$lebih75_label = "> 75% - $lebih75 mhsw ($lebih75_persen%)";
									echo '"' . $lebih75_label . '",';
									//Belum Mengisi
									$bm = $jml_mhsw - ($kurang25 + $antara25sd50 + $antara50sd75 + $lebih75);
									$bm_persen = number_format(($bm / $jml_mhsw) * 100, 2);
									$bm_label = "Belum Mengisi - $bm mhsw ($bm_persen%)";
									echo '"' . $bm_label . '",';
									?>
								],
								datasets: [{
									label: "",
									data: [
										<?php
										echo '"' . $kurang25 . '",';
										echo '"' . $antara25sd50 . '",';
										echo '"' . $antara50sd75 . '",';
										echo '"' . $lebih75 . '",';
										echo '"' . $bm . '",';
										?>
									],
									backgroundColor: [
										"rgba(255, 0, 0, 1)",
										"rgba(255, 153, 102, 1)",
										"rgba(200, 255, 0, 1)",
										"rgba(0, 255, 0, 1)",
										"rgba(224, 224, 235, 1)"
									]
								}]
							};

							var myBarChart = new Chart(ctx, {
								type: 'pie',
								data: data,
								options: {
									responsive: true,
									plugins: {
										legend: {
											labels: {
												font: {
													weight: '600'
												}
											}
										}
									}
								}
							});
						</script>
						<?php
						$no++;
						echo "</td></tr></table>";
						echo "</td></tr>";

						//Kepuasan
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em;  font-weight:700;\">Kepuasan</font></td>";
						echo "</tr>";
						echo "<tr><td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td colspan=2><font style=\"font-size:0.9em; font-weight:600;\">Seberapa besar kepuasan Anda terhadap keseluruhan program stase di Bagian ini (termasuk program stase luar)?<br>";

						$piechart = "piechart" . "$no";
						echo "<center>";
						echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
						echo "</center>";
						?>
						<script type="text/javascript">
							var ctx = document.getElementById("<?php echo $piechart; ?>").getContext("2d");
							var data = {
								labels: [
									<?php
									$input = "input_13";
									//Sangat Tidak Puas
									$sts = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='1' AND `username`='$_COOKIE[user]'"));
									$sts_persen = number_format(($sts / $jml_mhsw) * 100, 2);
									$sts_label = "Sangat Tidak Puas - $sts mhsw ($sts_persen%)";
									echo '"' . $sts_label . '",';
									//Tidak Setuju
									$ts = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='2' AND `username`='$_COOKIE[user]'"));
									$ts_persen = number_format(($ts / $jml_mhsw) * 100, 2);
									$ts_label = "Tidak Puas - $ts mhsw ($ts_persen%)";
									echo '"' . $ts_label . '",';
									//Setuju
									$s = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='3' AND `username`='$_COOKIE[user]'"));
									$s_persen = number_format(($s / $jml_mhsw) * 100, 2);
									$s_label = "Puas - $s mhsw ($s_persen%)";
									echo '"' . $s_label . '",';
									//Sangat Setuju
									$ss = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='4' AND `username`='$_COOKIE[user]'"));
									$ss_persen = number_format(($ss / $jml_mhsw) * 100, 2);
									$ss_label = "Sangat Puas - $ss mhsw ($ss_persen%)";
									echo '"' . $ss_label . '",';
									//Belum Mengisi
									$bm = $jml_mhsw - ($sts + $ts + $s + $ss);
									$bm_persen = number_format(($bm / $jml_mhsw) * 100, 2);
									$bm_label = "Belum Mengisi - $bm mhsw ($bm_persen%)";
									echo '"' . $bm_label . '",';
									?>
								],
								datasets: [{
									label: "",
									data: [
										<?php
										echo '"' . $sts . '",';
										echo '"' . $ts . '",';
										echo '"' . $s . '",';
										echo '"' . $ss . '",';
										echo '"' . $bm . '",';
										?>
									],
									backgroundColor: [
										"rgba(255, 0, 0, 1)",
										"rgba(255, 153, 102, 1)",
										"rgba(200, 255, 0, 1)",
										"rgba(0, 255, 0, 1)",
										"rgba(224, 224, 235, 1)"
									]
								}]
							};

							var myBarChart = new Chart(ctx, {
								type: 'pie',
								data: data,
								options: {
									responsive: true,
									plugins: {
										legend: {
											labels: {
												font: {
													weight: '600'
												}
											}
										}
									}
								}
							});
						</script>
						<?php
						$no++;
						echo "</td></tr></table>";
						echo "</td></tr>";
						echo "<tr><td>&nbsp;</td></tr>";

						//2. Dosen
						echo "<tr>";
						echo "<td><font style=\"font-size:0.85em; font-weight: 700;\">2. DOSEN<br>";
						echo "Berikan evaluasi Anda bagi minimal <span style=\"color:red;\" > 3 (tiga)  dosen </span> dalam kepaniteraan ini yang melakukan minimal <span style=\"color:red;\" > 2 (dua) kali </span> tatap muka dengan Anda. Bila tidak ada, pilih 3 dosen dengan kuantitas dan/atau kualitas pertemuan paling banyak.";
						echo "<br>Aspek yang tidak bisa dinilai <span style=\"color:red;\" > (misalnya, karena mahasiswa tidak tahu pasti)  </span>  tidak perlu dinilai <span style=\"color:red;\" > (kolom dikosongkan)</span>.</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight: 600;\">Rekap dosen diakumulasi dari isian <span style=\"color:green;\" >Dosen 1 </span>, <span style=\"color:blue;\" >Dosen 2 </span>, dan <span style=\"color:purple;\" >Dosen 3: </span></font></td>";
						echo "</tr>";
						echo "<tr><td>";

						//Rata-rata jam tatap muka
						$jml_jam_dosen1 = mysqli_fetch_array(mysqli_query($con, "SELECT sum(`tatap_muka1`) FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'"));
						$jml_jam_dosen2 = mysqli_fetch_array(mysqli_query($con, "SELECT sum(`tatap_muka2`) FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'"));
						$jml_jam_dosen3 = mysqli_fetch_array(mysqli_query($con, "SELECT sum(`tatap_muka3`) FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'"));
						$jml_evaluasi_dosen1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `tatap_muka1`>0 AND `username`='$_COOKIE[user]'"));
						$jml_evaluasi_dosen2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `tatap_muka2`>0 AND `username`='$_COOKIE[user]'"));
						$jml_evaluasi_dosen3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `tatap_muka3`>0 AND `username`='$_COOKIE[user]'"));
						$rata_jam = (($jml_jam_dosen1[0] / $jml_evaluasi_dosen1) + ($jml_jam_dosen2[0] / $jml_evaluasi_dosen2) + ($jml_jam_dosen3[0] / $jml_evaluasi_dosen3)) / 3;
						$jam = floor($rata_jam);
						$menit = number_format(($rata_jam - $jam) * 60, 0);
						echo "<tr><td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr><td><font style=\"font-size:0.9em; font-weight:600;\">Rata-rata jam tatap muka: <span style=\"color:red;\" >$jam</span> jam , <span style=\"color:red;\" >$menit</span> menit</font>";
						echo "</td></tr></table>";
						echo "</td></tr>";

						//A. Umum
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em;font-weight:700; color:red;\">A. Umum</font></td>";
						echo "</tr>";
						$eval_dosen = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_umum' ORDER BY `id`");
						$id = 1;
						while ($data_eval = mysqli_fetch_array($eval_dosen)) {
							echo "<tr><td>";
							echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr><td><font style=\"font-size:0.9em; font-weight:600;\">$data_eval[pertanyaan]</font><br>";
							$piechart = "piechart" . "$no";
							echo "<center>";
							echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
							echo "</center>";
						?>
							<script type="text/javascript">
								var ctx = document.getElementById("<?php echo $piechart; ?>").getContext("2d");
								var data = {
									labels: [
										<?php
										$input1 = "input_21A" . $id;
										$input2 = "input_22A" . $id;
										$input3 = "input_23A" . $id;
										//Sangat Tidak Setuju
										$sts1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='1'"));
										$sts2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='1'"));
										$sts3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='1'"));
										$sts = $sts1 + $sts2 + $sts3;
										$sts_persen = number_format(($sts / (3 * $jml_mhsw)) * 100, 2);
										$sts_label = "Sangat Tidak Setuju - $sts review ($sts_persen%)";
										echo '"' . $sts_label . '",';
										//Tidak Setuju
										$ts1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='2'"));
										$ts2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='2'"));
										$ts3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='2'"));
										$ts = $ts1 + $ts2 + $ts3;
										$ts_persen = number_format(($ts / (3 * $jml_mhsw)) * 100, 2);
										$ts_label = "Tidak Setuju - $ts review ($ts_persen%)";
										echo '"' . $ts_label . '",';
										//Setuju
										$s1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='3'"));
										$s2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='3'"));
										$s3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='3'"));
										$s = $s1 + $s2 + $s3;
										$s_persen = number_format(($s / (3 * $jml_mhsw)) * 100, 2);
										$s_label = "Setuju - $s review ($s_persen%)";
										echo '"' . $s_label . '",';
										//Sangat Setuju
										$ss1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='4'"));
										$ss2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='4'"));
										$ss3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='4'"));
										$ss = $ss1 + $ss2 + $ss3;
										$ss_persen = number_format(($ss / (3 * $jml_mhsw)) * 100, 2);
										$ss_label = "Sangat Setuju - $ss review ($ss_persen%)";
										echo '"' . $ss_label . '",';
										//Belum Mengisi
										$bm = (3 * $jml_mhsw) - ($sts + $ts + $s + $ss);
										$bm_persen = number_format(($bm / (3 * $jml_mhsw)) * 100, 2);
										$bm_label = "Belum Mengisi - $bm review ($bm_persen%)";
										echo '"' . $bm_label . '",';
										?>
									],
									datasets: [{
										label: "",
										data: [
											<?php
											echo '"' . $sts . '",';
											echo '"' . $ts . '",';
											echo '"' . $s . '",';
											echo '"' . $ss . '",';
											echo '"' . $bm . '",';
											?>
										],
										backgroundColor: [
											"rgba(255, 0, 0, 1)",
											"rgba(255, 153, 102, 1)",
											"rgba(200, 255, 0, 1)",
											"rgba(0, 255, 0, 1)",
											"rgba(224, 224, 235, 1)"
										]
									}]
								};

								var myBarChart = new Chart(ctx, {
									type: 'pie',
									data: data,
									options: {
										responsive: true,
										plugins: {
											legend: {
												labels: {
													font: {
														weight: '600'
													}
												}
											}
										}
									}
								});
							</script>
						<?php
							echo "</td></tr></table></td></tr>";
							$no++;
							$id++;
						}

						//B. Dosen sebagai pengajar
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700; color:red;\">B. Dosen sebagai pengajar</font></td>";
						echo "</tr>";
						$eval_dosen = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_pengajar' ORDER BY `id`");
						$id = 1;
						while ($data_eval = mysqli_fetch_array($eval_dosen)) {
							echo "<tr><td>";
							echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr><td><font style=\"font-size:0.9em; font-weight:600;\">$data_eval[pertanyaan]</font><br>";
							$piechart = "piechart" . "$no";
							echo "<center>";
							echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
							echo "</center>";
						?>
							<script type="text/javascript">
								var ctx = document.getElementById("<?php echo $piechart; ?>").getContext("2d");
								var data = {
									labels: [
										<?php
										$input1 = "input_21B" . $id;
										$input2 = "input_22B" . $id;
										$input3 = "input_23B" . $id;
										//Sangat Tidak Setuju
										$sts1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='1'"));
										$sts2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='1'"));
										$sts3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='1'"));
										$sts = $sts1 + $sts2 + $sts3;
										$sts_persen = number_format(($sts / (3 * $jml_mhsw)) * 100, 2);
										$sts_label = "Sangat Tidak Setuju - $sts review ($sts_persen%)";
										echo '"' . $sts_label . '",';
										//Tidak Setuju
										$ts1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='2'"));
										$ts2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='2'"));
										$ts3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='2'"));
										$ts = $ts1 + $ts2 + $ts3;
										$ts_persen = number_format(($ts / (3 * $jml_mhsw)) * 100, 2);
										$ts_label = "Tidak Setuju - $ts review ($ts_persen%)";
										echo '"' . $ts_label . '",';
										//Setuju
										$s1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='3'"));
										$s2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='3'"));
										$s3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='3'"));
										$s = $s1 + $s2 + $s3;
										$s_persen = number_format(($s / (3 * $jml_mhsw)) * 100, 2);
										$s_label = "Setuju - $s review ($s_persen%)";
										echo '"' . $s_label . '",';
										//Sangat Setuju
										$ss1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='4'"));
										$ss2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='4'"));
										$ss3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='4'"));
										$ss = $ss1 + $ss2 + $ss3;
										$ss_persen = number_format(($ss / (3 * $jml_mhsw)) * 100, 2);
										$ss_label = "Sangat Setuju - $ss review ($ss_persen%)";
										echo '"' . $ss_label . '",';
										//Belum Mengisi
										$bm = (3 * $jml_mhsw) - ($sts + $ts + $s + $ss);
										$bm_persen = number_format(($bm / (3 * $jml_mhsw)) * 100, 2);
										$bm_label = "Belum Mengisi - $bm review ($bm_persen%)";
										echo '"' . $bm_label . '",';
										?>
									],
									datasets: [{
										label: "",
										data: [
											<?php
											echo '"' . $sts . '",';
											echo '"' . $ts . '",';
											echo '"' . $s . '",';
											echo '"' . $ss . '",';
											echo '"' . $bm . '",';
											?>
										],
										backgroundColor: [
											"rgba(255, 0, 0, 1)",
											"rgba(255, 153, 102, 1)",
											"rgba(200, 255, 0, 1)",
											"rgba(0, 255, 0, 1)",
											"rgba(224, 224, 235, 1)"
										]
									}]
								};

								var myBarChart = new Chart(ctx, {
									type: 'pie',
									data: data,
									options: {
										responsive: true,
										plugins: {
											legend: {
												labels: {
													font: {
														weight: '600'
													}
												}
											}
										}
									}
								});
							</script>
						<?php
							echo "</td></tr></table></td></tr>";
							$no++;
							$id++;
						}

						//C. Dosen sebagai penguji
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700; color:red;\">C. Dosen sebagai penguji</font></td>";
						echo "</tr>";
						$eval_dosen = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_penguji' ORDER BY `id`");
						$id = 1;
						while ($data_eval = mysqli_fetch_array($eval_dosen)) {
							echo "<tr><td>";
							echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr><td><font style=\"font-size:0.9em; font-weight:600;\">$data_eval[pertanyaan]</font><br>";
							$piechart = "piechart" . "$no";
							echo "<center>";
							echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
							echo "</center>";
						?>
							<script type="text/javascript">
								var ctx = document.getElementById("<?php echo $piechart; ?>").getContext("2d");
								var data = {
									labels: [
										<?php
										$input1 = "input_21C" . $id;
										$input2 = "input_22C" . $id;
										$input3 = "input_23C" . $id;
										//Sangat Tidak Setuju
										$sts1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='1'"));
										$sts2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='1'"));
										$sts3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='1'"));
										$sts = $sts1 + $sts2 + $sts3;
										$sts_persen = number_format(($sts / (3 * $jml_mhsw)) * 100, 2);
										$sts_label = "Sangat Tidak Setuju - $sts review ($sts_persen%)";
										echo '"' . $sts_label . '",';
										//Tidak Setuju
										$ts1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='2'"));
										$ts2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='2'"));
										$ts3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='2'"));
										$ts = $ts1 + $ts2 + $ts3;
										$ts_persen = number_format(($ts / (3 * $jml_mhsw)) * 100, 2);
										$ts_label = "Tidak Setuju - $ts review ($ts_persen%)";
										echo '"' . $ts_label . '",';
										//Setuju
										$s1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='3'"));
										$s2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='3'"));
										$s3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='3'"));
										$s = $s1 + $s2 + $s3;
										$s_persen = number_format(($s / (3 * $jml_mhsw)) * 100, 2);
										$s_label = "Setuju - $s review ($s_persen%)";
										echo '"' . $s_label . '",';
										//Sangat Setuju
										$ss1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='4'"));
										$ss2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='4'"));
										$ss3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='4'"));
										$ss = $ss1 + $ss2 + $ss3;
										$ss_persen = number_format(($ss / (3 * $jml_mhsw)) * 100, 2);
										$ss_label = "Sangat Setuju - $ss review ($ss_persen%)";
										echo '"' . $ss_label . '",';
										//Belum Mengisi
										$bm = (3 * $jml_mhsw) - ($sts + $ts + $s + $ss);
										$bm_persen = number_format(($bm / (3 * $jml_mhsw)) * 100, 2);
										$bm_label = "Belum Mengisi - $bm review ($bm_persen%)";
										echo '"' . $bm_label . '",';
										?>
									],
									datasets: [{
										label: "",
										data: [
											<?php
											echo '"' . $sts . '",';
											echo '"' . $ts . '",';
											echo '"' . $s . '",';
											echo '"' . $ss . '",';
											echo '"' . $bm . '",';
											?>
										],
										backgroundColor: [
											"rgba(255, 0, 0, 1)",
											"rgba(255, 153, 102, 1)",
											"rgba(200, 255, 0, 1)",
											"rgba(0, 255, 0, 1)",
											"rgba(224, 224, 235, 1)"
										]
									}]
								};

								var myBarChart = new Chart(ctx, {
									type: 'pie',
									data: data,
									options: {
										responsive: true,
										plugins: {
											legend: {
												labels: {
													font: {
														weight: '600'
													}
												}
											}
										}
									}
								});
							</script>
						<?php
							echo "</td></tr></table></td></tr>";
							$no++;
							$id++;
						}

						echo "<tr><td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>
						<td><font style=\"font-size:0.9em; font-weight:600;\">
            Untuk melihat komentar, usul, saran atau masukan untuk para dosen bisa klik tombol berikut:<br>
			Komentar, usul, saran atau masukan untuk dosen= 
            <a href=\"rekap_evaluasi_kritik_dosen.php?stase=$id_stase&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\" target=\"_blank\">
                <button class=\"btn btn-primary btn-sm\">
                    <i class=\"fa-solid fa-eye me-2\"></i> Klik
                </button>
            </a>
        </font>";
						echo "</td></tr>
						</table>";
						echo "</td></tr>";

						echo "<tr><td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr><td><font style=\"font-size:0.9em; font-weight:600;\">
       							 Untuk melihat rekap per dosen bisa klik tombol berikut:<br>
								 Klik untuk melihat rekap evaluasi per dosen =
        					<a href=\"rekap_evaluasi_dosen.php?stase=$id_stase&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\" target=\"_blank\">
            				<button class=\"btn btn-primary btn-sm\">
                				<i class=\"fa-solid fa-eye me-2\"></i> Klik
            				</button>
        					</a>
    						</font>";

						echo "</td></tr></table>";
						echo "</td></tr>";

						//3. Evaluasi Stase Luar
						echo "<tr><td>&nbsp;</td></tr>";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:700;\">3. EVALUASI STASE LUAR<br>";
						echo "Isilah kuesioner berikut apabila dalam stase yang Anda evaluasi ini ada stase luar.</font></td>";
						echo "</tr>";

						//A. Penilaian Materi Pembelajaran
						echo "<tr><td><font style=\"font-size:0.9em; font-weight:700; color:red;\">A. Penilaian Materi Pembelajaran</font></td></tr>";
						$eval_pemb = mysqli_query($con, "SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Stase_luar' ORDER BY `id`");
						$id = 1;
						while ($data_pemb = mysqli_fetch_array($eval_pemb)) {
							echo "<tr><td>";
							echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
							echo "<tr><td><font style=\"font-size:0.9em; font-weight:600;\">$data_pemb[pertanyaan]</font><br>";
							$piechart = "piechart" . "$no";
							echo "<center>";
							echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
							echo "</center>";
						?>
							<script type="text/javascript">
								var ctx = document.getElementById("<?php echo $piechart; ?>").getContext("2d");
								var data = {
									labels: [
										<?php
										$input = "input_3A" . $id;
										//Sangat Tidak Setuju
										$sts = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='1' AND `username`='$_COOKIE[user]'"));
										$sts_persen = number_format(($sts / $jml_mhsw) * 100, 2);
										$sts_label = "Sangat Tidak Setuju - $sts mhsw ($sts_persen%)";
										echo '"' . $sts_label . '",';
										//Tidak Setuju
										$ts = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='2' AND `username`='$_COOKIE[user]'"));
										$ts_persen = number_format(($ts / $jml_mhsw) * 100, 2);
										$ts_label = "Tidak Setuju - $ts mhsw ($ts_persen%)";
										echo '"' . $ts_label . '",';
										//Setuju
										$s = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='3' AND `username`='$_COOKIE[user]'"));
										$s_persen = number_format(($s / $jml_mhsw) * 100, 2);
										$s_label = "Setuju - $s mhsw ($s_persen%)";
										echo '"' . $s_label . '",';
										//Sangat Setuju
										$ss = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='4' AND `username`='$_COOKIE[user]'"));
										$ss_persen = number_format(($ss / $jml_mhsw) * 100, 2);
										$ss_label = "Sangat Setuju - $ss mhsw ($ss_persen%)";
										echo '"' . $ss_label . '",';
										//Belum Mengisi
										$bm = $jml_mhsw - ($sts + $ts + $s + $ss);
										$bm_persen = number_format(($bm / $jml_mhsw) * 100, 2);
										$bm_label = "Belum Mengisi - $bm mhsw ($bm_persen%)";
										echo '"' . $bm_label . '",';
										?>
									],
									datasets: [{
										label: "",
										data: [
											<?php
											echo '"' . $sts . '",';
											echo '"' . $ts . '",';
											echo '"' . $s . '",';
											echo '"' . $ss . '",';
											echo '"' . $bm . '",';
											?>
										],
										backgroundColor: [
											"rgba(255, 0, 0, 1)",
											"rgba(255, 153, 102, 1)",
											"rgba(200, 255, 0, 1)",
											"rgba(0, 255, 0, 1)",
											"rgba(224, 224, 235, 1)"
										]
									}]
								};

								var myBarChart = new Chart(ctx, {
									type: 'pie',
									data: data,
									options: {
										responsive: true,
										plugins: {
											legend: {
												labels: {
													font: {
														weight: '600'
													}
												}
											}
										}
									}
								});
							</script>
						<?php
							echo "</td></tr></table></td></tr>";
							$no++;
							$id++;
						}

						echo "<tr><td>";
						echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:600;\">Rekap evaluasi stase luar diberikan untuk tiap lokasi yang dievaluasi mahasiswa.</font></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><font style=\"font-size:0.9em; font-weight:600;\">
							Klik tombol untuk melihat rangkuman evaluasi stase luar
        					<a href=\"rekap_evaluasi_stase_luar.php?stase=$id_stase&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\" target=\"_blank\">		
            				<button class=\"btn btn-primary btn-sm\">
                			<i class=\"fa-solid fa-eye me-2\"></i> Klik
            				</button>
       						 </a>
    						</font></td>";
						echo "</tr></table>";
						echo "</td></tr>";

						echo "</table>";

						$delete_dummy = mysqli_query($con, "DELETE FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'");

						?>
					</div>
				</div>
			</main>


			<!-- End Content -->
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

	<script src="javascript/script1.js"></script>
	<script src="javascript/buttontopup.js"></script>
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze").freezeHeader();
			$("#freeze1").freezeHeader();
		});
	</script>
</body>

</HTML>