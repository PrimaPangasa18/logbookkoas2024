<?php
require 'connect.php';
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
$id_stase = $input["id_stase"];
$strSQL = "SELECT admin.username, admin.nama, dosen.gelar FROM admin JOIN dosen 
            WHERE (admin.level='4' OR (admin.level='6' AND admin.stase='".$id_stase."')) AND admin.username=dosen.nip ORDER BY admin.nama";
 $objQuery = mysqli_query($conn,$strSQL);
 $intNumField = mysqli_num_fields($objQuery);
 $resultArray = array();
 while($obResult = mysqli_fetch_array($objQuery))
 {
 $arrCol = array();
 for($i=0;$i<$intNumField;$i++)
 {
 $arrCol[mysqli_fetch_field_direct($objQuery, $i)->name] = $obResult[$i];
 }
 array_push($resultArray,$arrCol);
 }

echo json_encode(array('nmapproval'=>$resultArray));
mysqli_close($conn);
 

?>