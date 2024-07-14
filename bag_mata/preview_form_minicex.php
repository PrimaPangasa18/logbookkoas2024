<HTML>
<head>
	<meta charset="UTF-8">
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN MATA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI UJIAN MINI-CEX<p>Kepaniteraan (Stase) Ilmu Kesehatan Mata</font></h4>";

		$id_stase = "M104";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `mata_nilai_minicex` WHERE `id`='$id'"));

		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Nama mahasiswa koas
		echo "<tr>";
			echo "<td>Nama Mahasiswa</td>";
			echo "<td>$data_mhsw[nama]</td>";
		echo "</tr>";
		//NIM
		echo "<tr>";
			echo "<td>NIM</td>";
			echo "<td>$data_mhsw[nim]</td>";
		echo "</tr>";
		//Periode Stase
		echo "<tr>";
			$mulai_stase = tanggal_indo($datastase_mhsw[tgl_mulai]);
			$selesai_stase = tanggal_indo($datastase_mhsw[tgl_selesai]);
			echo "<td>Periode Kepaniteraan (Stase)</td>";
			echo "<td>$mulai_stase s.d. $selesai_stase</td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			echo "<td>Tanggal Ujian</td>";
			$tanggal_ujian = tanggal_indo($data_minicex[tgl_ujian]);
			echo "<td>$tanggal_ujian</td>";
		echo "</tr>";
		//Waktu Observasi
		echo "<tr>";
			echo "<td>Waktu Observasi</td>";
			echo "<td>$data_minicex[waktu_obs]&nbsp;&nbsp;menit</td>";
		echo "</tr>";
		//Waktu Umpan Balik
		echo "<tr>";
			echo "<td>Waktu Umpan Balik</td>";
			echo "<td>$data_minicex[waktu_ub]&nbsp;&nbsp;menit</td>";
		echo "</tr>";
		//Dosen Penguji
		echo "<tr>";
			echo "<td>Dosen Penguji</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:58%\">Komponen Penilaian Ketrampilan</th>";
			echo "<th style=\"width:22%\">Observasi</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//KOMUNIKASI
		echo "<tr>";
			echo "<td colspan=4>1. KOMUNIKASI</td>";
		echo "</tr>";
		//No 1.1.
		echo "<tr>";
			echo "<td align=center>1.1.</td>";
			echo "<td>Memperkenalkan diri dan menjelaskan perannya kepada pasien</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_11]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_11]</td>";
		echo "</tr>";
		//No 1.2.
		echo "<tr>";
			echo "<td align=center>1.2.</td>";
			echo "<td>Memperlihatkan kontak mata yang baik</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_12]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_12]</td>";
		echo "</tr>";
		//No 1.3.
		echo "<tr>";
			echo "<td align=center>1.3.</td>";
			echo "<td>Mendengarkan pasien tanpa menginterupsi</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_13]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_13]</td>";
		echo "</tr>";
		//No 1.4.
		echo "<tr>";
			echo "<td align=center>1.4.</td>";
			echo "<td>Mengekspresikan perhatiannya kepada pasien</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_14]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_14]</td>";
		echo "</tr>";
		//No 1.5.
		echo "<tr>";
			echo "<td align=center>1.5.</td>";
			echo "<td>Bertanya dengan pertanyaan terbuka</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_15]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_15]</td>";
		echo "</tr>";
		//No 1.6.
		echo "<tr>";
			echo "<td align=center>1.6.</td>";
			echo "<td>Memberi kesempatan kepada pasien untuk bertanya</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_16]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_16]</td>";
		echo "</tr>";
		//No 1.7.
		echo "<tr>";
			echo "<td align=center>1.7.</td>";
			echo "<td>Menjelaskan perencanaan selanjutnya dengan baik</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_17]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_17]</td>";
		echo "</tr>";

		//2. PROFESIONALISME
		echo "<tr>";
			echo "<td colspan=4>2. PROFESIONALISME</td>";
		echo "</tr>";
		//No 2.1.
		echo "<tr>";
			echo "<td align=center>2.1.</td>";
			echo "<td>Mengenakan pakaian yang pantas</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_21]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_21]</td>";
		echo "</tr>";
		//No 2.2.
		echo "<tr>";
			echo "<td align=center>2.2.</td>";
			echo "<td>Sopan / hormat pada pasien</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_22]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_22]</td>";
		echo "</tr>";
		//No 2.3.
		echo "<tr>";
			echo "<td align=center>2.3.</td>";
			echo "<td>Memperlihatkan sikap profesional (memanggil nama pasien, memperlihatkan keseriusan dan kompeten)</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_23]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_23]</td>";
		echo "</tr>";
		//No 2.4.
		echo "<tr>";
			echo "<td align=center>2.4.</td>";
			echo "<td>Menghargai kebebasan / kerahasiaan pribadi</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_24]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_24]</td>";
		echo "</tr>";

		//KETRAMPILAN KLINIK
		echo "<tr>";
			echo "<td colspan=4>3. KETRAMPILAN KLINIK</td>";
		echo "</tr>";
		//No 3.1.
		echo "<tr>";
			echo "<td align=center>3.1.</td>";
			echo "<td>Mencuci tangan sebelum dan sesudah  memeriksa pasien</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_31]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_31]</td>";
		echo "</tr>";
		//No 3.2.
		echo "<tr>";
			echo "<td align=center>3.2.</td>";
			echo "<td>Pemeriksaan visus</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_32]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_32]</td>";
		echo "</tr>";
		//No 3.3.
		echo "<tr>";
			echo "<td align=center>3.3.</td>";
			echo "<td>Pemeriksaan segmen anterior</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_33]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_33]</td>";
		echo "</tr>";
		//No 3.4.
		echo "<tr>";
			echo "<td align=center>3.4.</td>";
			echo "<td>Pemeriksaan funduskopi</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_34]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_34]</td>";
		echo "</tr>";
		//No 3.5.
		echo "<tr>";
			echo "<td align=center>3.5.</td>";
			echo "<td>Pemeriksaan tonometri</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_35]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_35]</td>";
		echo "</tr>";
		//No 3.6.
		echo "<tr>";
			echo "<td align=center>3.6.</td>";
			echo "<td>Pemeriksaan lapang pandang</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_36]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_36]</td>";
		echo "</tr>";

		//CLINICAL REASONING
		echo "<tr>";
			echo "<td colspan=4>4. CLINICAL REASONING</td>";
		echo "</tr>";
		//No 4.1.
		echo "<tr>";
			echo "<td align=center>4.1.</td>";
			echo "<td>Diagnosis</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_41]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_41]</td>";
		echo "</tr>";
		//No 4.2.
		echo "<tr>";
			echo "<td align=center>4.2.</td>";
			echo "<td>Tatalaksana</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_42]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_42]</td>";
		echo "</tr>";
		//No 4.3.
		echo "<tr>";
			echo "<td align=center>5.3.</td>";
			echo "<td>Edukasi</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_43]=="1") echo "Ya";
			else echo "Tidak";
			echo "</td>";
			echo "<td align=center>$data_minicex[aspek_43]</td>";
		echo "</tr>";

		//Nilai Total
		echo "<tr>";
			echo "<td colspan=3 align=right>Nilai Rata-Rata (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center><b>$data_minicex[nilai_rata]</b></td>";
		echo "</tr>";

		//Komentar / Saran
		echo "<tr>";
			echo "<td colspan=4>Komentar / Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_minicex[saran]</textarea></td>";
		echo "</tr>";

		echo "<tr><td colspan=4 align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
		echo "Dosen Penguji<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			echo "
					<script>
					window.location.href=\"penilaian_mata.php\";
					</script>
					";

		}


	}
	else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
?>

<script src="../jquery.min.js"></script>

<!--</body></html>-->
</BODY>
</HTML>
