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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN MATA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI PRESENTASI JOURNAL READING<p>Kepaniteraan (Stase) Ilmu Kesehatan Mata</font></h4>";

		$id_stase = "M104";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `mata_nilai_jurnal` WHERE `id`='$id'"));

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
		//Periode Stase
		echo "<tr>";
			$mulai_stase = tanggal_indo($datastase_mhsw[tgl_mulai]);
			$selesai_stase = tanggal_indo($datastase_mhsw[tgl_selesai]);
			echo "<td class=\"td_mid\">Periode Kepaniteraan (Stase)</td>";
			echo "<td class=\"td_mid\">$mulai_stase s.d. $selesai_stase</td>";
		echo "</tr>";
		//Tanggal Penyajian
		echo "<tr>";
			$tanggal_penyajian = tanggal_indo($data_jurnal[tgl_penyajian]);
			echo "<td class=\"td_mid\">Tanggal Penyajian</td>";
			echo "<td class=\"td_mid\">$tanggal_penyajian</td>";
		echo "</tr>";
		//Judul
		echo "<tr>";
			echo "<td>Judul Artikel</td>";
			echo "<td>$data_jurnal[judul_presentasi]</td>";
		echo "</tr>";
		//Dosen Penilai
		echo "<tr>";
			echo "<td>Dosen Penilai</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
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
		//1. Cara Penyajian
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td colspan=3>Cara Penyajian:</td>";
		echo "</tr>";
		//No 1.1
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.1. Penampilan</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center>$data_jurnal[aspek_1]</td>";
		echo "</tr>";
		//No 1.2
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.2. Penyampaian</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_jurnal[aspek_2]</td>";
		echo "</tr>";
		//No 1.3
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.3. Makalah</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_jurnal[aspek_3]</td>";
		echo "</tr>";
		//2. Penguasaan Materi
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Penguasaan Materi</td>";
			echo "<td align=center>30%</td>";
			echo "<td align=center>$data_jurnal[aspek_4]</td>";
		echo "</tr>";
		//3. Pengetahuan Teori / Penunjang
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Pengetahuan Teori / Penunjang</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_jurnal[aspek_5]</td>";
		echo "</tr>";
		//Nilai Total
		echo "<tr>";
			echo "<td colspan=3 align=right>Nilai Total (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center>$data_jurnal[nilai_total]</td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_jurnal[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_jurnal[saran]</textarea></td>";
		echo "</tr>";
		echo "</table><br>";

		//Nilai Penyanggah
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Penilaian Penyanggah:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:75%\">Nama / NIM Mahasiswa </th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//Penyanggah 1-5
		$i=1;
		while ($i<6)
		{
			echo "<tr>";
				echo "<td align=center>$i</td>";
				echo "<td>Penyanggah-$i: ";
					$penyanggah = "penyanggah"."$i";
					$nilai = "nilai"."$i";
					$penyanggah_i = "penyanggah_"."$i";
					$nilai_penyanggah_i = "nilai_penyanggah_"."$i";
					if ($data_jurnal[$penyanggah_i]=="-")
						echo "-";
					else
					{
						$mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$data_jurnal[$penyanggah_i]'"));
						echo "$mhsw[nama] ($mhsw[nim])";
					}
				echo "</td>";
				echo "<td align=center>$data_jurnal[$nilai_penyanggah_i]</td>";
			echo "</tr>";
			$i++;
		}

		echo "<tr><td colspan=3 align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
		echo "Dosen Penilai<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			echo "
					<script>
					window.location.href=\"penilaian_mata.php\";
					</script>
					";

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
