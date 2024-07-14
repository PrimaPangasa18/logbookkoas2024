<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="select2/dist/css/select2.css"/>
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE[level]==2)
	{
		if ($_COOKIE['level']==2) {include "menu2.php";}

		echo "<div class=\"text_header\" id=\"top\">ENTRY NILAI MANUAL</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">FILTER ENTRY NILAI MANUAL</font></h4><br>";

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
			echo "<td style=\"width:30%\">Nama / NIM Mahasiswa</td>";
			echo "<td style=\"width:70%\">";
			if ($_COOKIE['user']=="kaprodi")
			{
				echo "<select class=\"select_artwide\" name=\"nim_mhsw\" id=\"nim_mhsw\" required>";
				$data_mhsw = mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
				echo "<option value=\"\"><< Pilih Mahasiswa >></option>";
				while ($data=mysqli_fetch_array($data_mhsw))
				echo "<option value=\"$data[nim]\">$data[nama] (NIM: $data[nim])</option>";
				echo "</select>";
			}
			else
			{
				$id_stase = substr($_COOKIE['user'], 5, 3);
				$stase_id = "stase_M"."$id_stase";
				echo "<select class=\"select_artwide\" name=\"nim_mhsw\" id=\"nim_mhsw\" required>";
				$nim_mhsw = mysqli_query($con,"SELECT `nim` FROM `$stase_id` ORDER BY `id`");
				echo "<option value=\"\"><< Pilih Mahasiswa >></option>";
				while ($data=mysqli_fetch_array($nim_mhsw))
				{
					$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$data[nim]'"));
					echo "<option value=\"$data_mhsw[nim]\">$data_mhsw[nama] (NIM: $data_mhsw[nim])</option>";
				}
				echo "</select>";
			}
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Kepaniteraan / Stase</td>";
			echo "<td>";
			if ($_COOKIE['user']=="kaprodi")
			{
				echo "<select class=\"select_artwide\" name=\"stase\" id=\"stase\" required>";
				$data_stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
				echo "<option value=\"\"><< Pilihan Kepaniteraan (Stase) >></option>";
				while ($data=mysqli_fetch_array($data_stase))
				echo "<option value=\"$data[id]\">$data[kepaniteraan]</option>";
				echo "</select>";
			}
			else
			{
				$id_stase = substr($_COOKIE['user'], 5, 3);
				$stase_id = "M"."$id_stase";
				$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$stase_id'"));
				echo "<select class=\"select_artwide\" name=\"stase\" id=\"stase\" required>";
				echo "<option value=\"$data_stase[id]\">$data_stase[kepaniteraan]</option>";
				echo "</select>";
			}
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Jenis Nilai</td>";
			$filter_test = filter_nilai($stase_id);
			echo "<td>";
			$jenis_test = mysqli_query($con,"SELECT * FROM `jenis_test` WHERE $filter_test ORDER BY `id`");
			echo "<select class=\"select_art\" name=\"jenis_nilai\" id=\"jenis_nilai\" required>";
			echo "<option value=\"\"><< Jenis Nilai >></option>";
			while ($test = mysqli_fetch_array($jenis_test))
			{
				echo "<option value=\"$test[id]\">$test[jenis_test]</option>";
			}
			echo "</select>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Status Ujian/Test</td>";
			echo "<td>";
			$data_status = mysqli_query($con,"SELECT * FROM `status_ujian` ORDER BY `id`");
			echo "<select class=\"select_art\" name=\"status_ujian\" id=\"status_ujian\" required>";
			echo "<option value=\"\"><< Status Ujian/Test >></option>";
			while ($status = mysqli_fetch_array($data_status))
			{
				echo "<option value=\"$status[id]\">$status[status_ujian]</option>";
			}
			echo "</select>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Tanggal Ujian/Test</td>";
			echo "<td><input type=\"text\" class=\"input-tanggal\" name=\"tgl_ujian\" placeholder=\"yyyy-mm-dd\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" required /></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Tanggal Yudisium Nilai</td>";
			echo "<td><input type=\"text\" class=\"input-tanggal\" name=\"tgl_approval\" placeholder=\"yyyy-mm-dd\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" required /></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Nilai Ujian</td>";
			echo "<td><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai\" style=\"width:15%;font-size:0.85em;text-align:center\" placeholder=\"0-100\" required/></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Catatan Khusus</td>";
			echo "<td><textarea row=3 name=\"catatan\" style=\"width:100%;font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;border:0.5px solid grey;border-radius:5px;\"></textarea></td>";
		echo "</tr>";
		echo "</table><br>";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\"></center></form><br>";

		if ($_POST[submit]=="SUBMIT")
		{
			//Stase yang entry nilai:
			//Stase Neurologi (M092)
			//Stase Psikiatri (M093)
	    //Stase IKM-KP (M095)
	    //Stase THT-KL (M105)
			//Stase Anestesi (M102)
			//Stase Radiologi (M103)
			//Stase Mata (M104)
			//Stase IKFR (M094)
			//Stase IKGM (M106)
			//Stase Forensik (M112)
			//Stase Kulit dan Kelamin (M114)
			//Stase Ilmu Penyakit Dalam (M091)
			//Stase Ilmu Kebidanan dan Penyakit Kandungan (M111)
			//Stase Ilmu Bedah (M101)
			//Stase Ilmu Kesehatan Anak (M113)

	    if ($stase_id =="M092" or $stase_id =="M093" or $stase_id =="M095" or $stase_id =="M105" or $stase_id =="M102"
				or $stase_id =="M103" or $stase_id =="M104" or $stase_id =="M094" or $stase_id =="M106" or $stase_id =="M112"
				or $stase_id =="M114" or $stase_id =="M091" or $stase_id =="M111" or $stase_id =="M101" or $stase_id =="M113")
	    {
				if ($stase_id =="M095") {$db_tabel = "`ikmkp_nilai_test`";$kordik_id="K095";}
	      if ($stase_id =="M105") {$db_tabel = "`thtkl_nilai_test`";$kordik_id="K105";}
				if ($stase_id =="M092") {$db_tabel = "`neuro_nilai_test`";$kordik_id="K092";}
				if ($stase_id =="M093") {$db_tabel = "`psikiatri_nilai_test`";$kordik_id="K093";}
				if ($stase_id =="M102") {$db_tabel = "`anestesi_nilai_test`";$kordik_id="K102";}
				if ($stase_id =="M103") {$db_tabel = "`radiologi_nilai_test`";$kordik_id="K103";}
				if ($stase_id =="M104") {$db_tabel = "`mata_nilai_test`";$kordik_id="K104";}
				if ($stase_id =="M094") {$db_tabel = "`ikfr_nilai_test`";$kordik_id="K094";}
				if ($stase_id =="M106") {$db_tabel = "`ikgm_nilai_test`";$kordik_id="K106";}
				if ($stase_id =="M112") {$db_tabel = "`forensik_nilai_test`";$kordik_id="K112";}
				if ($stase_id =="M114") {$db_tabel = "`kulit_nilai_test`";$kordik_id="K114";}
				if ($stase_id =="M091") {$db_tabel = "`ipd_nilai_test`";$kordik_id="K091";}
				if ($stase_id =="M111") {$db_tabel = "`obsgyn_nilai_test`";$kordik_id="K111";}
				if ($stase_id =="M101") {$db_tabel = "`bedah_nilai_test`";$kordik_id="K101";}
				if ($stase_id =="M113") {$db_tabel = "`ika_nilai_test`";$kordik_id="K113";}

				$nim = $_POST[nim_mhsw];
				$id_stase = $_POST[stase];
				$jenis_nilai = $_POST[jenis_nilai];
				$nama_test = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `jenis_test` WHERE `id`='$jenis_nilai'"));
				$status_ujian = $_POST[status_ujian];
			  $nama_status_ujian = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `status_ujian` WHERE `id`='$status_ujian'"));
				$nilai = $_POST[nilai];
				if ($_POST[catatan]=="" OR empty($_POST[catatan])) $catatan = "-";
				else $catatan = addslashes($_POST[catatan]);
				$tgl_ujian = $_POST[tgl_ujian];
				$tgl_approval = $_POST[tgl_approval];
				$dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `username` FROM `admin` WHERE `stase`='$kordik_id'"));
				$nama_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$nim'"));

				echo "<table border=1 style=\"width:75%\">";
				echo "<tr>";
					echo "<td style=\"width:22%\">Nama / NIM Mahasiswa</td>";
					echo "<td style=\"width:78%\">$nama_mhsw[nama] (NIM: $nim)</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>Kepaniteraan / Stase</td>";
					$kepaniteraan = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
					echo "<td>$kepaniteraan[kepaniteraan]</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>Jenis Nilai</td>";
					echo "<td>$nama_test[jenis_test]</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>Status Ujian</td>";
					echo "<td>$nama_status_ujian[status_ujian]</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>Tanggal Ujian/Test</td>";
					$tanggal_ujian = tanggal_indo($tgl_ujian);
					echo "<td>$tanggal_ujian</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>Tanggal Yudisium Nilai</td>";
					$tanggal_approval = tanggal_indo($tgl_approval);
					echo "<td>$tanggal_approval</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>Nilai Ujian</td>";
					$nilai_ujian = number_format($nilai,2);
					echo "<td>$nilai_ujian</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>Catatan Khusus</td>";
					echo "<td><i>$catatan</i></td>";
				echo "</tr>";

				$cek_nim_stase = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM $db_tabel WHERE `nim`='$nim' AND `jenis_test`='$jenis_nilai' AND `status_ujian`='$status_ujian'"));
				if ($cek_nim_stase>0)
				{
					$nilai_update = mysqli_query($con,"UPDATE $db_tabel
						SET `dosen`='$dosen[username]',`nilai`='$nilai_ujian',`catatan`='$catatan',
						`tgl_test`='$tgl_ujian',`tgl_approval`='$tgl_approval',`status_approval`='1'
						WHERE `nim`='$nim' AND `jenis_test`='$jenis_nilai' AND `status_ujian`='$status_ujian'");

					if (!$nilai_update)
					{
						echo "<tr>";
							echo "<td>Status</td>";
							echo "<td><font style=\"color:red\">ERROR</font></td>";
						echo "</tr>";
					}
					else
					{
						echo "<tr>";
							echo "<td>Status</td>";
							echo "<td><font style=\"color:green\">UPDATED</font></td>";
						echo "</tr>";
					}
				}
				else
				{
					$nilai_insert = mysqli_query($con,"INSERT INTO $db_tabel
						( `nim`, `dosen`, `jenis_test`,`status_ujian`,
							`nilai`, `catatan`,
							`tgl_test`, `tgl_approval`, `status_approval`)
						VALUES
						( '$nim','$dosen[username]','$jenis_nilai','$status_ujian',
							'$nilai_ujian','$catatan',
							'$tgl_ujian','$tgl_approval','1')");

					if (!$nilai_insert)
					{
						echo "<tr>";
							echo "<td>Status</td>";
							echo "<td><font style=\"color:red\">ERROR</font></td>";
						echo "</tr>";
					}
					else
					{
						echo "<tr>";
							echo "<td>Status</td>";
							echo "<td><font style=\"color:green\">ISSUED</font></td>";
						echo "</tr>";
					}
				}
				echo "</table><br><br>";
			}
			//Stase yang tidak entry nilai
	    else
	    {
	      echo "<font style=\"color:red\">Catatan: Untuk Kepaniteraan/Stase ini tidak ada kegiatan $nama_test[jenis_test]!!!</font>";
	    }
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
<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
<script src="select2/dist/js/select2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.input-tanggal').datepicker({ dateFormat: 'yy-mm-dd' });
		$("#nim_mhsw").select2({
			 	placeholder: "<< Pilih Mahasiswa >>",
	      allowClear: true
		 	});
		$("#stase").select2({
			 	placeholder: "<< Pilihan Kepaniteraan (Stase) >>",
	      allowClear: true
		 	});
		$("#dosen").select2({
		   	placeholder: "< Nama Dosen/Residen >",
	      allowClear: true
	 		});
		$("#jenis_nilai").select2({
			 	placeholder: "<< Jenis Nilai >>",
	      allowClear: true
			});
		$("#status_ujian").select2({
			 	placeholder: "<< Jenis Ujian/Test >>",
	      allowClear: true
			});
	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
