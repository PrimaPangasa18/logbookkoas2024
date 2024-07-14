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
include "menu1.php";

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
	echo "<div class=\"text_header\">IMPORT USER</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

	if(isset($_POST['import']) AND !empty($_FILES['import_user_dosen']['tmp_name']))
	{
		$file = $_FILES['import_user_dosen']['tmp_name'];
		$handle = fopen($file, "r");
    $separator = $_POST[separator];
    $no = 0;
    while(($filesop = fgetcsv($handle, 1000, $separator)) !== false)
		{
			if ($no>0)
			{
			$nama = addslashes($filesop[1]);
			$username = $filesop[2];
			$password = MD5($filesop[3]);
			$level = $filesop[4];
			$gelar = $filesop[5];
			$kode_bagian = $filesop[6];
      $jml_username = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `admin` WHERE `username`='$username'"));
      if ($jml_username<1)
      {
        $sql1 = mysqli_query($con,"INSERT INTO `admin`
  				(`nama`, `username`, `password`, `level`)
  				VALUES
  				('$nama','$username','$password','$level')");

  			$sql2 = mysqli_query($con,"INSERT INTO `dosen`
  				(`nip`, `nama`, `gelar`, `kode_bagian`,`pin`,`qr`)
  				VALUES
  				('$username','$nama','$gelar','$kode_bagian','','')");
          echo "<font style=\"color:blue\">Username baru: $username - Password: $username - Nama: $nama</font>";
          if ($sql1) echo " - Status: <font style=\"color:green\">OK</font><br>";
          else
          {
            echo " - Status: <font style=\"color:red\">GAGAL</font><br>";
            $fail++;
          }
      }
      else {
        echo "<font style=\"color:red\">Username [$username] Nama [$nama] sudah ada dalam database!</font><br>";
        $ada++;
      }
  	}
  	$no++;

  	}
  	$no = $no - 1;
    $jml_masuk = $no-$ada;

    echo "<br><br>Proses import data selesai .... !!!<br>";
  	echo "Data dosen telah dimasukkan ke database.<br>";
    echo "Jumlah data yang dimasukkan: $jml_masuk<br>";
    echo "Jumlah data sudah ada dalam database: $ada<br>";
    echo "Jumlah status GAGAL: $fail<br>";

  }
	else
	{
		echo "<br><br>Maaf! Ada kesalahan input file!!!";
	}
	echo "<br><br><form method=POST action=\"$_SERVER[PHP_SELF]\">
		<input type=submit class=\"submit1\" name=back value=BACK>
		</form>";

	if ($_POST['back']=="BACK")
  echo "
		<script>
			window.location.href=\"import_user_dosen.php\";
		</script>
		";
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
