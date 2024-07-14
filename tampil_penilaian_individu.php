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
		echo "<div class=\"text_header\" id=\"top\">DATA PENILAIAN BAGIAN / KEPANITERAAN (STASE) PER INDIVIDU</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">PENILAIAN BAGIAN / KEPANITERAAN (STASE) PER INDIVIDU</font></h4>";

		$id_stase = $_GET[stase];
		$stase_id = "stase_".$id_stase;

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama`,`nim` FROM `biodata_mhsw` WHERE `nim`='$_GET[nim]'"));
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
		$nama_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$tanggal_mulai = tanggal_indo($data_stase[tgl_mulai]);
		$tanggal_selesai = tanggal_indo($data_stase[tgl_selesai]);
		$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		echo "<table style=\"border:collapse;\">";
		echo "<tr><td>Nama Mahasiswa</td><td>: $data_mhsw[nama]</td></tr>";
		echo "<tr><td>NIM</td><td>: $data_mhsw[nim]</td></tr>";
		echo "<tr><td>Kepaniteraan (Stase)</td><td>: $nama_stase[kepaniteraan]</td></tr>";
		echo "<tr><td>Periode Kepaniteraan (Stase)</td><td>: $periode</td></tr>";
		echo "</table><br><br>";

		//-------------------------------------
		//Kepaniteraan (Stase) Ilmu Kesehatan Penyakit Dalam
		//-------------------------------------
		if ($id_stase=="M091")
		{
			echo "</center><br><a href=\"#minicex_M091\"><i>Pengisian Formulir Penilaian Mini-Cex</i></a><br>";
			echo "<a href=\"#kasus_M091\"><i>Pengisian Formulir Penilaian Penyajian Kasus Besar</i></a><br>";
			echo "<a href=\"#ujian_M091\"><i>Pengisian Formulir Penilaian Ujian Akhir</i></a><br>";
			echo "<a href=\"#test_M091\"><i>Preview Nilai Ujian Tulis MCQ</i></a><br><br>";

			//Pengisian Formulir Penilaian MINI-CEX
			echo "<a id=\"minicex_M091\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Mini-Cex</font></a><br><br>";
			echo "<font style=\"font-size:0.675em\"><i>Catatan:<br>- Pengisian wajib untuk penilaian Mini-Cex adalah 7 (tujuh) kali.<br>- Nilai rata-rata adalah jumlah total nilai Mini-Cex dibagi 7 (tujuh).<br>- Untuk cetak, minimal 1 (satu) penilaian Mini-Cex telah disetujui Dosen Penilai.</i></font><br><br>";
			$nilai_minicex = mysqli_query($con,"SELECT * FROM `ipd_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M091\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
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
			    echo "<td>Penilaian Mini-Cex<br>Ruangan/Bangsal: $data_minicex[ruangan]<br><br>";
					echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Tanggal Penilaian: $tanggal_ujian<br>";
					echo "Rata-Rata Nilai: $data_minicex[nilai_rata]</i></td>";
		      echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
		      echo "<td align=center>";
		      if ($data_minicex[status_approval]=='0')
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
		      else
		      {
		        echo "<font style=\"color:green\">DISETUJUI</font><br>";
		        echo "per tanggal<br>";
		        echo "$tanggal_approval";
		      }
		      echo "</td>";
		      echo "</tr>";
		      $no++;
		      if ($kelas=="ganjil") $kelas="genap";
		      else $kelas="ganjil";
		    }
		  }
		  echo "</table><br><br>";

			//Pengisian Formulir Penilaian Penyajian Kasus Besar
			echo "<a id=\"kasus_M091\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Penyajian Kasus Besar</font></a><br><br>";
			$nilai_kasus = mysqli_query($con,"SELECT * FROM `ipd_nilai_kasus` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M091\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Penyajian</th>
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
			    $tanggal_penyajian = tanggal_indo($data_kasus[tgl_penyajian]);
			    $tanggal_approval = tanggal_indo($data_kasus[tgl_approval]);
					$awal_periode = tanggal_indo($data_kasus[tgl_awal]);
					$akhir_periode = tanggal_indo($data_kasus[tgl_akhir]);
					$periode = $awal_periode." s.d. ".$akhir_periode;

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_penyajian</td>";
			    echo "<td>Penilaian Penyajian Kasus Besar<br>Periode Stase: $periode<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
					echo "Kasus: $data_kasus[kasus]<br>";
					echo "Nilai: $data_kasus[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_minicex[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Ujian Akhir
			echo "<a id=\"ujian_M091\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian Akhir</font></a><br><br>";
			$nilai_ujian = mysqli_query($con,"SELECT * FROM `ipd_nilai_ujian` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M091\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Supervisor</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_ujian = mysqli_num_rows($nilai_ujian);
			if ($cek_nilai_ujian<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_ujian=mysqli_fetch_array($nilai_ujian))
			  {
			    $tanggal_isi = tanggal_indo($data_ujian[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_ujian[tgl_approval]);
					$awal_periode = tanggal_indo($data_ujian[tgl_awal]);
					$akhir_periode = tanggal_indo($data_ujian[tgl_akhir]);
					$periode = $awal_periode." s.d. ".$akhir_periode;

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_ujian[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Ujian Akhir<br>Periode Stase: $periode<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
					echo "Nilai Ujian: $data_ujian[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_minicex[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

		  //Preview Nilai-Nilai Test dan Perilaku
		  echo "<a id=\"test_M091\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai OSCE, Ujian Teori dan Perilaku</font></a><br><br>";
		  $nilai_test = mysqli_query($con,"SELECT * FROM `ipd_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
		  echo "<table id=\"freeze4__M091\" style=\"width:100%\">";
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
		      echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) Ilmu Penyakit Dalam<br><br>";
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
		  echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Ilmu Penyakit Dalam
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Neurologi
		//-------------------------------------
		if ($id_stase=="M092")
		{
			echo "</center><br><a href=\"#cbd_M092\"><i>Pengisian Formulir Penilaian Kasus CBD</i></a><br>";
			echo "<a href=\"#jurnal_M092\"><i>Pengisian Formulir Penilaian Journal Reading</i></a><br>";
			echo "<a href=\"#spv_M092\"><i>Pengisian Formulir Penilaian Ujian SPV</i></a><br>";
			echo "<a href=\"#minicex_M092\"><i>Pengisian Formulir Penilaian Ujian Minicex</i></a><br>";
			echo "<a href=\"#test_M092\"><i>Preview Nilai Penugasan dan Test</i></a><br><br>";

			//Pengisian Formulir Penilaian Kasus CBD
			echo "<a id=\"cbd_M092\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Kasus CBD</font></a><br><br>";
			$nilai_cbd = mysqli_query($con,"SELECT * FROM `neuro_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M092\" style=\"width:100%\">";
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
			    echo "<td>Penilaian Kasus CBD<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Kasus $data_cbd[kasus_ke] - Tanggal Ujian: $tanggal_ujian<br>";
			    echo "Fokus Kasus: $data_cbd[fokus_kasus]<br>";
			    echo "Nilai: $data_cbd[nilai_rata]</i>";
			    echo "</td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_cbd[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Journal Reading
			echo "<a id=\"jurnal_M092\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Journal Reading</font></a><br><br>";
			$nilai_jurnal = mysqli_query($con,"SELECT * FROM `neuro_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M092\" style=\"width:100%\">";
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
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_jurnal[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			   }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Ujian SPV
			echo "<a id=\"spv_M092\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian SPV</font></a><br><br>";
			$nilai_spv = mysqli_query($con,"SELECT * FROM `neuro_nilai_spv` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M092\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penguji</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_spv = mysqli_num_rows($nilai_spv);
			if ($cek_nilai_spv<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_spv=mysqli_fetch_array($nilai_spv))
			  {
			    $tanggal_isi = tanggal_indo($data_spv[tgl_isi]);
			    $tanggal_ujian = tanggal_indo($data_spv[tgl_ujian]);
			    $tanggal_approval = tanggal_indo($data_spv[tgl_approval]);

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_spv[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Ujian SPV<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Tanggal Ujian: $tanggal_ujian<br>";
			    echo "Nilai Ujian: $data_spv[nilai]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_spv[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Ujian MINI-CEX
			echo "<a id=\"minicex_M092\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian MINI-CEX</font></a><br><br>";
			$nilai_minicex = mysqli_query($con,"SELECT * FROM `neuro_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze4_M092\" style=\"width:100%\">";
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
			    $awal_periode = tanggal_indo($data_minicex[tgl_awal]);
			    $akhir_periode = tanggal_indo($data_minicex[tgl_akhir]);

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Ujian MINI-CEX<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Tanggal Ujian: $tanggal_ujian<br>";
			    echo "Nilai Ujian: $data_minicex[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_minicex[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Preview Nilai Ujian/Test (id test lihat tabel jenis_test)
			echo "<a id=\"test_M092\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai Ujian/Test</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `neuro_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze5_M092\" style=\"width:100%\">";
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
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) Neurologi<br><br>";
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
			echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Neurologi
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Psikiatri
		//-------------------------------------
		if ($id_stase=="M093")
		{
			echo "<br></center><a href=\"#jurnal_M093\"><i>Pengisian Formulir Penilaian Journal Reading</i></a><br>";
			echo "<a href=\"#cbd_M093\"><i>Pengisian Formulir Penilaian CBD</i></a><br>";
			echo "<a href=\"#minicex_M093\"><i>Pengisian Formulir Penilaian Ujian MINI-CEX</i></a><br>";
			echo "<a href=\"#osce_M093\"><i>Pengisian Formulir Penilaian Ujian OSCE</i></a><br>";
			echo "<a href=\"#test_M093\"><i>Preview Nilai-Nilai Test</i></a><br><br>";

			//Pengisian Formulir Penilaian Journal Reading
			echo "<a id=\"jurnal_M093\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Journal Reading</font></a><br><br>";
			$nilai_jurnal = mysqli_query($con,"SELECT * FROM `psikiatri_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M093\" style=\"width:100%\">";
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
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>";
			    echo "NIM: $data_mhsw[nim]<br>";
			    echo "<i>Jurnal:$data_jurnal[nama_jurnal]<br>";
			    echo "Judul Artikel: $data_jurnal[judul_paper]<br>";
			    echo "Nilai: $data_jurnal[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_jurnal[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian CBD
			echo "<a id=\"cbd_M093\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian CBD</font></a><br><br>";
			$nilai_cbd = mysqli_query($con,"SELECT * FROM `psikiatri_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M093\" style=\"width:100%\">";
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
			    echo "<td>Penilaian Kasus CBD<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>";
			    echo "NIM: $data_mhsw[nim]<br>";
			    echo "Fokus Pertemuan Klinik: $data_cbd[fokus_pertemuan]<br>";
			    echo "Tanggal Ujian: $tanggal_ujian<br>";
			    echo "Nilai: $data_cbd[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_cbd[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian MINI-CEX
			echo "<a id=\"minicex_M093\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian MINI-CEX</font></a><br><br>";
			$nilai_minicex = mysqli_query($con,"SELECT * FROM `psikiatri_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M093\" style=\"width:100%\">";
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
			    $awal_periode = tanggal_indo($data_minicex[tgl_awal]);
			    $akhir_periode = tanggal_indo($data_minicex[tgl_akhir]);

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian MINI-CEX<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>";
			    echo "NIM: $data_mhsw[nim]<br>";
			    echo "Tanggal Ujian: $tanggal_ujian<br>";
			    echo "Nilai: $data_minicex[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_minicex[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Ujian OSCE
			echo "<a id=\"osce_M093\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian OSCE</font></a><br><br>";
			$nilai_osce = mysqli_query($con,"SELECT * FROM `psikiatri_nilai_osce` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze4_M093\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penguji</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_osce = mysqli_num_rows($nilai_osce);
			if ($cek_nilai_osce<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_osce=mysqli_fetch_array($nilai_osce))
			  {
			    $tanggal_isi = tanggal_indo($data_osce[tgl_isi]);
			    $tanggal_ujian = tanggal_indo($data_osce[tgl_ujian]);
			    $tanggal_approval = tanggal_indo($data_osce[tgl_approval]);

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_osce[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Ujian OSCE<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>";
			    echo "NIM: $data_mhsw[nim]<br>";
			    echo "Tanggal Ujian: $tanggal_ujian<br>";
			    echo "Nilai: $data_osce[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_osce[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Preview Nilai Test (id test lihat tabel jenis_test)
			echo "<a id=\"test_M093\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai Test</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `psikiatri_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze5_M093\" style=\"width:100%\">";
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
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) Ilmu Kesehatan Jiwa<br><br>";
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
			echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Psikiatri
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Psikiatri
		//-------------------------------------
		if ($id_stase=="M105")
		{
		  echo "</center><br><a href=\"#presentasi_M105\"><i>Pengisian Formulir Penilaian Presentasi Kasus</i></a><br>";
		  echo "<a href=\"#responsi_M105\"><i>Pengisian Formulir Penilaian Responsi Kasus Kecil</i></a><br>";
		  echo "<a href=\"#jurnal_M105\"><i>Pengisian Formulir Penilaian Journal Reading</i></a><br>";
		  echo "<a href=\"#test_M105\"><i>Preview Nilai Test</i></a><br>";
		  echo "<a href=\"#osce_M105\"><i>Pengisian Formulir Penilaian Ujian OSCE</i></a><br><br>";

		  //Pengisian Formulir Penilaian Presentasi Kasus
		  echo "<a id=\"presentasi_M105\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Presentasi Kasus</font></a><br><br>";
		  $nilai_presentasi = mysqli_query($con,"SELECT * FROM `thtkl_nilai_presentasi` WHERE `nim`='$data_mhsw[nim]'");
		  echo "<table id=\"freeze1_M105\" style=\"width:100%\">";
		  echo "<thead>
		        <th style=\"width:5%\">No</th>
		        <th style=\"width:15%\">Tanggal Pengisian</th>
		        <th style=\"width:40%\">Jenis Penilaian</th>
		        <th style=\"width:25%\">Dosen Pembimbing/Penguji</th>
		        <th style=\"width:15%\">Status Approval</th>
		        </thead>";
		  $cek_nilai_presentasi = mysqli_num_rows($nilai_presentasi);
		  if ($cek_nilai_presentasi<1)
		  {
		    echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
		  }
		  else
		  {
		    $no=1;
		    $kelas = "ganjil";
		    while ($data_presentasi=mysqli_fetch_array($nilai_presentasi))
		    {
		      $tanggal_isi = tanggal_indo($data_presentasi[tgl_isi]);
		      $tanggal_approval = tanggal_indo($data_presentasi[tgl_approval]);
		      $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_presentasi[dosen]'"));
		      echo "<tr class=\"$kelas\">";
		      echo "<td align=center>$no</td>";
		      echo "<td>$tanggal_isi</td>";
		      echo "<td>Penilaian Presentasi Kasus<br><br>";
		      echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
		      echo "Nilai: $data_presentasi[nilai_rata]</i></td>";
		      echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
		      echo "<td align=center>";
		      if ($data_presentasi[status_approval]=='0')
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
		      else
		      {
		        echo "<font style=\"color:green\">DISETUJUI</font><br>";
		        echo "per tanggal<br>";
		        echo "$tanggal_approval";
		      }
		      echo "</td>";
		      echo "</tr>";
		      $no++;
		      if ($kelas=="ganjil") $kelas="genap";
		      else $kelas="ganjil";
		    }
		  }
		  echo "</table><br><br>";

		  //Pengisian Formulir Penilaian Responsi Kasus Kecil
		  echo "<a id=\"responsi_M105\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Responsi Kasus Kecil</font></a><br><br>";
		  $nilai_responsi = mysqli_query($con,"SELECT * FROM `thtkl_nilai_responsi` WHERE `nim`='$data_mhsw[nim]'");
		  echo "<table id=\"freeze2_M105\" style=\"width:100%\">";
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
		      echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
		      echo "<td align=center>";
		      if ($data_responsi[status_approval]=='0')
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
		      else
		      {
		        echo "<font style=\"color:green\">DISETUJUI</font><br>";
		        echo "per tanggal<br>";
		        echo "$tanggal_approval";
		      }
		      echo "</td>";
		      echo "</tr>";
		      $no++;
		      if ($kelas=="ganjil") $kelas="genap";
		      else $kelas="ganjil";
		    }
		  }
		  echo "</table><br><br>";

		  //Pengisian Formulir Penilaian Journal Reading
		  echo "<a id=\"jurnal_M105\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Journal Reading</font></a><br><br>";
		  $nilai_jurnal = mysqli_query($con,"SELECT * FROM `thtkl_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
		  echo "<table id=\"freeze3_M105\" style=\"width:100%\">";
		  echo "<thead>
		        <th style=\"width:5%\">No</th>
		        <th style=\"width:15%\">Tanggal Pengisian</th>
		        <th style=\"width:40%\">Jenis Penilaian</th>
		        <th style=\"width:25%\">Dosen Pembimbing/Penguji</th>
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
		      echo "<td>Penilaian Jounal Reading Ke-$data_jurnal[jurnal_ke]<br><br>";
		      echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
		      echo "Jurnal:$data_jurnal[nama_jurnal]<br>";
		      echo "Judul Artikel: $data_jurnal[judul_paper]<br>";
		      echo "Nilai: $data_jurnal[nilai_rata]</i></td>";
		      echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
		      echo "<td align=center>";
		      if ($data_jurnal[status_approval]=='0')
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
		      else
		      {
		        echo "<font style=\"color:green\">DISETUJUI</font><br>";
		        echo "per tanggal<br>";
		        echo "$tanggal_approval";
		      }
		      echo "</td>";
		      echo "</tr>";
		      $no++;
		      if ($kelas=="ganjil") $kelas="genap";
		      else $kelas="ganjil";
		    }
		  }
		  echo "</table><br><br>";

		  //Preview Nilai Test (id Test lihat tabel jenis_test)
		  echo "<a id=\"test_M105\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai Test</font></a><br><br>";
		  $nilai_test = mysqli_query($con,"SELECT * FROM `thtkl_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
		  echo "<table id=\"freeze4_M105\" style=\"width:100%\">";
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
		      echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan THT-KL<br><br>";
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
		  echo "</table><br><br>";

		  //Pengisian Formulir Penilaian Ujian OSCE
		  echo "<a id=\"osce_M105\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian OSCE</font></a><br><br>";
		  $nilai_osce = mysqli_query($con,"SELECT * FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' ORDER BY `tgl_isi`,`tgl_ujian`");
		  echo "<table id=\"freeze5_M105\" style=\"width:100%\">";
		  echo "<thead>
		        <th style=\"width:5%\">No</th>
		        <th style=\"width:15%\">Tanggal Pengisian</th>
		        <th style=\"width:40%\">Jenis Penilaian</th>
		        <th style=\"width:25%\">Dosen Penguji</th>
		        <th style=\"width:15%\">Status Approval</th>
		        </thead>";
		  $cek_nilai_osce = mysqli_num_rows($nilai_osce);
		  if ($cek_nilai_osce<1)
		  {
		    echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
		  }
		  else
		  {
		    $no=1;
		    $kelas = "ganjil";
		    while ($data_osce=mysqli_fetch_array($nilai_osce))
		    {
		      $tanggal_isi = tanggal_indo($data_osce[tgl_isi]);
		      $tanggal_ujian = tanggal_indo($data_osce[tgl_ujian]);
		      $tanggal_approval = tanggal_indo($data_osce[tgl_approval]);
		      $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_osce[dosen]'"));
		      echo "<tr class=\"$kelas\">";
		      echo "<td align=center>$no</td>";
		      echo "<td>$tanggal_isi</td>";
		      echo "<td>Penilaian Ujian OSCE $data_osce[jenis_ujian]<br><br>";
		      echo "<i>Tanggal Ujian: $tanggal_ujian<br>";
		      echo "Nilai Ujian: $data_osce[nilai]</i>";
		      echo "</td>";
		      echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
		      echo "<td align=center>";
		      if ($data_osce[status_approval]=='0')
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
		      else
		      {
		        echo "<font style=\"color:green\">DISETUJUI</font><br>";
		        echo "per tanggal<br>";
		        echo "$tanggal_approval";
		      }
		      echo "</td>";
		      echo "</tr>";
		      $no++;
		      if ($kelas=="ganjil") $kelas="genap";
		      else $kelas="ganjil";
		    }
		  }
		  echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Psikiatri
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) IKM-KP
		//-------------------------------------
		if ($id_stase=="M095")
		{
			echo "</center><a href=\"#pkbi_M095\"><i>Pengisian Formulir Penilaian Kegiatan di PKBI</i></a><br>";
			echo "<a href=\"#p2ukm__M095\"><i>Pengisian Formulir Penilaian Kegiatan di P2UKM</i></a><br>";
			echo "<a href=\"#test_M095\"><i>Preview Nilai Penugasan dan Test</i></a><br>";
			echo "<a href=\"#komprehensip_M095\"><i>Pengisian Formulir Penilaian Ujian Komprehensip</i></a><br><br>";

			//Pengisian Formulir Penilaian Kegiatan di PKBI
			echo "<a id=\"pkbi_M095\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Kegiatan di PKBI</font></a><br><br>";
			$nilai_pkbi = mysqli_query($con,"SELECT * FROM `ikmkp_nilai_pkbi` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M095\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:40%\">Jenis Penilaian</th>
						<th style=\"width:25%\">Dosen Pembimbing</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_pkbi = mysqli_num_rows($nilai_pkbi);
			if ($cek_nilai_pkbi<1)
			{
				echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_pkbi=mysqli_fetch_array($nilai_pkbi))
				{
					$tanggal_isi = tanggal_indo($data_pkbi[tgl_isi]);
					$tanggal_approval = tanggal_indo($data_pkbi[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_pkbi[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Penilaian Kegiatan di PKBI<br><br>";
					echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>Nilai: $data_pkbi[nilai_total]</i></td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_pkbi[status_approval]=='0') echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Kegiatan di P2UKM
			echo "<a id=\"p2ukm_M095\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Kegiatan di P2UKM</font></a><br><br>";
			$nilai_p2ukm = mysqli_query($con,"SELECT * FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M095\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:40%\">Jenis Penilaian</th>
						<th style=\"width:25%\">Dosen Pembimbing</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_p2ukm = mysqli_num_rows($nilai_p2ukm);
			if ($cek_nilai_p2ukm<1)
			{
				echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_p2ukm=mysqli_fetch_array($nilai_p2ukm))
				{
					$tanggal_isi = tanggal_indo($data_p2ukm[tgl_isi]);
					$tanggal_approval = tanggal_indo($data_p2ukm[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_p2ukm[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Penilaian $data_p2ukm[jenis_penilaian]<br><br>";
					echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>Nilai: $data_p2ukm[nilai_total]</i></td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_p2ukm[status_approval]=='0') echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//Preview Nilai Penugasan dan Test (id test lihat tabel jenis_test)
			echo "<a id=\"test_M095\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai Penugasan dan Test</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `ikmkp_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M095\" style=\"width:100%\">";
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
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) IKM-KP<br><br>";
					echo "Status Ujian/Test: <i>$status_ujian[status_ujian]</i><br>";
					echo "Catatan: <i>$data_test[catatan]</i></td>";
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
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Ujian Komprehensip
			echo "<a id=\"komprehensip_M095\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian Komprehensip</font></a><br><br>";
			$nilai_komprehensip = mysqli_query($con,"SELECT * FROM `ikmkp_nilai_komprehensip` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze4_M095\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:40%\">Jenis Penilaian</th>
						<th style=\"width:25%\">Dosen Pembimbing</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_komprehensip = mysqli_num_rows($nilai_komprehensip);
			if ($cek_nilai_komprehensip<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_komprehensip=mysqli_fetch_array($nilai_komprehensip))
			  {
			    $tanggal_isi = tanggal_indo($data_komprehensip[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_komprehensip[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_komprehensip[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Ujian Komprehensip<br><br>";
					echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>Nilai: $data_komprehensip[nilai_total]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_komprehensip[status_approval]=='0') echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) IKM-KP
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Ilmu Bedah
		//-------------------------------------
		if ($id_stase=="M101")
		{
			echo "</center><br><a href=\"#mentor_M101\"><i>Pengisian Formulir Penilaian Mentoring di RS Jejaring</i></a><br>";
			echo "<a href=\"#test_M101\"><i>Preview Nilai-Nilai Pre-Test, Post-Test, Skill Lab dan OSCE</i></a><br><br>";

			//Pengisian Formulir Penilaian Mentoring di RS Jejaring
			echo "<a id=\"mentor_M101\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Mentoring di RS Jejaring</font></a><br><br>";
			echo "<font style=\"font-size:0.675em\"><i>Catatan:<br>- Pengisian wajib untuk penilaian Mentoring di RS Jejaring adalah 2 (dua) kali (Penilaian Bulan ke-1 dan Penilaian Bulan ke-2).<br>- Nilai rata-rata adalah jumlah total penilaian dibagi 2 (dua).<br>- Untuk cetak, minimal 1 (satu) penilaian telah disetujui Dosen Penilai (Mentor).</i></font><br><br>";
			$nilai_mentor = mysqli_query($con,"SELECT * FROM `bedah_nilai_mentor` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M101\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:40%\">Jenis Penilaian</th>
						<th style=\"width:25%\">Dokter Penilai</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_mentor = mysqli_num_rows($nilai_mentor);
			if ($cek_nilai_mentor<1)
			{
				echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_mentor=mysqli_fetch_array($nilai_mentor))
				{
					$tanggal_isi = tanggal_indo($data_mentor[tgl_isi]);
					$tanggal_awal = tanggal_indo($data_mentor[tgl_awal]);
					$tanggal_akhir = tanggal_indo($data_mentor[tgl_akhir]);
					$tanggal_approval = tanggal_indo($data_mentor[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_mentor[dosen]'"));
					$jam_isi = $data_mentor[jam_isi];
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Penilaian Mentoring di RS Jejaring<br><br>";
					echo "Mentoring Bulan ke-$data_mentor[bulan_ke]<br>
								Periode Tanggal: <i>$tanggal_awal s.d. $tanggal_akhir</i><br>
								RS Jejaring: <i>$data_mentor[rs_jejaring]</i><br>
								Nilai: <i>$data_mentor[nilai_rata]</i>
								</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_mentor[status_approval]=='0')
					{
						echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
					}
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table>";
			echo "</center><br><br>";


			//Preview Nilai-Nilai Pre-Test, Post-Test, Skill Lab dan OSCE
			echo "<a id=\"test_M101\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai Pre-Test, Post-Test, Skill Lab dan OSCE</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `bedah_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M101\" style=\"width:100%\">";
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
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) Ilmu Bedah<br><br>";
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
			echo "</center><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Ilmu Bedah
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Anestesi
		//-------------------------------------
		if ($id_stase=="M102")
		{
			echo "</center><br><a href=\"#dops_M102\"><i>Pengisian Formulir Penilaian DOPS</i></a><br>";
			echo "<a href=\"#osce_M102\"><i>Pengisian Formulir Penilaian Ujian OSCE</i></a><br>";
			echo "<a href=\"#test_M102\"><i>Preview Nilai-Nilai Test dan Perilaku</i></a><br><br>";

			//Pengisian Formulir Penilaian DOPS
			echo "<a id=\"dops_M102\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian DOPS</font></a><br><br>";
			$nilai_dops = mysqli_query($con,"SELECT * FROM `anestesi_nilai_dops` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M102\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_dops = mysqli_num_rows($nilai_dops);
			if ($cek_nilai_dops<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_dops=mysqli_fetch_array($nilai_dops))
			  {
			    $tanggal_isi = tanggal_indo($data_dops[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_dops[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian DOPS<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Nilai: $data_dops[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_dops[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Ujian OSCE
			echo "<a id=\"osce_M102\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian OSCE</font></a><br><br>";
			$nilai_osce = mysqli_query($con,"SELECT * FROM `anestesi_nilai_osce` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M102\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penguji (Tutor)</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_osce = mysqli_num_rows($nilai_osce);
			if ($cek_nilai_osce<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_osce=mysqli_fetch_array($nilai_osce))
			  {
			    $tanggal_isi = tanggal_indo($data_osce[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_osce[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_osce[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Ujian OSCE<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Nilai: $data_osce[nilai_total]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_osce[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Preview Nilai-Nilai Test dan Perilaku
			echo "<a id=\"test_M102\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai Test dan Perilaku</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M102\" style=\"width:100%\">";
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
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) Anestesi dan Intensive Care<br><br>";
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
			echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Anestesi
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Radiologi
		//-------------------------------------
		if ($id_stase=="M103")
		{
			echo "</center><br><a href=\"#cbd1_M103\"><i>Pengisian Formulir Penilaian Kasus CBD - Radiodiagnostik</i></a><br>";
			echo "<a href=\"#cbd2_M103\"><i>Pengisian Formulir Penilaian Kasus CBD - Radioterapi</i></a><br>";
			echo "<a href=\"#jurnal_M103\"><i>Pengisian Formulir Penilaian Journal Reading</i></a><br>";
			echo "<a href=\"#test_M103\"><i>Preview Nilai Test dan Sikap/Perilaku</i></a><br><br>";

			//Pengisian Formulir Penilaian Kasus CBD - Radiodiagnostik
			echo "<a id=\"cbd1_M103\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Kasus CBD - Radiodiagnostik</font></a><br><br>";
			$kasus = "Radiodiagnostik";
			$nilai_cbd = mysqli_query($con,"SELECT * FROM `radiologi_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='$kasus'");
			echo "<table id=\"freeze1_M103\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Pembimbing</th>
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
			    $tanggal_approval = tanggal_indo($data_cbd[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Kasus CBD<br>Jenis Kasus: $data_cbd[kasus]<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>Nilai: $data_cbd[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_cbd[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Kasus CBD - Radioterapi
			echo "<a id=\"cbd2_M103\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Kasus CBD - Radioterapi</font></a><br><br>";
			$kasus = "Radioterapi";
			$nilai_cbd = mysqli_query($con,"SELECT * FROM `radiologi_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='$kasus'");
			echo "<table id=\"freeze2_M103\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Pembimbing</th>
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
			    $tanggal_approval = tanggal_indo($data_cbd[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Kasus CBD<br>Jenis Kasus: $data_cbd[kasus]<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>Nilai: $data_cbd[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_cbd[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Journal Reading
			echo "<a id=\"jurnal_M103\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Journal Reading</font></a><br><br>";
			$nilai_jurnal = mysqli_query($con,"SELECT * FROM `radiologi_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M103\" style=\"width:100%\">";
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
			    echo "Judul Artikel: $data_jurnal[judul_paper]<br>Nilai: $data_jurnal[nilai_rata]</i><br></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_jurnal[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Preview Nilai Test dan Sikap/Perilaku
			echo "<a id=\"test_M103\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai Test dan Sikap/Perilaku</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `radiologi_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze4_M103\" style=\"width:100%\">";
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
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan Radiologi<br><br>";
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
			echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Radiologi
		//-------------------------------------


		//-------------------------------------
		//Kepaniteraan (Stase) Ilmu Kesehatan Mata
		//-------------------------------------
		if ($id_stase=="M104")
		{
			echo "</center><br><a href=\"#presentasi_M104\"><i>Pengisian Formulir Penilaian Presentasi Kasus Besar</i></a><br>";
			echo "<a href=\"#jurnal_M104\"><i>Pengisian Formulir Penilaian Presentasi Journal Reading</i></a><br>";
			echo "<a href=\"#penyanggah_M104\"><i>Pengisian Formulir Penilaian Penyanggah Presentasi</i></a><br>";
			echo "<a href=\"#minicex_M104\"><i>Pengisian Formulir Penilaian Ujian Mini-Cex</i></a><br>";
			echo "<a href=\"#test_M104\"><i>Preview Nilai-Nilai Test</i></a><br><br>";

			//Pengisian Formulir Penilaian Presentasi Kasus Besar
			echo "<a id=\"presentasi_M104\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Presentasi Kasus Besar</font></a><br><br>";
			$nilai_presentasi = mysqli_query($con,"SELECT * FROM `mata_nilai_presentasi` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M104\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_presentasi = mysqli_num_rows($nilai_presentasi);
			if ($cek_nilai_presentasi<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_presentasi=mysqli_fetch_array($nilai_presentasi))
			  {
			    $tanggal_isi = tanggal_indo($data_presentasi[tgl_isi]);
			    $tanggal_approval = tanggal_indo($data_presentasi[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_presentasi[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Presentasi Kasus Besar<br><br>";
			    echo "<i>Judul Kasus: $data_presentasi[judul_presentasi]<br>Nilai: $data_presentasi[nilai_total]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_presentasi[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Presentasi Journal Reading
			echo "<a id=\"jurnal_M104\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Presentasi Journal Reading</font></a><br><br>";
			$nilai_jurnal = mysqli_query($con,"SELECT * FROM `mata_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M104\" style=\"width:100%\">";
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
			    echo "<td>Penilaian Presentasi Journal Reading<br><br>";
			    echo "<i>Judul Artikel: $data_jurnal[judul_presentasi]<br>Nilai: $data_jurnal[nilai_total]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_jurnal[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Preview Nilai Penyanggah Presentasi
			echo "<a id=\"penyanggah_M104\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai Penyanggah Presentasi</font></a><br><br>";
			$nilai_penyanggah = mysqli_query($con,"SELECT * FROM `mata_nilai_penyanggah` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M104\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Presentasi</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
			      <th style=\"width:15%\">Nilai</th>
			      </thead>";
			$cek_nilai_penyanggah = mysqli_num_rows($nilai_penyanggah);
			if ($cek_nilai_penyanggah<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_penyanggah=mysqli_fetch_array($nilai_penyanggah))
			  {
			    $tanggal_presentasi = tanggal_indo($data_penyanggah[tgl_presentasi]);
			    $tanggal_approval = tanggal_indo($data_penyanggah[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_penyanggah[dosen]'"));
					$mhsw_presenter = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_penyanggah[presenter]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_presentasi</td>";
			    echo "<td>Nilai Penyanggah Presentasi $data_penyanggah[jenis_presentasi] dari mahasiswa:<br><br>";
			    echo "<i>Nama Mahasiswa: $mhsw_presenter[nama]<br>NIM: $mhsw_presenter[nim]<br>";
					echo "Judul: $data_penyanggah[judul_presentasi]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
					echo "<i>Tanggal Penilaian: $tanggal_approval</i>";
					echo "</td>";
			    echo "<td align=center>$data_penyanggah[nilai]</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Ujian Mini-Cex
			echo "<a id=\"minicex\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian Mini-Cex</font></a><br><br>";
			$nilai_minicex = mysqli_query($con,"SELECT * FROM `mata_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' ORDER BY `tgl_isi`,`tgl_ujian`");
			echo "<table id=\"freeze4_M104\" style=\"width:100%\">";
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
			    echo "<td>Penilaian Ujian Mini-Cex<br>";
					echo "Tanggal Ujian: $tanggal_ujian<br><br>";
					echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>Nilai: $data_minicex[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_minicex[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Preview Nilai-Nilai Test (id Test lihat tabel jenis_test)
			echo "<a id=\"test\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai Test</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `mata_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze5_M104\" style=\"width:100%\">";
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
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) Ilmu Kesehatan Mata<br><br>";
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
			echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Ilmu Kesehatan Mata
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) IKFR
		//-------------------------------------
		if ($id_stase=="M094")
		{
		  echo "</center><br><a href=\"#kasus_M094\"><i>Pengisian Formulir Penilaian Diskusi Kasus</i></a><br>";
		  echo "<a href=\"#minicex_M094\"><i>Pengisian Formulir Penilaian Ujian MINI-CEX</i></a><br>";
		  echo "<a href=\"#test_M094\"><i>Preview Nilai-Nilai Test dan Perilaku</i></a><br><br>";

		  //Pengisian Formulir Penilaian Diskusi Kasus
		  echo "<a id=\"kasus_M094\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Diskusi Kasus</font></a><br><br>";
		  $nilai_kasus = mysqli_query($con,"SELECT * FROM `ikfr_nilai_kasus` WHERE `nim`='$data_mhsw[nim]'");
		  echo "<table id=\"freeze1_M094\" style=\"width:100%\">";
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
		      echo "<td>Penilaian Kegiatan Diskusi Kasus<br><br>";
		      echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
		      echo "Kasus: $data_kasus[kasus]<br>";
		      echo "Nilai: $data_kasus[nilai_rata]</i></td>";
		      echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
		      echo "<td align=center>";
		      if ($data_kasus[status_approval]=='0')
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
		      else
		      {
		        echo "<font style=\"color:green\">DISETUJUI</font><br>";
		        echo "per tanggal<br>";
		        echo "$tanggal_approval";
		       }
		      echo "</td>";
		      echo "</tr>";
		      $no++;
		      if ($kelas=="ganjil") $kelas="genap";
		      else $kelas="ganjil";
		    }
		  }
		  echo "</table><br><br>";

		  //Pengisian Formulir Penilaian Ujian MINI-CEX
		  echo "<a id=\"minicex_M094\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian MINI-CEX</font></a><br><br>";
		  $nilai_minicex = mysqli_query($con,"SELECT * FROM `ikfr_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
		  echo "<table id=\"freeze2_M094\" style=\"width:100%\">";
		  echo "<thead>
		        <th style=\"width:5%\">No</th>
		        <th style=\"width:15%\">Tanggal Pengisian</th>
		        <th style=\"width:40%\">Jenis Penilaian</th>
		        <th style=\"width:25%\">Dosen Penilai</th>
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
		      $tanggal_approval = tanggal_indo($data_minicex[tgl_approval]);
		      $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
		      echo "<tr class=\"$kelas\">";
		      echo "<td align=center>$no</td>";
		      echo "<td>$tanggal_isi</td>";
		      echo "<td>Penilaian Ujian MINI-CEX<br><br>";
		      echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>Nilai: $data_minicex[nilai_rata]</i></td>";
		      echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
		      echo "<td align=center>";
		      if ($data_minicex[status_approval]=='0')
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
		      else
		      {
		        echo "<font style=\"color:green\">DISETUJUI</font><br>";
		        echo "per tanggal<br>";
		        echo "$tanggal_approval";
		      }
		      echo "</td>";
		      echo "</tr>";
		      $no++;
		      if ($kelas=="ganjil") $kelas="genap";
		      else $kelas="ganjil";
		    }
		  }
		  echo "</table><br><br>";

			//Preview Nilai-Nilai Test dan Perilaku
		  echo "<a id=\"test_M094\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai Test dan Perilaku</font></a><br><br>";
		  $nilai_test = mysqli_query($con,"SELECT * FROM `ikfr_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
		  echo "<table id=\"freeze3_M094\" style=\"width:100%\">";
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
		      echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) IKFR<br><br>";
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
		  echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) IKFR
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) IKGM
		//-------------------------------------
		if ($id_stase=="M106")
		{
			echo "</center><br><a href=\"#kasus_M106\"><i>Pengisian Formulir Penilaian Laporan Kasus</i></a><br>";
			echo "<a href=\"#jurnal_M106\"><i>Pengisian Formulir Penilaian Journal Reading</i></a><br>";
			echo "<a href=\"#responsi_M106\"><i>Pengisian Formulir Penilaian Responsi Kasus Kecil</i></a><br>";
			echo "<a href=\"#test_M106\"><i>Preview Nilai-Nilai Test dan Perilaku</i></a><br><br>";

			//Pengisian Formulir Penilaian Laporan Kasus
			echo "<a id=\"kasus_M106\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Laporan Kasus</font></a><br><br>";
			$nilai_kasus = mysqli_query($con,"SELECT * FROM `ikgm_nilai_kasus` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M106\" style=\"width:100%\">";
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
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_kasus[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Journal Reading
			echo "<a id=\"jurnal_M106\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Journal Reading</font></a><br><br>";
			$nilai_jurnal = mysqli_query($con,"SELECT * FROM `ikgm_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M106\" style=\"width:100%\">";
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
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_jurnal[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Responsi Kasus Kecil
			echo "<a id=\"responsi_M106\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Responsi Kasus Kecil</font></a><br><br>";
			$nilai_responsi = mysqli_query($con,"SELECT * FROM `ikgm_nilai_responsi` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M106\" style=\"width:100%\">";
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
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_responsi[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Preview Nilai-Nilai Test dan Perilaku
			echo "<a id=\"test_M106\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai Test dan Perilaku</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `ikgm_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze4_M106\" style=\"width:100%\">";
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
			echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) IKGM
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan
		//-------------------------------------
		if ($id_stase=="M111")
		{
			echo "</center><br><a href=\"#minicex_M111\"><i>Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)</i></a><br>";
			echo "<a href=\"#cbd_M111\"><i>Pengisian Formulir Penilaian Case-Based Discussion (CBD)</i></a><br>";
			echo "<a href=\"#jurnal_M111\"><i>Pengisian Formulir Penilaian Journal Reading</i></a><br>";
			echo "<a href=\"#test_M111\"><i>Preview Nilai Penugasan dan Test (DOPS/OSCE, MCQ, dan MINI-PAT)</i></a><br><br>";

			//Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)
			echo "<a id=\"minicex_M111\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)</font></a><br><br>";
			$nilai_minicex = mysqli_query($con,"SELECT * FROM `obsgyn_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M111\" style=\"width:100%\">";
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
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
		      echo "<td align=center>";
		      if ($data_minicex[status_approval]=='0')
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
		      else
		      {
		        echo "<font style=\"color:green\">DISETUJUI</font><br>";
		        echo "per tanggal<br>";
		        echo "$tanggal_approval";
		      }
		      echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian CBD
			echo "<a id=\"cbd_M111\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian CBD</font></a><br><br>";
			$nilai_cbd = mysqli_query($con,"SELECT * FROM `obsgyn_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
			echo "<font style=\"font-size:0.675em\"><i>Catatan:<br>- Pengisian wajib untuk penilaian CBD adalah 4 (empat) kali.<br>- Nilai rata-rata adalah jumlah total nilai CBD dibagi 4 (empat).<br>- Untuk cetak, minimal 1 (satu) penilaian CBD telah disetujui Dosen Penilai.</i></font><br><br>";
			echo "<table id=\"freeze2_M111\" style=\"width:100%\">";
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
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
		      if ($data_cbd[status_approval]=='0')
		      echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
		      else
		      {
		        echo "<font style=\"color:green\">DISETUJUI</font><br>";
		        echo "per tanggal<br>";
		        echo "$tanggal_approval";
		      }
		      echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian Journal Reading
			echo "<a id=\"jurnal_M111\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Journal Reading</font></a><br><br>";
			$nilai_jurnal = mysqli_query($con,"SELECT * FROM `obsgyn_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M111\" style=\"width:100%\">";
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
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_jurnal[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table>";
			echo "</center><br><br>";

			//Preview Nilai Penugasan dan Test (DOPS/OSCE, MCQ, dan MINI-PAT)
			echo "<a id=\"test_M111\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai Penugasan dan Test (DOPS/OSCE, MCQ, dan MINI-PAT)</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `obsgyn_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze4_M111\" style=\"width:100%\">";
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
			echo "</center><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Forensik
		//-------------------------------------
		if ($id_stase=="M112")
		{
			echo "</center><br><a href=\"#visum_M112\"><i>Pengisian Formulir Penilaian Visum Bayangan</i></a><br>";
			echo "<a href=\"#jaga_M112\"><i>Pengisian Formulir Penilaian Kegiatan Jaga</i></a><br>";
			echo "<a href=\"#substase_M112\"><i>Pengisian Formulir Penilaian Substase</i></a><br>";
			echo "<a href=\"#referat_M112\"><i>Pengisian Formulir Penilaian Referat</i></a><br>";
			echo "<a href=\"#test_M112\"><i>Preview Nilai-Nilai Test dan Perilaku</i></a><br><br>";

			//Pengisian Formulir Penilaian Visum Bayangan
			echo "<a id=\"visum_M112\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Visum Bayangan</font></a><br><br>";
			$nilai_visum = mysqli_query($con,"SELECT * FROM `forensik_nilai_visum` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M112\" style=\"width:100%\">";
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
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_visum[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Kegiatan Jaga
			echo "<a id=\"jaga_M112\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Kegiatan Jaga</font></a><br><br>";
			$nilai_jaga = mysqli_query($con,"SELECT * FROM `forensik_nilai_jaga` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M112\" style=\"width:100%\">";
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
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_jaga[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Substase
			echo "<a id=\"substase_M112\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Substase</font></a><br><br>";
			$nilai_substase = mysqli_query($con,"SELECT * FROM `forensik_nilai_substase` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M112\" style=\"width:100%\">";
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
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_substase[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Referat
			echo "<a id=\"referat_M112\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Referat</font></a><br><br>";
			$nilai_referat = mysqli_query($con,"SELECT * FROM `forensik_nilai_referat` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze4_M112\" style=\"width:100%\">";
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
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_referat[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Preview Nilai-Nilai Test dan Perilaku
			echo "<a id=\"test_M112\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai Test dan Perilaku</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze5_M112\" style=\"width:100%\">";
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
			echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Forensik
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Ilmu Kesehatan Anak
		//-------------------------------------
		if ($id_stase=="M113")
		{
			echo "</center><br><a href=\"#minicex_M113\"><i>Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)</i></a><br>";
			echo "<a href=\"#dops_M113\"><i>Pengisian Formulir Penilaian Direct Observation of Procedural Skill (DOPS)</i></a><br>";
			echo "<a href=\"#cbd_M113\"><i>Pengisian Formulir Penilaian Case-Based Discussion (CBD) - Kasus Poliklinik</i></a><br>";
			echo "<a href=\"#kasus_M113\"><i>Pengisian Formulir Penilaian Penyajian Kasus Besar</i></a><br>";
			echo "<a href=\"#jurnal_M113\"><i>Pengisian Formulir Penilaian Penyajian Journal Reading</i></a><br>";
			echo "<a href=\"#minipat_M113\"><i>Pengisian Formulir Penilaian Mini Peer Assesment Tool (Mini-PAT)</i></a><br>";
			echo "<a href=\"#ujian_M113\"><i>Pengisian Formulir Penilaian Ujian Akhir Kepaniteraan</i></a><br>";
			echo "<a href=\"#test_M113\"><i>Preview Nilai Ujian/Test (Pre-Test, Post-Test, dan Ujian OSCE)</i></a><br><br>";

			//Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)
			echo "<a id=\"minicex_M113\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian MINI-CEX (Mini Clinical Examination)</font></a><br><br>";
			echo "<font style=\"font-size:0.675em\"><i>Catatan:<br>- Pengisian wajib untuk penilaian Mini-Cex adalah 14 (empat belas) kali.<br>- Nilai total Ujian Mini-Cex dihitung berdasarkan rasio penilaian jenis evaluasi masing-masing Mini-Cex (Infeksi: #1-#4, Non Infeksi: #5-#11, ERIA: #12, Perinatologi: #13, dan Kasus RS Jejaring: #14).<br>- Untuk cetak, minimal 1 (satu) penilaian Mini-Cex telah disetujui Dosen Penilai / DPJP.</i></font><br><br>";
			$nilai_minicex = mysqli_query($con,"SELECT * FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M113\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai / DPJP</th>
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
			    echo "<td>Penilaian Ujian MINI-CEX (Mini Clinical Examination)<br>";
					$evaluasi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ika_evaluasi_ref` WHERE `id`='$data_minicex[evaluasi]'"));
			    echo "Jenis Evaluasi: $evaluasi[evaluasi] (#$evaluasi[id])<br>";
					echo "Tanggal Ujian: $tanggal_ujian<br><br>";
					echo "Nilai Ujian: $data_minicex[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_minicex[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Direct Observation of Procedural Skill (DOPS)
			echo "<a id=\"dops_M113\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Direct Observation of Procedural Skill (DOPS)</font></a><br><br>";
			$nilai_dops = mysqli_query($con,"SELECT * FROM `ika_nilai_dops` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M113\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_dops = mysqli_num_rows($nilai_dops);
			if ($cek_nilai_dops<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_dops=mysqli_fetch_array($nilai_dops))
			  {
			    $tanggal_isi = tanggal_indo($data_dops[tgl_isi]);
					$tanggal_ujian = tanggal_indo($data_dops[tgl_ujian]);
			    $tanggal_approval = tanggal_indo($data_dops[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Direct Observation of Procedural Skill (DOPS)<br>(Penilaian Ketrampilan Klinis)<br><br>";
					echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
					echo "Tanggal Ujian: $tanggal_ujian<br>";
			    echo "Nilai: $data_dops[nilai_rata]</i></td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_dops[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian CBD
			echo "<a id=\"cbd_M113\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian CBD  - Kasus Poliklinik</font></a><br><br>";
			$nilai_cbd = mysqli_query($con,"SELECT * FROM `ika_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_M113\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai / Mentor</th>
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
			    echo "<td>Penilaian Case-Based Discussion (CBD)<br>(Kasus Poliklinik)<br><br>";
					echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
					echo "Nama Poliklinik: $data_cbd[poliklinik]<br>";
					echo "Tanggal Ujian: $tanggal_ujian<br>";
					echo "Nilai: $data_cbd[nilai_rata]</i>";
					echo "</td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_cbd[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Penyajian Kasus Besar
			echo "<a id=\"kasus_M113\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Penyajian Kasus Besar</font></a><br><br>";
			$nilai_kasus = mysqli_query($con,"SELECT * FROM `ika_nilai_kasus` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze4_M113\" style=\"width:100%\">";
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
			    $tanggal_penyajian = tanggal_indo($data_kasus[tgl_penyajian]);
			    $tanggal_approval = tanggal_indo($data_kasus[tgl_approval]);
			    $awal_periode = tanggal_indo($data_kasus[tgl_awal]);
			    $akhir_periode = tanggal_indo($data_kasus[tgl_akhir]);

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Penyajian Kasus Besar<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Judul Kasus: $data_kasus[kasus]<br>";
			    echo "Tanggal Penyajian: $tanggal_penyajian<br>";
			    echo "Nilai: $data_kasus[nilai_rata]</i>";
			    echo "</td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_kasus[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Penyajian Journal Reading
			echo "<a id=\"jurnal_M113\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Penyajian Journal Reading</font></a><br><br>";
			$nilai_jurnal = mysqli_query($con,"SELECT * FROM `ika_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze5_M113\" style=\"width:100%\">";
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
			    $tanggal_penyajian = tanggal_indo($data_jurnal[tgl_penyajian]);
			    $tanggal_approval = tanggal_indo($data_jurnal[tgl_approval]);
			    $awal_periode = tanggal_indo($data_jurnal[tgl_awal]);
			    $akhir_periode = tanggal_indo($data_jurnal[tgl_akhir]);

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Penyajian Journal Reading<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Judul Paper/Makalah: $data_jurnal[jurnal]<br>";
			    echo "Tanggal Penyajian: $tanggal_penyajian<br>";
			    echo "Nilai: $data_jurnal[nilai_rata]</i>";
			    echo "</td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_jurnal[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Mini Peer Assesment Tool (Mini-PAT)
			echo "<a id=\"minipat_M113\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Mini Peer Assesment Tool (Mini-PAT)</font></a><br><br>";
			$nilai_minipat = mysqli_query($con,"SELECT * FROM `ika_nilai_minipat` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze6_M113\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penilai</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_minipat = mysqli_num_rows($nilai_minipat);
			if ($cek_nilai_minipat<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_minipat=mysqli_fetch_array($nilai_minipat))
			  {
			    $tanggal_isi = tanggal_indo($data_minipat[tgl_isi]);
			    $tanggal_penilaian = tanggal_indo($data_minipat[tgl_penilaian]);
			    $tanggal_approval = tanggal_indo($data_minipat[tgl_approval]);
			    $awal_periode = tanggal_indo($data_minipat[tgl_awal]);
			    $akhir_periode = tanggal_indo($data_minipat[tgl_akhir]);

			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_minipat[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Mini Peer Assesment Tool (Mini-PAT)<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Tanggal Penilaian: $tanggal_penilaian<br>";
			    echo "Nilai: $data_minipat[nilai_rata]</i>";
			    echo "</td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_minipat[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Ujian Akhir Kepaniteraan
			echo "<a id=\"ujian_M113\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian Akhir Kepaniteraan</font></a><br><br>";
			echo "<font style=\"font-size:0.675em\"><i>Catatan:<br>- Ujian Akhir maksimal dilakukan 3 (tiga) kali (perbaikan).<br>- Nilai Akhir diambil yang terbaik dari semua ujian yang diikuti.</i></font><br><br>";
			$nilai_ujian = mysqli_query($con,"SELECT * FROM `ika_nilai_ujian` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze7_M113\" style=\"width:100%\">";
			echo "<thead>
			      <th style=\"width:5%\">No</th>
			      <th style=\"width:15%\">Tanggal Pengisian</th>
			      <th style=\"width:40%\">Jenis Penilaian</th>
			      <th style=\"width:25%\">Dosen Penguji</th>
			      <th style=\"width:15%\">Status Approval</th>
			      </thead>";
			$cek_nilai_ujian = mysqli_num_rows($nilai_ujian);
			if ($cek_nilai_ujian<1)
			{
			  echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
			  $no=1;
			  $kelas = "ganjil";
			  while ($data_ujian=mysqli_fetch_array($nilai_ujian))
			  {
			    $tanggal_isi = tanggal_indo($data_ujian[tgl_isi]);
			    $tanggal_ujian = tanggal_indo($data_ujian[tgl_ujian]);
			    $tanggal_approval = tanggal_indo($data_ujian[tgl_approval]);
			    $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_ujian[dosen]'"));
			    echo "<tr class=\"$kelas\">";
			    echo "<td align=center>$no</td>";
			    echo "<td>$tanggal_isi</td>";
			    echo "<td>Penilaian Ujian Akhir Kepaniteraan (Stase) Ilmu Kesehatan Anak<br><br>";
			    echo "<i>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]<br>";
			    echo "Tanggal Ujian: $tanggal_ujian<br>";
			    echo "Nilai: $data_ujian[nilai_rata]</i>";
			    echo "</td>";
			    echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
			    echo "<td align=center>";
			    if ($data_ujian[status_approval]=='0')
			    echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
			    else
			    {
			      echo "<font style=\"color:green\">DISETUJUI</font><br>";
			      echo "per tanggal<br>";
			      echo "$tanggal_approval";
			    }
			    echo "</td>";
			    echo "</tr>";
			    $no++;
			    if ($kelas=="ganjil") $kelas="genap";
			    else $kelas="ganjil";
			  }
			}
			echo "</table><br><br>";

			//Preview Nilai Ujian/Test (Pre-Test, Post-Test, dan Ujian OSCE)
			echo "<a id=\"test_M113\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai Ujian/Test (Pre-Test, Post-Test, dan Ujian OSCE)</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `ika_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze8_M113\" style=\"width:100%\">";
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
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) Ilmu Kesehatan Anak<br><br>";
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
			echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Ilmu Kesehatan Anak
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin
		//-------------------------------------
		if ($id_stase=="M114")
		{
			echo "</center><br><a href=\"#cbd_M114\"><i>Pengisian Formulir Penilaian Ujian Kasus</i></a><br>";
			echo "<a href=\"#test_M114\"><i>Preview Nilai-Nilai OSCE, Ujian Teori dan Perilaku</i></a><br><br>";

			//Pengisian Formulir Penilaian CBD
			echo "<a id=\"cbd_M114\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Ujian Kasus</font></a><br><br>";
			$nilai_cbd = mysqli_query($con,"SELECT * FROM `kulit_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_M114\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal/Jam Pengisian</th>
						<th style=\"width:40%\">Judul Kasus</th>
						<th style=\"width:25%\">Dokter Pembimbing Lapangan</th>
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
					$tanggal_approval = tanggal_indo($data_cbd[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
					$jam_isi = $data_cbd[jam_isi];
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi<br>Pukul $jam_isi</td>";
					echo "<td>Judul: <i>$data_cbd[kasus]</i><br><br>";
					echo "<i>Nilai: $data_cbd[nilai_rata]</i></td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_cbd[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//Preview Nilai-Nilai Test dan Perilaku
			echo "<a id=\"test_M114\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Preview Nilai-Nilai OSCE, Ujian Teori dan Perilaku</font></a><br><br>";
			$nilai_test = mysqli_query($con,"SELECT * FROM `kulit_nilai_test` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_M114\" style=\"width:100%\">";
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
			    echo "<td>Penilaian $jenis_test[jenis_test] Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin<br><br>";
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
			echo "</table><br><br>";
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin
		//-------------------------------------

		//-------------------------------------
		//Kepaniteraan (Stase) Komprehensip dan Kedokteran Keluarga
		//-------------------------------------
		if ($id_stase=="M121")
		{
			//Komprehensip
			//------------------------------------
			echo "<h4>Kepaniteraan Komprehensip</h4>";
			echo "</center><br><a href=\"#laporan_kompre\"><i>Pengisian Formulir Penilaian Laporan</i></a><br>";
			echo "<a href=\"#sikap_kompre\"><i>Pengisian Formulir Penilaian Sikap/Perilaku</i></a><br>";
			echo "<a href=\"#cbd_kompre\"><i>Pengisian Formulir Penilaian Case Based Discussion (CBD)</i></a><br>";
			echo "<a href=\"#presensi_kompre\"><i>Pengisian Formulir Penilaian Presensi / Kehadiran</i></a><br><br>";

			//Pengisian Formulir Penilaian Laporan
			echo "<a id=\"laporan_kompre\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Laporan</font></a><br><br>";
			$nilai_laporan = mysqli_query($con,"SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_kompre\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Instansi / Lokasi</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dosen Pembimbing Lapangan</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_laporan = mysqli_num_rows($nilai_laporan);
			if ($cek_nilai_laporan<1)
			{
				echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_laporan=mysqli_fetch_array($nilai_laporan))
				{
					$tanggal_isi = tanggal_indo($data_laporan[tgl_isi]);
					$tanggal_mulai = tanggal_indo($data_laporan[tgl_mulai]);
					$tanggal_selesai = tanggal_indo($data_laporan[tgl_selesai]);
					$tanggal_approval = tanggal_indo($data_laporan[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_laporan[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_laporan[instansi]<br><i>Lokasi: $data_laporan[lokasi]<br><br>";
					echo "Nilai Individu: $data_laporan[nilai_rata_ind]<br>";
					echo "Nilai Kelompok: $data_laporan[nilai_rata_kelp]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_laporan[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Sikap/Perilaku
			echo "<a id=\"sikap_kompre\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Sikap/Perilaku</font></a><br><br>";
			$nilai_sikap = mysqli_query($con,"SELECT * FROM `kompre_nilai_sikap` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_kompre\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Instansi / Lokasi</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dokter Pembimbing</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_sikap = mysqli_num_rows($nilai_sikap);
			if ($cek_nilai_sikap<1)
			{
				echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_sikap=mysqli_fetch_array($nilai_sikap))
				{
					$tanggal_isi = tanggal_indo($data_sikap[tgl_isi]);
					$tanggal_mulai = tanggal_indo($data_sikap[tgl_mulai]);
					$tanggal_selesai = tanggal_indo($data_sikap[tgl_selesai]);
					$tanggal_approval = tanggal_indo($data_sikap[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_sikap[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_sikap[instansi]<br><i>Lokasi: $data_sikap[lokasi]<br><br>";
					echo "Nilai: $data_sikap[nilai_rata]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_sikap[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian CBD
			echo "<a id=\"cbd_kompre\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Case Based Discussion (CBD)</font></a><br><br>";
			$nilai_cbd = mysqli_query($con,"SELECT * FROM `kompre_nilai_cbd` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_kompre\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal/Jam Pengisian</th>
						<th style=\"width:40%\">Judul Kasus</th>
						<th style=\"width:25%\">Dokter Pembimbing Lapangan</th>
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
					$tanggal_approval = tanggal_indo($data_cbd[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
					$jam_isi = $data_cbd[jam_isi];
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi<br>Pukul $jam_isi</td>";
					echo "<td>Judul: <i>$data_cbd[kasus]</i><br><br>";
					echo "<i>Nilai: $data_cbd[nilai_rata]</i></td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_cbd[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Presensi / Kehadiran
			echo "<a id=\"presensi_kompre\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Presensi / Kehadiran</font></a><br><br>";
			$nilai_presensi = mysqli_query($con,"SELECT * FROM `kompre_nilai_presensi` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze4_kompre\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Instansi / Lokasi</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dokter Pembimbing</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_presensi = mysqli_num_rows($nilai_presensi);
			if ($cek_nilai_presensi<1)
			{
				echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_presensi=mysqli_fetch_array($nilai_presensi))
				{
					$tanggal_isi = tanggal_indo($data_presensi[tgl_isi]);
					$tanggal_mulai = tanggal_indo($data_presensi[tgl_mulai]);
					$tanggal_selesai = tanggal_indo($data_presensi[tgl_selesai]);
					$tanggal_approval = tanggal_indo($data_presensi[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_presensi[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_presensi[instansi]<br><i>Lokasi: $data_presensi[lokasi]</i><br><br><i>Nilai: $data_presensi[nilai_total]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_presensi[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";
			//------------------------------------

			//Kedokteran Keluarga
			//------------------------------------
			echo "<h4>Kepaniteraan Kedokteran Keluarga</h4>";
			echo "</center><br><a href=\"#lap_kasus_kdk\"><i>Pengisian Formulir Penilaian Portofolio Laporan Kasus</i></a><br>";
			echo "<a href=\"#sikap_kdk\"><i>Pengisian Formulir Penilaian Sikap/Perilaku</i></a><br>";
			echo "<a href=\"#dops_kdk\"><i>Pengisian Formulir DOPS</i></a><br>";
			echo "<a href=\"#minicex\"><i>Pengisian Formulir Penilaian MINI-CEX</i></a><br>";
			echo "<a href=\"#presensi_kdk\"><i>Pengisian Formulir Penilaian Presensi / Kehadiran</i></a><br><br>";

			//Pengisian Formulir Penilaian Portofolio Laporan Kasus
			echo "<a id=\"lap_kasus_kdk\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Portofolio Laporan Kasus</font></a><br><br>";
			$nilai_kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze1_kdk\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Lokasi / Kasus</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dosen Pembimbing Lapangan</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_kasus = mysqli_num_rows($nilai_kasus);
			if ($cek_nilai_kasus<1)
			{
				echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_kasus=mysqli_fetch_array($nilai_kasus))
				{
					$tanggal_isi = tanggal_indo($data_kasus[tgl_isi]);
					$tanggal_mulai = tanggal_indo($data_kasus[tgl_mulai]);
					$tanggal_selesai = tanggal_indo($data_kasus[tgl_selesai]);
					$tanggal_approval = tanggal_indo($data_kasus[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>$data_kasus[lokasi]<br><i>Kasus: $data_kasus[kasus]</i><br><br><i>Nilai: $data_kasus[nilai_rata]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_kasus[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Sikap/Perilaku
			echo "<a id=\"sikap_kdk\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Sikap/Perilaku</font></a><br><br>";
			$nilai_sikap = mysqli_query($con,"SELECT * FROM `kdk_nilai_sikap` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze2_kdk\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Instansi / Lokasi</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dokter Pembimbing</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_sikap = mysqli_num_rows($nilai_sikap);
			if ($cek_nilai_sikap<1)
			{
				echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_sikap=mysqli_fetch_array($nilai_sikap))
				{
					$tanggal_isi = tanggal_indo($data_sikap[tgl_isi]);
					$tanggal_mulai = tanggal_indo($data_sikap[tgl_mulai]);
					$tanggal_selesai = tanggal_indo($data_sikap[tgl_selesai]);
					$tanggal_approval = tanggal_indo($data_sikap[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_sikap[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_sikap[instansi]<br><i>Lokasi: $data_sikap[lokasi]</i><br><br><i>Nilai: $data_sikap[nilai_rata]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_sikap[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//Pengisian Formulir DOPS
			echo "<a id=\"dops_kdk\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir DOPS</font></a><br><br>";
			$nilai_dops = mysqli_query($con,"SELECT * FROM `kdk_nilai_dops` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze3_kdk\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Instansi / Lokasi</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dokter Pembimbing</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_dops = mysqli_num_rows($nilai_dops);
			if ($cek_nilai_dops<1)
			{
				echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_dops=mysqli_fetch_array($nilai_dops))
				{
					$tanggal_isi = tanggal_indo($data_dops[tgl_isi]);
					$tanggal_mulai = tanggal_indo($data_dops[tgl_mulai]);
					$tanggal_selesai = tanggal_indo($data_dops[tgl_selesai]);
					$tanggal_approval = tanggal_indo($data_dops[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_dops[instansi]<br><i>Lokasi: $data_dops[lokasi]</i><br><br><i>Nilai: $data_dops[nilai_rata]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_dops[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian MINI-CEX
			echo "<a id=\"minicex_kdk\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian MINI-CEX</font></a><br><br>";
			$nilai_minicex = mysqli_query($con,"SELECT * FROM `kdk_nilai_minicex` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze4_kdk\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:20%\">Instansi / Lokasi</th>
						<th style=\"width:5%\">No Ujian</th>
						<th style=\"width:15%\">Problem Pasien/Diagnosis</th>
						<th style=\"width:25%\">Penilai/DPJP</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_minicex = mysqli_num_rows($nilai_minicex);
			if ($cek_nilai_minicex<1)
			{
				echo "<tr><td colspan=7 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_minicex=mysqli_fetch_array($nilai_minicex))
				{
					$tanggal_isi = tanggal_indo($data_minicex[tgl_isi]);
					$tanggal_approval = tanggal_indo($data_minicex[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_minicex[instansi]<br><i>Lokasi: $data_minicex[lokasi]</i><br><br><i>Nilai: $data_minicex[nilai_rata]</i></td>";
					echo "<td align=center>$data_minicex[no_ujian]</td>";
					echo "<td>$data_minicex[diagnosis]</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_minicex[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//Pengisian Formulir Penilaian Presensi / Kehadiran
			echo "<a id=\"presensi_kdk\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Presensi / Kehadiran</font></a><br><br>";
			$nilai_presensi = mysqli_query($con,"SELECT * FROM `kdk_nilai_presensi` WHERE `nim`='$data_mhsw[nim]'");
			echo "<table id=\"freeze5_kdk\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Instansi / Lokasi</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dokter Pembimbing</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_presensi = mysqli_num_rows($nilai_presensi);
			if ($cek_nilai_presensi<1)
			{
				echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_presensi=mysqli_fetch_array($nilai_presensi))
				{
					$tanggal_isi = tanggal_indo($data_presensi[tgl_isi]);
					$tanggal_mulai = tanggal_indo($data_presensi[tgl_mulai]);
					$tanggal_selesai = tanggal_indo($data_presensi[tgl_selesai]);
					$tanggal_approval = tanggal_indo($data_presensi[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_presensi[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_presensi[instansi]<br><i>Lokasi: $data_presensi[lokasi]</i><br><br><i>Nilai: $data_presensi[nilai_total]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</td>";
					echo "<td align=center>";
					if ($data_presensi[status_approval]=='0')
					echo "<font style=\"color:red\">BELUM DISETUJUI</font>";
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table><br><br>";

			//------------------------------------
		}
		//-------------------------------------
		//End of Kepaniteraan (Stase) Komprehensip dan Kedokteran Keluarga
		//-------------------------------------

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
		$("#freeze1_M091").freezeHeader();
		$("#freeze2_M091").freezeHeader();
		$("#freeze3_M091").freezeHeader();
		$("#freeze4_M091").freezeHeader();

		$("#freeze1_M092").freezeHeader();
		$("#freeze2_M092").freezeHeader();
		$("#freeze3_M092").freezeHeader();
		$("#freeze4_M092").freezeHeader();
		$("#freeze5_M092").freezeHeader();

		$("#freeze1_M093").freezeHeader();
		$("#freeze2_M093").freezeHeader();
		$("#freeze3_M093").freezeHeader();
		$("#freeze4_M093").freezeHeader();
		$("#freeze5_M093").freezeHeader();

		$("#freeze1_M105").freezeHeader();
		$("#freeze2_M105").freezeHeader();
		$("#freeze3_M105").freezeHeader();
		$("#freeze4_M105").freezeHeader();
		$("#freeze5_M105").freezeHeader();

		$("#freeze1_M095").freezeHeader();
		$("#freeze2_M095").freezeHeader();
		$("#freeze3_M095").freezeHeader();
		$("#freeze4_M095").freezeHeader();
		$("#freeze5_M095").freezeHeader();

		$("#freeze1_M101").freezeHeader();
		$("#freeze2_M101").freezeHeader();

		$("#freeze1_M102").freezeHeader();
		$("#freeze2_M102").freezeHeader();
		$("#freeze3_M102").freezeHeader();

		$("#freeze1_M103").freezeHeader();
		$("#freeze2_M103").freezeHeader();
		$("#freeze3_M103").freezeHeader();
		$("#freeze4_M103").freezeHeader();
		$("#freeze5_M103").freezeHeader();

	  $("#freeze1_M104").freezeHeader();
		$("#freeze2_M104").freezeHeader();
		$("#freeze3_M104").freezeHeader();
		$("#freeze4_M104").freezeHeader();
		$("#freeze5_M104").freezeHeader();

		$("#freeze1_M094").freezeHeader();
		$("#freeze2_M094").freezeHeader();
		$("#freeze3_M094").freezeHeader();
		$("#freeze4_M094").freezeHeader();

		$("#freeze1_M106").freezeHeader();
		$("#freeze2_M106").freezeHeader();
		$("#freeze3_M106").freezeHeader();
		$("#freeze4_M106").freezeHeader();

		$("#freeze1_M111").freezeHeader();
		$("#freeze2_M111").freezeHeader();
		$("#freeze3_M111").freezeHeader();
		$("#freeze4_M111").freezeHeader();

		$("#freeze1_M112").freezeHeader();
		$("#freeze2_M112").freezeHeader();
		$("#freeze3_M112").freezeHeader();
		$("#freeze4_M112").freezeHeader();
		$("#freeze5_M112").freezeHeader();

		$("#freeze1_M113").freezeHeader();
		$("#freeze2_M113").freezeHeader();
		$("#freeze3_M113").freezeHeader();
		$("#freeze4_M113").freezeHeader();
		$("#freeze5_M113").freezeHeader();
		$("#freeze6_M113").freezeHeader();
		$("#freeze7_M113").freezeHeader();
		$("#freeze8_M113").freezeHeader();

		$("#freeze1_M114").freezeHeader();
		$("#freeze2_M114").freezeHeader();

		$("#freeze1_kompre").freezeHeader();
		$("#freeze2_kompre").freezeHeader();
		$("#freeze3_kompre").freezeHeader();
		$("#freeze4_kompre").freezeHeader();
		$("#freeze5_kompre").freezeHeader();

		$("#freeze1_kdk").freezeHeader();
		$("#freeze2_kdk").freezeHeader();
		$("#freeze3_kdk").freezeHeader();
		$("#freeze4_kdk").freezeHeader();
		$("#freeze5_kdk").freezeHeader();
	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
