<HTML>
<head>
  <title>::On-line Logbook Koas Pendidikan Dokter FK-UNDIP::</title>
  <meta name="viewport" content="width=device-width, maximum-scale=1">
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
  <style>
  .blink {
    animation: blink-animation 1s steps(5, start) infinite;
    -webkit-animation: blink-animation 1s steps(5, start) infinite;
  }
  @keyframes blink-animation {
    to {
      visibility: hidden;
    }
  }
  @-webkit-keyframes blink-animation {
    to {
      visibility: hidden;
    }
  }
  </style>
<!--</head>-->
</head>
<BODY>
<?php
  require_once "../clear_dummy.php";
  $pin = rand(1000,9999);
  $qr = acakstring(9);
  $reset_pin_qr = mysqli_query($con,"UPDATE `dosen` SET `pin`='$pin',`qr`='$qr' WHERE `nip`=$_COOKIE[user]");
?>
<img src="../images/main_header_new.jpg" style="width:100%;height:auto"><br>
<div class="menu">
	<ul class="nav">
		<li><a href="../menu_awal.php">BERANDA</a></li>
    <li><a href="#">EDIT DAFTAR</a>
			<ul>
        <li><a href="#">&nbsp;&nbsp;PENYAKIT</a>
          <ul>
            <li><a href="../cari_penyakit.php">&nbsp;&nbsp;EDIT PENYAKIT</a></li>
            <li><a href="../tambah_daftar_penyakit.php">&nbsp;&nbsp;TAMBAH PENYAKIT</a></li>
          </ul>
        </li>
        <li><a href="#">&nbsp;&nbsp;KETRAMPILAN</a>
          <ul>
            <li><a href="../cari_ketrampilan.php">&nbsp;&nbsp;EDIT KETRAMPILAN</a></li>
            <li><a href="../tambah_daftar_ketrampilan.php">&nbsp;&nbsp;TAMBAH KETRAMPILAN</a></li>
          </ul>
        </li>
      </ul>
		</li>
    <li><a href="#">LIHAT ROTASI</a>
			<ul>
        <li><a href="#">&nbsp;&nbsp;ROTASI STASE</a>
          <ul>
				    <li><a href="../view_rotasi_kelp.php">&nbsp;&nbsp;ROTASI ANGKATAN</a></li>
				    <li><a href="../view_rotasi_individu.php">&nbsp;&nbsp;ROTASI INDIVIDU</a></li>
          </ul>
        </li>
        <li><a href="../rotasi_internal_stase_search.php">&nbsp;&nbsp;ROTASI INTERNAL</a></li>
      </ul>
		</li>
    <li><a href="#">REKAP</a>
			<ul>
				<li><a href="#">&nbsp;&nbsp;REKAP UMUM</a>
					<ul>
            <li><a href="../rekap_umum_admin.php">&nbsp;&nbsp;REKAP STASE</a></li>
            <li><a href="../rekap_umumeval_admin.php">&nbsp;&nbsp;EVALUASI HARIAN</a></li>
            <li><a href="../rekap_umumeval_stase_search.php">&nbsp;&nbsp;EVALUASI STASE</a></li>
            <li><a href="../capaian_umum_search.php">&nbsp;&nbsp;KETUNTASAN/GRADE</a></li>
            <li><a href="../nilai_bag_umum_search.php">&nbsp;&nbsp;REKAP NILAI BAGIAN</a></li>
            <li><a href="../nilai_akhir_umum_search.php">&nbsp;&nbsp;REKAP NILAI AKHIR</a></li>
          </ul>
				</li>
				<li><a href="#">&nbsp;&nbsp;REKAP INDIVIDU</a>
					<ul>
            <li><a href="../rekap_individu_search.php">&nbsp;&nbsp;REKAP STASE</a></li>
            <li><a href="../rekap_evaluasi_search.php">&nbsp;&nbsp;EVALUASI HARIAN</a></li>
            <li><a href="../rekap_evaluasi_stase_search.php">&nbsp;&nbsp;EVALUASI STASE</a></li>
            <li><a href="../capaian_individu_search.php">&nbsp;&nbsp;KETUNTASAN/GRADE</a></li>
            <li><a href="../nilai_bag_search.php">&nbsp;&nbsp;CETAK NILAI BAGIAN</a></li>
            <li><a href="../nilai_akhir_search.php">&nbsp;&nbsp;NILAI AKHIR BAGIAN</a></li>
          </ul>
				</li>
			</ul>
		</li>
    <li><a href="#">GENERATE</a>
      <ul>
        <li><a href="../generate_pin_search.php">&nbsp;&nbsp;GENERATE OTP</a></li>
        <li><a href="../generate_qr_search.php">&nbsp;&nbsp;GENERATE QR</a></li>
      </ul>
    </li>
    <li><a href="#">KEGIATAN</a>
			<ul>
				<li><a href="../filter_kegiatan_dosen_search.php">&nbsp;&nbsp;DAFTAR KEGIATAN</a></li>
				<li><a href="../filter_rekap_kegiatan.php">&nbsp;&nbsp;REKAP KEGIATAN</a></li>
        <li><a href="#">&nbsp;&nbsp;ENTRY NILAI</a>
          <ul>
				    <li><a href="../upload_nilai.php">&nbsp;&nbsp;UPLOAD NILAI</a></li>
				    <li><a href="../entry_nilai_manual.php">&nbsp;&nbsp;ENTRY MANUAL</a></li>
            <li><a href="../hapus_nilai_manual.php">&nbsp;&nbsp;HAPUS MANUAL</a></li>
          </ul>
        </li>
			</ul>
		</li>
    <li><a href="#">EDIT USER</a>
      <ul>
        <li><a href="../edit_user_mhsw.php">&nbsp;&nbsp;USER MAHASISWA</a></li>
        <li><a href="../edit_user_dosen.php">&nbsp;&nbsp;USER DOSEN/RESIDEN</a></li>
      </ul>
    </li>
    <li><a href="#">USER INTERFACE</a>
			<ul>
        <li><a href="../profil_dosen.php">&nbsp;&nbsp;PROFIL DIRI</a></li>
        <li><a href="../update_admin.php">&nbsp;&nbsp;UPDATE PROFIL</a></li>
				<li><a href="../logout.php">&nbsp;&nbsp;LOGOUT</a></li>
			</ul>
		</li>
  </ul>
</div>

<script src="../jquery-3.1.1.min.js"></script>

<script>
/* To Disable Inspect Element */
$(document).bind("contextmenu",function(e) {
 e.preventDefault();
});

$(document).keydown(function(e){
    if(e.which === 123){
       return false;
    }
});

$(document).bind("contextmenu",function(e) {
 e.preventDefault();
});
$(document).keydown(function(e){
    if(e.ctrlKey && (e.which === 83)){
       e.preventDefault();
       return false;
    }
});

document.onkeydown = function(e) {
if(event.keyCode == 123) {
return false;
}
if(e.ctrlKey && e.keyCode == 'E'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'H'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'A'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'F'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'E'.charCodeAt(0)){
return false;
}
}
</script>

<!--</body></html>-->
</BODY>
</HTML>
