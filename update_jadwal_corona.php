<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

//PERGESERAN STASE

//yg mulai 20 Januari
//--------------------------------------START
//semua sudah selesai
//--------------------------------------OK

//yg mulai 17 Februari
//--------------------------------------START
///stase kecil dan stase pendek sudah selesai
///stase besar diselesaikan tgl 29 Maret, sisa 2 minggu dijadwalkan 11 Mei
$stase_besar = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='56'");
while ($data = mysqli_fetch_array($stase_besar)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_selesai`='2020-03-29' WHERE `tgl_mulai`='2020-02-17'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//--------------------------------------OK

//yg mulai 16 Maret
//--------------------------------------START
///stase kecil berakhir 29 Maret, sisa 2 minggu dijadwalkan 11 Mei
$stase_kecil = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='28'");
while ($data = mysqli_fetch_array($stase_kecil)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_selesai`='2020-03-29' WHERE `tgl_mulai`='2020-03-16'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
///stase besar berakhir tgl 12 April, sisanya dijadwalkan 01 Juni - 12 Juli
$stase_besar = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='56'");
while ($data = mysqli_fetch_array($stase_besar)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_selesai`='2020-04-12' WHERE `tgl_mulai`='2020-03-16'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
///stase pendek yg mulai tgl 30 Maret, ditaruh jadwal luar dulu dan nanti dipindah 11 Mei - 31 Mei
$stase_pendek = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='14'");
while ($data = mysqli_fetch_array($stase_pendek)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2021-03-30',`tgl_selesai`='2021-04-13' WHERE `tgl_mulai`='2020-03-30'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//--------------------------------------OK

//yg mulai 13 April
//--------------------------------------START
///stase kecil maju 30 Maret - 12 April, sisanya dijadwal 1 Juni - 14 Juni
$stase_kecil = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='28'");
while ($data = mysqli_fetch_array($stase_kecil)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-03-30',`tgl_selesai`='2020-04-12' WHERE `tgl_mulai`='2020-04-13'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//stase besar maju 30 Maret - 26 April, sisanya dijadwal 15 Juni - 12 Juli
$stase_besar = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='56'");
while ($data = mysqli_fetch_array($stase_besar)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-03-30',`tgl_selesai`='2020-04-26' WHERE `tgl_mulai`='2020-04-13'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//stase pendek
$stase_pendek = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='14'");
while ($data = mysqli_fetch_array($stase_pendek)) {
  $stase_id = 'stase_' . $data['id'];
  //stase pendek yang mulai 13 April, maju 30 Maret-12 April
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-03-30',`tgl_selesai`='2020-04-12' WHERE `tgl_mulai`='2020-04-13'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
  //stase pendek yang mulai 27 April, ditaruh jadwal luar dulu dan nanti pindah 1 Juni - 14 Juni
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2021-04-27',`tgl_selesai`='2021-05-10' WHERE `tgl_mulai`='2020-04-27'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//--------------------------------------OK

//yg mulai 11 Mei
//--------------------------------------START
//stase kecil maju 13 April - 26 April, sisanya dijadwal 15 Juni - 28 Juni
$stase_kecil = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='28'");
while ($data = mysqli_fetch_array($stase_kecil)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-04-13',`tgl_selesai`='2020-04-26' WHERE `tgl_mulai`='2020-05-11'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//stase besar maju 13 April - 10 Mei, sisanya dijadwal 29 Juni - 26 Juli
$stase_besar = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='56'");
while ($data = mysqli_fetch_array($stase_besar)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-04-13',`tgl_selesai`='2020-05-10' WHERE `tgl_mulai`='2020-05-11'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//stase pendek
$stase_pendek = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='14'");
while ($data = mysqli_fetch_array($stase_pendek)) {
  $stase_id = 'stase_' . $data['id'];
  //stase pendek yang mulai 11 Mei, maju 13 April - 26 April
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-04-13',`tgl_selesai`='2020-04-26' WHERE `tgl_mulai`='2020-05-11'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
  //stase pendek yang mulai 25 Mei, ditaruh jadwal luar dulu dan nanti pindah 15 Juni - 28 Juni
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2021-05-25',`tgl_selesai`='2021-06-14' WHERE `tgl_mulai`='2020-05-25'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//--------------------------------------OK

//yg mulai 15 Juni
//--------------------------------------START
//stase kecil maju 27 April - 10 Mei, sisanya dijadwal 29 Juni - 12 Juli
$stase_kecil = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='28'");
while ($data = mysqli_fetch_array($stase_kecil)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-04-27',`tgl_selesai`='2020-05-10' WHERE `tgl_mulai`='2020-06-15'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//stase besar maju 27 April - 14 Juni, sisanya dijadwal 13 Juli - 9 Agt
$stase_besar = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='56'");
while ($data = mysqli_fetch_array($stase_besar)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-04-27',`tgl_selesai`='2020-06-14' WHERE `tgl_mulai`='2020-06-15'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//stase pendek
$stase_pendek = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='14'");
while ($data = mysqli_fetch_array($stase_pendek)) {
  $stase_id = 'stase_' . $data['id'];
  //stase pendek yang mulai 15 Juni, maju 27 April - 10 Mei
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-04-27',`tgl_selesai`='2020-05-10' WHERE `tgl_mulai`='2020-06-15'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
  //stase pendek yang mulai 29 Juni, dibiarkan
}
//--------------------------------------OK


//PEMINDAHAN DAN PENAMBAHAN STASE
//yg mulai 17 Februari
//--------------------------------------START
//stase kecil dan stase pendek tidak ada penambahan/pemindahan
//stase besar yang diselesaikan tgl 29 Maret, sisa 2 minggu dijadwalkan 11 Mei - 31 Mei (kena idul fitri)
$stase_besar = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='56'");
while ($data = mysqli_fetch_array($stase_besar)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-05-11', `tgl_selesai`='2020-05-31',`rotasi`='9' WHERE `tgl_mulai`='2020-02-17' AND `tgl_selesai`='2020-03-29'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//--------------------------------------OK

//yg mulai 16 Maret
//--------------------------------------START
///stase kecil yang diselesaikan 29 Maret, sisa 2 minggu dijadwalkan 11 Mei - 31 Mei (kena idul fitri)
$stase_kecil = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='28'");
while ($data = mysqli_fetch_array($stase_kecil)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-05-11',`tgl_selesai`='2020-05-31',`rotasi`='9' WHERE `tgl_mulai`='2020-03-16' AND `tgl_selesai`='2020-03-29'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//stase besar berakhir tgl 12 April, sisanya dijadwalkan 01 Juni - 12 Juli
// -->> SEDANG BERLANGSUNG, BELUM BISA DIJADWAL TAMBAHAN!!! <<---
///stase pendek yg ditaruh jadwal luar, dipindah 11 Mei - 31 Mei (kena idul fitri)
$stase_pendek = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='14'");
while ($data = mysqli_fetch_array($stase_pendek)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-05-11',`tgl_selesai`='2020-05-31',`rotasi`='9' WHERE `tgl_mulai`='2021-03-30' AND `tgl_selesai`='2021-04-13'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//--------------------------------------OK

//yg mulai 13 April
//--------------------------------------START
///stase kecil maju 30 Maret - 12 April, sisanya dijadwal 1 Juni - 14 Juni
// -->> SEDANG BERLANGSUNG, BELUM BISA DIJADWAL TAMBAHAN!!! <<---
//stase besar maju 30 Maret - 26 April, sisanya dijadwal 15 Juni - 12 Juli
// -->> SEDANG BERLANGSUNG, BELUM BISA DIJADWAL TAMBAHAN!!! <<---
///stase pendek yg ditaruh jadwal luar, dipindah 1 Juni - 14 Juni
$stase_pendek = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='14'");
while ($data = mysqli_fetch_array($stase_pendek)) {
  $stase_id = 'stase_' . $data['id'];
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2020-06-01',`tgl_selesai`='2020-06-14',`rotasi`='9' WHERE `tgl_mulai`='2021-04-27' AND `tgl_selesai`='2021-05-10'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//--------------------------------------OK

//yg mulai 11 Mei
//--------------------------------------START
//stase kecil maju 13 April - 26 April, sisanya dijadwal 15 Juni - 28 Juni
// -->> SEDANG BERLANGSUNG, BELUM BISA DIJADWAL TAMBAHAN!!! <<---
//stase besar maju 13 April - 10 Mei, sisanya dijadwal 29 Juni - 26 Juli
// -->> SEDANG BERLANGSUNG, BELUM BISA DIJADWAL TAMBAHAN!!! <<---
//stase pendek
$stase_pendek = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` WHERE `hari_stase`='14'");
while ($data = mysqli_fetch_array($stase_pendek)) {
  $stase_id = 'stase_' . $data['id'];
  //stase pendek yang ditaruh jadwal luar dipindah 15 Juni - 28 Juni
  $update = mysqli_query($con, "UPDATE `$stase_id` SET `tgl_mulai`='2021-06-15',`tgl_selesai`='2021-06-28',`rotasi`='9' WHERE `tgl_mulai`='2021-05-25' AND `tgl_selesai`='2021-06-14'");
  if ($update) echo "OK<br>";
  else echo "FAILED<br>";
}
//--------------------------------------OK

//yg mulai 15 Juni
//--------------------------------------START
//stase kecil maju 27 April - 10 Mei, sisanya dijadwal 15 Juni - 28 Juni
// -->> SEDANG BERLANGSUNG, BELUM BISA DIJADWAL TAMBAHAN!!! <<---
//stase besar maju 27 April - 14 Juni, sisanya dijadwal 29 Juni - 26 Juli
// -->> SEDANG BERLANGSUNG, BELUM BISA DIJADWAL TAMBAHAN!!! <<---
//stase pendek tidak ada perubahan
//--------------------------------------OK
