<HTML>
<head>
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==4)
	{
		if ($_COOKIE['level']==4) {include "menu4.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) IKM-KP</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">PENILAIAN KEPANITERAAN (STASE) IKM-KP</font></h4>";
		$tgl_mulai = $_GET[mulai];
		$tgl_selesai = $_GET[selesai];
		$approval = $_GET[approval];
		$mhsw = $_GET[mhsw];

		if ($approval=="all")
		{
			if ($mhsw=="all")
			{
				$filter_approval ="";
				$statusapproval = "Semua Status";
				$statusmhsw = "Semua Mahasiswa";
			}
			else
			{
				$nama_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$mhsw'"));
				$filter_approval ="AND `nim`='$mhsw'";
				$statusapproval = "Semua Status";
				$statusmhsw = "$nama_mhsw[nama] (NIM: $mhsw)";
			}

		}
		else
		{
			if ($mhsw=="all")
			{
				$filter_approval = "AND `status_approval`='$approval'";
				if ($approval=="0") $statusapproval = "Belum Disetujui";
				if ($approval=="1") $statusapproval = "Sudah Disetujui";
				$statusmhsw = "Semua Mahasiswa";
			}
			else
			{
				$nama_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$mhsw'"));
				$filter_approval = "AND `nim`='$mhsw' AND `status_approval`='$approval'";
				if ($approval=="0") $statusapproval = "Belum Disetujui";
				if ($approval=="1") $statusapproval = "Sudah Disetujui";
				$statusmhsw = "$nama_mhsw[nama] (NIM: $mhsw)";
			}
		}

		$mulai = tanggal_indo($tgl_mulai);
		$selesai = tanggal_indo($tgl_selesai);
		echo "<table class=\"tabel_normal\" style=\"font-size:0.85em;\">";
		echo "<tr><td style=\"padding:0 0 0 0;\">Periode Tanggal Pengisian</td><td style=\"padding:0 0 0 0;\">:</td><td style=\"padding:0 0 0 0;\"><i>$mulai s.d. $selesai</i></td></tr>";
		echo "<tr><td style=\"padding:0 0 0 0;\">Status Approval</td><td style=\"padding:0 0 0 0;\">:</td><td style=\"padding:0 0 0 0;\"><i>$statusapproval</i></td></tr>";
		echo "<tr><td style=\"padding:0 0 0 0;\">Mahasiswa</td><td style=\"padding:0 0 0 0;\">:</td><td style=\"padding:0 0 0 0;\"><i>$statusmhsw</i></td></tr>";
		echo "</table><br>";

		$id_stase = "M095";
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";

		echo "</center><br><a href=\"#pkbi\"><i>Penilaian Kegiatan di PKBI</i></a><br>";
		echo "<a href=\"#p2ukm\"><i>Penilaian Kegiatan di P2UKM</i></a><br>";
		echo "<a href=\"#komprehensip\"><i>Penilaian Ujian Komprehensip</i></a><br><br>";

		//Penilaian Kegiatan di PKBI
		echo "<a id=\"pkbi\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Kegiatan di PKBI</font></a><br><br>";
		$nilai_pkbi = mysqli_query($con,"SELECT * FROM `ikmkp_nilai_pkbi` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze1\" style=\"width:100%\">";
		echo "<thead>
					<th style=\"width:5%\">No</th>
					<th style=\"width:15%\">Tanggal Pengisian</th>
					<th style=\"width:65%\">Jenis Penilaian</th>
					<th style=\"width:15%\">Status Approval</th>
					</thead>";
		$cek_nilai_pkbi = mysqli_num_rows($nilai_pkbi);
		if ($cek_nilai_pkbi<1)
		{
			echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
			$no=1;
			$kelas = "ganjil";
			while ($data_pkbi=mysqli_fetch_array($nilai_pkbi))
			{
				$tanggal_isi = tanggal_indo($data_pkbi[tgl_isi]);
				$tanggal_approval = tanggal_indo($data_pkbi[tgl_approval]);
				$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_pkbi[nim]'"));
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				echo "<td>$tanggal_isi</td>";
				echo "<td>Penilaian Kegiatan di PKBI<br><br>";
				echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>Nilai: $data_pkbi[nilai_total]</i></td>";
				echo "<td align=center>";
				if ($data_pkbi[status_approval]=='0')
				{
					echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
					echo "<a href=\"approve_pkbi_dosen.php?id=$data_pkbi[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_pkbi[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
				}
				else
				{
					echo "<font style=\"color:green\">DISETUJUI</font><br>";
					echo "per tanggal<br>";
					echo "$tanggal_approval<p>";
					echo "<a href=\"view_form_pkbi.php?id=$data_pkbi[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_pkbi[id]\" value=\"VIEW\"></a><p>";
					echo "<a href=\"unapprove_pkbi_dosen.php?id=$data_pkbi[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_pkbi[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
				}
				echo "</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
		}
		echo "</table><br><br>";

		//Penilaian Kegiatan di P2UKM
		echo "<a id=\"p2ukm\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Kegiatan di P2UKM</font></a><br><br>";
		$nilai_p2ukm = mysqli_query($con,"SELECT * FROM `ikmkp_nilai_p2ukm` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze2\" style=\"width:100%\">";
		echo "<thead>
		      <th style=\"width:5%\">No</th>
		      <th style=\"width:15%\">Tanggal Pengisian</th>
		      <th style=\"width:65%\">Jenis Penilaian</th>
		      <th style=\"width:15%\">Status Approval</th>
		      </thead>";
		$cek_nilai_p2ukm = mysqli_num_rows($nilai_p2ukm);
		if ($cek_nilai_p2ukm<1)
		{
		  echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
		  $no=1;
		  $kelas = "ganjil";
		  while ($data_p2ukm=mysqli_fetch_array($nilai_p2ukm))
		  {
		    $tanggal_isi = tanggal_indo($data_p2ukm[tgl_isi]);
		    $tanggal_approval = tanggal_indo($data_p2ukm[tgl_approval]);
		    $data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_p2ukm[nim]'"));
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    echo "<td>$tanggal_isi</td>";
				echo "<td>Penilaian $data_p2ukm[jenis_penilaian]<br><br>";
				echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>Nilai: $data_p2ukm[nilai_total]</i></td>";
				echo "<td align=center>";
		    if ($data_p2ukm[status_approval]=='0')
		    {
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
		      echo "<a href=\"approve_p2ukm_dosen.php?id=$data_p2ukm[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_p2ukm[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
		    }
		    else
		    {
		      echo "<font style=\"color:green\">DISETUJUI</font><br>";
		      echo "per tanggal<br>";
		      echo "$tanggal_approval<p>";
		      echo "<a href=\"view_form_p2ukm.php?id=$data_p2ukm[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_p2ukm[id]\" value=\"VIEW\"></a><p>";
		      echo "<a href=\"unapprove_p2ukm_dosen.php?id=$data_p2ukm[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_p2ukm[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
		    }
		    echo "</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		}
		echo "</table><br><br>";

		//Nilai Ujian Komprehensip
		echo "<a id=\"komprehensip\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Ujian Komprehensip</font></a><br><br>";
		$nilai_komprehensip = mysqli_query($con,"SELECT * FROM `ikmkp_nilai_komprehensip` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze3\" style=\"width:100%\">";
		echo "<thead>
		      <th style=\"width:5%\">No</th>
		      <th style=\"width:15%\">Tanggal Pengisian</th>
		      <th style=\"width:65%\">Jenis Penilaian</th>
		      <th style=\"width:15%\">Status Approval</th>
		      </thead>";
		$cek_nilai_komprehensip = mysqli_num_rows($nilai_komprehensip);
		if ($cek_nilai_komprehensip<1)
		{
		  echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
		  $no=1;
		  $kelas = "ganjil";
		  while ($data_komprehensip=mysqli_fetch_array($nilai_komprehensip))
		  {
		    $tanggal_isi = tanggal_indo($data_komprehensip[tgl_isi]);
		    $tanggal_approval = tanggal_indo($data_komprehensip[tgl_approval]);
		    $data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_komprehensip[nim]'"));
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    echo "<td>$tanggal_isi</td>";
				echo "<td>Penilaian Ujian Komprehensip<br><br>";
				echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>Nilai: $data_komprehensip[nilai_total]</i></td>";
		    echo "<td align=center>";
		    if ($data_komprehensip[status_approval]=='0')
		    {
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
		      echo "<a href=\"approve_komprehensip_dosen.php?id=$data_komprehensip[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_komprehensip[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
		    }
		    else
		    {
		      echo "<font style=\"color:green\">DISETUJUI</font><br>";
		      echo "per tanggal<br>";
		      echo "$tanggal_approval<p>";
		      echo "<a href=\"view_form_komprehensip.php?id=$data_komprehensip[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_komprehensip[id]\" value=\"VIEW\"></a><p>";
		      echo "<a href=\"unapprove_komprehensip_dosen.php?id=$data_komprehensip[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_komprehensip[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
		    }
		    echo "</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		}
		echo "</table><br><br>";

		echo "<center><a href=\"#top\"><i>Goto top</i></a></center><br><br>";
		echo "</fieldset>";

	}
		else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
?>
<script type="text/javascript" src="../jquery.min.js"></script>
<script type="text/javascript" src="../freezeheader/js/jquery.freezeheader.js"></script>
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
