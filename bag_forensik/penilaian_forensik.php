<HTML>
<head>
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) KEDOKTERAN FORENSIK DAN MEDIKOLEGAL</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">FORM PENILAIAN KEPANITERAAN (STASE) KEDOKTERAN FORENSIK DAN MEDIKOLEGAL</font></h4><br>";
		$id_stase = "M112";
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$cek_stase = mysqli_num_rows($data_stase_mhsw);
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
		echo "<table style=\"width:60%;border:collapse;\">";
		echo "<tr><td style=\"width:40%;\">Kepaniteraan (stase)</td><td style=\"width:60%;\">: $data_stase[kepaniteraan]</td></tr>";
		if ($cek_stase>=1)
		{
			$tgl_mulai = tanggal_indo($datastase_mhsw[tgl_mulai]);
			$mulai = date_create($datastase_mhsw[tgl_mulai]);
			$tgl_selesai = tanggal_indo($datastase_mhsw[tgl_selesai]);
			echo "<tr><td>Tanggal mulai kepaniteraan (stase)</td><td>: $tgl_mulai</td></tr>";
			echo "<tr><td>Tanggal selesai kepaniteraan (stase)</td><td>: $tgl_selesai</td></tr>";
		}
		else
		{
			echo "<tr><td>Status Kepaniteraan (stase)</td><td>: <font style=\"color:red\">BELUM AKTIF</font></td></tr>";
		}
		echo "</table><br><br>";

		if ($cek_stase>=1)
		{
			echo "</center><br><a href=\"#visum\"><i>Pengisian Formulir Penilaian Visum Bayangan</i></a><br>";
			echo "<a href=\"#jaga\"><i>Pengisian Formulir Penilaian Kegiatan Jaga</i></a><br>";
			echo "<a href=\"#substase\"><i>Pengisian Formulir Penilaian Substase</i></a><br>";
			echo "<a href=\"#referat\"><i>Pengisian Formulir Penilaian Referat</i></a><br>";
			echo "<a href=\"#test\"><i>Preview Nilai-Nilai Test dan Perilaku</i></a><br><br>";

			//Pengisian Formulir Penilaian Visum Bayangan
			echo "<a id=\"visum\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Visum Bayangan</font></a><br><br>";
			$nilai_visum = mysqli_query($con,"SELECT * FROM `forensik_nilai_visum` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze1\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_visum = mysqli_num_rows($nilai_visum);
			if ($cek_nilai_visum<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_visum=mysqli_fetch_array($nilai_visum))
			  {
			    $tanggal_isi = tanggal_indo($data_visum[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_visum[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_visum[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Visum Bayangan Dosen Ke-$data_visum[dosen_ke]<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Nilai: $data_visum[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
			    if ($data_visum[status_approval]=='0') echo "<a href=\"approve_visum.php?id=$data_visum[id]\"><input type=\"button\" name=\"approve\".\"$data_visum[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			    echo "</td>";
			    echo "<td align=center>";
			    if ($data_visum[status_approval]=='0')
			    {
			      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			      echo "<a href=\"edit_form_visum.php?id=$data_visum[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_visum[id]\" value=\"EDIT\"></a><p>";
			      echo "<a href=\"preview_form_visum.php?id=$data_visum[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_visum[id]\" value=\"PREVIEW\"></a><p>";
			      echo "<a href=\"hapus_form_visum.php?id=$data_visum[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_visum[id]\" value=\"HAPUS\"></a>";
			    }
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			      echo "<br><br><a href=\"view_form_visum.php?id=$data_visum[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_visum[id]\" value=\"VIEW\"></a><p>";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			if ($cek_nilai_visum<4)
			echo "<br><center><a href=\"tambah_visum.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_visum = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `forensik_nilai_visum` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_visum>0)
			echo "<br><center><a href=\"cetak_visum.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian Kegiatan Jaga
			echo "<a id=\"jaga\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Kegiatan Jaga</font></a><br><br>";
			$nilai_jaga = mysqli_query($con,"SELECT * FROM `forensik_nilai_jaga` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze2\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_jaga = mysqli_num_rows($nilai_jaga);
			if ($cek_nilai_jaga<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_jaga=mysqli_fetch_array($nilai_jaga))
			  {
			    $tanggal_isi = tanggal_indo($data_jaga[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_jaga[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_jaga[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Kegiatan Jaga Dosen Ke-$data_jaga[dosen_ke]<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Nilai: $data_jaga[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
			    if ($data_jaga[status_approval]=='0') echo "<a href=\"approve_jaga.php?id=$data_jaga[id]\"><input type=\"button\" name=\"approve\".\"$data_jaga[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			    echo "</td>";
			    echo "<td align=center>";
			    if ($data_jaga[status_approval]=='0')
			    {
			      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			      echo "<a href=\"edit_form_jaga.php?id=$data_jaga[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_jaga[id]\" value=\"EDIT\"></a><p>";
			      echo "<a href=\"preview_form_jaga.php?id=$data_jaga[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_jaga[id]\" value=\"PREVIEW\"></a><p>";
			      echo "<a href=\"hapus_form_jaga.php?id=$data_jaga[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_jaga[id]\" value=\"HAPUS\"></a>";
			    }
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			      echo "<br><br><a href=\"view_form_jaga.php?id=$data_jaga[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_jaga[id]\" value=\"VIEW\"></a><p>";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			if ($cek_nilai_jaga<4)
			echo "<br><center><a href=\"tambah_jaga.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_jaga = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `forensik_nilai_jaga` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_jaga>0)
			echo "<br><center><a href=\"cetak_jaga.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian Substase
			echo "<a id=\"substase\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Substase</font></a><br><br>";
			$nilai_substase = mysqli_query($con,"SELECT * FROM `forensik_nilai_substase` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze3\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_substase = mysqli_num_rows($nilai_substase);
			if ($cek_nilai_substase<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_substase=mysqli_fetch_array($nilai_substase))
			  {
			    $tanggal_isi = tanggal_indo($data_substase[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_substase[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_substase[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Substase Dosen Ke-$data_substase[dosen_ke]<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Nilai: $data_substase[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
			    if ($data_substase[status_approval]=='0') echo "<a href=\"approve_substase.php?id=$data_substase[id]\"><input type=\"button\" name=\"approve\".\"$data_substase[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			    echo "</td>";
			    echo "<td align=center>";
			    if ($data_substase[status_approval]=='0')
			    {
			      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			      echo "<a href=\"edit_form_substase.php?id=$data_substase[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_substase[id]\" value=\"EDIT\"></a><p>";
			      echo "<a href=\"preview_form_substase.php?id=$data_substase[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_substase[id]\" value=\"PREVIEW\"></a><p>";
			      echo "<a href=\"hapus_form_substase.php?id=$data_substase[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_substase[id]\" value=\"HAPUS\"></a>";
			    }
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			      echo "<br><br><a href=\"view_form_substase.php?id=$data_substase[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_substase[id]\" value=\"VIEW\"></a><p>";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			if ($cek_nilai_substase<4)
			echo "<br><center><a href=\"tambah_substase.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_substase = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `forensik_nilai_substase` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_substase>0)
			echo "<br><center><a href=\"cetak_substase.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian Referat
			echo "<a id=\"referat\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Referat</font></a><br><br>";
			$nilai_referat = mysqli_query($con,"SELECT * FROM `forensik_nilai_referat` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze4\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_referat = mysqli_num_rows($nilai_referat);
			if ($cek_nilai_referat<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_referat=mysqli_fetch_array($nilai_referat))
			  {
			    $tanggal_isi = tanggal_indo($data_referat[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_referat[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_referat[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Referat<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Nilai: $data_referat[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
			    if ($data_referat[status_approval]=='0') echo "<a href=\"approve_referat.php?id=$data_referat[id]\"><input type=\"button\" name=\"approve\".\"$data_referat[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			    echo "</td>";
			    echo "<td align=center>";
			    if ($data_referat[status_approval]=='0')
			    {
			      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			      echo "<a href=\"edit_form_referat.php?id=$data_referat[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_referat[id]\" value=\"EDIT\"></a><p>";
			      echo "<a href=\"preview_form_referat.php?id=$data_referat[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_referat[id]\" value=\"PREVIEW\"></a><p>";
			      echo "<a href=\"hapus_form_referat.php?id=$data_referat[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_referat[id]\" value=\"HAPUS\"></a>";
			    }
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			      echo "<br><br><a href=\"view_form_referat.php?id=$data_referat[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_referat[id]\" value=\"VIEW\"></a><p>";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			if ($cek_nilai_referat<1)
			echo "<br><center><a href=\"tambah_referat.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_referat = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `forensik_nilai_referat` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_referat>0)
			echo "<br><center><a href=\"cetak_referat.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Preview Nilai-Nilai Test dan Perilaku
			echo "<a id=\"test\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai Test dan Perilaku</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `forensik_nilai_test` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze5\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Ujian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Kordik Kepaniteraan</th>
			      <th style=\"width:15%\">Nilai</th>
			      </thead>";
			$cek_nilai_test = mysqli_num_rows($nilai_test);
			if ($cek_nilai_test<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_test=mysqli_fetch_array($nilai_test))
			  {
			    $jenis_test = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `jenis_test` WHERE `id`='$data_test[jenis_test]'"));
			    $tanggal_test = tanggal_indo($data_test[tgl_test]);
			    $status_ujian = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `status_ujian` WHERE `id`='$data_test[status_ujian]'"));
			    $tanggal_approval = tanggal_indo($data_test[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_test[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_test</td>";
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal<br><br>";
			    echo "<i>Status Ujian/Test: $status_ujian[status_ujian]<br>";
			    echo "Catatan: $data_test[catatan]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
			    echo "<i>Tanggal Yudisium: $tanggal_approval</i>";
			    echo "</td>";
			    $nilai = number_format($data_test[nilai],2);
			    echo "<td align=center>$nilai</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			$cek_approved_test = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `forensik_nilai_test` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_test>0)
			echo "<br><center><a href=\"cetak_nilai_test.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";

			echo "<br><br><br><center><a href=\"cetak_nilai_forensik.php\" target=\"_BLANK\"><input type=\"button\" id=\"cetak_nilai\" class=\"submit1\" name=\"cetak_nilai\" value=\"CETAK NILAI\"></a>";
			echo "</center><br><br>";

		}

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
		 $("#freeze5").freezeHeader();
	});
</script>
<!--</body></html>-->
</BODY>
</HTML>
