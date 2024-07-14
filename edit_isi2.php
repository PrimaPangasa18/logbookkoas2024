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
		echo "<div class=\"text_header\">EDIT ISI LOGBOOK - KETRAMPILAN KLINIK</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT ISI LOGBOOK KEPANITERAAN (STASE) - KETRAMPILAN KLINIK</font></h4>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		$id_logbook = $_GET['id'];
		echo "<input type=\"hidden\" name=\"id_logbook\" value=\"$id_logbook\">";
		$data_logbook = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `jurnal_ketrampilan` WHERE `id`='$id_logbook'"));
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$data_logbook[stase]'"));
		$stase_id = "stase_".$data_logbook[stase];
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
						echo "<input type=\"hidden\" name=\"stase\" value=\"$data_logbook[stase]\">";
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
							if ($data_logbook[kelas]=="1") {$awal=0; $akhir=6;}
							if ($data_logbook[kelas]=="2") {$awal=7; $akhir=9;}
							if ($data_logbook[kelas]=="3") {$awal=10; $akhir=11;}
							if ($data_logbook[kelas]=="4") {$awal=12; $akhir=13;}
							if ($data_logbook[kelas]=="5") {$awal=14; $akhir=15;}
							if ($data_logbook[kelas]=="6") {$awal=16; $akhir=23;}

							echo "<select class=\"select_artwide\" name=\"kelas\" id=\"kelas\" required>";
							$datkelas = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kelas` WHERE `id`='$data_logbook[kelas]'"));
							echo "<option value=\"$data_logbook[kelas]\">Kelas $datkelas[kelas]</option>";
							$kelas = mysqli_query($con,"SELECT * FROM `kelas` ORDER BY `id`");
							while ($dat_kelas=mysqli_fetch_array($kelas))
							echo "<option value=\"$dat_kelas[id]\">Kelas $dat_kelas[kelas]</option>";
							echo "</select>";
							echo "<br><br>";
							$data_jam_mulai = substr($data_logbook[jam_awal], 0, 2);
							$data_menit_mulai = substr($data_logbook[jam_awal], 3, 2);
							$data_jam_selesai = substr($data_logbook[jam_akhir], 0, 2);
							$data_menit_selesai = substr($data_logbook[jam_akhir], 3, 2);
							echo "<div id=\"hide_jam\"><i>Jam Mulai/Selesai Kegiatan:</i><p>";
							echo "<table class=\"tabel_normal\" style=\"width:90%\">";
							echo "<tr>";
								echo "<td style=\"width:20%\">Jam Mulai</td>";
								echo "<td style=\"width:30%\">: <input type=\"number\" step=\"1\" min=\"$awal\" max=\"$akhir\" name=\"jam_mulai\" style=\"width:100px;font-size:0.85em;text-align:center\" value=\"$data_jam_mulai\" required/></td>";
								echo "<td style=\"width:20%\">Menit Mulai</td>";
								echo "<td style=\"width:30%\">: <input type=\"number\" step=\"1\" min=\"0\" max=\"59\" name=\"menit_mulai\" style=\"width:100px;font-size:0.85em;text-align:center\" value=\"$data_menit_mulai\" required/></td>";
							echo "</tr>";
							//Jam selesai
							echo "<tr>";
								echo "<td style=\"width:20%\">Jam Selesai</td>";
								echo "<td style=\"width:30%\">: <input type=\"number\" step=\"1\" min=\"$awal\" max=\"$akhir\" name=\"jam_selesai\" style=\"width:100px;font-size:0.85em;text-align:center\" value=\"$data_jam_selesai\" required/></td>";
								echo "<td style=\"width:20%\">Menit Selesai</td>";
								echo "<td style=\"width:30%\">: <input type=\"number\" step=\"1\" min=\"0\" max=\"59\" name=\"menit_selesai\" style=\"width:100px;font-size:0.85em;text-align:center\" value=\"$data_menit_selesai\" required/></td>";
							echo "</tr>";
							echo "</table></div>";
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
							$data_lokasi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `lokasi` WHERE `id`='$data_logbook[lokasi]'"));
							$lokasi = mysqli_query($con,"SELECT * FROM `lokasi` ORDER BY `id`");
							echo "<option value=\"$data_lokasi[id]\">$data_lokasi[lokasi]</option>";
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
							$data_kegiatan = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kegiatan` WHERE `id`='$data_logbook[kegiatan]'"));
 							$kegiatan = mysqli_query($con,"SELECT * FROM `kegiatan` ORDER BY `id`");
 							echo "<option value=\"$data_kegiatan[id]\">$data_kegiatan[kegiatan] [$data_kegiatan[level]]</option>";
 							while ($dat5_1=mysqli_fetch_array($kegiatan))
 							echo "<option value=\"$dat5_1[id]\">$dat5_1[kegiatan] [$dat5_1[level]]</option>";
 							echo "</select>";
 	          	?>
						</div>
 	        </td>
 	     </tr>

			 <tr class="ganjil">
 	         <!-- Ketrampilan (Level)-->
 	         <td colspan=2 class="td_mid">
 	           <i>Ketrampilan (Level SKDI/Kepmenkes/IPSG)</i>
 	         </td>
 			</tr>

			<tr class="genap">
 						<td class="td_mid">
 							&nbsp;&nbsp;&nbsp;&nbsp;Ketrampilan - 1
 						</td>
 						<td style="padding:5px 5px 5px 5px">
 							<div id="my_ketrampilan1" role="dialog" class="fix_style">
								<?php
								$id_include = "include_".$data_logbook[stase];
								$data_ketrampilan1 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `id`='$data_logbook[ketrampilan1]'"));
								$action_ketrampilan1=mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `$id_include`='1' ORDER BY `ketrampilan` ASC");
								echo "<select name=\"ketrampilan1\" id=\"ketrampilan1\" class=\"select_artwide\" required>";
								echo "<option value=\"$data_ketrampilan1[id]\">$data_ketrampilan1[ketrampilan] (Level: $data_ketrampilan1[skdi_level]/$data_ketrampilan1[k_level]/$data_ketrampilan1[ipsg_level]/$data_ketrampilan1[kml_level])</option>";
							  while ($for_ket1=mysqli_fetch_array($action_ketrampilan1))
							  {
							    echo "<option value=\"$for_ket1[id]\">$for_ket1[ketrampilan] (Level: $for_ket1[skdi_level]/$for_ket1[k_level]/$for_ket1[ipsg_level]/$for_ket1[kml_level])</option>";
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
						&nbsp;&nbsp;&nbsp;&nbsp;Ketrampilan - 2
					</td>
					<td style="padding:5px 5px 5px 5px">
						<div id="my_ketrampilan2" role="dialog" class="fix_style">
							<?php
							$data_ketrampilan2 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `id`='$data_logbook[ketrampilan2]'"));
							$action_ketrampilan2=mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `$id_include`='1' ORDER BY `ketrampilan` ASC");
							echo "<select name=\"ketrampilan2\" id=\"ketrampilan2\" class=\"select_artwide\">";
							echo "<option value=\"$data_ketrampilan2[id]\">$data_ketrampilan2[ketrampilan] (Level: $data_ketrampilan2[skdi_level]/$data_ketrampilan2[k_level]/$data_ketrampilan2[ipsg_level]/$data_ketrampilan2[kml_level])</option>";
							while ($for_ket2=mysqli_fetch_array($action_ketrampilan2))
							{
								echo "<option value=\"$for_ket2[id]\">$for_ket2[ketrampilan] (Level: $for_ket2[skdi_level]/$for_ket2[k_level]/$for_ket2[ipsg_level]/$for_ket2[kml_level])</option>";
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
					 &nbsp;&nbsp;&nbsp;&nbsp;Ketrampilan - 3
				 </td>
				 <td style="padding:5px 5px 5px 5px">
					 <div id="my_ketrampilan3" role="dialog" class="fix_style">
						 <?php
						 $data_ketrampilan3 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `id`='$data_logbook[ketrampilan3]'"));
						 $action_ketrampilan3=mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `$id_include`='1' ORDER BY `ketrampilan` ASC");
						 echo "<select name=\"ketrampilan3\" id=\"ketrampilan3\" class=\"select_artwide\">";
						 echo "<option value=\"$data_ketrampilan3[id]\">$data_ketrampilan3[ketrampilan] (Level: $data_ketrampilan3[skdi_level]/$data_ketrampilan3[k_level]/$data_ketrampilan3[ipsg_level]/$data_ketrampilan3[kml_level])</option>";
						 while ($for_ket3=mysqli_fetch_array($action_ketrampilan3))
						 {
							 echo "<option value=\"$for_ket3[id]\">$for_ket3[ketrampilan] (Level: $for_ket3[skdi_level]/$for_ket3[k_level]/$for_ket3[ipsg_level]/$for_ket3[kml_level])</option>";
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
					&nbsp;&nbsp;&nbsp;&nbsp;Ketrampilan - 4
				</td>
				<td style="padding:5px 5px 5px 5px">
					<div id="my_ketrampilan4" role="dialog" class="fix_style">
						<?php
						$data_ketrampilan4 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `id`='$data_logbook[ketrampilan4]'"));
						$action_ketrampilan4=mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `$id_include`='1' ORDER BY `ketrampilan` ASC");
						echo "<select name=\"ketrampilan4\" id=\"ketrampilan4\" class=\"select_artwide\">";
						echo "<option value=\"$data_ketrampilan4[id]\">$data_ketrampilan4[ketrampilan] (Level: $data_ketrampilan4[skdi_level]/$data_ketrampilan4[k_level]/$data_ketrampilan4[ipsg_level]/$data_ketrampilan4[kml_level])</option>";
						while ($for_ket4=mysqli_fetch_array($action_ketrampilan4))
						{
							echo "<option value=\"$for_ket4[id]\">$for_ket4[ketrampilan] (Level: $for_ket4[skdi_level]/$for_ket4[k_level]/$for_ket4[ipsg_level]/$for_ket4[kml_level])</option>";
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
	 			        <!-- Dosen/Residen -->
	 			        <td class="td_mid">
	 								<i>Dosen/Residen</i>
	 			        </td>
	 			        <td style="padding:5px 5px 5px 5px">
	 								<div id="my_dosen" role="dialog" class="fix_style">
	 								 	<?php
										echo "<select class=\"select_artwide\" name=\"dosen\" id=\"dosen\" required>";
										$dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
										$isi_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_logbook[dosen]'"));
										echo "<option value=\"$isi_dosen[nip]\">$isi_dosen[nama], $isi_dosen[gelar]</option>";
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
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"ubah\" value=\"UBAH\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[batal]=="BATAL")
		{
			echo "
				<script>
					window.location.href=\"edit_logbook.php?id=\"+\"$_POST[stase]\";
				</script>
			";
		}

		if ($_POST[ubah]=="UBAH")
		{
			$jam_awal=$_POST[jam_mulai].":".$_POST[menit_mulai].":"."00";
			$jam_akhir=$_POST[jam_selesai].":".$_POST[menit_selesai].":"."00";
			$jam_mulai_kegiatan = strtotime($jam_awal);
			$kelas = $_POST[kelas];
			$update_db = mysqli_query($con,"UPDATE `jurnal_ketrampilan`
				SET
				`jam_awal`='$jam_awal',`jam_akhir`='$jam_akhir',`kelas`='$kelas',
				`lokasi`='$_POST[lokasi]',`kegiatan`='$_POST[kegiatan]',
				`ketrampilan1`='$_POST[ketrampilan1]',
				`ketrampilan2`='$_POST[ketrampilan2]',
				`ketrampilan3`='$_POST[ketrampilan3]',
				`ketrampilan4`='$_POST[ketrampilan4]',
				`dosen`='$_POST[dosen]'
				WHERE `id`='$_POST[id_logbook]'");

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
		$("#hide_jam").hide();
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

	$("#ketrampilan1").select2({
		 	dropdownParent: $('#my_ketrampilan1'),
		 	placeholder: "< Pilihan Ketrampilan (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
    	allowClear: true
		});

		$("#ketrampilan2").select2({
			dropdownParent: $('#my_ketrampilan2'),
		 	placeholder: "< Pilihan Ketrampilan (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
      allowClear: true
 		});

		$("#ketrampilan3").select2({
			dropdownParent: $('#my_ketrampilan3'),
		 	placeholder: "< Pilihan Ketrampilan (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
      allowClear: true
 		});

		$("#ketrampilan4").select2({
			dropdownParent: $('#my_ketrampilan4'),
		 	placeholder: "< Pilihan Ketrampilan (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
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
