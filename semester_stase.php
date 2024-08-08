<HTML>

<head>
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
	<meta name="viewport" content="width=device-width, maximum-scale=1">
	<!--</head>-->
</head>

<BODY>

	<?php

	include "config.php";

	set_time_limit(0);
	error_reporting("E_ALL ^ E_NOTICE");


	$stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `semester`='$_POST[semester]' ORDER BY `id`");
	$no = 1;
	// echo "<table border=\"0\">";
	?>
	<br><br>
	<table class="table table-bordered" style="width: auto;">
		<tr class="table-primary" style="border-width: 1px; border-color: #000;">
			<td colspan="2" style="text-align:center;">
				<span style="font-size:1.0em; font-weight:600;">Urutan Kepaniteraan <span class="text-danger">(STASE)</span>:</span>
			</td>
		</tr>
		<?php while ($dat = mysqli_fetch_array($stase)) { ?>
			<tr class="table-success" style="border-width: 1px; border-color: #000;">
				<td style="text-align:center;width:300px;">
					<span style="font-size:1.0em;font-weight:600;">Urutan ke <span class="text-danger"><?php echo $no; ?></span>:</span>
				</td>
				<td style="padding:5px;">
					<?php
					$sts = mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `semester`='$_POST[semester]' ORDER BY `id`");
					?>
					<select class="select_artwide" name="stase<?php echo $no; ?>" id="stase<?php echo $no; ?>">
						<option value="">
							< Pilihan Kepaniteraan (Stase)>
						</option>
						<?php while ($dat1 = mysqli_fetch_array($sts)) {
							$pekan_stase = $dat1['hari_stase'] / 7; ?>
							<option value="<?php echo $dat1['id']; ?>">
								<?php echo $dat1['kepaniteraan']; ?> - Periode: <?php echo $pekan_stase; ?> pekan (<?php echo $dat1['hari_stase']; ?> hari)
							</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr class="table-primary" style="border-width: 1px; border-color: #000;">
				<td style="padding:5px;">
					<span style="font-size:1.0em;font-weight:600;">Rencana Tanggal Mulai (<span class="text-danger">yyyy-mm-dd</span>)</span>
				</td>
				<td style="padding:5px;">
					<input type="text" id="input-tanggal<?php echo $no; ?>" class="form-select" name="tgl_mulai<?php echo $no; ?>">
					<div id="tanggal<?php echo $no; ?>"></div>
					<div id="input_selesai<?php echo $no; ?>">
						<span style="font-size:0.8em;">Edit Tanggal Selesai <span class="text-danger">(yyyy-mm-dd)</span>:</span>
						<p>
							<input type="text" id="input-selesai<?php echo $no; ?>" class="form-select" name="tgl_selesai<?php echo $no; ?>" placeholder="Kosongi jika tidak ada perubahan!">
						</p>
					</div>
				</td>
			</tr>
			<?php $no++; ?>
		<?php } ?>
	</table>
	<input type="hidden" name="jml_stase" value="<?php echo $no - 1; ?>">

	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#input_selesai1').hide();
			$('#input_selesai2').hide();
			$('#input_selesai3').hide();
			$('#input_selesai4').hide();
			$('#input_selesai5').hide();
			$('#input_selesai6').hide();
			$('#input-tanggal1').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-tanggal1').change(function() {
				var tgl = $(this).val();
				var stase = $('#stase1').val();
				$.ajax({
					type: 'POST',
					url: 'tanggal_view.php?id=1',
					data: {
						'tgl_mulai1': tgl,
						'stase1': stase
					},
					success: function(response) {
						$('#tanggal1').html(response);
						$('#input_selesai1').show();
					}
				});
			});
			$('#input-tanggal2').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-tanggal2').change(function() {
				var tgl = $(this).val();
				var stase = $('#stase2').val();
				$.ajax({
					type: 'POST',
					url: 'tanggal_view.php?id=2',
					data: {
						'tgl_mulai2': tgl,
						'stase2': stase
					},
					success: function(response) {
						$('#tanggal2').html(response);
						$('#input_selesai2').show();
					}
				});
			});
			$('#input-tanggal3').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-tanggal3').change(function() {
				var tgl = $(this).val();
				var stase = $('#stase3').val();
				$.ajax({
					type: 'POST',
					url: 'tanggal_view.php?id=3',
					data: {
						'tgl_mulai3': tgl,
						'stase3': stase
					},
					success: function(response) {
						$('#tanggal3').html(response);
						$('#input_selesai3').show();
					}
				});
			});
			$('#input-tanggal4').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-tanggal4').change(function() {
				var tgl = $(this).val();
				var stase = $('#stase4').val();
				$.ajax({
					type: 'POST',
					url: 'tanggal_view.php?id=4',
					data: {
						'tgl_mulai4': tgl,
						'stase4': stase
					},
					success: function(response) {
						$('#tanggal4').html(response);
						$('#input_selesai4').show();
					}
				});
			});
			$('#input-tanggal5').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-tanggal5').change(function() {
				var tgl = $(this).val();
				var stase = $('#stase5').val();
				$.ajax({
					type: 'POST',
					url: 'tanggal_view.php?id=5',
					data: {
						'tgl_mulai5': tgl,
						'stase5': stase
					},
					success: function(response) {
						$('#tanggal5').html(response);
						$('#input_selesai5').show();
					}
				});
			});
			$('#input-tanggal6').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-tanggal6').change(function() {
				var tgl = $(this).val();
				var stase = $('#stase6').val();
				$.ajax({
					type: 'POST',
					url: 'tanggal_view.php?id=6',
					data: {
						'tgl_mulai6': tgl,
						'stase6': stase
					},
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
			$('#input-selesai1').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-selesai2').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-selesai3').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-selesai4').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-selesai5').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-selesai6').datepicker({
				dateFormat: 'yy-mm-dd'
			});

		});
	</script>



	<!--</body></html>-->
</BODY>

</HTML>