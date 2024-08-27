<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Approval Journal Reading Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="../images/undipsolid.png">
	<link rel="stylesheet" href="../style/style1.css" />
	<link rel="stylesheet" href="../style/buttontopup.css">
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
	<link rel="stylesheet" href="../select2/dist/css/select2.css" />

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
			<nav class="navbar navbar-expand px-4 py-3" style="background-color: #ff6f61; ">
				<form action="#" class="d-none d-sm-inline-block">
					<div class="input-group input-group-navbar">
						<img src="../images/undipsolid.png" alt="" style="width: 45px;">
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
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">APPROVAL NILAI PRESENTASI JOURNAL READING<br>KEPANITERAAN (STASE) ILMU KESEHATAN MATA</h2>
						<br>
						<?php
						$id_stase = "M104";
						$id = $_GET['id'];
						$data_jurnal = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `mata_nilai_jurnal` WHERE `id`='$id'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_jurnal[nim]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$data_jurnal[nim]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";

						$tgl_mulai = $_GET['mulai'];
						$tgl_selesai = $_GET['selesai'];
						$approval = $_GET['approval'];
						$mhsw = $_GET['mhsw'];
						echo "<input type=\"hidden\" name=\"tgl_mulai\" value=\"$tgl_mulai\" />";
						echo "<input type=\"hidden\" name=\"tgl_selesai\" value=\"$tgl_selesai\" />";
						echo "<input type=\"hidden\" name=\"approval\" value=\"$approval\" />";
						echo "<input type=\"hidden\" name=\"mhsw\" value=\"$mhsw\" />";

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
						//Tanggal Penyajian
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Tanggal Penyajian <span class=\"text-danger\">(yyyy-mm-dd)</span></strong></td>";
						echo "<td class=\"td_mid\"><input type=\"text\" class=\"form-select tanggal_penyajian\" name=\"tanggal_penyajian\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_jurnal[tgl_penyajian]\" /></td>";
						echo "</tr>";
						//Judul
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Judul Artikel</strong></td>";
						echo "<td><textarea name=\"judul_presentasi\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\"  required>$data_jurnal[judul_presentasi]</textarea></td>";
						echo "</tr>";
						//Dosen Pembimbing/Penguji
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Penilai</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
						echo "$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue;\">$data_dosen[nip]</span>)";
						echo "</td>";
						echo "<input type=\"hidden\" name=\"dosen\" value=\"$data_dosen[nip]\" />";
						echo "</tr>";
						echo "</table><br><br>";

						//Form nilai
						echo "<table border=2 style=\"width:70%;  background:rgba(255, 243, 205, 1);\" >";
						echo "<tr><td style=\"text-align:center; font-size:1.1em;\"><strong >Form Penilaian:</strong></td></tr>";
						echo "</table>";
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<th style=\"width:5%;text-align:center;\">No</th>";
						echo "<th style=\"width:55%;text-align:center;\">Komponen Penilaian</th>";
						echo "<th style=\"width:20%;text-align:center;\">Bobot</th>";
						echo "<th style=\"width:20%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//1. Cara Penyajian
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1</strong></td>";
						echo "<td colspan=3><strong>Cara Penyajian:</strong></td>";
						echo "</tr>";
						//No 1.1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center>&nbsp;</td>";
						echo "<td><strong>1.1. Penampilan</strong></td>";
						echo "<td align=center><strong>10%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek1\" value=\"$data_jurnal[aspek_1]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 1.2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center>&nbsp;</td>";
						echo "<td><strong>1.2. Penyampaian</strong></td>";
						echo "<td align=center><strong>20%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek2\" value=\"$data_jurnal[aspek_2]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 1.3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center>&nbsp;</td>";
						echo "<td><strong>1.3. Makalah</strong></td>";
						echo "<td align=center><strong>20%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek3\" value=\"$data_jurnal[aspek_3]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//2. Penguasaan Materi
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2</strong></td>";
						echo "<td><strong>Penguasaan Materi</strong></td>";
						echo "<td align=center><strong>30%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek4\" value=\"$data_jurnal[aspek_4]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//3. Pengetahuan Teori / Penunjang
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3</strong></td>";
						echo "<td><strong>Pengetahuan Teori / Penunjang</strong></td>";
						echo "<td align=center><strong>20%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_jurnal[aspek_5]\" id=\"aspek5\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Nilai Total
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=right><strong>Nilai Total <span class=\"text-danger\">(Jumlah Bobot x Nilai)</span></strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_total\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_total\" value=\"$data_jurnal[nilai_total]\" required disabled/></td>";
						echo "</tr>";
						echo "</table><br>";

						//Umpan Balik
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr  class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Umpan Balik:</strong><br>
						<textarea name=\"umpan_balik\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_jurnal[umpan_balik]</textarea></td>";
						echo "</tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Saran:</strong><br>
						<textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_jurnal[saran]</textarea></td>";
						echo "</tr>";
						echo "</table><br>";

						//Nilai Penyanggah
						echo "<table border=2 style=\"width:70%;  background:rgba(255, 243, 205, 1);\" >";
						echo "<tr><td style=\"text-align:center; font-size:1.1em;\"><strong >Penilaian Penyanggah:</strong></td></tr>";
						echo "</table>";
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<th style=\"width:5%;text-align:center;\">No</th>";
						echo "<th style=\"width:75%;text-align:center;\">Nama / NIM Mahasiswa </th>";
						echo "<th style=\"width:20%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//Penyanggah 1-5
						$i = 1;
						while ($i < 6) {
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td align=center class=\"td_mid\"><strong>$i</strong></td>";
							echo "<td class=\"td_mid\" style=\"font-weight:600;\">Penyanggah-$i: ";
							$penyanggah = "penyanggah" . "$i";
							$nilai = "nilai" . "$i";
							$penyanggah_i = "penyanggah_" . "$i";
							$nilai_penyanggah_i = "nilai_penyanggah_" . "$i";
							echo "<select class=\"form-select\" name=\"$penyanggah\" id=\"$penyanggah\" >";
							if ($data_jurnal[$penyanggah_i] == "-")
								echo "<option value=\"\">< Penyanggah-$i ></option>";
							else {
								$mhsw_i = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$data_jurnal[$penyanggah_i]'"));
								echo "<option value=\"$mhsw_i[nim]\">$mhsw_i[nama] ($mhsw_i[nim])</option>";
							}
							$mhsw = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
							while ($data_penyanggah = mysqli_fetch_array($mhsw))
								echo "<option value=\"$data_penyanggah[nim]\">$data_penyanggah[nama] ($data_penyanggah[nim])</option>";
							echo "</select>";
							echo "</td>";
							if ($data_jurnal[$nilai_penyanggah_i] == "-") echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"$nilai\" style=\"margin-top:17px;width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" placeholder=\"0-100\" /></td>";
							else echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"$nilai\" style=\"margin-top:17px;width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_jurnal[$nilai_penyanggah_i]\" /></td>";
							echo "</tr>";
							$i++;
						}
						echo "</table><br>";

						echo "<br>
						<button type=\"submit\" class=\"btn btn-danger\" style=\"margin-right:40px\" name=\"cancel\" value=\"CANCEL\" formnovalidate>
            					<i class=\"fas fa-times me-2\"></i> CANCEL
        						</button>";
						echo "<button type=\"submit\" class=\"btn btn-success\" name=\"approve\" value=\"APPROVE\">
            			<i class=\"fa-solid fa-check-to-slot\"></i> APPROVE
        			</button>
      				</form><br><br>";
						echo "</center>";

						if ($_POST['cancel'] == "CANCEL") {
							$tgl_mulai = $_POST['tgl_mulai'];
							$tgl_selesai = $_POST['tgl_selesai'];
							$approval = $_POST['approval'];
							$mhsw = $_POST['mhsw'];
							echo "
			<script>
				window.location.href=\"penilaian_mata_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
			</script>
			";
						}

						if ($_POST['approve'] == "APPROVE") {
							$tgl_mulai = $_POST['tgl_mulai'];
							$tgl_selesai = $_POST['tgl_selesai'];
							$approval = $_POST['approval'];
							$mhsw = $_POST['mhsw'];
							$aspek1 = number_format($_POST['aspek1'], 2);
							$aspek2 = number_format($_POST['aspek2'], 2);
							$aspek3 = number_format($_POST['aspek3'], 2);
							$aspek4 = number_format($_POST['aspek4'], 2);
							$aspek5 = number_format($_POST['aspek5'], 2);

							if (!empty($_POST['nilai1'])) $nilai_penyanggah1 = number_format($_POST['nilai1'], 2);
							else $nilai_penyanggah1 = "-";
							if (!empty($_POST['nilai2'])) $nilai_penyanggah2 = number_format($_POST['nilai2'], 2);
							else $nilai_penyanggah2 = "-";
							if (!empty($_POST['nilai3'])) $nilai_penyanggah3 = number_format($_POST['nilai3'], 2);
							else $nilai_penyanggah3 = "-";
							if (!empty($_POST['nilai4'])) $nilai_penyanggah4 = number_format($_POST['nilai4'], 2);
							else $nilai_penyanggah4 = "-";
							if (!empty($_POST['nilai5'])) $nilai_penyanggah5 = number_format($_POST['nilai5'], 2);
							else $nilai_penyanggah5 = "-";

							if (!empty($_POST['penyanggah1']) and $_POST['penyanggah1'] != "") $penyanggah1 = $_POST['penyanggah1'];
							else {
								$penyanggah1 = "-";
								$nilai_penyanggah1 = "-";
							}
							if (!empty($_POST['penyanggah2']) and $_POST['penyanggah2'] != "") $penyanggah2 = $_POST['penyanggah2'];
							else {
								$penyanggah2 = "-";
								$nilai_penyanggah2 = "-";
							}
							if (!empty($_POST['penyanggah3']) and $_POST['penyanggah3'] != "") $penyanggah3 = $_POST['penyanggah3'];
							else {
								$penyanggah3 = "-";
								$nilai_penyanggah3 = "-";
							}
							if (!empty($_POST['penyanggah4']) and $_POST['penyanggah4'] != "") $penyanggah4 = $_POST['penyanggah4'];
							else {
								$penyanggah4 = "-";
								$nilai_penyanggah4 = "-";
							}
							if (!empty($_POST['penyanggah5']) and $_POST['penyanggah5'] != "") $penyanggah5 = $_POST['penyanggah5'];
							else {
								$penyanggah5 = "-";
								$nilai_penyanggah5 = "-";
							}


							$judul = addslashes($_POST['judul_presentasi']);

							$total_nilai = $_POST['aspek1'] * 0.1 + $_POST['aspek2'] * 0.2 + $_POST['aspek3'] * 0.2 + $_POST['aspek4'] * 0.3 + $_POST['aspek5'] * 0.2;
							$total = number_format($total_nilai, 2);

							$umpan_balik = addslashes($_POST['umpan_balik']);
							$saran = addslashes($_POST['saran']);

							$update_jurnal = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET
				`judul_presentasi`='$judul',`tgl_penyajian`='$_POST[tanggal_penyajian]',
				`aspek_1`='$aspek1',`aspek_2`='$aspek2',`aspek_3`='$aspek3',
				`aspek_4`='$aspek4',`aspek_5`='$aspek5',`nilai_total`='$total',
				`umpan_balik`='$umpan_balik',`saran`='$saran',
				`penyanggah_1`='$penyanggah1',`nilai_penyanggah_1`='$nilai_penyanggah1',
				`penyanggah_2`='$penyanggah2',`nilai_penyanggah_2`='$nilai_penyanggah2',
				`penyanggah_3`='$penyanggah3',`nilai_penyanggah_3`='$nilai_penyanggah3',
				`penyanggah_4`='$penyanggah4',`nilai_penyanggah_4`='$nilai_penyanggah4',
				`penyanggah_5`='$penyanggah5',`nilai_penyanggah_5`='$nilai_penyanggah5',
				`tgl_approval`='$tgl', `status_approval`='1'
				WHERE `id`='$_POST[id]'");

							//Penyanggah 1-5
							$i = 1;
							while ($i < 6) {
								if (!empty($_POST["penyanggah" . "$i"]) and $_POST["penyanggah" . "$i"] != "") {
									$penyanggah = $_POST["penyanggah" . "$i"];
									$data_penyanggah = mysqli_query($con, "SELECT `id` FROM `mata_nilai_penyanggah` WHERE `nim`='$penyanggah' AND `dosen`='$_POST[dosen]' AND `presenter`='$_POST[nim]' AND `jenis_presentasi`='Journal Reading'");
									$cek_penyanggah = mysqli_num_rows($data_penyanggah);
									$nilai_penyanggah = number_format($_POST["nilai" . "$i"], 2);
									if ($cek_penyanggah > 0) {
										$data = mysqli_fetch_array($data_penyanggah);
										$update_penyanggah = mysqli_query($con, "UPDATE `mata_nilai_penyanggah` SET
							`id_presentasi`='$_POST[id]',
							`tgl_presentasi`='$_POST[tanggal_penyajian]',
							`judul_presentasi`='$judul',
							`nilai`='$nilai_penyanggah',
							`tgl_approval`='$tgl',
							`status_approval`='1' WHERE `id`='$data[id]'");
									} else {
										$insert_penyanggah = mysqli_query($con, "INSERT INTO `mata_nilai_penyanggah`
							( `id_presentasi`,`nim`, `presenter`, `jenis_presentasi`, `tgl_presentasi`,
								`judul_presentasi`, `dosen`, `nilai`,
								`tgl_approval`, `status_approval`)
							VALUES
							( '$_POST[id]','$penyanggah','$_POST[nim]','Journal Reading','$_POST[tanggal_penyajian]',
								'$judul','$_POST[dosen]','$nilai_penyanggah',
								'$tgl','1')");
									}
								}
								$i++;
							}
							echo "
				<script>
				window.location.href = \"penilaian_mata_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
	<script src="../javascript/buttontopup.js"></script>
	<script src="../jquery.min.js"></script>
	<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
	<script src="../select2/dist/js/select2.js"></script>
	<script>
		$(document).ready(function() {
			$("#dosen").select2({
				placeholder: "<< Dosen Pembimbing/Penguji >>",
				allowClear: true
			});
			$("#penyanggah1").select2({
				placeholder: "< Penyanggah-1 >",
				allowClear: true
			});
			$("#penyanggah2").select2({
				placeholder: "< Penyanggah-2 >",
				allowClear: true
			});
			$("#penyanggah3").select2({
				placeholder: "< Penyanggah-3 >",
				allowClear: true
			});
			$("#penyanggah4").select2({
				placeholder: "< Penyanggah-4 >",
				allowClear: true
			});
			$("#penyanggah5").select2({
				placeholder: "< Penyanggah-5 >",
				allowClear: true
			});

		});
	</script>
	<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.tanggal_penyajian').datepicker({
				dateFormat: 'yy-mm-dd'
			});
		});
	</script>

	<script>
		function sum() {
			var aspek1 = document.getElementById('aspek1').value;
			var aspek2 = document.getElementById('aspek2').value;
			var aspek3 = document.getElementById('aspek3').value;
			var aspek4 = document.getElementById('aspek4').value;
			var aspek5 = document.getElementById('aspek5').value;
			var result = 0.1 * parseFloat(aspek1) + 0.2 * parseFloat(aspek2) + 0.2 * parseFloat(aspek3) + 0.3 * parseFloat(aspek4) + 0.2 * parseFloat(aspek5);
			if (!isNaN(result)) {
				document.getElementById('nilai_total').value = number_format(result, 2);
			}

			function number_format(number, decimals, decPoint, thousandsSep) {
				number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
				var n = !isFinite(+number) ? 0 : +number
				var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
				var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
				var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
				var s = ''

				var toFixedFix = function(n, prec) {
					var k = Math.pow(10, prec)
					return '' + (Math.round(n * k) / k)
						.toFixed(prec)
				}

				// @todo: for IE parseFloat(0.55).toFixed(0) = 0;
				s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
				if (s[0].length > 3) {
					s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
				}
				if ((s[1] || '').length < prec) {
					s[1] = s[1] || ''
					s[1] += new Array(prec - s[1].length + 1).join('0')
				}

				return s.join(dec)
			}
		}
	</script>
</body>

</html>