<HTML>
<head>
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<meta name="viewport" content="width=device-width, maximum-scale=1">
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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU BEDAH</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
			echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">NILAI MENTORING<p>KEPANITERAAN (STASE) ILMU BEDAH</font></h4>";

			$id = $_GET[id];
			$id_stase = "M101";
			$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
			$stase_id = "stase_".$id_stase;
			$data_mentor = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `bedah_nilai_mentor` WHERE `id`='$id'"));
			$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_mentor[nim]'"));
			$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_mentor[nim]'");
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

			echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
			echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";

			echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";

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
			//Mentoring Bulan ke-
			echo "<tr>";
				echo "<td>Mentoring Bulan ke-</td>";
				echo "<td>";
				if ($data_mentor[bulan_ke]=="1")
				echo "<input type=\"radio\" name=\"bulan_ke\" value=\"1\" checked/>&nbsp;&nbsp;1 (<i>Bulan ke-1</i>)<br>
							<input type=\"radio\" name=\"bulan_ke\" value=\"2\" disabled/>&nbsp;&nbsp;2 (<i>Bulan ke-2</i>)";
				if ($data_mentor[bulan_ke]=="2")
				echo "<input type=\"radio\" name=\"bulan_ke\" value=\"1\" disabled/>&nbsp;&nbsp;1 (<i>Bulan ke-1</i>)<br>
							<input type=\"radio\" name=\"bulan_ke\" value=\"2\" checked/>&nbsp;&nbsp;2 (<i>Bulan ke-2</i>)";
				echo "</td>";
			echo "</tr>";
			//Periode Kegiatan
			echo "<tr>";
				$tanggal_awal = tanggal_indo($data_mentor[tgl_awal]);
				$tanggal_akhir = tanggal_indo($data_mentor[tgl_akhir]);
				echo "<td>Periode Penilaian</td>";
				echo "<td>$tanggal_awal s.d. $tanggal_akhir</td>";
			echo "</tr>";
			//Dosen Penilai (Mentor)
			echo "<tr>";
				echo "<td>Dosen Penilai (Mentor)</td>";
				echo "<td>";
				$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_mentor[dosen]'"));
				echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
				echo "</td>";
			echo "</tr>";
			echo "</table><br><br>";

			//Form nilai
			echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
			echo "<tr><td><b>Form Penilaian:</b></td></tr>";
			echo "</table>";
			echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
			echo "<thead>";
			 	echo "<th style=\"width:5%\">No</th>";
				echo "<th style=\"width:75%\">Aspek Yang Dinilai</th>";
				echo "<th style=\"width:20%\">Nilai (0-100)</th>";
			echo "</thead>";
			//No 1
			echo "<tr>";
				echo "<td align=center>1</td>";
				echo "<td>Persentase Kehadiran</td>";
				echo "<td align=center>$data_mentor[aspek_1]</td>";
			echo "</tr>";
			//No 2
			echo "<tr>";
				echo "<td align=center>2</td>";
				echo "<td>Pengetahuan Bedah</td>";
				echo "<td align=center>$data_mentor[aspek_2]</td>";
			echo "</tr>";
			//No 3
			echo "<tr>";
				echo "<td align=center>3</td>";
				echo "<td>Keaktifan</td>";
				echo "<td align=center>$data_mentor[aspek_3]</td>";
			echo "</tr>";
			//No 4
			echo "<tr>";
				echo "<td align=center>4</td>";
				echo "<td>Keterampilan</td>";
				echo "<td align=center>$data_mentor[aspek_4]</td>";
			echo "</tr>";
			//No 5
			echo "<tr>";
				echo "<td align=center>5</td>";
				echo "<td>Kerjasama</td>";
				echo "<td align=center>$data_mentor[aspek_5]</td>";
			echo "</tr>";
			//No 6
			echo "<tr>";
				echo "<td align=center>6</td>";
				echo "<td>Sikap Kesopanan</td>";
				echo "<td align=center>$data_mentor[aspek_6]</td>";
			echo "</tr>";
			//Rata-Rata Nilai
			echo "<tr>";
				echo "<td colspan=2 align=right>Rata-Rata Nilai</td>";
				echo "<td align=center><b>$data_mentor[nilai_rata]</b></td>";
			echo "</tr>";
			echo "</table><br>";

			echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
			//Kasus Ilmiah / Problem Pasien
			echo "<tr>";
				echo "<td>Kasus Ilmiah / Problem Pasien yang Pernah Didiskusikan:<br><textarea name=\"kasus\" rows=10 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_mentor[kasus]</textarea></td>";
			echo "</tr>";
			//Umpan Balik
			echo "<tr>";
				echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_mentor[umpan_balik]</textarea></td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_mentor[saran]</textarea></td>";
			echo "</tr>";
			$tanggal_approval = tanggal_indo($data_mentor[tgl_approval]);
			echo "<tr><td align=right><br><i>Status: <b>DISETUJUI</b><br>pada tanggal $tanggal_approval<br>";
			echo "Dosen Penilai (Mentor)<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
			echo "</td></tr>";
			echo "</table><br>";

			echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
			echo "<br><br></form></fieldset>";

			if ($_POST[back]=="BACK")
			{
				if ($_COOKIE['level']==5)
				echo "
						<script>
						window.location.href=\"penilaian_bedah.php\";
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
						window.location.href=\"penilaian_bedah_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
