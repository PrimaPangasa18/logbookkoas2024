<HTML>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />

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

		echo "<div class=\"text_header\" id=\"top\">REKAP LOGBOOK</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP LOGBOOK KEPANITERAAN (STASE)</font></h4><br>";

		$id_stase = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));

		$stase_id = "stase_".$id_stase;
		$include_id = "include_".$id_stase;
		$target_id = "target_".$id_stase;
		$data_stase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'"));
		$kurang_hari = '-4 days';
		$batas_akhir = date('Y-m-d', strtotime($kurang_hari, strtotime($data_stase_mhsw[tgl_selesai])));

		$mulai_stase = date_create($data_stase_mhsw[tgl_mulai]);
		$akhir_stase = date_create($data_stase_mhsw[tgl_selesai]);
		$hari_stase = date_diff($mulai_stase,$akhir_stase);
		$batas_tengah = (int)(($hari_stase->days+1)/2)-4;//kurang 4 hari


		/*
		//Catatan Khusus untuk kasus Stase Bedah tgl mulai 17 Feb 2020 (akan pindah ke RSUD Kendal pada pekan ke-5)!!!
		if ($id_stase=="M101" AND $data_stase_mhsw['tgl_mulai']=="2020-02-17")
		{
		    $batas_tengah = (int)($data_stase[hari_stase]/2) - 7;
		}
	    //------------------------------------------------------------
	    */

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		echo "<table style=\"border:1;\">";
			echo "<tr><td>Kepaniteraan (stase)</td><td>: $data_stase[kepaniteraan]</td></tr>";
			$tgl_mulai = tanggal_indo($data_stase_mhsw[tgl_mulai]);
			$tgl_selesai = tanggal_indo($data_stase_mhsw[tgl_selesai]);
			if ($data_stase_mhsw[tgl_mulai]=="2000-01-01" OR $data_stase_mhsw[tgl_mulai]=="")
			{
				echo "<tr><td>Tanggal mulai kepaniteraan (stase)</td><td>: -</td></tr>";
				echo "<tr><td>Tanggal selesai kepaniteraan (stase)</td><td>: -</td></tr>";
			}
			else {
				echo "<tr><td>Tanggal mulai kepaniteraan (stase)</td><td>: $tgl_mulai</td></tr>";
				echo "<tr><td>Tanggal selesai kepaniteraan (stase)</td><td>: $tgl_selesai</td></tr>";
			}
			echo "<tr><td>Status pengisian log book</td>";
			switch ($data_stase_mhsw[status])
			{
				case '':
					{
						$status = "BELUM TERJADWAL";
						echo "<td>: <font style=\"color:red\">$status</font></td></tr>";
					}
				break;
				case '0':
					{
						$status = "BELUM AKTIF/DILAKSANAKAN";
						echo "<td>: <font style=\"color:grey\">$status</font></td></tr>";
					}
				break;
				case '1':
					{
						$status = "SEDANG BERJALAN (AKTIF)";
						echo "<td>: <font style=\"color:green\">$status</font></td></tr>";
					}
				break;
				case '2':
					{
						$status = "SUDAH SELESAI/TERLEWATI";
						echo "<td>: <font style=\"color:blue\">$status</font></td></tr>";
					}
				break;
			}

		echo "</table><br><br>";

		echo "</center><a href=\"#penyakit\">Rekap Jurnal Penyakit</a><br>";
		echo "<a href=\"#ketrampilan\">Rekap Jurnal Ketrampilan Klinik</a><br>";
		echo "<a href=\"#catatan\">Catatan Rekap Jurnal</a><br>";
		echo "<a href=\"#evaluasi\">Evaluasi Akhir Kepaniteraan/Stase</a>";
		if ($data_stase_mhsw[evaluasi]=='0') echo "<font style=\"color:red\">&nbsp;&nbsp;[Status: BELUM]</font><br>";
		else echo "<font style=\"color:green\">&nbsp;&nbsp;[Status: SUDAH]</font><br>";
		echo "<a href=\"#cetak\">Cetak Rekap Jurnal</a><br>";
		echo "<br><br>";

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

		$data_jurnal_penyakit = mysqli_query($con,"SELECT * FROM `jurnal_penyakit` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `status`='1'");
		$delete_dummy_penyakit = mysqli_query($con,"DELETE FROM `jurnal_penyakit_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");
		while ($data=mysqli_fetch_array($data_jurnal_penyakit))
		{
			$insert_penyakit = mysqli_query($con,"INSERT INTO `jurnal_penyakit_dummy`
				(`id`, `nim`, `nama`, `angkatan`,
					`grup`, `hari`, `tanggal`, `stase`,
					`jam_awal`, `jam_akhir`, `kelas`, `lokasi`,
					`kegiatan`, `penyakit1`, `penyakit2`, `penyakit3`,
					`penyakit4`, `dosen`, `status`, `evaluasi`, `username`)
				VALUES
				('$data[id]', '$data[nim]', '$data[nama]', '$data[angkatan]',
				  '$data[grup]', '$data[hari]', '$data[tanggal]', '$data[stase]',
				  '$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]', '$data[lokasi]',
				  '$data[kegiatan]', '$data[penyakit1]', '$data[penyakit2]', '$data[penyakit3]',
				  '$data[penyakit4]', '$data[dosen]', '$data[status]', '$data[evaluasi]', '$_COOKIE[user]')");
		}

		$data_jurnal_ketrampilan = mysqli_query($con,"SELECT * FROM `jurnal_ketrampilan` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `status`='1'");
		$delete_dummy_ketrampilan = mysqli_query($con,"DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");
		while ($data=mysqli_fetch_array($data_jurnal_ketrampilan))
		{
			$insert_ketrampilan = mysqli_query($con,"INSERT INTO `jurnal_ketrampilan_dummy`
				(`id`, `nim`, `nama`, `angkatan`,
					`grup`, `hari`, `tanggal`, `stase`,
					`jam_awal`, `jam_akhir`, `kelas`, `lokasi`,
					`kegiatan`, `ketrampilan1`, `ketrampilan2`, `ketrampilan3`,
					`ketrampilan4`, `dosen`, `status`, `evaluasi`, `username`)
				VALUES
				('$data[id]', '$data[nim]', '$data[nama]', '$data[angkatan]',
				  '$data[grup]', '$data[hari]', '$data[tanggal]', '$data[stase]',
				  '$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]', '$data[lokasi]',
				  '$data[kegiatan]', '$data[ketrampilan1]', '$data[ketrampilan2]', '$data[ketrampilan3]',
				  '$data[ketrampilan4]', '$data[dosen]', '$data[status]', '$data[evaluasi]', '$_COOKIE[user]')");
		}

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

		  if ($data[skdi_level]=="MKM" OR $data[k_level]=="MKM" OR $data[ipsg_level]=="MKM" OR $data[kml_level]=="MKM")
		  {
		    $jml_MKM = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_1 = 0;
		    $jml_2 = 0;
		    $jml_3 = 0;
		    $jml_4A = 0;
		    $jml_U = 0;
		  }
		  else
		  {
		    $jml_1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
		      AND (`kegiatan`='1' OR `kegiatan`='2')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
		      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
		      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_4A = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
		      AND `kegiatan`='10'
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_U = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
		      AND (`kegiatan`='11' OR `kegiatan`='12')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_MKM = 0;
		  }
		  $jumlah_total=$jml_1+$jml_2+$jml_3+$jml_4A+$jml_MKM+$jml_U;

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
		$jml_ketuntasan = 0;
		$item = 0;
		$ketuntasan = 0;
		while ($data=mysqli_fetch_array($daftar_ketrampilan))
		{
		  echo "<tr>";
		  echo "<td align=center>$no</td>";
		  echo "<td>$data[ketrampilan]</td>";
		  echo "<td align=center>$data[skdi_level] / $data[k_level] / $data[ipsg_level] / $data[kml_level]</td>";
		  echo "<td align=center>$data[$target_id]</td>";

		  if ($data[skdi_level]=="MKM" OR $data[k_level]=="MKM" OR $data[ipsg_level]=="MKM" OR $data[kml_level]=="MKM")
		  {
		    $jml_MKM = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_1 = 0;
		    $jml_2 = 0;
		    $jml_3 = 0;
		    $jml_4A = 0;
		    $jml_U = 0;
		  }
		  else
		  {
		    $jml_1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
		      AND (`kegiatan`='1' OR `kegiatan`='2')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_4A = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		      AND `kegiatan`='10'
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_U = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
		      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		      AND (`kegiatan`='11' OR `kegiatan`='12')
		      AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		    $jml_MKM = 0;
		  }

		  $jumlah_total=$jml_1+$jml_2+$jml_3+$jml_4A+$jml_MKM+$jml_U;

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

		echo "<br><a id=\"evaluasi\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Evaluasi Akhir Kepaniteraan/Stase</font></a><br><br>";

		if ($data_stase_mhsw[evaluasi]=='1')
		{
			echo "<font style=\"color:green\">Sudah terisi ... </font><a href=\"lihat_evaluasi.php?id=$id_stase&nim=$_COOKIE[user]&menu=rekap\">LIHAT EVALUASI</a><br><br>";
		}
		else {
			echo "<br><font style=\"color:red\"><< BELUM MENGISI EVALUASI AKHIR!!! >></font><br><br>";
			echo "Silakan mengisi evaluasi akhir kepaniteraan/stase di menu [<a href=\"rotasi_internal.php\">Rotasi Stase</a>] setelah jadwal kegiatan berakhir!<br><br>";
			echo "<a class=\"blink\"><font style=\"color:red\">Catatan: Rekap Akhir Kepaniteraan/Stase hanya bisa dicetak setelah mengisi evaluasi akhir Kepaniteraan/Stase!</a></font><br><br>";
		}

		echo "<a href=\"#top\"><i>Goto top</i></a><br><br><br>";

		//Cetak
		$mulai = date_create($data_stase_mhsw[tgl_mulai]);
		$sekarang = date_create($tgl);
		$hari_skrg = date_diff($mulai,$sekarang);
		$jmlhari_skrg = $hari_skrg->days+1;
		if ($jmlhari_skrg<$batas_tengah) $tengah_stase=0;
		else $tengah_stase=1;
		if ($data_stase_mhsw[evaluasi]=="1" AND $tgl>=$batas_akhir) $akhir_stase="1";
		else $akhir_stase="0";

		echo "<a id=\"cetak\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Cetak Rekap Jurnal</font></a><br><br>";
		echo "<table border=0>";
		echo "<tr>";
			echo "<td align=center>";
			if ($tengah_stase=="1")
				echo "<a href='cetak_setting.php?jenis=penyakit&cetak=tengah&id=$id_stase' target=\"_blank\"><img style=\"border:0;\" src=\"images\printer_run.png\" width=\"40px\" height=\"40px\" title=\"Cetak rekap ke pdf\"></a>";
				else
				echo "<img style=\"border:0;\" src=\"images\printer_stop.png\" width=\"40px\" height=\"40px\" title=\"Cetak rekap ke pdf\">";
			echo "</td>";
			echo "<td align=center>";
			if ($tengah_stase=="1")
				echo "<a href='cetak_setting.php?jenis=ketrampilan&cetak=tengah&id=$id_stase' target=\"_blank\"><img style=\"border:0;\" src=\"images\printer_run.png\" width=\"40px\" height=\"40px\" title=\"Cetak rekap ke pdf\"></a>";
				else
				echo "<img style=\"border:0;\" src=\"images\printer_stop.png\" width=\"40px\" height=\"40px\" title=\"Cetak rekap ke pdf\">";
			echo "</td>";
			echo "<td align=center>";
			if ($akhir_stase=="1")
				echo "<a href='cetak_setting.php?jenis=penyakit&cetak=akhir&id=$id_stase' target=\"_blank\"><img style=\"border:0;\" src=\"images\printer_run.png\" width=\"40px\" height=\"40px\" title=\"Cetak rekap ke pdf\"></a>";
				else
				echo "<img style=\"border:0;\" src=\"images\printer_stop.png\" width=\"40px\" height=\"40px\" title=\"Cetak rekap ke pdf\">";
			echo "</td>";
			echo "<td align=center>";
			if ($akhir_stase=="1")
				echo "<a href='cetak_setting.php?jenis=ketrampilan&cetak=akhir&id=$id_stase' target=\"_blank\"><img style=\"border:0;\" src=\"images\printer_run.png\" width=\"40px\" height=\"40px\" title=\"Cetak rekap ke pdf\"></a>";
				else
				echo "<img style=\"border:0;\" src=\"images\printer_stop.png\" width=\"40px\" height=\"40px\" title=\"Cetak rekap ke pdf\">";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center><font style=\"font-size:0.825em\"><i>Cetak Jurnal Penyakit</i></font></td>";
			echo "<td align=center><font style=\"font-size:0.825em\"><i>Cetak Jurnal Ketrampilan Klinis</i></font></td>";
			echo "<td align=center><font style=\"font-size:0.825em\"><i>Cetak Jurnal Penyakit</i></font></td>";
			echo "<td align=center><font style=\"font-size:0.825em\"><i>Cetak Jurnal Ketrampilan Klinis</i></font></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2 align=center>Cetak Tengah Kepaniteraan (Stase)</td>";
			echo "<td colspan=2 align=center>Cetak Akhir Kepaniteraan (Stase)</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=4 align=center><font style=\"font-size:0.825em\"><i>Ctt: Anda hanya bisa mencetak rekap pada pertengahan kepaniteraan (stase) atau setelah mengisi evaluasi akhir kepaniteraan (stase)</i></font></td>";
		echo "</tr>";
		echo "</table><br><br>";
		echo "<a href=\"#top\"><i>Goto top</i></a><br><br>";
		//----Cetak

		$delete_dummy_penyakit = mysqli_query($con,"DELETE FROM `jurnal_penyakit_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");
		$delete_dummy_ketrampilan = mysqli_query($con,"DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");
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
