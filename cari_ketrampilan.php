<HTML>
<head>
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
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass'])AND $_COOKIE['level']==2)
{
  if ($_COOKIE['level']==2) {include "menu2.php";}
  echo "<div class=\"text_header\">CARI DAN EDIT KETRAMPILAN</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
  echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">CARI DAN EDIT KETRAMPILAN</font></h4><br>";
  echo "</center>";

  echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
  echo "<center>Cari Nama Ketrampilan: ";
  echo "<input type=\"text\" name=\"kunci\" style=\"width:200px\"></input><font style=\"font-size:0.625em\"><br><br><i>(ketikkan nama ketrampilan atau id ketrampilan)</i></font><br><br>";
  echo "&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"cari\" value=\"CARI\"></input></form><br><br>";

  $id = $_GET[id];

  if ($_POST['cari']=="CARI")
  {
    $ketrampilan_kunci = mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE (`ketrampilan` LIKE '%$_POST[kunci]%' OR `id` LIKE '%$_POST[kunci]%') ORDER BY `ketrampilan`");
    $jml = mysqli_num_rows($ketrampilan_kunci);
    if ($jml>=1)
    {
      echo "<font style=\"font-size:0.625em\"><i>(Klik ID Ketrampilan untuk edit ketrampilan)</i></font><p>";
      echo "<table style=\"width:60%;border:0;box-shadow: 10px 10px 20px rgba(0,0,0,0.4)\">";
      echo "<tr class=\"ganjil\">";
        echo "<td style=\"width:7%;text-align:center\">No</td>";
        echo "<td style=\"width:20%;text-align:center\">ID Ketrampilan</td>";
        echo "<td style=\"width:73%;text-align:center\">Nama Ketrampilan</td>";
      echo "</tr>";
      $no=1;
      while ($data=mysqli_fetch_array($ketrampilan_kunci))
      {
        echo "<tr class=\"genap\">";
          echo "<td style=\"text-align:center\">$no</td>";
          echo "<td><a href=\"edit_daftar_ketrampilan.php?id=$data[id]\">$data[id]</a></td>";
          echo "<td>$data[ketrampilan]</td>";
        echo "</tr>";
        $no++;
      }
      echo "</table>";
    }
    else
    {
      echo "<font style=\"font-size:1.0em;color:red\">Tidak ada nama ketrampilan dengan kata kunci \"<i>$_POST[kunci]\" !!!</i></font>";
    }
  }

  if ($_GET['status']=="HAPUS")
  {
    echo "<font style=\"color:red\">Data ketrampilan tersebut telah dihapus!!!</font>";
  }
  if ($_GET['status']=="UBAH")
  {
    echo "<font style=\"color:blue\">Data ketrampilan tersebut telah diedit dan disimpan!!!</font><br>";
    $data_ketrampilan = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `id`='$id'"));
    echo "<table>";
    echo "<thead><th colspan=2 align=center>Data Ketrampilan</th></thead>";
    echo "<tr class=\"ganjil\">";
    echo "<td>Nama Ketrampilan</td>";
    echo "<td>$data_ketrampilan[ketrampilan]</td>";
    echo "</tr>";
    echo "<tr class=\"ganjil\">";
    echo "<td>Level SKDI</td>";
    echo "<td>$data_ketrampilan[skdi_level]</td>";
    echo "</tr>";
    echo "<tr class=\"ganjil\">";
    echo "<td>Level Kepmenkes</td>";
    echo "<td>$data_ketrampilan[k_level]</td>";
    echo "</tr>";
    echo "<tr class=\"ganjil\">";
    echo "<td>Level IPSG</td>";
    echo "<td>$data_ketrampilan[ipsg_level]</td>";
    echo "</tr>";
    echo "<tr class=\"ganjil\">";
    echo "<td>Level Muatan Lokal</td>";
    echo "<td>$data_ketrampilan[kml_level]</td>";
    echo "</tr>";
    echo "<thead><th colspan=2 align=center>Kepaniteraan (Stase)</th></thead>";
    $no=1;
    $daftar_stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
    while ($data=mysqli_fetch_array($daftar_stase))
    {
      $nama_cek = "include_".$data[id];
      $target = "target_".$data[id];
      echo "<tr class=\"ganjil\">";
      echo "<td>$no. $data[kepaniteraan]</td>";
      if ($data_ketrampilan[$nama_cek]=='1') echo "<td>Included, Target: $data_ketrampilan[$target]</td>";
      else echo "<td> - </td>";
      echo "</tr>";
      $no++;
    }
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



<!--</body></html>-->
</BODY>
</HTML>
