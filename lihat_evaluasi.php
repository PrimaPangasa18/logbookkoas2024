<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3 OR $_COOKIE['level']==5))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
		if ($_COOKIE['level']==2) {include "menu2.php";}
		if ($_COOKIE['level']==3) {include "menu3.php";}
		if ($_COOKIE['level']==5) {include "menu5.php";}
		echo "<div class=\"text_header\">EVALUASI KEPANITERAAN (STASE)</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    	<legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">HASIL EVALUASI  KEPANITERAAN (STASE)</font></h4>";


		$id_stase = $_GET['id'];
		$nim_mhsw = $_GET['nim'];
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"nim\" value=\"$nim_mhsw\" />";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\" />";
		echo "<input type=\"hidden\" name=\"menu\" value=\"$_GET[menu]\" />";

		$kepaniteraan=mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_evaluasi =mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw' AND `stase`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw'"));
		echo "<table style=\"width:60%;background-color:rgba(255,249,222,0.5)\">";
		echo "<tr>";
		echo "<td align=center><img src=\"images/evaluasi_header.jpg\" style=\"width:100%;height:auto\"></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align=center><font style=\"font-family:Arial;font-size:1.25em\">Evaluasi Pelaksanaan Kepaniteraan (Stase)<br>$kepaniteraan[kepaniteraan]</font></td>";
		echo "</tr>";
		if ($_COOKIE['level']!=5)
		{
			echo "<tr>";
			echo "<td align=center><br><font style=\"font-size:1em\">Nama Mahasiswa [NIM]<br>$data_mhsw[nama] [$nim_mhsw]<br><br></font></td>";
			echo "</tr>";
		}
		echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>1. SISTEM PEMBELAJARAN DALAM STASE</b></font></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Penilaian Materi Pembelajaran</b></font></td>";
		echo "</tr>";

		//1. Pembelajaran
		$eval_pemb = mysqli_query($con,"SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Pembelajaran' ORDER BY `id`");
		$no = 1;
		while ($data_pemb=mysqli_fetch_array($eval_pemb))
		{
			echo "<tr>";
			echo "<td>";
				echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
				echo "<tr>";
				$radio_name = "input_11".$no;
				echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\"><b>$data_pemb[pertanyaan]</b><br><i>Jawaban: ";
				if ($data_evaluasi[$radio_name]=='1') echo "Sangat tidak setuju";
				if ($data_evaluasi[$radio_name]=='2') echo "Tidak setuju";
				if ($data_evaluasi[$radio_name]=='3') echo "Setuju";
				if ($data_evaluasi[$radio_name]=='4') echo "Sangat setuju";
				echo "</i></font></td>";
				echo "</tr>";
				echo "</table>";
			echo "</td>";
			echo "</tr>";
			$no++;
		}
		//Refleksi diri
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Tuliskan refleksi diri anda setelah menjalankan stase kepaniteraan ini: </b></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>";
			echo "<table style=\"background-color:white;border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr>";
			echo "<td><font style=\"font-family:Tahoma;font-size:0.85em\"><i>$data_evaluasi[refleksi]</i></font></td>";
			echo "</tr>";
			echo "</table>";
		echo "</td>";
		echo "</tr>";
		//Komentar
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Silakan tulis dalam kolom di bawah ini komentar, usul, saran, atau kritik dalam bahasa yang santun: </b></font></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>";
			echo "<table style=\"background-color:white;border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr>";
			echo "<td><font style=\"font-family:Tahoma;font-size:0.85em\"><i>$data_evaluasi[komentar]</i></font></td>";
			echo "</tr>";
			echo "</table>";
		echo "</td>";
		echo "</tr>";
		//Pencapaian
		echo "<tr>";
			echo "<td>";
				echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
				echo "<tr>";
				$radio_name = "input_12";
				echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\"><b>Menurut Anda, seberapa banyak pencapaian kompetensi level 3A, 3B, 4A yang Anda capat dalam kepaniteraan Bagian ini (termasuk dengan stase luar kepaniteraan ini)?</b><br>";
				echo "<i>Jawaban: ";
				if ($data_evaluasi[$radio_name]=='1') echo "< 25%";
				if ($data_evaluasi[$radio_name]=='2') echo "25-50%";
				if ($data_evaluasi[$radio_name]=='3') echo "50-75%";
				if ($data_evaluasi[$radio_name]=='4') echo "> 75%";
				echo "</i></font></td>";
				echo "</tr>";
				echo "</table>";
			echo "</td>";
		echo "</tr>";
		//Kepuasan
		echo "<tr>";
			echo "<td>";
				echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
				echo "<tr>";
				$radio_name = "input_13";
				echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\"><b>Seberapa besar kepuasan Anda terhadap keseluruhan program stase di Bagian ini (termasuk program stase luar)?</b><br>";
				echo "<i>Jawaban: ";
				if ($data_evaluasi[$radio_name]=='1') echo "Sangat tidak puas";
				if ($data_evaluasi[$radio_name]=='2') echo "Tidak puas";
				if ($data_evaluasi[$radio_name]=='3') echo "Puas";
				if ($data_evaluasi[$radio_name]=='4') echo "Sangat puas";
				echo "</i></font></td>";
				echo "</tr>";
				echo "</table>";
			echo "</td>";
		echo "</tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		//2. Dosen
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>2. DOSEN</b><br>";
		echo "Berikan evaluasi Anda bagi minimal 3 (tiga)  dosen  dalam kepaniteraan ini yang melakukan minimal 2 (dua) kali tatap muka dengan Anda. Bila tidak ada, pilih 3 dosen dengan kuantitas dan/atau kualitas pertemuan paling banyak.";
		echo "<br>Aspek yang tidak bisa dinilai (misalnya, karena mahasiswa tidak tahu pasti) tidak perlu dinilai (kolom dikosongkan).</font></td>";
		echo "</tr>";
		for ($x = 1; $x <= 3; $x++)
		{
			echo "<tr><td>&nbsp;</td></tr>";
			//Pilihan Dosen x
			echo "<tr>";
			$dosenx = "dosen".$x;
			$dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_evaluasi[$dosenx]'"));
			echo "<td><font style=\"font-size:0.85em\"><b>2.$x. Nama Dosen:</b> <i>$dosen[nama], $dosen[gelar]</i></font>";
			echo "</td>";
			echo "</tr>";
			//Jumlah Jam Tatap Muka Dosen x
			echo "<tr>";
			$tatap_mukax = "tatap_muka".$x;
			echo "<td><font style=\"font-size:0.85em\"><b>Jumlah jam tatap muka: </b><i>$data_evaluasi[$tatap_mukax] jam</i></font>";
			echo "</td>";
			echo "</tr>";
			//Dosen x Umum
			echo "<tr>";
			echo "<td><font style=\"font-size:0.85em\"><b>A. Umum </b></font></td>";
			echo "</tr>";
			$dosen_umum = mysqli_query($con,"SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_umum' ORDER BY `id`");
			$no = 1;
			while ($data_umum=mysqli_fetch_array($dosen_umum))
			{
				echo "<tr>";
				echo "<td>";
					echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
					echo "<tr>";
					$radio_name = "input_2".$x."A".$no;
					echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\"><b>$data_umum[pertanyaan]</b><br><i>Jawaban: ";
					if ($data_evaluasi[$radio_name]=='1') echo "Sangat tidak setuju";
					if ($data_evaluasi[$radio_name]=='2') echo "Tidak setuju";
					if ($data_evaluasi[$radio_name]=='3') echo "Setuju";
					if ($data_evaluasi[$radio_name]=='4') echo "Sangat setuju";
					echo "</i></font></td>";
					echo "</tr>";
					echo "</table>";
				echo "</td>";
				echo "</tr>";
				$no++;
			}
			//Dosen x Pengajar
			echo "<tr>";
			echo "<td><font style=\"font-size:0.85em\"><b>B. Dosen sebagai pengajar</b></font></td>";
			echo "</tr>";
			$dosen_pengajar = mysqli_query($con,"SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_pengajar' ORDER BY `id`");
			$no = 1;
			while ($data_pengajar=mysqli_fetch_array($dosen_pengajar))
			{
				echo "<tr>";
				echo "<td>";
					echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
					echo "<tr>";
					$radio_name = "input_2".$x."B".$no;
					echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\"><b>$data_pengajar[pertanyaan]</b><br><i>Jawaban: ";
					if ($data_evaluasi[$radio_name]=='1') echo "Sangat tidak setuju";
					if ($data_evaluasi[$radio_name]=='2') echo "Tidak setuju";
					if ($data_evaluasi[$radio_name]=='3') echo "Setuju";
					if ($data_evaluasi[$radio_name]=='4') echo "Sangat setuju";
					echo "</i></font></td>";
					echo "</tr>";
					echo "</table>";
				echo "</td>";
				echo "</tr>";
				$no++;
			}
			//Dosen x Penguji
			echo "<tr>";
			echo "<td><font style=\"font-size:0.85em\"><b>C. Dosen sebagai penguji</b></font></td>";
			echo "</tr>";
			$dosen_penguji = mysqli_query($con,"SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_penguji' ORDER BY `id`");
			$no = 1;
			while ($data_penguji=mysqli_fetch_array($dosen_penguji))
			{
				echo "<tr>";
				echo "<td>";
					echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
					echo "<tr>";
					$radio_name = "input_2".$x."C".$no;
					echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\"><b>$data_penguji[pertanyaan]</b><br><i>Jawaban: ";
					if ($data_evaluasi[$radio_name]=='1') echo "Sangat tidak setuju";
					if ($data_evaluasi[$radio_name]=='2') echo "Tidak setuju";
					if ($data_evaluasi[$radio_name]=='3') echo "Setuju";
					if ($data_evaluasi[$radio_name]=='4') echo "Sangat setuju";
					echo "</i></font></td>";
					echo "</tr>";
					echo "</table>";
				echo "</td>";
				echo "</tr>";
				$no++;
			}
			//Komentar dosen x
			echo "<tr>";
			echo "<td><font style=\"font-size:0.85em\"><b>Silakan mengisi pada kolom di bawah ini dengan bahasa yang santun komentar, usul, saran atau masukan: </b></font></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>";
				echo "<table style=\"background-color:white;border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
				echo "<tr>";
				$komentar_dosenx = "komentar_dosen".$x;
				echo "<td><font style=\"font-family:Tahoma;font-size:0.85em\"><i>$data_evaluasi[$komentar_dosenx]</i></font></td>";
				echo "</tr>";
				echo "</table>";
			echo "</td>";
			echo "</tr>";
		}
		echo "<tr><td>&nbsp;</td></tr>";
		//Evaluasi stase luar
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>EVALUASI STASE LUAR</b><br>
					Isilah kuesioner berikut apabila dalam stase yang Anda evaluasi ini ada stase luar.</font></td>";
		echo "</tr>";
		//Lokasi stase luar
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Lokasi stase luar:</b> <i>$data_evaluasi[lokasi_luar]</i></font>";
		echo "</td>";
		echo "</tr>";
		//Lama stase luar
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Lama stase (minggu):</b> <i>$data_evaluasi[lama_luar] minggu</i></font>";
		echo "</td>";
		echo "</tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		//A. Penilaian Materi Pembelajaran
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>A. Penilaian Materi Pembelajaran </b></font></td>";
		echo "</tr>";
		echo "<tr>";
		$materi = mysqli_query($con,"SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Stase_luar' ORDER BY `id`");
		$no = 1;
		while ($data_materi=mysqli_fetch_array($materi))
		{
			echo "<tr>";
			echo "<td>";
				echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
				echo "<tr>";
				$radio_name = "input_3A".$no;
				echo "<td style=\"width:52%\"><font style=\"font-size:0.85em\"><b>$data_materi[pertanyaan]</b><br><i>Jawaban: ";
				if ($data_evaluasi[$radio_name]=='1') echo "Sangat tidak setuju";
				if ($data_evaluasi[$radio_name]=='2') echo "Tidak setuju";
				if ($data_evaluasi[$radio_name]=='3') echo "Setuju";
				if ($data_evaluasi[$radio_name]=='4') echo "Sangat setuju";
				echo "</i></font></td>";
				echo "</tr>";
				echo "</table>";
			echo "</td>";
			echo "</tr>";
			$no++;
		}
		echo "<tr><td>&nbsp;</td></tr>";
		//Komentar disukai stase luar
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Tuliskan hal-hal yang Anda sukai dari stase luar ini: </b></font></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>";
			echo "<table style=\"background-color:white;border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr>";
			echo "<td><font style=\"font-family:Tahoma;font-size:0.85em\"><i>$data_evaluasi[like_luar]</i></font></td>";
			echo "</tr>";
			echo "</table>";
		echo "</td>";
		echo "</tr>";
		//Komentar tidak disukai stase luar
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Tuliskan hal-hal yang tidak ada Anda sukai/perlu diperbaiki dari stase luar ini: </b></font></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>";
			echo "<table style=\"background-color:white;border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr>";
			echo "<td><font style=\"font-family:Tahoma;font-size:0.85em\"><i>$data_evaluasi[unlike_luar]</i></font></td>";
			echo "</tr>";
			echo "</table>";
		echo "</td>";
		echo "</tr>";
		//Catatan
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\">
			<i>Catatan:<br>
			Area kompetensi 1 : Profesionalitas yang luhur<br>
			Area kompetensi 2 : Mawas diri dan pengembangan diri<br>
			Area kompetensi 3 : Komunikasi efektif<br>
			Area kompetensi 4 : Pengelolaan informasi</i>";
		echo "</font></td>";
		echo "</tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		echo "</table>";


		echo "<br><br><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			if ($_COOKIE[level]!='5')
			{
				if ($_POST[menu]=="rekap")
				{
					echo "
					<script>
						window.location.href=\"rekap_indstase_admin.php?id=\"+\"$_POST[id_stase]\"+\"&nim=\"+\"$_POST[nim]\";
					</script>
					";
				}
				if ($_POST[menu]=="rotasi")
				{
					echo "
					<script>
						window.location.href=\"rotasi_internal.php?user_name=\"+\"$_POST[nim]\";
					</script>
					";
				}
			}
			else
			{
				echo "
				<script>
					window.location.href=\"rekap_indstase.php?id=\"+\"$_POST[id_stase]\";
				</script>
				";
			}

		}

	}
		else
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
?>

<script src="jquery.min.js"></script>
<script src="select2/dist/js/select2.js"></script>

<script>
$(document).ready(function() {
	$("#dosen1").select2({
		placeholder: "< Pilihan Dosen >",
		allowClear: true
	});
	$("#dosen2").select2({
		placeholder: "< Pilihan Dosen >",
		allowClear: true
	});
	$("#dosen3").select2({
		placeholder: "< Pilihan Dosen >",
		allowClear: true
	});
	$("#lama_luar").select2({
		placeholder: "< Pilihan Minggu >",
		allowClear: true
	});
});
</script>

<!--</body></html>-->
</BODY>
</HTML>
