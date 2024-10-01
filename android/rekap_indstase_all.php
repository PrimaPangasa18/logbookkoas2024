<?php
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
 

$id_stase = $input['id'];
$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
$stase_id = "stase_".$id_stase;
$data_stase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'"));
$mulai_stase = date_create($data_stase_mhsw[tgl_mulai]);
$akhir_stase = date_create($data_stase_mhsw[tgl_selesai]);
$hari_stase = date_diff($mulai_stase,$akhir_stase);
$batas_tengah = (int)(($hari_stase->days+1)/2)-4;


// Proses Jurnal Penyakit
$data_jurnal_penyakit = mysqli_query($con, "SELECT * FROM `jurnal_penyakit` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `status`='1'");
$delete_dummy_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");

while ($data = mysqli_fetch_array($data_jurnal_penyakit)) {
    $insert_penyakit = mysqli_query($con, "INSERT INTO `jurnal_penyakit_dummy` (
        `id`, `nim`, `nama`, `angkatan`, `grup`, `hari`, `tanggal`, `stase`,
        `jam_awal`, `jam_akhir`, `kelas`, `lokasi`, `kegiatan`, `penyakit1`, 
        `penyakit2`, `penyakit3`, `penyakit4`, `dosen`, `status`, `evaluasi`, `username`
    ) VALUES (
        '$data[id]', '$data[nim]', '$data[nama]', '$data[angkatan]', '$data[grup]', 
        '$data[hari]', '$data[tanggal]', '$data[stase]', '$data[jam_awal]', 
        '$data[jam_akhir]', '$data[kelas]', '$data[lokasi]', '$data[kegiatan]', 
        '$data[penyakit1]', '$data[penyakit2]', '$data[penyakit3]', '$data[penyakit4]', 
        '$data[dosen]', '$data[status]', '$data[evaluasi]', '$_COOKIE[user]'
    )");
}

// Proses Jurnal Ketrampilan
$data_jurnal_ketrampilan = mysqli_query($con, "SELECT * FROM `jurnal_ketrampilan` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `status`='1'");
$delete_dummy_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");

while ($data = mysqli_fetch_array($data_jurnal_ketrampilan)) {
    $insert_ketrampilan = mysqli_query($con, "INSERT INTO `jurnal_ketrampilan_dummy` (
        `id`, `nim`, `nama`, `angkatan`, `grup`, `hari`, `tanggal`, `stase`,
        `jam_awal`, `jam_akhir`, `kelas`, `lokasi`, `kegiatan`, `ketrampilan1`, 
        `ketrampilan2`, `ketrampilan3`, `ketrampilan4`, `dosen`, `status`, `evaluasi`, `username`
    ) VALUES (
        '$data[id]', '$data[nim]', '$data[nama]', '$data[angkatan]', '$data[grup]', 
        '$data[hari]', '$data[tanggal]', '$data[stase]', '$data[jam_awal]', 
        '$data[jam_akhir]', '$data[kelas]', '$data[lokasi]', '$data[kegiatan]', 
        '$data[ketrampilan1]', '$data[ketrampilan2]', '$data[ketrampilan3]', 
        '$data[ketrampilan4]', '$data[dosen]', '$data[status]', '$data[evaluasi]', '$_COOKIE[user]'
    )");
}

// Mengambil data penyakit dari tabel `daftar_penyakit`
$daftar_penyakit = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1' ORDER BY `$target_id` DESC, `penyakit` ASC");


