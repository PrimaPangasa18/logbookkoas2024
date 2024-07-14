<HTML>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="qr_code.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="text-security-master/dist/text-security.css" type="text/css" media="screen" />
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE[level]==5)
	{
		include "menu5.php";

		echo "<div class=\"text_header\">LOGBOOK APPROVAL</div>";

		$id_logbook = $_GET[id];
		$jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT `stase`,`dosen` FROM `jurnal_penyakit` WHERE `id`='$id_logbook'"));
		$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$jurnal[dosen]'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_logbook\" value=$id_logbook>";
		echo "<input type=\"hidden\" id=\"stase\" name=\"stase\" value=$jurnal[stase]>";
		echo "<br><br><br><br><br><center><<< APPROVAL >>><br><br>";
		echo "<table>";
		echo "<tr class=\"ganjil\"><td>Nama Dosen/Dokter/Residen</td><td>$data_dosen[nama], $data_dosen[gelar]<br>($data_dosen[nip])</td></tr>";
		echo "<tr class=\"genap\"><td>Password Approval</td>";
		echo "<td>";
		?>
		<input type="text" name="dosenpass" class="inputpass" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />
		<?php
		echo "</td></tr>";
		echo "<tr class=\"ganjil\"><td colspan=\"2\">atau</td></tr>";
		echo "<tr class=\"genap\"><td>";
		echo "Masukkan OTP Dosen/Dokter/Residen</td><td>";
		?>
		<input name="dosenpin" autocomplete="off" type="text" style="width:250px"/><br>
		<?php
		echo "</td></tr>";
		echo "<tr class=\"ganjil\"><td colspan=\"2\">atau</td></tr>";
		echo "<tr class=\"genap\"><td>";
		echo "Scanning QR Code<br><font style=\"font-size:0.625em\"><i>(gunakan smartphone)</i></font></td><td>";
		?>
		<input type=text name="dosenqr" size=16 placeholder="Tracking QR-Code" class=qrcode-text style="width:250px" />
		<label class=qrcode-text-btn>
		  <input type=file accept="image/*" capture=environment onchange="openQRCamera(this);" tabindex=-1>
		</label>
		<?php
		echo "</td></tr></table><br><br>";

		echo "<input type=\"submit\" class=\"submit1\" name=\"approve\" value=\"APPROVE\">";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\">";
    echo "</form>";

		if ($_POST[approve]=="APPROVE")
		{
			$jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT `dosen` FROM `jurnal_penyakit` WHERE `id`='$_POST[id_logbook]'"));
			$user_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `password` FROM `admin` WHERE `username`='$jurnal[dosen]'"));
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$jurnal[dosen]'"));
			$dosenpass_md5 = md5($_POST['dosenpass']);
			if (($_POST['dosenpass']!="" AND $dosenpass_md5==$user_dosen['password'])
			 OR ($_POST[dosenpin]!="" AND $_POST[dosenpin]==$data_dosen[pin])
			 OR ($_POST[dosenqr]!="" AND $_POST[dosenqr]==$data_dosen[qr]))
			{
				$update_status = mysqli_query($con,"UPDATE `jurnal_penyakit`
						SET `status`='1' WHERE `id`='$_POST[id_logbook]'");
				echo "<input type=\"hidden\" id=\"idstase\" name=\"idstase\" value=$_POST[stase]>";
				?>
				<script type="text/javascript">
					var stase=document.getElementById("idstase").value;
					alert("Approval SUKSES ...");
					window.location.href = 'edit_logbook.php?id='+stase;
				</script>
				<?php
			}
			else
			{
				echo "<input type=\"hidden\" id=\"idlogbook\" name=\"idlogbook\" value=$_POST[id_logbook]>";
				?>
				<script type="text/javascript">
					var idlog=document.getElementById("idlogbook").value;
					alert("Approval GAGAL ...");
					window.location.href = 'approve.php?id='+idlog;
        </script>
				<?php
			}
		}

		if ($_POST[cancel]=="CANCEL")
		{
			echo "
				<script>
					window.location.href=\"edit_logbook.php?id=\"+\"$_POST[stase]\";
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

<script src="jquery.min.js"></script>
<script src="qr_packed.js"></script>
<script type="text/javascript">
	function openQRCamera(node) {
		var reader = new FileReader();
		reader.onload = function() {
			node.value = "";
			qrcode.callback = function(res) {
				if(res instanceof Error) {
					alert("No QR code found. Please make sure the QR code is within the camera's frame and try again.");
				} else {
					node.parentNode.previousElementSibling.value = res;
				}
			};
			qrcode.decode(reader.result);
		};
		reader.readAsDataURL(node.files[0]);
	}
</script>

<!--</body></html>-->
</BODY>
</HTML>
