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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN ILMU KESEHATAN THT-KL</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">NILAI PRESENTASI KASUS<p>Kepaniteraan Ilmu Kesehatan THT-KL</font></h4>";

		$id_stase = "M104";
		$id = $_GET['id'];
		$data_presentasi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `mata_nilai_presentasi` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_presentasi[nim]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_presentasi[nim]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

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
		//Periode Stase
		echo "<tr>";
			$mulai_stase = tanggal_indo($datastase_mhsw[tgl_mulai]);
			$selesai_stase = tanggal_indo($datastase_mhsw[tgl_selesai]);
			echo "<td class=\"td_mid\">Periode Kepaniteraan (Stase)</td>";
			echo "<td class=\"td_mid\">$mulai_stase s.d. $selesai_stase</td>";
		echo "</tr>";
		//Tanggal Penyajian
		echo "<tr>";
			$tanggal_penyajian = tanggal_indo($data_presentasi[tgl_penyajian]);
			echo "<td class=\"td_mid\">Tanggal Penyajian</td>";
			echo "<td class=\"td_mid\">$tanggal_penyajian</td>";
		echo "</tr>";
		//Judul
		echo "<tr>";
			echo "<td>Judul Presentasi Kasus</td>";
			echo "<td>$data_presentasi[judul_presentasi]</td>";
		echo "</tr>";
		//Dosen Penilai
		echo "<tr>";
			echo "<td>Dosen Penilai</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_presentasi[dosen]'"));
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
			echo "<td align=center>$data_presentasi[aspek_1]</td>";
		echo "</tr>";
		//No 1.2
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.2. Penyampaian</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_presentasi[aspek_2]</td>";
		echo "</tr>";
		//No 1.3
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.3. Makalah</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_presentasi[aspek_3]</td>";
		echo "</tr>";
		//2. Penguasaan Materi
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Penguasaan Materi</td>";
			echo "<td align=center>30%</td>";
			echo "<td align=center>$data_presentasi[aspek_4]</td>";
		echo "</tr>";
		//3. Pengetahuan Teori / Penunjang
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Pengetahuan Teori / Penunjang</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_presentasi[aspek_5]</td>";
		echo "</tr>";
		//Nilai Total
		echo "<tr>";
			echo "<td colspan=3 align=right>Nilai Total (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center>$data_presentasi[nilai_total]</td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_presentasi[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_presentasi[saran]</textarea></td>";
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
					if ($data_presentasi[$penyanggah_i]=="-")
						echo "-";
					else
					{
						$mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$data_presentasi[$penyanggah_i]'"));
						echo "$mhsw[nama] ($mhsw[nim])";
					}
				echo "</td>";
				echo "<td align=center>$data_presentasi[$nilai_penyanggah_i]</td>";
			echo "</tr>";
			$i++;
		}

		$tanggal_approval = tanggal_indo($data_presentasi[tgl_approval]);
		echo "<tr><td colspan=3 align=right><br><i>Status: <b>DISETUJUI</b><br>pada tanggal $tanggal_approval<br>";
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
					window.location.href=\"penilaian_mata.php\";
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
					window.location.href=\"penilaian_mata_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
