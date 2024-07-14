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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) RADIOLOGI</div>";

		$id_stase = "M103";
		$id = $_GET['id'];
		$data_cbd = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `radiologi_nilai_cbd` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_cbd[nim]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_cbd[nim]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$kasus_up = strtoupper($data_cbd[kasus]);

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">NILAI KASUS CBD - $kasus_up<p>Kepaniteraan (Stase) Radiologi</font></h4>";

		$tanggal_ujian = tanggal_indo($data_cbd[tgl_ujian]);
		$tanggal_approval = tanggal_indo($data_cbd[tgl_approval]);

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
		//Jenis Kasus
		echo "<tr>";
			echo "<td>Jenis Kasus</td>";
			echo "<td>$data_cbd[kasus]</td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Ujian</td>";
			echo "<td class=\"td_mid\">$tanggal_ujian</td>";
		echo "</tr>";
		//Dosen Penguji
		echo "<tr>";
			echo "<td>Dosen Penguji</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
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
			echo "<th style=\"width:75%\">Aspek yang Dinilai</th>";
			echo "<th style=\"width:20%\">Nilai</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kemampuan membuat permintaan dan mengintepretasi pemeriksaan radiologi</td>";
			echo "<td align=center>$data_cbd[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Kemampuan Penulisan</td>";
			echo "<td align=center>$data_cbd[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Penyajian</td>";
			echo "<td align=center>$data_cbd[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Penguasaan Materi</td>";
			echo "<td align=center>$data_cbd[aspek_4]</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Tanya Jawab</td>";
			echo "<td align=center>$data_cbd[aspek_5]</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Keaktifan</td>";
			echo "<td align=center>$data_cbd[aspek_6]</td>";
		echo "</tr>";
		//Nilai Rata
		echo "<tr>";
			echo "<td colspan=2 align=right>Nilai Rata-Rata</td>";
			echo "<td align=center><b>$data_cbd[nilai_rata]</b></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_cbd[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_cbd[saran]</textarea></td>";
		echo "</tr>";

		echo "<tr><td align=right><br><i>Status: <b>DISETUJUI</b><br>pada tanggal $tanggal_approval<br>";
		echo "Dosen Penguji<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			if ($_COOKIE['level']==5)
			echo "
					<script>
					window.location.href=\"penilaian_radiologi.php\";
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
					window.location.href=\"penilaian_radiologi_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
