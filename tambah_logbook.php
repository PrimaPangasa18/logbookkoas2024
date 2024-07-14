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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}
		echo "<div class=\"text_header\">ISI LOGBOOK - PENYAKIT</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ISI LOGBOOK KEPANITERAAN (STASE) - PENYAKIT</font></h4>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		$id_stase = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

		$tgl_mulai = tanggal_indo($datastase_mhsw[tgl_mulai]);
		$tgl_selesai = tanggal_indo($datastase_mhsw[tgl_selesai]);
		$tgl_isi = tanggal_indo($tgl);
		$mulai = date_create($datastase_mhsw[tgl_mulai]);
		$selesai = date_create($datastase_mhsw[tgl_selesai]);
		$sekarang = date_create($tgl);
		$jmlhari_stase = $data_stase[hari_stase];
		$hari_skrg = date_diff($mulai,$sekarang);
		$jmlhari_skrg = $hari_skrg->days+1;
		?>

  	<table style="width:90%;">
      <tr class="ganjil">
          <!-- Kepaniteraan/Stase -->
          <td style="width:30%;" class="td_mid">
            <i>Kepaniteraan/Stase</i>
          </td>
          <td style="width:70%;padding:5px 5px 5px 5px">
            <?php
						echo "<input type=\"hidden\" name=\"stase\" value=\"$id_stase\">";
						echo "<font style=\"font-size:1.0em\">$data_stase[kepaniteraan]</font>";
            ?>
          </td>
      </tr>

      <tr class="ganjil">
          <!-- Tanggal/Hari ke- -->
          <td class="td_mid">
            <i>Tanggal/Hari</i>
          </td>
          <td style="padding:5px 5px 5px 5px">
          	<?php
						echo "<input type=\"hidden\" name=\"jmlhari_skrg\" value=\"$jmlhari_skrg\">";
						echo "<font style=\"font-size:1.0em\">$tgl_isi / Hari ke-$jmlhari_skrg dari $jmlhari_stase hari masa kepaniteraan (stase)</font>";
          	?>
          </td>
      </tr>

			<tr class="ganjil">
	        <!-- Kelas Kegiatan -->
	        <td>
	            <i>Kelas Waktu Kegiatan</i>
							<?
							echo "<br><font style=\"font-size:0.85em\"><i>";
							$kelas = mysqli_query($con,"SELECT `keterangan` FROM `kelas` ORDER BY `id`");
							$no = 1;
							while ($data_kelas=mysqli_fetch_array($kelas))
							{
								echo "<br>$no. $data_kelas[keterangan] WIB";
								$no++;
							}
							echo "<br>(Ctt: Untuk kegiatan melewati batas waktu kelas, buat kegiatan yang sama dengan kelas waktu yang berbeda!)</i></font>";
							?>
	        </td>
	        <td style="padding:5px 5px 5px 5px">
						<div id="my_kelas" role="dialog" class="fix_style">
							<?php
							echo "<select class=\"select_artwide\" name=\"kelas\" id=\"kelas\" required>";
							$kelas = mysqli_query($con,"SELECT * FROM `kelas` ORDER BY `id`");
							echo "<option value=\"\">< Pilihan Kelas Waktu Kegiatan ></option>";
							while ($dat_kelas=mysqli_fetch_array($kelas))
							echo "<option value=\"$dat_kelas[id]\">Kelas $dat_kelas[kelas]</option>";
							echo "</select>";
							echo "<br><br>";
	          	?>
						</div>
						<div id="jam_mulai_selesai">
						</div>
					</td>
	     </tr>

			<tr class="ganjil">
	        <!-- Lokasi -->
	        <td class="td_mid">
	            <i>Lokasi</i>
	        </td>
	        <td style="padding:5px 5px 5px 5px">
						<div id="my_lokasi" role="dialog" class="fix_style">
							<?php
							echo "<select class=\"select_artwide\" name=\"lokasi\" id=\"lokasi\" required>";
							$lokasi = mysqli_query($con,"SELECT * FROM `lokasi` ORDER BY `id`");
							echo "<option value=\"\">< Pilihan Lokasi ></option>";
							while ($dat5=mysqli_fetch_array($lokasi))
							echo "<option value=\"$dat5[id]\">$dat5[lokasi]</option>";
							echo "</select>";
	          	?>
						</div>
	        </td>
	     </tr>

			 <tr class="ganjil">
 	        <!-- Kegiatan -->
 	        <td class="td_mid">
 	            <i>Kegiatan (Level)</i>
 	        </td>
 	        <td style="padding:5px 5px 5px 5px">
						<div id="my_kegiatan" role="dialog" class="fix_style">
							<?php
 							echo "<select class=\"select_artwide\" name=\"kegiatan\" id=\"kegiatan\" required>";
 							$kegiatan = mysqli_query($con,"SELECT * FROM `kegiatan` ORDER BY `id`");
 							echo "<option value=\"\">< Pilihan Kegiatan ></option>";
 							while ($dat5_1=mysqli_fetch_array($kegiatan))
 							echo "<option value=\"$dat5_1[id]\">$dat5_1[kegiatan] [$dat5_1[level]]</option>";
 							echo "</select>";
 	          	?>
						</div>
 	        </td>
 	     </tr>

			<tr class="ganjil">
	         <!-- Penyakit (Level)-->
	         <td colspan=2 class="td_mid">
	           <i>Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal)</i>
	         </td>
			</tr>

			<tr class="genap">
						<td class="td_mid">
							&nbsp;&nbsp;&nbsp;&nbsp;Penyakit - 1
						</td>
						<td style="padding:5px 5px 5px 5px">
							<div id="my_penyakit1" role="dialog" class="fix_style">
 								<?php
								$id_include = "include_".$id_stase;
								$action_penyakit1=mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `$id_include`='1' ORDER BY `penyakit` ASC");
								echo "<select name=\"penyakit1\" id=\"penyakit1\" class=\"select_artwide\" required>";
								echo "<option value=\"\">< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal)></option>";
							  while ($for_peny1=mysqli_fetch_array($action_penyakit1))
							  {
							    echo "<option value=\"$for_peny1[id]\">$for_peny1[penyakit] (Level: $for_peny1[skdi_level]/$for_peny1[k_level]/$for_peny1[ipsg_level]/$for_peny1[kml_level])</option>";
							  }
								echo "</select>";
								?>
							</div>
	          </td>
	     </tr>

			 <tr class="genap">
					<td class="td_mid">
						&nbsp;
					</td>
					<td style="padding:5px 5px 5px 5px">
						 <?php
						 echo "<font style=\"font-size:0.625em\"><i>&nbsp;&nbsp;(Wajib isi)</i></font>";
						 ?>
					</td>
		 </tr>

		 <tr class="genap">
					<td class="td_mid">
						&nbsp;&nbsp;&nbsp;&nbsp;Penyakit - 2
					</td>
					<td style="padding:5px 5px 5px 5px">
						<div id="my_penyakit2" role="dialog" class="fix_style">
								<?php
							$action_penyakit2=mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `$id_include`='1' ORDER BY `penyakit` ASC");
							echo "<select name=\"penyakit2\" id=\"penyakit2\" class=\"select_artwide\">";
							echo "<option value=\"\">< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal)></option>";
							while ($for_peny2=mysqli_fetch_array($action_penyakit2))
							{
								echo "<option value=\"$for_peny2[id]\">$for_peny2[penyakit] (Level: $for_peny2[skdi_level]/$for_peny2[k_level]/$for_peny2[ipsg_level]/$for_peny2[kml_level])</option>";
							}
							echo "</select>";
							?>
						</div>
					</td>
		 </tr>

			 <tr class="genap">
					<td class="td_mid">
						&nbsp;
					</td>
					<td style="padding:5px 5px 5px 5px">
						 <?php
						 echo "<font style=\"font-size:0.625em\"><i>&nbsp;&nbsp;(Tambahan - optional)</i></font>";
						 ?>
					</td>
		 </tr>

		 <tr class="genap">
					<td class="td_mid">
						&nbsp;&nbsp;&nbsp;&nbsp;Penyakit - 3
					</td>
					<td style="padding:5px 5px 5px 5px">
						<div id="my_penyakit3" role="dialog" class="fix_style">
								<?php
							$action_penyakit3=mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `$id_include`='1' ORDER BY `penyakit` ASC");
							echo "<select name=\"penyakit3\" id=\"penyakit3\" class=\"select_artwide\">";
							echo "<option value=\"\">< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal)></option>";
							while ($for_peny3=mysqli_fetch_array($action_penyakit3))
							{
								echo "<option value=\"$for_peny3[id]\">$for_peny3[penyakit] (Level: $for_peny3[skdi_level]/$for_peny3[k_level]/$for_peny3[ipsg_level]/$for_peny3[kml_level])</option>";
							}
							echo "</select>";
							?>
						</div>
					</td>
		 </tr>

			 <tr class="genap">
					<td class="td_mid">
						&nbsp;
					</td>
					<td style="padding:5px 5px 5px 5px">
						 <?php
						 echo "<font style=\"font-size:0.625em\"><i>&nbsp;&nbsp;(Tambahan - optional)</i></font>";
						 ?>
					</td>
		 </tr>

		 <tr class="genap">
					<td class="td_mid">
						&nbsp;&nbsp;&nbsp;&nbsp;Penyakit - 4
					</td>
					<td style="padding:5px 5px 5px 5px">
						<div id="my_penyakit4" role="dialog" class="fix_style">
								<?php
							$action_penyakit4=mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `$id_include`='1' ORDER BY `penyakit` ASC");
							echo "<select name=\"penyakit4\" id=\"penyakit4\" class=\"select_artwide\">";
							echo "<option value=\"\">< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal)></option>";
							while ($for_peny4=mysqli_fetch_array($action_penyakit4))
							{
								echo "<option value=\"$for_peny4[id]\">$for_peny4[penyakit] (Level: $for_peny4[skdi_level]/$for_peny4[k_level]/$for_peny4[ipsg_level]/$for_peny4[kml_level])</option>";
							}
							echo "</select>";
							?>
						</div>
					</td>
		 </tr>

			 <tr class="genap">
					<td class="td_mid">
						&nbsp;
					</td>
					<td style="padding:5px 5px 5px 5px">
						 <?php
						 echo "<font style=\"font-size:0.625em\"><i>&nbsp;&nbsp;(Tambahan - optional)</i></font>";
						 ?>
					</td>
		 </tr>

			 	<tr class="ganjil">
			        <!-- Dosen/Residen-->
			        <td class="td_mid">
								<i>Dosen/Residen</i>
			        </td>
			        <td style="padding:5px 5px 5px 5px">
								<div id="my_dosen" role="dialog" class="fix_style">
								 	<?php
									echo "<select class=\"select_artwide\" name=\"dosen\" id=\"dosen\" required>";
									$dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
									echo "<option value=\"\">< Pilihan Dosen/Residen ></option>";
									while ($dat9=mysqli_fetch_array($dosen))
									{
										$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$dat9[username]'"));
										echo "<option value=\"$dat9[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
									}
									echo "</select></font>";
			          	?>
								</div>
			        </td>
			   </tr>
			</table>
		<?php
		echo "<br><br><input type=\"submit\" class=\"submit1\" name=\"batal\" value=\"BATAL\" formnovalidate />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"tambahkan\" value=\"TAMBAHKAN\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[batal]=="BATAL")
		{
			echo "
				<script>
					window.location.href=\"edit_logbook.php?id=\"+\"$_POST[stase]\";
					</script>
				";
		}

		if ($_POST[tambahkan]=="TAMBAHKAN")
		{
			$jam_awal=$_POST[jam_mulai].":".$_POST[menit_mulai].":"."00";
			$jam_akhir=$_POST[jam_selesai].":".$_POST[menit_selesai].":"."00";
			$jam_mulai_kegiatan = strtotime($jam_awal);
			$kelas = $_POST[kelas];
			$angkatan_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan`,`grup` FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
			$cek_evaluasi = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `evaluasi` WHERE `nim`='$_COOKIE[user]' AND `tanggal`='$tgl'"));
			if ($cek_evaluasi>=1) $evaluasi = 1; else $evaluasi = 0;
			$tambah_db = mysqli_query($con,"INSERT INTO `jurnal_penyakit`
				( `nim`, `angkatan`,`grup`,`hari`,
					`tanggal`, `stase`, `jam_awal`,
					`jam_akhir`, `kelas`,`lokasi`, `kegiatan`,
					 `penyakit1`, `penyakit2`,
					`penyakit3`, `penyakit4`,
					`dosen`,`status`,`evaluasi`)
				VALUES
				( '$_COOKIE[user]','$angkatan_mhsw[angkatan]','$angkatan_mhsw[grup]','$_POST[jmlhari_skrg]',
					'$tgl','$_POST[stase]','$jam_awal',
					'$jam_akhir','$kelas','$_POST[lokasi]','$_POST[kegiatan]',
					'$_POST[penyakit1]','$_POST[penyakit2]',
					'$_POST[penyakit3]','$_POST[penyakit4]',
					'$_POST[dosen]','0','$evaluasi')");
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
<script src="select2/dist/js/select2.js"></script>


<script>
$(document).ready(function() {

	$('#kelas').change(function() {
		var kls = $(this).val();
		$.ajax({
			 type: 'POST',
			 url: 'jam_mulai_selesai.php',
			 data: 'kelas=' + kls,
			 success: function(response) {
				 $('#jam_mulai_selesai').html(response);
			 }
		 });
	 });

	$("#kelas").select2({
		dropdownParent: $('#my_kelas'),
	 	placeholder: "< Pilihan Kelas Waktu Kegiatan >",
	  allowClear: true
	});


	$("#lokasi").select2({
		dropdownParent: $('#my_lokasi'),
	 	placeholder: "< Lokasi >",
	  allowClear: true
	});

	$("#kegiatan").select2({
		dropdownParent: $('#my_kegiatan'),
	 	placeholder: "< Kegiatan >",
	  allowClear: true
	});

	$("#penyakit1").select2({
		 	dropdownParent: $('#my_penyakit1'),
		 	placeholder: "< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
    	allowClear: true
		});

		$("#penyakit2").select2({
			dropdownParent: $('#my_penyakit2'),
		 	placeholder: "< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
      allowClear: true
 		});

		$("#penyakit3").select2({
			dropdownParent: $('#my_penyakit3'),
		 	placeholder: "< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
      allowClear: true
 		});

		$("#penyakit4").select2({
			dropdownParent: $('#my_penyakit4'),
		 	placeholder: "< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
      allowClear: true
 		});

		$("#dosen").select2({
			dropdownParent: $('#my_dosen'),
		 	placeholder: "< Pilihan Dosen/Residen >",
      allowClear: true
		});
});

</script>



<!--</body></html>-->
</BODY>
</HTML>
