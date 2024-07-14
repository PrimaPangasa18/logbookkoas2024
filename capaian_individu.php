<HTML>
<head>
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
	  if ($_COOKIE['level']==2) {include "menu2.php";}
	  if ($_COOKIE['level']==3) {include "menu3.php";}
		echo "<div class=\"text_header\">KETUNTASAN/GRADE KOAS</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">KETUNTASAN/GRADE KOAS</font></h4>";

		$id_stase = $_GET[stase];
		$nim_mhsw = $_GET[nim];
		if ($id_stase=="all") $filterstase = "`id` LIKE "."'%M%'";
		else $filterstase = "`id`="."'$id_stase'";
		$nama_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama`,`angkatan` FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw'"));
		echo "<table border=0 style=\"width:50%\">";
		echo "<tr>";
		echo "<td style=\"width:30%\">Nama Mahasiswa</td>";
		echo "<td style=\"width:70%\">: $nama_mhsw[nama]</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>NIM</td>";
		echo "<td>: $nim_mhsw</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Angkatan Koas</td>";
		echo "<td>: $nama_mhsw[angkatan]</td>";
		echo "</tr>";
		echo "</table><br><br>";

		echo "</center><a href=\"#penyakit\">Ketuntasan/Grade Jurnal Penyakit</a><br>";
		echo "<a href=\"#ketrampilan\">Ketuntasan/Grade Jurnal Ketrampilan Klinik</a><br><br>";

		//Ketuntasan Jurnal Penyakit
		$stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE $filterstase ORDER BY `id`");
		echo "<a id=\"penyakit\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Ketuntasan/Grade Jurnal Penyakit</font></a><br><br>";
		echo "<table style=\"width:100%\" border=1 id=\"freeze\">";
		echo "<thead>";
		echo "<th style=\"width:4%\">No</th>";
		echo "<th style=\"width:28%\">Kepaniteraan (Stase)</th>";
		echo "<th style=\"width:13%\">Tgl Mulai</th>";
		echo "<th style=\"width:13%\">Tgl Selesai</th>";
		echo "<th style=\"width:14%\">Status</th>";
		echo "<th style=\"width:14%\">Evaluasi</th>";
		echo "<th style=\"width:9%\">Ketuntasan</th>";
		echo "<th style=\"width:5%\">Grade</th>";
		echo "</thead>";

		$no = 1;
		$kelas = "ganjil";
		while ($data_stase=mysqli_fetch_array($stase))
		{
			echo "<tr class=\"$kelas\">";
				echo "<td>$no</td>";
				echo "<td>$data_stase[kepaniteraan]</td>";
				$stase_id="stase_".$data_stase[id];
				$data_stase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'"));
				$tanggal_mulai = tanggal_indo($data_stase_mhsw[tgl_mulai]);
				$tanggal_selesai = tanggal_indo($data_stase_mhsw[tgl_selesai]);
				echo "<td align=center>$tanggal_mulai</td>";
				echo "<td align=center>$tanggal_selesai</td>";
				switch ($data_stase_mhsw[status])
				{
					case '':
						{
							$status = "BELUM TERJADWAL";
							echo "<td><font style=\"color:red\">$status</font></td>";
						}
					break;
					case '0':
						{
							$status = "BELUM AKTIF/ DILAKSANAKAN";
							echo "<td><font style=\"color:grey\">$status</font></td>";
						}
					break;
					case '1':
						{
							$status = "SEDANG BERJALAN (AKTIF)";
							echo "<td><font style=\"color:green\">$status</font></td>";
						}
					break;
					case '2':
						{
							$status = "SUDAH SELESAI/ TERLEWATI";
							echo "<td><font style=\"color:blue\">$status</font></td>";
						}
					break;
				}
				switch ($data_stase_mhsw[evaluasi])
				{
					case '':
						{
							$evaluasi = "BELUM TERJADWAL";
							echo "<td><font style=\"color:red\">$evaluasi</font></td>";
						}
					break;
					case '0':
						{
							$evaluasi = "BELUM MENGISI";
							echo "<td><font style=\"color:red\">$evaluasi</font></td>";
						}
					break;
					case '1':
						{
							$evaluasi = "SUDAH MENGISI";
							echo "<td><font style=\"color:green\">$evaluasi</font></td>";
						}
					break;
				}

				$data_jurnal_penyakit = mysqli_query($con,"SELECT * FROM `jurnal_penyakit` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]' AND `status`='1'");
				$delete_dummy_penyakit = mysqli_query($con,"DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$_COOKIE[user]'");
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

				//Perhitungan Ketercapaian
				$include_id = "include_".$data_stase[id];
				$target_id = "target_".$data_stase[id];
				$daftar_penyakit = mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1'");
				$jml_ketuntasan = 0;
				$item = 0;
				$ketuntasan = 0;
				while ($data=mysqli_fetch_array($daftar_penyakit))
				{
					if ($data[skdi_level]=="MKM" OR $data[k_level]=="MKM" OR $data[ipsg_level]=="MKM" OR $data[kml_level]=="MKM")
					{
						$jml_MKM = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
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
						$jml_1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
							AND (`kegiatan`='1' OR `kegiatan`='2')
							AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
							AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
							AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_4A = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
							AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
							AND `kegiatan`='10'
							AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
						$jml_U = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
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
				      }
				      else
				      {
				        if ($jml_4A<=$batas_target AND $jml_4A>=1)
				        //Blok warna hijau muda
				        {
				          $ketuntasan = 75;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
				        }
				        if ($jml_4A<1)
				        {
				          if ($jml_3>=$batas_target)
									{
										//Blok warna hijau muda
										$ketuntasan = 75;
					          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
					          $item++;
									}
									else
									{
										if ($jumlah_total>=1)
										{
											//Blok warna kuning
											$ketuntasan = 50;
					            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
					            $item++;
										}
										else
										{
											//Blok warna merah
											$ketuntasan = 0;
					            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
					            $item++;
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
				      }
				      else
				      {
				        if (($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
				        //Blok warna hijau muda
				        {
				          $ketuntasan = 75;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
				        }
				        else
				        {
				          if ($jml_2>=1 OR $jumlah_total>=1)
				          //Blok warna kuning
				          {
				            $ketuntasan = 50;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
				          }
				          else
				          //Blok warna merah
				          {
				            $ketuntasan = 0;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
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
				      }
				      else
				      {
				        if (($jml_2<=$batas_target AND $jml_2>=1) OR ($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
				        //Blok warna hijau muda
				        {
				          $ketuntasan = 75;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
				        }
				        else
				        {
				          if ($jml_1>=1)
				          //Blok warna kuning
				          {
				            $ketuntasan = 50;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
				          }
				          else
				          //Blok warna merah
				          {
				            $ketuntasan = 0;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
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
				      }
				      else
				      {
				        if ($jumlah_total>=1)
				        //Blok warna hijau muda
				        {
				          $ketuntasan = 75;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
				        }
				        else
				        {
				          //Blok warna merah
				          $ketuntasan = 0;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
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
				      }
				      else
				      {
				        if ($jml_MKM<=$batas_target AND $jml_MKM>=1)
				        //Blok warna hijaumuda
				        {
				          $ketuntasan = 75;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
				        }
				        else
				        {
				          //Blok warna kuning
				          if ($jml_1>=1 OR $jml_2>=1 OR $jml_3>=1 OR $jml_4A>=1 OR $jml_U>=1)
				          {
				            $ketuntasan = 50;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
				          }
				          else
				          //Blok warna merah
				          {
				            $ketuntasan = 0;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
				          }
				        }
				      }
				    }
				    //End - Pewarnaan Capaian MKM
					}
				}
				$ketuntasan_rata = $jml_ketuntasan/$item;
				if ($ketuntasan_rata<=100 AND $ketuntasan_rata>=80) $grade = "A";
				if ($ketuntasan_rata<80 AND $ketuntasan_rata>=70) $grade = "B";
				if ($ketuntasan_rata<70 AND $ketuntasan_rata>=60) $grade = "C";
				if ($ketuntasan_rata<60 AND $ketuntasan_rata>=50) $grade = "D";
				if ($ketuntasan_rata<50) $grade = "E";
				$ketuntasan_rata = number_format($ketuntasan_rata,2);
				echo "<td align=center>$ketuntasan_rata %</td>";
				echo "<td align=center>$grade</td>";
				//-----
			echo "</tr>";
			$no++;
			if ($kelas=="ganjil") $kelas="genap";
			else $kelas="ganjil";
			$delete_dummy_penyakit = mysqli_query($con,"DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$_COOKIE[user]'");
		}
		echo "</table><br><br>";
		echo "<center><a href=\"#top\"><i>Goto top</i></a></center><br><br>";

		//Ketuntasan Jurnal Ketrampilan
		$stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE $filterstase ORDER BY `id`");
		echo "<a id=\"ketrampilan\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Ketuntasan/Grade Jurnal Ketrampilan</font></a><br><br>";
		echo "<table style=\"width:100%\" border=1 id=\"freeze1\">";
		echo "<thead>";
		echo "<th style=\"width:4%\">No</th>";
		echo "<th style=\"width:28%\">Kepaniteraan (Stase)</th>";
		echo "<th style=\"width:13%\">Tgl Mulai</th>";
		echo "<th style=\"width:13%\">Tgl Selesai</th>";
		echo "<th style=\"width:14%\">Status</th>";
		echo "<th style=\"width:14%\">Evaluasi</th>";
		echo "<th style=\"width:9%\">Ketuntasan</th>";
		echo "<th style=\"width:5%\">Grade</th>";
		echo "</thead>";

		$no = 1;
		$kelas = "ganjil";
		while ($data_stase=mysqli_fetch_array($stase))
		{
		  echo "<tr class=\"$kelas\">";
		    echo "<td>$no</td>";
		    echo "<td>$data_stase[kepaniteraan]</td>";
		    $stase_id="stase_".$data_stase[id];
		    $data_stase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'"));
		    $tanggal_mulai = tanggal_indo($data_stase_mhsw[tgl_mulai]);
		    $tanggal_selesai = tanggal_indo($data_stase_mhsw[tgl_selesai]);
		    echo "<td align=center>$tanggal_mulai</td>";
		    echo "<td align=center>$tanggal_selesai</td>";
		    switch ($data_stase_mhsw[status])
		    {
		      case '':
		        {
		          $status = "BELUM TERJADWAL";
		          echo "<td><font style=\"color:red\">$status</font></td>";
		        }
		      break;
		      case '0':
		        {
		          $status = "BELUM AKTIF/ DILAKSANAKAN";
		          echo "<td><font style=\"color:grey\">$status</font></td>";
		        }
		      break;
		      case '1':
		        {
		          $status = "SEDANG BERJALAN (AKTIF)";
		          echo "<td><font style=\"color:green\">$status</font></td>";
		        }
		      break;
		      case '2':
		        {
		          $status = "SUDAH SELESAI/ TERLEWATI";
		          echo "<td><font style=\"color:blue\">$status</font></td>";
		        }
		      break;
		    }
		    switch ($data_stase_mhsw[evaluasi])
		    {
		      case '':
		        {
		          $evaluasi = "BELUM TERJADWAL";
		          echo "<td><font style=\"color:red\">$evaluasi</font></td>";
		        }
		      break;
		      case '0':
		        {
		          $evaluasi = "BELUM MENGISI";
		          echo "<td><font style=\"color:red\">$evaluasi</font></td>";
		        }
		      break;
		      case '1':
		        {
		          $evaluasi = "SUDAH MENGISI";
		          echo "<td><font style=\"color:green\">$evaluasi</font></td>";
		        }
		      break;
		    }

				$data_jurnal_ketrampilan = mysqli_query($con,"SELECT * FROM `jurnal_ketrampilan` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]' AND `status`='1'");
				$delete_dummy_ketrampilan = mysqli_query($con,"DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$_COOKIE[user]'");
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

		    //Perhitungan Ketercapaian
		    $include_id = "include_".$data_stase[id];
		    $target_id = "target_".$data_stase[id];
		    $daftar_ketrampilan = mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1'");
		    $jml_ketuntasan = 0;
		    $item = 0;
		    $ketuntasan = 0;
		    while ($data=mysqli_fetch_array($daftar_ketrampilan))
		    {
					if ($data[skdi_level]=="MKM" OR $data[k_level]=="MKM" OR $data[ipsg_level]=="MKM" OR $data[kml_level]=="MKM")
					{
		        $jml_MKM = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
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
		        $jml_1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='1' OR `kegiatan`='2')
		          AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		        $jml_2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
		          AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		        $jml_3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
		          AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		        $jml_4A = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
		          AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
		          AND `kegiatan`='10'
		          AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
		        $jml_U = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$nim_mhsw' AND `stase`='$data_stase[id]'
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
				      }
				      else
				      {
				        if ($jml_4A<=$batas_target AND $jml_4A>=1)
				        //Blok warna hijau muda
				        {
				          $ketuntasan = 75;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
				        }
				        if ($jml_4A<1)
				        {
				          if ($jml_3>=$batas_target)
									{
										//Blok warna hijau muda
										$ketuntasan = 75;
					          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
					          $item++;
									}
									else
									{
										if ($jumlah_total>=1)
										{
											//Blok warna kuning
											$ketuntasan = 50;
					            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
					            $item++;
										}
										else
										{
											//Blok warna merah
											$ketuntasan = 0;
					            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
					            $item++;
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
				      }
				      else
				      {
				        if (($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
				        //Blok warna hijau muda
				        {
				          $ketuntasan = 75;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
				        }
				        else
				        {
				          if ($jml_2>=1 OR $jumlah_total>=1)
				          //Blok warna kuning
				          {
				            $ketuntasan = 50;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
				          }
				          else
				          //Blok warna merah
				          {
				            $ketuntasan = 0;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
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
				      }
				      else
				      {
				        if (($jml_2<=$batas_target AND $jml_2>=1) OR ($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
				        //Blok warna hijau muda
				        {
				          $ketuntasan = 75;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
				        }
				        else
				        {
				          if ($jml_1>=1)
				          //Blok warna kuning
				          {
				            $ketuntasan = 50;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
				          }
				          else
				          //Blok warna merah
				          {
				            $ketuntasan = 0;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
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
				      }
				      else
				      {
				        if ($jumlah_total>=1)
				        //Blok warna hijau muda
				        {
				          $ketuntasan = 75;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
				        }
				        else
				        {
				          //Blok warna merah
				          $ketuntasan = 0;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
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
				      }
				      else
				      {
				        if ($jml_MKM<=$batas_target AND $jml_MKM>=1)
				        //Blok warna hijaumuda
				        {
				          $ketuntasan = 75;
				          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				          $item++;
				        }
				        else
				        {
				          //Blok warna kuning
				          if ($jml_1>=1 OR $jml_2>=1 OR $jml_3>=1 OR $jml_4A>=1 OR $jml_U>=1)
				          {
				            $ketuntasan = 50;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
				          }
				          else
				          //Blok warna merah
				          {
				            $ketuntasan = 0;
				            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
				            $item++;
				          }
				        }
				      }
				    }
				    //End - Pewarnaan Capaian MKM
				  }
		    }
		    $ketuntasan_rata = $jml_ketuntasan/$item;
		    if ($ketuntasan_rata<=100 AND $ketuntasan_rata>=80) $grade = "A";
		    if ($ketuntasan_rata<80 AND $ketuntasan_rata>=70) $grade = "B";
		    if ($ketuntasan_rata<70 AND $ketuntasan_rata>=60) $grade = "C";
		    if ($ketuntasan_rata<60 AND $ketuntasan_rata>=50) $grade = "D";
		    if ($ketuntasan_rata<50) $grade = "E";
		    $ketuntasan_rata = number_format($ketuntasan_rata,2);
		    echo "<td align=center>$ketuntasan_rata %</td>";
		    echo "<td align=center>$grade</td>";
		    //-----
		  echo "</tr>";
		  $no++;
		  if ($kelas=="ganjil") $kelas="genap";
		  else $kelas="ganjil";
			$delete_dummy_ketrampilan = mysqli_query($con,"DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$_COOKIE[user]'");
		}
		echo "</table><br><br>";
		echo "<center><a href=\"#top\"><i>Goto top</i></a></center><br><br>";

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
