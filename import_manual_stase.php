<HTML>
<head>

<!--</head>-->
</head>
<BODY>

<?php
	
	include "config.php";
	include "fungsi.php";

	error_reporting("E_ALL ^ E_NOTICE");
	$data_import = mysqli_query($con,"SELECT * FROM `pp69_import` ORDER BY `id`");
	$no=0;
	while ($data=mysqli_fetch_array($data_import))
	{
		for ($x = 1; $x <= 6; $x++)
		{
			$stasex = "stase".$x;
			$tanggalx = "tgl".$x;
			$id_stase = $data[$stasex];
			$tgl_mulai = $data[$tanggalx];
			$stase_id = "stase_".$id_stase;
			if ($id_stase!="")
			{
				$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
				$hari_tambah = $data_stase['hari_stase']-1;
				$tambah_hari = '+'.$hari_tambah.' days';
				$tgl_selesai = date('Y-m-d', strtotime($tambah_hari, strtotime($tgl_mulai)));

				$jml_stase = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `$stase_id` WHERE `nim`='$data[nim]'"));
				if ($jml_stase<1)
				{
					$insert_stase=mysqli_query($con,"INSERT INTO `$stase_id`
						(`nim`, `rotasi`, `tgl_mulai`, `tgl_selesai`, `status`, `evaluasi`)
						VALUES
						('$data[nim]','$x','$tgl_mulai','$tgl_selesai','0','0')");
				}
				else {
					$update_stase=mysqli_query($con,"UPDATE `$stase_id`
						SET
						`rotasi`='$x',`tgl_mulai`='$tgl_mulai',
						`tgl_selesai`='$tgl_selesai',`status`='0',`evaluasi`='0'
						WHERE `nim`='$data[nim]'");
				}

			}
		}
		$no++;
	}

	echo "Update data $no mahasiswa .... selesai";
?>

<!--</body></html>-->
</BODY>
</HTML>
