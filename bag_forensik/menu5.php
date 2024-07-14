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
<?php require_once "../clear_dummy.php"; ?>
<img src="../images/main_header_new.jpg" style="width:100%;height:auto"><br>
<div class="menu">
	<ul class="nav">
		<li><a href="../menu_awal.php">BERANDA</a></li>
    <li><a href="../informasi.php">INFORMASI</a></li>
    <li><a href="../biodata.php">BIODATA</a></li>
    <li><a href="../rotasi_internal.php">ROTASI STASE</a></li>
    <li><a href="../rotasi_internal_stase.php">ROTASI INTERNAL</a></li>
    <li><a href="#">LOGBOOK JURNAL</a>
			<ul>
				<li><a href="../cek_logbook.php">&nbsp;&nbsp;CEK JURNAL</a></li>
				<li><a href="../isi_logbook.php">&nbsp;&nbsp;ISI JURNAL</a></li>
				<li><a href="../rekap_individu.php">&nbsp;&nbsp;REKAP JURNAL</a></li>
      </ul>
		</li>
    <li><a href="#">NILAI BAGIAN</a>
			<ul>
				<li><a href="../penilaian_bagian.php">&nbsp;&nbsp;PENILAIAN BAGIAN</a></li>
        <li><a href="../nilai_akhir_search.php">&nbsp;&nbsp;NILAI AKHIR BAGIAN</a></li>
      </ul>
		</li>
    <li><a href="#">USER INTERFACE</a>
			<ul>
				<li><a href="../edit_usermhsw_action.php">&nbsp;&nbsp;UPDATE PROFIL</a></li>
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
