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
	include "phpqrcode/qrlib.php";

	error_reporting("E_ALL ^ E_NOTICE");

	if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
	else{
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE[level]==4 OR $_COOKIE[level]==6))
		{
			if ($_COOKIE['level']==4) {include "menu4.php";}
			if ($_COOKIE['level']==6) {include "menu6.php";}

		echo "<div class=\"text_header\">GENERATE QR CODE</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
      <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		if (empty($_POST[qr]))
		{
			$code_gen = acakstring(9);
			$update = mysqli_query($con,"UPDATE `dosen`
				SET `qr`='$code_gen' WHERE `nip`='$_COOKIE[user]'");
			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">GENERATE QR CODE</font></h4>";
    	echo "<br>";
			echo "<font style=\"color:blue\">Generate QR Code untuk approval kegiatan mahasiswa koas terkait.<br></font>";
			echo "<font style=\"color:red\"><br>(Ctt: QR Code berlaku hanya dalam 5 menit, selama ditayangkan. Jika halaman ditutup, maka PIN akan ke-reset!!)</font><br><br><br>";
			echo "<input type=\"submit\" class=\"submit1\" name=\"qr\" value=\"GENERATE\">";
		}

		if ($_POST[qr]=="GENERATE")
		{
			$code_gen = acakstring(9);
			echo "<center><br><br>";
			$tempdir = "qr-image/";
			$imgtemp = $_COOKIE[user].".png";
			QRcode::png($code_gen,$tempdir.$imgtemp,"H",15,1);
			echo "<img src=\"$tempdir$imgtemp\" />";
			$update = mysqli_query($con,"UPDATE `dosen`
				SET `qr`='$code_gen' WHERE `nip`='$_COOKIE[user]'");
			echo "<br><br><font style=\"font-size:1.5em;\">Sisa waktu approval Anda menggunakan QR Code:</font>";
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

				function hitung() {
          	setTimeout(hitung, 1000);
            $('#timer').html(menit + ' menit : ' + detik + ' detik');
            detik--;
            if (detik < 0) {
                detik = 59;
                menit--;
                if (menit < 0) {
                    clearInterval();
										window.location = 'generate_qr.php';
                }
            }
        }
        hitung();
    });
</script>



<!--</body></html>-->
</BODY>
</HTML>
