<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
<!--</head>-->
</head>
<BODY>

<?php

	include "config.php";
	include "fungsi.php";

	error_reporting("E_ALL ^ E_NOTICE");

	if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
	else{
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==1)
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}

		echo "<div class=\"text_header\">ROTASI INDIVIDU KEPANITERAAN (STASE)</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	   	<legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI INDIVIDU KEPANITERAAN (STASE)</font></h4><br>";
		$nim_mhsw = $_GET[user_name];

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

		echo "<table>";

		//Semester IX
		echo "<tr class=\"ganjil\">";
		echo "<td colspan=\"5\"><b>SEMESETER IX (SEMBILAN)</b></td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
		echo "<th align=\"center\" style=\"width:20px\">Urutan</th>";
		echo "<th align=\"center\" style=\"width:500px\">Kepaniteraan (Stase)</th>";
		echo "<th align=\"center\" style=\"width:150px\">Tanggal Mulai</th>";
		echo "<th align=\"center\" style=\"width:150px\">Tanggal Selesai</th>";
		echo "<th align=\"center\" style=\"width:150px\">Status</th>";
		echo "</tr>";

		$stase_ix = mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `semester`='9' ORDER BY `id` ASC");
		$no = 1;
		while ($data_ix = mysqli_fetch_array($stase_ix))
		{
			$stase_id = "stase_".$data_ix[id];
			$data_stase = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
			$jml_jadwal = mysqli_num_rows($data_stase);
			echo "<tr class=\"genap\">";
			if ($jml_jadwal>0)
			{
				$stase = mysqli_fetch_array($data_stase);
				echo "<td align=\"center\">$no</td>";
				echo "<td>$data_ix[kepaniteraan]</td>";
				$tgl_mulai = tanggal_indo($stase[tgl_mulai]);
				echo "<td>$tgl_mulai</td>";
				$tgl_selesai = tanggal_indo($stase[tgl_selesai]);
				echo "<td>$tgl_selesai</td>";
				if ($stase[status]=="0")
				{
					echo "<td><font style=\"color:blue\">Belum Aktif</font><br>";
					echo "<a href=\"rotasi_delete_ind.php?nim=$nim_mhsw&stase=$data_ix[id]\"><input type=\"button\" name=\"delete\" style=\"color:red\" value=\"DELETE\"></a></td>";
				}
				if ($stase[status]=="1") echo "<td><font style=\"color:green\">Aktif</font></td>";
				if ($stase[status]=="2") echo "<td><font style=\"color:red\">Sudah Lewat</font></td>";
			}
			else
			{
				echo "<td align=\"center\">$no</td>";
				echo "<td>$data_ix[kepaniteraan]</td>";
				echo "<td colspan=3 align=\"center\"><font style=\"color:red\"><< Belum dijadwalkan >></font></td>";
			}
			echo "</tr>";
			$no++;
		}
		echo "<tr class=\"ganjil\"><td colspan=\"5\">&nbsp;</td></tr>";

		//Semester X
		echo "<tr class=\"ganjil\">";
		echo "<td colspan=\"5\"><b>SEMESETER X (SEPULUH)</b></td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
		echo "<th align=\"center\" style=\"width:20px\">Urutan</th>";
		echo "<th align=\"center\" style=\"width:500px\">Kepaniteraan (Stase)</th>";
		echo "<th align=\"center\" style=\"width:150px\">Tanggal Mulai</th>";
		echo "<th align=\"center\" style=\"width:150px\">Tanggal Selesai</th>";
		echo "<th align=\"center\" style=\"width:150px\">Status</th>";
		echo "</tr>";

		$stase_x = mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `semester`='10' ORDER BY `id` ASC");
		$no = 1;
		while ($data_x = mysqli_fetch_array($stase_x))
		{
			$stase_id = "stase_".$data_x[id];
			$data_stase = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
			$jml_jadwal = mysqli_num_rows($data_stase);
			echo "<tr class=\"genap\">";
			if ($jml_jadwal>0)
			{
				$stase = mysqli_fetch_array($data_stase);
				echo "<td align=\"center\">$no</td>";
				echo "<td>$data_x[kepaniteraan]</td>";
				$tgl_mulai = tanggal_indo($stase[tgl_mulai]);
				echo "<td>$tgl_mulai</td>";
				$tgl_selesai = tanggal_indo($stase[tgl_selesai]);
				echo "<td>$tgl_selesai</td>";
				if ($stase[status]=="0")
				{
					echo "<td><font style=\"color:blue\">Belum Aktif</font><br>";
					echo "<a href=\"rotasi_delete_ind.php?nim=$nim_mhsw&stase=$data_x[id]\"><input type=\"button\" name=\"delete\" style=\"color:red\" value=\"DELETE\"></a></td>";
				}
				if ($stase[status]=="1") echo "<td><font style=\"color:green\">Aktif</font></td>";
				if ($stase[status]=="2") echo "<td><font style=\"color:red\">Sudah Lewat</font></td>";
			}
			else
			{
				echo "<td align=\"center\">$no</td>";
				echo "<td>$data_x[kepaniteraan]</td>";
				echo "<td colspan=3 align=\"center\"><font style=\"color:red\"><< Belum dijadwalkan >></font></td>";
			}
			echo "</tr>";
			$no++;
		}
		echo "<tr class=\"ganjil\"><td colspan=\"5\">&nbsp;</td></tr>";

		//Semester XI
		echo "<tr class=\"ganjil\">";
		echo "<td colspan=\"5\"><b>SEMESETER XI (SEBELAS)</b></td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
		echo "<th align=\"center\" style=\"width:20px\">Urutan</th>";
		echo "<th align=\"center\" style=\"width:500px\">Kepaniteraan (Stase)</th>";
		echo "<th align=\"center\" style=\"width:150px\">Tanggal Mulai</th>";
		echo "<th align=\"center\" style=\"width:150px\">Tanggal Selesai</th>";
		echo "<th align=\"center\" style=\"width:150px\">Status</th>";
		echo "</tr>";

		$stase_xi = mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `semester`='11' ORDER BY `id` ASC");
		$no = 1;
		while ($data_xi = mysqli_fetch_array($stase_xi))
		{
			$stase_id = "stase_".$data_xi[id];
			$data_stase = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
			$jml_jadwal = mysqli_num_rows($data_stase);
			echo "<tr class=\"genap\">";
			if ($jml_jadwal>0)
			{
				$stase = mysqli_fetch_array($data_stase);
				echo "<td align=\"center\">$no</td>";
				echo "<td>$data_xi[kepaniteraan]</td>";
				$tgl_mulai = tanggal_indo($stase[tgl_mulai]);
				echo "<td>$tgl_mulai</td>";
				$tgl_selesai = tanggal_indo($stase[tgl_selesai]);
				echo "<td>$tgl_selesai</td>";
				if ($stase[status]=="0")
				{
					echo "<td><font style=\"color:blue\">Belum Aktif</font><br>";
					echo "<a href=\"rotasi_delete_ind.php?nim=$nim_mhsw&stase=$data_xi[id]\"><input type=\"button\" name=\"delete\" style=\"color:red\" value=\"DELETE\"></a></td>";
				}
				if ($stase[status]=="1") echo "<td><font style=\"color:green\">Aktif</font></td>";
				if ($stase[status]=="2") echo "<td><font style=\"color:red\">Sudah Lewat</font></td>";
			}
			else
			{
				echo "<td align=\"center\">$no</td>";
				echo "<td>$data_xi[kepaniteraan]</td>";
				echo "<td colspan=3 align=\"center\"><font style=\"color:red\"><< Belum dijadwalkan >></font></td>";
			}
			echo "</tr>";
			$no++;
		}
		echo "<tr class=\"ganjil\"><td colspan=\"5\">&nbsp;</td></tr>";

		//Semester XII
		echo "<tr class=\"ganjil\">";
		echo "<td colspan=\"5\"><b>SEMESETER XII (DUA BELAS)</b></td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
		echo "<th align=\"center\" style=\"width:20px\">Urutan</th>";
		echo "<th align=\"center\" style=\"width:500px\">Kepaniteraan (Stase)</th>";
		echo "<th align=\"center\" style=\"width:150px\">Tanggal Mulai</th>";
		echo "<th align=\"center\" style=\"width:150px\">Tanggal Selesai</th>";
		echo "<th align=\"center\" style=\"width:150px\">Status</th>";
		echo "</tr>";

		$stase_xii = mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `semester`='12' ORDER BY `id` ASC");
		$no = 1;
		while ($data_xii = mysqli_fetch_array($stase_xii))
		{
			$stase_id = "stase_".$data_xii[id];
			$data_stase = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
			$jml_jadwal = mysqli_num_rows($data_stase);
			echo "<tr class=\"genap\">";
			if ($jml_jadwal>0)
			{
				$stase = mysqli_fetch_array($data_stase);
				echo "<td align=\"center\">$no</td>";
				echo "<td>$data_xii[kepaniteraan]</td>";
				$tgl_mulai = tanggal_indo($stase[tgl_mulai]);
				echo "<td>$tgl_mulai</td>";
				$tgl_selesai = tanggal_indo($stase[tgl_selesai]);
				echo "<td>$tgl_selesai</td>";
				if ($stase[status]=="0")
				{
					echo "<td><font style=\"color:blue\">Belum Aktif</font><br>";
					echo "<a href=\"rotasi_delete_ind.php?nim=$nim_mhsw&stase=$data_xii[id]\"><input type=\"button\" name=\"delete\" style=\"color:red\" value=\"DELETE\"></a></td>";
				}
				if ($stase[status]=="1") echo "<td><font style=\"color:green\">Aktif</font></td>";
				if ($stase[status]=="2") echo "<td><font style=\"color:red\">Sudah Lewat</font></td>";
			}
			else
			{
				echo "<td align=\"center\">$no</td>";
				echo "<td>$data_xii[kepaniteraan]</td>";
				echo "<td colspan=3 align=\"center\"><font style=\"color:red\"><< Belum dijadwalkan >></font></td>";
			}
			echo "</tr>";
			$no++;
		}
		echo "<tr class=\"ganjil\"><td colspan=\"5\">&nbsp;</td></tr>";

		echo "</table><br>";
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
