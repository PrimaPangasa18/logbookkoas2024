<HTML>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
<!--</head>-->
</head>
<BODY>

<?php

include "../config.php";
include "../fungsi.php";

error_reporting("E_ALL ^ E_NOTICE");

	if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
	else{
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==5 OR $_COOKIE['level']==4))
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}
		if ($_COOKIE['level']==4) {include "menu4.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU PENYAKIT DALAM</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">NILAI PENYAJIAN KASUS BESAR<p>KEPANITERAAN (STASE) ILMU PENYAKIT DALAM</font></h4>";

		$id = $_GET['id'];
		$data_kasus = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ipd_nilai_kasus` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_kasus[nim]'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		$tgl_mulai = $_GET[mulai];
		$tgl_selesai = $_GET[selesai];
		$approval = $_GET[approval];
		$mhsw = $_GET[mhsw];
		echo "<input type=\"hidden\" name=\"tgl_mulai\" value=\"$tgl_mulai\" />";
		echo "<input type=\"hidden\" name=\"tgl_selesai\" value=\"$tgl_selesai\" />";
		echo "<input type=\"hidden\" name=\"approval\" value=\"$approval\" />";
		echo "<input type=\"hidden\" name=\"mhsw\" value=\"$mhsw\" />";

		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Nama Mahasiswa
		echo "<tr>";
			echo "<td>Nama Mahasiswa</td>";
			echo "<td>$data_mhsw[nama]</td>";
		echo "</tr>";
		//NIM
		echo "<tr>";
			echo "<td>NIM</td>";
			echo "<td>$data_mhsw[nim]</td>";
		echo "</tr>";
		//Periode Kegiatan
		echo "<tr>";
			$tanggal_awal = tanggal_indo($data_kasus[tgl_awal]);
			$tanggal_akhir = tanggal_indo($data_kasus[tgl_akhir]);
			echo "<td>Periode Stase</td>";
			echo "<td>$tanggal_awal s.d. $tanggal_akhir</td>";
		echo "</tr>";
		//Judul Kasus
		echo "<tr>";
			echo "<td>Kasus</td>";
			echo "<td><textarea name=\"kasus\" style=\"width:97%;font-family:Tahoma;font-size:1em\" disabled>$data_kasus[kasus]</textarea></td>";
		echo "</tr>";
		//Tanggal Penyajian
		echo "<tr>";
			$tanggal_penyajian = tanggal_indo($data_kasus[tgl_penyajian]);
			echo "<td>Tanggal Penyajian</td>";
			echo "<td>$tanggal_penyajian</td>";
		echo "</tr>";
		//Dosen Penilai
		echo "<tr>";
			echo "<td>Dosen Penilai</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:55%\">Komponen Penilaian</th>";
			echo "<th style=\"width:20%\">Bobot</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//Aspek Penyajian
		echo "<tr>";
			echo "<td colspan=4>Aspek Penyajian:</td>";
		echo "</tr>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Slide, sikap, suara</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center>$data_kasus[aspek_1]</td>";
		echo "</tr>";
		//Aspek Naskah
		echo "<tr>";
			echo "<td colspan=4>Aspek Naskah:</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Kesesuaian format dan kedalaman pembahasan untuk dokter umum</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_kasus[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Kepustakaan</td>";
			echo "<td align=center>5%</td>";
			echo "<td align=center>$data_kasus[aspek_3]</td>";
		echo "</tr>";
		//Aspek Diskusi
		echo "<tr>";
			echo "<td colspan=4>Aspek Diskusi:</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Anamnesis (<i>sacred seven, fundamental four</i>)</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center>$data_kasus[aspek_4]</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Pemeriksaan Fisik (<i>status lokalis dan generalis</i>)</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center>$data_kasus[aspek_5]</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Pemeriksaan Penunjang (<i>jenis dan interpretasi hasil</i>)</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center>$data_kasus[aspek_6]</td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Diagnosis Banding / Diagnosis Kerja</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center>$data_kasus[aspek_7]</td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
			echo "<td align=center>8</td>";
			echo "<td>Tatalaksana</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center>$data_kasus[aspek_8]</td>";
		echo "</tr>";
		//No 9
		echo "<tr>";
			echo "<td align=center>9</td>";
			echo "<td>Komplikasi, Pencegahan, Prognosis, Tindak lanjut di FKTP</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center>$data_kasus[aspek_9]</td>";
		echo "</tr>";
		//No 10
		echo "<tr>";
			echo "<td align=center>10</td>";
			echo "<td>Diskusi lain (patogenesis, farmakologi dsb)</td>";
			echo "<td align=center>5%</td>";
			echo "<td align=center>$data_kasus[aspek_10]</td>";
		echo "</tr>";
		//Nilai Rata-Rata
		echo "<tr>";
			echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><b>$data_kasus[nilai_rata]</b></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
			echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_kasus[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_kasus[saran]</textarea></td>";
		echo "</tr>";

		$tanggal_approval = tanggal_indo($data_kasus[tgl_approval]);
		echo "<tr><td colspan=4 align=right><br><i>Status: <b>DISETUJUI</b><br>pada tanggal $tanggal_approval<br>";
		echo "Dosen Penilai<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			if ($_COOKIE['level']==5)
			echo "
					<script>
					window.location.href=\"penilaian_ipd.php\";
					</script>
					";

			if ($_COOKIE['level']==4)
			{
				$tgl_mulai=$_POST[tgl_mulai];
				$tgl_selesai=$_POST[tgl_selesai];
				$approval=$_POST[approval];
				$mhsw=$_POST[mhsw];
				echo "
				<script>
					window.location.href=\"penilaian_ipd_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
				</script>
				";
			}
		}


	}
	else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
?>

<script src="../jquery.min.js"></script>

<!--</body></html>-->
</BODY>
</HTML>
