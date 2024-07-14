<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
	<script src="js/Chart.js"></script>
	<style type="text/css">
					.pie_container {
							width: 50%;
							margin: 15px auto;
					}
	</style>
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
	  if ($_COOKIE['level']==2) {include "menu2.php";}
	  if ($_COOKIE['level']==3) {include "menu3.php";}
		echo "<div class=\"text_header\">REKAP EVALUASI AKHIR STASE - UMUM</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP EVALUASI AKHIR STASE - UMUM</font></h4>";

		$id_stase = $_GET[stase];
		$angkatan_filter = $_GET[angk];
		$stase_id = "stase_".$id_stase;
		$include_id = "include_".$id_stase;
		$target_id = "target_".$id_stase;
		$tgl_awal = $_GET[tglawal];
		$tgl_akhir = $_GET[tglakhir];

		$filterstase = "`stase`="."'$id_stase'";
		$filtertgl = " AND (`tanggal`>="."'$tgl_awal'"." AND `tanggal`<="."'$tgl_akhir')";
		$filter = $filterstase.$filtertgl;

		$mhsw = mysqli_query($con,"SELECT `nim` FROM `$stase_id` WHERE `tgl_mulai`>='$tgl_awal' AND `tgl_selesai`<='$tgl_akhir' ORDER BY `nim`");
		$jml_mhsw = mysqli_num_rows($mhsw);
		$stase = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));

		//--------------------
		echo "<table style=\"width:50%\">";
		echo "<tr>";
		echo "<td style=\"width:30%\">Kepaniteraan (Stase)</td><td style=\"width:70%\">: $stase[kepaniteraan]</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Angkatan</td>";
		if ($angkatan_filter=="all") echo "<td>: Semua Angkatan</td>";
		else echo "<td>: Angkatan $angkatan_filter</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Jumlah Mahasiswa</td><td>: $jml_mhsw orang</td>";
		echo "</tr>";
		$tglawal=tanggal_indo($tgl_awal);
		$tglakhir=tanggal_indo($tgl_akhir);
		echo "<tr>";
		echo "<td>Periode Kegiatan</td><td>: $tglawal s.d $tglakhir</td>";
		echo "</tr>";
		echo "</table><br><br>";
		//------------------

		$delete_dummy = mysqli_query($con,"DELETE FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'");

		$id = 1;
		while ($nim_mhsw = mysqli_fetch_array($mhsw))
		{
			$data_evaluasi = mysqli_query($con,"SELECT * FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw[nim]' AND `stase`='$id_stase'");
			$jml_data_evaluasi = mysqli_num_rows($data_evaluasi);
			if ($jml_data_evaluasi>0)
			{
				$data_dummy = mysqli_fetch_array($data_evaluasi);
				$insert_dummy = mysqli_query($con,"INSERT INTO `dummy_evaluasi_stase`
					( `id`, `nim`, `stase`, `tgl_isi`,
						`input_111`, `input_112`, `input_113`, `input_114`,
						`input_115`, `input_116`, `input_117`, `input_118`,
						`input_119`, `input_1110`, `input_1111`, `input_1112`,
						`input_1113`, `input_1114`, `refleksi`, `komentar`,
						`input_12`, `input_13`, `dosen1`, `tatap_muka1`,
						`input_21A1`, `input_21A2`, `input_21A3`, `input_21A4`,
						`input_21A5`, `input_21A6`, `input_21A7`, `input_21B1`,
						`input_21B2`, `input_21B3`, `input_21B4`, `input_21B5`,
						`input_21B6`, `input_21B7`, `input_21B8`, `input_21B9`,
						`input_21B10`, `input_21B11`, `input_21B12`, `input_21C1`,
						`input_21C2`, `input_21C3`, `komentar_dosen1`, `dosen2`,
						`tatap_muka2`, `input_22A1`, `input_22A2`, `input_22A3`,
						`input_22A4`, `input_22A5`, `input_22A6`, `input_22A7`,
						`input_22B1`, `input_22B2`, `input_22B3`, `input_22B4`,
						`input_22B5`, `input_22B6`, `input_22B7`, `input_22B8`,
						`input_22B9`, `input_22B10`, `input_22B11`, `input_22B12`,
						`input_22C1`, `input_22C2`, `input_22C3`, `komentar_dosen2`,
						`dosen3`, `tatap_muka3`, `input_23A1`, `input_23A2`,
						`input_23A3`, `input_23A4`, `input_23A5`, `input_23A6`,
						`input_23A7`, `input_23B1`, `input_23B2`, `input_23B3`,
						`input_23B4`, `input_23B5`, `input_23B6`, `input_23B7`,
						`input_23B8`, `input_23B9`, `input_23B10`, `input_23B11`,
						`input_23B12`, `input_23C1`, `input_23C2`, `input_23C3`,
						`komentar_dosen3`, `lokasi_luar`, `lama_luar`, `input_3A1`,
						`input_3A2`, `input_3A3`, `input_3A4`, `input_3A5`,
						`input_3A6`, `input_3A7`, `input_3A8`, `input_3A9`,
						`input_3A10`, `like_luar`, `unlike_luar`,`username`)
				VALUES
				( '$id', '$data_dummy[nim]', '$data_dummy[stase]', '$data_dummy[tgl_isi]',
					'$data_dummy[input_111]', '$data_dummy[input_112]', '$data_dummy[input_113]', '$data_dummy[input_114]',
					'$data_dummy[input_115]', '$data_dummy[input_116]', '$data_dummy[input_117]', '$data_dummy[input_118]',
					'$data_dummy[input_119]', '$data_dummy[input_1110]', '$data_dummy[input_1111]', '$data_dummy[input_1112]',
					'$data_dummy[input_1113]', '$data_dummy[input_1114]', '$data_dummy[refleksi]', '$data_dummy[komentar]',
					'$data_dummy[input_12]', '$data_dummy[input_13]', '$data_dummy[dosen1]', '$data_dummy[tatap_muka1]',
					'$data_dummy[input_21A1]', '$data_dummy[input_21A2]', '$data_dummy[input_21A3]', '$data_dummy[input_21A4]',
					'$data_dummy[input_21A5]', '$data_dummy[input_21A6]', '$data_dummy[input_21A7]', '$data_dummy[input_21B1]',
					'$data_dummy[input_21B2]', '$data_dummy[input_21B3]', '$data_dummy[input_21B4]', '$data_dummy[input_21B5]',
					'$data_dummy[input_21B6]', '$data_dummy[input_21B7]', '$data_dummy[input_21B8]', '$data_dummy[input_21B9]',
					'$data_dummy[input_21B10]', '$data_dummy[input_21B11]', '$data_dummy[input_21B12]', '$data_dummy[input_21C1]',
					'$data_dummy[input_21C2]', '$data_dummy[input_21C3]', '$data_dummy[komentar_dosen1]', '$data_dummy[dosen2]',
					'$data_dummy[tatap_muka2]', '$data_dummy[input_22A1]', '$data_dummy[input_22A2]', '$data_dummy[input_22A3]',
					'$data_dummy[input_22A4]', '$data_dummy[input_22A5]', '$data_dummy[input_22A6]', '$data_dummy[input_22A7]',
					'$data_dummy[input_22B1]', '$data_dummy[input_22B2]', '$data_dummy[input_22B3]', '$data_dummy[input_22B4]',
					'$data_dummy[input_22B5]', '$data_dummy[input_22B6]', '$data_dummy[input_22B7]', '$data_dummy[input_22B8]',
					'$data_dummy[input_22B9]', '$data_dummy[input_22B10]', '$data_dummy[input_22B11]', '$data_dummy[input_22B12]',
					'$data_dummy[input_22C1]', '$data_dummy[input_22C2]', '$data_dummy[input_22C3]', '$data_dummy[komentar_dosen2]',
					'$data_dummy[dosen3]', '$data_dummy[tatap_muka3]', '$data_dummy[input_23A1]', '$data_dummy[input_23A2]',
					'$data_dummy[input_23A3]', '$data_dummy[input_23A4]', '$data_dummy[input_23A5]', '$data_dummy[input_23A6]',
					'$data_dummy[input_23A7]', '$data_dummy[input_23B1]', '$data_dummy[input_23B2]', '$data_dummy[input_23B3]',
					'$data_dummy[input_23B4]', '$data_dummy[input_23B5]', '$data_dummy[input_23B6]', '$data_dummy[input_23B7]',
					'$data_dummy[input_23B8]', '$data_dummy[input_23B9]', '$data_dummy[input_23B10]', '$data_dummy[input_23B11]',
					'$data_dummy[input_23B12]', '$data_dummy[input_23C1]', '$data_dummy[input_23C2]', '$data_dummy[input_23C3]',
					'$data_dummy[komentar_dosen3]', '$data_dummy[lokasi_luar]', '$data_dummy[lama_luar]', '$data_dummy[input_3A1]',
					'$data_dummy[input_3A2]', '$data_dummy[input_3A3]', '$data_dummy[input_3A4]', '$data_dummy[input_3A5]',
					'$data_dummy[input_3A6]', '$data_dummy[input_3A7]', '$data_dummy[input_3A8]', '$data_dummy[input_3A9]',
					'$data_dummy[input_3A10]', '$data_dummy[like_luar]', '$data_dummy[unlike_luar]','$_COOKIE[user]')");
				$id++;
			}
		}

		echo "<table style=\"width:60%;background-color:rgba(255,249,222,0.5)\">";
		echo "<tr>";
		echo "<td align=center><img src=\"images/evaluasi_header.jpg\" style=\"width:100%;height:auto\"></td>";
		echo "</tr>";
		echo "<tr><td>&nbsp;</td></tr>";

		//1. Sistem Pembalajaran Dalam Satase
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>1. SISTEM PEMBELAJARAN DALAM STASE</b></font></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Penilaian Materi Pembelajaran</b></font></td>";
		echo "</tr>";

		$eval_pemb = mysqli_query($con,"SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Pembelajaran' ORDER BY `id`");
		$no = 1;
		while ($data_pemb=mysqli_fetch_array($eval_pemb))
		{
			echo "<tr><td>";
			echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr><td><font style=\"font-size:0.85em\">$data_pemb[pertanyaan]</font><br>";
			$piechart = "piechart"."$no";
			echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
			?>
			<script  type="text/javascript">
	    	  <? echo "
						var ctx = document.getElementById(\"$piechart\").getContext(\"2d\");
						";
					?>
	    	  var data = {
	    	            labels: [
											<?php
												$input = "input_11".$no;
												//Sangat Tidak Setuju
												$sts = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='1' AND `username`='$_COOKIE[user]'"));
												$sts_persen = number_format(($sts/$jml_mhsw)*100,2);
												$sts_label = "Sangat Tidak Setuju - $sts mhsw ($sts_persen%)";
												echo '"' . $sts_label . '",';
												//Tidak Setuju
												$ts = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='2' AND `username`='$_COOKIE[user]'"));
												$ts_persen = number_format(($ts/$jml_mhsw)*100,2);
												$ts_label = "Tidak Setuju - $ts mhsw ($ts_persen%)";
												echo '"' . $ts_label . '",';
												//Setuju
												$s = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='3' AND `username`='$_COOKIE[user]'"));
												$s_persen = number_format(($s/$jml_mhsw)*100,2);
												$s_label = "Setuju - $s mhsw ($s_persen%)";
												echo '"' . $s_label . '",';
												//Sangat Setuju
												$ss = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='4' AND `username`='$_COOKIE[user]'"));
												$ss_persen = number_format(($ss/$jml_mhsw)*100,2);
												$ss_label = "Sangat Setuju - $ss mhsw ($ss_persen%)";
												echo '"' . $ss_label . '",';
												//Belum Mengisi
												$bm = $jml_mhsw-($sts+$ts+$s+$ss);
												$bm_persen = number_format(($bm/$jml_mhsw)*100,2);
												$bm_label = "Belum Mengisi - $bm mhsw ($bm_persen%)";
												echo '"' . $bm_label . '",';
											?>
										],
	    	            datasets: [
	    	            {
	    	              label: "",
	    	              data: [
												<?php
													echo '"' . $sts . '",';
													echo '"' . $ts . '",';
													echo '"' . $s . '",';
													echo '"' . $ss . '",';
													echo '"' . $bm . '",';
												?>
											],
	                    backgroundColor: [
	                      "rgba(255, 0, 0, 1)",
	                      "rgba(255, 153, 102, 1)",
	                      "rgba(200, 255, 0, 1)",
	                      "rgba(0, 255, 0, 1)",
												"rgba(224, 224, 235, 1)"
											]
	    	            }
	    	            ]
	    	            };

	    	  var myBarChart = new Chart(ctx, {
	    	            type: 'pie',
	    	            data: data,
	    	            options: {
	                    responsive: true
	    	          }
	    	        });
	    </script>
			<?php
			echo "</td></tr></table></td></tr>";
			$no++;
		}

		//Refleksi diri
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Refleksi Diri</b></font></td>";
		echo "</tr>";
		echo "<tr><td>";
		echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\">Tuliskan refleksi diri anda setelah menjalankan stase kepaniteraan ini: </font></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><a href=\"rekap_evaluasi_refleksi_diri.php?stase=$id_stase&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\" target=\"_blank\">
					Klik untuk melihat rangkuman isian refleksi diri <input type=button value=\"Klik\"></a></font></td>";
		echo "</tr></table>";
		echo "</td></tr>";

		//Komentar
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Kritik dan Saran</b></font></td>";
		echo "</tr>";
		echo "<tr><td>";
		echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\">Silakan tulis dalam kolom di bawah ini komentar, usul, saran, atau kritik dalam bahasa yang santun: </font></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><a href=\"rekap_evaluasi_saran.php?stase=$id_stase&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\" target=\"_blank\">Klik untuk melihat rangkuman isian komentar, usul, saran, atau kritik <input type=button value=\"Klik\"></a></font></td>";
		echo "</tr></table>";
		echo "</td></tr>";

		//Pencapaian
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Pencapaian</b></font></td>";
		echo "</tr>";
		echo "<tr><td>";
			echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr>";
			echo "<td colspan=2><font style=\"font-size:0.85em\">Menurut Anda, seberapa banyak pencapaian kompetensi level 3A, 3B, 4A yang Anda capai dalam kepaniteraan Bagian ini (termasuk dengan stase luar kepaniteraan ini)?<br>";

			$piechart = "piechart"."$no";
			echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
			?>
			<script  type="text/javascript">
	    	  <? echo "
						var ctx = document.getElementById(\"$piechart\").getContext(\"2d\");
						";
					?>
	    	  var data = {
	    	            labels: [
											<?php
												$input = "input_12";
												//< 25%
												$kurang25 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='1' AND `username`='$_COOKIE[user]'"));
												$kurang25_persen = number_format(($kurang25/$jml_mhsw)*100,2);
												$kurang25_label = "< 25% - $kurang25 mhsw ($kurang25_persen%)";
												echo '"' . $kurang25_label . '",';
												//25-50%
												$antara25sd50 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='2' AND `username`='$_COOKIE[user]'"));
												$antara25sd50_persen = number_format(($antara25sd50/$jml_mhsw)*100,2);
												$antara25sd50_label = "25-50% - $antara25sd50 mhsw ($antara25sd50_persen%)";
												echo '"' . $antara25sd50_label . '",';
												//50-75%
												$antara50sd75 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='3' AND `username`='$_COOKIE[user]'"));
												$antara50sd75_persen = number_format(($antara50sd75/$jml_mhsw)*100,2);
												$antara50sd75_label = "50-75% - $antara50sd75 mhsw ($antara50sd75_persen%)";
												echo '"' . $antara50sd75_label . '",';
												//> 75%
												$lebih75 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='4' AND `username`='$_COOKIE[user]'"));
												$lebih75_persen = number_format(($lebih75/$jml_mhsw)*100,2);
												$lebih75_label = "> 75% - $lebih75 mhsw ($lebih75_persen%)";
												echo '"' . $lebih75_label . '",';
												//Belum Mengisi
												$bm = $jml_mhsw-($kurang25+$antara25sd50+$antara50sd75+$lebih75);
												$bm_persen = number_format(($bm/$jml_mhsw)*100,2);
												$bm_label = "Belum Mengisi - $bm mhsw ($bm_persen%)";
												echo '"' . $bm_label . '",';
											?>
										],
	    	            datasets: [
	    	            {
	    	              label: "",
	    	              data: [
												<?php
													echo '"' . $kurang25 . '",';
													echo '"' . $antara25sd50 . '",';
													echo '"' . $antara50sd75 . '",';
													echo '"' . $lebih75 . '",';
													echo '"' . $bm . '",';
												?>
											],
	                    backgroundColor: [
	                      "rgba(255, 0, 0, 1)",
	                      "rgba(255, 153, 102, 1)",
	                      "rgba(200, 255, 0, 1)",
	                      "rgba(0, 255, 0, 1)",
												"rgba(224, 224, 235, 1)"
											]
	    	            }
	    	            ]
	    	            };

	    	  var myBarChart = new Chart(ctx, {
	    	            type: 'pie',
	    	            data: data,
	    	            options: {
	                    responsive: true
	    	          }
	    	        });
	    </script>
			<?php
			$no++;
			echo "</td></tr></table>";
		echo "</td></tr>";

		//Kepuasan
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>Kepuasan</b></font></td>";
		echo "</tr>";
		echo "<tr><td>";
			echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr>";
			echo "<td colspan=2><font style=\"font-size:0.85em\">Seberapa besar kepuasan Anda terhadap keseluruhan program stase di Bagian ini (termasuk program stase luar)?<br>";

			$piechart = "piechart"."$no";
			echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
			?>
			<script  type="text/javascript">
	    	  <? echo "
						var ctx = document.getElementById(\"$piechart\").getContext(\"2d\");
						";
					?>
	    	  var data = {
	    	            labels: [
											<?php
												$input = "input_13";
												//Sangat Tidak Puas
												$sts = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='1' AND `username`='$_COOKIE[user]'"));
												$sts_persen = number_format(($sts/$jml_mhsw)*100,2);
												$sts_label = "Sangat Tidak Puas - $sts mhsw ($sts_persen%)";
												echo '"' . $sts_label . '",';
												//Tidak Setuju
												$ts = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='2' AND `username`='$_COOKIE[user]'"));
												$ts_persen = number_format(($ts/$jml_mhsw)*100,2);
												$ts_label = "Tidak Puas - $ts mhsw ($ts_persen%)";
												echo '"' . $ts_label . '",';
												//Setuju
												$s = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='3' AND `username`='$_COOKIE[user]'"));
												$s_persen = number_format(($s/$jml_mhsw)*100,2);
												$s_label = "Puas - $s mhsw ($s_persen%)";
												echo '"' . $s_label . '",';
												//Sangat Setuju
												$ss = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='4' AND `username`='$_COOKIE[user]'"));
												$ss_persen = number_format(($ss/$jml_mhsw)*100,2);
												$ss_label = "Sangat Puas - $ss mhsw ($ss_persen%)";
												echo '"' . $ss_label . '",';
												//Belum Mengisi
												$bm = $jml_mhsw-($sts+$ts+$s+$ss);
												$bm_persen = number_format(($bm/$jml_mhsw)*100,2);
												$bm_label = "Belum Mengisi - $bm mhsw ($bm_persen%)";
												echo '"' . $bm_label . '",';
											?>
										],
	    	            datasets: [
	    	            {
	    	              label: "",
	    	              data: [
												<?php
													echo '"' . $sts . '",';
													echo '"' . $ts . '",';
													echo '"' . $s . '",';
													echo '"' . $ss . '",';
													echo '"' . $bm . '",';
												?>
											],
	                    backgroundColor: [
	                      "rgba(255, 0, 0, 1)",
	                      "rgba(255, 153, 102, 1)",
	                      "rgba(200, 255, 0, 1)",
	                      "rgba(0, 255, 0, 1)",
												"rgba(224, 224, 235, 1)"
											]
	    	            }
	    	            ]
	    	            };

	    	  var myBarChart = new Chart(ctx, {
	    	            type: 'pie',
	    	            data: data,
	    	            options: {
	                    responsive: true
	    	          }
	    	        });
	    </script>
			<?php
			$no++;
			echo "</td></tr></table>";
		echo "</td></tr>";
		echo "<tr><td>&nbsp;</td></tr>";

		//2. Dosen
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>2. DOSEN</b><br>";
		echo "Berikan evaluasi Anda bagi minimal 3 (tiga)  dosen  dalam kepaniteraan ini yang melakukan minimal 2 (dua) kali tatap muka dengan Anda. Bila tidak ada, pilih 3 dosen dengan kuantitas dan/atau kualitas pertemuan paling banyak.";
		echo "<br>Aspek yang tidak bisa dinilai (misalnya, karena mahasiswa tidak tahu pasti) tidak perlu dinilai (kolom dikosongkan).</font></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\">Rekap dosen diakumulasi dari isian Dosen 1, Dosen 2, dan Dosen 3:</font></td>";
		echo "</tr>";
		echo "<tr><td>";

		//Rata-rata jam tatap muka
		$jml_jam_dosen1 = mysqli_fetch_array(mysqli_query($con,"SELECT sum(`tatap_muka1`) FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'"));
		$jml_jam_dosen2 = mysqli_fetch_array(mysqli_query($con,"SELECT sum(`tatap_muka2`) FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'"));
		$jml_jam_dosen3 = mysqli_fetch_array(mysqli_query($con,"SELECT sum(`tatap_muka3`) FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'"));
		$jml_evaluasi_dosen1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `tatap_muka1`>0 AND `username`='$_COOKIE[user]'"));
		$jml_evaluasi_dosen2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `tatap_muka2`>0 AND `username`='$_COOKIE[user]'"));
		$jml_evaluasi_dosen3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `tatap_muka3`>0 AND `username`='$_COOKIE[user]'"));
		$rata_jam = (($jml_jam_dosen1[0]/$jml_evaluasi_dosen1)+($jml_jam_dosen2[0]/$jml_evaluasi_dosen2)+($jml_jam_dosen3[0]/$jml_evaluasi_dosen3))/3;
		$jam = floor($rata_jam);
		$menit = number_format(($rata_jam - $jam)*60,0);
		echo "<tr><td>";
		echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
		echo "<tr><td><font style=\"font-size:0.85em\">Rata-rata jam tatap muka: $jam jam , $menit menit</font>";
		echo "</td></tr></table>";
		echo "</td></tr>";

		//A. Umum
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\">A. Umum</font></td>";
		echo "</tr>";
		$eval_dosen = mysqli_query($con,"SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_umum' ORDER BY `id`");
		$id = 1;
		while ($data_eval=mysqli_fetch_array($eval_dosen))
		{
			echo "<tr><td>";
			echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr><td><font style=\"font-size:0.85em\">$data_eval[pertanyaan]</font><br>";
			$piechart = "piechart"."$no";
			echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
			?>
			<script  type="text/javascript">
	    	  <? echo "
						var ctx = document.getElementById(\"$piechart\").getContext(\"2d\");
						";
					?>
	    	  var data = {
	    	            labels: [
											<?php
												$input1 = "input_21A".$id;
												$input2 = "input_22A".$id;
												$input3 = "input_23A".$id;
												//Sangat Tidak Setuju
												$sts1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='1'"));
												$sts2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='1'"));
												$sts3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='1'"));
												$sts = $sts1 + $sts2 + $sts3;
												$sts_persen = number_format(($sts/(3*$jml_mhsw))*100,2);
												$sts_label = "Sangat Tidak Setuju - $sts review ($sts_persen%)";
												echo '"' . $sts_label . '",';
												//Tidak Setuju
												$ts1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='2'"));
												$ts2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='2'"));
												$ts3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='2'"));
												$ts = $ts1 + $ts2 + $ts3;
												$ts_persen = number_format(($ts/(3*$jml_mhsw))*100,2);
												$ts_label = "Tidak Setuju - $ts review ($ts_persen%)";
												echo '"' . $ts_label . '",';
												//Setuju
												$s1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='3'"));
												$s2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='3'"));
												$s3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='3'"));
												$s = $s1 + $s2 + $s3;
												$s_persen = number_format(($s/(3*$jml_mhsw))*100,2);
												$s_label = "Setuju - $s review ($s_persen%)";
												echo '"' . $s_label . '",';
												//Sangat Setuju
												$ss1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='4'"));
												$ss2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='4'"));
												$ss3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='4'"));
												$ss = $ss1 + $ss2 + $ss3;
												$ss_persen = number_format(($ss/(3*$jml_mhsw))*100,2);
												$ss_label = "Sangat Setuju - $ss review ($ss_persen%)";
												echo '"' . $ss_label . '",';
												//Belum Mengisi
												$bm = (3*$jml_mhsw)-($sts+$ts+$s+$ss);
												$bm_persen = number_format(($bm/(3*$jml_mhsw))*100,2);
												$bm_label = "Belum Mengisi - $bm review ($bm_persen%)";
												echo '"' . $bm_label . '",';
											?>
										],
	    	            datasets: [
	    	            {
	    	              label: "",
	    	              data: [
												<?php
													echo '"' . $sts . '",';
													echo '"' . $ts . '",';
													echo '"' . $s . '",';
													echo '"' . $ss . '",';
													echo '"' . $bm . '",';
												?>
											],
	                    backgroundColor: [
	                      "rgba(255, 0, 0, 1)",
	                      "rgba(255, 153, 102, 1)",
	                      "rgba(200, 255, 0, 1)",
	                      "rgba(0, 255, 0, 1)",
												"rgba(224, 224, 235, 1)"
											]
	    	            }
	    	            ]
	    	            };

	    	  var myBarChart = new Chart(ctx, {
	    	            type: 'pie',
	    	            data: data,
	    	            options: {
	                    responsive: true
	    	          }
	    	        });
	    </script>
			<?php
			echo "</td></tr></table></td></tr>";
			$no++;
			$id++;
		}

		//B. Dosen sebagai pengajar
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\">B. Dosen sebagai pengajar</font></td>";
		echo "</tr>";
		$eval_dosen = mysqli_query($con,"SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_pengajar' ORDER BY `id`");
		$id = 1;
		while ($data_eval=mysqli_fetch_array($eval_dosen))
		{
			echo "<tr><td>";
			echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr><td><font style=\"font-size:0.85em\">$data_eval[pertanyaan]</font><br>";
			$piechart = "piechart"."$no";
			echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
			?>
			<script  type="text/javascript">
	    	  <? echo "
						var ctx = document.getElementById(\"$piechart\").getContext(\"2d\");
						";
					?>
	    	  var data = {
	    	            labels: [
											<?php
												$input1 = "input_21B".$id;
												$input2 = "input_22B".$id;
												$input3 = "input_23B".$id;
												//Sangat Tidak Setuju
												$sts1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='1'"));
												$sts2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='1'"));
												$sts3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='1'"));
												$sts = $sts1 + $sts2 + $sts3;
												$sts_persen = number_format(($sts/(3*$jml_mhsw))*100,2);
												$sts_label = "Sangat Tidak Setuju - $sts review ($sts_persen%)";
												echo '"' . $sts_label . '",';
												//Tidak Setuju
												$ts1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='2'"));
												$ts2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='2'"));
												$ts3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='2'"));
												$ts = $ts1 + $ts2 + $ts3;
												$ts_persen = number_format(($ts/(3*$jml_mhsw))*100,2);
												$ts_label = "Tidak Setuju - $ts review ($ts_persen%)";
												echo '"' . $ts_label . '",';
												//Setuju
												$s1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='3'"));
												$s2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='3'"));
												$s3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='3'"));
												$s = $s1 + $s2 + $s3;
												$s_persen = number_format(($s/(3*$jml_mhsw))*100,2);
												$s_label = "Setuju - $s review ($s_persen%)";
												echo '"' . $s_label . '",';
												//Sangat Setuju
												$ss1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='4'"));
												$ss2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='4'"));
												$ss3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='4'"));
												$ss = $ss1 + $ss2 + $ss3;
												$ss_persen = number_format(($ss/(3*$jml_mhsw))*100,2);
												$ss_label = "Sangat Setuju - $ss review ($ss_persen%)";
												echo '"' . $ss_label . '",';
												//Belum Mengisi
												$bm = (3*$jml_mhsw)-($sts+$ts+$s+$ss);
												$bm_persen = number_format(($bm/(3*$jml_mhsw))*100,2);
												$bm_label = "Belum Mengisi - $bm review ($bm_persen%)";
												echo '"' . $bm_label . '",';
											?>
										],
	    	            datasets: [
	    	            {
	    	              label: "",
	    	              data: [
												<?php
													echo '"' . $sts . '",';
													echo '"' . $ts . '",';
													echo '"' . $s . '",';
													echo '"' . $ss . '",';
													echo '"' . $bm . '",';
												?>
											],
	                    backgroundColor: [
	                      "rgba(255, 0, 0, 1)",
	                      "rgba(255, 153, 102, 1)",
	                      "rgba(200, 255, 0, 1)",
	                      "rgba(0, 255, 0, 1)",
												"rgba(224, 224, 235, 1)"
											]
	    	            }
	    	            ]
	    	            };

	    	  var myBarChart = new Chart(ctx, {
	    	            type: 'pie',
	    	            data: data,
	    	            options: {
	                    responsive: true
	    	          }
	    	        });
	    </script>
			<?php
			echo "</td></tr></table></td></tr>";
			$no++;
			$id++;
		}

		//C. Dosen sebagai penguji
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\">C. Dosen sebagai penguji</font></td>";
		echo "</tr>";
		$eval_dosen = mysqli_query($con,"SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Dosen_penguji' ORDER BY `id`");
		$id = 1;
		while ($data_eval=mysqli_fetch_array($eval_dosen))
		{
			echo "<tr><td>";
			echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr><td><font style=\"font-size:0.85em\">$data_eval[pertanyaan]</font><br>";
			$piechart = "piechart"."$no";
			echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
			?>
			<script  type="text/javascript">
	    	  <? echo "
						var ctx = document.getElementById(\"$piechart\").getContext(\"2d\");
						";
					?>
	    	  var data = {
	    	            labels: [
											<?php
												$input1 = "input_21C".$id;
												$input2 = "input_22C".$id;
												$input3 = "input_23C".$id;
												//Sangat Tidak Setuju
												$sts1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='1'"));
												$sts2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='1'"));
												$sts3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='1'"));
												$sts = $sts1 + $sts2 + $sts3;
												$sts_persen = number_format(($sts/(3*$jml_mhsw))*100,2);
												$sts_label = "Sangat Tidak Setuju - $sts review ($sts_persen%)";
												echo '"' . $sts_label . '",';
												//Tidak Setuju
												$ts1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='2'"));
												$ts2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='2'"));
												$ts3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='2'"));
												$ts = $ts1 + $ts2 + $ts3;
												$ts_persen = number_format(($ts/(3*$jml_mhsw))*100,2);
												$ts_label = "Tidak Setuju - $ts review ($ts_persen%)";
												echo '"' . $ts_label . '",';
												//Setuju
												$s1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='3'"));
												$s2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='3'"));
												$s3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='3'"));
												$s = $s1 + $s2 + $s3;
												$s_persen = number_format(($s/(3*$jml_mhsw))*100,2);
												$s_label = "Setuju - $s review ($s_persen%)";
												echo '"' . $s_label . '",';
												//Sangat Setuju
												$ss1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input1`='4'"));
												$ss2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input2`='4'"));
												$ss3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input3`='4'"));
												$ss = $ss1 + $ss2 + $ss3;
												$ss_persen = number_format(($ss/(3*$jml_mhsw))*100,2);
												$ss_label = "Sangat Setuju - $ss review ($ss_persen%)";
												echo '"' . $ss_label . '",';
												//Belum Mengisi
												$bm = (3*$jml_mhsw)-($sts+$ts+$s+$ss);
												$bm_persen = number_format(($bm/(3*$jml_mhsw))*100,2);
												$bm_label = "Belum Mengisi - $bm review ($bm_persen%)";
												echo '"' . $bm_label . '",';
											?>
										],
	    	            datasets: [
	    	            {
	    	              label: "",
	    	              data: [
												<?php
													echo '"' . $sts . '",';
													echo '"' . $ts . '",';
													echo '"' . $s . '",';
													echo '"' . $ss . '",';
													echo '"' . $bm . '",';
												?>
											],
	                    backgroundColor: [
	                      "rgba(255, 0, 0, 1)",
	                      "rgba(255, 153, 102, 1)",
	                      "rgba(200, 255, 0, 1)",
	                      "rgba(0, 255, 0, 1)",
												"rgba(224, 224, 235, 1)"
											]
	    	            }
	    	            ]
	    	            };

	    	  var myBarChart = new Chart(ctx, {
	    	            type: 'pie',
	    	            data: data,
	    	            options: {
	                    responsive: true
	    	          }
	    	        });
	    </script>
			<?php
			echo "</td></tr></table></td></tr>";
			$no++;
			$id++;
		}

		echo "<tr><td>";
		echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
		echo "<tr><td><font style=\"font-size:0.85em\">Untuk melihat komentar, usul, saran atau masukan untuk para dosen bisa klik link berikut:<br><a href=\"rekap_evaluasi_kritik_dosen.php?stase=$id_stase&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\" target=\"_blank\">Komentar, usul, saran atau masukan untuk dosen <input type=button value=\"Klik\"></a></font>";
		echo "</td></tr></table>";
		echo "</td></tr>";

		echo "<tr><td>";
		echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
		echo "<tr><td><font style=\"font-size:0.85em\">Untuk melihat rekap per dosen bisa klik link berikut:<br><a href=\"rekap_evaluasi_dosen.php?stase=$id_stase&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\" target=\"_blank\">Klik untuk melihat rekap evaluasi per dosen <input type=button value=\"Klik\"></a></font></td>";
		echo "</td></tr></table>";
		echo "</td></tr>";

		//3. Evaluasi Stase Luar
		echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><b>3. EVALUASI STASE LUAR</b><br>";
		echo "Isilah kuesioner berikut apabila dalam stase yang Anda evaluasi ini ada stase luar.</font></td>";
		echo "</tr>";

		//A. Penilaian Materi Pembelajaran
		echo "<tr><td><font style=\"font-size:0.85em\">A. Penilaian Materi Pembelajaran</font></td></tr>";
		$eval_pemb = mysqli_query($con,"SELECT * FROM `pertanyaan_evaluasi` WHERE `topik` like 'Stase_luar' ORDER BY `id`");
		$id = 1;
		while ($data_pemb=mysqli_fetch_array($eval_pemb))
		{
			echo "<tr><td>";
			echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr><td><font style=\"font-size:0.85em\">$data_pemb[pertanyaan]</font><br>";
			$piechart = "piechart"."$no";
			echo "
			<div class=\"pie_container\">
	            <canvas id=\"$piechart\" width=\"100\" height=\"100\"></canvas>
	    </div>";
			?>
			<script  type="text/javascript">
	    	  <? echo "
						var ctx = document.getElementById(\"$piechart\").getContext(\"2d\");
						";
					?>
	    	  var data = {
	    	            labels: [
											<?php
												$input = "input_3A".$id;
												//Sangat Tidak Setuju
												$sts = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='1' AND `username`='$_COOKIE[user]'"));
												$sts_persen = number_format(($sts/$jml_mhsw)*100,2);
												$sts_label = "Sangat Tidak Setuju - $sts mhsw ($sts_persen%)";
												echo '"' . $sts_label . '",';
												//Tidak Setuju
												$ts = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='2' AND `username`='$_COOKIE[user]'"));
												$ts_persen = number_format(($ts/$jml_mhsw)*100,2);
												$ts_label = "Tidak Setuju - $ts mhsw ($ts_persen%)";
												echo '"' . $ts_label . '",';
												//Setuju
												$s = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='3' AND `username`='$_COOKIE[user]'"));
												$s_persen = number_format(($s/$jml_mhsw)*100,2);
												$s_label = "Setuju - $s mhsw ($s_persen%)";
												echo '"' . $s_label . '",';
												//Sangat Setuju
												$ss = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `dummy_evaluasi_stase` WHERE `$input`='4' AND `username`='$_COOKIE[user]'"));
												$ss_persen = number_format(($ss/$jml_mhsw)*100,2);
												$ss_label = "Sangat Setuju - $ss mhsw ($ss_persen%)";
												echo '"' . $ss_label . '",';
												//Belum Mengisi
												$bm = $jml_mhsw-($sts+$ts+$s+$ss);
												$bm_persen = number_format(($bm/$jml_mhsw)*100,2);
												$bm_label = "Belum Mengisi - $bm mhsw ($bm_persen%)";
												echo '"' . $bm_label . '",';
											?>
										],
	    	            datasets: [
	    	            {
	    	              label: "",
	    	              data: [
												<?php
													echo '"' . $sts . '",';
													echo '"' . $ts . '",';
													echo '"' . $s . '",';
													echo '"' . $ss . '",';
													echo '"' . $bm . '",';
												?>
											],
	                    backgroundColor: [
	                      "rgba(255, 0, 0, 1)",
	                      "rgba(255, 153, 102, 1)",
	                      "rgba(200, 255, 0, 1)",
	                      "rgba(0, 255, 0, 1)",
												"rgba(224, 224, 235, 1)"
											]
	    	            }
	    	            ]
	    	            };

	    	  var myBarChart = new Chart(ctx, {
	    	            type: 'pie',
	    	            data: data,
	    	            options: {
	                    responsive: true
	    	          }
	    	        });
	    </script>
			<?php
			echo "</td></tr></table></td></tr>";
			$no++;
			$id++;
		}

		echo "<tr><td>";
		echo "<table style=\"border:0.5px solid grey;border-radius:5px;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\">Rekap evaluasi stase luar diberikan untuk tiap lokasi yang dievaluasi mahasiswa.</font></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><font style=\"font-size:0.85em\"><a href=\"rekap_evaluasi_stase_luar.php?stase=$id_stase&angk=$angkatan_filter&tglawal=$tgl_awal&tglakhir=$tgl_akhir\" target=\"_blank\">Klik untuk melihat rangkuman evaluasi stase luar <input type=button value=\"Klik\"></a></font></td>";
		echo "</tr></table>";
		echo "</td></tr>";

		echo "</table>";

		$delete_dummy = mysqli_query($con,"DELETE FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'");

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
<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
<script>
  $(document).ready(function(){
	   $("#freeze").freezeHeader();
		 $("#freeze1").freezeHeader();
  });
</script>



<!--</body></html>-->
</BODY>
</HTML>
