<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
<!--</head>-->
</head>
<BODY>

<?php
	
	include "config.php";
	include "fungsi.php";

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

		echo "<div class=\"text_header\">GENERATE OTP DOSEN/RESIDEN</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
      <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		$user_dosen = $_GET[user_name];
		if (empty($_POST[pin]))
		{
			echo "<input type=\"hidden\" name=\"userdosen\" value=\"$user_dosen\" />";
			$pin_gen = rand(1000,9999);
			$update = mysqli_query($con,"UPDATE `dosen`
				SET `pin`='$pin_gen' WHERE `nip`='$user_dosen'");
			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">GENERATE OTP DOSEN/RESIDEN</font></h4>";
    	echo "<br>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$user_dosen'"));
			echo "<table>";
      echo "<tr>";
        echo "<td>";
        echo "Nama Dosen/Residen";
        echo "</td>";
				echo "<td>";
        echo ": $data_dosen[nama], $data_dosen[gelar]";
        echo "</td>";
      echo "</tr>";
			echo "<tr>";
				echo "<td>";
				echo "Username Dosen/Residen";
				echo "</td>";
				echo "<td>";
        echo ": $data_dosen[nip]";
        echo "</td>";
      echo "</tr>";
			echo "</table><br><br>";

			echo "<font style=\"color:blue\">Generate OTP dosen/residen untuk approval kegiatan mahasiswa koas terkait.<br></font>";
			echo "<font style=\"color:red\"><br>(Ctt: OTP berlaku hanya dalam 5 menit, selama ditayangkan. Jika halaman ditutup, maka OTP akan ke-reset!!)</font><br><br><br>";
			echo "<input type=\"submit\" class=\"submit1\" name=\"pin\" value=\"GENERATE\">";
		}

		if ($_POST[pin]=="GENERATE")
		{
			$pin_gen = rand(1000,9999);
			echo "<input type=\"hidden\" id=\"user_dosen\" name=\"user_dosen\" value=\"$_POST[userdosen]\"/>";
			echo "<br><br><center><table style=\"border:2px solid black\">";
			echo "<tr><td align=center style=\"width:200px\"><br><font style=\"color:blue;font-size:50px;font-family:arial;font-weight:bold\">$pin_gen</font><br><br></td></tr>";
			echo "</table>";
			$update = mysqli_query($con,"UPDATE `dosen`
				SET `pin`='$pin_gen' WHERE `nip`='$_POST[userdosen]'");
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$_POST[userdosen]'"));
			echo "<center><br><br><font style=\"font-size:1.5em;\">(Dosen/Residen: $data_dosen[nama], $data_dosen[gelar])<br>Sisa waktu approval menggunakan OTP:<br></font>";
			?>
			<br><br><font style="font-size:30px;color:red"><div id="timer"></div></font>
			<?php
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
<script type="text/javascript">
$(document).ready(function() {
        var detik = 0;
        var menit = 5;
				var user_dosen = document.getElementById('user_dosen').value;

				function hitung() {
          	setTimeout(hitung, 1000);
            $('#timer').html(menit + ' menit : ' + detik + ' detik');
            detik--;
            if (detik < 0) {
                detik = 59;
                menit--;
                if (menit < 0) {
                    clearInterval();
										window.location = 'generate_pin_admin.php?user_name='+user_dosen;
                }
            }
        }
        hitung();
    });
</script>



<!--</body></html>-->
</BODY>
</HTML>
