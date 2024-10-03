<?php
$response = array();
include 'connect.php';

//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

//Check for Mandatory parameters
$username = $input['username'];
$dosen    = $input['dosen'];
$tgl  = $input['tgl'];
$id = $input['id'];
$id_internal = $input["id_internal"];
$rotasi = $input["rotasi"];
$lastcharrotasi = substr($rotasi, -1);
$data1 = $id;
$data2 = "internal_" . $data1;
$tanggal = "tgl" . $lastcharrotasi;
$ds = "dosen" . $lastcharrotasi;
$rts = "rotasi" . $lastcharrotasi;

$query    = "UPDATE $data2 SET $rts='" . $rotasi . "', $tanggal='" . $tgl . "', $ds='" . $dosen . "' WHERE nim='$username' AND id=$id_internal";
mysqli_query($conn, $query);
if (mysqli_query($conn, $query)) {
    $response["pesan"] = 1;
} else {
    $response["pesan"] = 0;
}
echo json_encode($response);
