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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
	  if ($_COOKIE['level']==2) {include "menu2.php";}
	  if ($_COOKIE['level']==3) {include "menu3.php";}

		echo "<div class=\"text_header\" id=\"top\">REKAP UMUM LOGBOOK</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP UMUM LOGBOOK KEPANITERAAN (STASE)</font></h4>";

		$grup_filter = $_GET[grup];
		$stase_filter = $_GET[stase];
		$angkatan_filter = $_GET[angk];
		$tgl_awal = $_GET[tglawal];
		$tgl_akhir = $_GET[tglakhir];
		$stase_id = "stase_".$stase_filter;
		$target_id = "target_".$stase_filter;
		$include_id = "include_".$stase_filter;

		$filterstase = "`stase`="."'$stase_filter'";
		$filtertgl = " AND (`tanggal`>="."'$tgl_awal'"." AND `tanggal`<="."'$tgl_akhir')";

		$hari_kurang = 28-1;
		$kurang_hari = '-'.$hari_kurang.' days';
		$tgl_batas = date('Y-m-d', strtotime($kurang_hari, strtotime($tgl_akhir)));

		if ($grup_filter=="1") $filtergrup = "`tgl_mulai`<="."'$tgl_batas'";
		if ($grup_filter=="2") $filtergrup = "`tgl_mulai`>"."'$tgl_batas'"." AND `tgl_mulai`<="."'$tgl_akhir'";
		if ($grup_filter=="all") $filtergrup = "`tgl_mulai`<="."'$tgl_akhir'";

		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$stase_filter'"));
		$kepaniteraan = $data_stase[kepaniteraan];
		$filter = $filterstase.$filtertgl;

		$mhsw = mysqli_query($con,"SELECT `nim` FROM `$stase_id` WHERE $filtergrup AND (`tgl_mulai`>='$tgl_awal' AND `tgl_selesai`<='$tgl_akhir')");
		$i = 1;
		$delete_dummy_penyakit = mysqli_query($con,"DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$_COOKIE[user]'");
		$delete_dummy_ketrampilan = mysqli_query($con,"DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$_COOKIE[user]'");
		while ($data_mhsw=mysqli_fetch_array($mhsw))
		{
			//cek Angkatan
			$angkatan = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
			if ($angkatan_filter==$angkatan[angkatan] OR $angkatan_filter=="all")
			{
				$mhsw_nim[$i] = $data_mhsw[nim];
				$filter_penyakit = "SELECT * FROM `jurnal_penyakit` WHERE `nim`='$data_mhsw[nim]' AND ".$filter." AND `status`='1'";
				$jurnal_penyakit = mysqli_query($con,$filter_penyakit);
				while ($data=mysqli_fetch_array($jurnal_penyakit))
				{
					$insert_dummy = mysqli_query($con,"INSERT INTO `jurnal_penyakit_dummy`
						(`id`, `nim`, `nama`,`angkatan`, `grup`,
							`hari`, `tanggal`, `stase`,
							`jam_awal`, `jam_akhir`, `kelas`,
							`lokasi`, `kegiatan`,
							`penyakit1`, `penyakit2`, `penyakit3`, `penyakit4`,
							`dosen`, `status`, `evaluasi`,`username`)
						VALUES
						('$data[id]', '$data[nim]', '','$data[angkatan]', '$data[grup]',
						  '$data[hari]', '$data[tanggal]', '$data[stase]',
						  '$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]',
						  '$data[lokasi]', '$data[kegiatan]',
						  '$data[penyakit1]', '$data[penyakit2]', '$data[penyakit3]', '$data[penyakit4]',
						  '$data[dosen]', '$data[status]', '$data[evaluasi]','$_COOKIE[user]')");
				}
				$filter_ketrampilan = "SELECT * FROM `jurnal_ketrampilan` WHERE `nim`='$data_mhsw[nim]' AND ".$filter." AND `status`='1'";
				$jurnal_ketrampilan = mysqli_query($con,$filter_ketrampilan);
				while ($data=mysqli_fetch_array($jurnal_ketrampilan))
				{
					$insert_dummy = mysqli_query($con,"INSERT INTO `jurnal_ketrampilan_dummy`
						(`id`, `nim`, `nama`,`angkatan`, `grup`,
							`hari`, `tanggal`, `stase`,
							`jam_awal`, `jam_akhir`, `kelas`,
							`lokasi`, `kegiatan`,
							`ketrampilan1`, `ketrampilan2`, `ketrampilan3`, `ketrampilan4`,
							`dosen`, `status`, `evaluasi`,`username`)
						VALUES
						('$data[id]', '$data[nim]', '','$data[angkatan]', '$data[grup]',
						  '$data[hari]', '$data[tanggal]', '$data[stase]',
						  '$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]',
						  '$data[lokasi]', '$data[kegiatan]',
						  '$data[ketrampilan1]', '$data[ketrampilan2]', '$data[ketrampilan3]', '$data[ketrampilan4]',
						  '$data[dosen]', '$data[status]', '$data[evaluasi]','$_COOKIE[user]')");
				}
				$i++;
			}
		}
		$jml_mhsw = $i-1;


		//--------------------
		echo "<table style=\"width:50%\">";
		echo "<tr>";
		echo "<td style=\"width:30%\">Kepaniteraan (Stase)</td><td style=\"width:70%\">: $data_stase[kepaniteraan]</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Angkatan</td>";
		if ($angkatan_filter=="all") echo "<td>: Semua Angkatan</td>";
		else echo "<td>: Angkatan $angkatan_filter</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Grup</td>";
		if ($grup_filter=="all") echo "<td>: Semua</td>";
		else
		{
			if ($grup_filter=='1') echo "<td>: Senior</td>";
			else echo "<td>: Yunior</td>";
		}
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

		echo "</center><a href=\"#penyakit\">Rekap Jurnal Penyakit</a><br>";
		echo "<a href=\"#ketrampilan\">Rekap Jurnal Ketrampilan Klinik</a><br>";
		echo "<a href=\"#catatan\">Catatan Rekap Jurnal</a><br><br>";

		echo "<a id=\"penyakit\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Rekap Jurnal Penyakit</font></a><br><br>";
		echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
		echo "<thead>";
		echo "<tr>";
			echo "<th rowspan=2 style=\"width:5%;text-align:center;vertical-align:middle;font-weight:bold\">No</th>";
			echo "<th rowspan=2 style=\"width:37%;text-align:center;vertical-align:middle;font-weight:bold\">Penyakit</th>";
			echo "<th rowspan=2 style=\"width:18%;text-align:center;vertical-align:middle;font-weight:bold\">Level SKDI/Kepmenkes<br>/IPSG/Muatan Lokal</th>";
			echo "<th rowspan=2 style=\"width:10%;text-align:center;vertical-align:middle;font-weight:bold\">Target Minimum</th>";
			echo "<th colspan=6 style=\"width:30%;text-align:center;font-weight:bold\">Level/Kode Kegiatan</th>";
		echo "</tr>";
		echo "<tr>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">1</th>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">2</th>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">3</th>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">4A</th>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">MKM</th>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">U</th>";
		echo "</tr>";
		echo "</thead>";

		$daftar_penyakit = mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1' ORDER BY `$target_id` DESC,`penyakit` ASC");
		$no = 1;
		$jml_ketuntasan = 0;
		$item = 0;
		$ketuntasan = 0;
		while ($data=mysqli_fetch_array($daftar_penyakit))
		{
			echo "<tr>";
			echo "<td align=center>$no</td>";
			echo "<td>$data[penyakit]</td>";
			echo "<td align=center>$data[skdi_level] / $data[k_level] / $data[ipsg_level] / $data[kml_level]</td>";
			echo "<td align=center>$data[$target_id]</td>";

			$tot_jml_1=0;
			$tot_jml_2=0;
			$tot_jml_3=0;
			$tot_jml_4A=0;
			$tot_jml_MKM=0;
			$tot_jml_U=0;
			for ($i=1;$i<=$jml_mhsw;$i++)
			{
					if ($data[skdi_level]=="MKM" OR $data[k_level]=="MKM" OR $data[ipsg_level]=="MKM" OR $data[kml_level]=="MKM")
					{
						$jml_MKM = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND `username`='$_COOKIE[user]'"));
						$jml_1 = 0;
						$jml_2 = 0;
						$jml_3 = 0;
						$jml_4A = 0;
						$jml_U = 0;
					}
					else
					{
						$jml_1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
							AND (`kegiatan`='1' OR `kegiatan`='2')
							AND `username`='$_COOKIE[user]'"));
						$jml_2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
							AND `username`='$_COOKIE[user]'"));
						$jml_3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
							AND `username`='$_COOKIE[user]'"));
						$jml_4A = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND `kegiatan`='10'
							AND `username`='$_COOKIE[user]'"));
						$jml_U = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim[$i]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND (`kegiatan`='11' OR `kegiatan`='12')
							AND `username`='$_COOKIE[user]'"));
						$jml_MKM = 0;
					}
					$tot_jml_1=$tot_jml_1+$jml_1;
					$tot_jml_2=$tot_jml_2+$jml_2;
					$tot_jml_3=$tot_jml_3+$jml_3;
					$tot_jml_4A=$tot_jml_4A+$jml_4A;
					$tot_jml_MKM=$tot_jml_MKM+$jml_MKM;
					$tot_jml_U=$tot_jml_U+$jml_U;
			}
			$jml_1=$tot_jml_1/$jml_mhsw;
			$jml_2=$tot_jml_2/$jml_mhsw;
			$jml_3=$tot_jml_3/$jml_mhsw;
			$jml_4A=$tot_jml_4A/$jml_mhsw;
			$jml_MKM=$tot_jml_MKM/$jml_mhsw;
			$jml_U=$tot_jml_U/$jml_mhsw;

		  $jumlah_total=$jml_1+$jml_2+$jml_3+$jml_4A+$jml_MKM+$jml_U;
		  $jml_1=number_format($jml_1,2);
		  $jml_2=number_format($jml_2,2);;
		  $jml_3=number_format($jml_3,2);;
		  $jml_4A=number_format($jml_4A,2);;
		  $jml_MKM=number_format($jml_MKM,2);;
		  $jml_U=number_format($jml_U,2);;

			//Kasus tidak ada target
			if ($data[$target_id]<1)
			{
				if ($jumlah_total>0)
				{
					//Blok warna hijau tua
					$ketuntasan = 100;
					$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
					$item++;
					blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
				}
				else
				{
					echo "<td style=\"width:5%;\">&nbsp;</td>";
					echo "<td style=\"width:5%;\">&nbsp;</td>";
					echo "<td style=\"width:5%;\">&nbsp;</td>";
					echo "<td style=\"width:5%;\">&nbsp;</td>";
					echo "<td style=\"width:5%;\">&nbsp;</td>";
					echo "<td style=\"width:5%;\">&nbsp;</td>";
				}
			}
			else
			//Kasus ada target
			{
				$blocked = 0;
				//Start - Pewarnaan Capaian Level 4A
				if (($data[skdi_level]=="4A" OR $data[k_level]=="4A" OR $data[ipsg_level]=="4A" OR $data[kml_level]=="4A") AND $blocked == 0)
				{
					$batas_target = $data[$target_id]/2;
					$blocked = 1;
					if ($jml_4A>$batas_target)
					{
						//Blok warna hijau tua
						$ketuntasan = 100;
						$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
						$item++;
						blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
					}
					else
					{
						if ($jml_4A<=$batas_target AND $jml_4A>=1)
						//Blok warna hijau muda
						{
							$ketuntasan = 75;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
						if ($jml_4A<1)
						{
							if ($jml_3>=$batas_target)
							//Blok warna hijau muda
							{
								$ketuntasan = 75;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
							else
							{
								if ($jumlah_total>=1)
								//Blok warna kuning
								{
									$ketuntasan = 50;
									$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
									$item++;
									blok_warna("kuning",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
								}
								else
								//Blok warna merah
								{
									$ketuntasan = 0;
									$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
									$item++;
									blok_warna("merah",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
								}
							}
						}
					}
				}
				//End - Pewarnaan Capaian Level 4A

				//Start - Pewarnaan Capaian Level 3A dan 3B
				if (($data[skdi_level]=="3" OR $data[k_level]=="3" OR $data[ipsg_level]=="3" OR $data[kml_level]=="3"
						OR $data[skdi_level]=="3A" OR $data[k_level]=="3A" OR $data[ipsg_level]=="3A" OR $data[kml_level]=="3A"
						OR $data[skdi_level]=="3B" OR $data[k_level]=="3B" OR $data[ipsg_level]=="3B" OR $data[kml_level]=="3B")
				 		AND $blocked == 0)
				{
					$batas_target = $data[$target_id]/2;
					$blocked = 1;
					if ($jml_3>$batas_target OR $jml_4A>$batas_target)
					{
						//Blok warna hijau tua
						$ketuntasan = 100;
						$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
						$item++;
						blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
					}
					else
					{
						if (($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
						//Blok warna hijau muda
						{
							$ketuntasan = 75;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
						else
						{
							if ($jumlah_total>=1)
							//Blok warna kuning
							{
								$ketuntasan = 50;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("kuning",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
							else
							//Blok warna merah
							{
								$ketuntasan = 0;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("merah",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
						}
					}
				}
				//End - Pewarnaan Capaian Level 3A dan 3B

				//Start - Pewarnaan Capaian Level 2
				if (($data[skdi_level]=="2" OR $data[k_level]=="2" OR $data[ipsg_level]=="2" OR $data[kml_level]=="2") AND $blocked == 0)
				{
					$batas_target = $data[$target_id]/2;
					$blocked = 1;
					if ($jml_2>$batas_target OR $jml_3>$batas_target OR $jml_4A>$batas_target)
					{
						//Blok warna hijau tua
						$ketuntasan = 100;
						$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
						$item++;
						blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
					}
					else
					{
						if (($jml_2<=$batas_target AND $jml_2>=1) OR ($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
						//Blok warna hijau muda
						{
							$ketuntasan = 75;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
						else
						{
							if ($jml_1>=1)
							//Blok warna kuning
							{
								$ketuntasan = 50;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("kuning",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
							else
							//Blok warna merah
							{
								$ketuntasan = 0;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("merah",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
						}
					}
				}
				//End - Pewarnaan Capaian Level 2

				//Start - Pewarnaan Capaian Level 1
				if (($data[skdi_level]=="1" OR $data[k_level]=="1" OR $data[ipsg_level]=="1" OR $data[kml_level]=="1") AND $blocked == 0)
				{
					$batas_target = $data[$target_id]/2;
					$blocked = 1;
					if ($jml_1>$batas_target OR $jml_2>$batas_target OR $jml_3>$batas_target OR $jml_4A>$batas_target)
					{
						//Blok warna hijau tua
						$ketuntasan = 100;
						$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
						$item++;
						blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
					}
					else
					{
						if ($jumlah_total>=1)
						//Blok warna hijau muda
						{
							$ketuntasan = 75;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
						else
						{
							//Blok warna merah
							$ketuntasan = 0;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("merah",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
					}
				}
				//End - Pewarnaan Capaian Level 1

				//Start - Pewarnaan Capaian MKM
				if (($data[skdi_level]=="MKM" OR $data[k_level]=="MKM" OR $data[ipsg_level]=="MKM" OR $data[kml_level]=="MKM") AND $blocked == 0)
				{
					$batas_target = $data[$target_id]/2;
					$blocked = 1;
					if ($jml_MKM>$batas_target)
					{
						//Blok warna hijau tua
						$ketuntasan = 100;
						$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
						$item++;
						blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
					}
					else
					{
						if ($jml_MKM<=$batas_target AND $jml_MKM>=1)
						//Blok warna hijaumuda
						{
							$ketuntasan = 75;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
						else
						{
							//Blok warna kuning
							if ($jml_1>=1 OR $jml_2>=1 OR $jml_3>=1 OR $jml_4A>=1 OR $jml_U>=1)
							{
								$ketuntasan = 50;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("kuning",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
							else
							//Blok warna merah
							{
								$ketuntasan = 0;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("merah",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
						}
					}
				}
				//End - Pewarnaan Capaian MKM
			}
			echo "</tr>";
			$no++;
		}
		$ketuntasan_rata = $jml_ketuntasan/$item;
		if ($ketuntasan_rata<=100 AND $ketuntasan_rata>=80) $grade = "A";
		if ($ketuntasan_rata<80 AND $ketuntasan_rata>=70) $grade = "B";
		if ($ketuntasan_rata<70 AND $ketuntasan_rata>=60) $grade = "C";
		if ($ketuntasan_rata<60 AND $ketuntasan_rata>=50) $grade = "D";
		if ($ketuntasan_rata<50) $grade = "E";
		$ketuntasan_rata = number_format($ketuntasan_rata,2);
		echo "<tr class=\"genap\">";
		echo "<td colspan=10><b>Ketuntasan: $ketuntasan_rata %<br>";
		echo "Grade: $grade</b></td>";
		echo "</tr>";
		echo "</table><br><br>";
		echo "<center><a href=\"#top\"><i>Goto top</i></a></center><br><br>";

		echo "<a id=\"ketrampilan\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Rekap Jurnal Ketrampilan Klinik</font></a><br><br>";
		echo "<table style=\"width:100%\" border=1 id=\"freeze1\">";
		echo "<thead>";
		echo "<tr>";
			echo "<th rowspan=2 style=\"width:5%;text-align:center;vertical-align:middle;font-weight:bold\">No</th>";
			echo "<th rowspan=2 style=\"width:37%;text-align:center;vertical-align:middle;font-weight:bold\">Ketrampilan</th>";
			echo "<th rowspan=2 style=\"width:18%;text-align:center;vertical-align:middle;font-weight:bold\">Level SKDI/Kepmenkes<br>/IPSG/Muatan Lokal</th>";
			echo "<th rowspan=2 style=\"width:10%;text-align:center;vertical-align:middle;font-weight:bold\">Target Minimum</th>";
			echo "<th colspan=6 style=\"width:30%;text-align:center;font-weight:bold\">Level/Kode Kegiatan</th>";
		echo "</tr>";
		echo "<tr>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">1</th>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">2</th>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">3</th>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">4A</th>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">MKM</th>";
			echo "<th style=\"width:5%;text-align:center;font-weight:bold\">U</th>";
		echo "</tr>";
		echo "</thead>";

		$daftar_ketrampilan = mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1' ORDER BY `$target_id` DESC,`ketrampilan` ASC");
		$no = 1;
		while ($data=mysqli_fetch_array($daftar_ketrampilan))
		{
		  echo "<tr>";
		  echo "<td align=center>$no</td>";
		  echo "<td>$data[ketrampilan]</td>";
			echo "<td align=center>$data[skdi_level] / $data[k_level] / $data[ipsg_level] / $data[kml_level]</td>";
			echo "<td align=center>$data[$target_id]</td>";

		  $tot_jml_1=0;
		  $tot_jml_2=0;
		  $tot_jml_3=0;
		  $tot_jml_4A=0;
		  $tot_jml_MKM=0;
		  $tot_jml_U=0;
		  for ($i=1;$i<=$jml_mhsw;$i++)
			{
					if ($data[skdi_level]=="MKM" OR $data[k_level]=="MKM" OR $data[ipsg_level]=="MKM" OR $data[kml_level]=="MKM")
					{
		        $jml_MKM = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND `username`='$_COOKIE[user]'"));
		        $jml_1 = 0;
		        $jml_2 = 0;
		        $jml_3 = 0;
		        $jml_4A = 0;
		        $jml_U = 0;
		      }
		      else
		      {
		        $jml_1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='1' OR `kegiatan`='2')
		          AND `username`='$_COOKIE[user]'"));
		        $jml_2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
		          AND `username`='$_COOKIE[user]'"));
		        $jml_3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
		          AND `username`='$_COOKIE[user]'"));
		        $jml_4A = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND `kegiatan`='10'
		          AND `username`='$_COOKIE[user]'"));
		        $jml_U = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim[$i]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='11' OR `kegiatan`='12')
		          AND `username`='$_COOKIE[user]'"));
		        $jml_MKM = 0;
		      }
		      $tot_jml_1=$tot_jml_1+$jml_1;
		      $tot_jml_2=$tot_jml_2+$jml_2;
		      $tot_jml_3=$tot_jml_3+$jml_3;
		      $tot_jml_4A=$tot_jml_4A+$jml_4A;
		      $tot_jml_MKM=$tot_jml_MKM+$jml_MKM;
		      $tot_jml_U=$tot_jml_U+$jml_U;
		  }
			$jml_1=$tot_jml_1/$jml_mhsw;
			$jml_2=$tot_jml_2/$jml_mhsw;
			$jml_3=$tot_jml_3/$jml_mhsw;
			$jml_4A=$tot_jml_4A/$jml_mhsw;
			$jml_MKM=$tot_jml_MKM/$jml_mhsw;
			$jml_U=$tot_jml_U/$jml_mhsw;

		  $jumlah_total=$jml_1+$jml_2+$jml_3+$jml_4A+$jml_MKM+$jml_U;
		  $jml_1=number_format($jml_1,2);
		  $jml_2=number_format($jml_2,2);;
		  $jml_3=number_format($jml_3,2);;
		  $jml_4A=number_format($jml_4A,2);;
		  $jml_MKM=number_format($jml_MKM,2);;
		  $jml_U=number_format($jml_U,2);;

			//Kasus tidak ada target
			if ($data[$target_id]<1)
			{
				if ($jumlah_total>0)
				{
					//Blok warna hijau tua
					$ketuntasan = 100;
					$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
					$item++;
					blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
				}
				else
				{
					echo "<td style=\"width:5%;\">&nbsp;</td>";
					echo "<td style=\"width:5%;\">&nbsp;</td>";
					echo "<td style=\"width:5%;\">&nbsp;</td>";
					echo "<td style=\"width:5%;\">&nbsp;</td>";
					echo "<td style=\"width:5%;\">&nbsp;</td>";
					echo "<td style=\"width:5%;\">&nbsp;</td>";
				}
			}
			else
			//Kasus ada target
			{
				$blocked = 0;
				//Start - Pewarnaan Capaian Level 4A
				if (($data[skdi_level]=="4A" OR $data[k_level]=="4A" OR $data[ipsg_level]=="4A" OR $data[kml_level]=="4A") AND $blocked == 0)
				{
					$batas_target = $data[$target_id]/2;
					$blocked = 1;
					if ($jml_4A>$batas_target)
					{
						//Blok warna hijau tua
						$ketuntasan = 100;
						$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
						$item++;
						blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
					}
					else
					{
						if ($jml_4A<=$batas_target AND $jml_4A>=1)
						//Blok warna hijau muda
						{
							$ketuntasan = 75;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
						if ($jml_4A<1)
						{
							if ($jml_3>=$batas_target)
							//Blok warna hijau muda
							{
								$ketuntasan = 75;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
							else
							{
								if ($jumlah_total>=1)
								//Blok warna kuning
								{
									$ketuntasan = 50;
									$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
									$item++;
									blok_warna("kuning",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
								}
								else
								//Blok warna merah
								{
									$ketuntasan = 0;
									$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
									$item++;
									blok_warna("merah",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
								}
							}
						}
					}
				}
				//End - Pewarnaan Capaian Level 4A

				//Start - Pewarnaan Capaian Level 3A dan 3B
				if (($data[skdi_level]=="3" OR $data[k_level]=="3" OR $data[ipsg_level]=="3" OR $data[kml_level]=="3"
						OR $data[skdi_level]=="3A" OR $data[k_level]=="3A" OR $data[ipsg_level]=="3A" OR $data[kml_level]=="3A"
						OR $data[skdi_level]=="3B" OR $data[k_level]=="3B" OR $data[ipsg_level]=="3B" OR $data[kml_level]=="3B")
				 		AND $blocked == 0)
				{
					$batas_target = $data[$target_id]/2;
					$blocked = 1;
					if ($jml_3>$batas_target OR $jml_4A>$batas_target)
					{
						//Blok warna hijau tua
						$ketuntasan = 100;
						$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
						$item++;
						blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
					}
					else
					{
						if (($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
						//Blok warna hijau muda
						{
							$ketuntasan = 75;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
						else
						{
							if ($jumlah_total>=1)
							//Blok warna kuning
							{
								$ketuntasan = 50;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("kuning",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
							else
							//Blok warna merah
							{
								$ketuntasan = 0;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("merah",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
						}
					}
				}
				//End - Pewarnaan Capaian Level 3A dan 3B

				//Start - Pewarnaan Capaian Level 2
				if (($data[skdi_level]=="2" OR $data[k_level]=="2" OR $data[ipsg_level]=="2" OR $data[kml_level]=="2") AND $blocked == 0)
				{
					$batas_target = $data[$target_id]/2;
					$blocked = 1;
					if ($jml_2>$batas_target OR $jml_3>$batas_target OR $jml_4A>$batas_target)
					{
						//Blok warna hijau tua
						$ketuntasan = 100;
						$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
						$item++;
						blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
					}
					else
					{
						if (($jml_2<=$batas_target AND $jml_2>=1) OR ($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
						//Blok warna hijau muda
						{
							$ketuntasan = 75;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
						else
						{
							if ($jml_1>=1)
							//Blok warna kuning
							{
								$ketuntasan = 50;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("kuning",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
							else
							//Blok warna merah
							{
								$ketuntasan = 0;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("merah",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
						}
					}
				}
				//End - Pewarnaan Capaian Level 2

				//Start - Pewarnaan Capaian Level 1
				if (($data[skdi_level]=="1" OR $data[k_level]=="1" OR $data[ipsg_level]=="1" OR $data[kml_level]=="1") AND $blocked == 0)
				{
					$batas_target = $data[$target_id]/2;
					$blocked = 1;
					if ($jml_1>$batas_target OR $jml_2>$batas_target OR $jml_3>$batas_target OR $jml_4A>$batas_target)
					{
						//Blok warna hijau tua
						$ketuntasan = 100;
						$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
						$item++;
						blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
					}
					else
					{
						if ($jumlah_total>=1)
						//Blok warna hijau muda
						{
							$ketuntasan = 75;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
						else
						{
							//Blok warna merah
							$ketuntasan = 0;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("merah",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
					}
				}
				//End - Pewarnaan Capaian Level 1

				//Start - Pewarnaan Capaian MKM
				if (($data[skdi_level]=="MKM" OR $data[k_level]=="MKM" OR $data[ipsg_level]=="MKM" OR $data[kml_level]=="MKM") AND $blocked == 0)
				{
					$batas_target = $data[$target_id]/2;
					$blocked = 1;
					if ($jml_MKM>$batas_target)
					{
						//Blok warna hijau tua
						$ketuntasan = 100;
						$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
						$item++;
						blok_warna("hijautua",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
					}
					else
					{
						if ($jml_MKM<=$batas_target AND $jml_MKM>=1)
						//Blok warna hijaumuda
						{
							$ketuntasan = 75;
							$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
							$item++;
							blok_warna("hijaumuda",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
						}
						else
						{
							//Blok warna kuning
							if ($jml_1>=1 OR $jml_2>=1 OR $jml_3>=1 OR $jml_4A>=1 OR $jml_U>=1)
							{
								$ketuntasan = 50;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("kuning",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
							else
							//Blok warna merah
							{
								$ketuntasan = 0;
								$jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
								$item++;
								blok_warna("merah",$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM);
							}
						}
					}
				}
				//End - Pewarnaan Capaian MKM
			}

			echo "</tr>";
			$no++;
		}
		$ketuntasan_rata = $jml_ketuntasan/$item;
		if ($ketuntasan_rata<=100 AND $ketuntasan_rata>=80) $grade = "A";
		if ($ketuntasan_rata<80 AND $ketuntasan_rata>=70) $grade = "B";
		if ($ketuntasan_rata<70 AND $ketuntasan_rata>=60) $grade = "C";
		if ($ketuntasan_rata<60 AND $ketuntasan_rata>=50) $grade = "D";
		if ($ketuntasan_rata<50) $grade = "E";
		$ketuntasan_rata = number_format($ketuntasan_rata,2);
		echo "<tr class=\"genap\">";
		echo "<td colspan=10><b>Ketuntasan: $ketuntasan_rata %<br>";
		echo "Grade: $grade</b></td>";
		echo "</tr>";
		echo "</table><br><br>";
		echo "<center><a href=\"#top\"><i>Goto top</i></a><br><br>";

		echo "<a id=\"catatan\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Catatan Rekap Jurnal</font></a><br><br>";
		echo "<table border=1 style=\"width:60%\">";
		echo "<tr class=\"ganjil\"><td align=center colspan=2>Status Warna Pencapaian</td></tr>";
		echo "<tr class=\"genap\"><td align=center style=\"width:20%\">Warna</td><td align=center style=\"width:80%\">Status</td></tr>";
		echo "<tr><td style=\"background-color:rgba(252, 15, 0, 0.5)\">&nbsp;</td><td>Ada target kegiatan, belum ada kegiatan</td></tr>";
		echo "<tr><td style=\"background-color:rgba(252, 255, 0, 0.5)\">&nbsp;</td><td>Ada target kegiatan, kegiatan belum sesuai level</td></tr>";
		echo "<tr><td style=\"background-color:rgba(0, 250, 0, 0.5)\">&nbsp;</td><td>Ada target kegiatan, kegiatan sesuai level belum sampai 50%</td></tr>";
		echo "<tr><td style=\"background-color:rgba(0, 100, 0, 0.75)\">&nbsp;</td><td>Ada target kegiatan, target telah tercapai</td></tr>";
		echo "<tr><td>&nbsp;</td><td>Tidak ada target kegiatan</td></tr>";
		echo "</table><br><br>";
		echo "<table border=1 style=\"width:65%\">";
		echo "<tr class=\"ganjil\"><td align=center colspan=2>Level/Kode Capaian Kegiatan</td></tr>";
		echo "<tr class=\"genap\"><td align=center style=\"width:15%\">Level/Kode</td><td align=center style=\"width:85%\">Capaian Kegiatan</td></tr>";
		echo "<tr>";
			echo "<td>Level 1</td>";
			echo "<td>";
				echo "MENDENGARKAN kuliah/tentiran/mentoring.<br>";
				echo "MENDENGARKAN tentiran residen.<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Level 2</td>";
			echo "<td>";
				echo "MENGIKUTI/MENYAKSIKAN bedside teaching.<br>";
				echo "MENYAKSIKAN DEMO pada pasien.<br>";
				echo "MENYAKSIKAN DEMO pada video.<br>";
				echo "MENJADI PRESENTAN/PENYANGGAH pada laporan jaga/laporan kasus/jurnal reading.<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Level 3</td>";
			echo "<td>";
				echo "ASISTEN OPERATOR/ASISTEN TINDAKAN.<br>";
				echo "SIMULASI kasus pada PS/manekin/sesama koas.<br>";
				echo "SIMULASI manajemen terapi/resep.<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Level 4A</td>";
			echo "<td>";
				echo "MELAKUKAN SENDIRI pada pasien sungguhan (dengan supervisi).<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr><td>Kode MKM</td><td>Masalah Kesehatan Masyarakat<br></td></tr>";
		echo "<tr><td>Kode U</td><td>Kegiatan Ujian Teori/Praktek<br></td></tr>";
		echo "</table><br><br>";
		echo "<a href=\"#top\"><i>Goto top</i></a><br><br>";
		$delete_dummy_penyakit = mysqli_query($con,"DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$_COOKIE[user]'");
		$delete_dummy_ketrampilan = mysqli_query($con,"DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$_COOKIE[user]'");

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
