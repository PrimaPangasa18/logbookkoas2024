<HTML>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
  <script type="text/javascript" src="jquery.min.js"></script>
<!--</head>-->
</head>

<BODY>
<?php

include "config.php";
include "fungsi.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
  echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
}
else{
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass'])AND ($_COOKIE['level']==1))
{
	if ($_COOKIE['level']==1) {include "menu1.php";}
	
	echo "<div class=\"text_header\">UPDATE NIM MAHASISWA KOAS</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

  echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">UPDATE NIM MAHASISWA KOAS</font></h4><br></center>";

  echo "Import file berekstensi <i>csv</i> (*.csv) dengan kolom yang sama dengan format header tabel di bawah, menggunakan separator ( , ) atau ( ; ).<br><br>";
	echo "Format header tabel:<br>";
	echo "<font color=blue>| <i>no</i> | <i>nama</i> | <i>nim_lama</i> | <i>nim_baru</i> |</font><br>";
	echo "<br><font color=red>Catatan: <br>";
	echo "1. Kolom <i>no</i> diisi dengan nomor urut</i>.<br>";
  echo "2. Kolom <i>nama</i> diisi dengan nama lengkap mahasiswa koas.<br>";
	echo "3. Kolom <i>nim_lama</i> diisi dengan NIM lama mahasiswa koas.<br>";
  echo "4. Kolom <i>nim_baru</i> diisi dengan NIM baru mahasiswa koas.<br>";
  echo "</font><br><br>";

  echo "<form name=\"myForm\" id=\"myForm\" onSubmit=\"return validateForm()\" action=\"csvupdate_nim_koas.php\" method=\"post\" enctype=\"multipart/form-data\" required>";
  echo "Import file: <input type=\"file\" id=\"update_nim_koas\" name=\"update_nim_koas\" accept=\".csv\"></input><br><br>";
  echo "Separator file csv: ";
  echo "<select name=\"separator\" required>";
  echo "<option value=\"\">< Pilihan Separator ></option>";
  echo "<option value=\",\">Koma --> ( , )</option>";
  echo "<option value=\";\">Titik Koma --> ( ; )</option>";
  echo "</select>";
  echo "<br><br><input type=\"submit\" class=\"submit1\" name=\"import\" value=\"Import\"></input></form><br>";

  echo "<br><br></fieldset>";
}
else
echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
}
?>



<!--</body></html>-->
</BODY>
</HTML>
