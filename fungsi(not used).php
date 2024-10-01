<?php
date_default_timezone_set('Asia/Jakarta');
set_time_limit(0);

//-------------------------------------
function tanggal_indo($date)
{
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember");

	$tahun = substr($date, 0, 4);
	$bulan = substr($date, 5, 2);
	$tanggal   = substr($date, 8, 2);

	$result = $tanggal . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
	return $result;
}

//-------------------------------------
function tanggal_indo_skt($date)
{
	$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des");

	$tahun = substr($date, 0, 4);
	$bulan = substr($date, 5, 2);
	$tanggal   = substr($date, 8, 2);

	$result = $tanggal . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
	return $result;
}

//-------------------------------------
function ver_user($username,$password)
{
	include "config.php";
	$login=mysqli_query($con,"SELECT * FROM `admin` WHERE username='$username' AND password ='$password'");
	$ketemu=mysqli_num_rows($login);
	return $ketemu;
}

//-------------------------------------
function logout()
{
	session_destroy();
}

//-------------------------------------
function acakstring($panjang)
{
	$karakter = 'QWERTYUIOPLKJHGFDSAZXCVBNM1234567890';
	$string = '';
	for ($i=0;$i<$panjang;$i++)
	{
		$pos = rand(0,strlen($karakter)-1);
		$string .=$karakter[$pos];
	}
	return $string;
}

