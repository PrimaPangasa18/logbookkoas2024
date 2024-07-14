<HTML>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable_color.css" type="text/css" media="screen" />
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE[level]==4 OR $_COOKIE[level]==6))
		{
				if ($_COOKIE['level']==4) {include "menu4.php";}
				if ($_COOKIE['level']==6) {include "menu6.php";}


		echo "<div class=\"text_header\" id=\"top\">DAFTAR KEGIATAN DOSEN/RESIDEN</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">DAFTAR KEGIATAN DOSEN/RESIDEN</font></h4><br>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		$mhsw_filter = $_GET[mhsw];
		$stase_filter = $_GET[stase];
		$status_filter = $_GET[appstatus];
		$tanggal_filter = $_GET[tgl_kegiatan];

		if ($mhsw_filter=="all") $filtermhsw = "";
		else $filtermhsw = "AND `nim`="."'$mhsw_filter'";
		if ($stase_filter=="all") $filterstase = "";
		else $filterstase = "AND `stase`="."'$stase_filter'";
		if ($status_filter=="all") $filterstatus = "";
		else $filterstatus = "AND `status`="."'$status_filter'";
		if ($tanggal_filter=="Semua Tanggal") $filtertanggal = "";
		else $filtertanggal = "AND `tanggal`="."'$tanggal_filter'";
		$dosen_filter = "`dosen`="."'$_COOKIE[user]'";

		$filter_penyakit = "SELECT * FROM `jurnal_penyakit` WHERE `dosen`='$_COOKIE[user]' $filtermhsw $filterstase $filtertanggal $filterstatus ORDER BY `tanggal`,`jam_awal`";
		$filter_ketrampilan = "SELECT * FROM `jurnal_ketrampilan` WHERE `dosen`='$_COOKIE[user]' $filtermhsw $filterstase $filtertanggal $filterstatus ORDER BY `tanggal`,`jam_awal`";
		$biodata_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$_COOKIE[user]'"));
		$data_penyakit = mysqli_query($con,$filter_penyakit);
		$data_ketrampilan = mysqli_query($con,$filter_ketrampilan);
		$jum1=mysqli_num_rows($data_penyakit);
		$jum2=mysqli_num_rows($data_ketrampilan);

		echo "<b>Kegiatan Terkait Jurnal Penyakit Mahasiswa Koas</b><br><br>";
		echo "<table id=\"freeze\" style=\"width:100%;\">";
		echo "<thead>";
			echo "<th style=\"width:4%;line-height:1.2em;\">No</th>";
			echo "<th style=\"width:21%;line-height:1.2em;\">Nama Mahasiswa (NIM)/<br>Tanggal/Waktu</th>";
			echo "<th style=\"width:25%;line-height:1.2em;\">Kegiatan (Level Penguasaan)/<br>Lokasi</th>";
			echo "<th style=\"width:25%;line-height:1.2em;\">Penyakit<br>(Level SKDI/Kepmenkes/<br>IPSG/Muatan Lokal)</th>";
			echo "<th style=\"width:25%;line-height:1.2em;\">Kegiatan Dosen/Residen<br>Approval</th>";
		echo "</thead>";
		if ($jum1==0)
		{
			echo "<tr><td colspan=5 align=\"center\"><br><i><<  E M P T Y  >></i><br><br></td></tr>";
		}
		$no = 1;
		$kelas = "ganjil";
		while ($data1=mysqli_fetch_array($data_penyakit))
		{
			$nama_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$data1[nim]'"));
			$tanggal_keg = tanggal_indo($data1[tanggal]);
			$kegiatan = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kegiatan` WHERE `id`='$data1[kegiatan]'"));
			$lokasi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `lokasi` WHERE `id`='$data1[lokasi]'"));
			echo "<tr class=\"$kelas\">";
				echo "<td class=\"td_up\" align=center>$no</td>";
				echo "<td class=\"td_up\">
					<a href=\"biodata.php?nim=$data1[nim]\" target=\"_blank\">$nama_mhsw[nama]</a><br>($data1[nim])<p>
					<i>Tanggal: $tanggal_keg</i><br>
					<i>Waktu: $data1[jam_awal] - $data1[jam_akhir]</i></td>";
				echo "<td class=\"td_up\">$kegiatan[kegiatan] ($kegiatan[level]).<p>
					<i>Lokasi: $lokasi[lokasi]</i></td>";
				echo "<td class=\"td_up\">";

				$id = 1;
				while ($id<=4)
				{
					$penyakit_id = "penyakit".$id;
					if ($data1[$penyakit_id]!="")
					{
						$penyakit = mysqli_fetch_array(mysqli_query($con,"SELECT `penyakit`,`skdi_level`,`k_level`,`ipsg_level`,`kml_level` FROM `daftar_penyakit` WHERE `id`='$data1[$penyakit_id]'"));
						$penyakit_kapital = strtoupper($penyakit[penyakit]);
						if ($id==1) $warna = "black";
						if ($id==2) $warna = "brown";
						if ($id==3) $warna = "blue";
						if ($id==4) $warna = "green";
						echo "<font style=\"color:$warna\">$penyakit_kapital ($penyakit[skdi_level]/$penyakit[k_level]/$penyakit[ipsg_level]/$penyakit[kml_level]).</font><p>";
					}
					$id++;
				}
				echo "</td>";
				echo "<td class=\"td_up\">$kegiatan[kegiatan_dosen] [$kegiatan[level] - Kategori $kegiatan[kategori]]<p><i>Status: ";
				if ($data1[status]==0)
				{
					echo "<font style=\"color:red\">Not-Approved</font>";
					echo "&nbsp;&nbsp;<a href=\"dosen_approve.php?mhsw=$mhsw_filter&stase=$stase_filter&tgl_kegiatan=$tanggal_filter&appstatus=$status_filter&jurnal=penyakit&id=$data1[id]&status=1\"><input type=\"button\" name=\"approve\" style=\"color:red\" value=\"APPROVE\"></a>";
				}
				else
				{
					echo "<font style=\"color:green\">Approved</font>";
					echo "&nbsp;&nbsp;<a href=\"dosen_approve.php?mhsw=$mhsw_filter&stase=$stase_filter&tgl_kegiatan=$tanggal_filter&appstatus=$status_filter&jurnal=penyakit&id=$data1[id]&status=0\"><input type=\"button\" name=\"unapprove\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
				}
				echo "</i></td>";
			echo "</tr>";
			$no++;
			if ($kelas=="ganjil") $kelas="genap";
			else $kelas="ganjil";
		}
		echo "</table><br>";
		$filter_penyakit_unapprove = "SELECT * FROM `jurnal_penyakit` WHERE `dosen`='$_COOKIE[user]' $filtermhsw $filterstase $filtertanggal AND `status`='0'";
		$data_penyakit_unapprove = mysqli_query($con,$filter_penyakit_unapprove);
		$jum_unapprove_1=mysqli_num_rows($data_penyakit_unapprove);
		if ($jum_unapprove_1>=1 AND $status_filter!=1)
		{
			echo "<a href=\"dosen_approve_all.php?mhsw=$mhsw_filter&stase=$stase_filter&tgl_kegiatan=$tanggal_filter&appstatus=$status_filter&jurnal=penyakit\">";
			echo "<input type=\"button\" class=\"submit1\" name=\"approve1_all\" value=\"APPROVE ALL\" /></a>";
		}
		echo "<center><br><a href=\"#top\"><i>Goto top</i></a><br><br>";

		echo "<b>Kegiatan Terkait Jurnal Ketrampilan Mahasiswa Koas</b><br><br>";
		echo "<table id=\"freeze1\" style=\"width:100%;\">";
		echo "<thead>";
			echo "<th style=\"width:4%;line-height:1.2em;\">No</th>";
			echo "<th style=\"width:21%;line-height:1.2em;\">Nama Mahasiswa (NIM)/<br>Tanggal/Waktu</th>";
			echo "<th style=\"width:25%;line-height:1.2em;\">Kegiatan (Level Penguasaan)/<br>Lokasi</th>";
			echo "<th style=\"width:25%;line-height:1.2em;\">Ketrampilan<br>(Level SKDI/Kepmenkes/<br>IPSG/Muatan Lokal)</th>";
			echo "<th style=\"width:25%;line-height:1.2em;\">Kegiatan Dosen/Residen<br>Approval</th>";
		echo "</thead>";
		if ($jum2==0)
		{
			echo "<tr><td colspan=5 align=\"center\"><br><i><<  E M P T Y  >></i><br><br></td></tr>";
		}

		$no = 1;
		$kelas = "ganjil";
		while ($data2=mysqli_fetch_array($data_ketrampilan))
		{
			$nama_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$data2[nim]'"));
			$tanggal_keg = tanggal_indo($data2[tanggal]);
			$kegiatan = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kegiatan` WHERE `id`='$data2[kegiatan]'"));
			$lokasi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `lokasi` WHERE `id`='$data2[lokasi]'"));
			echo "<tr class=\"$kelas\">";
				echo "<td class=\"td_up\" align=center>$no</td>";
				echo "<td class=\"td_up\">
				<a href=\"biodata.php?nim=$data2[nim]\" target=\"_blank\">$nama_mhsw[nama]</a><br>($data2[nim])<p>
				<i>Tanggal: $tanggal_keg</i><br>
					<i>Waktu: $data2[jam_awal] - $data2[jam_akhir]</i></td>";
				echo "<td class=\"td_up\">$kegiatan[kegiatan] ($kegiatan[level]).<p>
					<i>Lokasi: $lokasi[lokasi]</i></td>";
				echo "<td class=\"td_up\">";

				$id = 1;
				while ($id<=4)
				{
					$ketrampilan_id = "ketrampilan".$id;
					if ($data2[$ketrampilan_id]!="")
					{
						$ketrampilan = mysqli_fetch_array(mysqli_query($con,"SELECT `ketrampilan`,`skdi_level`,`k_level`,`ipsg_level`,`kml_level` FROM `daftar_ketrampilan` WHERE `id`='$data2[$ketrampilan_id]'"));
						$ketrampilan_kapital = strtoupper($ketrampilan[ketrampilan]);
						if ($id==1) $warna = "black";
						if ($id==2) $warna = "brown";
						if ($id==3) $warna = "blue";
						if ($id==4) $warna = "green";
						echo "<font style=\"color:$warna\">$ketrampilan_kapital ($ketrampilan[skdi_level]/$ketrampilan[k_level]/$ketrampilan[ipsg_level]/$ketrampilan[kml_level]).</font><p>";
					}
					$id++;
				}
				echo "</td>";
				echo "<td class=\"td_up\">$kegiatan[kegiatan_dosen] [$kegiatan[level] - Kategori $kegiatan[kategori]]<p><i>Status: ";
				if ($data2[status]==0)
				{
					echo "<font style=\"color:red\">Not-Approved</font>";
					echo "&nbsp;&nbsp;<a href=\"dosen_approve.php?mhsw=$mhsw_filter&stase=$stase_filter&tgl_kegiatan=$tanggal_filter&appstatus=$status_filter&jurnal=ketrampilan&id=$data2[id]&status=1\"><input type=\"button\" name=\"approve\" style=\"color:red\" value=\"APPROVE\"></a>";
				}
				else
				{
					echo "<font style=\"color:green\">Approved</font>";
					echo "&nbsp;&nbsp;<a href=\"dosen_approve.php?mhsw=$mhsw_filter&stase=$stase_filter&tgl_kegiatan=$tanggal_filter&appstatus=$status_filter&jurnal=ketrampilan&id=$data2[id]&status=0\"><input type=\"button\" name=\"unapprove\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
				}
				echo "</i></td>";
			echo "</tr>";
			$no++;
			if ($kelas=="ganjil") $kelas="genap";
			else $kelas="ganjil";
		}
		echo "</table><br>";
		$filter_ketrampilan_unapprove = "SELECT * FROM `jurnal_ketrampilan` WHERE `dosen`='$_COOKIE[user]' $filtermhsw $filterstase $filtertanggal AND `status`='0'";
		$data_ketrampilan_unapprove = mysqli_query($con,$filter_ketrampilan_unapprove);
		$jum_unapprove_2=mysqli_num_rows($data_ketrampilan_unapprove);
		if ($jum_unapprove_2>=1 AND $status_filter!=1)
		{
			echo "<a href=\"dosen_approve_all.php?mhsw=$mhsw_filter&stase=$stase_filter&tgl_kegiatan=$tanggal_filter&appstatus=$status_filter&jurnal=ketrampilan\">";
			echo "<input type=\"button\" class=\"submit1\" name=\"approve2_all\" value=\"APPROVE ALL\" /></a>";
		}
		echo "<center><br><a href=\"#top\"><i>Goto top</i></a><br><br>";

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
