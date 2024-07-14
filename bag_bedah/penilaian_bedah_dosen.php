<HTML>
<head>
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
<!--</head>-->
</head>
<BODY>

<?php

	include "../config.php";
	include "../fungsi.php";

	error_reporting("E_ALL ^ E_NOTICE");

	if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
	else{
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==4)
	{
		if ($_COOKIE['level']==4) {include "menu4.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU BEDAH</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">PENILAIAN KEPANITERAAN (STASE) ILMU BEDAH</font></h4>";
		$tgl_mulai = $_GET[mulai];
		$tgl_selesai = $_GET[selesai];
		$approval = $_GET[approval];
		$mhsw = $_GET[mhsw];

		if ($approval=="all")
		{
			if ($mhsw=="all")
			{
				$filter_approval ="";
				$statusapproval = "Semua Status";
				$statusmhsw = "Semua Mahasiswa";
			}
			else
			{
				$nama_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$mhsw'"));
				$filter_approval ="AND `nim`='$mhsw'";
				$statusapproval = "Semua Status";
				$statusmhsw = "$nama_mhsw[nama] (NIM: $mhsw)";
			}

		}
		else
		{
			if ($mhsw=="all")
			{
				$filter_approval = "AND `status_approval`='$approval'";
				if ($approval=="0") $statusapproval = "Belum Disetujui";
				if ($approval=="1") $statusapproval = "Sudah Disetujui";
				$statusmhsw = "Semua Mahasiswa";
			}
			else
			{
				$nama_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$mhsw'"));
				$filter_approval = "AND `nim`='$mhsw' AND `status_approval`='$approval'";
				if ($approval=="0") $statusapproval = "Belum Disetujui";
				if ($approval=="1") $statusapproval = "Sudah Disetujui";
				$statusmhsw = "$nama_mhsw[nama] (NIM: $mhsw)";
			}
		}

		$mulai = tanggal_indo($tgl_mulai);
		$selesai = tanggal_indo($tgl_selesai);
		echo "<table class=\"tabel_normal\" style=\"font-size:0.85em;\">";
		echo "<tr><td style=\"padding:0 0 0 0;\">Periode Tanggal Pengisian</td><td style=\"padding:0 0 0 0;\">:</td><td style=\"padding:0 0 0 0;\"><i>$mulai s.d. $selesai</i></td></tr>";
		echo "<tr><td style=\"padding:0 0 0 0;\">Status Approval</td><td style=\"padding:0 0 0 0;\">:</td><td style=\"padding:0 0 0 0;\"><i>$statusapproval</i></td></tr>";
		echo "<tr><td style=\"padding:0 0 0 0;\">Mahasiswa</td><td style=\"padding:0 0 0 0;\">:</td><td style=\"padding:0 0 0 0;\"><i>$statusmhsw</i></td></tr>";
		echo "</table><br>";

		$id_stase = "M101";
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";

		echo "</center><br><a href=\"#mentor\"><i>Penilaian Mentoring</i></a><br><br>";

		//Penilaian Mentoring
		echo "<a id=\"mentor\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Mentoring</font></a><br><br>";
		$nilai_mentor = mysqli_query($con,"SELECT * FROM `bedah_nilai_mentor` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze1\" style=\"width:100%\">";
		echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:65%\">Jenis Penilaian</th>
						<th style=\"width:15%\">Status Approval</th>
					</thead>";
		$cek_nilai_mentor = mysqli_num_rows($nilai_mentor);
		if ($cek_nilai_mentor<1)
		{
			echo "<tr><td colspan=4 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
			$no=1;
			$kelas = "ganjil";
			while ($data_mentor=mysqli_fetch_array($nilai_mentor))
			{
				$tanggal_isi = tanggal_indo($data_mentor[tgl_isi]);
				$tanggal_awal = tanggal_indo($data_mentor[tgl_awal]);
				$tanggal_akhir = tanggal_indo($data_mentor[tgl_akhir]);
				$tanggal_approval = tanggal_indo($data_mentor[tgl_approval]);
				$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_mentor[nim]'"));
				$jam_isi = $data_mentor[jam_isi];
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				echo "<td>$tanggal_isi</td>";
				echo "<td>Penilaian Mentoring<br><br>";
				echo "Nama Mahasiswa (NIM): <i>$data_mhsw[nama] ($data_mhsw[nim])</i><br>
							Mentoring Bulan ke-$data_mentor[bulan_ke]<br>
							Periode Tanggal: <i>$tanggal_awal s.d. $tanggal_akhir</i><br>
							Nilai: <i>$data_mentor[nilai_rata]</i>
							</td>";
				echo "<td align=center>";
				if ($data_mentor[status_approval]=='0')
				{
					echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
					echo "<a href=\"approve_mentor_dosen.php?id=$data_mentor[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_mentor[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
				}
				else
				{
					echo "<font style=\"color:green\">DISETUJUI</font><br>";
					echo "per tanggal<br>";
					echo "$tanggal_approval<p>";
					echo "<a href=\"view_form_mentor.php?id=$data_mentor[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_mentor[id]\" value=\"VIEW\"></a><p>";
					echo "<a href=\"unapprove_mentor_dosen.php?id=$data_mentor[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_mentor[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
				}
				echo "</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
		}
		echo "</table><br><br>";

		echo "<center><a href=\"#top\"><i>Goto top</i></a></center><br><br>";
		echo "</fieldset>";

	}
		else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
?>
<script type="text/javascript" src="../jquery.min.js"></script>
<script type="text/javascript" src="../freezeheader/js/jquery.freezeheader.js"></script>
<script>
  $(document).ready(function(){
	   $("#freeze1").freezeHeader();
		 $("#freeze2").freezeHeader();
		 $("#freeze3").freezeHeader();
		 $("#freeze4").freezeHeader();
  });
</script>
<!--</body></html>-->
</BODY>
</HTML>