//-------------------------------------
function blok_warna($warna,$jml_1,$jml_2,$jml_3,$jml_4A,$jml_U,$jml_MKM)
{
	if ($warna=="hijautua")
	{
	  if ($jml_1>0) echo "<td align=center style=\"background-color:rgba(0,100,0,0.75)\">$jml_1</td>";
	  else echo "<td style=\"background-color:rgba(0,100,0,0.75)\">&nbsp;</td>";
	  if ($jml_2>0) echo "<td align=center style=\"background-color:rgba(0,100,0,0.75)\">$jml_2</td>";
	  else echo "<td style=\"background-color:rgba(0,100,0,0.75)\">&nbsp;</td>";
	  if ($jml_3>0) echo "<td align=center style=\"background-color:rgba(0,100,0,0.75)\">$jml_3</td>";
	  else echo "<td style=\"background-color:rgba(0,100,0,0.75)\">&nbsp;</td>";
	  if ($jml_4A>0) echo "<td align=center style=\"background-color:rgba(0,100,0,0.75)\">$jml_4A</td>";
	  else echo "<td style=\"background-color:rgba(0,100,0,0.75)\">&nbsp;</td>";
	  if ($jml_MKM>0) echo "<td align=center style=\"background-color:rgba(0,100,0,0.75)\">$jml_MKM</td>";
	  else echo "<td style=\"background-color:rgba(0,100,0,0.75)\">&nbsp;</td>";
	  if ($jml_U>0) echo "<td align=center style=\"background-color:rgba(0,100,0,0.75)\">$jml_U</td>";
	  else echo "<td style=\"background-color:rgba(0,100,0,0.75)\">&nbsp;</td>";
	}
	if ($warna=="hijaumuda")
	{
		if ($jml_1>0) echo "<td align=center style=\"background-color:rgba(0,250,0,0.5)\">$jml_1</td>";
		else echo "<td style=\"background-color:rgba(0,250,0,0.5)\">&nbsp;</td>";
		if ($jml_2>0) echo "<td align=center style=\"background-color:rgba(0,250,0,0.5)\">$jml_2</td>";
		else echo "<td style=\"background-color:rgba(0,250,0,0.5)\">&nbsp;</td>";
		if ($jml_3>0) echo "<td align=center style=\"background-color:rgba(0,250,0,0.5)\">$jml_3</td>";
		else echo "<td style=\"background-color:rgba(0,250,0,0.5)\">&nbsp;</td>";
		if ($jml_4A>0) echo "<td align=center style=\"background-color:rgba(0,250,0,0.5)\">$jml_4A</td>";
		else echo "<td style=\"background-color:rgba(0,250,0,0.5)\">&nbsp;</td>";
		if ($jml_MKM>0) echo "<td align=center style=\"background-color:rgba(0,250,0,0.5)\">$jml_MKM</td>";
		else echo "<td style=\"background-color:rgba(0,250,0,0.5)\">&nbsp;</td>";
		if ($jml_U>0) echo "<td align=center style=\"background-color:rgba(0,250,0,0.5)\">$jml_U</td>";
		else echo "<td style=\"background-color:rgba(0,250,0,0.5)\">&nbsp;</td>";
	}
	if ($warna=="kuning")
	{
		if ($jml_1>0) echo "<td align=center style=\"background-color:rgb(255, 255, 0, 0.5)\">$jml_1</td>";
		else echo "<td style=\"background-color:rgba(252, 255, 0, 0.5)\">&nbsp;</td>";
		if ($jml_2>0) echo "<td align=center style=\"background-color:rgb(255, 255, 0, 0.5)\">$jml_2</td>";
		else echo "<td style=\"background-color:rgb(255, 255, 0, 0.5)\">&nbsp;</td>";
		if ($jml_3>0) echo "<td align=center style=\"background-color:rgb(255, 255, 0, 0.5)\">$jml_3</td>";
		else echo "<td style=\"background-color:rgb(255, 255, 0, 0.5)\">&nbsp;</td>";
		if ($jml_4A>0) echo "<td align=center style=\"background-color:rgb(255, 255, 0, 0.5)\">$jml_4A</td>";
		else echo "<td style=\"background-color:rgb(255, 255, 0, 0.5)\">&nbsp;</td>";
		if ($jml_MKM>0) echo "<td align=center style=\"background-color:rgb(255, 255, 0, 0.5)\">$jml_MKM</td>";
		else echo "<td style=\"background-color:rgb(255, 255, 0, 0.5)\">&nbsp;</td>";
		if ($jml_U>0) echo "<td align=center style=\"background-color:rgb(255, 255, 0, 0.5)\">$jml_U</td>";
		else echo "<td style=\"background-color:rgb(255, 255, 0, 0.5)\">&nbsp;</td>";
	}
	if ($warna=="merah")
	{
		if ($jml_1>0) echo "<td align=center style=\"background-color:rgb(255, 15, 0, 0.5)\">$jml_1</td>";
		else echo "<td style=\"background-color:rgba(252, 15, 0, 0.5)\">&nbsp;</td>";
		if ($jml_2>0) echo "<td align=center style=\"background-color:rgb(255, 15, 0, 0.5)\">$jml_2</td>";
		else echo "<td style=\"background-color:rgb(255, 15, 0, 0.5)\">&nbsp;</td>";
		if ($jml_3>0) echo "<td align=center style=\"background-color:rgb(255, 15, 0, 0.5)\">$jml_3</td>";
		else echo "<td style=\"background-color:rgb(255, 15, 0, 0.5)\">&nbsp;</td>";
		if ($jml_4A>0) echo "<td align=center style=\"background-color:rgb(255, 15, 0, 0.5)\">$jml_4A</td>";
		else echo "<td style=\"background-color:rgb(255, 15, 0, 0.5)\">&nbsp;</td>";
		if ($jml_MKM>0) echo "<td align=center style=\"background-color:rgb(255, 15, 0, 0.5)\">$jml_MKM</td>";
		else echo "<td style=\"background-color:rgb(255, 15, 0, 0.5)\">&nbsp;</td>";
		if ($jml_U>0) echo "<td align=center style=\"background-color:rgb(255, 15, 0, 0.5)\">$jml_U</td>";
		else echo "<td style=\"background-color:rgb(255, 15, 0, 0.5)\">&nbsp;</td>";
	}
}

