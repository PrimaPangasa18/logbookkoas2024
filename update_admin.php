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
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass'])AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2))
{
  if ($_COOKIE['level']==1) {include "menu1.php";}
  if ($_COOKIE['level']==2) {include "menu2.php";}
  echo "<div class=\"text_header\">UPDATE PROFIL</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
  echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">UPDATE PROFIL</font></h4>";


  if (empty($_POST[cancel]) and empty($_POST[simpan]) and empty($_POST[hapus]))
  {
    $data_user = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `admin` WHERE `username`='$_COOKIE[user]'"));
    $dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_user[username]'"));
    $bagian = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `bagian_ilmu` WHERE `id`='$dosen[kode_bagian]'"));
    $level_user = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `level` WHERE `id`='$data_user[level]'"));

  echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
  echo "<table>";
  echo "<tr class=\"ganjil\">";
    echo "<td style=\"width:200px\">";
      echo "Username";
    echo "</td>";
    echo "<td>";
      echo "$data_user[username]";
      echo "<br>&nbsp;<font style=\"font-size:0.625em;font-family:GEORGIA\"><i>(Username tidak bisa diubah, NIP/NIDK/NIM/ID dari user)</i></font>";
      echo "<input type=\"hidden\" name=\"user_name\" value=\"$data_user[username]\" />";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"genap\">";
    echo "<td style=\"width:200px\">";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<i>Password Baru</i>";
    echo "</td>";
    echo "<td>";
      echo "<input type=\"password\" id=\"form-password\" value=\"\" name=\"user_pass\" class=\"select_art\">";
      echo "<br><input type=\"checkbox\" id=\"form-checkbox\">&nbsp;<font style=\"font-size:0.625em\"><i>Show password</i></font>";
      echo "<br>&nbsp;<font style=\"font-size:0.625em;font-family:GEORGIA\"><i>(Jika kosong/tidak diisi, password tidak berubah)</i></font>";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\">";
    echo "<td>";
      echo "Nama Lengkap";
    echo "</td>";
    echo "<td>";
      echo "$dosen[nama]";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"genap\">";
    echo "<td>";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<i>Nama Lengkap Baru</i>";
    echo "</td>";
    echo "<td>";
      echo "<input class=\"select_art\" type=\"text\" name=\"user_surename\" value=\"$dosen[nama]\" >";
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
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<i>Gelar Baru</i>";
    echo "</td>";
    echo "<td>";
      echo "<input class=\"select_art\" type=\"text\" name=\"user_gelar\" value=\"$dosen[gelar]\">";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\">";
    echo "<td>";
      echo "Bagian";
    echo "</td>";
    echo "<td>";
      echo "$bagian[bagian]";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"genap\">";
    echo "<td>";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<i>Bagian Baru</i>";
    echo "</td>";
    echo "<td>";
      echo "<select name=\"bagian\" class=\"select_art\" >";
      $action_bag=mysqli_query($con,"SELECT * FROM `bagian_ilmu` ORDER BY `id` ASC");
		  echo "<option value=\"$bagian[id]\">$bagian[id] - $bagian[bagian]</option>";
			while ($for_bag=mysqli_fetch_array($action_bag))
      {
        echo "<option value=\"$for_bag[id]\">$for_bag[id] - $for_bag[bagian]</option>";
      }
      echo "</select>";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"genap\">";
    echo "<td colspan=2 style=\"text-align:center\">";
      echo "<br><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\">";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"simpan\" value=\"SIMPAN\">";
    echo "<br><br></td>";
  echo "</tr>";
  echo "</table></form>";
  }

  if ($_POST['cancel']=="CANCEL")
  {
    echo "
		<script>
			window.location.href=\"profil_dosen.php\";
		</script>
		";
  }

  if ($_POST['simpan']=="SIMPAN")
  {
    if ($_POST['user_pass']!="")
    {
      $user_password = MD5($_POST['user_pass']);
      $update_admin=mysqli_query($con,"UPDATE `admin`
          SET
          `nama`='$_POST[user_surename]',
          `password`='$user_password',
          `level`='$_COOKIE[level]'
          WHERE `username`='$_POST[user_name]'");
    }
    else {
      $update_admin=mysqli_query($con,"UPDATE `admin`
          SET
          `nama`='$_POST[user_surename]',
          `level`='$_COOKIE[level]'
          WHERE `username`='$_POST[user_name]'");
    }

    $update_dosen=mysqli_query($con,"UPDATE `dosen`
        SET
        `nama`='$_POST[user_surename]',
        `gelar`='$_POST[user_gelar]',
        `kode_bagian`='$_POST[bagian]'
        WHERE `nip`='$_POST[user_name]'");
    echo "
    	<script>
    		window.location.href=\"profil_dosen.php\";
  		</script>
  		";
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
<script type="text/javascript">
	$(document).ready(function(){
		$('#form-checkbox').click(function(){
			if($(this).is(':checked')){
				$('#form-password').attr('type','text');
			}else{
				$('#form-password').attr('type','password');
			}
		});
	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
