<?php
	include_once "connect.php";
	if($conn){
    $id=$_POST['id'];
    $nama=$_POST['nama'];
    $image=$_POST['image'];
	$username= $_POST['username'];
    $password=$_POST["password"];
    $nama=$_POST["nama"];
    $namalngkp=$_POST["namalengkap"];
	$proplhr=$_POST["proplahir"];
    $kotalhr=$_POST["kotalahir"];
    $tgllhr=$_POST["tgllahir"];
    $alamat=$_POST["alamat"];
    $propalmt=$_POST["propinsialamat"];
    $kotaalmt=$_POST["kotalamat"];
    $nohp=$_POST["nohp"];
    $email=$_POST["email"];
    $nmwali=$_POST["namawali"];
    $almtwali=$_POST["alamatwali"];
    $propwali=$_POST["propwali"];
    $kotawali=$_POST["kotawali"];
    $nohpwali=$_POST["nohpwali"];
    $foto=$_POST["foto"];
		$sql="REPLACE INTO biodata_mhsw (id,nama,NIM,kota_lahir,prop_lahir,tanggal_lahir,alamat,kota_alamat,prop_alamat,no_hp,email,nama_ortu,alamat_ortu,kota_ortu,prop_ortu,no_hportu,foto) VALUES ('$id','$nama','$username','$kotalhr','$proplhr','$tgllhr','$alamat','$kotaalmt','$propalmt','$nohp','$email','$nmwali','$almtwali','$kotawali','$propwali','$nohpwali','$foto.jpg')";
        $sql1    = "UPDATE admin SET nama='$nama',password='$password'WHERE username='$username'";
		// sesuiakan ip address laptop/pc atau URL server
        $query="SELECT foto FROM biodata_mhsw WHERE nim='$username'";
		$path = "../foto/$foto.jpg";
	//	$coba=mysqli_query($conn,$sql);
	//	$coba1=mysqli_query($conn,$sql1);
		
        $hps=mysqli_fetch_assoc(mysqli_query($conn,$query));
		if (mysqli_query($conn,$sql)){
          /*  $storagelocation = exo_getglobalvariable('HEPubStorageLocation', '');
            $file = $storagelocation.'myfile1.txt';
            $fp = fopen($file, "w") or die("Couldn't open $file for writing!");
            fwrite($fp, $data) or die("Couldn't write values to file!"); 
            fclose($fp); */

             $path1 = "../foto/$hps[foto]";
            unlink($path1);
			file_put_contents($path,base64_decode($image));
			$coba=mysqli_query($conn,$sql);
		$coba1=mysqli_query($conn,$sql1);
			echo json_encode(array('response'=>'Image Uploaded Successfully'));
        }
		 else{ 
			echo json_encode(array('response'=>'Image Uploaded Failed1')); 
		}
	}else{
        echo json_encode(array('response'=>'Image Uploaded Failed2')); 
    }	
	
	mysqli_close($conn);
?>	