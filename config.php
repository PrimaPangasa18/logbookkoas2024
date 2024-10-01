<?php
$dbhost='localhost';
$dbuser='root';
$dbpass='';
//$dbname='logbook_koas';
$dbname='fkundipl_logbook_koas';
//$dbname='logbookkoas2024';
$con = new mysqli($dbhost, $dbuser, $dbpass,$dbname) or die("Opps some thing went wrong");

date_default_timezone_set("Asia/Jakarta");
$tgl = date('Y-m-d');
$waktu = date("h-i-sa");
$tahun = substr($tgl, 0, 4);
$jalur="E-LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER";

function encrypt($action, $string) {
    $output = false;
    $key_key = "elogbookkoas";
    $iv_key = "fkundip@00123";
    $cipher = "AES-256-CBC";

    $key = hash('sha256', $key_key);
    $iv = substr(hash('sha256', $key_key), 0,16);

    if($action == 'encrypt'){
        $output = openssl_encrypt($string, $cipher, $key, 0, $iv); 
        $output = base64_encode($output);
    }
    else if($action == 'decrypt'){
        $output = openssl_decrypt(base64_decode($string), $cipher, $key, 0, $iv);
    }
    return $output;
}
?>
