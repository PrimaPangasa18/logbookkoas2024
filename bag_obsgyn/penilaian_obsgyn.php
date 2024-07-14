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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KEBIDANAN DAN PENYAKIT KANDUNGAN</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">FORM PENILAIAN KEPANITERAAN (STASE) ILMU KEBIDANAN DAN PENYAKIT KANDUNGAN</font></h4><br>";
		$id_stase = "M111";
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
			echo "</center><br><a href=\"#minicex\"><i>Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)</i></a><br>";
			echo "<a href=\"#cbd\"><i>Pengisian Formulir Penilaian Case-Based Discussion (CBD)</i></a><br>";
			echo "<a href=\"#jurnal\"><i>Pengisian Formulir Penilaian Journal Reading</i></a><br>";
			echo "<a href=\"#test\"><i>Preview Nilai Penugasan dan Test (DOPS/OSCE, MCQ, dan MINI-PAT)</i></a><br><br>";

			//Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)
			echo "<a id=\"minicex\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)</font></a><br><br>";
			$nilai_minicex = mysqli_query($con,"SELECT * FROM `obsgyn_nilai_minicex` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze1\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penguji</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_minicex = mysqli_num_rows($nilai_minicex);
			if ($cek_nilai_minicex<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
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

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Ujian MINI-CEX (Mini Clinical Examination)<br><br>";
					echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Tanggal Ujian: $tanggal_ujian<br>";
					echo "Nilai Ujian: $data_minicex[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
			    if ($data_minicex[status_approval]=='0') echo "<a href=\"approve_minicex.php?id=$data_minicex[id]\"><input type=\"button\" name=\"approve\".\"$data_minicex[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			    echo "</td>";
			    echo "<td align=center>";
			    if ($data_minicex[status_approval]=='0')
			    {
			      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			      echo "<a href=\"edit_form_minicex.php?id=$data_minicex[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_minicex[id]\" value=\"EDIT\"></a><p>";
			      echo "<a href=\"preview_form_minicex.php?id=$data_minicex[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_minicex[id]\" value=\"PREVIEW\"></a><p>";
			      echo "<a href=\"hapus_form_minicex.php?id=$data_minicex[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_minicex[id]\" value=\"HAPUS\"></a>";
			    }
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			      echo "<br><br><a href=\"view_form_minicex.php?id=$data_minicex[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_minicex[id]\" value=\"VIEW\"></a><p>";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			if ($cek_nilai_minicex<1)
			echo "<center><br><a href=\"tambah_minicex.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>&nbsp;&nbsp;&nbsp;&nbsp;";
			$cek_approved_minicex = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `obsgyn_nilai_minicex` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_minicex>0)
			echo "<center><br><a href=\"cetak_minicex.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian CBD
			echo "<a id=\"cbd\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian CBD</font></a><br><br>";
			$nilai_cbd = mysqli_query($con,"SELECT * FROM `obsgyn_nilai_cbd` WHERE `nim`='$_COOKIE[user]'");
			echo "<font style=\"font-size:0.675em\"><i>Catatan:<br>- Pengisian wajib untuk penilaian CBD adalah 4 (empat) kali.<br>- Nilai rata-rata adalah jumlah total nilai CBD dibagi 4 (empat).<br>- Untuk cetak, minimal 1 (satu) penilaian CBD telah disetujui Dosen Penilai.</i></font><br><br>";
			echo "<table id=\"freeze2\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai/Penguji</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_cbd = mysqli_num_rows($nilai_cbd);
			if ($cek_nilai_cbd<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_cbd=mysqli_fetch_array($nilai_cbd))
			  {
					$tanggal_isi = tanggal_indo($data_cbd[tgl_isi]);
					$tanggal_ujian = tanggal_indo($data_cbd[tgl_ujian]);
			    $tanggal_approval = tanggal_indo($data_cbd[tgl_approval]);
					$awal_periode = tanggal_indo($data_cbd[tgl_awal]);
					$akhir_periode = tanggal_indo($data_cbd[tgl_akhir]);

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Case-Based Discussion (CBD)<br><br>";
					echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
					echo "Kasus $data_cbd[kasus_ke] - Tanggal Ujian: $tanggal_ujian<br>";
					echo "Fokus Kasus: $data_cbd[fokus_kasus]<br>";
					echo "Nilai: $data_cbd[nilai_rata]</i>";
					echo "</td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
			    if ($data_cbd[status_approval]=='0') echo "<a href=\"approve_cbd.php?id=$data_cbd[id]\"><input type=\"button\" name=\"approve\".\"$data_cbd[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			    echo "</td>";
			    echo "<td align=center>";
			    if ($data_cbd[status_approval]=='0')
			    {
			      echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			      echo "<a href=\"edit_form_cbd.php?id=$data_cbd[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_cbd[id]\" value=\"EDIT\"></a><p>";
			      echo "<a href=\"preview_form_cbd.php?id=$data_cbd[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_cbd[id]\" value=\"PREVIEW\"></a><p>";
			      echo "<a href=\"hapus_form_cbd.php?id=$data_cbd[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_cbd[id]\" value=\"HAPUS\"></a>";
			    }
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			      echo "<br><br><a href=\"view_form_cbd.php?id=$data_cbd[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_cbd[id]\" value=\"VIEW\"></a><p>";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			$cek_all_cbd = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `obsgyn_nilai_cbd` WHERE `nim`='$_COOKIE[user]'"));
			$cek_approved_cbd = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `obsgyn_nilai_cbd` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_all_cbd<4)
			{
			  echo "<br><center><a href=\"tambah_cbd.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			  if ($cek_approved_cbd>0)
			  echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"cetak_cbd.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			}
			else
			{
			  if ($cek_approved_cbd>0)
			  echo "<br><center><a href=\"cetak_cbd.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			}
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian Journal Reading
			echo "<a id=\"jurnal\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Journal Reading</font></a><br><br>";
			$nilai_jurnal = mysqli_query($con,"SELECT * FROM `obsgyn_nilai_jurnal` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze3\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
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
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>";
			    echo "NIM: $data_mhsw[nim]<br>";
			    echo "Jurnal:$data_jurnal[nama_jurnal]<br>";
			    echo "Judul Artikel: $data_jurnal[judul_paper]<br>";
			    echo "Nilai: $data_jurnal[nilai_rata]</i></td>";
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
			$cek_approved_jurnal = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `obsgyn_nilai_jurnal` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_jurnal>0)
			echo "<br><center><a href=\"cetak_jurnal.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Preview Nilai Penugasan dan Test (DOPS/OSCE, MCQ, dan MINI-PAT)
			echo "<a id=\"test\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai Penugasan dan Test (DOPS/OSCE, MCQ, dan MINI-PAT)</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `obsgyn_nilai_test` WHERE `nim`='$_COOKIE[user]'");
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
					$status_ujian = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `status_ujian` WHERE `id`='$data_test[status_ujian]'"));
					$tanggal_test = tanggal_indo($data_test[tgl_test]);
			    $tanggal_approval = tanggal_indo($data_test[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_test[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_test</td>";
			    echo "<td>Penilaian $jenis_test[jenis_test]<br><br>";
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
			$cek_approved_test = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `obsgyn_nilai_test` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_test>0)
			echo "<br><center><a href=\"cetak_nilai_test.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"cetak\" value=\"CETAK\"></a>";

			echo "<br><br><br><center><a href=\"cetak_nilai_obsgyn.php\" target=\"_BLANK\"><input type=\"button\" id=\"cetak_nilai\" class=\"submit1\" name=\"cetak_nilai\" value=\"CETAK NILAI\"></a>";
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
