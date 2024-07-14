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

		echo "<div class=\"text_header\" id=\"top\">UPLOAD NILAI</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">FILTER UPLOAD NILAI</font></h4><br>";

		echo "<form name=\"myForm\" id=\"myForm\" onSubmit=\"return validateForm()\" action=\"csvimport_nilai.php\" method=\"post\" enctype=\"multipart/form-data\" required>";

		echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
			echo "<td style=\"width:30%\">Kepaniteraan / Stase</td>";
			echo "<td style=\"width:70%\">";
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
		echo "<td colspan=2>";
		echo "Upload file nilai dalam format csv dengan header: no - nama - nim - nilai - catatan<br><br>";
		echo "<font style=\"font-size:0.75em;color:blue\">Catatan:<br>";
		echo "Kolom <i>no</i> diisi nomor urut.<br>";
		echo "Kolom <i>nama</i> diisi nama mahasiswa koas.<br>";
		echo "Kolom <i>nim</i> diisi nim mahasiswa koas.<br>";
		echo "Kolom <i>nilai</i> diisi nilai mahasiswa koas, dalam format desimal dengan dua angka di belakang titik (0.00 - 100.00).<br>";
		echo "Kolom <i>catatan</i> diisi catatan khusus mengenai nilai ujian/test mahasiswa koas.<br>";
		echo "</font>";

		echo "<br>Import file: <input type=\"file\" id=\"import_nilai\" name=\"import_nilai\" accept=\".csv\"></input><br><br>";
	  echo "Separator file csv: ";
	  echo "<select name=\"separator\" required>";
	  echo "<option value=\"\">< Pilihan Separator ></option>";
	  echo "<option value=\",\">Koma --> ( , )</option>";
	  echo "<option value=\";\">Titik Koma --> ( ; )</option>";
	  echo "</select>";
	  echo "<br><br><center><input type=\"submit\" class=\"submit1\" name=\"import\" value=\"Import\"></input></form><br>";

		echo "</td>";
		echo "</tr>";

		echo "</table><br>";
		echo "</form>";



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
			 	placeholder: "<< Status Ujian/Test >>",
	      allowClear: true
			});
	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
