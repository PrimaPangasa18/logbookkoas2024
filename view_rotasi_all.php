<HTML>
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
<!--</head>-->
</head>
<BODY>
<?php

include "config.php";
include "fungsi.php";
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
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
{
  if ($_COOKIE['level']==1) {include "menu1.php";}
  if ($_COOKIE['level']==2) {include "menu2.php";}
  if ($_COOKIE['level']==3) {include "menu3.php";}

  echo "<div class=\"text_header\">ROTASI KEPANITERAAN (STASE)</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

  echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI KEPANITERAAN (STASE)</font></h4><br>";

  $angkatan = $_GET['angk'];
  if ($angkatan=="all")
  {
    $mhsw = mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
    $angkatan = "Semua Angkatan";
  }
  else
  $mhsw = mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `angkatan`='$angkatan' ORDER BY `nama`");
  $jml_mhsw = mysqli_num_rows($mhsw);

  echo "<table border=0 style=\"width:30%\">";
  echo "<tr><td style=\"width:40%\">Angkatan</td><td style=\"width:60%\">: $angkatan</td></tr>";
  echo "<tr><td>Jumlah Mahasiswa</td><td>: $jml_mhsw orang</td></tr>";
  echo "</table><br>";

  echo "<table style=\"width:100%;\" id=\"freeze\">";
  echo "<thead>";
  echo "<tr>";
  echo "<td colspan=18 align=center style=\"background:white;\">";
  //Keterangan
  echo "<table style=\"width:100%;border:0;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0);\">";
  echo "<tr>";
    echo "<td colspan=\"4\"><font style=\"font-size:0.8em;line-height:0.8em;\"><i>Keterangan:</i></font></td>";
  echo "</tr>";
  echo "<tr>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M091</b>: <i>Ilmu Penyakit Dalam</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M101</b>: <i>Ilmu Bedah</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M111</b>: <i>Ilmu Kebidanan dan Penyakit Kandungan</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M121</b>: <i>Komprehensip dan Kedokteran Keluarga</i></font></td>";
  echo "</tr>";
  echo "<tr>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M092</b>: <i>Neurologi</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M102</b>: <i>Anestesi dan Intensive Care</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M112</b>: <i>Kedokteran Forensik dan Medikolegal</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\">&nbsp;</td>";
  echo "</tr>";
  echo "<tr>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M093</b>: <i>Ilmu Kesehatan Jiwa</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M103</b>: <i>Radiologi</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M113</b>: <i>Ilmu Kesehatan Anak</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\">&nbsp;</td>";
  echo "</tr>";
  echo "<tr>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M094</b>: <i>IKFR</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M104</b>: <i>Ilmu Kesehatan Mata</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M114</b>: <i>Ilmu Kesehatan Kulit dan Kelamin</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\">&nbsp;</td>";
  echo "</tr>";
  echo "<tr>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M095</b>: <i>IKM-KP</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M105</b>: <i>Ilmu Kesehatan THT-KL</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\">&nbsp;</td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\">&nbsp;</td>";
  echo "</tr>";
  echo "<tr>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M096</b>: <i>Ilmu Jantung dan Pembuluh Darah</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\"><b>M106</b>: <i>IKGM</i></font></td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\">&nbsp;</td>";
    echo "<td><font style=\"font-size:0.8em;line-height:0.8em;\">&nbsp;</td>";
  echo "</tr>";
  echo "</table>";
  //-----------
  echo "</td>";
  echo "</tr>";
  echo "<tr>";
  echo "<th style=\"width:3%;\">No</th>";
  echo "<th style=\"width:12%;\">Nama (NIM)</th>";
  $stase1 = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
  while ($data_stase1=mysqli_fetch_array($stase1))
  {
    echo "<th style=\"width:5%;\">$data_stase1[id]</th>";
  }
  echo "</tr>";
  echo "</thead>";
  $no=1;
  $kelas=ganjil;
  while ($data_mhsw=mysqli_fetch_array($mhsw))
  {
    echo "<tr class=\"$kelas\">";
      echo "<td>$no</td>";
      echo "<td>$data_mhsw[nama]<br>$data_mhsw[nim]</td>";
      $stase2 = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
      while ($data_stase2=mysqli_fetch_array($stase2))
      {
        $stase_id = "stase_".$data_stase2[id];
        $jml_stase_mhsw = mysqli_num_rows(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
        $stase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
        if ($jml_stase_mhsw>=1)
        {
          $tglmulai=tanggal_indo_skt($stase_mhsw[tgl_mulai]);
          $tglselesai=tanggal_indo_skt($stase_mhsw[tgl_selesai]);
          echo "<td align=\"center\" style=\"padding:5px 0 5px 0;background-color:rgba(123, 205, 91, 0.2)\"><font style=\"font-size:0.7em\">$tglmulai<br> sd<br> $tglselesai</font></td>";
        }
        else echo "<td style=\"background-color:rgba(252, 15, 0, 0.2)\">&nbsp;</td>";
      }
    echo "</tr>";
    if ($kelas=="ganjil") $kelas="genap";
    else $kelas="ganjil";
    $no++;
  }
  echo "</table>";


  echo "<br><br></fieldset>";
}
else
echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
}
?>

<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
<script>
  $(document).ready(function(){
	   $("#freeze").freezeHeader();
  });
</script>



<!--</body></html>-->
</BODY>
</HTML>
