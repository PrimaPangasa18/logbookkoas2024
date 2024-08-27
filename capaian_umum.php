<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Ketuntasan/Grade Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">


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
			<nav class="navbar navbar-expand px-4 py-3" style="background-color: #ff6f61; ">
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
						<h3 class="fw-bold fs-4 mb-3">KETUNTASAN/GRADE KOAS</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							KETUNTASAN/GRADE KOAS
						</h2>
						<br><br>
						<?php
						$grup_filter = $_GET['grup'];
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

						$hari_kurang = 28 - 1;
						$kurang_hari = '-' . $hari_kurang . ' days';
						$tgl_batas = date('Y-m-d', strtotime($kurang_hari, strtotime($tgl_akhir)));

						if ($grup_filter == "1") $filtergrup = "`tgl_mulai`<=" . "'$tgl_batas'";
						if ($grup_filter == "2") $filtergrup = "`tgl_mulai`>" . "'$tgl_batas'" . " AND `tgl_mulai`<=" . "'$tgl_akhir'";
						if ($grup_filter == "all") $filtergrup = "`tgl_mulai`<=" . "'$tgl_akhir'";

						$mhsw = mysqli_query($con, "SELECT `nim` FROM `$stase_id` WHERE $filtergrup AND (`tgl_mulai`>='$tgl_awal' AND `tgl_selesai`<='$tgl_akhir') ORDER BY `nim`");

						$delete_dummy_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$_COOKIE[user]'");
						$delete_dummy_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$_COOKIE[user]'");
						$delete_dummy_mhsw = mysqli_query($con, "DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
						$i = 0;
						while ($data_mhsw = mysqli_fetch_array($mhsw)) {
							//cek Angkatan
							$angkatan = mysqli_fetch_array(mysqli_query($con, "SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
							if ($angkatan_filter == $angkatan['angkatan'] or $angkatan_filter == "all") {
								$i++;
								$nama = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
								$nama_mhsw = addslashes($nama['nama']);
								$insert_dummy = mysqli_query($con, "INSERT INTO `daftar_koas_temp`
					(`id`,`nim`,`nama`,`username`)
					VALUES
					('$i','$data_mhsw[nim]','$nama_mhsw','$_COOKIE[user]')");
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
						('$data[id]', '$data[nim]','','$data[angkatan]', '$data[grup]',
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
						('$data[id]', '$data[nim]','','$data[angkatan]', '$data[grup]',
						  '$data[hari]', '$data[tanggal]', '$data[stase]',
						  '$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]',
						  '$data[lokasi]', '$data[kegiatan]',
						  '$data[ketrampilan1]', '$data[ketrampilan2]', '$data[ketrampilan3]', '$data[ketrampilan4]',
						  '$data[dosen]', '$data[status]', '$data[evaluasi]','$_COOKIE[user]')");
								}
							}
						}
						$jml_mhsw = $i;

						$stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						?>
						<center>
							<table class="table table-bordered" style="width: 50%;">
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td style="width: 30%;"><strong>Kepaniteraan (Stase)</strong></td>
									<td style="width: 70%; color:darkblue; font-weight:600">: <?php echo $stase['kepaniteraan']; ?></td>
								</tr>
								<tr class="table-success" style="border-width: 1px; border-color: #000;">
									<td><strong>Angkatan</strong></td>
									<td class="text-danger" style="font-weight: 600; ">
										<?php
										if ($angkatan_filter == "all") {
											echo ": Semua Angkatan";
										} else {
											echo ": Angkatan $angkatan_filter";
										}
										?>
									</td>
								</tr>
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td><strong>Grup</strong></td>
									<td style="font-weight: 600; color:darkgreen">
										<?php
										if ($grup_filter == "all") {
											echo ": Semua";
										} else {
											if ($grup_filter == '1') {
												echo ": Senior";
											} else {
												echo ": Yunior";
											}
										}
										?>
									</td>
								</tr>
								<tr class="table-success" style="border-width: 1px; border-color: #000;">
									<td><strong>Jumlah Mahasiswa</strong></td>
									<td style="font-weight: 600; ">: <span style="color:red;"><?php echo $jml_mhsw; ?></span> Orang</td>
								</tr>
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td><strong>Periode Kegiatan</strong></td>
									<td style="font-weight: 600;">: <span style="color: darkblue;"><?php echo tanggal_indo($tgl_awal); ?></span> s.d <span style="color: darkblue;"><?php echo tanggal_indo($tgl_akhir); ?></span></td>
								</tr>
							</table>
							<br><br>
						</center>
						<center>
							<span class="text-danger" style="font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600">Tekan tombol dibawah ini untuk melihat ketuntasan jurnal penyakit/keterampilan</span>
							<br><br>
							<a href="#penyakit" class="btn btn-success me-3">Ketuntasan/Grade Jurnal Penyakit</a>
							<a href="#ketrampilan" class="btn btn-primary me-3">Ketuntasan/Grade Jurnal Ketrampilan Klinik</a>
							<br><br>
						</center>
						<a id="penyakit" style="font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;">Ketuntasan/Grade Jurnal Penyakit</a><br><br>
						<?php
						//Ketuntasan Jurnal Penyakit
						echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<tr>";
						echo "<th style=\"width:4% ;text-align:center;\">No</th>";
						echo "<th style=\"width:10% ;text-align:center;\">NIM</th>";
						echo "<th style=\"width:18% ;text-align:center;\">Nama</th>";
						echo "<th style=\"width:13% ;text-align:center;\">Tgl Mulai</th>";
						echo "<th style=\"width:13% ;text-align:center;\">Tgl Selesai</th>";
						echo "<th style=\"width:14% ;text-align:center;\">Status</th>";
						echo "<th style=\"width:14% ;text-align:center;\">Evaluasi</th>";
						echo "<th style=\"width:9% ;text-align:center;\">Ketuntasan</th>";
						echo "<th style=\"width:5% ;text-align:center;\">Grade</th>";
						echo "</tr>";
						echo "</thead>";
						$daftar_mhsw = mysqli_query($con, "SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]' ORDER BY `nama`,`nim`");
						$no = 1;
						$kelas = "ganjil";
						while ($data_mhsw = mysqli_fetch_array($daftar_mhsw)) {
							echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
							echo "<td style=\"text-align:center;color:red; font-weight:600;\">$data_mhsw[nim]</td>";
							echo "<td style=\"text-align:center;color:black; font-weight:600;\">$data_mhsw[nama]</td>";
							$data_stase_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
							$tanggal_mulai = tanggal_indo($data_stase_mhsw['tgl_mulai']);
							$tanggal_selesai = tanggal_indo($data_stase_mhsw['tgl_selesai']);
							echo "<td align=center style=\"color:darkblue; font-weight:600;\">$tanggal_mulai</td>";
							echo "<td align=center style=\"color:darkblue; font-weight:600;\">$tanggal_selesai</td>";
							switch ($data_stase_mhsw['status']) {
								case '': {
										$status = "BELUM TERJADWAL";
										echo "<td align=center><span style=\"color:red;font-weight:600;\">$status</span></td>";
									}
									break;
								case '0': {
										$status = "BELUM AKTIF/ DILAKSANAKAN";
										echo "<td align=center><span style=\"color:grey;font-weight:600;\">$status</span></td>";
									}
									break;
								case '1': {
										$status = "SEDANG BERJALAN (AKTIF)";
										echo "<td><span style=\"color:darkgreen;font-weight:600;\">$status</span></td>";
									}
									break;
								case '2': {
										$status = "SUDAH SELESAI/ TERLEWATI";
										echo "<td align=center><span style=\"color:darkblue;font-weight:600;\">$status</span></td>";
									}
									break;
							}
							switch ($data_stase_mhsw['evaluasi']) {
								case '': {
										$evaluasi = "BELUM TERJADWAL";
										echo "<td align=center><span style=\"color:red;font-weight:600;\">$evaluasi</span></td>";
									}
									break;
								case '0': {
										$evaluasi = "BELUM MENGISI";
										echo "<td align=center><span style=\"color:darkred;font-weight:600;\">$evaluasi</span></td>";
									}
									break;
								case '1': {
										$evaluasi = "SUDAH MENGISI";
										echo "<td align=center ><span style=\"color:green;font-weight:600;\">$evaluasi</span></td>";
									}
									break;
							}

							//Perhitungan Ketercapaian
							$daftar_penyakit = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1'");
							$jml_ketuntasan = 0;
							$item = 0;
							$ketuntasan = 0;
							while ($data = mysqli_fetch_array($daftar_penyakit)) {
								if ($data['skdi_level'] == "MKM" or $data['k_level'] == "MKM" or $data['ipsg_level'] == "MKM" or $data['kml_level'] == "MKM") {
									$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]'
								AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
								AND `username`='$_COOKIE[user]'"));
									$jml_1 = 0;
									$jml_2 = 0;
									$jml_3 = 0;
									$jml_4A = 0;
									$jml_U = 0;
								} else {
									$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]'
								AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
								AND (`kegiatan`='1' OR `kegiatan`='2')
								AND `username`='$_COOKIE[user]'"));
									$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]'
								AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
								AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
								AND `username`='$_COOKIE[user]'"));
									$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]'
								AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
								AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
								AND `username`='$_COOKIE[user]'"));
									$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]'
								AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
								AND `kegiatan`='10'
								AND `username`='$_COOKIE[user]'"));
									$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]'
								AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
								AND (`kegiatan`='11' OR `kegiatan`='12')
								AND `username`='$_COOKIE[user]'"));
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
										} else {
											if ($jml_4A <= $batas_target and $jml_4A >= 1)
											//Blok warna hijau muda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
											}
											if ($jml_4A < 1) {
												if ($jml_3 >= $batas_target) {
													//Blok warna hijau muda
													$ketuntasan = 75;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
												} else {
													if ($jumlah_total >= 1) {
														//Blok warna kuning
														$ketuntasan = 50;
														$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
														$item++;
													} else {
														//Blok warna merah
														$ketuntasan = 0;
														$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
														$item++;
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
										} else {
											if (($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1))
											//Blok warna hijau muda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
											} else {
												if ($jml_2 >= 1 or $jumlah_total >= 1)
												//Blok warna kuning
												{
													$ketuntasan = 50;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
												} else
												//Blok warna merah
												{
													$ketuntasan = 0;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
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
										} else {
											if (($jml_2 <= $batas_target and $jml_2 >= 1) or ($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1))
											//Blok warna hijau muda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
											} else {
												if ($jml_1 >= 1)
												//Blok warna kuning
												{
													$ketuntasan = 50;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
												} else
												//Blok warna merah
												{
													$ketuntasan = 0;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
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
										} else {
											if ($jumlah_total >= 1)
											//Blok warna hijau muda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
											} else {
												//Blok warna merah
												$ketuntasan = 0;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
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
										} else {
											if ($jml_MKM <= $batas_target and $jml_MKM >= 1)
											//Blok warna hijaumuda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
											} else {
												//Blok warna kuning
												if ($jml_1 >= 1 or $jml_2 >= 1 or $jml_3 >= 1 or $jml_4A >= 1 or $jml_U >= 1) {
													$ketuntasan = 50;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
												} else
												//Blok warna merah
												{
													$ketuntasan = 0;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
												}
											}
										}
									}
									//End - Pewarnaan Capaian MKM
								}
							}
							if ($item == 0) $ketuntasan_rata = 0;
							else $ketuntasan_rata = $jml_ketuntasan / $item;
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
							echo "<td align=center style=\"font-weight:600;\">$ketuntasan_rata %</td>";
							echo "<td align=center style=\"color:$color; font-weight:600;\">$grade</td>";
							//-----
							echo "</tr>";
							if ($kelas == "ganjil") $kelas = "genap";
							else $kelas = "ganjil";
							$no++;
						}
						if ($no == 1) echo "<tr>
						<td class=\"table-warning\" colspan=9 align=center  style=\"border-width: 1px; border-color: #000; color:red; font-weight:600;\" ><< E M P T Y >></td></tr>";
						echo "</table><br><br>";
						?>
						<a id="ketrampilan" style="font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;">Ketuntasan/Grade Jurnal Ketrampilan</a><br><br>
						<?php
						//Ketuntasan Jurnal Ketrampilan
						echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze1\">";
						echo "<thead class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<tr>";
						echo "<th style=\"width:4%; text-align:center;\">No</th>";
						echo "<th style=\"width:10%; text-align:center;\">NIM</th>";
						echo "<th style=\"width:18%; text-align:center;\">Nama</th>";
						echo "<th style=\"width:13%; text-align:center;\">Tgl Mulai</th>";
						echo "<th style=\"width:13%; text-align:center;\">Tgl Selesai</th>";
						echo "<th style=\"width:14%; text-align:center;\">Status</th>";
						echo "<th style=\"width:14%; text-align:center;\">Evaluasi</th>";
						echo "<th style=\"width:9%; text-align:center;\">Ketuntasan</th>";
						echo "<th style=\"width:5%; text-align:center;\">Grade</th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tbody>";

						$daftar_mhsw = mysqli_query($con, "SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]' ORDER BY `nama`,`nim`");
						$no = 1;
						$kelas = "ganjil";
						while ($data_mhsw = mysqli_fetch_array($daftar_mhsw)) {
							echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
							echo "<td style=\"text-align:center;color:red; font-weight:600;\">$data_mhsw[nim]</td>";
							echo "<td style=\"text-align:center;color:black; font-weight:600;\">$data_mhsw[nama]</td>";
							$data_stase_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
							$tanggal_mulai = tanggal_indo($data_stase_mhsw['tgl_mulai']);
							$tanggal_selesai = tanggal_indo($data_stase_mhsw['tgl_selesai']);
							echo "<td align=center style=\"color:darkblue; font-weight:600;\">$tanggal_mulai</td>";
							echo "<td align=center style=\"color:darkblue; font-weight:600;\">$tanggal_selesai</td>";
							switch ($data_stase_mhsw['status']) {
								case '': {
										$status = "BELUM TERJADWAL";
										echo "<td align=center><span style=\"color:red; font-weight:600;\">$status</span></td>";
									}
									break;
								case '0': {
										$status = "BELUM AKTIF/ DILAKSANAKAN";
										echo "<td align=center><span style=\"color:grey; font-weight:600;\">$status</span></td>";
									}
									break;
								case '1': {
										$status = "SEDANG BERJALAN (AKTIF)";
										echo "<td align=center><span style=\"color:darkgreen; font-weight:600;\">$status</span></td>";
									}
									break;
								case '2': {
										$status = "SUDAH SELESAI/ TERLEWATI";
										echo "<td align=center><span style=\"color:darkblue; font-weight:600;\">$status</span></td>";
									}
									break;
							}
							switch ($data_stase_mhsw['evaluasi']) {
								case '': {
										$evaluasi = "BELUM TERJADWAL";
										echo "<td align=center><span style=\"color:red; font-weight:600;\">$evaluasi</span></td>";
									}
									break;
								case '0': {
										$evaluasi = "BELUM MENGISI";
										echo "<td align=center><span style=\"color:darkred; font-weight:600;\">$evaluasi</span></td>";
									}
									break;
								case '1': {
										$evaluasi = "SUDAH MENGISI";
										echo "<td align=center><span style=\"color:darkgreen; font-weight:600;\">$evaluasi</span></td>";
									}
									break;
							}

							//Perhitungan Ketercapaian
							$daftar_ketrampilan = mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1'");
							$jml_ketuntasan = 0;
							$item = 0;
							$ketuntasan = 0;
							while ($data = mysqli_fetch_array($daftar_ketrampilan)) {
								if ($data['skdi_level'] == "MKM" or $data['k_level'] == "MKM" or $data['ipsg_level'] == "MKM" or $data['kml_level'] == "MKM") {
									$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]'
			          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
			          AND `username`='$_COOKIE[user]'"));
									$jml_1 = 0;
									$jml_2 = 0;
									$jml_3 = 0;
									$jml_4A = 0;
									$jml_U = 0;
								} else {
									$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]'
			          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
			          AND (`kegiatan`='1' OR `kegiatan`='2')
			          AND `username`='$_COOKIE[user]'"));
									$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]'
			          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
			          AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
			          AND `username`='$_COOKIE[user]'"));
									$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]'
			          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
			          AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
			          AND `username`='$_COOKIE[user]'"));
									$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]'
			          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
			          AND `kegiatan`='10'
			          AND `username`='$_COOKIE[user]'"));
									$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]'
			          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
			          AND (`kegiatan`='11' OR `kegiatan`='12')
			          AND `username`='$_COOKIE[user]'"));
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
										} else {
											if ($jml_4A <= $batas_target and $jml_4A >= 1)
											//Blok warna hijau muda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
											}
											if ($jml_4A < 1) {
												if ($jml_3 >= $batas_target) {
													//Blok warna hijau muda
													$ketuntasan = 75;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
												} else {
													if ($jumlah_total >= 1) {
														//Blok warna kuning
														$ketuntasan = 50;
														$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
														$item++;
													} else {
														//Blok warna merah
														$ketuntasan = 0;
														$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
														$item++;
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
										} else {
											if (($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1))
											//Blok warna hijau muda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
											} else {
												if ($jml_2 >= 1 or $jumlah_total >= 1)
												//Blok warna kuning
												{
													$ketuntasan = 50;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
												} else
												//Blok warna merah
												{
													$ketuntasan = 0;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
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
										} else {
											if (($jml_2 <= $batas_target and $jml_2 >= 1) or ($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1))
											//Blok warna hijau muda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
											} else {
												if ($jml_1 >= 1)
												//Blok warna kuning
												{
													$ketuntasan = 50;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
												} else
												//Blok warna merah
												{
													$ketuntasan = 0;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
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
										} else {
											if ($jumlah_total >= 1)
											//Blok warna hijau muda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
											} else {
												//Blok warna merah
												$ketuntasan = 0;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
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
										} else {
											if ($jml_MKM <= $batas_target and $jml_MKM >= 1)
											//Blok warna hijaumuda
											{
												$ketuntasan = 75;
												$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
												$item++;
											} else {
												//Blok warna kuning
												if ($jml_1 >= 1 or $jml_2 >= 1 or $jml_3 >= 1 or $jml_4A >= 1 or $jml_U >= 1) {
													$ketuntasan = 50;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
												} else
												//Blok warna merah
												{
													$ketuntasan = 0;
													$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
													$item++;
												}
											}
										}
									}
									//End - Pewarnaan Capaian MKM
								}
							}
							if ($item == 0) $ketuntasan_rata = 0;
							else $ketuntasan_rata = $jml_ketuntasan / $item;
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
							echo "<td align=center style=\"font-weight:600;\">$ketuntasan_rata %</td>";
							echo "<td align=center style=\"color:$color; font-weight:600;\">$grade</td>";
							//-----
							echo "</tr>";
							if ($kelas == "ganjil") $kelas = "genap";
							else $kelas = "ganjil";
							$no++;
						}
						if ($no == 1) echo "<tr>
						<td class=\"table-warning\" colspan=9 align=center  style=\"border-width: 1px; border-color: #000; color:red; font-weight:600;\"><< E M P T Y >></td></tr>";
						echo "</tbody>";
						echo "</table><br><br>";
						$delete_dummy_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$_COOKIE[user]'");
						$delete_dummy_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$_COOKIE[user]'");
						$delete_dummy_mhsw = mysqli_query($con, "DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
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
	<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze").freezeHeader();
			$("#freeze1").freezeHeader();
		});
	</script>
</body>

</HTML>