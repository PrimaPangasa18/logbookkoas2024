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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE)ILMU KESEHATAN JIWA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">NILAI UJIAN MINI-CEX - UJIAN KOMPETENSI KLINIK<p>Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</font></h4>";

		$id_stase = "M093";
		$id = $_GET['id'];
		$data_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `psikiatri_nilai_minicex` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_minicex[nim]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_minicex[nim]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

		$tanggal_ujian = tanggal_indo($data_minicex[tgl_ujian]);
		$tanggal_approval = tanggal_indo($data_minicex[tgl_approval]);
		$awal_periode = tanggal_indo($data_minicex[tgl_awal]);
		$akhir_periode = tanggal_indo($data_minicex[tgl_akhir]);

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

		//Nama mahasiswa
		echo "<tr>";
			echo "<td style=\"width:40%\">Nama Mahasiswa Koas</td>";
			echo "<td style=\"width:60%\">$data_mhsw[nama]</td>";
		echo "</tr>";
		//NIM
		echo "<tr>";
			echo "<td>NIM</td>";
			echo "<td>$data_mhsw[nim]</td>";
		echo "</tr>";
		//Periode Kegiatan
		echo "<tr>";
			echo "<td>Periode Kegiatan</td>";
			echo "<td>$awal_periode s.d. $akhir_periode</td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			echo "<td>Tanggal Ujian</td>";
			echo "<td>$tanggal_ujian</td>";
		echo "</tr>";
		//Dosen Penilai/Penguji
		echo "<tr>";
			echo "<td>Dosen Penilai/Penguji</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</select>";
			echo "</td>";
		echo "</tr>";
		//Tempat/Lokasi
		echo "<tr>";
			echo "<td>Tempat / Lokasi</td>";
			echo "<td>$data_minicex[lokasi]</td>";
		echo "</tr>";
		//Nama Pasien
		echo "<tr>";
			echo "<td>Nama Pasien</td>";
			echo "<td>$data_minicex[nama_pasien]</td>";
		echo "</tr>";
		//Nama Pasien
		echo "<tr>";
			echo "<td>Umur Pasien</td>";
			echo "<td>$data_minicex[umur_pasien] tahun</td>";
		echo "</tr>";
		//Jenis Kelamin Pasien
		echo "<tr>";
			echo "<td>Jenis Kelamin Pasien</td>";
			echo "<td>$data_minicex[jk_pasien]</td>";
		echo "</tr>";
		//Status Kasus
		echo "<tr>";
			echo "<td>Status Kasus</td>";
			echo "<td>$data_minicex[status_kasus]</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:7%\">No</th>";
			echo "<th style=\"width:78%\">Aspek Yang Dinilai</th>";
			echo "<th style=\"width:15%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No A.1
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.1</td>";
			echo "<td>Kemampuan wawancara</td>";
			echo "<td align=center class=\"td_mid\">$data_minicex[aspek_1]</td>";
		echo "</tr>";
		//No A.2
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.2</td>";
			echo "<td>Pemeriksaan status mental</td>";
			echo "<td align=center class=\"td_mid\">$data_minicex[aspek_2]</td>";
		echo "</tr>";
		//No A.3
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.3</td>";
			echo "<td>Kemampuanan diagnosis</td>";
			echo "<td align=center class=\"td_mid\">$data_minicex[aspek_3]</td>";
		echo "</tr>";
		//No A.4
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.4</td>";
			echo "<td>Kemampuan terapi</td>";
			echo "<td align=center class=\"td_mid\">$data_minicex[aspek_4]</td>";
		echo "</tr>";
		//No A.5
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.5</td>";
			echo "<td>Kemampuan konseling</td>";
			echo "<td align=center class=\"td_mid\">$data_minicex[aspek_5]</td>";
		echo "</tr>";
		//No A.6
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.6</td>";
			echo "<td>Profesionalisme dan etika</td>";
			echo "<td align=center class=\"td_mid\">$data_minicex[aspek_6]</td>";
		echo "</tr>";
		//No B
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">B</td>";
			echo "<td>Teori</td>";
			echo "<td align=center class=\"td_mid\">$data_minicex[aspek_7]</td>";
		echo "</tr>";

		//Rata-Rata Nilai
		if ($data_minicex[nilai_rata]>=80) $nilai_huruf = "A";
		if ($data_minicex[nilai_rata]>=70 AND $data_minicex[nilai_rata]<80) $nilai_huruf = "B";
		if ($data_minicex[nilai_rata]<70) $nilai_huruf = "C";
		echo "<tr>";
			echo "<td colspan=2 align=right class=\"td_mid\">Rata-Rata Nilai (Nilai Total / 7)</td>";
			echo "<td align=center class=\"td_mid\"><b>$data_minicex[nilai_rata]</b></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2 align=right class=\"td_mid\">Konversi Nilai ke Huruf</td>";
			echo "<td align=center class=\"td_mid\"><b>$nilai_huruf</b></td>";
		echo "</tr>";
		echo "<tr><td colspan=3><font style=\"font-size:0.75em;\">";
		echo "<i>Keterangan:<br>";
		echo "Nilai Batas Lulus (NBL) = 70<br>";
		echo "Nilai A = 80.00 - 100.00 (SUPERIOR)<br>";
		echo "Nilai B = 70.00 - 79.99 (LULUS)<br>";
		echo "Nilai C < 70.00 (TIDAK LULUS)";
		echo "</i></font></td></tr>";
		echo "</table><br><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2>1. Waktu Penilaian MINI-CEX:</td></tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;Observasi</td>";
			echo "<td>";
			echo "$data_minicex[waktu_observasi] menit";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;Memberikan umpan balik</td>";
			echo "<td>";
			echo "$data_minicex[waktu_ub] menit";
			echo "</td>";
		echo "</tr>";
		//Kepuasaan penilai terhadap minicex
		echo "<tr>";
			echo "<td colspan=2>2. Kepuasaan penilai terhadap MINI-CEX: <i>$data_minicex[kepuasan1]</i></td>";
		echo "</tr>";
		//Kepuasaan peserta terhadap minicex
		echo "<tr>";
			echo "<td colspan=2>3. Kepuasaan peserta ujian terhadap MINI-CEX: <i>$data_minicex[kepuasan2]</i></td>";
		echo "</tr>";

		echo "<tr><td colspan=2 align=right><br><i>Status: <b>DISETUJUI</b><br>pada tanggal $tanggal_approval<br>";
		echo "Dosen Penilai/Penguji<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			if ($_COOKIE['level']==5)
			echo "
					<script>
					window.location.href=\"penilaian_psikiatri.php\";
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
					window.location.href=\"penilaian_psikiatri_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
