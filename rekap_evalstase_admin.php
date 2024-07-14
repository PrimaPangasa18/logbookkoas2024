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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
	  if ($_COOKIE['level']==2) {include "menu2.php";}
	  if ($_COOKIE['level']==3) {include "menu3.php";}

		echo "<div class=\"text_header\" id=\"top\">REKAP INDIVIDU EVALUASI</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP INDIVIDU EVALUASI KEPANITERAAN (STASE)</font></h4><br>";

		$mhsw_nim = $_GET['nim'];
		$id_stase = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$mhsw_nim'"));
		$biodata_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$mhsw_nim'"));
		$data_eval_mhsw = mysqli_query($con,"SELECT * FROM `evaluasi` WHERE `nim`='$mhsw_nim' AND `stase`='$id_stase' ORDER BY `tanggal`");
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		echo "<table style=\"border:1;\">";
			echo "<tr><td>Nama Mahasiswa</td><td>: $biodata_mhsw[nama]</td></tr>";
			echo "<tr><td>NIM</td><td>: $biodata_mhsw[nim]</td></tr>";
			echo "<tr><td>Angkatan Koas</td><td>: $biodata_mhsw[angkatan]</td></tr>";
			echo "<tr><td>Kepaniteraan (stase)</td><td>: $data_stase[kepaniteraan]</td></tr>";
			$tgl_mulai = tanggal_indo($data_stase_mhsw[tgl_mulai]);
			$tgl_selesai = tanggal_indo($data_stase_mhsw[tgl_selesai]);
			echo "<tr><td>Tanggal mulai kepaniteraan (stase)</td><td>: $tgl_mulai</td></tr>";
			echo "<tr><td>Tanggal selesai kepaniteraan (stase)</td><td>: $tgl_selesai</td></tr>";
			echo "<tr><td>Status pengisian log book</td>";
			switch ($data_stase_mhsw[status])
			{
				case '0':
					{
						$status = "BELUM AKTIF/DILAKSANAKAN";
						echo "<td>: <font style=\"color:blue\">$status</font></td></tr>";
					}
				break;
				case '1':
					{
						$status = "SEDANG BERJALAN (AKTIF)";
						echo "<td>: <font style=\"color:green\">$status</font></td></tr>";
					}
				break;
				case '2':
					{
						$status = "SUDAH SELESAI/TERLEWATI";
						echo "<td>: <font style=\"color:red\">$status</font></td></tr>";
					}
				break;
			}

		echo "</table><br><br>";

		echo "<table style=\"width:100%;\">";
		echo "<thead>";
			echo "<th style=\"width:5%;\">No</th>";
			echo "<th style=\"width:15%;\">Tanggal</th>";
			echo "<th style=\"width:40%;\">Evaluasi</th>";
			echo "<th style=\"width:40%;\">Rencana Hari Berikutnya</th>";
		echo "</thead>";
		$no=1;
		$kelas="ganjil";
		while ($data=mysqli_fetch_array($data_eval_mhsw))
		{
			$tanggalisi=tanggal_indo($data[tanggal]);
			echo "<tr class=\"$kelas\">";
				echo "<td>$no</td>";
				echo "<td>$tanggalisi</td>";
				echo "<td>$data[evaluasi]</td>";
				echo "<td>$data[rencana]</td>";
			echo "</tr>";
			$no++;
			if ($kelas=="ganjil") $kelas="genap";
			else $kelas="ganjil";
		}

		echo "</table><br><br>";
		echo "<center><a href=\"#top\"><i>Goto top</i></a></center><br><br>";

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
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
<script>
  $(document).ready(function(){
	   $("#freeze").freezeHeader();
		 $("#freeze1").freezeHeader();
  });
</script>



<!--</body></html>-->
</BODY>
</HTML>
