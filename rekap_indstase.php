<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rekap Jurnal Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">REKAP LOGBOOK</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">REKAP LOGBOOK KEPANITERAAN (STASE)</h2>
						<br>
						<?php
						$id_stase = $_GET['id'];
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));

						$stase_id = "stase_" . $id_stase;
						$include_id = "include_" . $id_stase;
						$target_id = "target_" . $id_stase;
						$data_stase_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'"));
						$kurang_hari = '-4 days';
						$batas_akhir = date('Y-m-d', strtotime($kurang_hari, strtotime($data_stase_mhsw['tgl_selesai'])));

						$mulai_stase = date_create($data_stase_mhsw['tgl_mulai']);
						$akhir_stase = date_create($data_stase_mhsw['tgl_selesai']);
						$hari_stase = date_diff($mulai_stase, $akhir_stase);
						$batas_tengah = (int)(($hari_stase->days + 1) / 2) - 4; //kurang 4 hari


						/*
		//Catatan Khusus untuk kasus Stase Bedah tgl mulai 17 Feb 2020 (akan pindah ke RSUD Kendal pada pekan ke-5)!!!
		if ($id_stase=="M101" AND $data_stase_mhsw['tgl_mulai']=="2020-02-17")
		{
		    $batas_tengah = (int)($data_stase[hari_stase]/2) - 7;
		}
	    //------------------------------------------------------------
	    */

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<center>";
						echo "<table class=\"table table-bordered\" style=\"width:auto\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td style=\"width:300px;\"><strong>Kepaniteraan (STASE)<strong></td>
						<td style=\"width:400px; font-weight:600; color:purple\">: $data_stase[kepaniteraan]</td></tr>";
						$tgl_mulai = tanggal_indo($data_stase_mhsw['tgl_mulai']);
						$tgl_selesai = tanggal_indo($data_stase_mhsw['tgl_selesai']);
						if ($data_stase_mhsw['tgl_mulai'] == "2000-01-01" or $data_stase_mhsw['tgl_mulai'] == "") {
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\" >
							<td><strong>Tanggal mulai kepaniteraan (STASE)</strong></td><td>: -</td>
							</tr>";
							echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
							<td><strong>Tanggal selesai kepaniteraan (STASE)</strong></td><td>: -</td></tr>";
						} else {
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
							<td><strong>Tanggal mulai kepaniteraan (STASE)</strong></td><td style=\"color:darkblue; font-weight:600;\">: $tgl_mulai</td></tr>";
							echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
							<td><strong>Tanggal selesai kepaniteraan (STASE)</strong></td><td style=\"color:darkblue; font-weight:600;\">: $tgl_selesai</td></tr>";
						}
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<td><strong>Status pengisian log book</strong></td>";
						switch ($data_stase_mhsw['status']) {
							case '': {
									$status = "BELUM TERJADWAL";
									echo "<td style=\"font-weight:600;\">: <font style=\"color:red\">$status</font></td></tr>";
								}
								break;
							case '0': {
									$status = "BELUM AKTIF/DILAKSANAKAN";
									echo "<td style=\"font-weight:600;\">: <font style=\"color:grey\">$status</font></td></tr>";
								}
								break;
							case '1': {
									$status = "SEDANG BERJALAN (AKTIF)";
									echo "<td style=\"font-weight:600;\">: <font style=\"color:green\">$status</font></td></tr>";
								}
								break;
							case '2': {
									$status = "SUDAH SELESAI/TERLEWATI";
									echo "<td style=\"font-weight:600;\">: <font style=\"color:blue\">$status</font></td></tr>";
								}
								break;
						}

						echo "</table><br><br>";
						echo "
								<span class=\"text-danger\" style=\"font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600\">Tekan tombol dibawah ini untuk melihat rekap/catatan/evaluasi</span>
								<br><br>
								<a href=\"#penyakit\" class=\"btn btn-success me-3\">Rekap Jurnal Penyakit</a>
								<a href=\"#ketrampilan\" class=\"btn btn-primary me-3\">Rekap Jurnal Ketrampilan Klinik</a>
								<a href=\"#catatan\" class=\"btn me-3\" style=\"background-color: #800080; color: white;\">Catatan Rekap Jurnal</a>
								<a href=\"#evaluasi\" class=\"btn me-3\" style=\"background-color: #A52A2A; color: white;\">Evaluasi Akhir Kepaniteraan/Stase</a>
								<a href=\"#cetak\" class=\"btn  me-3\" style=\"background-color: #00008B; color: white;\">Cetak Rekap Jurnal</a>
								<br><br>
								";
						if ($data_stase_mhsw['evaluasi'] == '0') {
							echo "<span style=\"color:red; font-weight:600;\">&nbsp;&nbsp;Evaluasi akhir [Status: BELUM]</span><br>";
						} else {
							echo "<span style=\"color:green; font-weight:600;\">&nbsp;&nbsp;Evaluasi akhir [Status: SUDAH]</span><br>";
						}
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

						$data_jurnal_penyakit = mysqli_query($con, "SELECT * FROM `jurnal_penyakit` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `status`='1'");
						$delete_dummy_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");
						while ($data = mysqli_fetch_array($data_jurnal_penyakit)) {
							$insert_penyakit = mysqli_query($con, "INSERT INTO `jurnal_penyakit_dummy`
				(`id`, `nim`, `nama`, `angkatan`,
					`grup`, `hari`, `tanggal`, `stase`,
					`jam_awal`, `jam_akhir`, `kelas`, `lokasi`,
					`kegiatan`, `penyakit1`, `penyakit2`, `penyakit3`,
					`penyakit4`, `dosen`, `status`, `evaluasi`, `username`)
				VALUES
				('$data[id]', '$data[nim]', '$data[nama]', '$data[angkatan]',
				  '$data[grup]', '$data[hari]', '$data[tanggal]', '$data[stase]',
				  '$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]', '$data[lokasi]',
				  '$data[kegiatan]', '$data[penyakit1]', '$data[penyakit2]', '$data[penyakit3]',
				  '$data[penyakit4]', '$data[dosen]', '$data[status]', '$data[evaluasi]', '$_COOKIE[user]')");
						}

						$data_jurnal_ketrampilan = mysqli_query($con, "SELECT * FROM `jurnal_ketrampilan` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `status`='1'");
						$delete_dummy_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");
						while ($data = mysqli_fetch_array($data_jurnal_ketrampilan)) {
							$insert_ketrampilan = mysqli_query($con, "INSERT INTO `jurnal_ketrampilan_dummy`
				(`id`, `nim`, `nama`, `angkatan`,
					`grup`, `hari`, `tanggal`, `stase`,
					`jam_awal`, `jam_akhir`, `kelas`, `lokasi`,
					`kegiatan`, `ketrampilan1`, `ketrampilan2`, `ketrampilan3`,
					`ketrampilan4`, `dosen`, `status`, `evaluasi`, `username`)
				VALUES
				('$data[id]', '$data[nim]', '$data[nama]', '$data[angkatan]',
				  '$data[grup]', '$data[hari]', '$data[tanggal]', '$data[stase]',
				  '$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]', '$data[lokasi]',
				  '$data[kegiatan]', '$data[ketrampilan1]', '$data[ketrampilan2]', '$data[ketrampilan3]',
				  '$data[ketrampilan4]', '$data[dosen]', '$data[status]', '$data[evaluasi]', '$_COOKIE[user]')");
						}

						$daftar_penyakit = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1' ORDER BY `$target_id` DESC,`penyakit` ASC");
						$no = 1;
						$jml_ketuntasan = 0;
						$item = 0;
						$ketuntasan = 0;
						while ($data = mysqli_fetch_array($daftar_penyakit)) {
							echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000; font-weight:600;\">";
							echo "<td align=center  style=\"font-weight:600;\">$no</td>";
							echo "<td style=\"font-weight:600;\">$data[penyakit]</td>";
							echo "<td align=center style=\"font-weight:600;\">
    								<span style=\"color: darkblue;\">{$data['skdi_level']}</span> / 
    								<span style='color: darkred;'>{$data['k_level']}</span> / 
    								<span style='color: purple;'>{$data['ipsg_level']}</span> / 
    								<span style='color: brown;'>{$data['kml_level']}</span>
							</td>";
							echo "<td align=center style=\"font-weight:600;\">$data[$target_id]</td>";

							if ($data['skdi_level'] == "MKM" or $data['k_level'] == "MKM" or $data['ipsg_level'] == "MKM" or $data['kml_level'] == "MKM") {
								$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_1 = 0;
								$jml_2 = 0;
								$jml_3 = 0;
								$jml_4A = 0;
								$jml_U = 0;
							} else {
								$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
		      AND (`kegiatan`='1' OR `kegiatan`='2')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
		      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
		      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
		      AND `kegiatan`='10'
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
		      AND (`kegiatan`='11' OR `kegiatan`='12')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_MKM = 0;
							}
							$jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;

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
						$jml_ketuntasan = 0;
						$item = 0;
						$ketuntasan = 0;
						while ($data = mysqli_fetch_array($daftar_ketrampilan)) {
							echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000; font-weight:600;\">";
							echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
							echo "<td style=\"font-weight:600;\">$data[ketrampilan]</td>";
							echo "<td align=center style=\"font-weight:600;\">
    								<span style=\"color: darkblue;\">{$data['skdi_level']}</span> / 
    								<span style='color: darkred;'>{$data['k_level']}</span> / 
    								<span style='color: purple;'>{$data['ipsg_level']}</span> / 
    								<span style='color: brown;'>{$data['kml_level']}</span>
							</td>";
							echo "<td style=\"text-align:center; font-weight:600;\">$data[$target_id]</td>";

							if ($data['skdi_level'] == "MKM" or $data['k_level'] == "MKM" or $data['ipsg_level'] == "MKM" or $data['kml_level'] == "MKM") {
								$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_1 = 0;
								$jml_2 = 0;
								$jml_3 = 0;
								$jml_4A = 0;
								$jml_U = 0;
							} else {
								$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
		      AND (`kegiatan`='1' OR `kegiatan`='2')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		      AND `kegiatan`='10'
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		      AND (`kegiatan`='11' OR `kegiatan`='12')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
								$jml_MKM = 0;
							}

							$jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;

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


						// Evaluasi Akhir Kepaniteraan/Stase
						echo "<center>";
						echo "<br><a id=\"evaluasi\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Evaluasi Akhir Kepaniteraan/Stase</a><br><br>";

						echo "<div class='alert alert-info' role='alert' style='width:60%; margin: auto; text-align: center; font-family: \'Poppins\', sans-serif;'>";

						if ($data_stase_mhsw['evaluasi'] == '1') {
							echo "<p style='color:green; font-weight:600;'>Sudah terisi!! </p>";
							echo "<a href='lihat_evaluasi.php?id=$id_stase&nim=$_COOKIE[user]&menu=rekap' class='btn btn-success'>LIHAT EVALUASI</a><br><br>";
						} else {
							echo "<p style='color:red;'><strong>&lt;&lt; BELUM MENGISI EVALUASI AKHIR!! &gt;&gt;</strong></p><br>";
							echo "<p style='color:black; font-weight:600;'>Silakan mengisi <span class='text-danger'>evaluasi akhir kepaniteraan/stase</span> dengan menekan button menu di bawah ini, setelah jadwal kegiatan berakhir:</p>";
							echo "<a href='rotasi_internal.php' class='btn btn-success'>Rotasi Stase</a><br><br>";
							echo "<p class='text-danger'><strong>Catatan: Rekap Akhir Kepaniteraan/Stase hanya bisa dicetak setelah mengisi evaluasi akhir Kepaniteraan/Stase!</strong></p><br>";
						}

						echo "</div><br><br>";

						echo "</center>";

						//Cetak
						$mulai = date_create($data_stase_mhsw['tgl_mulai']);
						$sekarang = date_create($tgl);
						$hari_skrg = date_diff($mulai, $sekarang);
						$jmlhari_skrg = $hari_skrg->days + 1;
						if ($jmlhari_skrg < $batas_tengah) $tengah_stase = 0;
						else $tengah_stase = 1;
						if ($data_stase_mhsw['evaluasi'] == "1" and $tgl >= $batas_akhir) $akhir_stase = "1";
						else $akhir_stase = "0";
						echo "<center>";
						echo "<a id=\"cetak\" style=\"font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;\">Cetak Rekap Jurnal</a><br><br>";
						echo "</center>";
						echo "<br>";
						echo "<table border='0' style='width:80%; margin: auto; text-align: center;'>";

						echo "<tr>";
						echo "<td class='align-middle'>";
						if ($tengah_stase == "1") {
							echo "<a href='cetak_setting.php?jenis=penyakit&cetak=tengah&id=$id_stase' target='_blank' class='btn btn-success' role='button' title='Cetak rekap ke pdf'><i class='fa fa-print'></i></a>";
						} else {
							echo "<button class='btn btn-danger' disabled title='Cetak rekap ke pdf'><i class='fa fa-print'></i></button>";
						}
						echo "</td>";

						echo "<td class='align-middle'>";
						if ($tengah_stase == "1") {
							echo "<a href='cetak_setting.php?jenis=ketrampilan&cetak=tengah&id=$id_stase' target='_blank' class='btn btn-success' role='button' title='Cetak rekap ke pdf'><i class='fa fa-print'></i></a>";
						} else {
							echo "<button class='btn btn-danger' disabled title='Cetak rekap ke pdf'><i class='fa fa-print'></i></button>";
						}
						echo "</td>";

						echo "<td class='align-middle'>";
						if ($akhir_stase == "1") {
							echo "<a href='cetak_setting.php?jenis=penyakit&cetak=akhir&id=$id_stase' target='_blank' class='btn btn-success' role='button' title='Cetak rekap ke pdf'><i class='fa fa-print'></i></a>";
						} else {
							echo "<button class='btn btn-danger' disabled title='Cetak rekap ke pdf'><i class='fa fa-print'></i></button>";
						}
						echo "</td>";

						echo "<td class='align-middle'>";
						if ($akhir_stase == "1") {
							echo "<a href='cetak_setting.php?jenis=ketrampilan&cetak=akhir&id=$id_stase' target='_blank' class='btn btn-success' role='button' title='Cetak rekap ke pdf'><i class='fa fa-print'></i></a>";
						} else {
							echo "<button class='btn btn-danger' disabled title='Cetak rekap ke pdf'><i class='fa fa-print'></i></button>";
						}
						echo "</td>";
						echo "</tr>";

						echo "<tr>";
						echo "<td class='align-middle'><font style='font-size:0.9em; font-weight:500;'><span>Cetak Jurnal Penyakit</span></font></td>";
						echo "<td class='align-middle'><font style='font-size:0.9em; font-weight:500;'><span>Cetak Jurnal Ketrampilan Klinis</span></font></td>";
						echo "<td class='align-middle'><font style='font-size:0.9em; font-weight:500;'><span>Cetak Jurnal Penyakit</span></font></td>";
						echo "<td class='align-middle'><font style='font-size:0.9em; font-weight:500;'><span>Cetak Jurnal Ketrampilan Klinis</span></font></td>";
						echo "</tr>";

						echo "<tr>";
						echo "<td colspan='2' class='align-middle'style='font-weight:600;'>Cetak Tengah Kepaniteraan (Stase)</td>";
						echo "<td colspan='2' class='align-middle' style='font-weight:600;'>Cetak Akhir Kepaniteraan (Stase)</td>";
						echo "</tr>";
						echo "<tr>";
						echo "</tr>";
						echo "<tr>";
						echo "<td colspan='4' class='align-middle'><font style='font-size:0.825em'><span style='font-weight:600; color:red;'>Ctt: Anda hanya bisa mencetak rekap pada pertengahan kepaniteraan (stase) atau setelah mengisi evaluasi akhir kepaniteraan (stase)</span></font></td>";
						echo "</tr>";
						echo "</table><br><br>";
						echo "<center>";
						echo "<span class='text-danger;' style='font-weight:600;' > Notes: Tombol berwarna <span style='color:red;'>merah</span> berarti <span style='color:red;'>tidak bisa dicetak</span>,
						Tombol berwarna <span style='color:green;'>hijau</span> berarti <span style='color:green;'>bisa mencetak jurnal</span></span>";
						echo "</center>";
						//----Cetak

						$delete_dummy_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");
						$delete_dummy_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");
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