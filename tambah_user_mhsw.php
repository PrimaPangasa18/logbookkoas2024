<HTML>
<head>
    <meta charset="utf-8">
    <title>::Tambah User::</title>
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
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass'])AND $_COOKIE['level']==1)
{
  if ($_COOKIE['level']==1) {include "menu1.php";}
  echo "<div class=\"text_header\">TAMBAH USER</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
  echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">TAMBAH USER MAHASISWA</font></h4><br>";

  echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
  echo "<table border=\"0\">";
  echo "<tr class=\"ganjil\">";
    echo "<td style=\"width:200px\">";
      echo "New username (NIM)";
    echo "</td>";
    echo "<td>";
      echo "<input type=\"text\" name=\"user_name\" class=\"select_art\" required>";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\">";
    echo "<td>";
      echo "Nama Lengkap";
    echo "</td>";
    echo "<td>";
      echo "<input type=\"text\" name=\"user_surename\" class=\"select_art\" required>";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\">";
    echo "<td>";
      echo "Password";
    echo "</td>";
    echo "<td>";
      echo "<input type=\"password\" id=\"form-password\" name=\"user_pass\" class=\"select_art\" required>";
      echo "<br><input type=\"checkbox\" id=\"form-checkbox\"> &nbsp;<font style=\"font-size:0.625em\"><i>Show password</i></font>";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\">";
    echo "<td colspan=2 align=\"center\">";
      echo "<br><input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\">";
    echo "<br><br></td>";
  echo "</tr>";
  echo "</table></form>";


  if ($_POST['submit']=="SUBMIT")
  {
    $jml_username = mysqli_num_rows(mysqli_query($con,"SELECT `username` FROM `admin` WHERE `username`='$_POST[user_name]'"));
    if ($jml_username>=1)
    {
      echo "
        <script type=\"text/javascript\">
          alert(\"Username (NIP) tersebut sudah ada dalam data user!!!\");
          window.location.href = \"tambah_user.php\";
        </script>";
    }
    else
    {
      $user_password = MD5($_POST['user_pass']);
      $nama = addslashes($_POST[user_surename]);
      $tambah_admin=mysqli_query($con,"INSERT INTO `admin`
        (`nama`, `username`, `password`, `level`)
        VALUES
        ('$nama','$_POST[user_name]','$user_password','5')");
      $tambah_mhsw=mysqli_query($con,"INSERT INTO `biodata_mhsw`
				( `nama`, `nim`, `kota_lahir`, `prop_lahir`, `tanggal_lahir`,
					`alamat`, `kota_alamat`, `prop_alamat`, `no_hp`, `email`,
					`nama_ortu`, `alamat_ortu`, `kota_ortu`, `prop_ortu`, `no_hportu`,
					`foto`,`dosen_wali`)
				VALUES
				(	'$nama','$_POST[user_name]','','','2001-01-01',
					'','','','','',
					'','','','','',
					'profil_blank.png','')");
      echo "<center><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style=\"color:green\">User baru berhasil ditambahkan ...</font>";
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
