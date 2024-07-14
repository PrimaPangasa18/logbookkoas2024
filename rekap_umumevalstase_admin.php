<HTML>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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

		echo "<div class=\"text_header\" id=\"top\">REKAP UMUM EVALUASI</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP UMUM EVALUASI KEPANITERAAN (STASE)</font></h4>";

		$grup_filter = $_GET[grup];
		$stase_filter = $_GET[stase];
		$angkatan_filter = $_GET[angk];
		$tgl_awal = $_GET[tglawal];
		$tgl_akhir = $_GET[tglakhir];
		$stase_id = "stase_".$stase_filter;
		$target_id = "target_".$stase_filter;
		$include_id = "include_".$stase_filter;

		$filterstase = "`stase`="."'$stase_filter'";
		$filtertgl = " AND (`tanggal`>="."'$tgl_awal'"." AND `tanggal`<="."'$tgl_akhir')";

		$hari_kurang = 28-1;
		$kurang_hari = '-'.$hari_kurang.' days';
		$tgl_batas = date('Y-m-d', strtotime($kurang_hari, strtotime($tgl_akhir)));

		if ($grup_filter=="1") $filtergrup = "`tgl_mulai`<="."'$tgl_batas'";
		if ($grup_filter=="2") $filtergrup = "`tgl_mulai`>"."'$tgl_batas'"." AND `tgl_mulai`<="."'$tgl_akhir'";
		if ($grup_filter=="all") $filtergrup = "`tgl_mulai`<="."'$tgl_akhir'";

		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$stase_filter'"));
		$kepaniteraan = $data_stase[kepaniteraan];
		$filter = $filterstase.$filtertgl;

		$mhsw = mysqli_query($con,"SELECT `nim` FROM `$stase_id` WHERE $filtergrup");
		$i = 1;
		while ($data_mhsw=mysqli_fetch_array($mhsw))
		{
			//cek Angkatan
			$angkatan = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
			if ($angkatan_filter==$angkatan[angkatan] OR $angkatan_filter=="all")
			{
				$mhsw_nim[$i] = $data_mhsw[nim];
				$i++;
			}
		}
		$jml_mhsw = $i-1;

		//--------------------
		echo "<table style=\"width:50%\">";
		echo "<tr>";
		echo "<td style=\"width:30%\">Kepaniteraan (Stase)</td><td style=\"width:70%\">: $data_stase[kepaniteraan]</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Angkatan</td>";
		if ($angkatan_filter=="all") echo "<td>: Semua Angkatan</td>";
		else echo "<td>: Angkatan $angkatan_filter</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Grup</td>";
		if ($grup_filter=="all") echo "<td>: Semua</td>";
		else
		{
			if ($grup_filter=='1') echo "<td>: Senior</td>";
			else echo "<td>: Yunior</td>";
		}
		echo "</tr>";
		echo "<tr>";
		echo "<td>Jumlah Mahasiswa</td><td>: $jml_mhsw orang</td>";
		echo "</tr>";
		$tglawal=tanggal_indo($tgl_awal);
		$tglakhir=tanggal_indo($tgl_akhir);
		echo "<tr>";
		echo "<td>Periode Kegiatan</td><td>: $tglawal s.d $tglakhir</td>";
		echo "</tr>";
		echo "</table><br><br>";
		//------------------

		echo "<table id=\"freeze\" style=\"width:100%;\">";
		echo "<thead>";
			echo "<th style=\"width:5%;\">No</th>";
			echo "<th style=\"width:12%;\">Tanggal</th>";
			echo "<th style=\"width:21%;\">Nama Mahasiswa (NIM)<br>Kepaniteraan/Stase</th>";
			echo "<th style=\"width:30%;\">Evaluasi</th>";
			echo "<th style=\"width:30%;\">Rencana Hari Berikutnya</th>";
		echo "</thead>";

		$kelas="ganjil";
		$no_i=1;
		for ($no=1;$no<=$jml_mhsw;$no++)
		{
			$eval = mysqli_query($con,"SELECT * FROM `evaluasi` WHERE $filter AND `nim`='$mhsw_nim[$no]' ORDER BY `tanggal`");
			while ($data=mysqli_fetch_array($eval))
			{
				$tanggalisi=tanggal_indo($data[tanggal]);
				echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no_i</td>";
					echo "<td>$tanggalisi</td>";
					$mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$mhsw_nim[$no]'"));
					$nama_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$data[stase]'"));
					echo "<td>$mhsw[nama] ($mhsw_nim[$no])<br><br><i>Kepaniteraan/Stase:</i><br><b>$nama_stase[kepaniteraan]</b></td>";
					echo "<td>$data[evaluasi]</td>";
					echo "<td>$data[rencana]</td>";
				echo "</tr>";
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
				$no_i++;
			}
		}
		if ($no_i==1) echo "<tr><td colspan=5 align=center><< E M P T Y >></td></tr>";
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
  });
</script>



<!--</body></html>-->
</BODY>
</HTML>
