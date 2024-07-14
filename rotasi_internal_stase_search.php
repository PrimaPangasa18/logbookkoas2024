<HTML>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="select2/dist/css/select2.css"/>


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
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
{
  if ($_COOKIE['level']==1) {include "menu1.php";}
  if ($_COOKIE['level']==2) {include "menu2.php";}
  if ($_COOKIE['level']==3) {include "menu3.php";}

  echo "<div class=\"text_header\">ROTASI INTERNAL KEPANITERAAN (STASE)</div>";

  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

  echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI INTERNAL KEPANITERAAN (STASE)</font></h4><br>";

  echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
  echo "<table>";
  echo "<tr class=\"ganjil\">";
  echo "<td class=\"td_mid\">Nama Mahasiswa</td>";
  echo "<td class=\"td_mid\">";
  echo "<select class=\"select_artwide\" name=\"mahasiswa\" id=\"mahasiswa\" required>";
  $data_mhsw = mysqli_query($con,"SELECT * FROM `biodata_mhsw` ORDER BY `nama`");
  echo "<option value=\"\">< Nama Mahasiswa [NIM] ></option>";
  while ($data1=mysqli_fetch_array($data_mhsw))
  echo "<option value=\"$data1[nim]\">$data1[nama] [$data1[nim]]</option>";
  echo "</select>";
  echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\">";
  echo "<td class=\"td_mid\">Kepaniteraan (Stase)</td>";
  echo "<td class=\"td_mid\">";
  echo "<select class=\"select_artwide\" name=\"stase\" id=\"stase\" required>";
  $data_stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
  echo "<option value=\"\">< Pilihan Kepaniteraan (Stase) ></option>";
  while ($data=mysqli_fetch_array($data_stase))
  echo "<option value=\"$data[id]\">$data[kepaniteraan]</option>";
  echo "</select>";
  echo "</td>";
  echo "</tr>";
  echo "</table>";
  echo "<br><br><input type=\"submit\" class=\"submit1\" name=\"tampilkan\" value=\"TAMPILKAN\" /><br><br>";
  echo "</form>";

  if ($_POST['tampilkan']=="TAMPILKAN")
  {
    echo "
    		<script>
    			window.location.href=\"internal_stase_admin.php?id=\"+\"$_POST[stase]\"+\"&mhsw=\"+\"$_POST[mahasiswa]\";
    		</script>
    		";
  }

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

<script type="text/javascript" src="jquery.min.js"></script>
<script src="select2/dist/js/select2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#stase").select2({
			 	placeholder: "< Kepaniteraan (Stase) >",
	      allowClear: true
		 	});
		$("#mahasiswa").select2({
		   	placeholder: "< Nama Mahasiswa [NIM] >",
	      allowClear: true
	 		});

	});
</script>


<!--</body></html>-->
</BODY>
</HTML>
