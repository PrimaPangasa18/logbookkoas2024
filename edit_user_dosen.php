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
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE[level]==2))
{
  if ($_COOKIE['level']==1) {include "menu1.php";}
  if ($_COOKIE['level']==2) {include "menu2.php";}
  echo "<div class=\"text_header\">EDIT USER</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
  echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT USER DOSEN/RESIDEN</font></h4><br>";
  echo "</center>";

  echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
  echo "<center>Cari User: ";
  echo "<input type=\"text\" name=\"kunci\" style=\"width:200px\"></input><font style=\"font-size:0.625em\"><br><br><i>
        (masukkan NIP atau nama dosen atau residen)</i></font><br><br>";
  echo "&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"cari\" value=\"CARI\"></input><br><br>";

  if ($_POST['cari']=="CARI")
  {
    $kunci = addslashes($_POST[kunci]);
    if ($_COOKIE['level']==2) $user_kunci = mysqli_query($con,"SELECT * FROM `admin` WHERE (`level`='4' OR `level`='6') AND (`username` LIKE '%$kunci%' OR `nama` LIKE '%$kunci%')");
    if ($_COOKIE['level']==1) $user_kunci = mysqli_query($con,"SELECT * FROM `admin` WHERE `level`!='5' AND (`username` LIKE '%$kunci%' OR `nama` LIKE '%$kunci%')");
    $jml = mysqli_num_rows($user_kunci);
    if ($jml>=1)
    {
      echo "<font style=\"font-size:0.625em\"><i>(Klik username untuk edit user)</i></font><p>";
      echo "<table style=\"border:0;box-shadow: 10px 10px 20px rgba(0,0,0,0.4)\">";
      echo "<tr class=\"ganjil\">";
        echo "<td style=\"width:50px;text-align:center\">No</td>";
        echo "<td style=\"width:150px;text-align:center\">Username</td>";
        echo "<td style=\"width:400px;text-align:center\">Nama Dosen/Residen</td>";
        echo "<td style=\"width:300px;text-align:center\">Bagian</td>";
        echo "<td style=\"width:200px;text-align:center\">Level User</td>";
      echo "</tr>";
      $no=1;
      while ($data=mysqli_fetch_array($user_kunci))
      {
        $dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data[username]'"));
        $bagian = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `bagian_ilmu` WHERE `id`='$dosen[kode_bagian]'"));
        $level_user = mysqli_fetch_array(mysqli_query($con,"SELECT `level` FROM `level` WHERE `id`='$data[level]'"));
        echo "<tr class=\"genap\">";
          echo "<td style=\"text-align:center\">$no</td>";
          echo "<td><a href=\"edit_userdosen_action.php?user_name=$data[username]\">$data[username]</a></td>";
          echo "<td>$data[nama], $dosen[gelar]</td>";
          echo "<td>$bagian[bagian]</td>";
          echo "<td style=\"text-align:center\">$level_user[level]</td>";
        echo "</tr>";
        $no++;
      }
      echo "</table>";
    }
    else
    {
      echo "<font style=\"font-size:1.0em;color:red\">Tidak ada user dengan kata kunci \"<i>$_POST[kunci]\" !!!</i></font>";
    }
  }

  if ($_GET['status']=="HAPUS")
  {
    echo "<font style=\"color:red\">Username tersebut telah dihapus!!!</font>";
  }
  if ($_GET['status']=="SIMPAN")
  {
    echo "<font style=\"color:blue\">Username tersebut telah diedit dan disimpan!!!</font>";
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
