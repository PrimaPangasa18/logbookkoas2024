<?php
$response = array();
include 'connect.php';

// Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convert JSON into array

// Check for Mandatory parameters
$username = $input['username'];
$stase    = $input['stase'];
$tanggal  = $input['tanggal'];
$SEvaluasi= $input['SEvaluasi'];
$SRencana = $input['SRencana'];

// Retrieve angkatan and grup from biodata_mhsw table
$query_angkatan = "SELECT angkatan FROM biodata_mhsw WHERE nim='".$username."'";
$angkatan_result = mysqli_query($conn, $query_angkatan);

if ($angkatan_result) {
    $angkatan_a = mysqli_fetch_assoc($angkatan_result);
    $angkatan = $angkatan_a["angkatan"];
} else {
    $response["status"] = 1;
    $response["message"] = "Error fetching angkatan: " . mysqli_error($conn);
    echo json_encode($response);
    exit();
}

$query_grup = "SELECT grup FROM biodata_mhsw WHERE nim='".$username."' AND angkatan='".$angkatan."'";
$grup_result = mysqli_query($conn, $query_grup);

if ($grup_result) {
    $grup_a = mysqli_fetch_assoc($grup_result);
    $grup = $grup_a["grup"];
} else {
    $response["status"] = 1;
    $response["message"] = "Error fetching grup: " . mysqli_error($conn);
    echo json_encode($response);
    exit();
}

// Update evaluasi table
$query_update_evaluasi = "UPDATE evaluasi SET evaluasi='".$SEvaluasi."', rencana='".$SRencana."' WHERE nim='".$username."' AND grup='".$grup."' AND stase='".$stase."' AND tanggal='".$tanggal."'";
if (!mysqli_query($conn, $query_update_evaluasi)) {
    $response["status"] = 1;
    $response["message"] = "Error updating evaluasi: " . mysqli_error($conn);
    echo json_encode($response);
    exit();
}

// Update jurnal_penyakit table
$query_jp = "UPDATE jurnal_penyakit SET evaluasi=1 WHERE stase='".$stase."'";
if (!mysqli_query($conn, $query_jp)) {
    $response["status"] = 1;
    $response["message"] = "Error updating jurnal_penyakit: " . mysqli_error($conn);
    echo json_encode($response);
    exit();
}

// Update jurnal_ketrampilan table
$query_jk = "UPDATE jurnal_ketrampilan SET evaluasi=1 WHERE stase='".$stase."'";
if (!mysqli_query($conn, $query_jk)) {
    $response["status"] = 1;
    $response["message"] = "Error updating jurnal_ketrampilan: " . mysqli_error($conn);
    echo json_encode($response);
    exit();
}

// If all queries succeeded
$response["status"] = 0;
$response["message"] = "Successful";
echo json_encode($response);
?>
