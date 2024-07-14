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
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">NILAI CBD - LEMBAR PENILAIAN FORMATIF KOMPETENSI KLINIK<p>Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</font></h4>";

		$id_stase = "M093";
		$id = $_GET['id'];
		$data_cbd = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `psikiatri_nilai_cbd` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_cbd[nim]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_cbd[nim]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

		$tanggal_ujian = tanggal_indo($data_cbd[tgl_ujian]);
		$tanggal_approval = tanggal_indo($data_cbd[tgl_approval]);
		$awal_periode = tanggal_indo($data_cbd[tgl_awal]);
		$akhir_periode = tanggal_indo($data_cbd[tgl_akhir]);

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
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</select>";
			echo "</td>";
		echo "</tr>";
		//Situasi Ruangan
		echo "<tr>";
			echo "<td>Situasi Ruangan</td>";
			echo "<td>$data_cbd[situasi_ruangan]</td>";
		echo "</tr>";
		//Nama Pasien
		echo "<tr>";
			echo "<td>Nama Pasien</td>";
			echo "<td>$data_cbd[nama_pasien]</td>";
		echo "</tr>";
		//Nama Pasien
		echo "<tr>";
			echo "<td>Umur Pasien</td>";
			echo "<td>$data_cbd[umur_pasien] tahun</td>";
		echo "</tr>";
		//Jenis Kelamin Pasien
		echo "<tr>";
			echo "<td>Jenis Kelamin Pasien</td>";
			echo "<td>$data_cbd[jk_pasien]</td>";
		echo "</tr>";
		//Status Kasus
		echo "<tr>";
			echo "<td>Status Kasus</td>";
			echo "<td>$data_cbd[status_kasus]</td>";
		echo "</tr>";
		//Tingkat Kerumitan
		echo "<tr>";
			echo "<td>Tingkat Kerumitan</td>";
			echo "<td>$data_cbd[tingkat_kerumitan]</td>";
		echo "</tr>";
		//Fokus Pertemuan Klinik
		echo "<tr>";
			echo "<td>Fokus Pertemuan Klinik</td>";
			echo "<td>$data_cbd[fokus_pertemuan]</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:55%\">Komponen Kompetensi</th>";
			echo "<th style=\"width:20%\">Status Observasi</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kemampuan membuat catatan medis</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_1]=="1") echo "Ya"; else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_cbd[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Clinical assesment</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_2]=="1") echo "Ya"; else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_cbd[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Investigasi dan rujukan</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_3]=="1") echo "Ya"; else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_cbd[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Terapi</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_4]=="1") echo "Ya"; else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_cbd[aspek_4]</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Follow up dan rencana pengelolaan selanjutnya</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_5]=="1") echo "Ya"; else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_cbd[aspek_5]</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Profesionalisme</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_6]=="1") echo "Ya"; else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_cbd[aspek_6]</td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Penilaian klinik secara keseluruhan</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_7]=="1") echo "Ya"; else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_cbd[aspek_7]</td>";
		echo "</tr>";
		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=3>Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center>$data_cbd[nilai_rata]</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2 align=center><b>UMPAN BALIK TERHADAP KINERJA PESERTA UJIAN</b></td></tr>";
		echo "<tr>";
			echo "<td align=center style=\"width:50%\">Aspek yang sudah bagus</td>";
			echo "<td align=center style=\"width:50%\">Aspek yang perlu diperbaiki</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_cbd[ub_bagus]</textarea></td>";
			echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_cbd[ub_perbaikan]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>Action plan yang disetujui bersama:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_cbd[saran]</textarea></td>";
		echo "</tr>";
		//Kepuasaan penilai terhadap CBD
		echo "<tr>";
			echo "<td colspan=2><b>Catatan:</b><br><br>Kepuasaan penilai terhadap CBD: <i>$data_cbd[kepuasan]</i><br><br></td>";
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
