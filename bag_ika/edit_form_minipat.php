<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Edit Mini-PAT Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="../images/undipsolid.png">
	<link rel="stylesheet" href="../style/style1.css" />
	<link rel="stylesheet" href="../style/buttontopup.css">
	<link rel="stylesheet" href="../select2/dist/css/select2.css" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">

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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">EDIT FORMULIR PENILAIAN MINI PEER ASSESMENT TOOL (MINI-PAT)<br>KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</h2>
						<br>
						<?php
						$id = $_GET['id'];
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
						$data_minipat = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `ika_nilai_minipat` WHERE `id`='$id'"));

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
						echo "<center>";
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";
						//Nama mahasiswa koas
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Nama Mahasiswa</strong></td>";
						echo "<td style=\"width:60%;font-weight:600; color:darkgreen\">$data_mhsw[nama]</td>";
						echo "</tr>";
						//NIM
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>NIM</strong></td>";
						echo "<td style=\" font-weight:600; color:red\">$data_mhsw[nim]</td>";
						echo "</tr>";
						//Periode Kegiatan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Periode Stase <span class=\"text-danger\">(yyyy-mm-dd)</span><strong></td>";
						echo "<td class=\"td_mid\">
						<input type=\"text\" class=\"tgl_awal\" name=\"tgl_awal\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px; text-align:center\" value=\"$data_minipat[tgl_awal]\" />";
						echo " <span style=\" font-weight:600;\">s.d.</span> <input type=\"text\" class=\"tgl_akhir\" name=\"tgl_akhir\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px; text-align:center\" value=\"$data_minipat[tgl_akhir]\" /></td>";
						echo "</tr>";
						//Tanggal Penilaian
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Tanggal Penilaian <span class=\"text-danger\">(yyyy-mm-dd)</span><strong></td>";
						echo "<td class=\"td_mid\">
						<input type=\"text\" class=\"form-select tgl_penilaian\" name=\"tgl_penilaian\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_minipat[tgl_penilaian]\" /></td>";
						echo "</tr>";
						//Dosen Penilai
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Penilai</strong></td>";
						echo "<td>";
						echo "<select class=\"form-select\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" name=\"dosen\" id=\"dosen\" required>";
						$data_dosen_isian = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minipat[dosen]'"));
						echo "<option value=\"$data_dosen_isian[nip]\">$data_dosen_isian[nama], $data_dosen_isian[gelar] ($data_dosen_isian[nip])</option>";
						$dosen = mysqli_query($con, "SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
						while ($data = mysqli_fetch_array($dosen)) {
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data[username]'"));
							echo "<option value=\"$data[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
						}
						echo "</select>";
						echo "</td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Form nilai
						echo "<table border=2 style=\"width:70%;  background:rgba(255, 243, 205, 1);\" >";
						echo "<tr><td style=\"text-align:center; font-size:1.1em;\"><strong >Form Penilaian:</strong></td></tr>";
						echo "</table>";
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<th style=\"width:5%;text-align:center;\">No</th>";
						echo "<th style=\"width:80%;text-align:center;\">Komponen Penilaian</th>";
						echo "<th style=\"width:15%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//Kemampuan Diagnosis
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=center><strong>Kemampuan Diagnosis:</strong></td>";
						echo "</tr>";
						//No 1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1</strong></td>";
						echo "<td><strong>Kemampuan menegakkan diagnosis</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek1\" value=\"$data_minipat[aspek_1]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2</strong></td>";
						echo "<td><strong>Kemampuan memformulasikan rencana tatalaksana</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek2\" value=\"$data_minipat[aspek_2]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3</strong></td>";
						echo "<td><strong>Kesadaran akan keterbatasan diri sendiri</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek3\" value=\"$data_minipat[aspek_3]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>4</strong></td>";
						echo "<td><strong>Kemampuan terhadap aspek psikososial dan penyakit</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek4\" value=\"$data_minipat[aspek_4]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 5
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>5</strong></td>";
						echo "<td><strong>Pemilihan/penggunaan alat penunjang medik</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek5\" value=\"$data_minipat[aspek_5]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Menjaga Praktik Kedokteran
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=center><strong>Menjaga Praktik Kedokteran:</strong></td>";
						echo "</tr>";
						//No 6
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>6</strong></td>";
						echo "<td><strong>Kemampuan memanfaatkan waktu secara efektif dan prioritas</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek6\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek6\" value=\"$data_minipat[aspek_6]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 7
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>7</strong></td>";
						echo "<td><strong>Kemampuan melaksanakan kewajiban dokter dan kecakapan secara teknis</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek7\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek7\" value=\"$data_minipat[aspek_7]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Partisipasi dalam Pendidikan
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=center><strong>Partisipasi dalam Pendidikan:</strong></td>";
						echo "</tr>";
						//No 8
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>8</strong></td>";
						echo "<td><strong>Keinginan dan kemampuan ikut mendidik sesama peserta didik dan peserta didik profesi lain</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek8\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek8\" value=\"$data_minipat[aspek_8]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Hubungan dengan Pasien
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=center><strong>Hubungan dengan Pasien:</strong></td>";
						echo "</tr>";
						//No 9
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>9</strong></td>";
						echo "<td><strong>Komunikasi dengan pasien</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek9\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek9\" value=\"$data_minipat[aspek_9]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 10
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>10</strong></td>";
						echo "<td><strong>Komunikasi dengan keluarga pasien</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek10\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek10\" value=\"$data_minipat[aspek_10]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 11
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>11</strong></td>";
						echo "<td><strong>Menghargai hak pasien</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek11\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek11\" value=\"$data_minipat[aspek_11]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Kerjasama
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=center><strong>Kerjasama:</strong></td>";
						echo "</tr>";
						//No 12
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>12</strong></td>";
						echo "<td><strong>Komunikasi verbal dengan teman sejawat</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek12\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek12\" value=\"$data_minipat[aspek_12]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 13
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>13</strong></td>";
						echo "<td><strong>Komunikasi tertulis dengan teman sejawat</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek13\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek13\" value=\"$data_minipat[aspek_13]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 14
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>14</strong></td>";
						echo "<td><strong>Kemampuan memahami dan menilai kontribusi orang lain</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek14\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek14\" value=\"$data_minipat[aspek_14]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 15
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>15</strong></td>";
						echo "<td><strong>Asesibilitas dan reliabilitas</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek15\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek15\" value=\"$data_minipat[aspek_15]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 16
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>16</strong></td>";
						echo "<td><strong>Penilaian secara keseluruhan terhadap peserta didik</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek16\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek16\" value=\"$data_minipat[aspek_16]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Nilai Rata-Rata
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2 align=right><strong>Rata-Rata Nilai</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_minipat[nilai_rata]\" required disabled/></td>";
						echo "</tr>";
						echo "</table><br>";

						//Umpan Balik
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr  class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Umpan Balik:</strong><br>
						<textarea name=\"umpan_balik\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_minipat[umpan_balik]</textarea></td>";
						echo "</tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Saran:</strong><br>
						<textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_minipat[saran]</textarea></td>";
						echo "</tr>";
						echo "</table><br>";

						echo "<br>
						<button type=\"submit\" class=\"btn btn-danger\" style=\"margin-right:40px\" name=\"cancel\" value=\"CANCEL\" formnovalidate>
            					<i class=\"fas fa-times me-2\"></i> CANCEL
        						</button>";
						echo "<button type=\"submit\" class=\"btn btn-success\" name=\"ubah\" value=\"UBAH\">
            			<i class=\"fas fa-edit me-2\"></i> UBAH
        			</button>
      				</form><br><br>";
						echo "</center>";

						if ($_POST['cancel'] == "CANCEL") {
							echo "
				<script>
					window.location.href=\"penilaian_ika.php\";
				</script>
				";
						}

						if ($_POST['ubah'] == "UBAH") {
							$aspek1 = number_format($_POST['aspek1'], 2);
							$aspek2 = number_format($_POST['aspek2'], 2);
							$aspek3 = number_format($_POST['aspek3'], 2);
							$aspek4 = number_format($_POST['aspek4'], 2);
							$aspek5 = number_format($_POST['aspek5'], 2);
							$aspek6 = number_format($_POST['aspek6'], 2);
							$aspek7 = number_format($_POST['aspek7'], 2);
							$aspek8 = number_format($_POST['aspek8'], 2);
							$aspek9 = number_format($_POST['aspek9'], 2);
							$aspek10 = number_format($_POST['aspek10'], 2);
							$aspek11 = number_format($_POST['aspek11'], 2);
							$aspek12 = number_format($_POST['aspek12'], 2);
							$aspek13 = number_format($_POST['aspek13'], 2);
							$aspek14 = number_format($_POST['aspek14'], 2);
							$aspek15 = number_format($_POST['aspek15'], 2);
							$aspek16 = number_format($_POST['aspek16'], 2);

							$umpan_balik = addslashes($_POST['umpan_balik']);
							$saran = addslashes($_POST['saran']);

							$nilai_rata = ($_POST['aspek1'] + $_POST['aspek2'] + $_POST['aspek3'] + $_POST['aspek4'] + $_POST['aspek5'] + $_POST['aspek6'] + $_POST['aspek7'] + $_POST['aspek8'] + $_POST['aspek9'] + $_POST['aspek10'] + $_POST['aspek11'] + $_POST['aspek12'] + $_POST['aspek13'] + $_POST['aspek14'] + $_POST['aspek15'] + $_POST['aspek16']) / 16;
							$nilai_rata = number_format($nilai_rata, 2);


							$update_minipat = mysqli_query($con, "UPDATE `ika_nilai_minipat` SET
				`tgl_awal`='$_POST[tgl_awal]',`tgl_akhir`='$_POST[tgl_akhir]',
				`dosen`='$_POST[dosen]',
				`aspek_1`='$aspek1',`aspek_2`='$aspek2',
				`aspek_3`='$aspek3',`aspek_4`='$aspek4',
				`aspek_5`='$aspek5',`aspek_6`='$aspek6',
				`aspek_7`='$aspek7',`aspek_8`='$aspek8',
				`aspek_9`='$aspek9',`aspek_10`='$aspek10',
				`aspek_11`='$aspek11',`aspek_12`='$aspek12',
				`aspek_13`='$aspek13',`aspek_14`='$aspek14',
				`aspek_15`='$aspek15',`aspek_16`='$aspek16',
				`nilai_rata`='$nilai_rata',
				`umpan_balik`='$umpan_balik',`saran`='$saran',
				`tgl_isi`='$tgl',`tgl_penilaian`='$_POST[tgl_penilaian]'
				WHERE `id`='$_POST[id]'");

							echo "
				<script>
					window.location.href=\"penilaian_ika.php\";
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

	<script src="../javascript/script1.js"></script>
	<script src="../javascript/buttontopup.js"></script>
	<script src="../jquery.min.js"></script>
	<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
	<script src="../select2/dist/js/select2.js"></script>
	<script type="text/javascript" src="../freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze").freezeHeader();
			$("#freeze1").freezeHeader();
		});
	</script>
	<script>
		$(document).ready(function() {
			$("#dosen").select2({
				placeholder: "<< Dosen Penilai >>",
				allowClear: true
			});
		});
	</script>
	<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.tgl_penilaian').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('.tgl_awal').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('.tgl_akhir').datepicker({
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
			var aspek6 = document.getElementById('aspek6').value;
			var aspek7 = document.getElementById('aspek7').value;
			var aspek8 = document.getElementById('aspek8').value;
			var aspek9 = document.getElementById('aspek9').value;
			var aspek10 = document.getElementById('aspek10').value;
			var aspek11 = document.getElementById('aspek11').value;
			var aspek12 = document.getElementById('aspek12').value;
			var aspek13 = document.getElementById('aspek13').value;
			var aspek14 = document.getElementById('aspek14').value;
			var aspek15 = document.getElementById('aspek15').value;
			var aspek16 = document.getElementById('aspek16').value;

			var result = parseFloat((parseFloat(aspek1) + parseFloat(aspek2) + parseFloat(aspek3) + parseFloat(aspek4) + parseFloat(aspek5) + parseFloat(aspek6) + parseFloat(aspek7) + parseFloat(aspek8) + parseFloat(aspek9) + parseFloat(aspek10) + parseFloat(aspek11) + parseFloat(aspek12) + parseFloat(aspek13) + parseFloat(aspek14) + parseFloat(aspek15) + parseFloat(aspek16)) / 16);
			if (!isNaN(result)) {
				document.getElementById('nilai_rata').value = number_format(result, 2);
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