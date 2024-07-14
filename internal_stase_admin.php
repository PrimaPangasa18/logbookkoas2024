<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
	<meta name="viewport" content="width=device-width, maximum-scale=1">
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
		if ($_COOKIE['level']==2) {include "menu2.php";}
		if ($_COOKIE['level']==3) {include "menu3.php";}

		echo "<div class=\"text_header\">ROTASI INTERNAL KEPANITERAAN (STASE)</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI INTERNAL KEPANITERAAN (STASE)</font></h4>";

		$id_stase = $_GET['id'];
		$nim = $_GET['mhsw'];
		$id_internal = "internal_".$id_stase;
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$nim'");
		$jml_stase_mhsw = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `$stase_id` WHERE `nim`='$nim'"));
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim'"));

		echo "<table style=\"border:collapse;\">";
			echo "<tr><td>Nama Mahasiswa</td><td>: $data_mhsw[nama] (NIM: $data_mhsw[nim])</td></tr>";
			echo "<tr><td>Kepaniteraan (stase)</td><td>: $data_stase[kepaniteraan]</td></tr>";
			if ($jml_stase_mhsw>0)
			{
				$tgl_mulai = tanggal_indo($datastase_mhsw[tgl_mulai]);
				$tgl_selesai = tanggal_indo($datastase_mhsw[tgl_selesai]);
			}
			else {
				$tgl_mulai = "BELUM TERJADWAL";
				$tgl_selesai = "BELUM TERJADWAL";
			}
			echo "<tr><td>Tanggal mulai kepaniteraan (stase)</td><td>: $tgl_mulai</td></tr>";
			echo "<tr><td>Tanggal selesai kepaniteraan (stase)</td><td>: $tgl_selesai</td></tr>";
		echo "</table><br>";

		$cek_internal = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `$id_internal` WHERE `nim`='$nim'"));
		if ($cek_internal==0)
		{
			echo "<table style=\"width:100%\">";
			echo "<thead>";
			echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:20%\">Rotasi Internal</th>";
			echo "<th style=\"width:15%\">Lama Pelaksanaan</th>";
			echo "<th style=\"width:15%\">Tanggal Mulai</th>";
			echo "<th style=\"width:15%\">Tanggal Selesai</th>";
			echo "<th style=\"width:35%\">Status Approval</th>";
			echo "</thead>";
			echo "<tr>";
			echo "<td colspan=6 align=center style=\"padding:10px\">";
			echo "<font style=\"color:red\">Status rotasi internal stase belum aktif!!!</font>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
		}
		else {
			echo "<table style=\"width:100%\">";
			echo "<thead>";
			echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:20%\">Rotasi Internal</th>";
			echo "<th style=\"width:15%\">Lama Pelaksanaan</th>";
			echo "<th style=\"width:15%\">Tanggal Mulai</th>";
			echo "<th style=\"width:15%\">Tanggal Selesai</th>";
			echo "<th style=\"width:35%\">Status Approval</th>";
			echo "</thead>";

			$rotasi_internal = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$id_internal` WHERE `nim`='$nim'"));
			$rotasi = mysqli_query($con,"SELECT * FROM `rotasi_internal` WHERE `stase`='$id_stase' ORDER BY `id`");
			$i=1;
			$kelas="ganjil";
			while ($data=mysqli_fetch_array($rotasi))
			{
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$i</td>";
				echo "<td>$data[internal]</td>";
				echo "<td align=center>$data[hari] hari</td>";
				$tgl_i = "tgl".$i;
				if (!is_null($rotasi_internal[$tgl_i]))
				{
					$tgl_mulai = tanggal_indo($rotasi_internal[$tgl_i]);
					echo "<td align=center>$tgl_mulai</td>";

					$hari_tambah = $data[hari]-1;
					$tambah_hari = '+'.$hari_tambah.' days';
					$tglselesai = date('Y-m-d', strtotime($tambah_hari, strtotime($rotasi_internal[$tgl_i])));
					$tgl_selesai = tanggal_indo($tglselesai);
					echo "<td align=center>$tgl_selesai</td>";
				}
				else
				{
					echo "<td align=center><font style=\"color:red\"><i><< empty >></i></font></td>";
					echo "<td align=center><font style=\"color:red\"><i><< empty >></i></font></td>";
				}
				$id_status="status".$i;
				$id_dosen="dosen".$i;
				echo "<td align=center>";
				if ($rotasi_internal[$id_status]==0) echo "<font style=\"color:red\">Unapproved</font>";
				else echo "<font style=\"color:green\">Approved</font>";
				if (!is_null($rotasi_internal[$id_dosen]))
				{
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$rotasi_internal[$id_dosen]'"));
					echo "<br><br>Approval oleh<br><br>$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
				}
				else {
					echo "<br><br>Approval oleh<br><br><font style=\"color:red\"><< BELUM DIPILIH >></font>";
				}
				echo "</td>";
				echo "</tr>";
				$i++;
				if ($kelas=="ganjil") $kelas="genap"; else $kelas="ganjil";
			}
		}
		echo "<br><br></fieldset>";
	}
		else
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
?>
<script type="text/javascript" src="jquery.min.js"></script>



<!--</body></html>-->
</BODY>
</HTML>
