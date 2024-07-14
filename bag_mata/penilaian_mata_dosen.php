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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN MATA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN MATA</font></h4>";
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

		$id_stase = "M104";
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";

		echo "</center><br><a href=\"#presentasi\"><i>Penilaian Presentasi Kasus Besar</i></a><br>";
		echo "<a href=\"#jurnal\"><i>Penilaian Journal Reading</i></a><br>";
		echo "<a href=\"#minicex\"><i>Penilaian Ujian Mini-Cex</i></a><br><br>";

		//Penilaian Presentasi Kasus
		echo "<a id=\"presentasi\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Presentasi Kasus</font></a><br><br>";
		$nilai_presentasi = mysqli_query($con,"SELECT * FROM `mata_nilai_presentasi` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze1\" style=\"width:100%\">";
		echo "<thead>
		      <th style=\"width:5%\">No</th>
		      <th style=\"width:15%\">Tanggal Pengisian</th>
		      <th style=\"width:65%\">Jenis Penilaian</th>
		      <th style=\"width:15%\">Status Approval</th>
		      </thead>";
		$cek_nilai_presentasi = mysqli_num_rows($nilai_presentasi);
		if ($cek_nilai_presentasi<1)
		{
		  echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
		  $no=1;
		  $kelas = "ganjil";
		  while ($data_presentasi=mysqli_fetch_array($nilai_presentasi))
		  {
		    $tanggal_isi = tanggal_indo($data_presentasi[tgl_isi]);
		    $tanggal_approval = tanggal_indo($data_presentasi[tgl_approval]);
		    $data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_presentasi[nim]'"));
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    echo "<td>$tanggal_isi</td>";
		    echo "<td>Penilaian Presentasi Kasus Besar<br><br>";
		    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>";
				echo "NIM: $data_mhsw[nim]<br>";
				echo "Judul Kasus: $data_presentasi[judul_presentasi]<br>Nilai: $data_presentasi[nilai_total]</i></td>";
		    echo "<td align=center>";
		    if ($data_presentasi[status_approval]=='0')
		    {
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
		      echo "<a href=\"approve_presentasi_dosen.php?id=$data_presentasi[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_presentasi[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
		    }
		    else
		    {
		      echo "<font style=\"color:green\">DISETUJUI</font><br>";
		      echo "per tanggal<br>";
		      echo "$tanggal_approval<p>";
		      echo "<a href=\"view_form_presentasi.php?id=$data_presentasi[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_presentasi[id]\" value=\"VIEW\"></a><p>";
		      echo "<a href=\"unapprove_presentasi_dosen.php?id=$data_presentasi[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_presentasi[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
		    }
		    echo "</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		}
		echo "</table><br><br>";



	//Penilaian Journal Reading
	echo "<a id=\"jurnal\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Journal Reading</font></a><br><br>";
	$nilai_jurnal = mysqli_query($con,"SELECT * FROM `mata_nilai_jurnal` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
	echo "<table id=\"freeze2\" style=\"width:100%\">";
	echo "<thead>
	      <th style=\"width:5%\">No</th>
	      <th style=\"width:15%\">Tanggal Pengisian</th>
	      <th style=\"width:65%\">Jenis Penilaian</th>
	      <th style=\"width:15%\">Status Approval</th>
	      </thead>";
	$cek_nilai_jurnal = mysqli_num_rows($nilai_jurnal);
	if ($cek_nilai_jurnal<1)
	{
	  echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
	}
	else
	{
	  $no=1;
	  $kelas = "ganjil";
	  while ($data_jurnal=mysqli_fetch_array($nilai_jurnal))
	  {
	    $tanggal_isi = tanggal_indo($data_jurnal[tgl_isi]);
	    $tanggal_approval = tanggal_indo($data_jurnal[tgl_approval]);
	    $data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_jurnal[nim]'"));
	    echo "<tr class=\"$kelas\">";
	    echo "<td align=center>$no</td>";
	    echo "<td>$tanggal_isi</td>";
	    echo "<td>Penilaian Journal Reading<br><br>";
	    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			echo "Judul Artikel: $data_jurnal[judul_presentasi]<br>Nilai: $data_jurnal[nilai_total]</i>";
			echo "</td>";
	    echo "<td align=center>";
	    if ($data_jurnal[status_approval]=='0')
	    {
	      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
	      echo "<a href=\"approve_jurnal_dosen.php?id=$data_jurnal[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_jurnal[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
	    }
	    else
	    {
	      echo "<font style=\"color:green\">DISETUJUI</font><br>";
	      echo "per tanggal<br>";
	      echo "$tanggal_approval<p>";
	      echo "<a href=\"view_form_jurnal.php?id=$data_jurnal[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_jurnal[id]\" value=\"VIEW\"></a><p>";
	      echo "<a href=\"unapprove_jurnal_dosen.php?id=$data_jurnal[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_jurnal[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
	    }
	    echo "</td>";
	    echo "</tr>";
	    $no++;
	    if ($kelas=="ganjil") $kelas="genap";
	    else $kelas="ganjil";
	  }
	}
	echo "</table><br><br>";

	//Penilaian Ujian Mini-Cex
	echo "<a id=\"minicex\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Ujian Mini-Cex</font></a><br><br>";
	$nilai_minicex = mysqli_query($con,"SELECT * FROM `mata_nilai_minicex` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `tgl_isi`");
	echo "<table id=\"freeze3\" style=\"width:100%\">";
	echo "<thead>
	      <th style=\"width:5%\">No</th>
	      <th style=\"width:15%\">Tanggal Pengisian</th>
	      <th style=\"width:65%\">Jenis Penilaian</th>
	      <th style=\"width:15%\">Status Approval</th>
	      </thead>";
	$cek_nilai_minicex = mysqli_num_rows($nilai_minicex);
	if ($cek_nilai_minicex<1)
	{
	  echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
	}
	else
	{
	  $no=1;
	  $kelas = "ganjil";
	  while ($data_minicex=mysqli_fetch_array($nilai_minicex))
	  {
	    $tanggal_isi = tanggal_indo($data_minicex[tgl_isi]);
	    $tanggal_ujian = tanggal_indo($data_minicex[tgl_ujian]);
	    $tanggal_approval = tanggal_indo($data_minicex[tgl_approval]);
	    $data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_minicex[nim]'"));
	    echo "<tr class=\"$kelas\">";
	    echo "<td align=center>$no</td>";
	    echo "<td>$tanggal_isi</td>";
	    echo "<td>Penilaian Ujian Mini-Cex<br><br>";
	    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: <i>$data_mhsw[nim]<br>";
	    echo "Tanggal Ujian: $tanggal_ujian<br>Nilai: $data_minicex[nilai_rata]</i></td>";
	    echo "<td align=center>";
	    if ($data_minicex[status_approval]=='0')
	    {
	      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
	      echo "<a href=\"approve_minicex_dosen.php?id=$data_minicex[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_minicex[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
	    }
	    else
	    {
	      echo "<font style=\"color:green\">DISETUJUI</font><br>";
	      echo "per tanggal<br>";
	      echo "$tanggal_approval<p>";
	      echo "<a href=\"view_form_minicex.php?id=$data_minicex[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_minicex[id]\" value=\"VIEW\"></a><p>";
	      echo "<a href=\"unapprove_minicex_dosen.php?id=$data_minicex[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_minicex[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
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
