<?php
require 'connect.php';
$username = $_GET["username"];
$tanggal = $_GET["tanggal"];
$id = $_GET["id"];
 $strSQL = "SELECT evaluasi,rencana FROM evaluasi WHERE nim='".$username."' AND tanggal='".$tanggal."' AND stase='".$id."'";
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
 
mysqli_close($conn);
 
echo json_encode($resultArray);
?>