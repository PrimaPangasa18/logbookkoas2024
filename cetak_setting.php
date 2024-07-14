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
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3 OR $_COOKIE['level']==5))
{
  if ($_COOKIE['level']==1) {include "menu1.php";}
  if ($_COOKIE['level']==2) {include "menu2.php";}
  if ($_COOKIE['level']==3) {include "menu3.php";}
  if ($_COOKIE['level']==5) {include "menu5.php";}

  if ($_COOKIE['level']==5) echo "<div class=\"text_header\">REKAP LOGBOOK</div>";
  else echo "<div class=\"text_header\">REKAP INDIVIDU LOGBOOK</div>";

  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

  if ($_COOKIE['level']==5) echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP LOGBOOK KEPANITERAAN (STASE)</font></h4><br>";
  else echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP INDIVIDU JURNAL KEPANITERAAN (STASE)</font></h4><br>";
  echo "<b>Setting Cetak Logbook</b><br><br>";

  if ($_COOKIE['level']==5) $mhsw_nim = $_COOKIE[user];
  else $mhsw_nim = $_GET['nim'];
  $jenis = $_GET['jenis'];
  $cetak = $_GET['cetak'];
  $id_stase = $_GET['id'];
  $data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$mhsw_nim'"));

  echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
  echo "<input type=\"hidden\" name=\"mhsw_nim\" value=\"$mhsw_nim\" />";
  echo "<input type=\"hidden\" name=\"jenis\" value=\"$jenis\" />";
  echo "<input type=\"hidden\" name=\"cetak\" value=\"$cetak\" />";
  echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\" />";

  echo "<table border=\"0\">";
  //Jumlah baris/halaman
  echo "<tr class=\"ganjil\">";
  echo "<td class=\"td_mid\">Jumlah baris/halaman</td>";
  echo "<td>";
  echo "<select class=\"select_art\" name=\"baris_cetak\" id=\"baris_cetak\" required>";
  echo "<option value=\"50\">50 baris (maksimal)</option>";
  for ($i=0;$i<=25;$i++)
  {
    $value = 25+$i;
    echo "<option value=\"$value\">$value baris</option>";
  }
  echo "</select>";
  echo "</td>";
  echo "</tr>";
  //Dosen wali
  echo "<tr class=\"ganjil\">";
    echo "<td class=\"td_mid\">";
      echo "Dosen Wali";
    echo "</td>";
    echo "<td>";
      $dosen_wali = mysqli_fetch_array(mysqli_query($con,"SELECT `nama`,`gelar` FROM `dosen` WHERE `nip`='$data_mhsw[dosen_wali]'"));
      echo "<select class=\"select_artwide\" name=\"dosen_wali\" id=\"dosen_wali\" required>";
      $dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' ORDER BY `nama`");
      if ($data_mhsw[dosen_wali]=="")
      echo "<option value=\"\">< Pilihan Dosen Wali ></option>";
      else
      echo "<option value=\"$data_mhsw[dosen_wali]\">$dosen_wali[nama], $dosen_wali[gelar] (NIP. $data_mhsw[dosen_wali])</option>";
      while ($dat9=mysqli_fetch_array($dosen))
      {
        $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$dat9[username]'"));
        echo "<option value=\"$dat9[username]\">$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</option>";
      }
      echo "</select>";
    echo "</td>";
  echo "</tr>";
  //Tanggal cetak
  echo "<tr class=\"ganjil\">";
    echo "<td>";
      echo "Tanggal Cetak <i>(yyyy-mm-dd) &nbsp;&nbsp;&nbsp;</i>";
    echo "</td>";
    echo "<td>";
      echo "<input type=\"text\" id=\"input-tanggal\" name=\"tgl_cetak\" class=\"select_artwide\" value=\"$tgl\" />";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\"><td colspan=2>&nbsp;</td></tr>";

  echo "<tr class=\"genap\">";
     echo "<td colspan=2 style=\"text-align:center\">";
       echo "<br><input type=\"submit\" class=\"submit1\" name=\"print\" value=\"CETAK PDF\">";
     echo "<br><br></td>";
   echo "</tr>";

  echo "</table></form>";

  if ($_POST['print']=="CETAK PDF")
  {
    $update_dosenwali = mysqli_query($con,"UPDATE `biodata_mhsw` SET `dosen_wali`='$_POST[dosen_wali]' WHERE `nim`='$_POST[mhsw_nim]'");
    echo "
    		<script>
    			window.location.href=\"cetak_rekap_indstase.php?jenis=\"+\"$_POST[jenis]\"+\"&cetak=\"+\"$_POST[cetak]\"+\"&id=\"+\"$_POST[id_stase]\"+\"&nim=\"+\"$_POST[mhsw_nim]\"+\"&baris_cetak=\"+\"$_POST[baris_cetak]\"+\"&tgl_cetak=\"+\"$_POST[tgl_cetak]\";
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
<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
    $("#baris_cetak").select2({
			placeholder: "< Pilihan Baris/Halaman >",
      allowClear: true
		});
		$("#dosen_wali").select2({
			placeholder: "< Pilihan Dosen Wali >",
      allowClear: true
		});
    $('#input-tanggal').datepicker({ dateFormat: 'yy-mm-dd' });
  });
</script>


<!--</body></html>-->
</BODY>
</HTML>
