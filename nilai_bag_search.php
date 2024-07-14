<HTML>
	<head>
		<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="select2/dist/css/select2.css"/>
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
	  if ($_COOKIE['level']==2) {include "menu2.php";}
	  if ($_COOKIE['level']==3) {include "menu3.php";}
		echo "<div class=\"text_header\">CETAK NILAI BAGIAN / KEPANITERAAN (STASE)</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">CETAK NILAI BAGIAN / KEPANITERAAN (STASE)</font></h4><br>";

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		echo "<table border=\"0\">";
		echo "<tr class=\"ganjil\">";
		echo "<td class=\"td_mid\">Kepaniteraan (stase)</td>";
		echo "<td class=\"td_mid\">";
		echo "<select class=\"select_artwide\" name=\"bagian\" id=\"bagian\" required>";
		$data_bagian = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
		echo "<option value=\"\">< Pilihan Bagian / Kepaniteraan (Stase) ></option>";
		while ($bagian=mysqli_fetch_array($data_bagian))
		echo "<option value=\"$bagian[id]\">$bagian[kepaniteraan]</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
		echo "<td class=\"td_mid\">Nama/NIM Mahasiswa</td>";
		echo "<td class=\"td_mid\">";
		echo "<select class=\"select_artwide\" name=\"mhsw\" id=\"mhsw\" required>";
		$mhsw = mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
		echo "<option value=\"\">< Pilihan Mahasiswa ></option>";
		while ($data1=mysqli_fetch_array($mhsw))
		echo "<option value=\"$data1[nim]\">$data1[nama] ($data1[nim])</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "<br><input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\">";
		echo "</form>";

		if ($_POST[submit]=="SUBMIT")
		{

			echo "<table class=\"tabel_normal\" style=\"width:75%\">";
			echo "<tr>";
			echo "<td>";
			$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$_POST[mhsw]'"));
			echo "Nama Mahasiswa : $data_mhsw[nama] (NIM: $_POST[mhsw])";
			echo "</td>";
			echo "</tr>";

			if ($_POST[bagian]=="M091")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Penyakit Dalam:</b><br>";
				echo "<a href=\"bag_ipd/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Mini-Cex</a><br>";
				echo "<a href=\"bag_ipd/cetak_kasus.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Penyajian Kasus Besar</a><br>";
				echo "<a href=\"bag_ipd/cetak_ujian.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Akhir</a><br>";
				echo "<a href=\"bag_ipd/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test (Ujian MCQ)</a><br>";
				echo "<a href=\"bag_ipd/cetak_nilai_ipd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Penyakit Dalam</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M092")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Neurologi:</b><br>";
				echo "<a href=\"bag_neuro/cetak_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kasus CBD</a><br>";
				echo "<a href=\"bag_neuro/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
				echo "<a href=\"bag_neuro/cetak_spv.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian SPV</a><br>";
				echo "<a href=\"bag_neuro/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian MINI-CEX</a><br>";
				echo "<a href=\"bag_neuro/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test</a><br>";
				echo "<a href=\"bag_neuro/cetak_nilai_neuro.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Neurologi</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M093")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan Jiwa:</b><br>";
				echo "<a href=\"bag_psikiatri/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
				echo "<a href=\"bag_psikiatri/cetak_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai CBD</a><br>";
				echo "<a href=\"bag_psikiatri/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian MINI-CEX</a><br>";
				echo "<a href=\"bag_psikiatri/cetak_osce.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian OSCE</a><br>";
				echo "<a href=\"bag_psikiatri/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai-Nilai Test</a><br>";
				echo "<a href=\"bag_psikiatri/cetak_nilai_psikiatri.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M094")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) IKFR:</b><br>";
				echo "<a href=\"bag_ikfr/cetak_kasus.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Diskusi Kasus</a><br>";
				echo "<a href=\"bag_ikfr/cetak_minicex.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Mini-Cex</a><br>";
				echo "<a href=\"bag_ikfr/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Pre-Test, Post-Test, Sikap/Perilaku, dan Ujian OSCE</a><br>";
				echo "<a href=\"bag_ikfr/cetak_nilai_ikfr.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) IKFR</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M095")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) IKM-KP:</b><br>";
				echo "<a href=\"bag_ikmkp/cetak_pkbi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kegiatan di PKBI</a><br>";
				echo "<a href=\"bag_ikmkp/cetak_p2ukm.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kegiatan di P2UKM Mlonggo</a><br>";
				echo "<a href=\"bag_ikmkp/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test</a><br>";
				echo "<a href=\"bag_ikmkp/cetak_komprehensip.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Komprehensip</a><br>";
				echo "<a href=\"bag_ikmkp/cetak_nilai_ikmkp.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) IKM-KP</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M096")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Jantung dan Pembuluh Darah:</b><br>";
				echo "<i>(Masih dalam penyusunan !!)</i>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M101")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Bedah:</b><br>";
				echo "<a href=\"bag_bedah/cetak_nilai_mentor.php?cbd=1&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Mentoring di RS Jejaring</a><br>";
				echo "<a href=\"bag_bedah/cetak_nilai_test.php?cbd=2&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Pre-Test, Post-Test, Skill Lab dan OSCE</a><br>";
				echo "<a href=\"bag_bedah/cetak_nilai_bedah.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Bedah</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M102")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Anestesi dan Intensive Care:</b><br>";
				echo "<a href=\"bag_anestesi/cetak_dops.php?cbd=1&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai DOPS</a><br>";
				echo "<a href=\"bag_anestesi/cetak_osce.php?cbd=2&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian OSCE</a><br>";
				echo "<a href=\"bag_anestesi/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai-Nilai Test dan Sikap/Perilaku</a><br>";
				echo "<a href=\"bag_anestesi/cetak_nilai_anestesi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Anestesi dan Intensive Care</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M103")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Radiologi:</b><br>";
				echo "<a href=\"bag_radiologi/cetak_cbd.php?cbd=1&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kasus CBD-Radiodiagnostik</a><br>";
				echo "<a href=\"bag_radiologi/cetak_cbd.php?cbd=2&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kasus CBD-Radioterapi</a><br>";
				echo "<a href=\"bag_radiologi/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
				echo "<a href=\"bag_radiologi/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai-Nilai Test dan Sikap/Perilaku</a><br>";
				echo "<a href=\"bag_radiologi/cetak_nilai_radiologi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Radiologi</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M104")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan Mata:</b><br>";
				echo "<a href=\"bag_mata/cetak_presentasi.php?cbd=1&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Presentasi Kasus Besar</a><br>";
				echo "<a href=\"bag_mata/cetak_jurnal.php?cbd=2&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Presentasi Journal Reading</a><br>";
				echo "<a href=\"bag_mata/cetak_nilai_penyanggah.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Penyanggah</a><br>";
				echo "<a href=\"bag_mata/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Mini-Cex</a><br>";
				echo "<a href=\"bag_mata/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test-Test (MCQ dan OSCE/OSCA)</a><br>";
				echo "<a href=\"bag_mata/cetak_nilai_mata.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Mata</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M105")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan THT-KL:</b><br>";
				echo "<a href=\"bag_thtkl/cetak_presentasi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Presentasi Kasus</a><br>";
				echo "<a href=\"bag_thtkl/cetak_responsi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Responsi Kasus Kecil</a><br>";
				echo "<a href=\"bag_thtkl/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
				echo "<a href=\"bag_thtkl/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test</a><br>";
				echo "<a href=\"bag_thtkl/cetak_osce.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian OSCE</a><br>";
				echo "<a href=\"bag_thtkl/cetak_nilai_thtkl.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan THT-KL</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M106")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) IKGM:</b><br>";
				echo "<a href=\"bag_ikgm/cetak_kasus.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Laporan Kasus</a><br>";
				echo "<a href=\"bag_ikgm/cetak_jurnal.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
				echo "<a href=\"bag_ikgm/cetak_responsi.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Responsi Kasus Kecil</a><br>";
				echo "<a href=\"bag_ikgm/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian OSCA dan Nilai Sikap dan Perilaku</a><br>";
				echo "<a href=\"bag_ikgm/cetak_nilai_ikgm.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) IKGM</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M111")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan:</b><br>";
				echo "<a href=\"bag_obsgyn/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai MINI-CEX (Mini Clinical Examination)</a><br>";
				echo "<a href=\"bag_obsgyn/cetak_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Case-Based Discussion (CBD)</a><br>";
				echo "<a href=\"bag_obsgyn/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
				echo "<a href=\"bag_obsgyn/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test (DOPS/OSCE, MCQ, dan MINI-PAT)</a><br>";
				echo "<a href=\"bag_obsgyn/cetak_nilai_obsgyn.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M112")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal:</b><br>";
				echo "<a href=\"bag_forensik/cetak_visum.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Visum Bayangan</a><br>";
				echo "<a href=\"bag_forensik/cetak_jaga.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Kegiatan Jaga</a><br>";
				echo "<a href=\"bag_forensik/cetak_substase.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Substase</a><br>";
				echo "<a href=\"bag_forensik/cetak_referat.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Referat</a><br>";
				echo "<a href=\"bag_forensik/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Pre-Test, Nilai Sikap dan Perilaku, dan Nilai Kompre Stasi I-V</a><br>";
				echo "<a href=\"bag_forensik/cetak_nilai_forensik.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M113")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan Anak:</b><br>";
				echo "<a href=\"bag_ika/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai MINI-CEX (Mini Clinical Examination)</a><br>";
				echo "<a href=\"bag_ika/cetak_dops.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Direct Observation of Procedural Skill (DOPS)</a><br>";
				echo "<a href=\"bag_ika/cetak_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Case-Based Discussion (CBD) - Kasus Poliklinik</a><br>";
				echo "<a href=\"bag_ika/cetak_kasus.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Penyajian Kasus Besar</a><br>";
				echo "<a href=\"bag_ika/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Penyajian Journal Reading</a><br>";
				echo "<a href=\"bag_ika/cetak_minipat.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Mini Peer Assesment Tool (Mini-PAT)</a><br>";
				echo "<a href=\"bag_ika/cetak_ujian.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Akhir Kepaniteraan</a><br>";
				echo "<a href=\"bag_ika/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian/Test (Pre-Test, Post-Test, dan Ujian OSCE)</a><br>";
				echo "<a href=\"bag_ika/cetak_nilai_ika.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Anak</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M114")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin:</b><br>";
				echo "<a href=\"bag_kulit/cetak_nilai_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Ujian Kasus</a><br>";
				echo "<a href=\"bag_kulit/cetak_nilai_test.php?&nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai-Nilai OSCE, Ujian Teori, dan Perilaku</a><br>";
				echo "<a href=\"bag_kulit/cetak_nilai_kulit.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin</a>";
				echo "</td>";
				echo "</tr>";
			}

			if ($_POST[bagian]=="M121")
			{
				echo "<tr>";
				echo "<td>";
				echo "<font style=\"font-size:0.75em\"><i>klik link di bawah ini untuk keperluan cetak</i></font>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kepaniteraan Komprehensip:</b><br>";
				echo "<a href=\"bag_kompre/cetak_laporan.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Laporan</a><br>";
				echo "<a href=\"bag_kompre/cetak_nilai_sikap.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Sikap/Perilaku</a><br>";
				echo "<a href=\"bag_kompre/cetak_nilai_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Case Based Discussion (CBD)</a><br>";
				echo "<a href=\"bag_kompre/cetak_nilai_presensi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Presensi / Kehadiran </a><br>";
				echo "<a href=\"bag_kompre/cetak_nilai_kompre.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Akhir Kepaniteraan Komprehensip</a>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>";
				echo "<b>Penilaian Kedokteran Keluarga:</b><br>";
				echo "<a href=\"bag_kdk/cetak_laporan_kasus.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Laporan Kasus (Nilai Dosen Pembimbing Lapangan)</a><br>";
				echo "<a href=\"bag_kdk/cetak_nilai_sikap.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Sikap di Puskesmas dan Klinik Pratama</a><br>";
				echo "<a href=\"bag_kdk/cetak_nilai_dops.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai DOPS di Puskesmas dan Klinik Pratama</a><br>";
				echo "<a href=\"bag_kdk/cetak_nilai_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Mini-CEX di Puskesmas dan Klinik Pratama</a><br>";
				echo "<a href=\"bag_kdk/cetak_nilai_presensi.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Presensi / Kehadiran di Puskesmas dan Klinik Pratama</a><br>";
				echo "<a href=\"bag_kdk/cetak_nilai_kdk.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Akhir Kepaniteraan Kedokteran Keluarga</a>";
				echo "</td>";
				echo "</tr>";
			}
			echo "</table>";

		}

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
<script src="select2/dist/js/select2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#bagian").select2({
			 	placeholder: "< Pilihan Bagian / Kepaniteraan (Stase) >",
	      allowClear: true
		 	});
		$("#mhsw").select2({
			 	placeholder: "< Pilihan Mahasiswa >",
	      allowClear: true
			});

	});
</script>

<!--</body></html>-->
</BODY>
</HTML>
