<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="select2/dist/css/select2.css"/>
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
	<meta name="viewport" content="width=device-width, maximum-scale=1">
<!--</head>-->
</head>
<BODY>

<?php

include "config.php";

set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");


  $stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `semester`='$_POST[semester]' ORDER BY `id`");
  $no = 1;
  echo "<table border=\"0\">";
  ?>
  <tr class="ganjil">
    <td colspan="2" style="padding:5px 5px 5px 5px">
        <font style="font-size:1.0em">Urutan Kepaniteraan (Stase):</font>
    </td>
  </tr>
  <?php
  while ($dat=mysqli_fetch_array($stase))
  {
    echo "<tr class=\"ganjil\">";
      echo "<td style=\"padding:5px 5px 5px 5px;width:300px;\"><font style=\"font-size:1.0em\">Urutan ke-$no</font></td>";
      echo "<td style=\"padding:5px 5px 5px 5px;\">";
        $sts = mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `semester`='$_POST[semester]' ORDER BY `id`");
        echo "<select class=\"select_artwide\" name=\"stase$no\" id=\"stase$no\">";
        echo "<option value=\"\">< Pilihan Kepaniteraan (Stase) ></option>";
        while ($dat1=mysqli_fetch_array($sts))
        {
          $pekan_stase = $dat1[hari_stase]/7;
          echo "<option value=\"$dat1[id]\">$dat1[kepaniteraan] - Periode: $pekan_stase pekan ($dat1[hari_stase] hari)</option>";
        }
        echo "</select>";
      echo "</td>";
    echo "</tr>";

		echo "<tr class=\"ganjil\">";
		echo "<td style=\"padding:5px 5px 5px 5px\">";
		echo "<font style=\"font-size:1.0em\">Rencana Tanggal Mulai (<i>yyyy-mm-dd</i>)</font>";
		echo "</td>";
		echo "<td style=\"padding:5px 5px 5px 5px\">";
		echo "<input type=\"text\" id=\"input-tanggal$no\" class=\"select_art\" name=\"tgl_mulai$no\">";
		echo "<div id=\"tanggal$no\"></div>";
		echo "<div id=\"input_selesai$no\">";
			echo "<i>Edit Tanggal Selesai (yyyy-mm-dd):</i><p>";
			echo "<input type=\"text\" id=\"input-selesai$no\" class=\"select_art\" name=\"tgl_selesai$no\" placeholder=\"Kosongi jika tidak ada perubahan!\">";
		echo "</div>";
		echo "</td>";
		echo "</tr>";


    $no++;
  }
  echo "</table>";
	$no = $no-1;
	echo "<input type=\"hidden\" name=\"jml_stase\" value=\"$no\">";
?>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
<script src="select2/dist/js/select2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#input_selesai1').hide();
		$('#input_selesai2').hide();
		$('#input_selesai3').hide();
		$('#input_selesai4').hide();
		$('#input_selesai5').hide();
		$('#input_selesai6').hide();
		$('#input-tanggal1').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-tanggal1').change(function() {
			var tgl = $(this).val();
			var stase = $('#stase1').val();
			$.ajax({
				 type: 'POST',
				 url: 'tanggal_view.php?id=1',
				 data: {'tgl_mulai1':tgl,'stase1':stase},
				 success: function(response) {
					 $('#tanggal1').html(response);
					 $('#input_selesai1').show();
				 }
			 });
		 });
		$('#input-tanggal2').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-tanggal2').change(function() {
			var tgl = $(this).val();
			var stase = $('#stase2').val();
			$.ajax({
				 type: 'POST',
				 url: 'tanggal_view.php?id=2',
				 data: {'tgl_mulai2':tgl,'stase2':stase},
				 success: function(response) {
					 $('#tanggal2').html(response);
					 $('#input_selesai2').show();
				 }
			 });
		 });
		$('#input-tanggal3').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-tanggal3').change(function() {
			var tgl = $(this).val();
			var stase = $('#stase3').val();
			$.ajax({
				 type: 'POST',
				 url: 'tanggal_view.php?id=3',
				 data: {'tgl_mulai3':tgl,'stase3':stase},
				 success: function(response) {
					 $('#tanggal3').html(response);
					 $('#input_selesai3').show();
				 }
			 });
		 });
		$('#input-tanggal4').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-tanggal4').change(function() {
			var tgl = $(this).val();
			var stase = $('#stase4').val();
			$.ajax({
				 type: 'POST',
				 url: 'tanggal_view.php?id=4',
				 data: {'tgl_mulai4':tgl,'stase4':stase},
				 success: function(response) {
					 $('#tanggal4').html(response);
					 $('#input_selesai4').show();
				 }
			 });
		 });
		$('#input-tanggal5').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-tanggal5').change(function() {
			var tgl = $(this).val();
			var stase = $('#stase5').val();
			$.ajax({
				 type: 'POST',
				 url: 'tanggal_view.php?id=5',
				 data: {'tgl_mulai5':tgl,'stase5':stase},
				 success: function(response) {
					 $('#tanggal5').html(response);
					 $('#input_selesai5').show();
				 }
			 });
		 });
		$('#input-tanggal6').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-tanggal6').change(function() {
			var tgl = $(this).val();
			var stase = $('#stase6').val();
			$.ajax({
				 type: 'POST',
				 url: 'tanggal_view.php?id=6',
				 data: {'tgl_mulai6':tgl,'stase6':stase},
				 success: function(response) {
					 $('#tanggal6').html(response);
					 $('#input_selesai6').show();
				 }
			 });
		 });
    $("#stase1").select2({
      placeholder: "< Pilihan Kepaniteraan (Stase) >",
			allowClear: true
    });

    $("#stase2").select2({
      placeholder: "< Pilihan Kepaniteraan (Stase) >",
			allowClear: true
    });

    $("#stase3").select2({
      placeholder: "< Pilihan Kepaniteraan (Stase) >",
			allowClear: true
    });

    $("#stase4").select2({
      placeholder: "< Pilihan Kepaniteraan (Stase) >",
			allowClear: true
    });

    $("#stase5").select2({
      placeholder: "< Pilihan Kepaniteraan (Stase) >",
			allowClear: true
    });

    $("#stase6").select2({
      placeholder: "< Pilihan Kepaniteraan (Stase) >",
			allowClear: true
    });
		$('#input-selesai1').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-selesai2').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-selesai3').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-selesai4').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-selesai5').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#input-selesai6').datepicker({ dateFormat: 'yy-mm-dd' });

  });
</script>



<!--</body></html>-->
</BODY>
</HTML>
