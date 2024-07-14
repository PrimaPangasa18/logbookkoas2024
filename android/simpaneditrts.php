<?php
$response = array();
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
if($conn){
    if(isset($input['username'])){
	$username = $input["username"];
	$id = $input["id"];
    $rotasi1=$input['rotasi1'];
    $tgl1=$input['tgl1'];
    $stase=$input['stase'];
    $dosen1=$input['dosen1'];
    $rotasi2=$input['rotasi2'];
    $tgl2=$input['tgl2'];
    $dosen2=$input['dosen2'];
    $rotasi3=$input['rotasi3'];
    $tgl3=$input['tgl3'];
    $dosen3=$input['dosen3'];
    $rotasi4=$input['rotasi4'];
    $tgl4=$input['tgl4'];
    $dosen4=$input['dosen4'];
    $rotasi5=$input['rotasi5'];
    $tgl5=$input['tgl5'];
    $dosen5=$input['dosen5'];
    $rotasi6=$input['rotasi6'];
    $tgl6=$input['tgl6'];
    $dosen6=$input['dosen6'];
    $data=$stase;
    $data1="internal_".$data;
	$query    = "REPLACE INTO $data1 (id,nim,rotasi1,tgl1,dosen1,rotasi2,tgl2,dosen2,rotasi3,tgl3,dosen3,rotasi4,tgl4,dosen4,rotasi5,tgl5,dosen5,rotasi6,tgl6,dosen6) VALUES('$id','$username','$rotasi1','$tgl1','$dosen1','$rotasi2','$tgl2','$dosen2','$rotasi3','$tgl3','$dosen3','$rotasi4','$tgl4','$dosen4','$rotasi5','$tgl5','$dosen5','$rotasi6','$tgl6','$dosen6')";
     //  $query    = "REPLACE INTO '".$data1."' (id,nim,rotasi1,tgl1,dosen1,rotasi2,tgl2,dosen2,rotasi3,tgl3,dosen3,rotasi4,tgl4,dosen4,rotasi5,tgl5,dosen5,rotasi6,tgl6,dosen6) SELECT * FROM(SELECT '".$id."','".$username."','".$rotasi1."','".$tgl1."','".$dosen1."','".$rotasi2."','".$tgl2."','".$dosen2."','".$rotasi3."','".$tgl3."','".$dosen3."','".$rotasi4."','".$tgl4."','".$dosen4."','".$rotasi5."','".$tgl5."','".$dosen5."','".$rotasi6."','".$tgl6."','".$dosen6."') AS temp WHERE NOT EXISTS (SELECT * FROM '".$data1."' WHERE id='".$id."' AND nim='".$username."' AND rotasi1='".$rotasi1."' AND tgl1='".$tgl1."' AND dosen1='".$dosen1."' AND rotasi2='".$rotasi2."' AND tgl2='".$tgl2."' AND dosen2='".$dosen2."' AND rotasi3='".$rotasi3."' AND tgl3='".$tgl3."' AND dosen3='".$dosen3."' AND rotasi4='".$rotasi4."' AND tgl4='".$tgl4."' AND dosen4='".$dosen4."' AND rotasi5='".$rotasi5."' AND tgl5='".$tgl5."' AND dosen5='".$dosen5."' AND rotasi6='".$rotasi6."' AND tgl6='".$tgl6."' AND dosen6='".$dosen6."')";
        if(mysqli_query($conn,$query)){
            $konek=mysqli_query($conn,$query);
    echo json_encode(array('response'=>'Saved successfully'));
        }else
           {
               echo json_encode(array('response'=>"failed"));
           }
    
}
           else{
        echo json_encode(array('response'=>'failed2'));
    }
       }
else{
    echo json_encode(array('response'=>'Tidak Terkoneksi'));
}
       
		

mysqli_close($conn);
?>