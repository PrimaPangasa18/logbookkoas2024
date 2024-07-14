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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) KEDOKTERAN FORENSIK DAN MEDIKOLEGAL</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">PENILAIAN KEPANITERAAN (STASE) KEDOKTERAN FORENSIK DAN MEDIKOLEGAL</font></h4>";
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

		$id_stase = "M112";
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";

		echo "</center><br><a href=\"#visum\"><i>Penilaian Visum Bayangan</i></a><br>";
		echo "<a href=\"#jaga\"><i>Penilaian Kegiatan Jaga</i></a><br>";
		echo "<a href=\"#substase\"><i>Penilaian Substase</i></a><br>";
		echo "<a href=\"#referat\"><i>Penilaian Referat</i></a><br><br>";

		//Penilaian Visum Bayangan
		echo "<a id=\"visum\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Visum Bayangan</font></a><br><br>";
		$nilai_visum = mysqli_query($con,"SELECT * FROM `forensik_nilai_visum` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze1\" style=\"width:100%\">";
		echo "<thead>
		      <th style=\"width:5%\">No</th>
		      <th style=\"width:15%\">Tanggal Pengisian</th>
		      <th style=\"width:65%\">Jenis Penilaian</th>
		      <th style=\"width:15%\">Status Approval</th>
		      </thead>";
		$cek_nilai_visum = mysqli_num_rows($nilai_visum);
		if ($cek_nilai_visum<1)
		{
		  echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
		  $no=1;
		  $kelas = "ganjil";
		  while ($data_visum=mysqli_fetch_array($nilai_visum))
		  {
		    $tanggal_isi = tanggal_indo($data_visum[tgl_isi]);
		    $tanggal_approval = tanggal_indo($data_visum[tgl_approval]);
		    $data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_visum[nim]'"));
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    echo "<td>$tanggal_isi</td>";
		    echo "<td>Penilaian Visum Bayangan Dosen Ke-$data_visum[dosen_ke]<br><br>";
		    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
		    echo "Nilai: $data_visum[nilai_rata]</i></td>";
		    echo "<td align=center>";
		    if ($data_visum[status_approval]=='0')
		    {
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
		      echo "<a href=\"approve_visum_dosen.php?id=$data_visum[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_visum[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
		    }
		    else
		    {
		      echo "<font style=\"color:green\">DISETUJUI</font><br>";
		      echo "per tanggal<br>";
		      echo "$tanggal_approval<p>";
		      echo "<a href=\"view_form_visum.php?id=$data_visum[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_visum[id]\" value=\"VIEW\"></a><p>";
		      echo "<a href=\"unapprove_visum_dosen.php?id=$data_visum[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_visum[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
		    }
		    echo "</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		}
		echo "</table><br><br>";

		//Penilaian Kegiatan Jaga
		echo "<a id=\"jaga\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Kegiatan Jaga</font></a><br><br>";
		$nilai_jaga = mysqli_query($con,"SELECT * FROM `forensik_nilai_jaga` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze2\" style=\"width:100%\">";
		echo "<thead>
		      <th style=\"width:5%\">No</th>
		      <th style=\"width:15%\">Tanggal Pengisian</th>
		      <th style=\"width:65%\">Jenis Penilaian</th>
		      <th style=\"width:15%\">Status Approval</th>
		      </thead>";
		$cek_nilai_jaga = mysqli_num_rows($nilai_jaga);
		if ($cek_nilai_jaga<1)
		{
		  echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
		  $no=1;
		  $kelas = "ganjil";
		  while ($data_jaga=mysqli_fetch_array($nilai_jaga))
		  {
		    $tanggal_isi = tanggal_indo($data_jaga[tgl_isi]);
		    $tanggal_approval = tanggal_indo($data_jaga[tgl_approval]);
		    $data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_jaga[nim]'"));
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    echo "<td>$tanggal_isi</td>";
		    echo "<td>Penilaian Kegiatan Jaga Dosen Ke-$data_jaga[dosen_ke]<br><br>";
		    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
		    echo "Nilai: $data_jaga[nilai_rata]</i></td>";
		    echo "<td align=center>";
		    if ($data_jaga[status_approval]=='0')
		    {
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
		      echo "<a href=\"approve_jaga_dosen.php?id=$data_jaga[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_jaga[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
		    }
		    else
		    {
		      echo "<font style=\"color:green\">DISETUJUI</font><br>";
		      echo "per tanggal<br>";
		      echo "$tanggal_approval<p>";
		      echo "<a href=\"view_form_jaga.php?id=$data_jaga[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_jaga[id]\" value=\"VIEW\"></a><p>";
		      echo "<a href=\"unapprove_jaga_dosen.php?id=$data_jaga[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_jaga[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
		    }
		    echo "</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		}
		echo "</table><br><br>";

		//Penilaian Substase
		echo "<a id=\"substase\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Substase</font></a><br><br>";
		$nilai_substase = mysqli_query($con,"SELECT * FROM `forensik_nilai_substase` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze3\" style=\"width:100%\">";
		echo "<thead>
		      <th style=\"width:5%\">No</th>
		      <th style=\"width:15%\">Tanggal Pengisian</th>
		      <th style=\"width:65%\">Jenis Penilaian</th>
		      <th style=\"width:15%\">Status Approval</th>
		      </thead>";
		$cek_nilai_substase = mysqli_num_rows($nilai_substase);
		if ($cek_nilai_substase<1)
		{
		  echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
		  $no=1;
		  $kelas = "ganjil";
		  while ($data_substase=mysqli_fetch_array($nilai_substase))
		  {
		    $tanggal_isi = tanggal_indo($data_substase[tgl_isi]);
		    $tanggal_approval = tanggal_indo($data_substase[tgl_approval]);
		    $data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_substase[nim]'"));
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    echo "<td>$tanggal_isi</td>";
		    echo "<td>Penilaian Substase Dosen Ke-$data_substase[dosen_ke]<br><br>";
		    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
		    echo "Nilai: $data_substase[nilai_rata]</i></td>";
		    echo "<td align=center>";
		    if ($data_substase[status_approval]=='0')
		    {
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
		      echo "<a href=\"approve_substase_dosen.php?id=$data_substase[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_substase[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
		    }
		    else
		    {
		      echo "<font style=\"color:green\">DISETUJUI</font><br>";
		      echo "per tanggal<br>";
		      echo "$tanggal_approval<p>";
		      echo "<a href=\"view_form_substase.php?id=$data_substase[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_substase[id]\" value=\"VIEW\"></a><p>";
		      echo "<a href=\"unapprove_substase_dosen.php?id=$data_substase[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_substase[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
		    }
		    echo "</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		}
		echo "</table><br><br>";

		//Penilaian Referat
		echo "<a id=\"referat\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Referat</font></a><br><br>";
		$nilai_referat = mysqli_query($con,"SELECT * FROM `forensik_nilai_referat` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze4\" style=\"width:100%\">";
		echo "<thead>
		      <th style=\"width:5%\">No</th>
		      <th style=\"width:15%\">Tanggal Pengisian</th>
		      <th style=\"width:65%\">Jenis Penilaian</th>
		      <th style=\"width:15%\">Status Approval</th>
		      </thead>";
		$cek_nilai_referat = mysqli_num_rows($nilai_referat);
		if ($cek_nilai_referat<1)
		{
		  echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
		  $no=1;
		  $kelas = "ganjil";
		  while ($data_referat=mysqli_fetch_array($nilai_referat))
		  {
		    $tanggal_isi = tanggal_indo($data_referat[tgl_isi]);
		    $tanggal_approval = tanggal_indo($data_referat[tgl_approval]);
		    $data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_referat[nim]'"));
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    echo "<td>$tanggal_isi</td>";
		    echo "<td>Penilaian Referat<br><br>";
		    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
		    echo "Nilai: $data_referat[nilai_rata]</i></td>";
		    echo "<td align=center>";
		    if ($data_referat[status_approval]=='0')
		    {
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
		      echo "<a href=\"approve_referat_dosen.php?id=$data_referat[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_referat[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
		    }
		    else
		    {
		      echo "<font style=\"color:green\">DISETUJUI</font><br>";
		      echo "per tanggal<br>";
		      echo "$tanggal_approval<p>";
		      echo "<a href=\"view_form_referat.php?id=$data_referat[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_referat[id]\" value=\"VIEW\"></a><p>";
		      echo "<a href=\"unapprove_referat_dosen.php?id=$data_referat[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_referat[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
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
