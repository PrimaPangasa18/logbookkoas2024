<HTML>
<head>
    <meta charset="utf-8">
    <title>::Edit User::</title>
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
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3 OR $_COOKIE['level']==4 OR $_COOKIE['level']==6))
{
  if ($_COOKIE['level']==1) {include "menu1.php";}
  if ($_COOKIE['level']==2) {include "menu2.php";}
  if ($_COOKIE['level']==3) {include "menu3.php";}
  if ($_COOKIE['level']==4) {include "menu4.php";}
  if ($_COOKIE['level']==6) {include "menu6.php";}
  echo "<div class=\"text_header\">PROFIL USER</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
  echo "<center><font style=\"font-family:Georgia;text-shadow:1px 1px black;color:#006400;font-size:1.25em\">PROFIL USER</font>";
  echo "<br><br><br>";

  $data_user = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `admin` WHERE `username`='$_COOKIE[user]'"));
  $dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_user[username]'"));
  $bagian = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `bagian_ilmu` WHERE `id`='$dosen[kode_bagian]'"));
  $level_user = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `level` WHERE `id`='$data_user[level]'"));

  echo "<table>";
  echo "<tr class=\"ganjil\">";
    echo "<td style=\"width:200px\">";
      echo "Username";
    echo "</td>";
    echo "<td>";
      echo "$data_user[username]";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"genap\">";
    echo "<td>";
      echo "Nama Lengkap";
    echo "</td>";
    echo "<td>";
      echo "$dosen[nama]";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\">";
    echo "<td>";
      echo "Gelar";
    echo "</td>";
    echo "<td>";
      echo "$dosen[gelar]";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"genap\">";
    echo "<td>";
      echo "Bagian";
    echo "</td>";
    echo "<td>";
      echo "$bagian[bagian]";
    echo "</td>";
  echo "</tr>";
  echo "</table><br><br>";
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



<!--</body></html>-->
</BODY>

</HTML>
