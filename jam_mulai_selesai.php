<style>
	.thin-border {
		border: 2px solid black;
	}
</style>
<?php

set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

if ($_POST['kelas'] == "1") {
	$awal = 0;
	$akhir = 6;
}
if ($_POST['kelas'] == "2") {
	$awal = 7;
	$akhir = 9;
}
if ($_POST['kelas'] == "3") {
	$awal = 10;
	$akhir = 11;
}
if ($_POST['kelas'] == "4") {
	$awal = 12;
	$akhir = 13;
}
if ($_POST['kelas'] == "5") {
	$awal = 14;
	$akhir = 15;
}
if ($_POST['kelas'] == "6") {
	$awal = 16;
	$akhir = 23;
}


echo "<span style=\"font-weight:500\">Jam Mulai/Selesai Kegiatan:</span><p>";
echo "<table class=\"tabel_normal\" style=\"width:80%\">";
//Jam mulai
echo '<div class="row mb-3">';
echo '<div class="col-3" style="font-weight:500; color:darkgreen;">Jam Mulai</div>';
echo '<div class="col-3">';
echo '<input type="number" class="form-control thin-border" step="1" min="' . $awal . '" max="' . $akhir . '" name="jam_mulai" style="font-size:0.85em;text-align:center"  placeholder="[' . $awal . ' - ' . $akhir . ']" required />';
echo '</div>';
echo '<div class="col-3" style="font-weight:500; color:darkgreen;">Menit Mulai</div>';
echo '<div class="col-3">';
echo '<input type="number" class="form-control thin-border" step="1" min="0" max="59" name="menit_mulai" style="font-size:0.85em;text-align:center" placeholder="[0 - 59]" required />';
echo '</div>';
echo '</div>';
//Jam selesai
echo '<div class="row mb-3">';
echo '<div class="col-3" style="font-weight:500; color:darkgreen;">Jam Selesai</div>';
echo '<div class="col-3">';
echo '<input type="number" class="form-control thin-border" step="1" min="' . $awal . '" max="' . $akhir . '" name="jam_selesai" style="font-size:0.85em;text-align:center" placeholder="[' . $awal . ' - ' . $akhir . ']" required />';
echo '</div>';
echo '<div class="col-3" style="font-weight:500; color:darkgreen;">Menit Selesai</div>';
echo '<div class="col-3">';
echo '<input type="number" class="form-control thin-border" step="1" min="0" max="59" name="menit_selesai" style="font-size:0.85em;text-align:center" placeholder="[0 - 59]" required />';
echo '</div>';
echo '</div>';


// echo "<span style=\"font-weight:500\">Jam Mulai/Selesai Kegiatan:</span><p>";
// echo "<table class=\"table table-bordered\" style=\"width:80%;\">";
// // Jam Mulai
// echo "<tr>";
// echo "<td style=\"width:20%;\">Jam Mulai</td>";
// echo "<td style=\"width:30%;\">: <input type=\"number\" class=\"form-control\" step=\"1\" min=\"$awal\" max=\"$akhir\" name=\"jam_mulai\" style=\"width:100px; display:inline-block; font-size:0.85em; text-align:center;\" placeholder=\"[$awal - $akhir]\" required/></td>";
// echo "<td style=\"width:20%;\">Menit Mulai</td>";
// echo "<td style=\"width:30%;\">: <input type=\"number\" class=\"form-control\" step=\"1\" min=\"0\" max=\"59\" name=\"menit_mulai\" style=\"width:100px; display:inline-block; font-size:0.85em; text-align:center;\" placeholder=\"[0 - 59]\" required/></td>";
// echo "</tr>";
// // Jam selesai
// echo "<tr>";
// echo "<td style=\"width:20%;\">Jam Selesai</td>";
// echo "<td style=\"width:30%;\">: <input type=\"number\" class=\"form-control\" step=\"1\" min=\"$awal\" max=\"$akhir\" name=\"jam_selesai\" style=\"width:100px; display:inline-block; font-size:0.85em; text-align:center;\" placeholder=\"[$awal - $akhir]\" required/></td>";
// echo "<td style=\"width:20%;\">Menit Selesai</td>";
// echo "<td style=\"width:30%;\">: <input type=\"number\" class=\"form-control\" step=\"1\" min=\"0\" max=\"59\" name=\"menit_selesai\" style=\"width:100px; display:inline-block; font-size:0.85em; text-align:center;\" placeholder=\"[0 - 59]\" required/></td>";
// echo "</tr>";
// echo "</table>";