function ketuntasan_penyakit($con,$include_id,$target_id,$mhsw_nim,$user)
{
	$daftar_penyakit = mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1'");
	$jml_ketuntasan = 0;
	$item = 0;
	$ketuntasan = 0;
	while ($data=mysqli_fetch_array($daftar_penyakit))
	{
	  if ($data['skdi_level']=="MKM" OR $data['k_level']=="MKM" OR $data['ipsg_level']=="MKM" OR $data['kml_level']=="MKM")
	  {
	    $jml_MKM = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
	      AND `username`='$user'"));
	    $jml_1 = 0;
	    $jml_2 = 0;
	    $jml_3 = 0;
	    $jml_4A = 0;
	    $jml_U = 0;
	  }
	  else
	  {
	    $jml_1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
	      AND (`kegiatan`='1' OR `kegiatan`='2')
	      AND `username`='$user'"));
	    $jml_2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
	      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
	      AND `username`='$user'"));
	    $jml_3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
	      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
	      AND `username`='$user'"));
	    $jml_4A = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
	      AND `kegiatan`='10'
	      AND `username`='$user'"));
	    $jml_U = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
	      AND (`kegiatan`='11' OR `kegiatan`='12')
	      AND `username`='$user'"));
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
	    if (($data['skdi_level']=="4A" OR $data['k_level']=="4A" OR $data['ipsg_level']=="4A" OR $data['kml_level']=="4A") AND $blocked == 0)
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
	    if (($data['skdi_level']=="3" OR $data['k_level']=="3" OR $data['ipsg_level']=="3" OR $data['kml_level']=="3"
	        OR $data['skdi_level']=="3A" OR $data['k_level']=="3A" OR $data['ipsg_level']=="3A" OR $data['kml_level']=="3A"
	        OR $data['skdi_level']=="3B" OR $data['k_level']=="3B" OR $data['ipsg_level']=="3B" OR $data['kml_level']=="3B")
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
	    if (($data['skdi_level']=="2" OR $data['k_level']=="2" OR $data['ipsg_level']=="2" OR $data['kml_level']=="2") AND $blocked == 0)
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
	    if (($data['skdi_level']=="1" OR $data['k_level']=="1" OR $data['ipsg_level']=="1" OR $data['kml_level']=="1") AND $blocked == 0)
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
	    if (($data['skdi_level']=="MKM" OR $data['k_level']=="MKM" OR $data['ipsg_level']=="MKM" OR $data['kml_level']=="MKM") AND $blocked == 0)
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
	if ($item==0) $grade_penyakit = 0;
	else $grade_penyakit = $jml_ketuntasan/$item;
	$grade_penyakit = number_format($grade_penyakit,2);
	return $grade_penyakit;
}

function ketuntasan_ketrampilan ($con,$include_id,$target_id,$mhsw_nim,$user)
{
	$daftar_ketrampilan = mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1'");
	$jml_ketuntasan = 0;
	$item = 0;
	$ketuntasan = 0;
	while ($data=mysqli_fetch_array($daftar_ketrampilan))
	{
	  if ($data['skdi_level']=="MKM" OR $data['k_level']=="MKM" OR $data['ipsg_level']=="MKM" OR $data['kml_level']=="MKM")
	  {
	    $jml_MKM = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
	      AND `username`='$user'"));
	    $jml_1 = 0;
	    $jml_2 = 0;
	    $jml_3 = 0;
	    $jml_4A = 0;
	    $jml_U = 0;
	  }
	  else
	  {
	    $jml_1 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]' OR `ketrampilan4`='$data[id]')
	      AND (`kegiatan`='1' OR `kegiatan`='2')
	      AND `username`='$user'"));
	    $jml_2 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
	      AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
	      AND `username`='$user'"));
	    $jml_3 = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
	      AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
	      AND `username`='$user'"));
	    $jml_4A = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
	      AND `kegiatan`='10'
	      AND `username`='$user'"));
	    $jml_U = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$mhsw_nim'
	      AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
	      AND (`kegiatan`='11' OR `kegiatan`='12')
	      AND `username`='$user'"));
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
	    if (($data['skdi_level']=="4A" OR $data['k_level']=="4A" OR $data['ipsg_level']=="4A" OR $data['kml_level']=="4A") AND $blocked == 0)
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
	    if (($data['skdi_level']=="3" OR $data['k_level']=="3" OR $data['ipsg_level']=="3" OR $data['kml_level']=="3"
	        OR $data['skdi_level']=="3A" OR $data['k_level']=="3A" OR $data['ipsg_level']=="3A" OR $data['kml_level']=="3A"
	        OR $data['skdi_level']=="3B" OR $data['k_level']=="3B" OR $data['ipsg_level']=="3B" OR $data['kml_level']=="3B")
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
	    if (($data['skdi_level']=="2" OR $data['k_level']=="2" OR $data['ipsg_level']=="2" OR $data['kml_level']=="2") AND $blocked == 0)
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
	    if (($data['skdi_level']=="1" OR $data['k_level']=="1" OR $data['ipsg_level']=="1" OR $data['kml_level']=="1") AND $blocked == 0)
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
	    if (($data['skdi_level']=="MKM" OR $data['k_level']=="MKM" OR $data['ipsg_level']=="MKM" OR $data['kml_level']=="MKM") AND $blocked == 0)
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
	if ($item==0) $grade_penyakit = 0;
	else $grade_ketrampilan = $jml_ketuntasan/$item;
	$grade_ketrampilan = number_format($grade_ketrampilan,2);
	return $grade_ketrampilan;
}

function filter_nilai($stase_id)
{
	//filter nilai
	switch ($stase_id)
	{
		//Neurologi: MCQ dan OSCE
		case 'M092' :
			$filter_test = "`id`='5' OR `id`='6'";
		break;
		//Psikiatri: Pre-Test dan Post-Test
		case 'M093' :
			$filter_test = "`id`='1' OR `id`='2'";
		break;
		//THT-KL: Pre-Test, Post-Test, Sikap, Tugas
		case 'M105' :
			$filter_test = "`id`='1' OR `id`='2' OR `id`='3' OR `id`='4'";
		break;
		//IKM-KP: Post-Test
		case 'M095' :
			$filter_test = "`id`='2'";
		break;
		//Anestesi: Pre-Test, Post-Test, Sikap, Tugas, MCQ
		case 'M102' :
			$filter_test = "`id`='1' OR `id`='2' OR `id`='3' OR `id`='4' OR `id`='6'";
		break;
		//Radiologi: Sikap, OSCE, MCQ
		case 'M103' :
			$filter_test = "`id`='3' OR `id`='5' OR `id`='6'";
		break;
		//Mata: OSCE, MCQ
		case 'M104' :
			$filter_test = "`id`='5' OR `id`='6'";
		break;
		//IKFR: Pre-Test, Post-Test, Sikap, OSCE
		case 'M094' :
			$filter_test = "`id`='1' OR `id`='2' OR `id`='3' OR `id`='5'";
		break;
		//IKGM: Sikap, OSCA
		case 'M106' :
			$filter_test = "`id`='3' OR `id`='5'";
		break;
		//Forensik: Pre-Test, Sikap, Kompre Stasi I-V
		case 'M112' :
			$filter_test = "`id`='1' OR `id`='3' OR `id`='7' OR `id`='8' OR `id`='9' OR `id`='10' OR `id`='11'";
		break;
		case 'M114' :
			$filter_test = "`id`='3' OR `id`='5' OR `id`='12'";
		break;
		case 'M091' :
			$filter_test = "`id`='6'";
		break;
		case 'M111' :
			$filter_test = "`id`='6' OR `id`='13' OR `id`='14'";
		break;
		case 'M101' :
			$filter_test = "`id`='1' OR `id`='2' OR `id`='5' OR `id`='15'";
		break;
		case 'M113' :
			$filter_test = "`id`='1' OR `id`='2' OR `id`='5'";
		break;
	}
	return $filter_test;
}
?>
