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
		echo "<div class=\"text_header\">EDIT ROTASI INTERNAL</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT ROTASI INTERNAL</font></h4>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		$id_stase = $_GET['stase'];
		$id_i = $_GET['id_i'];
		$id_internal = "internal_".$id_stase;
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
		echo "<input type=\"hidden\" name=\"id_internal\" value=\"$id_internal\">";
		echo "<input type=\"hidden\" name=\"id_i\" value=\"$id_i\">";
		echo "<table style=\"border:collapse;\">";
			echo "<tr><td>Kepaniteraan (stase)</td><td>: $data_stase[kepaniteraan]</td></tr>";
			$tgl_mulai = tanggal_indo($datastase_mhsw[tgl_mulai]);
			$tgl_selesai = tanggal_indo($datastase_mhsw[tgl_selesai]);
			echo "<tr><td>Tanggal mulai kepaniteraan (stase)</td><td>: $tgl_mulai</td></tr>";
			echo "<tr><td>Tanggal selesai kepaniteraan (stase)</td><td>: $tgl_selesai</td></tr>";
		echo "</table><br>";

		$internal_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$id_internal` WHERE `nim`='$_COOKIE[user]'"));
		$id_rotasi = $id_stase.$id_i;
		$data_rotasi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `rotasi_internal` WHERE `id`='$id_rotasi'"));
		$id_dosen = "dosen".$id_i;
		$id_tgl = "tgl".$id_i;
		echo "<table style=\"width:90%\">";
		echo "<tr class=\"ganjil\">";
			echo "<td style=\"width:30%\">Internal Stase</td>";
			echo "<td style=\"width:70%\">$data_rotasi[internal]";
			echo "</td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
			echo "<td>Lama Pelaksanaan</td>";
			echo "<td>$data_rotasi[hari] hari";
			echo "</td>";
		echo "</tr>";
		echo "<tr class=\"genap\">";
			echo "<td>Tanggal Mulai (yyyy-mm-dd)</td>";
			echo "<td><input type=\"text\" class=\"input-tanggal\" name=\"tgl_mulai\" value=\"$internal_stase[$id_tgl]\" style=\"font-size:1em;font-family:GEORGIA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" required/>";
			echo "</td>";
		echo "</tr>";
		echo "<tr class=\"genap\">";
			echo "<td>Approval oleh</td>";
			echo "<td>";
				echo "<div id=\"my_dosen\" role=\"dialog\" class=\"fix_style\">";
				echo "<select class=\"select_artwide\" name=\"dosen\" id=\"dosen\" required>";
				$dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
				echo "<option value=\"\">< Pilihan Dosen/Residen/Petugas ></option>";
				while ($dat=mysqli_fetch_array($dosen))
				{
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$dat[username]'"));
					echo "<option value=\"$dat[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
				}
				echo "</select></font>";
				echo "</div>";
			echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "<br><br><input type=\"submit\" class=\"submit1\" name=\"batal\" value=\"BATAL\" formnovalidate />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[batal]=="BATAL")
		{
			echo "
			<script>
				window.location.href=\"internal_stase.php?id=\"+\"$_POST[id_stase]\";
			</script>
			";
		}

		if ($_POST[submit]=="SUBMIT")
		{
			$id_tgl="tgl".$_POST[id_i];
			$id_dosen="dosen".$_POST[id_i];
			$id_status="status".$_POST[id_i];
			$update_internal = mysqli_query($con,"UPDATE `$_POST[id_internal]` SET
				`$id_tgl`='$_POST[tgl_mulai]',`$id_dosen`='$_POST[dosen]',`$id_status`='0'
				WHERE `nim`='$_COOKIE[user]'");
			echo "
			<script>
				window.location.href=\"internal_stase.php?id=\"+\"$_POST[id_stase]\";
			</script>
			";
		}
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
		$('.input-tanggal').datepicker({ dateFormat: 'yy-mm-dd' });
		$("#dosen").select2({
			dropdownParent: $('#my_dosen'),
		 	placeholder: "< Pilihan Dosen/Residen/Petugas >",
      allowClear: true
		});
	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
