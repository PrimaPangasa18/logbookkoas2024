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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) IKGM</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">FORM PENILAIAN KEPANITERAAN (STASE) IKGM</font></h4><br>";
		$id_stase = "M106";
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
			echo "</center><br><a href=\"#kasus\"><i>Pengisian Formulir Penilaian Laporan Kasus</i></a><br>";
			echo "<a href=\"#jurnal\"><i>Pengisian Formulir Penilaian Journal Reading</i></a><br>";
			echo "<a href=\"#responsi\"><i>Pengisian Formulir Penilaian Responsi Kasus Kecil</i></a><br>";
			echo "<a href=\"#test\"><i>Preview Nilai-Nilai Test dan Perilaku</i></a><br><br>";

			//Pengisian Formulir Penilaian Laporan Kasus
			echo "<a id=\"kasus\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Laporan Kasus</font></a><br><br>";
			$nilai_kasus = mysqli_query($con,"SELECT * FROM `ikgm_nilai_kasus` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze1\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_kasus = mysqli_num_rows($nilai_kasus);
			if ($cek_nilai_kasus<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_kasus=mysqli_fetch_array($nilai_kasus))
			  {
			    $tanggal_isi = tanggal_indo($data_kasus[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_kasus[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Kegiatan Laporan Kasus<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
					echo "Nama/Judul Kasus: $data_kasus[nama_kasus]<br>";
					echo "Nilai: $data_kasus[nilai_total]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
			    if ($data_kasus[status_approval]=='0') echo "<a href=\"approve_kasus.php?id=$data_kasus[id]\"><input type=\"button\" name=\"approve\".\"$data_kasus[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			    echo "</td>";
			    echo "<td align=center>";
			    if ($data_kasus[status_approval]=='0')
			    {
			      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			      echo "<a href=\"edit_form_kasus.php?id=$data_kasus[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_kasus[id]\" value=\"EDIT\"></a><p>";
			      echo "<a href=\"preview_form_kasus.php?id=$data_kasus[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_kasus[id]\" value=\"PREVIEW\"></a><p>";
			      echo "<a href=\"hapus_form_kasus.php?id=$data_kasus[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_kasus[id]\" value=\"HAPUS\"></a>";
			    }
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			      echo "<br><br><a href=\"view_form_kasus.php?id=$data_kasus[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_kasus[id]\" value=\"VIEW\"></a><p>";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			if ($cek_nilai_kasus<1)
			echo "<br><center><a href=\"tambah_kasus.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_kasus = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `ikgm_nilai_kasus` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_kasus>0)
			echo "<br><center><a href=\"cetak_kasus.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian Journal Reading
			echo "<a id=\"jurnal\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Journal Reading</font></a><br><br>";
			$nilai_jurnal = mysqli_query($con,"SELECT * FROM `ikgm_nilai_jurnal` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze2\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penguji (Tutor)</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_jurnal = mysqli_num_rows($nilai_jurnal);
			if ($cek_nilai_jurnal<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_jurnal=mysqli_fetch_array($nilai_jurnal))
			  {
			    $tanggal_isi = tanggal_indo($data_jurnal[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_jurnal[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Journal Reading<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Jurnal: $data_jurnal[nama_jurnal]<br>";
			    echo "Judul Artikel: $data_jurnal[judul_paper]<br>Nilai: $data_jurnal[nilai_total]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
			    if ($data_jurnal[status_approval]=='0') echo "<a href=\"approve_jurnal.php?id=$data_jurnal[id]\"><input type=\"button\" name=\"approve\".\"$data_jurnal[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			    echo "</td>";
			    echo "<td align=center>";
			    if ($data_jurnal[status_approval]=='0')
			    {
			      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			      echo "<a href=\"edit_form_jurnal.php?id=$data_jurnal[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_jurnal[id]\" value=\"EDIT\"></a><p>";
			      echo "<a href=\"preview_form_jurnal.php?id=$data_jurnal[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_jurnal[id]\" value=\"PREVIEW\"></a><p>";
			      echo "<a href=\"hapus_form_jurnal.php?id=$data_jurnal[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_jurnal[id]\" value=\"HAPUS\"></a>";
			    }
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			      echo "<br><br><a href=\"view_form_jurnal.php?id=$data_jurnal[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_jurnal[id]\" value=\"VIEW\"></a><p>";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			if ($cek_nilai_jurnal<1)
			echo "<br><center><a href=\"tambah_jurnal.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_jurnal = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `ikgm_nilai_jurnal` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_jurnal>0)
			echo "<br><center><a href=\"cetak_jurnal.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian Responsi Kasus Kecil
			echo "<a id=\"responsi\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Responsi Kasus Kecil</font></a><br><br>";
			$nilai_responsi = mysqli_query($con,"SELECT * FROM `ikgm_nilai_responsi` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze3\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Pembimbing/Penguji</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_responsi = mysqli_num_rows($nilai_responsi);
			if ($cek_nilai_responsi<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_responsi=mysqli_fetch_array($nilai_responsi))
			  {
			    $tanggal_isi = tanggal_indo($data_responsi[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_responsi[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_responsi[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Responsi Kasus Kecil Ke-$data_responsi[kasus_ke]<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Nilai: $data_responsi[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
			    if ($data_responsi[status_approval]=='0') echo "<a href=\"approve_responsi.php?id=$data_responsi[id]\"><input type=\"button\" name=\"approve\".\"$data_responsi[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			    echo "</td>";
			    echo "<td align=center>";
			    if ($data_responsi[status_approval]=='0')
			    {
			      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			      echo "<a href=\"edit_form_responsi.php?id=$data_responsi[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_responsi[id]\" value=\"EDIT\"></a><p>";
			      echo "<a href=\"preview_form_responsi.php?id=$data_responsi[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_responsi[id]\" value=\"PREVIEW\"></a><p>";
			      echo "<a href=\"hapus_form_responsi.php?id=$data_responsi[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_responsi[id]\" value=\"HAPUS\"></a>";
			    }
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			      echo "<br><br><a href=\"view_form_responsi.php?id=$data_responsi[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_responsi[id]\" value=\"VIEW\"></a><p>";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			echo "<br><center><a href=\"tambah_responsi.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_responsi = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `ikgm_nilai_responsi` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_responsi>0)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"cetak_responsi.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Preview Nilai-Nilai Test dan Perilaku
			echo "<a id=\"test\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai Test dan Perilaku</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `ikgm_nilai_test` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze4\" style=\"width:100%\">";
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
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) IKGM<br><br>";
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
			$cek_approved_test = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `ikgm_nilai_test` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_test>0)
			echo "<br><center><a href=\"cetak_nilai_test.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";

			echo "<br><br><br><center><a href=\"cetak_nilai_ikgm.php\" target=\"_BLANK\"><input type=\"button\" id=\"cetak_nilai\" class=\"submit1\" name=\"cetak_nilai\" value=\"CETAK NILAI\"></a>";
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
	});
</script>
<!--</body></html>-->
</BODY>
</HTML>
