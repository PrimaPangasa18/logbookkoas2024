<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rekap Umum Stase Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
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
						<h3 class="fw-bold fs-4 mb-3">REKAP UMUM LOGBOOK</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							REKAP UMUM LOGBOOK KEPANITERAAN (STASE)
						</h2>
						<br><br>
						<?php

						$grup_filter = $_GET['grup'];
						$stase_filter = $_GET['stase'];
						$angkatan_filter = $_GET['angk'];
						$tgl_awal = $_GET['tglawal'];
						$tgl_akhir = $_GET['tglakhir'];
						$stase_id = "stase_" . $stase_filter;
						$target_id = "target_" . $stase_filter;
						$include_id = "include_" . $stase_filter;

						$filterstase = "`stase`=" . "'$stase_filter'";
						$filtertgl = " AND (`tanggal`>=" . "'$tgl_awal'" . " AND `tanggal`<=" . "'$tgl_akhir')";

						$hari_kurang = 28 - 1;
						$kurang_hari = '-' . $hari_kurang . ' days';
						$tgl_batas = date('Y-m-d', strtotime($kurang_hari, strtotime($tgl_akhir)));

						if ($grup_filter == "1") $filtergrup = "`tgl_mulai`<=" . "'$tgl_batas'";
						if ($grup_filter == "2") $filtergrup = "`tgl_mulai`>" . "'$tgl_batas'" . " AND `tgl_mulai`<=" . "'$tgl_akhir'";
						if ($grup_filter == "all") $filtergrup = "`tgl_mulai`<=" . "'$tgl_akhir'";

						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$stase_filter'"));
						$kepaniteraan = $data_stase['kepaniteraan'];
						$filter = $filterstase . $filtertgl;

						$mhsw = mysqli_query($con, "SELECT `nim` FROM `$stase_id` WHERE $filtergrup AND (`tgl_mulai`>='$tgl_awal' AND `tgl_selesai`<='$tgl_akhir')");
						$i = 1;
						$delete_dummy_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$_COOKIE[user]'");
						$delete_dummy_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$_COOKIE[user]'");
						while ($data_mhsw = mysqli_fetch_array($mhsw)) {
							//cek Angkatan
							$angkatan = mysqli_fetch_array(mysqli_query($con, "SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
							if ($angkatan_filter == $angkatan['angkatan'] or $angkatan_filter == "all") {
								$mhsw_nim[$i] = $data_mhsw['nim'];
								$filter_penyakit = "SELECT * FROM `jurnal_penyakit` WHERE `nim`='$data_mhsw[nim]' AND " . $filter . " AND `status`='1'";
								$jurnal_penyakit = mysqli_query($con, $filter_penyakit);
								while ($data = mysqli_fetch_array($jurnal_penyakit)) {
									$insert_dummy = mysqli_query($con, "INSERT INTO `jurnal_penyakit_dummy`
						(`id`, `nim`, `nama`,`angkatan`, `grup`,
							`hari`, `tanggal`, `stase`,
							`jam_awal`, `jam_akhir`, `kelas`,
							`lokasi`, `kegiatan`,
							`penyakit1`, `penyakit2`, `penyakit3`, `penyakit4`,
							`dosen`, `status`, `evaluasi`,`username`)
						VALUES
						('$data[id]', '$data[nim]', '','$data[angkatan]', '$data[grup]',
						  '$data[hari]', '$data[tanggal]', '$data[stase]',
						  '$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]',
						  '$data[lokasi]', '$data[kegiatan]',
						  '$data[penyakit1]', '$data[penyakit2]', '$data[penyakit3]', '$data[penyakit4]',
						  '$data[dosen]', '$data[status]', '$data[evaluasi]','$_COOKIE[user]')");
								}
								$filter_ketrampilan = "SELECT * FROM `jurnal_ketrampilan` WHERE `nim`='$data_mhsw[nim]' AND " . $filter . " AND `status`='1'";
								$jurnal_ketrampilan = mysqli_query($con, $filter_ketrampilan);
								while ($data = mysqli_fetch_array($jurnal_ketrampilan)) {
									$insert_dummy = mysqli_query($con, "INSERT INTO `jurnal_ketrampilan_dummy`
						(`id`, `nim`, `nama`,`angkatan`, `grup`,
							`hari`, `tanggal`, `stase`,
							`jam_awal`, `jam_akhir`, `kelas`,
							`lokasi`, `kegiatan`,
							`ketrampilan1`, `ketrampilan2`, `ketrampilan3`, `ketrampilan4`,
							`dosen`, `status`, `evaluasi`,`username`)
						VALUES
						('$data[id]', '$data[nim]', '','$data[angkatan]', '$data[grup]',
						  '$data[hari]', '$data[tanggal]', '$data[stase]',
						  '$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]',
						  '$data[lokasi]', '$data[kegiatan]',
						  '$data[ketrampilan1]', '$data[ketrampilan2]', '$data[ketrampilan3]', '$data[ketrampilan4]',
						  '$data[dosen]', '$data[status]', '$data[evaluasi]','$_COOKIE[user]')");
								}
								$i++;
							}
						}
						$jml_mhsw = $i - 1;


						//--------------------
						echo "<center>";
						echo "<table class=\"table table-bordered\" style=\"width:auto\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:300px;\"><strong>Kepaniteraan (STASE)</strong></td>
						<td style=\"width:400px; font-weight:600; color:darkgreen\">: $data_stase[kepaniteraan]</td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Angkatan</strong></td>";
						if ($angkatan_filter == "all") echo "<td style=\"color:darkred; font-weight:600;\">: Semua Angkatan</td>";
						else echo "<td style=\"color:darkred; font-weight:600;\">: Angkatan $angkatan_filter</td>";
						echo "</tr>";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Grup</strong></td>";
						if ($grup_filter == "all") echo "<td style=\"color:red; font-weight:600;\">: Semua</td>";
						else {
							if ($grup_filter == '1') echo "<td style=\"color:red; font-weight:600;\">: Senior</td>";
							else echo "<td style=\"color:red; font-weight:600;\">: Yunior</td>";
						}
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Jumlah Mahasiswa</strong></td>
						<td style=\"font-weight:600;\">: <span class=\"text-danger\">$jml_mhsw</span> Orang</td>";
						echo "</tr>";
						$tglawal = tanggal_indo($tgl_awal);
						$tglakhir = tanggal_indo($tgl_akhir);
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Periode Kegiatan</strong></td>
						<td style=\"font-weight:600;\">: <span style=\"color:darkblue\">$tglawal</span> s.d <span style=\"color:darkblue\">$tglakhir</span></td>";
						echo "</tr>";
						echo "</table><br><br>";
						//------------------
						echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk melihat rekap atau catatan</span>
								<br><br>
								<a href=\"#penyakit\" class=\"btn btn-success me-3\">Rekap Jurnal Penyakit</a>
								<a href=\"#ketrampilan\" class=\"btn btn-primary me-3\">Rekap Jurnal Ketrampilan Klinik</a>
								<a href=\"#catatan\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Catatan Rekap Jurnal</a>";
						echo "</center>";

						echo "<br><br>";
						echo "<a id=\"penyakit\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Rekap Jurnal Penyakit</a><br><br>";
						echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze\">";
						echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<tr>";
						echo "<th rowspan=2 style=\"width:5%;text-align:center;vertical-align:middle;font-weight:bold\">No</th>";
						echo "<th rowspan=2 style=\"width:37%;text-align:center;vertical-align:middle;font-weight:bold\">Penyakit</th>";
						echo "<th rowspan=2 style=\"width:18%;text-align:center;vertical-align:middle;font-weight:bold\">Level SKDI/Kepmenkes<br>/IPSG/Muatan Lokal</th>";
						echo "<th rowspan=2 style=\"width:10%;text-align:center;vertical-align:middle;font-weight:bold\">Target Minimum</th>";
						echo "<th colspan=6 style=\"width:30%;text-align:center;font-weight:bold\">Level/Kode Kegiatan</th>";
						echo "</tr>";
						echo "<tr>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">1</th>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">2</th>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">3</th>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">4A</th>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">MKM</th>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">U</th>";
						echo "</tr>";
						echo "</thead>";

						$daftar_penyakit = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1' ORDER BY `$target_id` DESC,`penyakit` ASC");
						$no = 1;
						$jml_ketuntasan = 0;
						$item = 0;
						$ketuntasan = 0;
						while ($data = mysqli_fetch_array($daftar_penyakit)) {
							echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td align=center  style=\"font-weight:600;\">$no</td>";
							echo "<td style=\"font-weight:600;\">$data[penyakit]</td>";
							echo "<td align=center style=\"font-weight:600;\">
    								<span style=\"color: darkblue;\">{$data['skdi_level']}</span> / 
    								<span style='color: darkred;'>{$data['k_level']}</span> / 
    								<span style='color: purple;'>{$data['ipsg_level']}</span> / 
    								<span style='color: brown;'>{$data['kml_level']}</span>
							</td>";
							echo "<td align=center style=\"font-weight:600;\">$data[$target_id]</td>";

							$tot_jml_1 = 0;
							$tot_jml_2 = 0;
							$tot_jml_3 = 0;
							$tot_jml_4A = 0;
							$tot_jml_MKM = 0;
							$tot_jml_U = 0;
							for ($i = 1; $i <= $jml_mhsw; $i++) {
								if ($data['skdi_level'] == "MKM" or $data['k_level'] == "MKM" or $data['ipsg_level'] == "MKM" or $data['kml_level'] == "MKM") {
									$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND `username`='$_COOKIE[user]'"));
									$jml_1 = 0;
									$jml_2 = 0;
									$jml_3 = 0;
									$jml_4A = 0;
									$jml_U = 0;
								} else {
									$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
							AND (`kegiatan`='1' OR `kegiatan`='2')
							AND `username`='$_COOKIE[user]'"));
									$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
							AND `username`='$_COOKIE[user]'"));
									$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
							AND `username`='$_COOKIE[user]'"));
									$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND `kegiatan`='10'
							AND `username`='$_COOKIE[user]'"));
									$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND (`kegiatan`='11' OR `kegiatan`='12')
							AND `username`='$_COOKIE[user]'"));
									$jml_MKM = 0;
								}
								$tot_jml_1 = $tot_jml_1 + $jml_1;
								$tot_jml_2 = $tot_jml_2 + $jml_2;
								$tot_jml_3 = $tot_jml_3 + $jml_3;
								$tot_jml_4A = $tot_jml_4A + $jml_4A;
								$tot_jml_MKM = $tot_jml_MKM + $jml_MKM;
								$tot_jml_U = $tot_jml_U + $jml_U;
							}
							$jml_1 = $tot_jml_1 / $jml_mhsw;
							$jml_2 = $tot_jml_2 / $jml_mhsw;
							$jml_3 = $tot_jml_3 / $jml_mhsw;
							$jml_4A = $tot_jml_4A / $jml_mhsw;
							$jml_MKM = $tot_jml_MKM / $jml_mhsw;
							$jml_U = $tot_jml_U / $jml_mhsw;

							$jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
							$jml_1 = number_format($jml_1, 2);
							$jml_2 = number_format($jml_2, 2);;
							$jml_3 = number_format($jml_3, 2);;
							$jml_4A = number_format($jml_4A, 2);;
							$jml_MKM = number_format($jml_MKM, 2);;
							$jml_U = number_format($jml_U, 2);;

							//Kasus tidak ada target
							if ($data[$target_id] < 1) {
								if ($jumlah_total > 0) {
									//Blok warna hijau tua
									$ketuntasan = 100;
									$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
									$item++;
									blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
								} else {
									echo "<td style=\"width:5%;font-weight:600;\">&nbsp;</td>";
									echo "<td style=\"width:5%; font-weight:600;\">&nbsp;</td>";
									echo "<td style=\"width:5%;font-weight:600;\">&nbsp;</td>";
									echo "<td style=\"width:5%;font-weight:600;\">&nbsp;</td>";
									echo "<td style=\"width:5%;font-weight:600;\">&nbsp;</td>";
									echo "<td style=\"width:5%;font-weight:600;\">&nbsp;</td>";
								}
							} else
							//Kasus ada target
							{
								$blocked = 0;
								//Start - Pewarnaan Capaian Level 4A
								if (($data['skdi_level'] == "4A" or $data['k_level'] == "4A" or $data['ipsg_level'] == "4A" or $data['kml_level'] == "4A") and $blocked == 0) {
									$batas_target = $data[$target_id] / 2;
									$blocked = 1;
									if ($jml_4A > $batas_target) {
										//Blok warna hijau tua
										$ketuntasan = 100;
										$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
										$item++;
										blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
									} else {
										if ($jml_4A <= $batas_target and $jml_4A >= 1)
										//Blok warna hijau muda
										{
											$ketuntasan = 75;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										}
										if ($jml_4A < 1) {
											if ($jml_3 >= $batas_target)
											//Blok warna hijau muda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											} else {
												if ($jumlah_total >= 1)
												//Blok warna kuning
												{
													$ketuntasan = 50;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
													blok_warna("kuning", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
												} else
												//Blok warna merah
												{
													$ketuntasan = 0;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
													blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
												}
											}
										}
									}
								}
								//End - Pewarnaan Capaian Level 4A

								//Start - Pewarnaan Capaian Level 3A dan 3B
								if (($data['skdi_level'] == "3" or $data['k_level'] == "3" or $data['ipsg_level'] == "3" or $data['kml_level'] == "3"
										or $data['skdi_level'] == "3A" or $data['k_level'] == "3A" or $data['ipsg_level'] == "3A" or $data['kml_level'] == "3A"
										or $data['skdi_level'] == "3B" or $data['k_level'] == "3B" or $data['ipsg_level'] == "3B" or $data['kml_level'] == "3B")
									and $blocked == 0
								) {
									$batas_target = $data[$target_id] / 2;
									$blocked = 1;
									if ($jml_3 > $batas_target or $jml_4A > $batas_target) {
										//Blok warna hijau tua
										$ketuntasan = 100;
										$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
										$item++;
										blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
									} else {
										if (($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1))
										//Blok warna hijau muda
										{
											$ketuntasan = 75;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										} else {
											if ($jumlah_total >= 1)
											//Blok warna kuning
											{
												$ketuntasan = 50;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("kuning", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											} else
											//Blok warna merah
											{
												$ketuntasan = 0;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											}
										}
									}
								}
								//End - Pewarnaan Capaian Level 3A dan 3B

								//Start - Pewarnaan Capaian Level 2
								if (($data['skdi_level'] == "2" or $data['k_level'] == "2" or $data['ipsg_level'] == "2" or $data['kml_level'] == "2") and $blocked == 0) {
									$batas_target = $data[$target_id] / 2;
									$blocked = 1;
									if ($jml_2 > $batas_target or $jml_3 > $batas_target or $jml_4A > $batas_target) {
										//Blok warna hijau tua
										$ketuntasan = 100;
										$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
										$item++;
										blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
									} else {
										if (($jml_2 <= $batas_target and $jml_2 >= 1) or ($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1))
										//Blok warna hijau muda
										{
											$ketuntasan = 75;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										} else {
											if ($jml_1 >= 1)
											//Blok warna kuning
											{
												$ketuntasan = 50;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("kuning", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											} else
											//Blok warna merah
											{
												$ketuntasan = 0;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											}
										}
									}
								}
								//End - Pewarnaan Capaian Level 2

								//Start - Pewarnaan Capaian Level 1
								if (($data['skdi_level'] == "1" or $data['k_level'] == "1" or $data['ipsg_level'] == "1" or $data['kml_level'] == "1") and $blocked == 0) {
									$batas_target = $data[$target_id] / 2;
									$blocked = 1;
									if ($jml_1 > $batas_target or $jml_2 > $batas_target or $jml_3 > $batas_target or $jml_4A > $batas_target) {
										//Blok warna hijau tua
										$ketuntasan = 100;
										$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
										$item++;
										blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
									} else {
										if ($jumlah_total >= 1)
										//Blok warna hijau muda
										{
											$ketuntasan = 75;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										} else {
											//Blok warna merah
											$ketuntasan = 0;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										}
									}
								}
								//End - Pewarnaan Capaian Level 1

								//Start - Pewarnaan Capaian MKM
								if (($data['skdi_level'] == "MKM" or $data['k_level'] == "MKM" or $data['ipsg_level'] == "MKM" or $data['kml_level'] == "MKM") and $blocked == 0) {
									$batas_target = $data[$target_id] / 2;
									$blocked = 1;
									if ($jml_MKM > $batas_target) {
										//Blok warna hijau tua
										$ketuntasan = 100;
										$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
										$item++;
										blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
									} else {
										if ($jml_MKM <= $batas_target and $jml_MKM >= 1)
										//Blok warna hijaumuda
										{
											$ketuntasan = 75;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										} else {
											//Blok warna kuning
											if ($jml_1 >= 1 or $jml_2 >= 1 or $jml_3 >= 1 or $jml_4A >= 1 or $jml_U >= 1) {
												$ketuntasan = 50;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("kuning", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											} else
											//Blok warna merah
											{
												$ketuntasan = 0;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											}
										}
									}
								}
								//End - Pewarnaan Capaian MKM
							}
							echo "</tr>";
							$no++;
						}
						$ketuntasan_rata = $jml_ketuntasan / $item;
						if ($ketuntasan_rata <= 100 and $ketuntasan_rata >= 80) {
							$grade = "A";
							$color = "green";
						}
						if ($ketuntasan_rata < 80 and $ketuntasan_rata >= 70) {
							$grade = "B";
							$color = "blue";
						}
						if ($ketuntasan_rata < 70 and $ketuntasan_rata >= 60) {
							$grade = "C";
							$color = "orange";
						}
						if ($ketuntasan_rata < 60 and $ketuntasan_rata >= 50) {
							$grade = "D";
							$color = "darkred";
						}
						if ($ketuntasan_rata < 50) {
							$grade = "E";
							$color = "red";
						}
						$ketuntasan_rata = number_format($ketuntasan_rata, 2);
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=10 style=\"font-weight:600;\">Ketuntasan: $ketuntasan_rata %<br>";
						echo "Grade: <span style='color: $color;'>$grade</span></td>";
						echo "</tr>";
						echo "</table><br><br>";

						// Keterampilan
						echo "<a id=\"ketrampilan\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Rekap Jurnal Ketrampilan Klinik</a><br><br>";
						echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1\">";
						echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<tr>";
						echo "<th rowspan=2 style=\"width:5%;text-align:center;vertical-align:middle;font-weight:bold\">No</th>";
						echo "<th rowspan=2 style=\"width:37%;text-align:center;vertical-align:middle;font-weight:bold\">Ketrampilan</th>";
						echo "<th rowspan=2 style=\"width:18%;text-align:center;vertical-align:middle;font-weight:bold\">Level SKDI/Kepmenkes<br>/IPSG/Muatan Lokal</th>";
						echo "<th rowspan=2 style=\"width:10%;text-align:center;vertical-align:middle;font-weight:bold\">Target Minimum</th>";
						echo "<th colspan=6 style=\"width:30%;text-align:center;font-weight:bold\">Level/Kode Kegiatan</th>";
						echo "</tr>";
						echo "<tr>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">1</th>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">2</th>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">3</th>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">4A</th>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">MKM</th>";
						echo "<th style=\"width:5%;text-align:center;font-weight:bold\">U</th>";
						echo "</tr>";
						echo "</thead>";

						$daftar_ketrampilan = mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1' ORDER BY `$target_id` DESC,`ketrampilan` ASC");
						$no = 1;
						while ($data = mysqli_fetch_array($daftar_ketrampilan)) {
							echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
							echo "<td style=\"font-weight:600;\">$data[ketrampilan]</td>";
							echo "<td align=center style=\"font-weight:600;\">
    								<span style=\"color: darkblue;\">{$data['skdi_level']}</span> / 
    								<span style='color: darkred;'>{$data['k_level']}</span> / 
    								<span style='color: purple;'>{$data['ipsg_level']}</span> / 
    								<span style='color: brown;'>{$data['kml_level']}</span>
							</td>";
							echo "<td style=\"text-align:center; font-weight:600;\">$data[$target_id]</td>";
							$tot_jml_1 = 0;
							$tot_jml_2 = 0;
							$tot_jml_3 = 0;
							$tot_jml_4A = 0;
							$tot_jml_MKM = 0;
							$tot_jml_U = 0;
							for ($i = 1; $i <= $jml_mhsw; $i++) {
								if ($data['skdi_level'] == "MKM" or $data['k_level'] == "MKM" or $data['ipsg_level'] == "MKM" or $data['kml_level'] == "MKM") {
									$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND `username`='$_COOKIE[user]'"));
									$jml_1 = 0;
									$jml_2 = 0;
									$jml_3 = 0;
									$jml_4A = 0;
									$jml_U = 0;
								} else {
									$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='1' OR `kegiatan`='2')
		          AND `username`='$_COOKIE[user]'"));
									$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
		          AND `username`='$_COOKIE[user]'"));
									$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
		          AND `username`='$_COOKIE[user]'"));
									$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND `kegiatan`='10'
		          AND `username`='$_COOKIE[user]'"));
									$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='11' OR `kegiatan`='12')
		          AND `username`='$_COOKIE[user]'"));
									$jml_MKM = 0;
								}
								$tot_jml_1 = $tot_jml_1 + $jml_1;
								$tot_jml_2 = $tot_jml_2 + $jml_2;
								$tot_jml_3 = $tot_jml_3 + $jml_3;
								$tot_jml_4A = $tot_jml_4A + $jml_4A;
								$tot_jml_MKM = $tot_jml_MKM + $jml_MKM;
								$tot_jml_U = $tot_jml_U + $jml_U;
							}
							$jml_1 = $tot_jml_1 / $jml_mhsw;
							$jml_2 = $tot_jml_2 / $jml_mhsw;
							$jml_3 = $tot_jml_3 / $jml_mhsw;
							$jml_4A = $tot_jml_4A / $jml_mhsw;
							$jml_MKM = $tot_jml_MKM / $jml_mhsw;
							$jml_U = $tot_jml_U / $jml_mhsw;

							$jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
							$jml_1 = number_format($jml_1, 2);
							$jml_2 = number_format($jml_2, 2);;
							$jml_3 = number_format($jml_3, 2);;
							$jml_4A = number_format($jml_4A, 2);;
							$jml_MKM = number_format($jml_MKM, 2);;
							$jml_U = number_format($jml_U, 2);;

							//Kasus tidak ada target
							if ($data[$target_id] < 1) {
								if ($jumlah_total > 0) {
									//Blok warna hijau tua
									$ketuntasan = 100;
									$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
									$item++;
									blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
								} else {
									echo "<td style=\"width:5%;font-weight:600;\">&nbsp;</td>";
									echo "<td style=\"width:5%; font-weight:600;\">&nbsp;</td>";
									echo "<td style=\"width:5%; font-weight:600;\">&nbsp;</td>";
									echo "<td style=\"width:5%; font-weight:600;\">&nbsp;</td>";
									echo "<td style=\"width:5%; font-weight:600;\">&nbsp;</td>";
									echo "<td style=\"width:5%; font-weight:600;\">&nbsp;</td>";
								}
							} else
							//Kasus ada target
							{
								$blocked = 0;
								//Start - Pewarnaan Capaian Level 4A
								if (($data['skdi_level'] == "4A" or $data['k_level'] == "4A" or $data['ipsg_level'] == "4A" or $data['kml_level'] == "4A") and $blocked == 0) {
									$batas_target = $data[$target_id] / 2;
									$blocked = 1;
									if ($jml_4A > $batas_target) {
										//Blok warna hijau tua
										$ketuntasan = 100;
										$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
										$item++;
										blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
									} else {
										if ($jml_4A <= $batas_target and $jml_4A >= 1)
										//Blok warna hijau muda
										{
											$ketuntasan = 75;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										}
										if ($jml_4A < 1) {
											if ($jml_3 >= $batas_target)
											//Blok warna hijau muda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											} else {
												if ($jumlah_total >= 1)
												//Blok warna kuning
												{
													$ketuntasan = 50;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
													blok_warna("kuning", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
												} else
												//Blok warna merah
												{
													$ketuntasan = 0;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
													blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
												}
											}
										}
									}
								}
								//End - Pewarnaan Capaian Level 4A

								//Start - Pewarnaan Capaian Level 3A dan 3B
								if (($data['skdi_level'] == "3" or $data['k_level'] == "3" or $data['ipsg_level'] == "3" or $data['kml_level'] == "3"
										or $data['skdi_level'] == "3A" or $data['k_level'] == "3A" or $data['ipsg_level'] == "3A" or $data['kml_level'] == "3A"
										or $data['skdi_level'] == "3B" or $data['k_level'] == "3B" or $data['ipsg_level'] == "3B" or $data['kml_level'] == "3B")
									and $blocked == 0
								) {
									$batas_target = $data[$target_id] / 2;
									$blocked = 1;
									if ($jml_3 > $batas_target or $jml_4A > $batas_target) {
										//Blok warna hijau tua
										$ketuntasan = 100;
										$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
										$item++;
										blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
									} else {
										if (($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1))
										//Blok warna hijau muda
										{
											$ketuntasan = 75;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										} else {
											if ($jumlah_total >= 1)
											//Blok warna kuning
											{
												$ketuntasan = 50;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("kuning", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											} else
											//Blok warna merah
											{
												$ketuntasan = 0;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											}
										}
									}
								}
								//End - Pewarnaan Capaian Level 3A dan 3B

								//Start - Pewarnaan Capaian Level 2
								if (($data['skdi_level'] == "2" or $data['k_level'] == "2" or $data['ipsg_level'] == "2" or $data['kml_level'] == "2") and $blocked == 0) {
									$batas_target = $data[$target_id] / 2;
									$blocked = 1;
									if ($jml_2 > $batas_target or $jml_3 > $batas_target or $jml_4A > $batas_target) {
										//Blok warna hijau tua
										$ketuntasan = 100;
										$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
										$item++;
										blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
									} else {
										if (($jml_2 <= $batas_target and $jml_2 >= 1) or ($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1))
										//Blok warna hijau muda
										{
											$ketuntasan = 75;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										} else {
											if ($jml_1 >= 1)
											//Blok warna kuning
											{
												$ketuntasan = 50;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("kuning", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											} else
											//Blok warna merah
											{
												$ketuntasan = 0;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											}
										}
									}
								}
								//End - Pewarnaan Capaian Level 2

								//Start - Pewarnaan Capaian Level 1
								if (($data['skdi_level'] == "1" or $data['k_level'] == "1" or $data['ipsg_level'] == "1" or $data['kml_level'] == "1") and $blocked == 0) {
									$batas_target = $data[$target_id] / 2;
									$blocked = 1;
									if ($jml_1 > $batas_target or $jml_2 > $batas_target or $jml_3 > $batas_target or $jml_4A > $batas_target) {
										//Blok warna hijau tua
										$ketuntasan = 100;
										$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
										$item++;
										blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
									} else {
										if ($jumlah_total >= 1)
										//Blok warna hijau muda
										{
											$ketuntasan = 75;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										} else {
											//Blok warna merah
											$ketuntasan = 0;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										}
									}
								}
								//End - Pewarnaan Capaian Level 1

								//Start - Pewarnaan Capaian MKM
								if (($data['skdi_level'] == "MKM" or $data['k_level'] == "MKM" or $data['ipsg_level'] == "MKM" or $data['kml_level'] == "MKM") and $blocked == 0) {
									$batas_target = $data[$target_id] / 2;
									$blocked = 1;
									if ($jml_MKM > $batas_target) {
										//Blok warna hijau tua
										$ketuntasan = 100;
										$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
										$item++;
										blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
									} else {
										if ($jml_MKM <= $batas_target and $jml_MKM >= 1)
										//Blok warna hijaumuda
										{
											$ketuntasan = 75;
											$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
											$item++;
											blok_warna("hijaumuda", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
										} else {
											//Blok warna kuning
											if ($jml_1 >= 1 or $jml_2 >= 1 or $jml_3 >= 1 or $jml_4A >= 1 or $jml_U >= 1) {
												$ketuntasan = 50;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("kuning", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											} else
											//Blok warna merah
											{
												$ketuntasan = 0;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
												blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
											}
										}
									}
								}
								//End - Pewarnaan Capaian MKM
							}

							echo "</tr>";
							$no++;
						}
						$ketuntasan_rata = $jml_ketuntasan / $item;

						if ($ketuntasan_rata <= 100 and $ketuntasan_rata >= 80) {
							$grade = "A";
							$color = "green";
						}
						if ($ketuntasan_rata < 80 and $ketuntasan_rata >= 70) {
							$grade = "B";
							$color = "blue";
						}
						if ($ketuntasan_rata < 70 and $ketuntasan_rata >= 60) {
							$grade = "C";
							$color = "orange";
						}
						if ($ketuntasan_rata < 60 and $ketuntasan_rata >= 50) {
							$grade = "D";
							$color = "darkred";
						}
						if ($ketuntasan_rata < 50) {
							$grade = "E";
							$color = "red";
						}

						$ketuntasan_rata = number_format($ketuntasan_rata, 2);
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=10 style=\"font-weight:600;\">Ketuntasan: $ketuntasan_rata %<br>";
						echo "Grade: <span style='color: $color;'>$grade</span></td>";
						echo "</tr>";
						echo "</table><br><br>";

						// Catatan Rekap Jurnal
						echo "<center>";
						echo "<a id=\"catatan\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Catatan Rekap Jurnal</a><br><br>";
						echo "<table class='table table-bordered' style='width:60%;'>";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td align=center colspan=2><strong>Status Warna Pencapaian</strong></td></tr>";
						echo "<tr class=\"table-success\"   style=\"border-width: 1px; border-color: #000;\">
						<td align=center  style=\"width:20%; font-weight:600;\">Warna</td>
						<td align=center style=\"width:80%; font-weight:600;\">Status</td></tr>";
						echo "<tr class=\"table-warning\"  style=\"font-weight:600;border-width: 1px; border-color: #000;\"><td style=\"background-color:rgba(252, 15, 0, 0.5)\">&nbsp;</td><td>Ada target kegiatan, belum ada kegiatan</td></tr>";
						echo "<tr class=\"table-warning\" style=\"font-weight:600;border-width: 1px; border-color: #000;\"><td style=\"background-color:rgba(252, 255, 0, 1)\">&nbsp;</td><td>Ada target kegiatan, kegiatan belum sesuai level</td></tr>";
						echo "<tr class=\"table-warning\" style=\"font-weight:600;border-width: 1px; border-color: #000;\"><td style=\"background-color:rgba(0, 250, 0, 0.5)\">&nbsp;</td><td>Ada target kegiatan, kegiatan sesuai level belum sampai <span class= \"text-danger\">50%</span></td></tr>";
						echo "<tr class=\"table-warning\" style=\"font-weight:600;border-width: 1px; border-color: #000;\"><td style=\"background-color:rgba(0, 100, 0, 0.75)\">&nbsp;</td><td>Ada target kegiatan, target telah tercapai</td></tr>";
						echo "<tr class=\"table-warning\" style=\"font-weight:600;border-width: 1px; border-color: #000;\"><td style=\"background-color:rgba(255, 193, 7, 0.4)\">&nbsp;</td><td>Tidak ada target kegiatan</td></tr>";
						echo "</table><br><br>";
						echo "</center>";

						// Level/Kode Capaian Kegiatan
						echo "<center>";
						echo "<table class='table table-bordered' style='width:65%;'>";
						echo "<thead>";
						echo "<tr class=\"table-primary\"style=\"border-width: 1px; border-color: #000;\"><th colspan='2' class='text-center'>Level/Kode Capaian Kegiatan</th></tr>";
						echo "<tr class=\"table-success\"style=\"border-width: 1px; border-color: #000;\"><th style='width:15%; text-align:center;'>Level/Kode</th><th style='width:85%; text-align:center;'>Capaian Kegiatan</th></tr>";
						echo "</thead>";
						echo "<tbody class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<tr >";
						echo "<td><strong>Level 1</strong></td>";
						echo "<td style= \"font-weight:600;\">";
						echo "<span style= \"color:darkblue;\">MENDENGARKAN kuliah/tentiran/mentoring.</span><br>";
						echo "<span style= \"color:brown;\">MENDENGARKAN tentiran residen.</span><br>";
						echo "</td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><strong>Level 2</strong></td>";
						echo "<td style= \"font-weight:600;\">";
						echo "<span style= \"color:darkblue;\">MENGIKUTI/MENYAKSIKAN bedside teaching.</span><br>";
						echo "<span style= \"color:brown;\">MENYAKSIKAN DEMO pada pasien.</span><br>";
						echo "<span style= \"color:purple;\">MENYAKSIKAN DEMO pada video.</span><br>";
						echo "<span style= \"color:darkgreen;\">MENJADI PRESENTAN/PENYANGGAH pada laporan jaga/laporan kasus/jurnal reading.</span><br>";
						echo "</td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><strong>Level 3</strong></td>";
						echo "<td style= \"font-weight:600;\">";
						echo "<span style= \"color:darkblue;\">ASISTEN OPERATOR/ASISTEN TINDAKAN.</span><br>";
						echo "<span style= \"color:brown;\">SIMULASI kasus pada PS/manekin/sesama koas.</span><br>";
						echo "<span style= \"color:purple;\">SIMULASI manajemen terapi/resep.</span><br>";
						echo "</td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><strong>Level 4A</strong></td>";
						echo "<td style= \"font-weight:600;\">";
						echo "<span style= \"color:darkblue;\">
						<span style= \"color:darkblue;\">MELAKUKAN SENDIRI pada pasien sungguhan (dengan supervisi).</span><br>";
						echo "</td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><strong>Kode MKM</strong></td>";
						echo "<td style= \"font-weight:600;\">
						<span style= \"color:darkblue;\">Masalah Kesehatan Masyarakat</span><br></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><strong>Kode U</strong></td>";
						echo "<td style= \"font-weight:600;\">
						<span style= \"color:darkblue;\">Kegiatan Ujian Teori/Praktek<br></span></td>";
						echo "</tr>";
						echo "</tbody>";
						echo "</table><br><br>";
						echo "</center>";

						$delete_dummy_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$_COOKIE[user]'");
						$delete_dummy_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$_COOKIE[user]'");


						?>
					</div>
				</div>
			</main>


			<!-- End Content -->
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

</HTML>