<?php
include "config.php";
include "fungsi.php";

set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$no = $_GET[id];
$tgl_mulai = $_POST['tgl_mulai'.$no];
$id_stase = $_POST['stase'.$no];
if ($tgl_mulai=="" OR $tgl_mulai=="2000-01-01")
{
	/* Nothing */
}
else {
	if ($id_stase!="")
	{
		$tanggal_mulai = tanggal_indo($tgl_mulai);
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$hari_tambah = $data_stase['hari_stase']-1;
		$tambah_hari = '+'.$hari_tambah.' days';
		$tgl_selesai_stase = date('Y-m-d', strtotime($tambah_hari, strtotime($tgl_mulai)));
		$tglselesai_stase = tanggal_indo($tgl_selesai_stase);
		echo "<font style=\"font-size:0.75em;color:blue\"><br><i>Rencana Mulai Tanggal: $tanggal_mulai<br>";
		echo "Rencana Selesai Tanggal: $tglselesai_stase (default)</font><i><p>";
	}
	else {
		echo "<font style=\"font-size:0.75em;color:red\"><br>Pilih Kepaniteraan (Stase) dahulu ...<br></font>";
	}
}
?>
