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
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE[level]==2)
{
  if ($_COOKIE['level']==2) {include "menu2.php";}

  echo "<div class=\"text_header\">GENERATE QR CODE DOSEN/RESIDEN</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
  echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">GENERATE QR CODE DOSEN/RESIDEN</font></h4><br>";
  echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

  echo "<center>Cari Dosen/Residen: ";
  echo "<input type=\"text\" name=\"kunci\" style=\"width:200px\" /><br><br>
      <font style=\"font-size:0.625em\"><i>(masukkan NIP atau nama dosen atau username residen)</i></font><br><br>";
  echo "<input type=\"submit\" class=\"submit1\" name=\"cari\" value=\"CARI\" /><br><br>";

  if ($_POST['cari']=="CARI")
  {
    $user_kunci = mysqli_query($con,"SELECT * FROM `admin` WHERE (`level`='4' OR `level`='6') AND (`username` LIKE '%$_POST[kunci]%' OR `nama` LIKE '%$_POST[kunci]%')");
    $jml = mysqli_num_rows($user_kunci);
    if ($jml>=1)
    {
      echo "<table style=\"border:0\">";
      echo "<tr>";
        echo "<td>";
        echo "<font style=\"font-size:0.625em\"><i>(Klik username untuk generate QR code dosen)</i></font>";
        echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "<table style=\"border:0;box-shadow: 10px 10px 20px rgba(0,0,0,0.4)\">";
      echo "<tr class=\"ganjil\">";
        echo "<td style=\"width:50px;text-align:center\">No</td>";
        echo "<td style=\"width:150px;text-align:center\">Username (NIP/NIK)</td>";
        echo "<td style=\"width:400px;text-align:center\">Nama Dosen</td>";
      echo "</tr>";
      $no=1;
      while ($data=mysqli_fetch_array($user_kunci))
      {
        $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data[username]'"));
        echo "<tr class=\"genap\">";
          echo "<td style=\"text-align:center\">$no</td>";
          echo "<td><a href=\"generate_qr_admin.php?user_name=$data_dosen[nip]\">$data_dosen[nip]</a></td>";
          echo "<td>$data_dosen[nama], $data_dosen[gelar]</td>";
        echo "</tr>";
        $no++;
      }
      echo "</table>";
    }
    else
    {
      echo "<font style=\"font-size:1.0em;color:red\">Tidak ada dosen dengan kata kunci \"<i>$_POST[kunci]\" !!!</i></font>";
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
