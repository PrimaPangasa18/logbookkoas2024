<?php
require 'android/vendor/autoload.php'; // Include PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "config.php";
include "fungsi.php";
error_reporting(E_ALL ^ E_NOTICE);

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Fetch Data from Database
$id_stase = $_GET['stase'];
$angkatan_filter = $_GET['angk'];
$stase_id = "stase_" . $id_stase;
$include_id = "include_" . $id_stase;
$target_id = "target_" . $id_stase;
$tgl_awal = $_GET['tglawal'];
$tgl_akhir = $_GET['tglakhir'];

$filterstase = "`stase`=" . "'$id_stase'";
$filtertgl = " AND (`tanggal`>=" . "'$tgl_awal'" . " AND `tanggal`<=" . "'$tgl_akhir')";
$filter = $filterstase . $filtertgl;

$mhsw = mysqli_query($con, "SELECT `nim` FROM `$stase_id` WHERE `tgl_mulai`>='$tgl_awal' AND `tgl_selesai`<='$tgl_akhir' ORDER BY `nim`");
$jml_mhsw = mysqli_num_rows($mhsw);
$stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));

$delete_dummy = mysqli_query($con, "DELETE FROM `dummy_evaluasi_dosen` WHERE `username`='$_COOKIE[user]'");

while ($nim_mhsw = mysqli_fetch_array($mhsw)) {
    $data_dosen = mysqli_query($con, "SELECT `dosen1`,`tatap_muka1`,`dosen2`,`tatap_muka2`,`dosen3`,`tatap_muka3` FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw[nim]' AND `stase`='$id_stase'");
    $nip_dosen = mysqli_fetch_array($data_dosen);
    $jml_data_evaluasi = mysqli_num_rows($data_dosen);
    if ($jml_data_evaluasi > 0) {
        $jml_dosen1 = mysqli_num_rows(mysqli_query($con, "SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'"));
        if ($jml_dosen1 < 1) {
            $insert_dosen = mysqli_query($con, "INSERT INTO `dummy_evaluasi_dosen`
							(`nip`, `jml_review`,`jml_jam`,`username`)
							VALUES
							('$nip_dosen[dosen1]','1','$nip_dosen[tatap_muka1]','$_COOKIE[user]')");
        } else {
            $jml_review_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_review` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'"));
            $jml_jam_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'"));
            $jml_review = $jml_review_asal['jml_review'] + 1;
            $jml_jam = $jml_jam_asal['jml_jam'] + $nip_dosen['tatap_muka1'];
            $update_dosen = mysqli_query($con, "UPDATE `dummy_evaluasi_dosen`
							SET `jml_review`='$jml_review',`jml_jam`='$jml_jam' WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'");
        }
        $jml_dosen2 = mysqli_num_rows(mysqli_query($con, "SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'"));
        if ($jml_dosen2 < 1) {
            $insert_dosen = mysqli_query($con, "INSERT INTO `dummy_evaluasi_dosen`
							(`nip`, `jml_review`,`jml_jam`,`username`)
							VALUES
							('$nip_dosen[dosen2]','1','$nip_dosen[tatap_muka2]','$_COOKIE[user]')");
        } else {
            $jml_review_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_review` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'"));
            $jml_jam_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'"));
            $jml_review = $jml_review_asal['jml_review'] + 1;
            $jml_jam = $jml_jam_asal['jml_jam'] + $nip_dosen['tatap_muka2'];
            $update_dosen = mysqli_query($con, "UPDATE `dummy_evaluasi_dosen`
							SET `jml_review`='$jml_review',`jml_jam`='$jml_jam' WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'");
        }
        $jml_dosen3 = mysqli_num_rows(mysqli_query($con, "SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'"));
        if ($jml_dosen3 < 1) {
            $insert_dosen = mysqli_query($con, "INSERT INTO `dummy_evaluasi_dosen`
							(`nip`, `jml_review`, `jml_jam`,`username`)
							VALUES
							('$nip_dosen[dosen3]','1','$nip_dosen[tatap_muka3]','$_COOKIE[user]')");
        } else {
            $jml_review_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_review` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'"));
            $jml_jam_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'"));
            $jml_review = $jml_review_asal['jml_review'] + 1;
            $jml_jam = $jml_jam_asal['jml_jam'] + $nip_dosen['tatap_muka3'];
            $update_dosen = mysqli_query($con, "UPDATE `dummy_evaluasi_dosen`
							SET `jml_review`='$jml_review',`jml_jam`='$jml_jam' WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'");
        }
    }
}

$daftar_dosen = mysqli_query($con, "SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `username`='$_COOKIE[user]'");


// Apply bold formatting and column widths for title and headers
$sheet->setCellValue('B1', 'REKAP EVALUASI AKHIR STASE - UMUM')->getStyle('B1')->getFont()->setBold(true)->setSize(14);
$sheet->setCellValue('B2', 'EVALUASI DOSEN')->getStyle('B2')->getFont()->setBold(true)->setSize(12);

// Add header information with bold text
$sheet->setCellValue('B4', 'Kepaniteraan (STASE)')->getStyle('B4')->getFont()->setBold(true);
$sheet->setCellValue('C4', $stase['kepaniteraan']);
$sheet->setCellValue('B5', 'Angkatan')->getStyle('B5')->getFont()->setBold(true);
$sheet->setCellValue('C5', ($angkatan_filter == 'all') ? 'Semua Angkatan' : 'Angkatan ' . $angkatan_filter);
$sheet->setCellValue('B6', 'Jumlah Mahasiswa')->getStyle('B6')->getFont()->setBold(true);
$sheet->setCellValue('C6', $jml_mhsw);
$sheet->setCellValue('B7', 'Periode Kegiatan')->getStyle('B7')->getFont()->setBold(true);
$sheet->setCellValue('C7', "$tgl_awal s.d $tgl_akhir");

// Set Table Headers with bold text, center alignment, and borders
$headers = ['A9' => 'No', 'B9' => 'Nama Dosen/Dokter', 'C9' => 'NIP/NIK', 'D9' => 'Jumlah Review', 'E9' => 'Jumlah Jam'];
foreach ($headers as $cell => $header) {
    $sheet->setCellValue($cell, $header)
        ->getStyle($cell)->getFont()->setBold(true);
    $sheet->getStyle($cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
}

// Adjust column widths
$sheet->getColumnDimension('A')->setWidth(3); // No
$sheet->getColumnDimension('B')->setWidth(15); // Nama Dosen/Dokter
$sheet->getColumnDimension('C')->setWidth(15); // NIP/NIK
$sheet->getColumnDimension('D')->setWidth(12); // Jumlah Review
$sheet->getColumnDimension('E')->setWidth(20); // Jumlah Jam


$id = 1;
$row = 10;
while ($nip_dosen = mysqli_fetch_array($daftar_dosen)) {
    $data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_dosen[nip]'"));
    $jml_review = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_review`,`jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[nip]' AND`username`='$_COOKIE[user]'"));
    $sheet->setCellValue("A$row", $id)
        ->getStyle("A$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->setCellValue("B$row", $data_dosen['nama'] ?? '')
        ->getStyle("B$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->setCellValueExplicit("C$row", $data_dosen['nip'] ?? '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
        ->getStyle("C$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->setCellValue("D$row", $jml_review['jml_review'] ?? '')
        ->getStyle("D$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->setCellValue("E$row", $jml_review['jml_jam'] ?? '')
        ->getStyle("E$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $row++;
    $id++;
}

$delete_dummy = mysqli_query($con, "DELETE FROM `dummy_evaluasi_dosen` WHERE `username`='$_COOKIE[user]'");

// Auto-size all columns to fit the content
foreach (range('A', 'E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}


// Create and download Excel file
$filename = 'rekap_evaluasi_dosen.xlsx';
$writer = new Xlsx($spreadsheet);

// Output as download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
