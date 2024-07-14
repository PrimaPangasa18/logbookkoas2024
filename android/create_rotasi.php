<?php

    include "connect.php";
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON,TRUE);
    $username=$input['username'];
    $stase=$input['id'];
    
    $array_update1 = array();
    $array_update2 = array();
    $array_update3 = array();
    $array_update4 = array();
    $array_update5 = array();
    $array_update6 = array();
            $data1 = $stase;
            $data2 = "internal_".$data1;
	        $insert_internal =  "INSERT INTO $data2 (nim) 
                    SELECT * FROM (SELECT '".$username."') AS temp
                    WHERE NOT EXISTS(SELECT * FROM $data2 WHERE nim='".$username."')";
            mysqli_query ($conn,$insert_internal);


    $id1=$stase."1";
    $update1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM rotasi_internal WHERE id='".$id1."'"));
    if($update1!=null){
        $rotasi1 = "UPDATE $data2 SET rotasi1='".$update1['id']."' WHERE nim='".$username."'";
        mysqli_query ($conn,$rotasi1);
    }
    $array_update1[] =$update1;
    
    $id2=$stase."2";
    $update2 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM rotasi_internal WHERE id='".$id2."'"));
    if($update2!=null){
        $rotasi2 = "UPDATE $data2 SET rotasi2='".$update2['id']."' WHERE nim='".$username."'";
        mysqli_query ($conn,$rotasi2);
    }
    $array_update2[] =$update2;
    
    $id3=$stase."3";
    $update3 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM rotasi_internal WHERE id='".$id3."'"));
    if($update3!=null){
        $rotasi3 = "UPDATE $data2 SET rotasi3='".$update3['id']."' WHERE nim='".$username."'";
        mysqli_query ($conn,$rotasi3);
    }
    $array_update3[] =$update3;
    
    $id4=$stase."4";
    $update4 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM rotasi_internal WHERE id='".$id4."'"));
    if($update4!=null){
        $rotasi4 = "UPDATE $data2 SET rotasi4='".$update4['id']."' WHERE nim='".$username."'";
        mysqli_query ($conn,$rotasi4);
    }
    $array_update4[] =$update4;
    
    $id5=$stase."5";
    $update5 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM rotasi_internal WHERE id='".$id5."'"));
    if($update5!=null){
        $rotasi5 = "UPDATE $data2 SET rotasi5='".$update5['id']."' WHERE nim='".$username."'";
        mysqli_query ($conn,$rotasi5);
    }
    $array_update5[] =$update5;
    
    $id6=$stase."6";
    $update6 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM rotasi_internal WHERE id='".$id6."'"));
    if($update6!=null){
        $rotasi6 = "UPDATE $data2 SET rotasi6='".$update6['id']."' WHERE nim='".$username."'";
        mysqli_query ($conn,$rotasi6);
    }
	$array_update6[] =$update6;
			
			
                $array["update1"] = $array_update1;
                $array["update2"] = $array_update2;
                $array["update3"] = $array_update3;
                $array["update4"] = $array_update4;
                $array["update5"] = $array_update5;
                $array["update6"] = $array_update6;
        header('Content-type:application/json');
        echo json_encode($array);
		mysqli_close($conn);
?>
