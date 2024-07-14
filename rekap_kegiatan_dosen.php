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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE[level]==2 OR $_COOKIE[level]==4 OR $_COOKIE[level]==6))
	{
			if ($_COOKIE['level']==2) {include "menu2.php";}
			if ($_COOKIE['level']==4) {include "menu4.php";}
			if ($_COOKIE['level']==6) {include "menu6.php";}

		echo "<div class=\"text_header\" id=\"top\">REKAP KEGIATAN DOSEN/RESIDEN</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP KEGIATAN DOSEN/RESIDEN</font></h4>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		if ($_COOKIE['level']=="2") $dosen=$_GET[dosen];
		else $dosen=$_COOKIE[user];
		$filter_dosen="`dosen`='".$dosen."'";
		$stase=$_GET[stase];
		if ($stase=="all") $filter_stase="";
		else $filter_stase=" AND `stase`='".$stase."'";
		$appstatus=$_GET[appstatus];
		if ($appstatus=="all") $filter_status="";
		else $filter_status=" AND `status`='".$appstatus."'";
		$tgl_mulai=$_GET[tgl_mulai];
		$tgl_akhir=$_GET[tgl_akhir];
		$filter_tgl=" AND `tanggal` between '".$tgl_mulai."' AND '".$tgl_akhir."'";

		echo "<table>";
			echo "<tr>";
			echo "<td>Nama Dosen/Residen</td>";
			$data_dosen=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$dosen'"));
			echo "<td>: $data_dosen[nama], $data_dosen[gelar]";
			echo "</tr>";
			echo "<tr>";
			echo "<td>Username Dosen/Residen</td>";
			echo "<td>: $dosen";
			echo "</tr>";
			echo "<tr>";
			echo "<td>Kepaniteraan (Stase)</td>";
			if ($stase!="all")
			{
				$nama_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$stase'"));
				echo "<td>: $nama_stase[kepaniteraan]";
			}
			else echo "<td>: Semua kepaniteraan (stase)";
			echo "</tr>";
			echo "<tr>";
			echo "<td>Periode Tanggal</td>";
			$tanggal_mulai = tanggal_indo($tgl_mulai);
			$tanggal_akhir = tanggal_indo($tgl_akhir);
			echo "<td>: $tanggal_mulai s.d. $tanggal_akhir";
			echo "</tr>";
			echo "<tr>";
		echo "</table><br>";

		echo "</center><table class=\"tabel_normal\" style=\"width:60%\">";
		echo "<tr>";
		echo "<td>";
		echo "Rekap Kegiatan Jurnal Penyakit:<br>";
		echo "1. <a href=\"#penyakit_nonbed\">Kategori Non-Bedside Teaching</a><br>";
		echo "2. <a href=\"#penyakit_bed\">Kategori Bedside Teaching</a><br>";
		echo "Rekap Kegiatan Jurnal Ketrampilan:<br>";
		echo "1. <a href=\"#ketrampilan_nonbed\">Kategori Non-Bedside Teaching</a><br>";
		echo "2. <a href=\"#ketrampilan_bed\">Kategori Bedside Teaching</a><br>";
		echo "</td>";
		echo "</tr>";
		echo "<td style=\"font-size:0.85em\">";
		echo "<i>Kelas Kegiatan:<br>";
		$kelas = mysqli_query($con,"SELECT * FROM `kelas` ORDER BY `id`");
		$no = 1;
		while ($data_kelas=mysqli_fetch_array($kelas))
		{
			echo "$no. Kelas $data_kelas[kelas]: ";
			echo "Jam mulai kegiatan [$data_kelas[jam_mulai] - $data_kelas[jam_selesai]]<br>";
			$no++;
		}
		echo "</i></td>";
		echo "</tr>";
		echo "</table>";

		echo "<center>";
		echo "<h4>Rekap Kegiatan Jurnal Penyakit</h4>";

		//Kegiatan 1: Kategori Non-Bedside Teaching
		$tgl_filter1="SELECT DISTINCT `tanggal` FROM `jurnal_penyakit` WHERE ".$filter_dosen.$filter_stase.$filter_status.$filter_tgl." AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') ORDER BY `tanggal`";
		$tgl_kegiatan1 = mysqli_query($con,$tgl_filter1);
		$jml_kegiatan1 = mysqli_num_rows($tgl_kegiatan1);
		echo "</center><div id=\"penyakit_nonbed\"><font style=\"color:brown;\">Kegiatan Jurnal Penyakit Kategori Non-Bedside Teaching</font></div><p><center>";
		echo "<table style=\"width:100%\" id=\"freeze1\">";
		echo "<thead>";
		echo "<th style=\"width:5%\">No</th>";
		echo "<th style=\"width:15%\">Tanggal</th>";
		echo "<th style=\"width:50%\">Kegiatan Dosen / Kegiatan Mhsw - Lokasi</th>";
		echo "<th style=\"width:15%\">Jml Koas</th>";
		echo "<th style=\"width:15%\">Rerata Waktu</th>";
		echo "</thead>";

		$no = 1;
		if ($jml_kegiatan1>=1)
		{
			$baris = "ganjil";
			while ($data_tgl1=mysqli_fetch_array($tgl_kegiatan1))
			{
				echo "<tr class=\"$baris\">";
				echo "<td align=center style=\"padding:10px 5px 5px 5px;\">$no</td>";
				$tgl_indo = tanggal_indo($data_tgl1[tanggal]);
				echo "<td style=\"padding:10px 5px 5px 5px;\">$tgl_indo</td>";

				$filter_kelas1="SELECT DISTINCT `kelas` FROM `jurnal_penyakit` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') ORDER BY `kelas`";
				$kelas1 = mysqli_query($con,$filter_kelas1);
				echo "<td colspan=3>";
				echo "<table class=\"tabel_normal\" style=\"width:100%;padding:0;\">";
				while ($data_kelas1=mysqli_fetch_array($kelas1))
				{
					$kelas = mysqli_fetch_array(mysqli_query($con,"SELECT `kelas` FROM `kelas` WHERE `id`='$data_kelas1[kelas]'"));
					$filter_kegiatan1 = "SELECT DISTINCT `kegiatan`,`lokasi` FROM `jurnal_penyakit` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') AND `kelas`='$data_kelas1[kelas]' ORDER BY `kegiatan`";
					$kegiatan1 = mysqli_query($con,$filter_kegiatan1);
					while ($data_kegiatan1=mysqli_fetch_array($kegiatan1))
					{
						echo "<tr>";
						$kegiatan = mysqli_fetch_array(mysqli_query($con,"SELECT `kegiatan`,`kegiatan_dosen` FROM `kegiatan` WHERE `id`='$data_kegiatan1[kegiatan]'"));
						$lokasi = mysqli_fetch_array(mysqli_query($con,"SELECT `lokasi` FROM `lokasi` WHERE `id`='$data_kegiatan1[lokasi]'"));
						echo "<td class=\"td_normal\" style=\"width:62.5%;\"><b>$kegiatan[kegiatan_dosen] - Kelas $kelas[kelas]</b><br>";
						echo "<i>Kegiatan Mahasiswa:</i> $kegiatan[kegiatan].<br>";
						echo "<i>Lokasi:</i> $lokasi[lokasi].</td>";
						$filter_koas="SELECT `nim`,`jam_awal`,`jam_akhir` FROM `jurnal_penyakit` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND `kegiatan`='$data_kegiatan1[kegiatan]' AND `kelas`='$data_kelas1[kelas]'";
						$koas = mysqli_query($con,$filter_koas);
						$jml_koas = mysqli_num_rows($koas);
						echo "<td class=\"td_normal\" align=center style=\"width:18.75%;\">$jml_koas mhsw</td>";
						$tot_waktu=0;
						while ($data_koas=mysqli_fetch_array($koas))
						{
							$waktu_awal=strtotime($data_koas[jam_awal]);
							$waktu_akhir=strtotime($data_koas[jam_akhir]);
							$waktu=abs($waktu_akhir-$waktu_awal);
							$tot_waktu=$tot_waktu+$waktu;
						}
						$jam=number_format(floor($tot_waktu / (60 * 60)),2);
						$menit=number_format(floor(($tot_waktu - $jam * (60 * 60))/60),2);
						$rata_waktu = floor($tot_waktu/$jml_koas);
						$rata_jam=number_format(floor($rata_waktu / (60 * 60)),2);
						$rata_menit=number_format($rata_waktu/60,0);
						echo "<td class=\"td_normal\" style=\"width:18.75%;\">$rata_menit menit</td>";
						echo "</tr>";
					}
				}
				echo "</table>";
				echo "</td>";
				$no++;
				if ($baris=="ganjil") $baris="genap";
				else $baris="ganjil";
				echo "</tr>";
			}
		}
		else {
			echo "<tr>";
			echo "<td colspan=5 align=center>";
			echo "<< E M P T Y >></td>";
			echo "</tr>";
		}
		echo "</table><br><br>";
		echo "<center><a href=\"#top\"><i>Goto top</i></a></center><br><br>";

		//Kegiatan 2: Kategori Bedside Teaching
		$tgl_filter1="SELECT DISTINCT `tanggal` FROM `jurnal_penyakit` WHERE ".$filter_dosen.$filter_stase.$filter_status.$filter_tgl." AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') ORDER BY `tanggal`";
		$tgl_kegiatan1 = mysqli_query($con,$tgl_filter1);
		$jml_kegiatan1 = mysqli_num_rows($tgl_kegiatan1);
		echo "</center><div id=\"penyakit_bed\"><font style=\"color:brown;\">Kegiatan Jurnal Penyakit Kategori Bedside Teaching</font></div><p><center>";
		echo "<table style=\"width:100%\" id=\"freeze2\">";
		echo "<thead>";
		echo "<th style=\"width:5%\">No</th>";
		echo "<th style=\"width:15%\">Tanggal</th>";
		echo "<th style=\"width:50%\">Kegiatan Dosen / Kegiatan Mhsw - Lokasi</th>";
		echo "<th style=\"width:15%\">Jml Koas</th>";
		echo "<th style=\"width:15%\">Rerata Waktu</th>";
		echo "</thead>";

		$no = 1;
		if ($jml_kegiatan1>=1)
		{
			$baris = "ganjil";
			while ($data_tgl1=mysqli_fetch_array($tgl_kegiatan1))
			{
				echo "<tr class=\"$baris\">";
				echo "<td align=center style=\"padding:10px 5px 5px 5px;\">$no</td>";
				$tgl_indo = tanggal_indo($data_tgl1[tanggal]);
				echo "<td style=\"padding:10px 5px 5px 5px;\">$tgl_indo</td>";

				$filter_kelas1="SELECT DISTINCT `kelas` FROM `jurnal_penyakit` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') ORDER BY `kelas`";
				$kelas1 = mysqli_query($con,$filter_kelas1);
				echo "<td colspan=3>";
				echo "<table class=\"tabel_normal\" style=\"width:100%;padding:0;\">";
				while ($data_kelas1=mysqli_fetch_array($kelas1))
				{
					$kelas = mysqli_fetch_array(mysqli_query($con,"SELECT `kelas` FROM `kelas` WHERE `id`='$data_kelas1[kelas]'"));
					$filter_kegiatan1 = "SELECT DISTINCT `kegiatan`,`lokasi` FROM `jurnal_penyakit` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') AND `kelas`='$data_kelas1[kelas]' ORDER BY `kegiatan`";
					$kegiatan1 = mysqli_query($con,$filter_kegiatan1);
					while ($data_kegiatan1=mysqli_fetch_array($kegiatan1))
					{
						echo "<tr>";
						$kegiatan = mysqli_fetch_array(mysqli_query($con,"SELECT `kegiatan`,`kegiatan_dosen` FROM `kegiatan` WHERE `id`='$data_kegiatan1[kegiatan]'"));
						$lokasi = mysqli_fetch_array(mysqli_query($con,"SELECT `lokasi` FROM `lokasi` WHERE `id`='$data_kegiatan1[lokasi]'"));
						echo "<td class=\"td_normal\" style=\"width:62.5%;\"><b>$kegiatan[kegiatan_dosen] - Kelas $kelas[kelas]</b><br>";
						echo "<i>Kegiatan Mahasiswa:</i> $kegiatan[kegiatan].<br>";
						echo "<i>Lokasi:</i> $lokasi[lokasi].</td>";
						$filter_koas="SELECT `nim`,`jam_awal`,`jam_akhir` FROM `jurnal_penyakit` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND `kegiatan`='$data_kegiatan1[kegiatan]' AND `kelas`='$data_kelas1[kelas]'";
						$koas = mysqli_query($con,$filter_koas);
						$jml_koas = mysqli_num_rows($koas);
						echo "<td class=\"td_normal\" align=center style=\"width:18.75%;\">$jml_koas mhsw</td>";
						$tot_waktu=0;
						while ($data_koas=mysqli_fetch_array($koas))
						{
							$waktu_awal=strtotime($data_koas[jam_awal]);
							$waktu_akhir=strtotime($data_koas[jam_akhir]);
							$waktu=abs($waktu_akhir-$waktu_awal);
							$tot_waktu=$tot_waktu+$waktu;
						}
						$jam=number_format(floor($tot_waktu / (60 * 60)),2);
						$menit=number_format(floor(($tot_waktu - $jam * (60 * 60))/60),2);
						$rata_waktu = floor($tot_waktu/$jml_koas);
						$rata_jam=number_format(floor($rata_waktu / (60 * 60)),2);
						$rata_menit=number_format($rata_waktu/60,0);
						echo "<td class=\"td_normal\" style=\"width:18.75%;\">$rata_menit menit</td>";
						echo "</tr>";
					}
				}
				echo "</table>";
				echo "</td>";
				$no++;
				if ($baris=="ganjil") $baris="genap";
				else $baris="ganjil";
				echo "</tr>";
			}
		}
		else {
			echo "<tr>";
			echo "<td colspan=5 align=center>";
			echo "<< E M P T Y >></td>";
			echo "</tr>";
		}
		echo "</table><br><br>";
		echo "<center><a href=\"#top\"><i>Goto top</i></a></center><br><br>";

		echo "<h4>Rekap Kegiatan Jurnal Ketrampilan</h4>";

		//Kegiatan 1: Kategori Non-Bedside Teaching
		$tgl_filter1="SELECT DISTINCT `tanggal` FROM `jurnal_ketrampilan` WHERE ".$filter_dosen.$filter_stase.$filter_status.$filter_tgl." AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') ORDER BY `tanggal`";
		$tgl_kegiatan1 = mysqli_query($con,$tgl_filter1);
		$jml_kegiatan1 = mysqli_num_rows($tgl_kegiatan1);
		echo "</center><div id=\"ketrampilan_nonbed\"><font style=\"color:brown;\">Kegiatan Jurnal Ketrampilan Kategori Non-Bedside Teaching</font></div><p><center>";
		echo "<table style=\"width:100%\" id=\"freeze3\">";
		echo "<thead>";
		echo "<th style=\"width:5%\">No</th>";
		echo "<th style=\"width:15%\">Tanggal</th>";
		echo "<th style=\"width:50%\">Kegiatan Dosen / Kegiatan Mhsw - Lokasi</th>";
		echo "<th style=\"width:15%\">Jml Koas</th>";
		echo "<th style=\"width:15%\">Rerata Waktu</th>";
		echo "</thead>";

		$no = 1;
		if ($jml_kegiatan1>=1)
		{
		  $baris = "ganjil";
		  while ($data_tgl1=mysqli_fetch_array($tgl_kegiatan1))
		  {
		    echo "<tr class=\"$baris\">";
		    echo "<td align=center style=\"padding:10px 5px 5px 5px;\">$no</td>";
		    $tgl_indo = tanggal_indo($data_tgl1[tanggal]);
		    echo "<td style=\"padding:10px 5px 5px 5px;\">$tgl_indo</td>";

		    $filter_kelas1="SELECT DISTINCT `kelas` FROM `jurnal_ketrampilan` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') ORDER BY `kelas`";
		    $kelas1 = mysqli_query($con,$filter_kelas1);
		    echo "<td colspan=3>";
		    echo "<table class=\"tabel_normal\" style=\"width:100%;padding:0;\">";
		    while ($data_kelas1=mysqli_fetch_array($kelas1))
		    {
		      $kelas = mysqli_fetch_array(mysqli_query($con,"SELECT `kelas` FROM `kelas` WHERE `id`='$data_kelas1[kelas]'"));
		      $filter_kegiatan1 = "SELECT DISTINCT `kegiatan`,`lokasi` FROM `jurnal_ketrampilan` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') AND `kelas`='$data_kelas1[kelas]' ORDER BY `kegiatan`";
		      $kegiatan1 = mysqli_query($con,$filter_kegiatan1);
		      while ($data_kegiatan1=mysqli_fetch_array($kegiatan1))
		      {
		        echo "<tr>";
		        $kegiatan = mysqli_fetch_array(mysqli_query($con,"SELECT `kegiatan`,`kegiatan_dosen` FROM `kegiatan` WHERE `id`='$data_kegiatan1[kegiatan]'"));
						$lokasi = mysqli_fetch_array(mysqli_query($con,"SELECT `lokasi` FROM `lokasi` WHERE `id`='$data_kegiatan1[lokasi]'"));
						echo "<td class=\"td_normal\" style=\"width:62.5%;\"><b>$kegiatan[kegiatan_dosen] - Kelas $kelas[kelas]</b><br>";
						echo "<i>Kegiatan Mahasiswa:</i> $kegiatan[kegiatan].<br>";
						echo "<i>Lokasi:</i> $lokasi[lokasi].</td>";
						$filter_koas="SELECT `nim`,`jam_awal`,`jam_akhir` FROM `jurnal_ketrampilan` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND `kegiatan`='$data_kegiatan1[kegiatan]' AND `kelas`='$data_kelas1[kelas]'";
		        $koas = mysqli_query($con,$filter_koas);
		        $jml_koas = mysqli_num_rows($koas);
		        echo "<td class=\"td_normal\" align=center style=\"width:18.75%;\">$jml_koas mhsw</td>";
		        $tot_waktu=0;
		        while ($data_koas=mysqli_fetch_array($koas))
		        {
		          $waktu_awal=strtotime($data_koas[jam_awal]);
		          $waktu_akhir=strtotime($data_koas[jam_akhir]);
		          $waktu=abs($waktu_akhir-$waktu_awal);
		          $tot_waktu=$tot_waktu+$waktu;
		        }
		        $jam=number_format(floor($tot_waktu / (60 * 60)),2);
		        $menit=number_format(floor(($tot_waktu - $jam * (60 * 60))/60),2);
		        $rata_waktu = floor($tot_waktu/$jml_koas);
		        $rata_jam=number_format(floor($rata_waktu / (60 * 60)),2);
		        $rata_menit=number_format($rata_waktu/60,0);
		        echo "<td class=\"td_normal\" style=\"width:18.75%;\">$rata_menit menit</td>";
		        echo "</tr>";
		      }
		    }
		    echo "</table>";
		    echo "</td>";
		    $no++;
		    if ($baris=="ganjil") $baris="genap";
		    else $baris="ganjil";
		    echo "</tr>";
		  }
		}
		else {
		  echo "<tr>";
		  echo "<td colspan=5 align=center>";
		  echo "<< E M P T Y >></td>";
		  echo "</tr>";
		}
		echo "</table><br><br>";
		echo "<center><a href=\"#top\"><i>Goto top</i></a></center><br><br>";

		//Kegiatan 2: Kategori Bedside Teaching
		$tgl_filter1="SELECT DISTINCT `tanggal` FROM `jurnal_ketrampilan` WHERE ".$filter_dosen.$filter_stase.$filter_status.$filter_tgl." AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') ORDER BY `tanggal`";
		$tgl_kegiatan1 = mysqli_query($con,$tgl_filter1);
		$jml_kegiatan1 = mysqli_num_rows($tgl_kegiatan1);
		echo "</center><div id=\"ketrampilan_bed\"><font style=\"color:brown;\">Kegiatan Jurnal Ketrampilan Bedside Teaching</font></div><p><center>";
		echo "<table style=\"width:100%\" id=\"freeze4\">";
		echo "<thead>";
		echo "<th style=\"width:5%\">No</th>";
		echo "<th style=\"width:15%\">Tanggal</th>";
		echo "<th style=\"width:50%\">Kegiatan Dosen / Kegiatan Mhsw - Lokasi</th>";
		echo "<th style=\"width:15%\">Jml Koas</th>";
		echo "<th style=\"width:15%\">Rerata Waktu</th>";
		echo "</thead>";

		$no = 1;
		if ($jml_kegiatan1>=1)
		{
		  $baris = "ganjil";
		  while ($data_tgl1=mysqli_fetch_array($tgl_kegiatan1))
		  {
		    echo "<tr class=\"$baris\">";
		    echo "<td align=center style=\"padding:10px 5px 5px 5px;\">$no</td>";
		    $tgl_indo = tanggal_indo($data_tgl1[tanggal]);
		    echo "<td style=\"padding:10px 5px 5px 5px;\">$tgl_indo</td>";

		    $filter_kelas1="SELECT DISTINCT `kelas` FROM `jurnal_ketrampilan` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') ORDER BY `kelas`";
		    $kelas1 = mysqli_query($con,$filter_kelas1);
		    echo "<td colspan=3>";
		    echo "<table class=\"tabel_normal\" style=\"width:100%;padding:0;\">";
		    while ($data_kelas1=mysqli_fetch_array($kelas1))
		    {
		      $kelas = mysqli_fetch_array(mysqli_query($con,"SELECT `kelas` FROM `kelas` WHERE `id`='$data_kelas1[kelas]'"));
		      $filter_kegiatan1 = "SELECT DISTINCT `kegiatan`,`lokasi` FROM `jurnal_ketrampilan` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') AND `kelas`='$data_kelas1[kelas]' ORDER BY `kegiatan`";
		      $kegiatan1 = mysqli_query($con,$filter_kegiatan1);
		      while ($data_kegiatan1=mysqli_fetch_array($kegiatan1))
		      {
		        echo "<tr>";
		        $kegiatan = mysqli_fetch_array(mysqli_query($con,"SELECT `kegiatan`,`kegiatan_dosen` FROM `kegiatan` WHERE `id`='$data_kegiatan1[kegiatan]'"));
						$lokasi = mysqli_fetch_array(mysqli_query($con,"SELECT `lokasi` FROM `lokasi` WHERE `id`='$data_kegiatan1[lokasi]'"));
						echo "<td class=\"td_normal\" style=\"width:62.5%;\"><b>$kegiatan[kegiatan_dosen] - Kelas $kelas[kelas]</b><br>";
						echo "<i>Kegiatan Mahasiswa:</i> $kegiatan[kegiatan].<br>";
						echo "<i>Lokasi:</i> $lokasi[lokasi].</td>";
						$filter_koas="SELECT `nim`,`jam_awal`,`jam_akhir` FROM `jurnal_ketrampilan` WHERE ".$filter_dosen.$filter_stase.$filter_status." AND `tanggal`='$data_tgl1[tanggal]' AND `kegiatan`='$data_kegiatan1[kegiatan]' AND `kelas`='$data_kelas1[kelas]'";
		        $koas = mysqli_query($con,$filter_koas);
		        $jml_koas = mysqli_num_rows($koas);
		        echo "<td class=\"td_normal\" align=center style=\"width:18.75%;\">$jml_koas mhsw</td>";
		        $tot_waktu=0;
		        while ($data_koas=mysqli_fetch_array($koas))
		        {
		          $waktu_awal=strtotime($data_koas[jam_awal]);
		          $waktu_akhir=strtotime($data_koas[jam_akhir]);
		          $waktu=abs($waktu_akhir-$waktu_awal);
		          $tot_waktu=$tot_waktu+$waktu;
		        }
		        $jam=number_format(floor($tot_waktu / (60 * 60)),2);
		        $menit=number_format(floor(($tot_waktu - $jam * (60 * 60))/60),2);
		        $rata_waktu = floor($tot_waktu/$jml_koas);
		        $rata_jam=number_format(floor($rata_waktu / (60 * 60)),2);
		        $rata_menit=number_format($rata_waktu/60,0);
		        echo "<td class=\"td_normal\" style=\"width:18.75%;\">$rata_menit menit</td>";
		        echo "</tr>";
		      }
		    }
		    echo "</table>";
		    echo "</td>";
		    $no++;
		    if ($baris=="ganjil") $baris="genap";
		    else $baris="ganjil";
		    echo "</tr>";
		  }
		}
		else {
		  echo "<tr>";
		  echo "<td colspan=5 align=center>";
		  echo "<< E M P T Y >></td>";
		  echo "</tr>";
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
	   $("#freeze1").freezeHeader();
		 $("#freeze2").freezeHeader();
		 $("#freeze3").freezeHeader();
		 $("#freeze4").freezeHeader();
  });
</script>



<!--</body></html>-->
</BODY>
</HTML>
