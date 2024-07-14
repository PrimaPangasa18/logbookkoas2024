<HTML>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
  <script type="text/javascript" src="jquery.min.js"></script>
<!--</head>-->
</head>
<BODY>
<?php
include "config.php";
include "menu1.php";

set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
	echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
}
else{
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass'])AND ($_COOKIE['level']==1))
{
	echo "<div class=\"text_header\">UPDATE NIM MAHASISWA KOAS</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

	if(isset($_POST['import']) AND !empty($_FILES['update_nim_koas']['tmp_name']))
	{
		$file = $_FILES['update_nim_koas']['tmp_name'];
		$handle = fopen($file, "r");
    $separator = $_POST[separator];
    $sukses = 0;
    $fail = 0;
    $id = 1;
    while(($filesop = fgetcsv($handle, 1000, $separator)) !== false)
		{
    if ($id>1)
    {
			$nim_lama = $filesop[2];
			$nim_baru = $filesop[3];
      $password_new = md5($nim_baru);
      $jml_username = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `admin` WHERE `username`='$nim_lama'"));
      if ($jml_username>0)
      {
        //tabel admin
        $update_admin=mysqli_query($con,"UPDATE `admin` SET `username`='$nim_baru',`password`='$password_new' WHERE `username`='$nim_lama'");
        //tabel biodata_mhsw
        $update_biodata_mhsw=mysqli_query($con,"UPDATE `biodata_mhsw` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //tabel evaluasi
        $update_evaluasi=mysqli_query($con,"UPDATE `evaluasi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //tabel evaluasi_stase
        $update_evaluasi_stase=mysqli_query($con,"UPDATE `evaluasi_stase` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //tabel evaluasi
        $update_evaluasi=mysqli_query($con,"UPDATE `evaluasi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //tabel internal_Mxxx dan stase_Mxxx
        $stase=mysqli_query($con,"SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
        while ($data_stase=mysqli_fetch_array($stase))
        {
          $internal_id = "internal_".$data_stase[id];
          $stase_id = "stase_".$data_stase[id];
          $update_internal_id=mysqli_query($con,"UPDATE `$internal_id` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
          $update_stase_id=mysqli_query($con,"UPDATE `$stase_id` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        }
        //tabel jurnal_ketrampilan
        $update_jurnal_ketrampilan=mysqli_query($con,"UPDATE `jurnal_ketrampilan` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //tabel jurnal_penyakit
        $update_jurnal_penyakit=mysqli_query($con,"UPDATE `jurnal_penyakit` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");

        //update nilai bagian
        //M091 - Ilmu Penyakit Dalam
        $update_ipd_nilai_kasus=mysqli_query($con,"UPDATE `ipd_nilai_kasus` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ipd_nilai_minicex=mysqli_query($con,"UPDATE `ipd_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ipd_nilai_test=mysqli_query($con,"UPDATE `ipd_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ipd_nilai_ujian=mysqli_query($con,"UPDATE `ipd_nilai_ujian` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M092 - Neurologi
        $update_neuro_nilai_cbd=mysqli_query($con,"UPDATE `neuro_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_neuro_nilai_jurnal=mysqli_query($con,"UPDATE `neuro_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_neuro_nilai_minicex=mysqli_query($con,"UPDATE `neuro_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_neuro_nilai_spv=mysqli_query($con,"UPDATE `neuro_nilai_spv` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_neuro_nilai_test=mysqli_query($con,"UPDATE `neuro_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M093 - Ilmu Kesehatan Jiwa
        $update_psikiatri_nilai_cbd=mysqli_query($con,"UPDATE `psikiatri_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_psikiatri_nilai_jurnal=mysqli_query($con,"UPDATE `psikiatri_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_psikiatri_nilai_minicex=mysqli_query($con,"UPDATE `psikiatri_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_psikiatri_nilai_osce=mysqli_query($con,"UPDATE `psikiatri_nilai_osce` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_psikiatri_nilai_test=mysqli_query($con,"UPDATE `psikiatri_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M105 - Ilmu Kesehatan THT-KL
        $update_thtkl_nilai_jurnal=mysqli_query($con,"UPDATE `thtkl_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_thtkl_nilai_osce=mysqli_query($con,"UPDATE `thtkl_nilai_osce` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_thtkl_nilai_presentasi=mysqli_query($con,"UPDATE `thtkl_nilai_presentasi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_thtkl_nilai_responsi=mysqli_query($con,"UPDATE `thtkl_nilai_responsi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_thtkl_nilai_test=mysqli_query($con,"UPDATE `thtkl_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M095 - IKM-KP
        $update_ikmkp_nilai_komprehensip=mysqli_query($con,"UPDATE `ikmkp_nilai_komprehensip` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ikmkp_nilai_p2ukm=mysqli_query($con,"UPDATE `ikmkp_nilai_p2ukm` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ikmkp_nilai_pkbi=mysqli_query($con,"UPDATE `ikmkp_nilai_pkbi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ikmkp_nilai_test=mysqli_query($con,"UPDATE `ikmkp_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M101 - Ilmu Bedah
        $update_bedah_nilai_mentor=mysqli_query($con,"UPDATE `bedah_nilai_mentor` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_bedah_nilai_test=mysqli_query($con,"UPDATE `bedah_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M102 - Anestesi dan Intensive Care
        $update_anestesi_nilai_dops=mysqli_query($con,"UPDATE `anestesi_nilai_dops` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_anestesi_nilai_osce=mysqli_query($con,"UPDATE `anestesi_nilai_osce` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_anestesi_nilai_test=mysqli_query($con,"UPDATE `anestesi_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M103 - Radiologi
        $update_radiologi_nilai_cbd=mysqli_query($con,"UPDATE `radiologi_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_radiologi_nilai_jurnal=mysqli_query($con,"UPDATE `radiologi_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_radiologi_nilai_test=mysqli_query($con,"UPDATE `radiologi_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M104 - Ilmu Kesehatan Mata
        $update_mata_nilai_jurnal=mysqli_query($con,"UPDATE `mata_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_mata_nilai_jurnal_penyanggah_1=mysqli_query($con,"UPDATE `mata_nilai_jurnal` SET `penyanggah_1`='$nim_baru' WHERE `penyanggah_1`='$nim_lama'");
        $update_mata_nilai_jurnal_penyanggah_2=mysqli_query($con,"UPDATE `mata_nilai_jurnal` SET `penyanggah_2`='$nim_baru' WHERE `penyanggah_2`='$nim_lama'");
        $update_mata_nilai_jurnal_penyanggah_3=mysqli_query($con,"UPDATE `mata_nilai_jurnal` SET `penyanggah_3`='$nim_baru' WHERE `penyanggah_3`='$nim_lama'");
        $update_mata_nilai_jurnal_penyanggah_4=mysqli_query($con,"UPDATE `mata_nilai_jurnal` SET `penyanggah_4`='$nim_baru' WHERE `penyanggah_4`='$nim_lama'");
        $update_mata_nilai_jurnal_penyanggah_5=mysqli_query($con,"UPDATE `mata_nilai_jurnal` SET `penyanggah_5`='$nim_baru' WHERE `penyanggah_5`='$nim_lama'");
        $update_mata_nilai_minicex=mysqli_query($con,"UPDATE `mata_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_mata_nilai_penyanggah=mysqli_query($con,"UPDATE `mata_nilai_penyanggah` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_mata_nilai_penyanggah_presenter=mysqli_query($con,"UPDATE `mata_nilai_penyanggah` SET `presenter`='$nim_baru' WHERE `presenter`='$nim_lama'");
        $update_mata_nilai_presentasi=mysqli_query($con,"UPDATE `mata_nilai_presentasi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_mata_nilai_presentasi_penyanggah_1=mysqli_query($con,"UPDATE `mata_nilai_presentasi` SET `penyanggah_1`='$nim_baru' WHERE `penyanggah_1`='$nim_lama'");
        $update_mata_nilai_presentasi_penyanggah_2=mysqli_query($con,"UPDATE `mata_nilai_presentasi` SET `penyanggah_2`='$nim_baru' WHERE `penyanggah_2`='$nim_lama'");
        $update_mata_nilai_presentasi_penyanggah_3=mysqli_query($con,"UPDATE `mata_nilai_presentasi` SET `penyanggah_3`='$nim_baru' WHERE `penyanggah_3`='$nim_lama'");
        $update_mata_nilai_presentasi_penyanggah_4=mysqli_query($con,"UPDATE `mata_nilai_presentasi` SET `penyanggah_4`='$nim_baru' WHERE `penyanggah_4`='$nim_lama'");
        $update_mata_nilai_presentasi_penyanggah_5=mysqli_query($con,"UPDATE `mata_nilai_presentasi` SET `penyanggah_5`='$nim_baru' WHERE `penyanggah_5`='$nim_lama'");
        $update_mata_nilai_test=mysqli_query($con,"UPDATE `mata_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M094 - IKFR
        $update_ikfr_nilai_kasus=mysqli_query($con,"UPDATE `ikfr_nilai_kasus` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ikfr_nilai_minicex=mysqli_query($con,"UPDATE `ikfr_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ikfr_nilai_test=mysqli_query($con,"UPDATE `ikfr_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M106 - IKGM
        $update_ikgm_nilai_jurnal=mysqli_query($con,"UPDATE `ikgm_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ikgm_nilai_kasus=mysqli_query($con,"UPDATE `ikgm_nilai_kasus` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ikgm_nilai_responsi=mysqli_query($con,"UPDATE `ikgm_nilai_responsi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ikgm_nilai_test=mysqli_query($con,"UPDATE `ikgm_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M111 - Ilmu Kebidanan dan Penyakit Kandungan
        $update_obsgyn_nilai_cbd=mysqli_query($con,"UPDATE `obsgyn_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_obsgyn_nilai_jurnal=mysqli_query($con,"UPDATE `obsgyn_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_obsgyn_nilai_minicex=mysqli_query($con,"UPDATE `obsgyn_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_obsgyn_nilai_test=mysqli_query($con,"UPDATE `obsgyn_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M112 - Kedokteran Forensik dan Medikolegal
        $update_forensik_nilai_jaga=mysqli_query($con,"UPDATE `forensik_nilai_jaga` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_forensik_nilai_referat=mysqli_query($con,"UPDATE `forensik_nilai_referat` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_forensik_nilai_substase=mysqli_query($con,"UPDATE `forensik_nilai_substase` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_forensik_nilai_test=mysqli_query($con,"UPDATE `forensik_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_forensik_nilai_visum=mysqli_query($con,"UPDATE `forensik_nilai_visum` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M113 - Ilmu Kesehatan Anak
        $update_ika_nilai_cbd=mysqli_query($con,"UPDATE `ika_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ika_nilai_dops=mysqli_query($con,"UPDATE `ika_nilai_dops` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ika_nilai_jurnal=mysqli_query($con,"UPDATE `ika_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ika_nilai_kasus=mysqli_query($con,"UPDATE `ika_nilai_kasus` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ika_nilai_minicex=mysqli_query($con,"UPDATE `ika_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ika_nilai_minipat=mysqli_query($con,"UPDATE `ika_nilai_minipat` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ika_nilai_test=mysqli_query($con,"UPDATE `ika_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_ika_nilai_ujian=mysqli_query($con,"UPDATE `ika_nilai_ujian` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M114 - Ilmu Kesehatan Kulit dan Kelamin
        $update_kulit_nilai_cbd=mysqli_query($con,"UPDATE `kulit_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_kulit_nilai_test=mysqli_query($con,"UPDATE `kulit_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //M121 - Komprehensip dan Kedokteran Keluarga
        //Komprehensip
        $update_kompre_nilai_cbd=mysqli_query($con,"UPDATE `kompre_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_kompre_nilai_laporan=mysqli_query($con,"UPDATE `kompre_nilai_laporan` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_kompre_nilai_presensi=mysqli_query($con,"UPDATE `kompre_nilai_presensi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_kompre_nilai_sikap=mysqli_query($con,"UPDATE `kompre_nilai_sikap` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        //Kedokteran Keluarga
        $update_kdk_nilai_dops=mysqli_query($con,"UPDATE `kdk_nilai_dops` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_kdk_nilai_kasus=mysqli_query($con,"UPDATE `kdk_nilai_kasus` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_kdk_nilai_minicex=mysqli_query($con,"UPDATE `kdk_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_kdk_nilai_presensi=mysqli_query($con,"UPDATE `kdk_nilai_presensi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
        $update_kdk_nilai_sikap=mysqli_query($con,"UPDATE `kdk_nilai_sikap` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");

        echo "Updating NIM Lama: $nim_lama ----> NIM Baru: $nim_baru .... SUKSES<br>";
        $sukses++;
      }
      else
      {
        echo "<font style=\"color:red\"><< Mahasiswa Koas dengan NIM $nim_lama belum memiliki akun di aplikasi E-Logbook Koas!!! >> .... GAGAL</font><br>";
        $fail++;
      }
    }
    $id++;
		}

    echo "<br><br>Proses update NIM selesai .... !!!<br>";
		echo "Jumlah mahasiswa koas yang telah diupdate NIM: $sukses<br>";
    echo "Jumlah status GAGAL update: $fail<br>";
  }
	else
	{
		echo "<br><br>Maaf! Ada kesalahan input file!!!";
	}
	echo "<br><br><form method=POST action=\"$_SERVER[PHP_SELF]\">
		<input type=submit class=\"submit1\" name=back value=BACK>
		</form>";

	if ($_POST['back']=="BACK")
  echo "
		<script>
			window.location.href=\"update_nim_koas.php\";
		</script>
		";
  echo "</fieldset>";
}
else
echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
}
?>



<!--</body></html>-->
</BODY>
</HTML>
