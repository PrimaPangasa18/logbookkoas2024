<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="select2/dist/css/select2.css"/>
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==1)
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}

		echo "<div class=\"text_header\">ROTASI KEPANITERAAN (STASE)</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI KELOMPOK KEPANITERAAN (STASE) - HAPUS ROTASI</font></h4><br>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		$stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
		?>
		<table border="0">
			<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px;width:300px;">
							<font style="font-size:1.0em">Kepaniteraan (Stase)</font>
					</td>
					<td style="padding:5px 5px 5px 5px">
							<?php
							echo "<select class=\"select_artwide\" name=\"stase\" id=\"stase\" required>";
							if (empty($_POST[stase]) AND empty($_GET[get_stase])) echo "<option value=\"\">< Pilihan Kepaniteraan (Stase) ></option>";
							else
							{
								if (empty($_GET[get_stase]))
								{
									$stase_pilihan = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$_POST[stase]'"));
									echo "<option value=\"$_POST[stase]\">$stase_pilihan[kepaniteraan]</option>";
								}
								if (empty($_POST[stase]))
								{
									$stase_pilihan = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$_GET[get_stase]'"));
									echo "<option value=\"$_GET[get_stase]\">$stase_pilihan[kepaniteraan]</option>";
								}

							}
							while ($dat_stase=mysqli_fetch_array($stase))
							{
								echo "<option value=\"$dat_stase[id]\">$dat_stase[kepaniteraan] - (Semester: $dat_stase[semester] | Periode: $dat_stase[hari_stase] hari)</option>";
							}
							echo "</select>";
							?>
					</td>
		 	</tr>

		 	</table><br><br>



		<input type="submit" class="submit1" name="submit" value="SUBMIT" />


		<?php


		if ($_POST[submit]=="SUBMIT" OR $_GET[get_submit]=="SUBMIT")
		{
			if ($_POST[submit]=="SUBMIT") $stase_id="stase_".$_POST['stase'];
			if ($_GET[get_submit]=="SUBMIT") $stase_id="stase_".$_GET['get_stase'];
			$stase_all = mysqli_query($con,"SELECT DISTINCT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` ORDER BY `tgl_mulai`");
		  $no = 1;
			$kelas = "GANJIL";
			echo "<br><br><table style=\"width:100%\">";
			echo "<thead>";
			echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:25%\">Tanggal Mulai</th>";
			echo "<th style=\"width:25%\">Tanggal Selesai</th>";
			echo "<th style=\"width:20%\">Jumlah Peserta Koas</th>";
			echo "<th style=\"width:25%\">Status</th>";
			while ($data = mysqli_fetch_array($stase_all))
			{
				echo "<tr class=$kelas>";
				echo "<td align=\"center\">$no</td>";
				$tanggal_mulai=tanggal_indo($data[tgl_mulai]);
				echo "<td align=\"center\">$tanggal_mulai</td>";
				$tanggal_selesai=tanggal_indo($data[tgl_selesai]);
				echo "<td align=\"center\">$tanggal_selesai</td>";
				$jml_mhsw = mysqli_num_rows(mysqli_query($con,"SELECT `nim` FROM `$stase_id` WHERE `tgl_mulai`='$data[tgl_mulai]'"));
				echo "<td align=\"center\">$jml_mhsw</td>";
				if (strtotime($tgl)>strtotime($data[tgl_mulai]))
				{
					if (strtotime($tgl)<strtotime($data[tgl_selesai]))
					{
						echo "<td align=\"center\"><font style=\"color:green\"><i>Sedang Berjalan</i></font></td>";
					}
					if (strtotime($tgl)>strtotime($data[tgl_selesai]))
					{
						echo "<td align=\"center\"><font style=\"color:blue\"><i>Sudah Lewat</i></font></td>";
					}
				}
				else
				{
					echo "<td align=\"center\"><font style=\"color:red\"><i>Belum Dimulai</i></font><br>";
					echo "<a href=\"rotasi_delete.php?stase=$_POST[stase]&tgl_mulai=$data[tgl_mulai]\"><input type=\"button\" name=\"delete\" style=\"color:red\" value=\"DELETE\"></a></td>";
				}
				echo "</tr>";
				if ($kelas=="GANJIL") $kelas = "GENAP";
				else $kelas = "GANJIL";
				$no++;
			}
			echo "</table>";
		}

		echo "</form>";
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
<script src="select2/dist/js/select2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#stase").select2({
				placeholder: "< Pilihan Kepaniteraan (Stase) >"
			});
	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
