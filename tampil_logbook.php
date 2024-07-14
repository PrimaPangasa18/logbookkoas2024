<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}
		
		echo "<div class=\"text_header\">CEK LOGBOOK</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">CEK LOGBOOK KEPANITERAAN (STASE)</font></h4><br>";

		$id_stase = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"stase\" value=\"$id_stase\">";
		echo "<table border=\"0\">";
		echo "<tr>";
		echo "<td>Kepaniteraan (stase)</td><td>: $data_stase[kepaniteraan]</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Masukkan tanggal kegiatan (yyyy-mm-dd)</td><td>: <input type=\"text\" class=\"input-tanggal\" name=\"tgl_kegiatan\" style=\"font-size:1em;font-family:GEORGIA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" /></td>";
		echo "</tr>";
		echo "</table>";
		echo "<br><br><input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\">";
		echo "</form>";

		if ($_POST[submit]=="SUBMIT")
		{
			$tahun = substr($_POST[tgl_kegiatan], 0, 4);
			$bulan = substr($_POST[tgl_kegiatan], 5, 2);
			$tanggal = substr($_POST[tgl_kegiatan], 8, 2);
			$tanggal_input = $tahun."-".$bulan."-".$tanggal;

			echo "
				<script>
					window.location.href=\"tampiltgl_logbook.php?id=\"+\"$_POST[stase]\"+\"&tglkeg=\"+\"$tanggal_input\";
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
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.input-tanggal').datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
