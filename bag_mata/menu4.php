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
    <li><a href="../informasi.php">INFORMASI</a></li>
    <li><a href="../profil_dosen.php">PROFIL DIRI</a></li>
    <li><a href="../generate_pin.php">GENERATE OTP</a></li>
    <li><a href="../generate_qr.php">GENERATE QR</a></li>
    <li><a href="#">KEGIATAN</a>
			<ul>
				<li><a href="../filter_kegiatan_dosen.php">&nbsp;&nbsp;DAFTAR KEGIATAN</a></li>
				<li><a href="../filter_rekap_kegiatan.php">&nbsp;&nbsp;REKAP KEGIATAN</a></li>
        <li><a href="../penilaian_bag_dosen.php">&nbsp;&nbsp;PENILAIAN BAGIAN</a></li>
			</ul>
		</li>
    <li><a href="#">USER INTERFACE</a>
			<ul>
				<li><a href="../edit_userdosen_action.php">&nbsp;&nbsp;UPDATE PROFIL</a></li>
				<li><a href="../logout.php">&nbsp;&nbsp;LOGOUT</a></li>
			</ul>
		</li>
		<li class="disable"><a>&nbsp;</a></li>
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
