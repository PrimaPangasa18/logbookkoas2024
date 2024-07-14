<?php
	include "config.php";
	
	error_reporting("E_ALL ^ E_NOTICE");


	if (!empty($_GET['usr']) AND !empty($_GET['pwd']))
	{
		$pass=$_GET['pwd'];
		$login=mysqli_query($con,"SELECT * FROM `admin` WHERE `username`='$_GET[usr]' AND `password` ='$pass'");
		$ketemu=mysqli_num_rows($login);

	  // Apabila username dan password ditemukan
	  if ($ketemu == 1 )
		{
			
			$data_log=mysqli_fetch_array($login);
			$_COOKIE['user']=$_GET[usr];
			$_COOKIE['pass']=$pass;
			$_COOKIE['level']=$data_log['level'];
			if ($_COOKIE['level']=="5")
			{
				$biodata_mhsw=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_GET[usr]'"));
				$_COOKIE['nama']=$biodata_mhsw['nama'];
				$_COOKIE['gelar']=$biodata_mhsw['nim'];
				$_COOKIE['bagian']=$biodata_mhsw['kode_bagian'];
			}
			else
			{
				$data_dosen=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$_GET[usr]'"));
				$_COOKIE['nama']=$data_dosen['nama'];
				$_COOKIE['gelar']=$data_dosen['gelar'];
				$_COOKIE['bagian']=$data_dosen['kode_bagian'];
			}

			echo "
			<script>
				window.location.href=\"filter_rekap_kegiatan.php\";
			</script>
			";
		}
	    else
		{
			echo "
			<script>
				window.location.href=\"loginfailed.php\";
			</script>
			";
		}
	}
	else
	{ echo "
			<script>
				window.location.href=\"login.php\";
			</script>
			";
	}

?>
