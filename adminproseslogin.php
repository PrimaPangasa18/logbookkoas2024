<?php
include "config.php";
error_reporting("E_ALL ^ E_NOTICE");

if ($_COOKIE["user_login"]!="" AND !empty($_COOKIE["user_login"]) AND $_GET["st"]!="new" AND empty($_POST['username']) AND empty($_POST['password']))
{
	$_POST['username']=$_COOKIE["user_login"];
	$_POST['password']=$_COOKIE["userpassword"];
	$_REQUEST['commit']="1";
}

$pass=md5($_POST['password']);
if(isset($_REQUEST['commit']))
{
	$login=mysqli_query($con,"SELECT * FROM `admin` WHERE `username`='$_POST[username]' AND `password` ='$pass'");
	$ketemu=mysqli_num_rows($login);

  // Apabila username dan password ditemukan
  if ($ketemu == 1 )
	{
		$data_log=mysqli_fetch_array($login);
		setcookie ("user",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));
		setcookie ("pass",$pass,time()+ (10 * 365 * 24 * 60 * 60));
		setcookie ("level",$data_log['level'],time()+ (10 * 365 * 24 * 60 * 60));

		if ($data_log['level']=="5")
		{
			$biodata_mhsw=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_POST[username]'"));
			setcookie ("nama",$biodata_mhsw['nama'],time()+ (10 * 365 * 24 * 60 * 60));
			setcookie ("gelar",$biodata_mhsw['nim'],time()+ (10 * 365 * 24 * 60 * 60));
			setcookie ("bagian",$biodata_mhsw['kode_bagian'],time()+ (10 * 365 * 24 * 60 * 60));
		}
		else
		{
			$data_dosen=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$_POST[username]'"));
			setcookie ("nama",$data_dosen['nama'],time()+ (10 * 365 * 24 * 60 * 60));
			setcookie ("gelar",$data_dosen['gelar'],time()+ (10 * 365 * 24 * 60 * 60));
			setcookie ("bagian",$data_dosen['kode_bagian'],time()+ (10 * 365 * 24 * 60 * 60));
		}
		if(!empty($_POST["remember"])) {
    	setcookie ("user_login",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));
    	setcookie ("userpassword",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
  	    }
		else {
    	if(isset($_COOKIE["user_login"])) {
     		setcookie ("user_login","");
   		}
   		if(isset($_COOKIE["userpassword"])) {
     		setcookie ("userpassword","");
   		}
		}
		echo "
		<script>
			window.location.href=\"menu_awal.php\";
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
		";}
?>
