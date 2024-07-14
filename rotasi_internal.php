<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
<!--</head>-->
</head>
<BODY>

<?php
	error_reporting("E_ALL ^ E_NOTICE");

	include "config.php";
	include "fungsi.php";
	if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
	else{
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
	  if ($_COOKIE['level']==2) {include "menu2.php";}
	  if ($_COOKIE['level']==3) {include "menu3.php";}
	  if ($_COOKIE['level']==4) {include "menu4.php";}
		if ($_COOKIE['level']==5) {include "menu5.php";}

		if ($_COOKIE['level']==5)
		{
			echo "<div class=\"text_header\">ROTASI KEPANITERAAN (STASE)</div>";
			echo "<br><br><br><fieldset class=\"fieldset_art\">
	    	<legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI KEPANITERAAN (STASE)</font></h4><br>";
			$nim_mhsw = $_COOKIE[user];
		}
		else
		{
			echo "<div class=\"text_header\">ROTASI INDIVIDU KEPANITERAAN (STASE)</div>";
			echo "<br><br><br><fieldset class=\"fieldset_art\">
	    	<legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI INDIVIDU KEPANITERAAN (STASE)</font></h4><br>";
			$nim_mhsw = $_GET[user_name];
			//Informasi data mahasiswa
			$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw'"));
			echo "<table>";
			echo "<tr>";
				echo "<td style=\"width:150px\">Nama Mahasiswa</td>";
				echo "<td style=\"width:400px\">: $data_mhsw[nama]</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>NIM</td>";
				echo "<td>: $data_mhsw[nim]</td>";
			echo "</tr>";
			echo "</table><br><br>";
		}

		$semester = mysqli_query($con,"SELECT DISTINCT `semester` FROM `kepaniteraan` ORDER BY `semester`");
		echo "<table style=\"width:100%\">";
		echo "<tr class=\"ganjil\">";
			echo "<td colspan=\"6\"><i>Catatan Penting:<br>";
			echo "- Evaluasi akhir kepaniteraan (stase) wajib diisi sebagai syarat pemrosesan Grade Ketuntasan tiap kepaniteraan (stase)<i>";
		echo "</td></tr>";
		echo "<tr class=\"genap\"><td colspan=\"6\">&nbsp;</td></tr>";
		while ($data_smt=mysqli_fetch_array($semester))
		{
			//Semester i
			echo "<tr class=\"ganjil\">";
				echo "<td colspan=\"6\"><b>SEMESTER $data_smt[semester]</b></td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
				echo "<th align=\"center\" style=\"width:10%\">Urutan</th>";
				echo "<th align=\"center\" style=\"width:30%\">Kepaniteraan (Stase)</th>";
				echo "<th align=\"center\" style=\"width:15%\">Tanggal Mulai</th>";
				echo "<th align=\"center\" style=\"width:15%\">Tanggal Selesai</th>";
				echo "<th align=\"center\" style=\"width:15%\">Status</th>";
				echo "<th align=\"center\" style=\"width:15%\">Evaluasi</th>";
			echo "</tr>";
			$stase_smt = mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `semester`= '$data_smt[semester]' ORDER BY `id`");
			$hapus_dummy = mysqli_query($con,"DELETE FROM `dummy_rotasi` WHERE `username`='$_COOKIE[user]'");
			while ($data_stase= mysqli_fetch_array($stase_smt))
			{
				$stase_id = "stase_".$data_stase[id];
				$urutan_stase= mysqli_query($con,"SELECT `rotasi` FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
				$jml_mhsw = mysqli_num_rows($urutan_stase);
				$urutan_rotasi = mysqli_fetch_array($urutan_stase);
				if ($jml_mhsw==0) $urutan = 8; else $urutan = $urutan_rotasi[rotasi];
				$insert_dummy = mysqli_query($con,"INSERT INTO `dummy_rotasi`
					(`id`, `urutan`,`username`)
					VALUES
					('$data_stase[id]','$urutan','$_COOKIE[user]')");
			}
			$rotasi_stase = mysqli_query($con,"SELECT * FROM `dummy_rotasi` ORDER BY `urutan`,`id`");
			$no = 1;
			while ($data_rotasi=mysqli_fetch_array($rotasi_stase))
			{
				$id_stase = $data_rotasi[id];
				$stase_id = "stase_".$data_rotasi[id];
				$nama_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$data_rotasi[id]'"));
				echo "<tr class=\"genap\">";
				if ($data_rotasi[urutan]==9) echo "<td align=\"center\">#</td>";
				else echo "<td align=\"center\">$no</td>";
				if ($data_rotasi[urutan]==8)
				{
					echo "<td>$nama_stase[kepaniteraan]</td>";
					echo "<td colspan=4><font style=\"color:red\"><i><< BELUM DIJADWALKAN ATAU DIJADWAL ULANG >></i></font></td>";
				}
				else
				{
					echo "<td>$nama_stase[kepaniteraan]</td>";
					$datastase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'"));
					$tgl_mulai = tanggal_indo($datastase[tgl_mulai]);
					$tgl_selesai = tanggal_indo($datastase[tgl_selesai]);
					echo "<td align=\"center\">$tgl_mulai</td>";
					echo "<td align=\"center\">$tgl_selesai</td>";
					if ($datastase[status]=="0") echo "<td><font style=\"color:blue\">Belum Aktif</font></td>";
					if ($datastase[status]=="1") echo "<td><font style=\"color:green\">Aktif</font></td>";
					if ($datastase[status]=="2") echo "<td><font style=\"color:red\">Sudah Lewat</font></td>";
					if ($_COOKIE[level]=="5")
					{
						if ($datastase[evaluasi]=="0")
						{
							echo "<td><font style=\"color:red\">Belum &nbsp;</font>";
							echo "<a href=\"isi_evaluasi.php?id=$id_stase\"><input type=\"button\" name=\"entry_$id_stase\" value=\"ENTRY\"></a>";
							echo "</td>";
						}
						else {
							echo "<td><font style=\"color:green\">Sudah &nbsp;</font>";
							echo "<a href=\"edit_evaluasi.php?id=$id_stase\"><input type=\"button\" name=\"edit_$id_stase\" value=\"EDIT\"></a>";
							echo "</td>";
						}
					}
					else {
						if ($datastase[evaluasi]=="0")
						{
							echo "<td><font style=\"color:red\">Belum</font>";
							echo "</td>";
						}
						else {
							echo "<td><font style=\"color:green\">Sudah &nbsp;</font>";
							echo "<a href=\"lihat_evaluasi.php?id=$id_stase&nim=$nim_mhsw&menu=rotasi\"><input type=\"button\" name=\"lihat_$id_stase\" value=\"LIHAT\"></a>";
							echo "</td>";
						}
					}
				}
				echo "</tr>";
				$no++;
			}
			echo "<tr class=\"ganjil\"><td colspan=\"6\">&nbsp;</td></tr>";
		}

		echo "</table><br><br>";
		$hapus_dummy = mysqli_query($con,"DELETE FROM `dummy_rotasi` WHERE `username`='$_COOKIE[user]'");

		echo "</fieldset>";

	}
		else
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}

?>



<!--</body></html>-->
</BODY>
</HTML>
