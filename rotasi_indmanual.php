<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
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

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI INDIVIDU KEPANITERAAN (STASE) - TAMBAHAN/PENGGANTI</font></h4><br>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">";

		$nim_mhsw = $_GET[user_name];

		if (empty($_POST[submit]) AND empty($_POST[batal]) AND empty($_POST[simpan]))
		{

		?>
		<table border="0">
			<tr class="ganjil">
	 	 		<td style="padding:5px 5px 5px 5px;width:300px;">
	 		 			<font style="font-size:1.0em">Nama Mahasiswa [NIM]</font>
	 	 		</td>
	 	 		<td style="padding:5px 5px 5px 5px">
	 		 		<?php
	 			 		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw'"));
						echo "$data_mhsw[nama] - NIM: $data_mhsw[nim]";
						echo "<input type=\"hidden\" name=\"nim_mhsw\" value=\"$data_mhsw[nim]\" />";
					?>
		 		</td>
	 	 	</tr>
			<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px">
							<font style="font-size:1.0em">Jenjang Semester Koas</font>
					</td>
					<td style="padding:5px 5px 5px 5px">
							<?php
							echo "<select class=\"select_artwide\" name=\"semester\" id=\"semester\" required>";
							echo "<option value=\"\">< Pilihan Semester ></option>";
							echo "<option value=\"9\">Semester IX (Sembilan)</option>";
							echo "<option value=\"10\">Semester X (Sepuluh)</option>";
							echo "<option value=\"11\">Semester XI (Sebelas)</option>";
							echo "<option value=\"12\">Semester XII (Dua belas)</option>";
							echo "</select>";
							?>
					</td>
		 	</tr>
			<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px">
							<font style="font-size:1.0em">Kepaniteraan (Stase)</font>
					</td>
					<td style="padding:5px 5px 5px 5px">
							<?php
							echo "<select class=\"select_artwide\" name=\"stase\" id=\"stase\" required>";
							echo "<option value=\"\">< Pilihan Kepaniteraan (Stase) ></option>";
							echo "</select>";
							?>
					</td>
		 	</tr>
			<tr class="ganjil">
	 	 		<td style="padding:5px 5px 5px 5px">
	 		 			<font style="font-size:1.0em">Rencana Tanggal Mulai (<i>yyyy-mm-dd</i>)</font>
	 	 		</td>
	 	 		<td style="padding:5px 5px 5px 5px">
	 		 		<?php
	 			 		echo "<input type=\"text\" id=\"input-tanggal\" class=\"select_art\" name=\"tgl_mulai\">";
						echo "<div id=\"tanggal\"></div>";
						echo "<div id=\"input_selesai\">";
							echo "<i>Edit Tanggal Selesai (yyyy-mm-dd):</i><p>";
							echo "<input type=\"text\" id=\"input-selesai\" class=\"select_art\" name=\"tgl_selesai\" placeholder=\"Kosongi jika tidak ada perubahan!\">";
						echo "</div>";
	 		 		?>
		 		</td>
	 	 	</tr>
		</table><br><br>

		<?php

		echo "<input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\" />";
		}

		if (!empty($_POST[submit]) AND empty($_POST[batal]) AND empty($_POST[simpan]))
		{
			echo "<table border=\"0\">";
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px;width:275px;\"><font style=\"font-size:1.0em\">Nama Mahasiswa [NIM]</font></td>";
			$datamhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$_POST[nim_mhsw]'"));
			echo "<td style=\"padding:5px 5px 5px 5px;width:700px;\">$datamhsw[nama] - NIM: $datamhsw[nim]</td>";
			echo "<input type=\"hidden\" name=\"nim_mhsw\" value=\"$datamhsw[nim]\" />";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Semester Stase</font></td>";
			echo "<td style=\"padding:5px 5px 5px 5px\">$_POST[semester]</td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Kepaniteraan (Stase)</font></td>";
			$datastase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$_POST[stase]'"));
			$pekan_stase = $datastase[hari_stase]/7;
			echo "<td style=\"padding:5px 5px 5px 5px\">$datastase[kepaniteraan] - Periode: $pekan_stase pekan ($datastase[hari_stase] hari)</td>";
			echo "<input type=\"hidden\" name=\"stase\" value=\"$datastase[id]\" />";
			echo "</tr>";
			$tanggal_mulai = tanggal_indo($_POST[tgl_mulai]);
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Tanggal mulai kepaniteraan</font></td>";
			echo "<td style=\"padding:5px 5px 5px 5px\">$tanggal_mulai</td>";
			echo "<input type=\"hidden\" name=\"tglmulai\" value=\"$_POST[tgl_mulai]\" />";
			echo "</tr>";
			$hari_tambah = $datastase['hari_stase']-1;
			$tambah_hari = '+'.$hari_tambah.' days';
			$tgl_selesai = date('Y-m-d', strtotime($tambah_hari, strtotime($_POST[tgl_mulai])));
			if (!empty($_POST['tgl_selesai'])) $tgl_selesai = $_POST['tgl_selesai'];
			$tanggal_selesai = tanggal_indo($tgl_selesai);
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Tanggal selesai kepaniteraan</font></td>";
			echo "<td style=\"padding:5px 5px 5px 5px\">$tanggal_selesai</td>";
			echo "<input type=\"hidden\" name=\"tglselesai\" value=\"$tgl_selesai\" />";
			echo "</tr>";

			echo "</table><br><br>";

			echo "<input type=\"submit\" class=\"submit1\" name=\"batal\" value=\"BATAL\" />";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"simpan\" value=\"SIMPAN\" />";
		}


		if ($_POST[batal]=="BATAL")
		{
			echo "
				<script>
					window.location.href=\"rotasi_indmanual_search.php\";
				</script>
				";
		}

		if ($_POST[simpan]=="SIMPAN")
		{
			$stase = "stase_".$_POST[stase];
			$jmldata = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `$stase` WHERE `nim`='$_POST[nim_mhsw]'"));
			if ($jmldata>=1)
			{
				$status_stase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `status` FROM `$stase` WHERE `nim`='$_POST[nim_mhsw]'"));
				if ($status_stase_mhsw[status]=='0')
				{
					$update_stase = mysqli_query($con,"UPDATE `$stase`
						SET
						`rotasi`='9',`tgl_mulai`='$_POST[tglmulai]',
						`tgl_selesai`='$_POST[tglselesai]',`status`='0'
						WHERE `nim`='$_POST[nim_mhsw]'");
				}
				else
				{
					if ($_POST[tgl_mulai]<=$tgl)
					{
						$update_stase = mysqli_query($con,"UPDATE `$stase`
							SET
							`rotasi`='9',`tgl_mulai`='$_POST[tglmulai]',
							`tgl_selesai`='$_POST[tglselesai]',`status`='1'
							WHERE `nim`='$_POST[nim_mhsw]'");
					}
					else {
						$update_stase = mysqli_query($con,"UPDATE `$stase`
							SET
							`rotasi`='9',`tgl_mulai`='$_POST[tglmulai]',
							`tgl_selesai`='$_POST[tglselesai]',`status`='0'
							WHERE `nim`='$_POST[nim_mhsw]'");
					}

				}
			}
			else
			{
				$insert_stase = mysqli_query($con,"INSERT INTO `$stase`
					( `nim`, `rotasi`,
						`tgl_mulai`, `tgl_selesai`, `status`)
					VALUES
					( '$_POST[nim_mhsw]','9',
						'$_POST[tglmulai]','$_POST[tglselesai]','0')");
			}
			echo "<table border=\"0\">";
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px;width:275px;\"><font style=\"font-size:1.0em\">Nama Mahasiswa [NIM]</font></td>";
			$datamhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$_POST[nim_mhsw]'"));
			echo "<td style=\"padding:5px 5px 5px 5px;width:700px;\">$datamhsw[nama] - NIM: $datamhsw[nim]</td>";
			echo "</tr>";
			$datastase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$_POST[stase]'"));
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Semester Stase</font></td>";
			echo "<td style=\"padding:5px 5px 5px 5px\">$datastase[semester]</td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Kepaniteraan (Stase)</font></td>";
			$pekan_stase = $datastase[hari_stase]/7;
			echo "<td style=\"padding:5px 5px 5px 5px\">$datastase[kepaniteraan] - Periode: $pekan_stase pekan ($datastase[hari_stase] hari)</td>";
			echo "</tr>";
			$tanggal_mulai = tanggal_indo($_POST[tglmulai]);
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Tanggal mulai kepaniteraan</font></td>";
			echo "<td style=\"padding:5px 5px 5px 5px\">$tanggal_mulai</td>";
			echo "</tr>";
			$tanggal_selesai = tanggal_indo($_POST[tglselesai]);
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Tanggal selesai kepaniteraan</font></td>";
			echo "<td style=\"padding:5px 5px 5px 5px\">$tanggal_selesai</td>";
			echo "</tr>";

			echo "</table><br><br>";

			echo "<font style=\"color:green; font-size:1.0em;font-family:GEORGIA\"><< Perubahan tersimpan!! >></font>";


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
<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
<script src="select2/dist/js/select2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#input_selesai').hide();
		$('#input-tanggal').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-tanggal').change(function() {
			var tgl = $(this).val();
			var stase = $('#stase').val();
			$.ajax({
				 type: 'POST',
				 url: 'tanggal_view.php',
				 data: {'tgl_mulai':tgl,'stase':stase},
				 success: function(response) {
					 $('#tanggal').html(response);
					 $('#input_selesai').show();
				 }
			 });
		 });
		$('#input-selesai').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#semester').change(function() {
			var smt = $(this).val();
			$.ajax({
				 type: 'POST',
				 url: 'semester_stase_manual.php',
				 data: 'semester=' + smt,
				 success: function(response) {
					 $('#stase').html(response);
				 }
			 });
		 });

		 $("#semester").select2({
				placeholder: "< Pilihan Semester >"
			});

		 $("#stase").select2({
			 placeholder: "< Pilihan Kepaniteraan (Stase) >"
		 });




	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