while ($data = mysqli_fetch_array($daftar_penyakit)) {
    if ($data['skdi_level'] == "MKM" || $data['k_level'] == "MKM" || $data['ipsg_level'] == "MKM" || $data['kml_level'] == "MKM") {
        $jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_1 = 0;
        $jml_2 = 0;
        $jml_3 = 0;
        $jml_4A = 0;
        $jml_U = 0;
    } else {
        $jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
            AND (`kegiatan`='1' OR `kegiatan`='2')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
            AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
            AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
            AND `kegiatan`='10'
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
            AND (`kegiatan`='11' OR `kegiatan`='12')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_MKM = 0;
    }
    $jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Kasus tidak ada target
    $no_penyakit = 1;
    $jml_ketuntasan_penyakit = 0;
    $item_penyakit = 0;
    $ketuntasan = 0;
    $warna;

    if ($data[$target_id] < 1) {
        if ($jumlah_total > 0) {
            // Blok warna hijau tua
            $ketuntasan = 100;
            $jml_ketuntasan_penyakit = $jml_ketuntasan_penyakit + $ketuntasan;
            $item_penyakit++;
            blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
        } else {
            // Blok warna merah
            $ketuntasan = 0;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
        }
    } else {
        $ketuntasan = ($jumlah_total / $data[$target_id]) * 100;
        if ($ketuntasan >= 100) {
            $ketuntasan = 100;
            if ($jumlah_total >= ($data[$target_id] * 2)) {
                // Blok warna hijau tua
                $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
                $item++;
                blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
            } else {
                // Blok warna hijau muda
                $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
                $item++;
                blok_warna("hijau", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
            }
        } else if ($ketuntasan >= 50 && $ketuntasan < 100) {
            // Blok warna kuning
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            blok_warna("kuning", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
        } else {
            // Blok warna merah
            $ketuntasan = 0;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
        }
    }
}

$ketuntasan_rata = $jml_ketuntasan / $item;
if ($ketuntasan_rata <= 0) {
    blok_warna_rata("merah", $ketuntasan_rata);
} else if ($ketuntasan_rata > 0 && $ketuntasan_rata < 50) {
    blok_warna_rata("kuning", $ketuntasan_rata);
} else if ($ketuntasan_rata >= 50 && $ketuntasan_rata < 100) {
    blok_warna_rata("hijau", $ketuntasan_rata);
} else {
    blok_warna_rata("hijautua", $ketuntasan_rata);
}

$daftar_ketrampilan = mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1' ORDER BY `$target_id` DESC, `ketrampilan` ASC");
$no = 1;
$jml_ketuntasan = 0;
$item = 0;
$ketuntasan = 0;

while ($data = mysqli_fetch_array($daftar_ketrampilan)) {
    if ($data['skdi_level'] == "MKM" || $data['k_level'] == "MKM" || $data['ipsg_level'] == "MKM" || $data['kml_level'] == "MKM") {
        $jml_MKM = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_1 = 0;
        $jml_2 = 0;
        $jml_3 = 0;
        $jml_4A = 0;
        $jml_U = 0;
    } else {
        $jml_1 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND (`kegiatan`='1' OR `kegiatan`='2')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_2 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_3 = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_4A = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND `kegiatan`='10'
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_U = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND (`kegiatan`='11' OR `kegiatan`='12')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$_COOKIE[user]'"));
        $jml_MKM = 0;
    }
    $jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;

    // Kasus tidak ada target
    if ($data[$target_id] < 1) {
        if ($jumlah_total > 0) {
            // Blok warna hijau tua
            $ketuntasan = 100;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
        } else {
            // Blok warna merah
            $ketuntasan = 0;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
        }
    } else {
        $ketuntasan = ($jumlah_total / $data[$target_id]) * 100;
        if ($ketuntasan >= 100) {
            $ketuntasan = 100;
            if ($jumlah_total >= ($data[$target_id] * 2)) {
                // Blok warna hijau tua
                $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
                $item++;
                blok_warna("hijautua", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
            } else {
                // Blok warna hijau muda
                $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
                $item++;
                blok_warna("hijau", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
            }
        } else if ($ketuntasan >= 50 && $ketuntasan < 100) {
            // Blok warna kuning
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            blok_warna("kuning", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
        } else {
            // Blok warna merah
            $ketuntasan = 0;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            blok_warna("merah", $jml_1, $jml_2, $jml_3, $jml_4A, $jml_U, $jml_MKM);
        }
    }
}

$ketuntasan_rata = $jml_ketuntasan / $item;
if ($ketuntasan_rata <= 0) {
    blok_warna_rata("merah", $ketuntasan_rata);
} else if ($ketuntasan_rata > 0 && $ketuntasan_rata < 50) {
    blok_warna_rata("kuning", $ketuntasan_rata);
} else if ($ketuntasan_rata >= 50 && $ketuntasan_rata < 100) {
    blok_warna_rata("hijau", $ketuntasan_rata);
} else {
    blok_warna_rata("hijautua", $ketuntasan_rata);
}


?>


buat ini bisa menerima input json, lalu memberikan response json, response yang saya butuhkan adalah daftar jurnal keterampilan dan jurnal penyakit yang memiliki level skdi, dan lain lain