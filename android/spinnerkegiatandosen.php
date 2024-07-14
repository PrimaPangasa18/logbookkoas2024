<?php
    include "connect.php";
    $query_stase="SELECT * FROM kepaniteraan ORDER BY id";
    $result_stase = mysqli_query($conn,$query_stase);
	$rows_stase = mysqli_num_rows($result_stase);
	

    $array_stase = array();

    if($rows_stase > 0 ){
        while($row = mysqli_fetch_assoc($result_stase)){
            $array_stase[] = $row;

        }
    }
	$query_nama="SELECT nim,nama FROM biodata_mhsw ORDER BY nama";
    $result_nama = mysqli_query($conn,$query_nama);
	$rows_nama = mysqli_num_rows($result_nama);
	

    $array_nama = array();

    if($rows_nama > 0 ){
        while($row = mysqli_fetch_assoc($result_nama)){
            $array_nama[] = $row;

        }
    }
	
	$array["stase"]=$array_stase;
	$array["nama"]=$array_nama;
	
	
	

    header('Content-type:application/json');
    echo json_encode($array);
    mysqli_close($conn);

?>
