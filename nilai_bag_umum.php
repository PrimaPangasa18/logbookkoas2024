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
		echo "<div class=\"text_header\" id=\"top\">REKAP NILAI BAGIAN / KEPANITERAAN (STASE)</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP NILAI BAGIAN / KEPANITERAAN (STASE)</font></h4>";

		$grup_filter = $_GET[grup];
		$id_stase = $_GET[stase];
		$angkatan_filter = $_GET[angk];
		$stase_id = "stase_".$id_stase;
		$include_id = "include_".$id_stase;
		$target_id = "target_".$id_stase;
		$tgl_awal = $_GET[tglawal];
		$tgl_akhir = $_GET[tglakhir];

		$filterstase = "`stase`="."'$id_stase'";
		$filtertgl = " AND (`tanggal`>="."'$tgl_awal'"." AND `tanggal`<="."'$tgl_akhir')";
		$filter = $filterstase.$filtertgl;

		$hari_kurang = 28-1;
		$kurang_hari = '-'.$hari_kurang.' days';
		$tgl_batas = date('Y-m-d', strtotime($kurang_hari, strtotime($tgl_akhir)));

		if ($grup_filter=="1") $filtergrup = "`tgl_mulai`<="."'$tgl_batas'";
		if ($grup_filter=="2") $filtergrup = "`tgl_mulai`>"."'$tgl_batas'"." AND `tgl_mulai`<="."'$tgl_akhir'";
		if ($grup_filter=="all") $filtergrup = "`tgl_mulai`<="."'$tgl_akhir'";

		$mhsw = mysqli_query($con,"SELECT `nim` FROM `$stase_id` WHERE $filtergrup AND (`tgl_mulai`>='$tgl_awal' AND `tgl_selesai`<='$tgl_akhir') ORDER BY `nim`");
		$delete_dummy_mhsw = mysqli_query($con,"DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");

		$i = 0;
		while ($data_mhsw=mysqli_fetch_array($mhsw))
		{
			//cek Angkatan
			$angkatan = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
			if ($angkatan_filter==$angkatan[angkatan] OR $angkatan_filter=="all")
			{
				$i++;
				$nama = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				$nama_mhsw = addslashes($nama[nama]);
				$insert_dummy = mysqli_query($con,"INSERT INTO `daftar_koas_temp`
					(`id`,`nim`,`nama`,`username`)
					VALUES
					('$i','$data_mhsw[nim]','$nama_mhsw','$_COOKIE[user]')");
			}
		}
		$jml_mhsw=$i;

		$stase = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		//--------------------
		echo "<table style=\"width:50%\">";
		echo "<tr>";
		echo "<td style=\"width:30%\">Kepaniteraan (Stase)</td><td style=\"width:70%\">: $stase[kepaniteraan]</td>";
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
		echo "<font style=\"font-size:0.75em\"><i>(Klik nama mahasiswa untuk melihat data penilaiannya)</i></font><br><br>";
		//------------------

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Ilmu Penyakit Dalam
		if ($id_stase=="M091")
		{
		  echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
		  echo "<thead>
		          <th style=\"width:5%\">No</th>
		          <th style=\"width:35%\">Nama / NIM / Angkatan</th>
		          <th style=\"width:12%\">Periode</th>
		          <th style=\"width:8%\">Mini-Cex</th>
		          <th style=\"width:8%\">Ujian Kasus</th>
		          <th style=\"width:8%\">Ujian MCQ</th>
		          <th style=\"width:8%\">Ujian Akhir</th>
		          <th style=\"width:8%\">Nilai Angka</th>
		          <th style=\"width:8%\">Grade</th>
		        </thead>";

		  //Perhitungan Data per Mahasiswa
		  $data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
		  $no = 1;
		  $kelas = "ganjil";
		  while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
		  {
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    $angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
		    echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";
		    //Periode Kepaniteraan/Stase
		    $tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
		    $tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
		    $tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
		    echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//---------------------
				//Rekap Nilai Mini-Cex
				//---------------------
				//Nilai Rata Mini-Cex
				$daftar_minicex = mysqli_query($con,"SELECT `id` FROM `ipd_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'");
				$jumlah_minicex = mysqli_num_rows($daftar_minicex);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `ipd_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if ($jumlah_minicex>0) $rata_minicex =  number_format($jum_nilai[0]/7,2);
				else $rata_minicex = 0.00;
				//---------------------

				//-------------------------------
				//Rekap Nilai Penyajian Kasus Besar
				//-------------------------------
				$kasus = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ipd_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_kasus = number_format($kasus[nilai_rata],2);

				//-------------------------------
				//Rekap Nilai Ujian Akhir
				//-------------------------------
				$ujian = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ipd_nilai_ujian` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_ujian = number_format($ujian[nilai_rata],2);

				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$mcq = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ipd_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='6' AND `status_approval`='1'"));
				$nilai_mcq = number_format($mcq[0],2);

				$nilai_total = number_format(0.2*$rata_minicex+0.2*$nilai_kasus+0.2*$nilai_mcq+0.4*$nilai_ujian,2);

				echo "<td align=center>$rata_minicex</td>";
		    echo "<td align=center>$nilai_kasus</td>";
		    echo "<td align=center>$nilai_mcq</td>";
		    echo "<td align=center>$nilai_ujian</td>";

		    //Total Nilai
		    echo "<td align=center>$nilai_total</td>";

		    //Nilai Total Rata-rata
		    if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
		    if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
		    if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
		    if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
		    if ($nilai_total<50) $grade = "E";
		    echo "<td align=center>$grade<br><br>[ <a href=\"bag_ipd/cetak_nilai_ipd.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		  echo "</table>";

		  echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
		    </form>";
		}
		//End of Ilmu Penyakit Dalam

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Neurologi
		if ($id_stase=="M092")
		{
			echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
			echo "<thead>
							<th style=\"width:5%\">No</th>
							<th style=\"width:27%\">Nama / NIM / Angkatan</th>
							<th style=\"width:12%\">Periode</th>
							<th style=\"width:8%\">Kasus CBD</th>
							<th style=\"width:8%\">Jurnal</th>
							<th style=\"width:8%\">Ujian SPV</th>
							<th style=\"width:8%\">MINI-CEX</th>
							<th style=\"width:8%\">OSCE+MCQ</th>
							<th style=\"width:8%\">Nilai Angka</th>
							<th style=\"width:8%\">Grade</th>
						</thead>";

			//Perhitungan Data per Mahasiswa
			$data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$no = 1;
			$kelas = "ganjil";
			while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
			{
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				$angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";

				//Periode Kepaniteraan/Stase
				$tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
				$tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
				$tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
				echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Kasus CBD
				//-------------------------------
				$kasus_cbd = mysqli_query($con,"SELECT DISTINCT `kasus_ke` FROM `neuro_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'");
				$jumlah_nilai = 0;
				while ($data_cbd = mysqli_fetch_array($kasus_cbd))
				{
				  $nilai = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai_rata`) FROM `neuro_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `kasus_ke`='$data_cbd[kasus_ke]'"));
				  $jumlah_nilai = $jumlah_nilai + $nilai[0];
				}
				$nilai_cbd = $jumlah_nilai/5;
				$nilai_cbd = number_format($nilai_cbd,2);
				echo "<td align=center>$nilai_cbd</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Journal Reading
				//-------------------------------
				$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai_total`) FROM `neuro_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_jurnal = $data_nilai_jurnal[0];
				$nilai_jurnal = number_format($nilai_jurnal,2);
				echo "<td align=center>$nilai_jurnal</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Ujian SPV
				//-------------------------------
				$data_nilai_spv = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `neuro_nilai_spv` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_spv = $data_nilai_spv[0];
				$nilai_spv = number_format($nilai_spv,2);
				echo "<td align=center>$nilai_spv</td>";

				//-------------------------------
				//Rekap Nilai Ujian Mini-CEX
				//-------------------------------
				$data_nilai_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai_rata`) FROM `neuro_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_minicex = $data_nilai_minicex[0];
				$nilai_minicex = number_format($nilai_minicex,2);
				echo "<td align=center>$nilai_minicex</td>";

				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$nilai_osce = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `neuro_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='5'"));
				$nilai_mcq = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `neuro_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='6'"));
				$nilai_test = ($nilai_osce[0]+$nilai_mcq[0])/2;
				$nilai_test = number_format($nilai_test,2);
				echo "<td align=center>$nilai_test</td>";

				//Total Nilai
				$nilai_total = number_format(0.1*$nilai_cbd+0.1*$nilai_jurnal+0.15*$nilai_spv+0.15*$nilai_minicex+0.5*$nilai_test,2);
				echo "<td align=center>$nilai_total</td>";

				//Nilai Total Rata-rata
				if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
				if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
				if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
				if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
				if ($nilai_total<50) $grade = "E";
				echo "<td align=center>$grade<br><br>[ <a href=\"bag_neuro/cetak_nilai_neuro.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
			echo "</table>";

			echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
				</form>";
		}

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Psikiatri
		if ($id_stase=="M093")
		{
			echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
			echo "<thead>
							<th style=\"width:5%\">No</th>
							<th style=\"width:27%\">Nama / NIM / Angkatan</th>
							<th style=\"width:12%\">Periode</th>
							<th style=\"width:8%\">Jurnal</th>
							<th style=\"width:8%\">CBD</th>
							<th style=\"width:8%\">MINI-CEX</th>
							<th style=\"width:8%\">OSCE</th>
							<th style=\"width:8%\">Pre+Post</th>
							<th style=\"width:8%\">Nilai Angka</th>
							<th style=\"width:8%\">Grade</th>
						</thead>";

			//Perhitungan Data per Mahasiswa
			$data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$no = 1;
			$kelas = "ganjil";
			while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
			{
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				$angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";

				//Periode Kepaniteraan/Stase
				$tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
				$tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
				$tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
				echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Journal Reading
				//-------------------------------
				$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `psikiatri_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_jurnal = number_format($data_nilai_jurnal[nilai_rata],2);
				echo "<td align=center>$nilai_jurnal</td>";

				//-------------------------------
				//Rekap Nilai Penilaian CBD
				//-------------------------------
				$data_nilai_cbd = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `psikiatri_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_cbd = number_format($data_nilai_cbd[nilai_rata],2);
				echo "<td align=center>$nilai_cbd</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Ujian MINI-CEX
				//-------------------------------
				$data_nilai_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `psikiatri_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_minicex = number_format($data_nilai_minicex[nilai_rata],2);
				echo "<td align=center>$nilai_minicex</td>";

				//-------------------------------
				//Rekap Nilai Ujian OSCE
				//-------------------------------
				$data_nilai_osce = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `psikiatri_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_osce = number_format($data_nilai_osce[nilai_rata],2);
				echo "<td align=center>$nilai_osce</td>";

				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$nilai_pretest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `psikiatri_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='1'"));
				$nilai_posttest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `psikiatri_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='2'"));
				$nilai_test = ($nilai_pretest[0]+$nilai_posttest[0])/2;
				$nilai_test = number_format($nilai_test,2);echo "<td align=center>$nilai_test</td>";

				//Total Nilai
				$nilai_total = number_format(0.25*$nilai_jurnal+0.125*$nilai_cbd+0.125*$nilai_minicex+0.25*$nilai_osce+0.25*$nilai_test,2);
				echo "<td align=center>$nilai_total</td>";

				//Nilai Total Rata-rata
				if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
				if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
				if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
				if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
				if ($nilai_total<50) $grade = "E";
				echo "<td align=center>$grade<br><br>[ <a href=\"bag_psikiatri/cetak_nilai_psikiatri.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
			echo "</table>";

			echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
				</form>";
		}

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Ilmu Kesehatan THT-KL
		if ($id_stase=="M105")
		{
			echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
			echo "<thead>
							<th style=\"width:5%\">No</th>
							<th style=\"width:27%\">Nama / NIM / Angkatan</th>
							<th style=\"width:12%\">Periode</th>
							<th style=\"width:8%\">Presentasi</th>
							<th style=\"width:8%\">Responsi</th>
							<th style=\"width:8%\">Jurnal</th>
							<th style=\"width:8%\">Test</th>
							<th style=\"width:8%\">OSCE</th>
							<th style=\"width:8%\">Nilai Angka</th>
							<th style=\"width:8%\">Grade</th>
						</thead>";

			//Perhitungan Data per Mahasiswa
			$data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$no = 1;
			$kelas = "ganjil";
			while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
			{
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				$angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";
				//Periode Kepaniteraan/Stase
				$tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
				$tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
				$tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
				echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Presentasi Kasus
				//-------------------------------
				$data_nilai_presentasi = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `thtkl_nilai_presentasi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_presentasi = $data_nilai_presentasi[nilai_rata];
				$nilai_presentasi = number_format($nilai_presentasi,2);
				echo "<td align=center>$nilai_presentasi</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Responsi Kasus Kecil
				//-------------------------------
				$data_nilai_responsi = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `thtkl_nilai_responsi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$jml_data_responsi = mysqli_num_rows(mysqli_query($con,"SELECT `nilai_rata` FROM `thtkl_nilai_responsi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if ($jml_data_responsi==0) $nilai_responsi = 0;
				else $nilai_responsi = $data_nilai_responsi[0]/$jml_data_responsi;
				$nilai_responsi = number_format($nilai_responsi,2);
				echo "<td align=center>$nilai_responsi</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Journal Reading
				//-------------------------------
				$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `thtkl_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$jml_data_jurnal = mysqli_num_rows(mysqli_query($con,"SELECT `nilai_rata` FROM `thtkl_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if ($jml_data_jurnal==0) $nilai_jurnal = 0;
				else $nilai_jurnal = $data_nilai_jurnal[0]/$jml_data_jurnal;
				$nilai_jurnal = number_format($nilai_jurnal,2);
				echo "<td align=center>$nilai_jurnal</td>";

				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$nilai_pretest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `thtkl_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='1' AND `status_approval`='1'"));
				$nilai_posttest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `thtkl_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='2' AND `status_approval`='1'"));
				$nilai_sikap = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `thtkl_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
				$nilai_tugas = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `thtkl_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='4' AND `status_approval`='1'"));
				$nilai_test = ($nilai_pretest[0]+$nilai_posttest[0]+$nilai_sikap[0]+$nilai_tugas[0])/4;
				$nilai_test = number_format($nilai_test,2);
				echo "<td align=center>$nilai_test</td>";

				//-------------------------------
				//Rekap Nilai Ujian OSCE
				//-------------------------------
				$nilai_osce_laringfaring = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `jenis_ujian`='Laring Faring' AND `status_approval`='1'"));
				$nilai_osce_otologi = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `jenis_ujian`='Otologi' AND `status_approval`='1'"));
				$nilai_osce_rinologi = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `jenis_ujian`='Rinologi' AND `status_approval`='1'"));
				$nilai_osce_onkologi = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `jenis_ujian`='Onkologi' AND `status_approval`='1'"));
				$nilai_osce_alergiimunologi = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `jenis_ujian`='Alergi Imunologi' AND `status_approval`='1'"));

				$nilai_osce = ($nilai_osce_laringfaring[0]+$nilai_osce_otologi[0]+$nilai_osce_rinologi[0]+$nilai_osce_onkologi[0]+$nilai_osce_alergiimunologi[0])/5;
				$nilai_osce = number_format($nilai_osce,2);
				echo "<td align=center>$nilai_osce</td>";

				//Total Nilai
				$nilai_total = number_format(0.0476*$nilai_presentasi+0.0476*$nilai_responsi+0.0476*$nilai_jurnal+0.1905*$nilai_test+0.6667*$nilai_osce,2);
				echo "<td align=center>$nilai_total</td>";

				//Nilai Total Rata-rata
				if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
				if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
				if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
				if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
				if ($nilai_total<50) $grade = "E";
				echo "<td align=center>$grade<br><br>[ <a href=\"bag_thtkl/cetak_nilai_thtkl.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
			echo "</table>";

			echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
				</form>";
		}

		//Rekap Nilai Bagian / Kepaniteraan (Stase) IKM-KP
		if ($id_stase=="M095")
		{
			echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
			echo "<thead>
							<th style=\"width:5%\">No</th>
							<th style=\"width:35%\">Nama / NIM / Angkatan</th>
							<th style=\"width:12%\">Periode</th>
							<th style=\"width:8%\">PKBI</th>
							<th style=\"width:8%\">P2UKM</th>
							<th style=\"width:8%\">Post Test</th>
							<th style=\"width:8%\">Komprehensip</th>
							<th style=\"width:8%\">Nilai Angka</th>
							<th style=\"width:8%\">Grade</th>
						</thead>";

			//Perhitungan Data per Mahasiswa
			$data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$no = 1;
			$kelas = "ganjil";
			while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
			{
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				$angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";
				//Periode Kepaniteraan/Stase
				$tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
				$tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
				$tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
				echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Kegiatan di PKBI
				//-------------------------------
				$data_nilai_pkbi = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_total` FROM `ikmkp_nilai_pkbi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_pkbi = $data_nilai_pkbi[nilai_total];
				$nilai_pkbi = number_format($nilai_pkbi,2);
				echo "<td align=center>$nilai_pkbi</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Kegiatan di P2UKM Mlonggo
				//-------------------------------
				//Kegiatan Evaluasi Manajemen Puskesmas
				$data_nilai_p2ukm_emp = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai_total`) FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$data_mhsw[nim]' AND `jenis_penilaian`='Evaluasi Manajemen Puskesmas' AND `status_approval`='1'"));
				$nilai_p2ukm_emp = $data_nilai_p2ukm_emp[0];
				//Kegiatan Evaluasi Program Kesehatan
				$data_nilai_p2ukm_epk = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai_total`) FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$data_mhsw[nim]' AND `jenis_penilaian`='Evaluasi Program Kesehatan' AND `status_approval`='1'"));
				$nilai_p2ukm_epk = $data_nilai_p2ukm_epk[0];
				//Kegiatan Diagnosis Komunitas
				$data_nilai_p2ukm_dk = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai_total`) FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$data_mhsw[nim]' AND `jenis_penilaian`='Diagnosis Komunitas' AND `status_approval`='1'"));
				$nilai_p2ukm_dk = $data_nilai_p2ukm_dk[0];
				//Nilai Rata P2UKM
				$nilai_p2ukm = ($nilai_p2ukm_emp+$nilai_p2ukm_epk+$nilai_p2ukm_dk)/3;
				$nilai_p2ukm = number_format($nilai_p2ukm,2);
				echo "<td align=center>$nilai_p2ukm</td>";

				//-------------------------------
				//Rekap Nilai Penugasan dan Test
				//-------------------------------
				//Nilai Post-test (jenis_test=2)
				$nilai_terbaik = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ikmkp_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='2'"));
				$nilai_test = number_format($nilai_terbaik[0],2);
				echo "<td align=center>$nilai_test</td>";

				//-------------------------------
				//Rekap Nilai Ujian Komprehensip
				//-------------------------------
				$data_nilai_komprehensip = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_total` FROM `ikmkp_nilai_komprehensip` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_komprehensip = number_format($data_nilai_komprehensip[nilai_total],2);
				echo "<td align=center>$nilai_komprehensip</td>";

				//Total Nilai
				$nilai_total = number_format(($nilai_pkbi+2*$nilai_p2ukm+$nilai_test+4*$nilai_komprehensip)/8,2);
				echo "<td align=center>$nilai_total</td>";

				//Nilai Total Rata-rata
				if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
				if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
				if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
				if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
				if ($nilai_total<50) $grade = "E";
				echo "<td align=center>$grade<br><br>[ <a href=\"bag_ikmkp/cetak_nilai_ikmkp.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
			echo "</table>";

			echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
				</form>";
		}

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Ilmu Bedah
		if ($id_stase=="M101")
		{
			echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
			echo "<thead>
							<th style=\"width:5%\">No</th>
							<th style=\"width:27%\">Nama / NIM / Angkatan</th>
							<th style=\"width:12%\">Periode</th>
							<th style=\"width:7%\">Mentoring 1</th>
							<th style=\"width:7%\">Mentoring 2</th>
							<th style=\"width:7%\">Pre-Test</th>
							<th style=\"width:7%\">Post-Test</th>
							<th style=\"width:7%\">OSCE</th>
							<th style=\"width:7%\">Skill Lab</th>
							<th style=\"width:7%\">Nilai Angka</th>
							<th style=\"width:7%\">Grade</th>
						</thead>";

			//Perhitungan Data per Mahasiswa
			$data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$no = 1;
			$kelas = "ganjil";
			while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
			{
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				$angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";
				//Periode Kepaniteraan/Stase
				$tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
				$tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
				$tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
				echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai Mentoring
				//-------------------------------
				//Nilai Rata Mentoring
				$data_mentor1 = mysqli_query($con,"SELECT * FROM `bedah_nilai_mentor` WHERE `nim`='$data_mhsw[nim]' AND `bulan_ke`='1' AND `status_approval`='1'");
				$jum_mentor1 = mysqli_num_rows($data_mentor1);
				$mentor1 = mysqli_fetch_array($data_mentor1);
				if ($jum_mentor1>0) $nilai_mentor1 = number_format($mentor1[nilai_rata],2);
				else $nilai_mentor1 = "0.00";
				$data_mentor2 = mysqli_query($con,"SELECT * FROM `bedah_nilai_mentor` WHERE `nim`='$data_mhsw[nim]' AND `bulan_ke`='2' AND `status_approval`='1'");
				$jum_mentor2 = mysqli_num_rows($data_mentor2);
				$mentor2 = mysqli_fetch_array($data_mentor2);
				if ($jum_mentor2>0) $nilai_mentor2 = number_format($mentor2[nilai_rata],2);
				else $nilai_mentor2 = "0.00";
				//---------------------

				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$pre_test = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='1' AND `status_approval`='1'"));
				$post_test = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='2' AND `status_approval`='1'"));
				$osce = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
				$skill_lab = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='15' AND `status_approval`='1'"));

				$nilai_pre_test = number_format($pre_test[0],2);
				$nilai_post_test = number_format($post_test[0],2);
				$nilai_osce = number_format($osce[0],2);
				$nilai_skill_lab = number_format($skill_lab[0],2);

				$nilai_total = number_format(0.25*$nilai_mentor1+0.25*$nilai_mentor2+0.05*$nilai_pre_test+0.1*$nilai_post_test+0.25*$nilai_osce+0.1*$nilai_skill_lab,2);
				$nilai_total = number_format($nilai_total,2);

				echo "<td align=center>$nilai_mentor1</td>";
				echo "<td align=center>$nilai_mentor2</td>";
				echo "<td align=center>$nilai_pre_test</td>";
				echo "<td align=center>$nilai_post_test</td>";
				echo "<td align=center>$nilai_osce</td>";
				echo "<td align=center>$nilai_skill_lab</td>";

				//Total Nilai
				echo "<td align=center>$nilai_total</td>";

				//Nilai Total Rata-rata
				if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
				if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
				if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
				if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
				if ($nilai_total<50) $grade = "E";
				echo "<td align=center>$grade<br><br>[ <a href=\"bag_bedah/cetak_nilai_bedah.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
			echo "</table>";

			echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
				</form>";
		}
		//End of Ilmu Bedah

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Anestesi
		if ($id_stase=="M102")
		{
			echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
			echo "<thead>
							<th style=\"width:5%\">No</th>
							<th style=\"width:20%\">Nama / NIM / Angkatan</th>
							<th style=\"width:12%\">Periode</th>
							<th style=\"width:7%\">DOPS</th>
							<th style=\"width:7%\">OSCE</th>
							<th style=\"width:7%\">Pretest</th>
							<th style=\"width:7%\">Posttest</th>
							<th style=\"width:7%\">MCQ</th>
							<th style=\"width:7%\">Tugas</th>
							<th style=\"width:7%\">Sikap</th>
							<th style=\"width:7%\">Nilai Angka</th>
							<th style=\"width:7%\">Grade</th>
						</thead>";

			//Perhitungan Data per Mahasiswa
			$data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$no = 1;
			$kelas = "ganjil";
			while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
			{
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				$angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";

				//Periode Kepaniteraan/Stase
				$tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
				$tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
				$tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
				echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai DOPS
				//-------------------------------
				$data_nilai_dops = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `anestesi_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_dops = $data_nilai_dops[nilai_rata];
				$nilai_dops = number_format($nilai_dops,2);

				//-------------------------------
				//Rekap Nilai Ujian OSCE
				//-------------------------------
				$data_nilai_osce = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_total` FROM `anestesi_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_osce = $data_nilai_osce[nilai_total];
				$nilai_osce = number_format($nilai_osce,2);

				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$data_nilai_pretest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='1'"));
				$data_nilai_posttest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='2'"));
				$data_nilai_sikap = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='3'"));
				$data_nilai_tugas = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='4'"));
				$data_nilai_mcq = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='6'"));

				$nilai_pretest = number_format($data_nilai_pretest[0],2);
				$nilai_posttest = number_format($data_nilai_posttest[0],2);
				$nilai_sikap = number_format($data_nilai_sikap[0],2);
				$nilai_tugas = number_format($data_nilai_tugas[0],2);
				$nilai_mcq = number_format($data_nilai_mcq[0],2);

				echo "<td align=center>$nilai_dops</td>";
				echo "<td align=center>$nilai_osce</td>";
				echo "<td align=center>$nilai_pretest</td>";
				echo "<td align=center>$nilai_posttest</td>";
				echo "<td align=center>$nilai_mcq</td>";
				echo "<td align=center>$nilai_tugas</td>";
				echo "<td align=center>$nilai_sikap</td>";

				//Total Nilai
				$nilai_total = number_format(0.1*$nilai_dops+0.25*$nilai_osce+0.1*$nilai_pretest+0.1*$nilai_posttest+0.15*$nilai_mcq+0.1*$nilai_tugas+0.2*$nilai_sikap,2);
				echo "<td align=center>$nilai_total</td>";

				//Nilai Total Rata-rata
				if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
				if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
				if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
				if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
				if ($nilai_total<50) $grade = "E";
				echo "<td align=center>$grade<br><br>[ <a href=\"bag_anestesi/cetak_nilai_anestesi.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
			echo "</table>";

			echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
				</form>";
		}

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Radiologi
		if ($id_stase=="M103")
		{
			echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
			echo "<thead>
							<th style=\"width:5%\">No</th>
							<th style=\"width:27%\">Nama / NIM / Angkatan</th>
							<th style=\"width:12%\">Periode</th>
							<th style=\"width:7%\">CBD-RdDiag</th>
							<th style=\"width:7%\">CBD-RdTrpi</th>
							<th style=\"width:7%\">Jurnal</th>
							<th style=\"width:7%\">MCQ</th>
							<th style=\"width:7%\">OSCE</th>
							<th style=\"width:7%\">Sikap</th>
							<th style=\"width:7%\">Nilai Angka</th>
							<th style=\"width:7%\">Grade</th>
						</thead>";

			//Perhitungan Data per Mahasiswa
			$data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$no = 1;
			$kelas = "ganjil";
			while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
			{
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				$angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";

				//Periode Kepaniteraan/Stase
				$tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
				$tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
				$tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
				echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai Penilaian CBD - Radiodiagnostik
				//-------------------------------
				$data_nilai_cbd1 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `radiologi_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Radiodiagnostik' AND `status_approval`='1'"));
				$nilai_cbd1 = number_format($data_nilai_cbd1[nilai_rata],2);
				echo "<td align=center>$nilai_cbd1</td>";

				//-------------------------------
				//Rekap Nilai Penilaian CBD - Radioterapi
				//-------------------------------
				$data_nilai_cbd2 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `radiologi_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Radioterapi' AND `status_approval`='1'"));
				$nilai_cbd2 = number_format($data_nilai_cbd2[nilai_rata],2);
				echo "<td align=center>$nilai_cbd2</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Journal Reading
				//-------------------------------
				$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `radiologi_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_jurnal = number_format($data_nilai_jurnal[nilai_rata],2);
				echo "<td align=center>$nilai_jurnal</td>";

				//-------------------------------
				//Rekap Nilai Test MCQ
				//-------------------------------
				$data_nilai_mcq = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `radiologi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='6' AND `status_approval`='1'"));
				$nilai_mcq = number_format($data_nilai_mcq[0],2);//Nilai MCQ
				echo "<td align=center>$nilai_mcq</td>";

				//-------------------------------
				//Rekap Nilai Test OSCE
				//-------------------------------
				$data_nilai_osce = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `radiologi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
				$nilai_osce = number_format($data_nilai_osce[0],2);//Nilai OSCE
				echo "<td align=center>$nilai_osce</td>";

				//-------------------------------
				//Rekap Nilai Sikap dan Perilaku
				//-------------------------------
				$data_nilai_sikap = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `radiologi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
				$nilai_sikap = number_format($data_nilai_sikap[0],2);//Nilai Sikap dan Perilaku
				echo "<td align=center>$nilai_sikap</td>";

				//Total Nilai
				$nilai_total = number_format(0.10*$nilai_cbd1+0.10*$nilai_cbd2+0.10*$nilai_jurnal+0.30*$nilai_mcq+0.35*$nilai_osce+0.05*$nilai_sikap,2);
				echo "<td align=center>$nilai_total</td>";

				//Nilai Total Rata-rata
				if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
				if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
				if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
				if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
				if ($nilai_total<50) $grade = "E";
				echo "<td align=center>$grade<br><br>[ <a href=\"bag_radiologi/cetak_nilai_radiologi.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
			echo "</table>";

			echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
				</form>";
		}

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Ilmu Kesehatan Mata
		if ($id_stase=="M104")
		{
			echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
			echo "<thead>
							<th style=\"width:5%\">No</th>
							<th style=\"width:27%\">Nama / NIM / Angkatan</th>
							<th style=\"width:12%\">Periode</th>
							<th style=\"width:7%\">Kasus</th>
							<th style=\"width:7%\">Jurnal</th>
							<th style=\"width:7%\">Penyanggah</th>
							<th style=\"width:7%\">Minicex</th>
							<th style=\"width:7%\">MCQ</th>
							<th style=\"width:7%\">OSCE</th>
							<th style=\"width:7%\">Nilai Angka</th>
							<th style=\"width:7%\">Grade</th>
						</thead>";

			//Perhitungan Data per Mahasiswa
			$data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$no = 1;
			$kelas = "ganjil";
			while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
			{
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				$angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";

				//Periode Kepaniteraan/Stase
				$tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
				$tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
				$tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
				echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai Presentasi Kasus Besar
				//-------------------------------
				$data_nilai_presentasi = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_total` FROM `mata_nilai_presentasi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_presentasi = number_format($data_nilai_presentasi[nilai_total],2);
				echo "<td align=center>$nilai_presentasi</td>";

				//-------------------------------
				//Rekap Nilai Journal Reading
				//-------------------------------
				$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_total` FROM `mata_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_jurnal = number_format($data_nilai_jurnal[nilai_total],2);
				echo "<td align=center>$nilai_jurnal</td>";

				//-------------------------------
				//Rekap Nilai Penyanggah
				//-------------------------------
				$jml_penyanggah = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `mata_nilai_penyanggah` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$jml_nilai_penyanggah = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai`) FROM `mata_nilai_penyanggah` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if ($jml_penyanggah>0) $nilai_penyanggah = $jml_nilai_penyanggah[0]/$jml_penyanggah;
				else $nilai_penyanggah = 0;
				$nilai_penyanggah = number_format($nilai_penyanggah,2);
				echo "<td align=center>$nilai_penyanggah</td>";

				//-------------------------------
				//Rekap Nilai Ujian Mini-Cex
				//-------------------------------
				$data_nilai_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `mata_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_minicex = number_format($data_nilai_minicex[nilai_rata],2);
				echo "<td align=center>$nilai_minicex</td>";

				//-------------------------------
				//Rekap Nilai Test MCQ
				//-------------------------------
				$data_nilai_mcq = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `mata_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='6'"));
				$nilai_mcq = number_format($data_nilai_mcq[0],2);
				echo "<td align=center>$nilai_mcq</td>";

				//-------------------------------
				//Rekap Nilai Test OSCE
				//-------------------------------
				$data_nilai_osce = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `mata_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5'"));
				$nilai_osce = number_format($data_nilai_osce[0],2);
				echo "<td align=center>$nilai_osce</td>";

				//Total Nilai
				$nilai_total = number_format(0.15*$nilai_presentasi+0.05*$nilai_penyanggah+0.10*$nilai_jurnal+0.20*$nilai_minicex+0.25*$nilai_mcq+0.25*$nilai_osce,2);
				echo "<td align=center>$nilai_total</td>";

				//Nilai Total Rata-rata
				if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
				if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
				if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
				if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
				if ($nilai_total<50) $grade = "E";
				echo "<td align=center>$grade<br><br>[ <a href=\"bag_mata/cetak_nilai_mata.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
			echo "</table>";

			echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
				</form>";
		}

		//Rekap Nilai Bagian / Kepaniteraan (Stase) IKFR
		if ($id_stase=="M094")
		{
		  echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
		  echo "<thead>
		          <th style=\"width:5%\">No</th>
		          <th style=\"width:27%\">Nama / NIM / Angkatan</th>
		          <th style=\"width:12%\">Periode</th>
		          <th style=\"width:7%\">Kasus</th>
		          <th style=\"width:7%\">Minicex</th>
		          <th style=\"width:7%\">OSCE</th>
		          <th style=\"width:7%\">Pretest</th>
		          <th style=\"width:7%\">Posttest</th>
		          <th style=\"width:7%\">Sikap</th>
		          <th style=\"width:7%\">Nilai Angka</th>
		          <th style=\"width:7%\">Grade</th>
		        </thead>";

		  //Perhitungan Data per Mahasiswa
		  $data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
		  $no = 1;
		  $kelas = "ganjil";
		  while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
		  {
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    $angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
		    echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";

		    //Periode Kepaniteraan/Stase
		    $tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
		    $tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
		    $tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
		    echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Diskusi Kasus
				//-------------------------------
				$data_nilai_kasus = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ikfr_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_kasus = $data_nilai_kasus[nilai_rata];
				$nilai_kasus = number_format($nilai_kasus,2);
				echo "<td align=center>$nilai_kasus</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Ujian Mini-Cex
				//-------------------------------
				$data_nilai_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ikfr_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_minicex = $data_nilai_minicex[nilai_rata];
				$nilai_minicex = number_format($nilai_minicex,2);
				echo "<td align=center>$nilai_minicex</td>";


				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$pretest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ikfr_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='1' AND `status_approval`='1'"));
				$posttest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ikfr_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='2' AND `status_approval`='1'"));
				$sikap = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ikfr_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
				$osce = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ikfr_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
				$nilai_pretest = number_format($pretest[0],2);
				$nilai_posttest = number_format($posttest[0],2);
				$nilai_sikap = number_format($sikap[0],2);
				$nilai_osce = number_format($osce[0],2);

				echo "<td align=center>$nilai_osce</td>";
				echo "<td align=center>$nilai_pretest</td>";
				echo "<td align=center>$nilai_posttest</td>";
				echo "<td align=center>$nilai_sikap</td>";

				//Total Nilai
				$nilai_total = number_format(0.2*$nilai_kasus+0.2*$nilai_minicex+0.2*$nilai_osce+0.1*$nilai_pretest+0.2*$nilai_posttest+0.1*$nilai_sikap,2);
		    echo "<td align=center>$nilai_total</td>";

		    //Nilai Total Rata-rata
		    if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
		    if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
		    if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
		    if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
		    if ($nilai_total<50) $grade = "E";
		    echo "<td align=center>$grade<br><br>[ <a href=\"bag_ikfr/cetak_nilai_ikfr.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		  echo "</table>";

		  echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
		    </form>";
		}

		//Rekap Nilai Bagian / Kepaniteraan (Stase) IKGM
		if ($id_stase=="M106")
		{
		  echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
		  echo "<thead>
		          <th style=\"width:5%\">No</th>
		          <th style=\"width:27%\">Nama / NIM / Angkatan</th>
		          <th style=\"width:12%\">Periode</th>
		          <th style=\"width:8%\">Kasus</th>
		          <th style=\"width:8%\">Jurnal</th>
		          <th style=\"width:8%\">Responsi</th>
		          <th style=\"width:8%\">OSCA</th>
		          <th style=\"width:8%\">Sikap</th>
		          <th style=\"width:8%\">Nilai Angka</th>
		          <th style=\"width:8%\">Grade</th>
		        </thead>";

		  //Perhitungan Data per Mahasiswa
		  $data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
		  $no = 1;
		  $kelas = "ganjil";
		  while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
		  {
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    $angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
		    echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";

		    //Periode Kepaniteraan/Stase
		    $tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
		    $tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
		    $tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
		    echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Laporan Kasus
				//-------------------------------
				$data_nilai_kasus = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_total` FROM `ikgm_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_kasus = $data_nilai_kasus[nilai_total];
				$nilai_kasus = number_format($nilai_kasus,2);
				echo "<td align=center>$nilai_kasus</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Journal Reading
				//-------------------------------
				$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_total` FROM `ikgm_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_jurnal = $data_nilai_jurnal[nilai_total];
				$nilai_jurnal = number_format($nilai_jurnal,2);
				echo "<td align=center>$nilai_jurnal</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Responsi Kasus Kecil
				//-------------------------------
				$data_nilai_responsi = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `ikgm_nilai_responsi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$jml_data_responsi = mysqli_num_rows(mysqli_query($con,"SELECT `nilai_rata` FROM `ikgm_nilai_responsi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if ($jml_data_responsi==0) $nilai_responsi = 0;
				else $nilai_responsi = $data_nilai_responsi[0]/$jml_data_responsi;
				$nilai_responsi = number_format($nilai_responsi,2);
				echo "<td align=center>$nilai_responsi</td>";

				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$nilaisikap = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ikgm_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
				$nilaiosca = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ikgm_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
				$nilai_sikap = number_format($nilaisikap[0],2);
				$nilai_osca = number_format($nilaiosca[0],2);
				echo "<td align=center>$nilai_osca</td>";
				echo "<td align=center>$nilai_sikap</td>";

				//Total Nilai
				$nilai_total = number_format(0.125*$nilai_kasus+0.125*$nilai_jurnal+0.3*$nilai_responsi+0.3*$nilai_osca+0.15*$nilai_sikap,2);
		    echo "<td align=center>$nilai_total</td>";

		    //Nilai Total Rata-rata
		    if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
		    if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
		    if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
		    if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
		    if ($nilai_total<50) $grade = "E";
		    echo "<td align=center>$grade<br><br>[ <a href=\"bag_ikgm/cetak_nilai_ikgm.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		  echo "</table>";

		  echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
		    </form>";
		}

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan
		if ($id_stase=="M111")
		{
		  echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
		  echo "<thead>
		          <th style=\"width:5%\">No</th>
		          <th style=\"width:27%\">Nama / NIM / Angkatan</th>
		          <th style=\"width:12%\">Periode</th>
		          <th style=\"width:7%\">MINI-CEX</th>
		          <th style=\"width:7%\">CBD</th>
							<th style=\"width:7%\">Jurnal</th>
		          <th style=\"width:7%\">MCQ</th>
		          <th style=\"width:7%\">DOPS/OSCE</th>
							<th style=\"width:7%\">MINI-PAT</th>
		          <th style=\"width:7%\">Nilai Angka</th>
		          <th style=\"width:7%\">Grade</th>
		        </thead>";

		  //Perhitungan Data per Mahasiswa
		  $data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
		  $no = 1;
		  $kelas = "ganjil";
		  while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
		  {
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    $angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
		    echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";
		    //Periode Kepaniteraan/Stase
		    $tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
		    $tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
		    $tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
		    echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//---------------------
				//Rekap Nilai Mini-Cex
				//---------------------
				//Nilai Rata Mini-Cex
				$data_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `obsgyn_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if (!empty($data_minicex)) $nilai_minicex = $data_minicex[nilai_rata];
				else $nilai_minicex = 0;
				$nilai_minicex = number_format($nilai_minicex,2);
				//---------------------

				//-------------------------------
				//Rekap Nilai CBD
				//-------------------------------
				$daftar_cbd = mysqli_query($con,"SELECT `id` FROM `obsgyn_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'");
				$jumlah_cbd = mysqli_num_rows($daftar_cbd);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `obsgyn_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if ($jumlah_cbd>0) $rata_cbd =  $jum_nilai[0]/4;
				else $rata_cbd = 0;
				$rata_cbd = number_format($rata_cbd,2);

				//---------------------
				//Nilai Journal Reading
				$data_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `obsgyn_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if (!empty($data_jurnal)) $nilai_jurnal = $data_jurnal[nilai_rata];
				else $nilai_jurnal = 0;
				$nilai_jurnal = number_format($nilai_jurnal,2);
				//---------------------

				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$mcq = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `obsgyn_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='6' AND `status_approval`='1'"));
				$nilai_mcq = number_format($mcq[0],2);
				$dops_osce = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `obsgyn_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='13' AND `status_approval`='1'"));
				$nilai_dops_osce = number_format($dops_osce[0],2);
				$minipat = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `obsgyn_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='14' AND `status_approval`='1'"));
				$nilai_minipat = number_format($minipat[0],2);

				$nilai_total = number_format(0.2*$nilai_minicex+0.15*$rata_cbd+0.05*$nilai_jurnal+0.3*$nilai_dops_osce+0.2*$nilai_mcq+0.1*$nilai_minipat,2);

				echo "<td align=center>$nilai_minicex</td>";
		    echo "<td align=center>$rata_cbd</td>";
				echo "<td align=center>$nilai_jurnal</td>";
		    echo "<td align=center>$nilai_mcq</td>";
		    echo "<td align=center>$nilai_dops_osce</td>";
				echo "<td align=center>$nilai_minipat</td>";

		    //Total Nilai
		    echo "<td align=center>$nilai_total</td>";

		    //Nilai Total Rata-rata
		    if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
		    if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
		    if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
		    if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
		    if ($nilai_total<50) $grade = "E";
		    echo "<td align=center>$grade<br><br>[ <a href=\"bag_obsgyn/cetak_nilai_obsgyn.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		  echo "</table>";

		  echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
		    </form>";
		}
		//End of Ilmu Kebidanan dan Penyakit Kandungan

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal
		if ($id_stase=="M112")
		{
		  echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
		  echo "<thead>
		          <th style=\"width:5%\">No</th>
		          <th style=\"width:20%\">Nama / NIM / Angkatan</th>
		          <th style=\"width:12%\">Periode</th>
		          <th style=\"width:7%\">Visum</th>
		          <th style=\"width:7%\">Jaga</th>
		          <th style=\"width:7%\">Substase</th>
		          <th style=\"width:7%\">Referat</th>
		          <th style=\"width:7%\">Pretest</th>
							<th style=\"width:7%\">Sikap</th>
							<th style=\"width:7%\">Kompre</th>
							<th style=\"width:7%\">Nilai Angka</th>
		          <th style=\"width:7%\">Grade</th>
		        </thead>";

		  //Perhitungan Data per Mahasiswa
		  $data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
		  $no = 1;
		  $kelas = "ganjil";
		  while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
		  {
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    $angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
		    echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";

		    //Periode Kepaniteraan/Stase
		    $tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
		    $tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
		    $tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
		    echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai Penilaian Visum Bayangan
				//-------------------------------
				$data_nilai_visum1 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='1' AND `status_approval`='1'"));
				$data_nilai_visum2 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='2' AND `status_approval`='1'"));
				$data_nilai_visum3 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='3' AND `status_approval`='1'"));
				$data_nilai_visum4 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='4' AND `status_approval`='1'"));
				$nilai_visum1 = number_format($data_nilai_visum1[nilai_rata],2);
				$nilai_visum2 = number_format($data_nilai_visum2[nilai_rata],2);
				$nilai_visum3 = number_format($data_nilai_visum3[nilai_rata],2);
				$nilai_visum4 = number_format($data_nilai_visum4[nilai_rata],2);
				$nilai_visum = number_format(($nilai_visum1+$nilai_visum2+$nilai_visum3+$nilai_visum4)/4,2);

				//-------------------------------
				//Rekap Nilai Penilaian Kegiatan Jaga
				//-------------------------------
				$data_nilai_jaga1 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='1' AND `status_approval`='1'"));
				$data_nilai_jaga2 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='2' AND `status_approval`='1'"));
				$data_nilai_jaga3 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='3' AND `status_approval`='1'"));
				$data_nilai_jaga4 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='4' AND `status_approval`='1'"));
				$nilai_jaga1 = number_format($data_nilai_jaga1[nilai_rata],2);
				$nilai_jaga2 = number_format($data_nilai_jaga2[nilai_rata],2);
				$nilai_jaga3 = number_format($data_nilai_jaga3[nilai_rata],2);
				$nilai_jaga4 = number_format($data_nilai_jaga4[nilai_rata],2);
				$nilai_jaga = number_format(($nilai_jaga1+$nilai_jaga2+$nilai_jaga3+$nilai_jaga4)/4,2);

				//-------------------------------
				//Rekap Nilai Penilaian Substase
				//-------------------------------
				$data_nilai_substase1 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='1' AND `status_approval`='1'"));
				$data_nilai_substase2 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='2' AND `status_approval`='1'"));
				$data_nilai_substase3 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='3' AND `status_approval`='1'"));
				$data_nilai_substase4 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='4' AND `status_approval`='1'"));
				$nilai_substase1 = number_format($data_nilai_substase1[nilai_rata],2);
				$nilai_substase2 = number_format($data_nilai_substase2[nilai_rata],2);
				$nilai_substase3 = number_format($data_nilai_substase3[nilai_rata],2);
				$nilai_substase4 = number_format($data_nilai_substase4[nilai_rata],2);
				$nilai_substase = number_format(($nilai_substase1+$nilai_substase2+$nilai_substase3+$nilai_substase4)/4,2);

				//-------------------------------
				//Rekap Nilai Penilaian Referat
				//-------------------------------
				$data_nilai_referat = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_referat` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				$nilai_referat = $data_nilai_referat[nilai_rata];
				$nilai_referat = number_format($nilai_referat,2);

				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$pretest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='1' AND `status_approval`='1'"));
				$sikap = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
				$kompre1 = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='7' AND `status_approval`='1'"));
				$kompre2 = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='8' AND `status_approval`='1'"));
				$kompre3 = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='9' AND `status_approval`='1'"));
				$kompre4 = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='10' AND `status_approval`='1'"));
				$kompre5 = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='11' AND `status_approval`='1'"));
				$nilai_pretest = number_format($pretest[0],2);
				$nilai_sikap = number_format($sikap[0],2);
				$nilai_kompre1 = number_format($kompre1[0],2);
				$nilai_kompre2 = number_format($kompre2[0],2);
				$nilai_kompre3 = number_format($kompre3[0],2);
				$nilai_kompre4 = number_format($kompre4[0],2);
				$nilai_kompre5 = number_format($kompre5[0],2);
				$nilai_kompre = number_format(($nilai_kompre1+$nilai_kompre2+$nilai_kompre3+$nilai_kompre4+$nilai_kompre5)/5,2);

				$nilai_total = number_format(0.09*$nilai_visum+0.06*$nilai_jaga+0.12*$nilai_substase+0.24*$nilai_referat+0.1*$nilai_pretest+0.03*$nilai_sikap+0.36*$nilai_kompre,2);

				echo "<td align=center>$nilai_visum</td>";
				echo "<td align=center>$nilai_jaga</td>";
				echo "<td align=center>$nilai_substase</td>";
				echo "<td align=center>$nilai_referat</td>";
				echo "<td align=center>$nilai_pretest</td>";
				echo "<td align=center>$nilai_sikap</td>";
				echo "<td align=center>$nilai_kompre</td>";
				echo "<td align=center>$nilai_total</td>";

		    //Nilai Total Rata-rata
		    if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
		    if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
		    if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
		    if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
		    if ($nilai_total<50) $grade = "E";
		    echo "<td align=center>$grade<br><br>[ <a href=\"bag_forensik/cetak_nilai_forensik.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		  echo "</table>";

		  echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
		    </form>";
		}

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Ilmu Kesehatan Anak
		if ($id_stase=="M113")
		{
		  echo "<table style=\"width:100%;font-size:0.8em;\" border=1 id=\"freeze\">";
		  echo "<thead>
		          <th style=\"width:3%\">No</th>
		          <th style=\"width:10%\">Nama / NIM / Angkatan</th>
		          <th style=\"width:6%\">Periode</th>
							<th style=\"width:5%\">Pre-Test</th>
							<th style=\"width:5%\">Post-Test</th>
							<th style=\"width:5%\">MCex Inf</th>
							<th style=\"width:5%\">MCex Non Inf</th>
							<th style=\"width:5%\">MCex ERIA</th>
							<th style=\"width:5%\">MCex Peri</th>
							<th style=\"width:5%\">MCex Jejaring</th>
							<th style=\"width:5%\">CBD Poli</th>
							<th style=\"width:5%\">DOPS Klinis</th>
							<th style=\"width:5%\">Kasus</th>
							<th style=\"width:5%\">Jurnal</th>
							<th style=\"width:5%\">Mini-PAT</th>
							<th style=\"width:5%\">OSCE</th>
							<th style=\"width:5%\">Ujian Akhir</th>
							<th style=\"width:5%\">Nilai Angka</th>
							<th style=\"width:6%\">Grade</th>
		        </thead>";

		  //Perhitungan Data per Mahasiswa
		  $data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
		  $no = 1;
		  $kelas = "ganjil";
		  while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
		  {
		    echo "<tr class=\"$kelas\">";
		    echo "<td align=center>$no</td>";
		    $angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
		    echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";
		    //Periode Kepaniteraan/Stase
		    $tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
		    $tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
		    $tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
		    echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//Nilai Bagian
				//---------------------
				//-------------------------------
				//Rekap Nilai Mini-CEX

				//Rata Nilai Mini-CEX Infeksi
				$minicex_infeksi1 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='1' AND `status_approval`='1'"));
				$minicex_infeksi2 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='2' AND `status_approval`='1'"));
				$minicex_infeksi3 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='3' AND `status_approval`='1'"));
				$minicex_infeksi4 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='4' AND `status_approval`='1'"));
				if (!empty($minicex_infeksi1)) $nilai_minicex_infeksi1 = $minicex_infeksi1[nilai_rata];
				else $nilai_minicex_infeksi1 = 0;
				$nilai_minicex_infeksi1 = number_format($nilai_minicex_infeksi1,2);
				if (!empty($minicex_infeksi2)) $nilai_minicex_infeksi2 = $minicex_infeksi2[nilai_rata];
				else $nilai_minicex_infeksi2 = 0;
				$nilai_minicex_infeksi2 = number_format($nilai_minicex_infeksi2,2);
				if (!empty($minicex_infeksi3)) $nilai_minicex_infeksi3 = $minicex_infeksi3[nilai_rata];
				else $nilai_minicex_infeksi3 = 0;
				$nilai_minicex_infeksi3 = number_format($nilai_minicex_infeksi3,2);
				if (!empty($minicex_infeksi4)) $nilai_minicex_infeksi4 = $minicex_infeksi4[nilai_rata];
				else $nilai_minicex_infeksi4 = 0;
				$nilai_minicex_infeksi4 = number_format($nilai_minicex_infeksi4,2);

				$nilai_rata_minicex_infeksi = ($nilai_minicex_infeksi1 + $nilai_minicex_infeksi2 + $nilai_minicex_infeksi3 + $nilai_minicex_infeksi4)/4;
				$nilai_rata_minicex_infeksi = number_format($nilai_rata_minicex_infeksi,2);

				//Rata Nilai Mini-CEX Non-Infeksi
				$minicex_noninfeksi1 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='5' AND `status_approval`='1'"));
				$minicex_noninfeksi2 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='6' AND `status_approval`='1'"));
				$minicex_noninfeksi3 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='7' AND `status_approval`='1'"));
				$minicex_noninfeksi4 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='8' AND `status_approval`='1'"));
				$minicex_noninfeksi5 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='9' AND `status_approval`='1'"));
				$minicex_noninfeksi6 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='10' AND `status_approval`='1'"));
				$minicex_noninfeksi7 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='11' AND `status_approval`='1'"));
				if (!empty($minicex_noninfeksi1)) $nilai_minicex_noninfeksi1 = $minicex_noninfeksi1[nilai_rata];
				else $nilai_minicex_noninfeksi1 = 0;
				$nilai_minicex_noninfeksi1 = number_format($nilai_minicex_noninfeksi1,2);
				if (!empty($minicex_noninfeksi2)) $nilai_minicex_noninfeksi2 = $minicex_noninfeksi2[nilai_rata];
				else $nilai_minicex_noninfeksi2 = 0;
				$nilai_minicex_noninfeksi2 = number_format($nilai_minicex_noninfeksi2,2);
				if (!empty($minicex_noninfeksi3)) $nilai_minicex_noninfeksi3 = $minicex_noninfeksi3[nilai_rata];
				else $nilai_minicex_noninfeksi3 = 0;
				$nilai_minicex_noninfeksi3 = number_format($nilai_minicex_noninfeksi3,2);
				if (!empty($minicex_noninfeksi4)) $nilai_minicex_noninfeksi4 = $minicex_noninfeksi4[nilai_rata];
				else $nilai_minicex_noninfeksi4 = 0;
				$nilai_minicex_noninfeksi4 = number_format($nilai_minicex_noninfeksi4,2);
				if (!empty($minicex_noninfeksi5)) $nilai_minicex_noninfeksi5 = $minicex_noninfeksi5[nilai_rata];
				else $nilai_minicex_noninfeksi5 = 0;
				$nilai_minicex_noninfeksi5 = number_format($nilai_minicex_noninfeksi5,2);
				if (!empty($minicex_noninfeksi6)) $nilai_minicex_noninfeksi6 = $minicex_noninfeksi6[nilai_rata];
				else $nilai_minicex_noninfeksi6 = 0;
				$nilai_minicex_noninfeksi6 = number_format($nilai_minicex_noninfeksi6,2);
				if (!empty($minicex_noninfeksi7)) $nilai_minicex_noninfeksi7 = $minicex_noninfeksi7[nilai_rata];
				else $nilai_minicex_noninfeksi7 = 0;
				$nilai_minicex_noninfeksi7 = number_format($nilai_minicex_noninfeksi7,2);

				$nilai_rata_minicex_noninfeksi = ($nilai_minicex_noninfeksi1 + $nilai_minicex_noninfeksi2 + $nilai_minicex_noninfeksi3 + $nilai_minicex_noninfeksi4 + $nilai_minicex_noninfeksi5 + $nilai_minicex_noninfeksi6 + $nilai_minicex_noninfeksi7)/7;
				$nilai_rata_minicex_noninfeksi = number_format($nilai_rata_minicex_noninfeksi,2);

				//Rata Nilai Mini-CEX ERIA
				$minicex_eria = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='12' AND `status_approval`='1'"));
				if (!empty($minicex_eria)) $nilai_minicex_eria = $minicex_eria[nilai_rata];
				else $nilai_minicex_eria = 0;
				$nilai_minicex_eria = number_format($nilai_minicex_eria,2);

				//Rata Nilai Mini-CEX Perinatologi
				$minicex_perinatologi = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='13' AND `status_approval`='1'"));
				if (!empty($minicex_perinatologi)) $nilai_minicex_perinatologi = $minicex_perinatologi[nilai_rata];
				else $nilai_minicex_perinatologi = 0;
				$nilai_minicex_perinatologi = number_format($nilai_minicex_perinatologi,2);

				//Rata Nilai Mini-CEX RS Jejaring
				$minicex_jejaring = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='14' AND `status_approval`='1'"));
				if (!empty($minicex_jejaring)) $nilai_minicex_jejaring = $minicex_jejaring[nilai_rata];
				else $nilai_minicex_jejaring = 0;
				$nilai_minicex_jejaring = number_format($nilai_minicex_jejaring,2);

				//End of Rekap Nilai Mini-CEX
				//-------------------------------

				//-------------------------------
				//Rekap Nilai Penilaian DOPS
				//-------------------------------
				$data_nilai_dops = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if (!empty($data_nilai_dops)) $nilai_dops = $data_nilai_dops[nilai_rata];
				else $nilai_dops = 0;
				$nilai_dops = number_format($nilai_dops,2);

				//-------------------------------
				//Rekap Nilai Penilaian CBD
				//-------------------------------
				$data_nilai_cbd = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if (!empty($data_nilai_cbd)) $nilai_cbd = $data_nilai_cbd[nilai_rata];
				else $nilai_cbd = 0;
				$nilai_cbd = number_format($nilai_cbd,2);

				//-------------------------------
				//Rekap Nilai Penilaian Kasus Besar
				//-------------------------------
				$data_nilai_kasus = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if (!empty($data_nilai_kasus)) $nilai_kasus = $data_nilai_kasus[nilai_rata];
				else $nilai_kasus = 0;
				$nilai_kasus = number_format($nilai_kasus,2);

				//-------------------------------
				//Rekap Nilai Penilaian Journal Reading
				//-------------------------------
				$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if (!empty($data_nilai_jurnal)) $nilai_jurnal = $data_nilai_jurnal[nilai_rata];
				else $nilai_jurnal = 0;
				$nilai_jurnal = number_format($nilai_jurnal,2);

				//-------------------------------
				//Rekap Nilai Penilaian Mini-PAT
				//-------------------------------
				$data_nilai_minipat = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `ika_nilai_minipat` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if (!empty($data_nilai_minipat)) $nilai_minipat = $data_nilai_minipat[nilai_rata];
				else $nilai_minipat = 0;
				$nilai_minipat = number_format($nilai_minipat,2);

				//-------------------------------
				//Rekap Nilai Penilaian Ujian Akhir
				//-------------------------------
				$data_nilai_ujian = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai_rata`) FROM `ika_nilai_ujian` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if (!empty($data_nilai_ujian)) $nilai_ujian = $data_nilai_ujian[0];
				else $nilai_ujian = 0;
				$nilai_ujian = number_format($nilai_ujian,2);


				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$pretest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ika_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='1' AND `status_approval`='1'"));
				$posttest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ika_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='2' AND `status_approval`='1'"));
				$osce = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ika_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
				$nilai_pretest = number_format($pretest[0],2);
				$nilai_posttest = number_format($posttest[0],2);
				$nilai_osce = number_format($osce[0],2);

				$nilai_total = number_format(0.025*$nilai_pretest+0.075*$nilai_posttest+0.02*$nilai_rata_minicex_infeksi+0.02*$nilai_rata_minicex_noninfeksi+0.01*$nilai_minicex_eria+0.01*$nilai_minicex_perinatologi+0.02*$nilai_minicex_jejaring+0.02*$nilai_cbd+0.1*$nilai_dops+0.1*$nilai_kasus+0.1*$nilai_jurnal+0.1*$nilai_minipat+0.1*$nilai_osce+0.3*$nilai_ujian,2);

				echo "<td align=center>$nilai_pretest</td>";
		    echo "<td align=center>$nilai_posttest</td>";
		    echo "<td align=center>$nilai_rata_minicex_infeksi</td>";
		    echo "<td align=center>$nilai_rata_minicex_noninfeksi</td>";
				echo "<td align=center>$nilai_minicex_eria</td>";
		    echo "<td align=center>$nilai_minicex_perinatologi</td>";
		    echo "<td align=center>$nilai_minicex_jejaring</td>";
		    echo "<td align=center>$nilai_cbd</td>";
				echo "<td align=center>$nilai_dops</td>";
		    echo "<td align=center>$nilai_kasus</td>";
		    echo "<td align=center>$nilai_jurnal</td>";
		    echo "<td align=center>$nilai_minipat</td>";
				echo "<td align=center>$nilai_osce</td>";
		    echo "<td align=center>$nilai_ujian</td>";

		    //Total Nilai
		    echo "<td align=center>$nilai_total</td>";

		    //Nilai Total Rata-rata
		    if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
		    if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
		    if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
		    if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
		    if ($nilai_total<50) $grade = "E";
		    echo "<td align=center>$grade<br><br>[ <a href=\"bag_ika/cetak_nilai_ika.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
		    echo "</tr>";
		    $no++;
		    if ($kelas=="ganjil") $kelas="genap";
		    else $kelas="ganjil";
		  }
		  echo "</table>";

		  echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
		    <a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
		    </form>";
		}
		//End of Ilmu Kesehatan Anak

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin
		if ($id_stase=="M114")
		{
			echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
			echo "<thead>
							<th style=\"width:5%\">No</th>
							<th style=\"width:35%\">Nama / NIM / Angkatan</th>
							<th style=\"width:12%\">Periode</th>
							<th style=\"width:8%\">Kasus</th>
							<th style=\"width:8%\">OSCE</th>
							<th style=\"width:8%\">Teori</th>
							<th style=\"width:8%\">Sikap</th>
							<th style=\"width:8%\">Nilai Angka</th>
							<th style=\"width:8%\">Grade</th>
						</thead>";

			//Perhitungan Data per Mahasiswa
			$data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$no = 1;
			$kelas = "ganjil";
			while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
			{
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				$angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";
				//Periode Kepaniteraan/Stase
				$tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
				$tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
				$tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
				echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//Rekap Nilai CBD
				//-------------------------------
				$daftar_cbd = mysqli_query($con,"SELECT * FROM `kulit_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'");
				$jumlah_cbd = mysqli_num_rows($daftar_cbd);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`nilai_rata`) FROM `kulit_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if ($jumlah_cbd>0) $rata_aspek1 =  $jum_nilai[0]/$jumlah_cbd;
				else $rata_aspek1 = 0;
				if ($jumlah_cbd>0) $rata_aspek2 =  $jum_nilai[1]/$jumlah_cbd;
				else $rata_aspek2 = 0;
				if ($jumlah_cbd>0) $rata_aspek3 =  $jum_nilai[2]/$jumlah_cbd;
				else $rata_aspek3 = 0;
				if ($jumlah_cbd>0) $rata_aspek4 =  $jum_nilai[3]/$jumlah_cbd;
				else $rata_aspek4 = 0;
				if ($jumlah_cbd>0) $total_cbd =  $jum_nilai[4]/$jumlah_cbd;
				else $total_cbd = 0;
				$total_cbd = number_format($total_cbd,2);
				echo "<td align=center>$total_cbd</td>";

				//-------------------------------
				//Rekap Nilai Test
				//-------------------------------
				$sikap = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `kulit_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
				$osce = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `kulit_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
				$ujian = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `kulit_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='12' AND `status_approval`='1'"));
				$nilai_sikap = number_format($sikap[0],2);
				$nilai_osce = number_format($osce[0],2);
				$nilai_ujian = number_format($ujian[0],2);

				$nilai_rata = number_format(($total_cbd+$nilai_osce+$nilai_ujian)/3,2);
				if ($nilai_sikap>=70) $nilai_total = $nilai_rata;
				if ($nilai_sikap<70 AND $nilai_sikap>=60) $nilai_total = 0.8*$nilai_rata;
				if ($nilai_sikap<60) $nilai_total = 0.5*$nilai_rata;
				$nilai_total = number_format($nilai_total,2);

				echo "<td align=center>$nilai_osce</td>";
				echo "<td align=center>$nilai_ujian</td>";
				echo "<td align=center>$nilai_sikap</td>";

				//Total Nilai
				echo "<td align=center>$nilai_total</td>";

				//Nilai Total Rata-rata
				if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
				if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
				if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
				if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
				if ($nilai_total<50) $grade = "E";
				echo "<td align=center>$grade<br><br>[ <a href=\"bag_kulit/cetak_nilai_kulit.php?nim=$data_mhsw[nim]\" target=\"_blank\">Cetak</a> ]</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
			echo "</table>";

			echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
				</form>";
		}
		//End of Ilmu Kesehatan Kulit dan Kelamin

		//Rekap Nilai Bagian / Kepaniteraan (Stase) Komprehensip dan Kedokteran Keluarga (KDK)
		if ($id_stase=="M121")
		{
			echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
			echo "<thead>";
			echo "<tr>";
				echo "<th rowspan=2 style=\"width:4%\">No</th>";
				echo "<th rowspan=2 style=\"width:19%\">Nama / NIM /Angkatan</th>";
				echo "<th rowspan=2 style=\"width:12%\">Periode</th>";
				echo "<th colspan=5 style=\"width:25%\">Komprehensip</th>";
				echo "<th colspan=6 style=\"width:30%\">Kedokteran Keluarga</th>";
				echo "<th rowspan=2 style=\"width:5%\">Nilai Rata</th>";
				echo "<th rowspan=2 style=\"width:5%\">Grade</th>";
			echo "</tr>";
			echo "<tr>";
				//Nilai Komprehensip
				echo "<th style=\"width:5%;text-align:center;\">Lap</th>";
				echo "<th style=\"width:5%;text-align:center;\">Skp</th>";
				echo "<th style=\"width:5%;text-align:center;\">CBD</th>";
				echo "<th style=\"width:5%;text-align:center;\">Hdr</th>";
				echo "<th style=\"width:5%;text-align:center;\">NA</th>";
				//Nilai KDK
				echo "<th style=\"width:5%;text-align:center;\">Lap</th>";
				echo "<th style=\"width:5%;text-align:center;\">Skp</th>";
				echo "<th style=\"width:5%;text-align:center;\">DOPS</th>";
				echo "<th style=\"width:5%;text-align:center;\">MCEX</th>";
				echo "<th style=\"width:5%;text-align:center;\">Hdr</th>";
				echo "<th style=\"width:5%;text-align:center;\">NA</th>";
			echo "</tr>";
			echo "</thead>";

			//Perhitungan Data per Mahasiswa
			$data_mhsw_all = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$kelas = "ganjil";
			$no = 1;
			while ($data_mhsw = mysqli_fetch_array($data_mhsw_all))
			{
				echo "<tr class=\"$kelas\">";
				//No-Nama-NIM Mhsw
				echo "<td align=center>$no</td>";
				$angk_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
				echo "<td><a href=\"tampil_penilaian_individu.php?nim=$data_mhsw[nim]&stase=$id_stase\" target=\"_blank\">$data_mhsw[nama]</a><br>(NIM: $data_mhsw[nim])<br>Angk. $angk_mhsw[angkatan]</td>";
				//Periode Kepaniteraan/Stase
				$tgl_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
				$tgl_mulai = tanggal_indo_skt($tgl_stase[tgl_mulai]);
				$tgl_selesai = tanggal_indo_skt($tgl_stase[tgl_selesai]);
				echo "<td align=center>$tgl_mulai<br>s.d.<br>$tgl_selesai</td>";

				//-------------------------------
				//A. Nilai Komprehensip
				//-------------------------------
				//-------------------------------
				//Rekap Nilai Laporan
				//-------------------------------
				//---------------------
				//Rekap Nilai Puskesmas
				//---------------------
				//Nilai Rata Laporan
				$laporan = mysqli_query($con,"SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'");
				$jumlah_laporan = mysqli_num_rows($laporan);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT
				  SUM(`aspek1_ind`), SUM(`aspek2_ind`), SUM(`aspek3_ind`),SUM(`aspek4_ind`), SUM(`aspek5_ind`),
				  SUM(`aspek1_kelp`), SUM(`aspek2_kelp`), SUM(`aspek3_kelp`), SUM(`aspek4_kelp`), SUM(`aspek5_kelp`),
				  SUM(`nilai_rata_ind`), SUM(`nilai_rata_kelp`)
				  FROM `kompre_nilai_laporan` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				if ($jumlah_laporan>0) $rata_aspek1_ind_puskesmas =  number_format($jum_nilai[0]/$jumlah_laporan,2);
				else $rata_aspek1_ind_puskesmas = 0.00;
				if ($jumlah_laporan>0) $rata_aspek2_ind_puskesmas =  number_format($jum_nilai[1]/$jumlah_laporan,2);
				else $rata_aspek2_ind_puskesmas = 0.00;
				if ($jumlah_laporan>0) $rata_aspek3_ind_puskesmas =  number_format($jum_nilai[2]/$jumlah_laporan,2);
				else $rata_aspek3_ind_puskesmas = 0.00;
				if ($jumlah_laporan>0) $rata_aspek4_ind_puskesmas =  number_format($jum_nilai[3]/$jumlah_laporan,2);
				else $rata_aspek4_ind_puskesmas = 0.00;
				if ($jumlah_laporan>0) $rata_aspek5_ind_puskesmas =  number_format($jum_nilai[4]/$jumlah_laporan,2);
				else $rata_aspek5_ind_puskesmas = 0.00;
				if ($jumlah_laporan>0) $total_laporan_ind_puskesmas =  number_format($jum_nilai[10]/$jumlah_laporan,2);
				else $total_laporan_ind_puskesmas = 0.00;

				if ($jumlah_laporan>0) $rata_aspek1_kelp_puskesmas =  number_format($jum_nilai[5]/$jumlah_laporan,2);
				else $rata_aspek1_kelp_puskesmas = 0.00;
				if ($jumlah_laporan>0) $rata_aspek2_kelp_puskesmas =  number_format($jum_nilai[6]/$jumlah_laporan,2);
				else $rata_aspek2_kelp_puskesmas = 0.00;
				if ($jumlah_laporan>0) $rata_aspek3_kelp_puskesmas =  number_format($jum_nilai[7]/$jumlah_laporan,2);
				else $rata_aspek3_kelp_puskesmas = 0.00;
				if ($jumlah_laporan>0) $rata_aspek4_kelp_puskesmas =  number_format($jum_nilai[8]/$jumlah_laporan,2);
				else $rata_aspek4_kelp_puskesmas = 0.00;
				if ($jumlah_laporan>0) $rata_aspek5_kelp_puskesmas =  number_format($jum_nilai[9]/$jumlah_laporan,2);
				else $rata_aspek5_kelp_puskesmas = 0.00;
				if ($jumlah_laporan>0) $total_laporan_kelp_puskesmas =  number_format($jum_nilai[11]/$jumlah_laporan,2);
				else $total_laporan_kelp_puskesmas = 0.00;

				//---------------------
				//Rekap Nilai Rumah Sakit
				//---------------------
				//Nilai Rata laporan
				$laporan = mysqli_query($con,"SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'");
				$jumlah_laporan = mysqli_num_rows($laporan);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT
				  SUM(`aspek1_ind`), SUM(`aspek2_ind`), SUM(`aspek3_ind`),SUM(`aspek4_ind`), SUM(`aspek5_ind`),
				  SUM(`aspek1_kelp`), SUM(`aspek2_kelp`), SUM(`aspek3_kelp`), SUM(`aspek4_kelp`), SUM(`aspek5_kelp`),
				  SUM(`nilai_rata_ind`), SUM(`nilai_rata_kelp`)
				  FROM `kompre_nilai_laporan` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'"));
				if ($jumlah_laporan>0) $rata_aspek1_ind_rumkit =  number_format($jum_nilai[0]/$jumlah_laporan,2);
				else $rata_aspek1_ind_rumkit = 0.00;
				if ($jumlah_laporan>0) $rata_aspek2_ind_rumkit =  number_format($jum_nilai[1]/$jumlah_laporan,2);
				else $rata_aspek2_ind_rumkit = 0.00;
				if ($jumlah_laporan>0) $rata_aspek3_ind_rumkit =  number_format($jum_nilai[2]/$jumlah_laporan,2);
				else $rata_aspek3_ind_rumkit = 0.00;
				if ($jumlah_laporan>0) $rata_aspek4_ind_rumkit =  number_format($jum_nilai[3]/$jumlah_laporan,2);
				else $rata_aspek4_ind_rumkit = 0.00;
				if ($jumlah_laporan>0) $rata_aspek5_ind_rumkit =  number_format($jum_nilai[4]/$jumlah_laporan,2);
				else $rata_aspek5_ind_rumkit = 0.00;
				if ($jumlah_laporan>0) $total_laporan_ind_rumkit =  number_format($jum_nilai[10]/$jumlah_laporan,2);
				else $total_laporan_ind_rumkit = 0.00;

				if ($jumlah_laporan>0) $rata_aspek1_kelp_rumkit =  number_format($jum_nilai[5]/$jumlah_laporan,2);
				else $rata_aspek1_kelp_rumkit = 0.00;
				if ($jumlah_laporan>0) $rata_aspek2_kelp_rumkit =  number_format($jum_nilai[6]/$jumlah_laporan,2);
				else $rata_aspek2_kelp_rumkit = 0.00;
				if ($jumlah_laporan>0) $rata_aspek3_kelp_rumkit =  number_format($jum_nilai[7]/$jumlah_laporan,2);
				else $rata_aspek3_kelp_rumkit = 0.00;
				if ($jumlah_laporan>0) $rata_aspek4_kelp_rumkit =  number_format($jum_nilai[8]/$jumlah_laporan,2);
				else $rata_aspek4_kelp_rumkit = 0.00;
				if ($jumlah_laporan>0) $rata_aspek5_kelp_rumkit =  number_format($jum_nilai[9]/$jumlah_laporan,2);
				else $rata_aspek5_kelp_rumkit = 0.00;
				if ($jumlah_laporan>0) $total_laporan_kelp_rumkit =  number_format($jum_nilai[11]/$jumlah_laporan,2);
				else $total_laporan_kelp_rumkit = 0.00;

				$rata_laporan_puskesmas = number_format(0.5*$total_laporan_ind_puskesmas + 0.5*$total_laporan_kelp_puskesmas,2);
				$rata_laporan_rumkit = number_format(0.5*$total_laporan_ind_rumkit + 0.5*$total_laporan_kelp_rumkit,2);
				$rata_nilai_laporan = number_format(0.5*$rata_laporan_puskesmas+0.5*$rata_laporan_rumkit,2);
				//--------------------

				//-------------------------------
				//Rekap Nilai Sikap
				//---------------------
				//Rekap Nilai Puskesmas
				//---------------------
				//Nilai Rata Sikap
				$sikap = mysqli_query($con,"SELECT * FROM `kompre_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'");
				$jumlah_sikap = mysqli_num_rows($sikap);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`aspek_6`), SUM(`aspek_7`), SUM(`nilai_rata`) FROM `kompre_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				if ($jumlah_sikap>0) $rata_aspek1_puskesmas =  number_format($jum_nilai[0]/$jumlah_sikap,2);
				else $rata_aspek1_puskesmas = 0.00;
				if ($jumlah_sikap>0) $rata_aspek2_puskesmas =  number_format($jum_nilai[1]/$jumlah_sikap,2);
				else $rata_aspek2_puskesmas = 0.00;
				if ($jumlah_sikap>0) $rata_aspek3_puskesmas =  number_format($jum_nilai[2]/$jumlah_sikap,2);
				else $rata_aspek3_puskesmas = 0.00;
				if ($jumlah_sikap>0) $rata_aspek4_puskesmas =  number_format($jum_nilai[3]/$jumlah_sikap,2);
				else $rata_aspek4_puskesmas = 0.00;
				if ($jumlah_sikap>0) $rata_aspek5_puskesmas =  number_format($jum_nilai[4]/$jumlah_sikap,2);
				else $rata_aspek5_puskesmas = 0.00;
				if ($jumlah_sikap>0) $rata_aspek6_puskesmas =  number_format($jum_nilai[5]/$jumlah_sikap,2);
				else $rata_aspek6_puskesmas = 0.00;
				if ($jumlah_sikap>0) $rata_aspek7_puskesmas =  number_format($jum_nilai[6]/$jumlah_sikap,2);
				else $rata_aspek7_puskesmas = 0.00;
				if ($jumlah_sikap>0) $total_sikap_puskesmas =  number_format($jum_nilai[7]/$jumlah_sikap,2);
				else $total_sikap_puskesmas = 0.00;

				//---------------------
				//Rekap Nilai Rumah Sakit
				//---------------------
				//Nilai Rata Sikap
				$sikap = mysqli_query($con,"SELECT * FROM `kompre_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'");
				$jumlah_sikap = mysqli_num_rows($sikap);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`aspek_6`), SUM(`aspek_7`), SUM(`nilai_rata`) FROM `kompre_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'"));
				if ($jumlah_sikap>0) $rata_aspek1_rumkit =  number_format($jum_nilai[0]/$jumlah_sikap,2);
				else $rata_aspek1_rumkit = 0.00;
				if ($jumlah_sikap>0) $rata_aspek2_rumkit =  number_format($jum_nilai[1]/$jumlah_sikap,2);
				else $rata_aspek2_rumkit = 0.00;
				if ($jumlah_sikap>0) $rata_aspek3_rumkit =  number_format($jum_nilai[2]/$jumlah_sikap,2);
				else $rata_aspek3_rumkit = 0.00;
				if ($jumlah_sikap>0) $rata_aspek4_rumkit =  number_format($jum_nilai[3]/$jumlah_sikap,2);
				else $rata_aspek4_rumkit = 0.00;
				if ($jumlah_sikap>0) $rata_aspek5_rumkit =  number_format($jum_nilai[4]/$jumlah_sikap,2);
				else $rata_aspek5_rumkit = 0.00;
				if ($jumlah_sikap>0) $rata_aspek6_rumkit =  number_format($jum_nilai[5]/$jumlah_sikap,2);
				else $rata_aspek6_rumkit = 0.00;
				if ($jumlah_sikap>0) $rata_aspek7_rumkit =  number_format($jum_nilai[6]/$jumlah_sikap,2);
				else $rata_aspek7_rumkit = 0.00;
				if ($jumlah_sikap>0) $total_sikap_rumkit =  number_format($jum_nilai[7]/$jumlah_sikap,2);
				else $total_sikap_rumkit = 0.00;

				$rata_nilai_sikap = number_format(0.5*$total_sikap_puskesmas+0.5*$total_sikap_rumkit,2);
				//---------------------

				//---------------------
				//Rekap Nilai CBD
				//---------------------
				//Nilai Rata CBD
				$daftar_cbd = mysqli_query($con,"SELECT * FROM `kompre_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'");
				$jumlah_cbd = mysqli_num_rows($daftar_cbd);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`nilai_rata`) FROM `kompre_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
				if ($jumlah_cbd>0) $rata_aspek1 =  number_format($jum_nilai[0]/$jumlah_cbd,2);
				else $rata_aspek1 = 0.00;
				if ($jumlah_cbd>0) $rata_aspek2 =  number_format($jum_nilai[1]/$jumlah_cbd,2);
				else $rata_aspek2 = 0.00;
				if ($jumlah_cbd>0) $rata_aspek3 =  number_format($jum_nilai[2]/$jumlah_cbd,2);
				else $rata_aspek3 = 0.00;
				if ($jumlah_cbd>0) $rata_aspek4 =  number_format($jum_nilai[3]/$jumlah_cbd,2);
				else $rata_aspek4 = 0.00;
				if ($jumlah_cbd>0) $total_cbd =  number_format($jum_nilai[4]/$jumlah_cbd,2);
				else $total_cbd = 0.00;
				//---------------------

				//---------------------
				//Rekap Presensi / Kehadiran
				//---------------------
				//---------------------
				//Rekap Nilai Puskesmas
				//---------------------
				//Nilai Rata Presensi
				$presensi = mysqli_query($con,"SELECT * FROM `kompre_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'");
				$jumlah_presensi = mysqli_num_rows($presensi);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_masuk`), SUM(`nilai_absen`), SUM(`nilai_ijin`), SUM(`nilai_total`) FROM `kompre_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				if ($jumlah_presensi>0) $rata_nilai_masuk_puskesmas =  number_format($jum_nilai[0]/$jumlah_presensi,2);
				else $rata_nilai_masuk_puskesmas = 0.00;
				if ($jumlah_presensi>0) $rata_nilai_absen_puskesmas =  number_format($jum_nilai[1]/$jumlah_presensi,2);
				else $rata_nilai_absen_puskesmas = 0.00;
				if ($jumlah_presensi>0) $rata_nilai_ijin_puskesmas =  number_format($jum_nilai[2]/$jumlah_presensi,2);
				else $rata_nilai_ijin_puskesmas = 0.00;
				if ($jumlah_presensi>0) $rata_nilai_total_puskesmas =  number_format($jum_nilai[3]/$jumlah_presensi,2);
				else $rata_nilai_total_puskesmas = 0.00;
				$total_presensi_puskesmas=number_format($rata_nilai_total_puskesmas,2);

				//---------------------
				//Rekap Nilai Rumah Sakit
				//---------------------
				//Nilai Rata Presensi
				$presensi = mysqli_query($con,"SELECT * FROM `kompre_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'");
				$jumlah_presensi = mysqli_num_rows($presensi);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_masuk`), SUM(`nilai_absen`), SUM(`nilai_ijin`), SUM(`nilai_total`) FROM `kompre_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'"));
				if ($jumlah_presensi>0) $rata_nilai_masuk_rumkit =  number_format($jum_nilai[0]/$jumlah_presensi,2);
				else $rata_nilai_masuk_rumkit = 0.00;
				if ($jumlah_presensi>0) $rata_nilai_absen_rumkit =  number_format($jum_nilai[1]/$jumlah_presensi,2);
				else $rata_nilai_absen_rumkit = 0.00;
				if ($jumlah_presensi>0) $rata_nilai_ijin_rumkit =  number_format($jum_nilai[2]/$jumlah_presensi,2);
				else $rata_nilai_ijin_rumkit = 0.00;
				if ($jumlah_presensi>0) $rata_nilai_total_rumkit =  number_format($jum_nilai[3]/$jumlah_presensi,2);
				else $rata_nilai_total_rumkit = 0.00;
				$total_presensi_rumkit=number_format($rata_nilai_total_rumkit,2);

				$rata_nilai_presensi = number_format(0.5*$total_presensi_puskesmas + 0.5*$total_presensi_rumkit,2);
				//---------------------

				echo "<td align=center>$rata_nilai_laporan</td>";
				echo "<td align=center>$rata_nilai_sikap</td>";
				echo "<td align=center>$total_cbd</td>";
				echo "<td align=center>$rata_nilai_presensi</td>";
				$rata_nilai_kompre = number_format((0.2*$rata_nilai_laporan+0.3*$rata_nilai_sikap+0.2*$total_cbd+0.3*$rata_nilai_presensi),2);
				echo "<td align=center>$rata_nilai_kompre</td>";
				//--------------------------------

				//-------------------------------
				//B. Nilai KDK
				//-------------------------------
				//-------------------------------
				//Rekap Nilai DPL / Laporan Kasus
				//-------------------------------
				//Nilai Rata Kasus Ibu
				$kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Ibu' AND `status_approval`='1'");
				$jumlah_kasus = mysqli_num_rows($kasus);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Ibu' AND `status_approval`='1'"));
				if ($jumlah_kasus>0) $rata_aspek1_ibu =  number_format($jum_nilai[0]/$jumlah_kasus,2);
				else $rata_aspek1_ibu = 0.00;
				if ($jumlah_kasus>0) $rata_aspek2_ibu =  number_format($jum_nilai[1]/$jumlah_kasus,2);
				else $rata_aspek2_ibu = 0.00;
				if ($jumlah_kasus>0) $rata_aspek3_ibu =  number_format($jum_nilai[2]/$jumlah_kasus,2);
				else $rata_aspek3_ibu = 0.00;
				if ($jumlah_kasus>0) $rata_aspek4_ibu =  number_format($jum_nilai[3]/$jumlah_kasus,2);
				else $rata_aspek4_ibu = 0.00;
				if ($jumlah_kasus>0) $rata_aspek5_ibu =  number_format($jum_nilai[4]/$jumlah_kasus,2);
				else $rata_aspek5_ibu = 0.00;
				if ($jumlah_kasus>0) $total_kasus_ibu =  number_format($jum_nilai[5]/$jumlah_kasus,2);
				else $total_kasus_ibu = 0.00;

				//Nilai Rata Kasus Bayi/Balita
				$kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Bayi/Balita' AND `status_approval`='1'");
				$jumlah_kasus = mysqli_num_rows($kasus);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Bayi/Balita' AND `status_approval`='1'"));
				if ($jumlah_kasus>0) $rata_aspek1_bayi =  number_format($jum_nilai[0]/$jumlah_kasus,2);
				else $rata_aspek1_bayi = 0.00;
				if ($jumlah_kasus>0) $rata_aspek2_bayi =  number_format($jum_nilai[1]/$jumlah_kasus,2);
				else $rata_aspek2_bayi = 0.00;
				if ($jumlah_kasus>0) $rata_aspek3_bayi =  number_format($jum_nilai[2]/$jumlah_kasus,2);
				else $rata_aspek3_bayi = 0.00;
				if ($jumlah_kasus>0) $rata_aspek4_bayi =  number_format($jum_nilai[3]/$jumlah_kasus,2);
				else $rata_aspek4_bayi = 0.00;
				if ($jumlah_kasus>0) $rata_aspek5_bayi =  number_format($jum_nilai[4]/$jumlah_kasus,2);
				else $rata_aspek5_bayi = 0.00;
				if ($jumlah_kasus>0) $total_kasus_bayi =  number_format($jum_nilai[5]/$jumlah_kasus,2);
				else $total_kasus_bayi = 0.00;

				//Nilai Rata Kasus Remaja
				$kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Remaja' AND `status_approval`='1'");
				$jumlah_kasus = mysqli_num_rows($kasus);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Remaja' AND `status_approval`='1'"));
				if ($jumlah_kasus>0) $rata_aspek1_remaja =  number_format($jum_nilai[0]/$jumlah_kasus,2);
				else $rata_aspek1_remaja = 0.00;
				if ($jumlah_kasus>0) $rata_aspek2_remaja =  number_format($jum_nilai[1]/$jumlah_kasus,2);
				else $rata_aspek2_remaja = 0.00;
				if ($jumlah_kasus>0) $rata_aspek3_remaja =  number_format($jum_nilai[2]/$jumlah_kasus,2);
				else $rata_aspek3_remaja = 0.00;
				if ($jumlah_kasus>0) $rata_aspek4_remaja =  number_format($jum_nilai[3]/$jumlah_kasus,2);
				else $rata_aspek4_remaja = 0.00;
				if ($jumlah_kasus>0) $rata_aspek5_remaja =  number_format($jum_nilai[4]/$jumlah_kasus,2);
				else $rata_aspek5_remaja = 0.00;
				if ($jumlah_kasus>0) $total_kasus_remaja =  number_format($jum_nilai[5]/$jumlah_kasus,2);
				else $total_kasus_remaja = 0.00;

				//Nilai Rata Kasus Dewasa
				$kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Dewasa' AND `status_approval`='1'");
				$jumlah_kasus = mysqli_num_rows($kasus);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Dewasa' AND `status_approval`='1'"));
				if ($jumlah_kasus>0) $rata_aspek1_dewasa =  number_format($jum_nilai[0]/$jumlah_kasus,2);
				else $rata_aspek1_dewasa = 0.00;
				if ($jumlah_kasus>0) $rata_aspek2_dewasa =  number_format($jum_nilai[1]/$jumlah_kasus,2);
				else $rata_aspek2_dewasa = 0.00;
				if ($jumlah_kasus>0) $rata_aspek3_dewasa =  number_format($jum_nilai[2]/$jumlah_kasus,2);
				else $rata_aspek3_dewasa = 0.00;
				if ($jumlah_kasus>0) $rata_aspek4_dewasa =  number_format($jum_nilai[3]/$jumlah_kasus,2);
				else $rata_aspek4_dewasa = 0.00;
				if ($jumlah_kasus>0) $rata_aspek5_dewasa =  number_format($jum_nilai[4]/$jumlah_kasus,2);
				else $rata_aspek5_dewasa = 0.00;
				if ($jumlah_kasus>0) $total_kasus_dewasa =  number_format($jum_nilai[5]/$jumlah_kasus,2);
				else $total_kasus_dewasa = 0.00;

				//Nilai Rata Kasus Lansia
				$kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Lansia' AND `status_approval`='1'");
				$jumlah_kasus = mysqli_num_rows($kasus);
				$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Lansia' AND `status_approval`='1'"));
				if ($jumlah_kasus>0) $rata_aspek1_lansia =  number_format($jum_nilai[0]/$jumlah_kasus,2);
				else $rata_aspek1_lansia = 0.00;
				if ($jumlah_kasus>0) $rata_aspek2_lansia =  number_format($jum_nilai[1]/$jumlah_kasus,2);
				else $rata_aspek2_lansia = 0.00;
				if ($jumlah_kasus>0) $rata_aspek3_lansia =  number_format($jum_nilai[2]/$jumlah_kasus,2);
				else $rata_aspek3_lansia = 0.00;
				if ($jumlah_kasus>0) $rata_aspek4_lansia =  number_format($jum_nilai[3]/$jumlah_kasus,2);
				else $rata_aspek4_lansia = 0.00;
				if ($jumlah_kasus>0) $rata_aspek5_lansia =  number_format($jum_nilai[4]/$jumlah_kasus,2);
				else $rata_aspek5_lansia = 0.00;
				if ($jumlah_kasus>0) $total_kasus_lansia =  number_format($jum_nilai[5]/$jumlah_kasus,2);
				else $total_kasus_lansia = 0.00;

				$rata_nilai_kasus = ($total_kasus_ibu+$total_kasus_bayi+$total_kasus_remaja+$total_kasus_dewasa+$total_kasus_lansia)/5;
				$rata_nilai_dpl = number_format($rata_nilai_kasus,2);

				//-----------------
				//Rekap Nilai Klinik
				//-----------------
				//Rekap Nilai Sikap
				$jumlah_sikap_klinik = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
				$nilai_sikap_klinik = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
				if ($jumlah_sikap_klinik>0) $rata_nilai_sikap_klinik = number_format($nilai_sikap_klinik[0]/$jumlah_sikap_klinik,2);
				else $rata_nilai_sikap_klinik = 0.00;
				//Rekap Nilai DOPS
				$jumlah_dops_klinik = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
				$nilai_dops_klinik = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
				if ($jumlah_dops_klinik>0) $rata_nilai_dops_klinik = number_format($nilai_dops_klinik[0]/$jumlah_dops_klinik,2);
				else $rata_nilai_dops_klinik = 0.00;
				//Rekap Nilai Mini-CEX
				$jumlah_minicex_klinik = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
				$nilai_minicex_klinik = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
				if ($jumlah_minicex_klinik>0) $rata_nilai_minicex_klinik = number_format($nilai_minicex_klinik[0]/$jumlah_minicex_klinik,2);
				else $rata_nilai_minicex_klinik = 0.00;
				//Rekap Nilai Presensi Klinik
				$jumlah_presensi_klinik = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
				$nilai_presensi_klinik = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_total`) FROM `kdk_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
				if ($jumlah_presensi_klinik>0) $rata_nilai_presensi_klinik = number_format($nilai_presensi_klinik[0]/$jumlah_presensi_klinik,2);
				else $rata_nilai_presensi_klinik = 0.00;

				$rata_nilai_klinik = number_format((2*$rata_nilai_sikap_klinik + 1*$rata_nilai_dops_klinik + 2*$rata_nilai_minicex_klinik+2*$rata_nilai_presensi_klinik)/7,2);

				//-----------------
				//Rekap Nilai Puskesmas
				//-----------------
				//Rekap Nilai Sikap
				$jumlah_sikap_puskesmas = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				$nilai_sikap_puskesmas = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				if ($jumlah_sikap_puskesmas>0) $rata_nilai_sikap_puskesmas = number_format($nilai_sikap_puskesmas[0]/$jumlah_sikap_puskesmas,2);
				else $rata_nilai_sikap_puskesmas = 0.00;
				//Rekap Nilai DOPS
				$jumlah_dops_puskesmas = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				$nilai_dops_puskesmas = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				if ($jumlah_dops_puskesmas>0) $rata_nilai_dops_puskesmas = number_format($nilai_dops_puskesmas[0]/$jumlah_dops_puskesmas,2);
				else $rata_nilai_dops_puskesmas = 0.00;
				//Rekap Nilai Mini-CEX
				$jumlah_minicex_puskesmas = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				$nilai_minicex_puskesmas = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				if ($jumlah_minicex_puskesmas>0) $rata_nilai_minicex_puskesmas = number_format($nilai_minicex_puskesmas[0]/$jumlah_minicex_puskesmas,2);
				else $rata_nilai_minicex_puskesmas = 0.00;
				//Rekap Nilai Presensi Puskesmas
				$jumlah_presensi_puskesmas = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				$nilai_presensi_puskesmas = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_total`) FROM `kdk_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
				if ($jumlah_presensi_puskesmas>0) $rata_nilai_presensi_puskesmas = number_format($nilai_presensi_puskesmas[0]/$jumlah_presensi_puskesmas,2);
				else $rata_nilai_presensi_puskesmas = 0.00;

				$rata_nilai_sikap = number_format(($rata_nilai_sikap_klinik+$rata_nilai_sikap_puskesmas)/2,2);
				$rata_nilai_dops = number_format(($rata_nilai_dops_klinik+$rata_nilai_dops_puskesmas)/2,2);
				$rata_nilai_minicex = number_format(($rata_nilai_minicex_klinik+$rata_nilai_minicex_puskesmas)/2,2);
				$rata_nilai_presensi = number_format(($rata_nilai_presensi_klinik+$rata_nilai_presensi_puskesmas)/2,2);

				$rata_nilai_klinik =  number_format((2*$rata_nilai_sikap_klinik + 1*$rata_nilai_dops_klinik + 2*$rata_nilai_minicex_klinik+2*$rata_nilai_presensi_klinik)/7,2);
				$rata_nilai_puskesmas =  number_format((2*$rata_nilai_sikap_puskesmas + 1*$rata_nilai_dops_puskesmas + 2*$rata_nilai_minicex_puskesmas+2*$rata_nilai_presensi_puskesmas)/7,2);
				$rata_nilai_kdk = number_format((0.3*$rata_nilai_dpl+0.35*$rata_nilai_puskesmas+0.35*$rata_nilai_klinik),2);

				echo "<td align=center>$rata_nilai_dpl</td>";
				echo "<td align=center>$rata_nilai_sikap</td>";
				echo "<td align=center>$rata_nilai_dops</td>";
				echo "<td align=center>$rata_nilai_minicex</td>";
				echo "<td align=center>$rata_nilai_presensi</td>";
				echo "<td align=center>$rata_nilai_kdk</td>";
				//--------------------------------

				//Nilai Rata Kompre dan KDK
				$nilai_rata_kompre_kdk = number_format(($rata_nilai_kompre+$rata_nilai_kdk)/2,2);
				if ($nilai_rata_kompre_kdk<=100 AND $nilai_rata_kompre_kdk>=80) $grade = "A";
				if ($nilai_rata_kompre_kdk<80 AND $nilai_rata_kompre_kdk>=70) $grade = "B";
				if ($nilai_rata_kompre_kdk<70 AND $nilai_rata_kompre_kdk>=60) $grade = "C";
				if ($nilai_rata_kompre_kdk<60 AND $nilai_rata_kompre_kdk>=50) $grade = "D";
				if ($nilai_rata_kompre_kdk<50) $grade = "E";

				echo "<td align=center>$nilai_rata_kompre_kdk</td>";
				echo "<td align=center>$grade</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
			echo "</table>";

			echo "<br><br><center><form method=POST action=\"$_SERVER[PHP_SELF]\">
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\"><img width=\"40\" height=\"40\" src=excel-icon.png title=\"Export ke Excel\"></a><br>
				<a href=\"export_nilai_bag.php?stase=$id_stase&grup=$grup_filter&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\">Export to Excel</a><br>
				</form>";
		}

		echo "<br><a href=\"#top\"><i>Goto top</i></a><br><br>";



		$delete_dummy_mhsw = mysqli_query($con,"DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");

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
