<?php
require 'android/vendor/autoload.php'; // Include PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "config.php";
include "fungsi.php";

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Fetch Data from Database
$id_stase = $_GET['stase'];
$angkatan_filter = $_GET['angk'];
$tgl_awal = $_GET['tglawal'];
$tgl_akhir = $_GET['tglakhir'];

$filterstase = "`stase`=" . "'$id_stase'";
$filtertgl = " AND (`tanggal`>=" . "'$tgl_awal'" . " AND `tanggal`<=" . "'$tgl_akhir')";
$filter = $filterstase . $filtertgl;

$mhsw = mysqli_query($con, "SELECT `nim` FROM `stase_$id_stase` WHERE `tgl_mulai`>='$tgl_awal' AND `tgl_selesai`<='$tgl_akhir' ORDER BY `nim`");
$jml_mhsw = mysqli_num_rows($mhsw);
$stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
// Apply bold formatting and column widths for title and headers
$sheet->setCellValue('B1', 'REKAP EVALUASI AKHIR STASE - UMUM')->getStyle('B1')->getFont()->setBold(true)->setSize(14);
$sheet->setCellValue('B2', 'KOMENTAR, KRITIK DAN SARAN')->getStyle('B2')->getFont()->setBold(true)->setSize(12);

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
$headers = ['A9' => 'No', 'B9' => 'Nama Mahasiswa', 'C9' => 'NIM', 'D9' => 'Angkatan', 'E9' => 'Komentar, Kritik, dan Saran'];
foreach ($headers as $cell => $header) {
    $sheet->setCellValue($cell, $header)
        ->getStyle($cell)->getFont()->setBold(true);
    $sheet->getStyle($cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
}

// Adjust column widths
$sheet->getColumnDimension('A')->setWidth(3); // No
$sheet->getColumnDimension('B')->setWidth(25); // Nama Mahasiswa
$sheet->getColumnDimension('C')->setWidth(15); // NIM
$sheet->getColumnDimension('D')->setWidth(12); // Angkatan
$sheet->getColumnDimension('E')->setWidth(20); // Refleksi Diri

// Populate the rows with data and format cells
$id = 1;
$row = 10;
while ($nim_mhsw = mysqli_fetch_array($mhsw)) {
    $data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama`, `angkatan` FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw[nim]'"));
    $data_evaluasi = mysqli_query($con, "SELECT `komentar` FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw[nim]' AND `stase`='$id_stase'");
    $refleksi_diri = mysqli_fetch_array($data_evaluasi);

    // Fill Excel Rows with borders
    $sheet->setCellValue("A$row", $id)
        ->getStyle("A$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->setCellValue("B$row", $data_mhsw['nama'] ?? '')
        ->getStyle("B$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->setCellValueExplicit("C$row", $nim_mhsw['nim'] ?? '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
        ->getStyle("C$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->setCellValue("D$row", $data_mhsw['angkatan'] ?? '')
        ->getStyle("D$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->setCellValue("E$row", $refleksi_diri['komentar'] ?? 'Belum Mengisi Evaluasi')
        ->getStyle("E$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $row++;
    $id++;
}

// Auto-size all columns to fit the content
foreach (range('A', 'E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}


// Create and download Excel file
$filename = 'rekap_evaluasi_saran.xlsx';
$writer = new Xlsx($spreadsheet);

// Output as download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
