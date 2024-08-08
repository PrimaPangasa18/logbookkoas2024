<?php

include "config.php";
include "fungsi.php";
include "class.ezpdf.php";

error_reporting("E_ALL ^ E_NOTICE");

if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
	echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
} else {
	if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2 or $_COOKIE['level'] == 3 or $_COOKIE['level'] == 5)) {
		$mhsw_nim = $_GET['nim'];
		$jenis = $_GET['jenis'];
		$cetak = $_GET['cetak'];
		$id_stase = $_GET['id'];
		$batas = $_GET['baris_cetak'];
		$tgl_cetak = $_GET['tgl_cetak'];
		$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$stase_id = "stase_" . $id_stase;
		$include_id = "include_" . $id_stase;
		$target_id = "target_" . $id_stase;
		$data_stase_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$mhsw_nim'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$mhsw_nim'"));
		$mulai = date_create($data_stase_mhsw[tgl_mulai]);
		$selesai = date_create($data_stase_mhsw[tgl_selesai]);
		$batas_tengah = (int)($data_stase[hari_stase] / 2);

		$data_jurnal_penyakit = mysqli_query($con, "SELECT * FROM `jurnal_penyakit` WHERE `nim`='$mhsw_nim' AND `stase`='$id_stase' AND `status`='1'");
		$delete_dummy_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$_COOKIE[user]'");
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

		$data_jurnal_ketrampilan = mysqli_query($con, "SELECT * FROM `jurnal_ketrampilan` WHERE `nim`='$mhsw_nim' AND `stase`='$id_stase' AND `status`='1'");
		$delete_dummy_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$_COOKIE[user]'");
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

		if ($jenis == "penyakit") {
			if ($cetak == "tengah") $judul = "Cetak Rekap Jurnal Penyakit Tengah Kepaniteraan (Stase)";
			if ($cetak == "akhir") $judul = "Cetak Rekap Jurnal Penyakit Akhir Kepaniteraan (Stase)";
		}

		if ($jenis == "ketrampilan") {
			if ($cetak == "tengah") $judul = "Cetak Rekap Jurnal Ketrampilan Klinis Tengah Kepaniteraan (Stase)";
			if ($cetak == "akhir") $judul = "Cetak Rekap Jurnal Ketrampilan Klinis Akhir Kepaniteraan (Stase)";
		}

		if ($jenis == "penyakit") {
			$daftar_penyakit = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1'");
			$jml_ketuntasan = 0;
			$item = 0;
			$ketuntasan = 0;
			while ($data = mysqli_fetch_array($daftar_penyakit)) {
				if ($cetak == "tengah") {
					if ($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") {
						$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_1 = 0;
						$jml_2 = 0;
						$jml_3 = 0;
						$jml_4A = 0;
						$jml_U = 0;
					} else {
						$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
				      AND (`kegiatan`='1' OR `kegiatan`='2')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
				      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
				      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
				      AND `kegiatan`='10'
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
				      AND (`kegiatan`='11' OR `kegiatan`='12')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_MKM = 0;
					}
					$jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
				}
				if ($cetak == "akhir") {
					if ($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") {
						$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_1 = 0;
						$jml_2 = 0;
						$jml_3 = 0;
						$jml_4A = 0;
						$jml_U = 0;
					} else {
						$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
				      AND (`kegiatan`='1' OR `kegiatan`='2')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
				      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
				      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
				      AND `kegiatan`='10'
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
				      AND (`kegiatan`='11' OR `kegiatan`='12')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_MKM = 0;
					}
					$jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
				}
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
					if (($data[skdi_level] == "4A" or $data[k_level] == "4A" or $data[ipsg_level] == "4A" or $data[kml_level] == "4A") and $blocked == 0) {
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
					if (($data[skdi_level] == "3" or $data[k_level] == "3" or $data[ipsg_level] == "3" or $data[kml_level] == "3"
							or $data[skdi_level] == "3A" or $data[k_level] == "3A" or $data[ipsg_level] == "3A" or $data[kml_level] == "3A"
							or $data[skdi_level] == "3B" or $data[k_level] == "3B" or $data[ipsg_level] == "3B" or $data[kml_level] == "3B")
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
					if (($data[skdi_level] == "2" or $data[k_level] == "2" or $data[ipsg_level] == "2" or $data[kml_level] == "2") and $blocked == 0) {
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
					if (($data[skdi_level] == "1" or $data[k_level] == "1" or $data[ipsg_level] == "1" or $data[kml_level] == "1") and $blocked == 0) {
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
					if (($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") and $blocked == 0) {
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
			$ketuntasan_rata = $jml_ketuntasan / $item;
			if ($ketuntasan_rata <= 100 and $ketuntasan_rata >= 80) $grade = "A";
			if ($ketuntasan_rata < 80 and $ketuntasan_rata >= 70) $grade = "B";
			if ($ketuntasan_rata < 70 and $ketuntasan_rata >= 60) $grade = "C";
			if ($ketuntasan_rata < 60 and $ketuntasan_rata >= 50) $grade = "D";
			if ($ketuntasan_rata < 50) $grade = "E";
			$ketuntasan_rata = number_format($ketuntasan_rata, 2);
		}

		if ($jenis == "ketrampilan") {
			$daftar_ketrampilan = mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1'");
			$jml_ketuntasan = 0;
			$item = 0;
			$ketuntasan = 0;
			while ($data = mysqli_fetch_array($daftar_ketrampilan)) {
				if ($cetak == "tengah") {
					if ($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") {
						$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_1 = 0;
						$jml_2 = 0;
						$jml_3 = 0;
						$jml_4A = 0;
						$jml_U = 0;
					} else {
						$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
				      AND (`kegiatan`='1' OR `kegiatan`='2')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
				      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
				      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
				      AND `kegiatan`='10'
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
				      AND (`kegiatan`='11' OR `kegiatan`='12')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_MKM = 0;
					}

					$jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
				}
				if ($cetak == "akhir") {
					if ($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") {
						$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_1 = 0;
						$jml_2 = 0;
						$jml_3 = 0;
						$jml_4A = 0;
						$jml_U = 0;
					} else {
						$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
				      AND (`kegiatan`='1' OR `kegiatan`='2')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
				      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
				      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
				      AND `kegiatan`='10'
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
				      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
				      AND (`kegiatan`='11' OR `kegiatan`='12')
				      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_MKM = 0;
					}

					$jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
				}

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
					if (($data[skdi_level] == "4A" or $data[k_level] == "4A" or $data[ipsg_level] == "4A" or $data[kml_level] == "4A") and $blocked == 0) {
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
					if (($data[skdi_level] == "3" or $data[k_level] == "3" or $data[ipsg_level] == "3" or $data[kml_level] == "3"
							or $data[skdi_level] == "3A" or $data[k_level] == "3A" or $data[ipsg_level] == "3A" or $data[kml_level] == "3A"
							or $data[skdi_level] == "3B" or $data[k_level] == "3B" or $data[ipsg_level] == "3B" or $data[kml_level] == "3B")
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
					if (($data[skdi_level] == "2" or $data[k_level] == "2" or $data[ipsg_level] == "2" or $data[kml_level] == "2") and $blocked == 0) {
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
					if (($data[skdi_level] == "1" or $data[k_level] == "1" or $data[ipsg_level] == "1" or $data[kml_level] == "1") and $blocked == 0) {
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
					if (($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") and $blocked == 0) {
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
			$ketuntasan_rata = $jml_ketuntasan / $item;
			if ($ketuntasan_rata <= 100 and $ketuntasan_rata >= 80) $grade = "A";
			if ($ketuntasan_rata < 80 and $ketuntasan_rata >= 70) $grade = "B";
			if ($ketuntasan_rata < 70 and $ketuntasan_rata >= 60) $grade = "C";
			if ($ketuntasan_rata < 60 and $ketuntasan_rata >= 50) $grade = "D";
			if ($ketuntasan_rata < 50) $grade = "E";
			$ketuntasan_rata = number_format($ketuntasan_rata, 2);
		}


		$tanggal_mulai = tanggal_indo($data_stase_mhsw[tgl_mulai]);
		if ($cetak == "tengah") {
			$hari_tambah = (int)($data_stase['hari_stase'] / 2) - 1;
			$tambah_hari = '+' . $hari_tambah . ' days';
			$tgl_akhir_kegiatan = date('Y-m-d', strtotime($tambah_hari, strtotime($data_stase_mhsw[tgl_mulai])));
		}
		if ($cetak == "akhir") $tgl_akhir_kegiatan = $data_stase_mhsw[tgl_selesai];
		$tanggal_selesai = tanggal_indo($tgl_akhir_kegiatan);
		$periode = $tanggal_mulai . " - " . $tanggal_selesai;

		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);

		if ($jenis == "penyakit") $query1 = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1'");
		if ($jenis == "ketrampilan") $query1 = mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1'");
		$jumlah = mysqli_num_rows($query1);
		$batas1 = $jumlah % $batas;
		if ($batas1 == 0) $batashalakhir = $batas;
		else $batashalakhir = $batas1;
		$amp = (($jumlah - 1) / $batas) + 1;
		$amp1 = floor($amp);

		for ($i = $amp1; $i >= 1; $i--) {
			$pdf->selectFont('./fonts/Helvetica.afm');
			$kolom1 = array('item' => "");
			$tabel1{
				1} = array('item' => "REKAP LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
			$tabel1{
				2} = array('item' => "FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
			$tabel1{
				3} = array('item' => "");
			$tabel1{
				4} = array('item' => "$judul");
			$pdf->ezTable(
				$tabel1,
				$kolom1,
				"",
				array(
					'maxWidth' => 540, 'width' => 520, 'fontSize' => 10, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0,
					'cols' => array('item' => array('justification' => 'left'))
				)
			);
			$pdf->ezSetDy(-10, '');
			$kolom2 = array('item' => "", 'isi' => "");
			$tabel2{
				1} = array('item' => "Nama Mahasiswa", 'isi' => ": " . "$data_mhsw[nama]");
			$tabel2{
				2} = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
			$tabel2{
				3} = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "$data_stase[kepaniteraan]");
			$tabel2{
				4} = array('item' => "Periode Kegiatan", 'isi' => ": " . "$periode");
			$pdf->ezTable(
				$tabel2,
				$kolom2,
				"",
				array(
					'maxWidth' => 540, 'width' => 520, 'fontSize' => 10, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0,
					'cols' => array('item' => array('justification' => 'left', 'width' => 110))
				)
			);
			$pdf->ezSetDy(-10, '');

			$posisi = ($i - 1) * $batas;
			if ($i == $amp1) {
				if ($jenis == "penyakit") $query = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1' ORDER BY `$target_id` DESC,`penyakit` ASC LIMIT $posisi,$batashalakhir");
				if ($jenis == "ketrampilan") $query = mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1' ORDER BY `$target_id` DESC,`ketrampilan` ASC LIMIT $posisi,$batashalakhir");
			} else {
				if ($jenis == "penyakit") $query = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1' ORDER BY `$target_id` DESC,`penyakit` ASC LIMIT $posisi,$batas");
				if ($jenis == "ketrampilan") $query = mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1' ORDER BY `$target_id` DESC,`ketrampilan` ASC LIMIT $posisi,$batas");
			}
			//Header tabel
			$kolom_header = array('NO' => '', 'ITEM' => '', 'LEVEL' => '', 'TARGET' => '', 'C1' => '', 'C2' => '', 'C3' => '', 'C4A' => '', 'CMK' => '', 'CU' => '', 'GRADE' => '');
			if ($jenis == "penyakit")
				$tabel_header{
					1} = array('NO' => '<b>No</b>', 'ITEM' => '<b>Penyakit</b>', 'LEVEL' => '<b>Level S/K/I/M</b>', 'TARGET' => '<b>Target</b>', 'C1' => '<b>C1</b>', 'C2' => '<b>C2</b>', 'C3' => '<b>C3</b>', 'C4A' => '<b>C4</b>', 'CMK' => '<b>CM</b>', 'CU' => '<b>CU</b>', 'GRADE' => '<b>Grade</b>');
			if ($jenis == "ketrampilan")
				$tabel_header{
					1} = array('NO' => '<b>No</b>', 'ITEM' => '<b>Ketrampilan</b>', 'LEVEL' => '<b>Level S/K/I/M</b>', 'TARGET' => '<b>Target</b>', 'C1' => '<b>C1</b>', 'C2' => '<b>C2</b>', 'C3' => '<b>C3</b>', 'C4A' => '<b>C4</b>', 'CMK' => '<b>CM</b>', 'CU' => '<b>CU</b>', 'GRADE' => '<b>Grade</b>');
			$pdf->ezTable(
				$tabel_header,
				$kolom_header,
				"",
				array(
					'maxWidth' => 540, 'width' => 520, 'fontSize' => 8, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 2, 'titleFontSize' => 9, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 1, 'showBgCol' => 0,
					'cols' => array(
						'NO' => array('width' => 30, 'justification' => 'center'), 'ITEM' => array('justification' => 'center'), 'TARGET' => array('width' => 35, 'justification' => 'center'), 'LEVEL' => array('width' => 65, 'justification' => 'center'),
						'C1' => array('width' => 25, 'justification' => 'center'), 'C2' => array('width' => 25, 'justification' => 'center'), 'C3' => array('width' => 25, 'justification' => 'center'),
						'C4A' => array('width' => 25, 'justification' => 'center'), 'CMK' => array('width' => 25, 'justification' => 'center'), 'CU' => array('width' => 25, 'justification' => 'center'), 'GRADE' => array('width' => 35)
					)
				)
			);

			$no = 1;
			$j = $posisi + 1;
			$shaded = 0;
			while ($data = mysqli_fetch_array($query)) {
				if ($jenis == "penyakit") {
					if ($cetak == "tengah") {
						if ($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") {
							$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_1 = 0;
							$jml_2 = 0;
							$jml_3 = 0;
							$jml_4A = 0;
							$jml_U = 0;
						} else {
							$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
					      AND (`kegiatan`='1' OR `kegiatan`='2')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
					      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
					      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
					      AND `kegiatan`='10'
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
					      AND (`kegiatan`='11' OR `kegiatan`='12')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_MKM = 0;
						}
					}
					if ($cetak == "akhir") {
						$mkm = substr($data[id], 0, 5);
						if ($mkm == "MSK14") {
							$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_1 = 0;
							$jml_2 = 0;
							$jml_3 = 0;
							$jml_4A = 0;
							$jml_U = 0;
						} else {
							$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
					      AND (`kegiatan`='1' OR `kegiatan`='2')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
					      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
					      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
					      AND `kegiatan`='10'
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
					      AND (`kegiatan`='11' OR `kegiatan`='12')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_MKM = 0;
						}
					}
					$jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
					//Kasus tidak ada target
					if ($data[$target_id] < 1) {
						if ($jumlah_total > 0) {
							//Blok warna hijau tua
							$bintang = "******";
						} else {
							$bintang = "";
						}
					} else
					//Kasus ada target
					{
						$blocked = 0;
						//Start - Pewarnaan Capaian Level 4A
						if (($data[skdi_level] == "4A" or $data[k_level] == "4A" or $data[ipsg_level] == "4A" or $data[kml_level] == "4A") and $blocked == 0) {
							$batas_target = $data[$target_id] / 2;
							$blocked = 1;
							if ($jml_4A > $batas_target) {
								//Blok warna hijau tua
								$bintang = "******";
							} else {
								if ($jml_4A <= $batas_target and $jml_4A >= 1) {
									//Blok warna hijau muda
									$bintang = "****";
								}
								if ($jml_4A < 1) {
									if ($jml_3 >= $batas_target) {
										//Blok warna hijau muda
										$bintang = "****";
									} else {
										if ($jumlah_total >= 1) {
											//Blok warna kuning
											$bintang = "**";
										} else {
											//Blok warna merah
											$bintang = "";
										}
									}
								}
							}
						}
						//End - Pewarnaan Capaian Level 4A

						//Start - Pewarnaan Capaian Level 3A dan 3B
						if (($data[skdi_level] == "3" or $data[k_level] == "3" or $data[ipsg_level] == "3" or $data[kml_level] == "3"
								or $data[skdi_level] == "3A" or $data[k_level] == "3A" or $data[ipsg_level] == "3A" or $data[kml_level] == "3A"
								or $data[skdi_level] == "3B" or $data[k_level] == "3B" or $data[ipsg_level] == "3B" or $data[kml_level] == "3B")
							and $blocked == 0
						) {
							$batas_target = $data[$target_id] / 2;
							$blocked = 1;
							if ($jml_3 > $batas_target or $jml_4A > $batas_target) {
								//Blok warna hijau tua
								$bintang = "******";
							} else {
								if (($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1)) {
									//Blok warna hijau muda
									$bintang = "****";
								} else {
									if ($jumlah_total >= 1) {
										//Blok warna kuning
										$bintang = "**";
									} else {
										//Blok warna merah
										$bintang = "";
									}
								}
							}
						}
						//End - Pewarnaan Capaian Level 3A dan 3B

						//Start - Pewarnaan Capaian Level 2
						if (($data[skdi_level] == "2" or $data[k_level] == "2" or $data[ipsg_level] == "2" or $data[kml_level] == "2") and $blocked == 0) {
							$batas_target = $data[$target_id] / 2;
							$blocked = 1;
							if ($jml_2 > $batas_target or $jml_3 > $batas_target or $jml_4A > $batas_target) {
								//Blok warna hijau tua
								$bintang = "******";
							} else {
								if (($jml_2 <= $batas_target and $jml_2 >= 1) or ($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1)) {
									//Blok warna hijau muda
									$bintang = "****";
								} else {
									if ($jml_1 >= 1) {
										//Blok warna kuning
										$bintang = "**";
									} else {
										//Blok warna merah
										$bintang = "";
									}
								}
							}
						}
						//End - Pewarnaan Capaian Level 2

						//Start - Pewarnaan Capaian Level 1
						if (($data[skdi_level] == "1" or $data[k_level] == "1" or $data[ipsg_level] == "1" or $data[kml_level] == "1") and $blocked == 0) {
							$batas_target = $data[$target_id] / 2;
							$blocked = 1;
							if ($jml_1 > $batas_target or $jml_2 > $batas_target or $jml_3 > $batas_target or $jml_4A > $batas_target) {
								//Blok warna hijau tua
								$bintang = "******";
							} else {
								if ($jumlah_total >= 1) {
									//Blok warna hijau muda
									$bintang = "****";
								} else {
									//Blok warna merah
									$bintang = "";
								}
							}
						}
						//End - Pewarnaan Capaian Level 1

						//Start - Pewarnaan Capaian MKM
						if (($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") and $blocked == 0) {
							$batas_target = $data[$target_id] / 2;
							$blocked = 1;
							if ($jml_MKM > $batas_target) {
								//Blok warna hijau tua
								$bintang = "******";
							} else {
								if ($jml_MKM <= $batas_target and $jml_MKM >= 1) {
									//Blok warna hijaumuda
									$bintang = "****";
								} else {
									if ($jml_1 >= 1 or $jml_2 >= 1 or $jml_3 >= 1 or $jml_4A >= 1 or $jml_U >= 1) {
										//Blok warna kuning
										$bintang = "**";
									} else {
										//Blok warna merah
										$bintang = "";
									}
								}
							}
						}
						//End - Pewarnaan Capaian MKM
					}

					if ($jml_1 == 0) $jml_1 = "";
					if ($jml_2 == 0) $jml_2 = "";
					if ($jml_3 == 0) $jml_3 = "";
					if ($jml_4A == 0) $jml_4A = "";
					if ($jml_MKM == 0) $jml_MKM = "";
					if ($jml_U == 0) $jml_U = "";
					$baris = array(
						'NO' => $j, 'ITEM' => $data['penyakit'], 'LEVEL' => $data['skdi_level'] . "/" . $data['k_level'] . "/" . $data['ipsg_level'] . "/" . $data['kml_level'], 'TARGET' => $data[$target_id],
						'C1' => $jml_1, 'C2' => $jml_2, 'C3' => $jml_3, 'C4A' => $jml_4A, 'CMK' => $jml_MKM, 'CU' => $jml_U, 'GRADE' => '<b>' . $bintang . '</b>'
					);
				}

				if ($jenis == "ketrampilan") {
					if ($cetak == "tengah") {
						if ($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") {
							$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_1 = 0;
							$jml_2 = 0;
							$jml_3 = 0;
							$jml_4A = 0;
							$jml_U = 0;
						} else {
							$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
					      AND (`kegiatan`='1' OR `kegiatan`='2')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
					      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
					      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
					      AND `kegiatan`='10'
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `hari`<='$batas_tengah'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
					      AND (`kegiatan`='11' OR `kegiatan`='12')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_MKM = 0;
						}
					}
					if ($cetak == "akhir") {
						if ($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") {
							$jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_1 = 0;
							$jml_2 = 0;
							$jml_3 = 0;
							$jml_4A = 0;
							$jml_U = 0;
						} else {
							$jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
					      AND (`kegiatan`='1' OR `kegiatan`='2')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
					      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
					      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
					      AND `kegiatan`='10'
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase'
					      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
					      AND (`kegiatan`='11' OR `kegiatan`='12')
					      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
							$jml_MKM = 0;
						}
					}
					$jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
					//Kasus tidak ada target
					if ($data[$target_id] < 1) {
						if ($jumlah_total > 0) {
							//Blok warna hijau tua
							$bintang = "******";
						} else {
							$bintang = "";
						}
					} else
					//Kasus ada target
					{
						$blocked = 0;
						//Start - Pewarnaan Capaian Level 4A
						if (($data[skdi_level] == "4A" or $data[k_level] == "4A" or $data[ipsg_level] == "4A" or $data[kml_level] == "4A") and $blocked == 0) {
							$batas_target = $data[$target_id] / 2;
							$blocked = 1;
							if ($jml_4A > $batas_target) {
								//Blok warna hijau tua
								$bintang = "******";
							} else {
								if ($jml_4A <= $batas_target and $jml_4A >= 1) {
									//Blok warna hijau muda
									$bintang = "****";
								}
								if ($jml_4A < 1) {
									if ($jml_3 >= $batas_target) {
										//Blok warna hijau muda
										$bintang = "****";
									} else {
										if ($jumlah_total >= 1) {
											//Blok warna kuning
											$bintang = "**";
										} else {
											//Blok warna merah
											$bintang = "";
										}
									}
								}
							}
						}
						//End - Pewarnaan Capaian Level 4A

						//Start - Pewarnaan Capaian Level 3A dan 3B
						if (($data[skdi_level] == "3" or $data[k_level] == "3" or $data[ipsg_level] == "3" or $data[kml_level] == "3"
								or $data[skdi_level] == "3A" or $data[k_level] == "3A" or $data[ipsg_level] == "3A" or $data[kml_level] == "3A"
								or $data[skdi_level] == "3B" or $data[k_level] == "3B" or $data[ipsg_level] == "3B" or $data[kml_level] == "3B")
							and $blocked == 0
						) {
							$batas_target = $data[$target_id] / 2;
							$blocked = 1;
							if ($jml_3 > $batas_target or $jml_4A > $batas_target) {
								//Blok warna hijau tua
								$bintang = "******";
							} else {
								if (($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1)) {
									//Blok warna hijau muda
									$bintang = "****";
								} else {
									if ($jumlah_total >= 1) {
										//Blok warna kuning
										$bintang = "**";
									} else {
										//Blok warna merah
										$bintang = "";
									}
								}
							}
						}
						//End - Pewarnaan Capaian Level 3A dan 3B

						//Start - Pewarnaan Capaian Level 2
						if (($data[skdi_level] == "2" or $data[k_level] == "2" or $data[ipsg_level] == "2" or $data[kml_level] == "2") and $blocked == 0) {
							$batas_target = $data[$target_id] / 2;
							$blocked = 1;
							if ($jml_2 > $batas_target or $jml_3 > $batas_target or $jml_4A > $batas_target) {
								//Blok warna hijau tua
								$bintang = "******";
							} else {
								if (($jml_2 <= $batas_target and $jml_2 >= 1) or ($jml_3 <= $batas_target and $jml_3 >= 1) or ($jml_4A <= $batas_target and $jml_4A >= 1)) {
									//Blok warna hijau muda
									$bintang = "****";
								} else {
									if ($jumlah_total >= 1) {
										//Blok warna kuning
										$bintang = "**";
									} else {
										//Blok warna merah
										$bintang = "";
									}
								}
							}
						}
						//End - Pewarnaan Capaian Level 2

						//Start - Pewarnaan Capaian Level 1
						if (($data[skdi_level] == "1" or $data[k_level] == "1" or $data[ipsg_level] == "1" or $data[kml_level] == "1") and $blocked == 0) {
							$batas_target = $data[$target_id] / 2;
							$blocked = 1;
							if ($jml_1 >= $batas_target or $jml_2 >= $batas_target or $jml_3 >= $batas_target or $jml_4A >= $batas_target) {
								//Blok warna hijau tua
								$bintang = "******";
							} else {
								if ($jumlah_total >= 1) {
									//Blok warna hijau muda
									$bintang = "****";
								} else {
									//Blok warna merah
									$bintang = "";
								}
							}
						}
						//End - Pewarnaan Capaian Level 1

						//Start - Pewarnaan Capaian MKM
						if (($data[skdi_level] == "MKM" or $data[k_level] == "MKM" or $data[ipsg_level] == "MKM" or $data[kml_level] == "MKM") and $blocked == 0) {
							$batas_target = $data[$target_id] / 2;
							$blocked = 1;
							if ($jml_MKM > $batas_target) {
								//Blok warna hijau tua
								$bintang = "******";
							} else {
								if ($jml_MKM <= $batas_target and $jml_MKM >= 1) {
									//Blok warna hijaumuda
									$bintang = "****";
								} else {
									if ($jumlah_total >= 1) {
										//Blok warna kuning
										$bintang = "**";
									} else {
										//Blok warna merah
										$bintang = "";
									}
								}
							}
						}
						//End - Pewarnaan Capaian MKM
					}
					if ($jml_1 == 0) $jml_1 = "";
					if ($jml_2 == 0) $jml_2 = "";
					if ($jml_3 == 0) $jml_3 = "";
					if ($jml_4A == 0) $jml_4A = "";
					if ($jml_MKM == 0) $jml_MKM = "";
					if ($jml_U == 0) $jml_U = "";
					$baris = array(
						'NO' => $j, 'ITEM' => $data['ketrampilan'], 'LEVEL' => $data['skdi_level'] . "/" . $data['k_level'] . "/" . $data['ipsg_level'] . "/" . $data['kml_level'], 'TARGET' => $data[$target_id],
						'C1' => $jml_1, 'C2' => $jml_2, 'C3' => $jml_3, 'C4A' => $jml_4A, 'CMK' => $jml_MKM, 'CU' => $jml_U, 'GRADE' => '<b>' . $bintang . '</b>'
					);
				}
				$tabel3{
					1} = $baris;
				$no++;
				$j++;

				if ($shaded == 2) $shaded = 0;
				else $shaded = 2;

				if ($data[$target_id] < 1) {
					if ($bintang == "******")
						$pdf->ezTable(
							$tabel3,
							$kolom_header,
							"",
							array(
								'maxWidth' => 540, 'width' => 520, 'fontSize' => 8, 'showHeadings' => 0, 'shaded' => $shaded, 'showLines' => 2, 'titleFontSize' => 9, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 1, 'showBgCol' => 1, 'shadeCol2' => array(0.9, 0.9, 0.9),
								'cols' => array(
									'NO' => array('width' => 30, 'justification' => 'center'), 'TARGET' => array('width' => 35, 'justification' => 'center'), 'LEVEL' => array('width' => 65, 'justification' => 'center'),
									'C1' => array('width' => 25, 'justification' => 'center'), 'C2' => array('width' => 25, 'justification' => 'center'), 'C3' => array('width' => 25, 'justification' => 'center'),
									'C4A' => array('width' => 25, 'justification' => 'center'), 'CMK' => array('width' => 25, 'justification' => 'center'), 'CU' => array('width' => 25, 'justification' => 'center'), 'GRADE' => array('width' => 35, 'bgcolor' => array(0, 150 / 250, 0))
								)
							)
						);
					if ($bintang == "")
						$pdf->ezTable(
							$tabel3,
							$kolom_header,
							"",
							array(
								'maxWidth' => 540, 'width' => 520, 'fontSize' => 8, 'showHeadings' => 0, 'shaded' => $shaded, 'showLines' => 2, 'titleFontSize' => 9, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 1, 'showBgCol' => 1, 'shadeCol2' => array(0.9, 0.9, 0.9),
								'cols' => array(
									'NO' => array('width' => 30, 'justification' => 'center'), 'TARGET' => array('width' => 35, 'justification' => 'center'), 'LEVEL' => array('width' => 65, 'justification' => 'center'),
									'C1' => array('width' => 25, 'justification' => 'center'), 'C2' => array('width' => 25, 'justification' => 'center'), 'C3' => array('width' => 25, 'justification' => 'center'),
									'C4A' => array('width' => 25, 'justification' => 'center'), 'CMK' => array('width' => 25, 'justification' => 'center'), 'CU' => array('width' => 25, 'justification' => 'center'), 'GRADE' => array('width' => 35, 'bgcolor' => array(0.95, 0.95, 0.95))
								)
							)
						);
				} else {
					if ($bintang == "******")
						$pdf->ezTable(
							$tabel3,
							$kolom_header,
							"",
							array(
								'maxWidth' => 540, 'width' => 520, 'fontSize' => 8, 'showHeadings' => 0, 'shaded' => $shaded, 'showLines' => 2, 'titleFontSize' => 9, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 1, 'showBgCol' => 1, 'shadeCol2' => array(0.9, 0.9, 0.9),
								'cols' => array(
									'NO' => array('width' => 30, 'justification' => 'center'), 'TARGET' => array('width' => 35, 'justification' => 'center'), 'LEVEL' => array('width' => 65, 'justification' => 'center'),
									'C1' => array('width' => 25, 'justification' => 'center'), 'C2' => array('width' => 25, 'justification' => 'center'), 'C3' => array('width' => 25, 'justification' => 'center'),
									'C4A' => array('width' => 25, 'justification' => 'center'), 'CMK' => array('width' => 25, 'justification' => 'center'), 'CU' => array('width' => 25, 'justification' => 'center'), 'GRADE' => array('width' => 35, 'bgcolor' => array(0, 150 / 250, 0))
								)
							)
						);
					if ($bintang == "****")
						$pdf->ezTable(
							$tabel3,
							$kolom_header,
							"",
							array(
								'maxWidth' => 540, 'width' => 520, 'fontSize' => 8, 'showHeadings' => 0, 'shaded' => $shaded, 'showLines' => 2, 'titleFontSize' => 9, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 1, 'showBgCol' => 1, 'shadeCol2' => array(0.9, 0.9, 0.9),
								'cols' => array(
									'NO' => array('width' => 30, 'justification' => 'center'), 'TARGET' => array('width' => 35, 'justification' => 'center'), 'LEVEL' => array('width' => 65, 'justification' => 'center'),
									'C1' => array('width' => 25, 'justification' => 'center'), 'C2' => array('width' => 25, 'justification' => 'center'), 'C3' => array('width' => 25, 'justification' => 'center'),
									'C4A' => array('width' => 25, 'justification' => 'center'), 'CMK' => array('width' => 25, 'justification' => 'center'), 'CU' => array('width' => 25, 'justification' => 'center'), 'GRADE' => array('width' => 35, 'bgcolor' => array(0, 250 / 250, 0))
								)
							)
						);
					if ($bintang == "**")
						$pdf->ezTable(
							$tabel3,
							$kolom_header,
							"",
							array(
								'maxWidth' => 540, 'width' => 520, 'fontSize' => 8, 'showHeadings' => 0, 'shaded' => $shaded, 'showLines' => 2, 'titleFontSize' => 9, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 1, 'showBgCol' => 1, 'shadeCol2' => array(0.9, 0.9, 0.9),
								'cols' => array(
									'NO' => array('width' => 30, 'justification' => 'center'), 'TARGET' => array('width' => 35, 'justification' => 'center'), 'LEVEL' => array('width' => 65, 'justification' => 'center'),
									'C1' => array('width' => 25, 'justification' => 'center'), 'C2' => array('width' => 25, 'justification' => 'center'), 'C3' => array('width' => 25, 'justification' => 'center'),
									'C4A' => array('width' => 25, 'justification' => 'center'), 'CMK' => array('width' => 25, 'justification' => 'center'), 'CU' => array('width' => 25, 'justification' => 'center'), 'GRADE' => array('width' => 35, 'bgcolor' => array(250 / 250, 250 / 250, 0))
								)
							)
						);
					if ($bintang == "")
						$pdf->ezTable(
							$tabel3,
							$kolom_header,
							"",
							array(
								'maxWidth' => 540, 'width' => 520, 'fontSize' => 8, 'showHeadings' => 0, 'shaded' => $shaded, 'showLines' => 2, 'titleFontSize' => 9, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 1, 'showBgCol' => 1, 'shadeCol2' => array(0.9, 0.9, 0.9),
								'cols' => array(
									'NO' => array('width' => 30, 'justification' => 'center'), 'TARGET' => array('width' => 35, 'justification' => 'center'), 'LEVEL' => array('width' => 65, 'justification' => 'center'),
									'C1' => array('width' => 25, 'justification' => 'center'), 'C2' => array('width' => 25, 'justification' => 'center'), 'C3' => array('width' => 25, 'justification' => 'center'),
									'C4A' => array('width' => 25, 'justification' => 'center'), 'CMK' => array('width' => 25, 'justification' => 'center'), 'CU' => array('width' => 25, 'justification' => 'center'), 'GRADE' => array('width' => 35, 'bgcolor' => array(250 / 250, 75 / 250, 0))
								)
							)
						);
				}
			}


			$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Hal $i / $amp1");


			if ($i == $amp1) {
				//Keterangan
				$pdf->ezSetDy(-5, '');
				$kolom9 = array('item1' => "", 'item2' => "");
				$tabel9{
					1} = array('item1' => "Keterangan Umum:", 'item2' => "Keterangan Grade:");
				$pdf->ezTable(
					$tabel9,
					$kolom9,
					"",
					array('maxWidth' => 540, 'width' => 520, 'fontSize' => 6, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0)
				);
				//Catatan
				$pdf->ezSetDy(-5, '');
				$kolom8 = array('item' => "", 'ket1' => "", 'grade' => "", 'ket2' => "");
				$tabel8_1{
					1} = array('item' => "Level S/K/I/M", 'ket1' => ": Level SKDI/Kepmenkes/IPSG/Muatan Lokal", 'grade' => "******", 'ket2' => ": Ada target kegiatan, target telah tercapai");
				$pdf->ezTable(
					$tabel8_1,
					$kolom8,
					"",
					array(
						'maxWidth' => 540, 'width' => 520, 'fontSize' => 6, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0, 'showBgCol' => 1,
						'cols' => array('item' => array('width' => 50), 'ket1' => array('width' => 210), 'grade' => array('width' => 25, 'bgcolor' => array(0, 150 / 250, 0)))
					)
				);

				$tabel8_2{
					1} = array('item' => "C1", 'ket1' => ": Capaian Kegiatan Level 1", 'grade' => "****", 'ket2' => ": Ada target kegiatan, kegiatan sesuai level belum sampai 50%");
				$pdf->ezTable(
					$tabel8_2,
					$kolom8,
					"",
					array(
						'maxWidth' => 540, 'width' => 520, 'fontSize' => 6, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0, 'showBgCol' => 1,
						'cols' => array('item' => array('width' => 50), 'ket1' => array('width' => 210), 'grade' => array('width' => 25, 'bgcolor' => array(0, 250 / 250, 0)))
					)
				);

				$tabel8_3{
					1} = array('item' => "C2", 'ket1' => ": Capaian Kegiatan Level 2", 'grade' => "**", 'ket2' => ": Ada target kegiatan, kegiatan belum sesuai level");
				$pdf->ezTable(
					$tabel8_3,
					$kolom8,
					"",
					array(
						'maxWidth' => 540, 'width' => 520, 'fontSize' => 6, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0, 'showBgCol' => 1,
						'cols' => array('item' => array('width' => 50), 'ket1' => array('width' => 210), 'grade' => array('width' => 25, 'bgcolor' => array(250 / 250, 250 / 250, 0)))
					)
				);

				$tabel8_4{
					1} = array('item' => "C3", 'ket1' => ": Capaian Kegiatan Level 3/3A/3B", 'grade' => "", 'ket2' => ": Ada target kegiatan, belum ada kegiatan");
				$pdf->ezTable(
					$tabel8_4,
					$kolom8,
					"",
					array(
						'maxWidth' => 540, 'width' => 520, 'fontSize' => 6, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0, 'showBgCol' => 1,
						'cols' => array('item' => array('width' => 50), 'ket1' => array('width' => 210), 'grade' => array('width' => 25, 'bgcolor' => array(250 / 250, 75 / 250, 0)))
					)
				);

				$tabel8_5{
					1} = array('item' => "C4", 'ket1' => ": Capaian Kegiatan Level 4/4A", 'grade' => "", 'ket2' => ": Tidak ada target kegiatan, belum ada kegiatan");
				$pdf->ezTable(
					$tabel8_5,
					$kolom8,
					"",
					array(
						'maxWidth' => 540, 'width' => 520, 'fontSize' => 6, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0, 'showBgCol' => 1,
						'cols' => array('item' => array('width' => 50), 'ket1' => array('width' => 210), 'grade' => array('width' => 25, 'bgcolor' => array(0.95, 0.95, 0.95)))
					)
				);

				$tabel8_6{
					1} = array('item' => "CM", 'ket1' => ": Capaian Kegiatan Masalah Kesehatan Masyarakat", 'grade' => " ", 'ket2' => " ");
				$pdf->ezTable(
					$tabel8_6,
					$kolom8,
					"",
					array(
						'maxWidth' => 540, 'width' => 520, 'fontSize' => 6, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0, 'showBgCol' => 0,
						'cols' => array('item' => array('width' => 50), 'ket1' => array('width' => 210), 'grade' => array('width' => 25))
					)
				);

				$tabel8_7{
					1} = array('item' => "CU", 'ket1' => ": Capaian Kegiatan Ujian", 'grade' => " ", 'ket2' => " ");
				$pdf->ezTable(
					$tabel8_7,
					$kolom8,
					"",
					array(
						'maxWidth' => 540, 'width' => 520, 'fontSize' => 6, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0, 'showBgCol' => 0,
						'cols' => array('item' => array('width' => 50), 'ket1' => array('width' => 210), 'grade' => array('width' => 25))
					)
				);


				//Grading
				$pdf->ezSetDy(-10, '');
				$kolom4 = array('item' => "", 'nilai' => "");
				if ($cetak == "tengah") $tabel4{
					1} = array('item' => "Ketuntasan Tengah Kepaniteraan/Stase", 'nilai' => ": <b>$ketuntasan_rata %</b>");
				if ($cetak == "akhir") $tabel4{
					1} = array('item' => "Ketuntasan Akhir Kepaniteraan/Stase", 'nilai' => ": <b>$ketuntasan_rata %</b>");
				$tabel4{
					2} = array('item' => "Ekivalensi Grade Huruf", 'nilai' => ": <b>$grade</b>");
				$tanggal_cetak = tanggal_indo($tgl_cetak);
				$tabel4{
					3} = array('item' => "Tanggal Cetak Rekap", 'nilai' => ": <b>$tanggal_cetak</b>");
				$pdf->ezTable(
					$tabel4,
					$kolom4,
					"",
					array(
						'maxWidth' => 520, 'width' => 500, 'fontSize' => 10, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0,
						'cols' => array('item' => array('justification' => 'left', 'width' => 190))
					)
				);

				//Dosen Wali dan Kordik
				if ($cetak == "akhir") {
					$dosen_wali = mysqli_fetch_array(mysqli_query($con, "SELECT `nama`,`gelar` FROM `dosen` WHERE `nip`='$data_mhsw[dosen_wali]'"));
					$nama_dosenwali = $dosen_wali[nama] . ", " . $dosen_wali[gelar];
					$nip_dosenwali = "NIP. " . $data_mhsw[dosen_wali];
					$pdf->ezSetDy(-30, '');
					$kolom5 = array('item1' => "", 'item2' => "");
					$tabel5{
						1} = array('item1' => "Semarang, " . "......................... $tahun", 'item2' => "Semarang, " . "......................... $tahun");
					$tabel5{
						2} = array('item1' => "Ketua Bagian/Koordinator Pendidikan Klinik", 'item2' => "Dosen Wali,");
					$tabel5{
						3} = array('item1' => "", 'item2' => "");
					$tabel5{
						4} = array('item1' => "", 'item2' => "");
					$tabel5{
						5} = array('item1' => "", 'item2' => "");
					$tabel5{
						6} = array('item1' => "(___________________________)", 'item2' => "($nama_dosenwali)");
					$tabel5{
						7} = array('item1' => "NIP.", 'item2' => " $nip_dosenwali");
					$pdf->ezTable(
						$tabel5,
						$kolom5,
						"",
						array(
							'maxWidth' => 520, 'width' => 500, 'fontSize' => 10, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0,
							'cols' => array('item1' => array('justification' => 'left', 'width' => 275), 'item2' => array('justification' => 'left', 'width' => 225))
						)
					);
				} else {
					$pdf->ezSetDy(-30, '');
					$kolom5 = array('item' => "");
					$tabel5{
						1} = array('item' => "Semarang, " . "............................. $tahun");
					$tabel5{
						2} = array('item' => "");
					$tabel5{
						3} = array('item' => "Koordinator Pendidikan Klinik/Mentor");
					$tabel5{
						4} = array('item' => "");
					$tabel5{
						5} = array('item' => "");
					$tabel5{
						6} = array('item' => "");
					$tabel5{
						7} = array('item' => "(___________________________)");
					$tabel5{
						8} = array('item' => "NIP.");
					$pdf->ezTable(
						$tabel5,
						$kolom5,
						"",
						array(
							'maxWidth' => 520, 'width' => 500, 'fontSize' => 10, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0,
							'cols' => array('item1' => array('justification' => 'left'))
						)
					);
				}

				//Komentar
				$pdf->ezSetDy(-20, '');
				$kolom6 = array('item' => "");
				$tabel6{
					1} = array('item' => "Komentar/Catatan:");
				$pdf->ezTable(
					$tabel6,
					$kolom6,
					"",
					array(
						'maxWidth' => 520, 'width' => 500, 'fontSize' => 8, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 0,
						'cols' => array('item' => array('justification' => 'left'))
					)
				);
				$pdf->ezSetDy(-10, '');
				$kolom7 = array('item' => "");
				for ($dot = 1; $dot <= 5; $dot++)
					$tabel7{
						$dot} = array('item' => "................................................................................................................................................................................");
				$pdf->ezTable(
					$tabel7,
					$kolom7,
					"",
					array(
						'maxWidth' => 520, 'width' => 500, 'fontSize' => 10, 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'titleFontSize' => 12, 'xPos' => 'center', 'xOrientation' => 'center', 'rowGap' => 4,
						'cols' => array('item' => array('justification' => 'left'))
					)
				);
			}
			if ($i > 1) $pdf->ezNewPage();
		}

		$pdf->ezStream();
		$delete_dummy_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$_COOKIE[user]'");
		$delete_dummy_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$_COOKIE[user]'");
	} else
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
}
