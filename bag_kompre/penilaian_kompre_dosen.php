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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN KOMPREHENSIP</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">PENILAIAN KEPANITERAAN KOMPREHENSIP</font></h4>";
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

		$id_stase = "M121";
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";

		echo "</center><br><a href=\"#laporan\"><i>Penilaian Laporan</i></a><br>";
		echo "<a href=\"#sikap\"><i>Penilaian Sikap/Perilaku</i></a><br>";
		echo "<a href=\"#cbd\"><i>Penilaian Case Based Discussion (CBD)</i></a><br>";
		echo "<a href=\"#presensi\"><i>Penilaian Presensi / Kehadiran</i></a><br><br>";

		//Penilaian Laporan
		echo "<a id=\"laporan\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Laporan</font></a><br><br>";
		$nilai_laporan = mysqli_query($con,"SELECT * FROM `kompre_nilai_laporan` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze1\" style=\"width:100%\">";
		echo "<thead>
					<th style=\"width:5%\">No</th>
					<th style=\"width:15%\">Tanggal Pengisian</th>
					<th style=\"width:25%\">Instansi / Lokasi</th>
					<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
					<th style=\"width:25%\">Nama Dokter Muda</th>
					<th style=\"width:15%\">Status Approval</th>
					</thead>";
		$cek_nilai_laporan = mysqli_num_rows($nilai_laporan);
		if ($cek_nilai_laporan<1)
		{
			echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
			$no=1;
			$kelas = "ganjil";
			while ($data_laporan=mysqli_fetch_array($nilai_laporan))
			{
				$tanggal_isi = tanggal_indo($data_laporan[tgl_isi]);
				$tanggal_mulai = tanggal_indo($data_laporan[tgl_mulai]);
				$tanggal_selesai = tanggal_indo($data_laporan[tgl_selesai]);
				$tanggal_approval = tanggal_indo($data_laporan[tgl_approval]);
				$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_laporan[nim]'"));
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				echo "<td>$tanggal_isi</td>";
				echo "<td>Instansi: $data_laporan[instansi]<br><i>Lokasi: $data_laporan[lokasi]<i></td>";
				echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
				echo "<td>$data_mhsw[nama]<br>(NIM. $data_mhsw[nim])<br>";
				echo "<i>Nilai Individu: $data_laporan[nilai_rata_ind]<br>";
				echo "Nilai Kelompok: $data_laporan[nilai_rata_kelp]</i></td>";
				echo "<td align=center>";
				if ($data_laporan[status_approval]=='0')
				{
					echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
					echo "<a href=\"approve_laporan_dosen.php?id=$data_laporan[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_laporan[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
				}
				else
				{
					echo "<font style=\"color:green\">DISETUJUI</font><br>";
					echo "per tanggal<br>";
					echo "$tanggal_approval<p>";
					echo "<a href=\"view_form_laporan.php?id=$data_laporan[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_laporan[id]\" value=\"VIEW\"></a><p>";
					echo "<a href=\"unapprove_laporan_dosen.php?id=$data_laporan[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_laporan[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
				}
				echo "</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
		}
		echo "</table><br><br>";

		//Penilaian Sikap/Perilaku
		echo "<a id=\"sikap\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Sikap/Perilaku</font></a><br><br>";
		$nilai_sikap = mysqli_query($con,"SELECT * FROM `kompre_nilai_sikap` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze2\" style=\"width:100%\">";
		echo "<thead>
					<th style=\"width:5%\">No</th>
					<th style=\"width:15%\">Tanggal Pengisian</th>
					<th style=\"width:25%\">Instansi / Lokasi</th>
					<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
					<th style=\"width:25%\">Nama Dokter Muda</th>
					<th style=\"width:15%\">Status Approval</th>
					</thead>";
		$cek_nilai_sikap = mysqli_num_rows($nilai_sikap);
		if ($cek_nilai_sikap<1)
		{
			echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
			$no=1;
			$kelas = "ganjil";
			while ($data_sikap=mysqli_fetch_array($nilai_sikap))
			{
				$tanggal_isi = tanggal_indo($data_sikap[tgl_isi]);
				$tanggal_mulai = tanggal_indo($data_sikap[tgl_mulai]);
				$tanggal_selesai = tanggal_indo($data_sikap[tgl_selesai]);
				$tanggal_approval = tanggal_indo($data_sikap[tgl_approval]);
				$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_sikap[nim]'"));
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				echo "<td>$tanggal_isi</td>";
				echo "<td>Instansi: $data_sikap[instansi]<br><i>Lokasi: $data_sikap[lokasi]<br>";
				echo "Nilai: $data_sikap[nilai_rata]</i></td>";
				echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
				echo "<td>$data_mhsw[nama]<br>(NIM. $data_mhsw[nim])</td>";
				echo "<td align=center>";
				if ($data_sikap[status_approval]=='0')
				{
					echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
					echo "<a href=\"approve_sikap_dosen.php?id=$data_sikap[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_sikap[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
				}
				else
				{
					echo "<font style=\"color:green\">DISETUJUI</font><br>";
					echo "per tanggal<br>";
					echo "$tanggal_approval<p>";
					echo "<a href=\"view_form_sikap.php?id=$data_sikap[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_sikap[id]\" value=\"VIEW\"></a><p>";
					echo "<a href=\"unapprove_sikap_dosen.php?id=$data_sikap[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_sikap[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
				}
				echo "</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
		}
		echo "</table><br><br>";

		//Pengisian Formulir Penilaian CBD
		echo "<a id=\"cbd\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Case Based Discussion (CBD)</font></a><br><br>";
		$nilai_cbd = mysqli_query($con,"SELECT * FROM `kompre_nilai_cbd` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze3\" style=\"width:100%\">";
		echo "<thead>
					<th style=\"width:5%\">No</th>
					<th style=\"width:15%\">Tanggal/Jam Pengisian</th>
					<th style=\"width:40%\">Judul Kasus</th>
					<th style=\"width:25%\">Nama Dokter Muda</th>
					<th style=\"width:15%\">Status Approval</th>
					</thead>";
		$cek_nilai_cbd = mysqli_num_rows($nilai_cbd);
		if ($cek_nilai_cbd<1)
		{
			echo "<tr><td colspan=5 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
			$no=1;
			$kelas = "ganjil";
			while ($data_cbd=mysqli_fetch_array($nilai_cbd))
			{
				$tanggal_isi = tanggal_indo($data_cbd[tgl_isi]);
				$tanggal_approval = tanggal_indo($data_cbd[tgl_approval]);
				$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_cbd[nim]'"));
				$jam_isi = $data_cbd[jam_isi];
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				echo "<td>$tanggal_isi<br>Pukul $jam_isi</td>";
				echo "<td>Judul: <i>$data_cbd[kasus]</i><br><br>";
				echo "<i>Nilai: $data_cbd[nilai_rata]</i></td>";
				echo "<td>$data_mhsw[nama]<br>($data_mhsw[nim])</td>";
				echo "<td align=center>";
				if ($data_cbd[status_approval]=='0')
				{
					echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
					echo "<a href=\"approve_cbd_dosen.php?id=$data_cbd[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_cbd[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
				}
				else
				{
					echo "<font style=\"color:green\">DISETUJUI</font><br>";
					echo "per tanggal<br>";
					echo "$tanggal_approval<p>";
					echo "<a href=\"view_form_cbd.php?id=$data_cbd[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_cbd[id]\" value=\"VIEW\"></a><p>";
					echo "<a href=\"unapprove_cbd_dosen.php?id=$data_cbd[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_cbd[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
				}
				echo "</td>";
				echo "</tr>";
				$no++;
				if ($kelas=="ganjil") $kelas="genap";
				else $kelas="ganjil";
			}
		}
		echo "</table><br><br>";

		//Penilaian Presensi / Kehadiran
		echo "<a id=\"presensi\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Penilaian Presensi / Kehadiran</font></a><br><br>";
		$nilai_presensi = mysqli_query($con,"SELECT * FROM `kompre_nilai_presensi` WHERE `dosen`='$_COOKIE[user]' AND `tgl_isi`>='$tgl_mulai' AND `tgl_isi`<='$tgl_selesai' $filter_approval ORDER BY `id`");
		echo "<table id=\"freeze4\" style=\"width:100%\">";
		echo "<thead>
					<th style=\"width:5%\">No</th>
					<th style=\"width:15%\">Tanggal Pengisian</th>
					<th style=\"width:25%\">Lokasi / Instansi</th>
					<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
					<th style=\"width:25%\">Nama Dokter Muda</th>
					<th style=\"width:15%\">Status Approval</th>
					</thead>";
		$cek_nilai_presensi = mysqli_num_rows($nilai_presensi);
		if ($cek_nilai_presensi<1)
		{
			echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
		}
		else
		{
			$no=1;
			$kelas = "ganjil";
			while ($data_presensi=mysqli_fetch_array($nilai_presensi))
			{
				$tanggal_isi = tanggal_indo($data_presensi[tgl_isi]);
				$tanggal_mulai = tanggal_indo($data_presensi[tgl_mulai]);
				$tanggal_selesai = tanggal_indo($data_presensi[tgl_selesai]);
				$tanggal_approval = tanggal_indo($data_presensi[tgl_approval]);
				$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_presensi[nim]'"));
				echo "<tr class=\"$kelas\">";
				echo "<td align=center>$no</td>";
				echo "<td>$tanggal_isi</td>";
				echo "<td>$data_presensi[lokasi]<br><i>Instansi: $data_presensi[instansi]</i><br><i>Nilai: $data_presensi[nilai_total]</i></td>";
				echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
				echo "<td>$data_mhsw[nama]<br>(NIM. $data_mhsw[nim])</td>";
				echo "<td align=center>";
				if ($data_presensi[status_approval]=='0')
				{
					echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
					echo "<a href=\"approve_presensi_dosen.php?id=$data_presensi[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"approve\".\"$data_presensi[id]\" style=\"color:red\" value=\"VIEW & APPROVE\"></a>";
				}
				else
				{
					echo "<font style=\"color:green\">DISETUJUI</font><br>";
					echo "per tanggal<br>";
					echo "$tanggal_approval<p>";
					echo "<a href=\"view_form_presensi.php?id=$data_presensi[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"view\".\"$data_presensi[id]\" value=\"VIEW\"></a><p>";
					echo "<a href=\"unapprove_presensi_dosen.php?id=$data_presensi[id]&mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\"><input type=\"button\" name=\"unapprove\".\"$data_presensi[id]\" style=\"color:green\" value=\"UNAPPROVE\"></a>";
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
