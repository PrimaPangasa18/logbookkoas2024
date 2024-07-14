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

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI KELOMPOK KEPANITERAAN (STASE) - NORMAL</font></h4><br>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">";

		if (empty($_POST[submit]))
		{
		$stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
		?>
		<table border="0">
			<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px;width:300px;">
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
	 		 			<font style="font-size:1.0em">Import Data Koas</font>
	 	 		</td>
	 	 		<td style="padding:5px 5px 5px 5px">
	 		 		<?php
	 			 		echo "<input type=\"file\" id=\"daftar_koas\" name=\"daftar_koas\" accept=\".csv\" required><br><br>";
						echo "<font style=\"font-size:0.75em\"><i>Import file dalam format <i>csv</i> (*.csv) dengan separator ( , ) atau ( ; ) => no - nim - nama</i></font>";
	 		 		?>
		 		</td>
	 	 	</tr>

			<tr class="ganjil">
	 	 		<td style="padding:5px 5px 5px 5px">
	 		 			<font style="font-size:1.0em">Separator file csv:<br><i>koma --> ( , ) atau titik koma --> ( ; )</i></font>
	 	 		</td>
	 	 		<td style="padding:5px 5px 5px 5px">
	 		 		<?php
	 			 		echo "<select class=\"select_art\" id=\"separator\" name=\"separator\" required>";
						echo "<option value=\"\">< Pilihan Separator ></option>";
						echo "<option value=\",\">Koma --> ( , )</option>";
						echo "<option value=\";\">Titik Koma --> ( ; )</option>";
						echo "</select>";
	 		 		?>
		 		</td>
	 	 	</tr>


		</table><br>
		<div id="stase">
		</div><br><br>




		<?php

		echo "<input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\">";


		}

		if (!empty($_POST[submit]))
		{
			$delete_dummy_mhsw = mysqli_query($con,"DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
			$file = $_FILES['daftar_koas']['tmp_name'];
			$handle = fopen($file, "r");
			$separator = $_POST[separator];
			$id = 0;
			while(($filesop = fgetcsv($handle, 1000, $separator)) !== false)
			{
				if ($id>0 and $filesop[0]!="")
				{
					$nim = $filesop[1];
					$nama = $filesop[2];
					$insert_temp = mysqli_query($con,"INSERT INTO `daftar_koas_temp`
						(`id`, `nim`, `nama`,`username`)
						VALUES ('$id','$nim','$nama','$_COOKIE[user]')");
				}
				if ($filesop[0]!="") $id++;
			}

			echo "<table border=\"0\" style=\"width:75%\">";
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px;width:40%;\"><font style=\"font-size:1.0em\">Rotasi stase semester</font></td>";
			echo "<td style=\"padding:5px 5px 5px 5px;width:60%;\">$_POST[semester]</td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
			echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Jumlah rotasi stase</font></td>";
			echo "<td style=\"padding:5px 5px 5px 5px\">$_POST[jml_stase]</td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
			echo "<td colspan=\"2\" style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Urutan rotasi kepaniteraan (stase):</font></td>";
			echo "</tr>";
			$no = 1;
			$tgl_selesai_stase = "2000-01-01";
			while ($no<=$_POST[jml_stase])
			{
				$stase = $_POST['stase'.$no];
				$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$stase'"));
				$pekan_stase = $data_stase[hari_stase]/7;
				$tgl_mulai_stase = $_POST['tgl_mulai'.$no];
				$tglmulai_stase = tanggal_indo($tgl_mulai_stase);
				$hari_tambah = $data_stase['hari_stase']-1;
				$tambah_hari = '+'.$hari_tambah.' days';
				$tgl_selesai_stase = date('Y-m-d', strtotime($tambah_hari, strtotime($tgl_mulai_stase)));
				if (!empty($_POST['tgl_selesai'.$no])) $tgl_selesai_stase = $_POST['tgl_selesai'.$no];
				$tglselesai_stase = tanggal_indo($tgl_selesai_stase);


				echo "<tr class=\"genap\">";
				echo "<td style=\"padding:5px 5px 5px 15px\"><font style=\"font-size:1.0em\">Urutan ke-$no</font></td>";
				if ($stase!="")
				{
					echo "<td style=\"padding:5px 5px 5px 5px\"><b>$data_stase[kepaniteraan] - Periode: $pekan_stase pekan ($data_stase[hari_stase] hari)</b><br>";
					echo "<i>Mulai tanggal: $tglmulai_stase<br>";
					echo "Selesai tanggal: $tglselesai_stase</i></td>";
					$data_mhsw_koas = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
					while ($data_mhsw=mysqli_fetch_array($data_mhsw_koas))
					{
						$stase_id = "stase_".$stase;
						$jml_akun = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `admin` WHERE `username`='$data_mhsw[nim]'"));
						$jml_mhsw = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));

						if ($jml_akun>=1)
						{
							if ($jml_mhsw>=1)
							{
								$status_stase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `status` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
								if ($status_stase_mhsw[status]=='0')
								{
									$update_stase = mysqli_query($con,"UPDATE `$stase_id`
										SET
										`rotasi`='$no',`tgl_mulai`='$tgl_mulai_stase',`tgl_selesai`='$tgl_selesai_stase',`status`='0'
										WHERE `nim`='$data_mhsw[nim]'");
								}
								else
								{
									if ($tgl_mulai_stase<=$tgl)
									{
										$update_stase = mysqli_query($con,"UPDATE `$stase_id`
											SET
											`rotasi`='$no',`tgl_mulai`='$tgl_mulai_stase',`tgl_selesai`='$tgl_selesai_stase',`status`='1'
											WHERE `nim`='$data_mhsw[nim]'");
									}
									else
									{
										$update_stase = mysqli_query($con,"UPDATE `$stase_id`
											SET
											`rotasi`='$no',`tgl_mulai`='$tgl_mulai_stase',`tgl_selesai`='$tgl_selesai_stase',`status`='0'
											WHERE `nim`='$data_mhsw[nim]'");
									}

								}
							}
							else
							{
								$insert_stase = mysqli_query($con,"INSERT INTO `$stase_id`
									( `nim`, `rotasi`,
										`tgl_mulai`, `tgl_selesai`, `status`)
									VALUES
									( '$data_mhsw[nim]','$no',
									'$tgl_mulai_stase','$tgl_selesai_stase','0')");
							}
						}
					}
				}
				else {
					echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"color:red\"><< BELUM TERJADWAL >></font></td>";
				}

				echo "</tr>";


				$no++;
			}
			echo "</table><br><br>";

			echo "<h5>Daftar Mahasiswa Peserta Rotasi Kepaniteraan (Stase)</h5>";
			echo "<table style=\"width:100%\" id=\"freeze\">";
			echo "<thead>";
				echo "<th style=\"width:7%\">No</th>";
				echo "<th style=\"width:13%\">NIM</th>";
				echo "<th style=\"width:55%\">Nama</th>";
				echo "<th style=\"width:25%\">Status</th>";
			echo "</thead>";

			$daftar_koas = mysqli_query($con,"SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]' ORDER BY `nama`");
			$kelas = "ganjil";
			$no = 1;
			while ($dat_ko=mysqli_fetch_array($daftar_koas))
			{
				echo "<tr class=$kelas>";
				echo "<td align=\"center\">$no</td>";
				echo "<td>$dat_ko[nim]</td>";
				echo "<td>$dat_ko[nama]</td>";
				$jml_akun = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `admin` WHERE `username`='$dat_ko[nim]'"));
				if ($jml_akun>=1)
				echo "<td align=center><font style=\"color:green\">Terdaftar</font></td>";
				else
				echo "<td align=center><font style=\"color:red\">Belum punya akun</font></td>";
				echo "</tr>";
				if ($kelas=="genap") $kelas="ganjil";
				else $kelas="genap";
				$no++;
			}
			echo "</table>";
			$delete_dummy_mhsw = mysqli_query($con,"DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
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
<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		$('#input-tanggal').datepicker({ dateFormat: 'yy-mm-dd' });

		$('#semester').change(function() {
			var smt = $(this).val();
			$.ajax({
				 type: 'POST',
				 url: 'semester_stase.php',
				 data: 'semester=' + smt,
				 success: function(response) {
					 $('#stase').html(response);
				 }
			 });
		 });

		$("#semester").select2({
				placeholder: "< Pilihan Semester >",
				allowClear: true
			});
		$("#separator").select2({
 				placeholder: "< Pilihan Separator >",
 				allowClear: true
 			});
		$("#freeze").freezeHeader();



	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
