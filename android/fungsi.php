<?php
date_default_timezone_set('Asia/Jakarta');
set_time_limit(0);

//-------------------------------------
function tanggal_indo($date)
{
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

	$tahun = substr($date, 0, 4);
	$bulan = substr($date, 5, 2);
	$tanggal   = substr($date, 8, 2);

	$result = $tanggal . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
	return $result;
}


?>