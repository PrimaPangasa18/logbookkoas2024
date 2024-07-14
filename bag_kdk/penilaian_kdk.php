<HTML>
<head>
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN KEDOKTERAN KELUARGA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">FORM PENILAIAN KEPANITERAAN KEDOKTERAN KELUARGA</font></h4><br>";
		$id_stase = "M121";
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$cek_stase = mysqli_num_rows($data_stase_mhsw);
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
		echo "<table style=\"width:60%;border:collapse;\">";
		echo "<tr><td style=\"width:40%;\">Kepaniteraan (stase)</td><td style=\"width:60%;\">: $data_stase[kepaniteraan]</td></tr>";
		if ($cek_stase>=1)
		{
			$tgl_mulai = tanggal_indo($datastase_mhsw[tgl_mulai]);
			$mulai = date_create($datastase_mhsw[tgl_mulai]);
			$tgl_selesai = tanggal_indo($datastase_mhsw[tgl_selesai]);
			echo "<tr><td>Tanggal mulai kepaniteraan (stase)</td><td>: $tgl_mulai</td></tr>";
			echo "<tr><td>Tanggal selesai kepaniteraan (stase)</td><td>: $tgl_selesai</td></tr>";
		}
		else
		{
			echo "<tr><td>Status Kepaniteraan (stase)</td><td>: <font style=\"color:red\">BELUM AKTIF</font></td></tr>";
		}
		echo "</table><br><br>";

		if ($cek_stase>=1)
		{
			echo "</center><br><a href=\"#lap_kasus\"><i>Pengisian Formulir Penilaian Portofolio Laporan Kasus</i></a><br>";
			echo "<a href=\"#sikap\"><i>Pengisian Formulir Penilaian Sikap/Perilaku</i></a><br>";
			echo "<a href=\"#dops\"><i>Pengisian Formulir DOPS</i></a><br>";
			echo "<a href=\"#minicex\"><i>Pengisian Formulir Penilaian MINI-CEX</i></a><br>";
			echo "<a href=\"#presensi\"><i>Pengisian Formulir Penilaian Presensi / Kehadiran</i></a><br>";
			echo "<a href=\"#cetak_nilai\"><i>Cetak Nilai Kepaniteraan Kedokteran Keluarga</i></a><br><br>";


			//Pengisian Formulir Penilaian Portofolio Laporan Kasus
			echo "<a id=\"lap_kasus\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Portofolio Laporan Kasus</font></a><br><br>";
			$nilai_kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze1\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Lokasi / Kasus</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dosen Pembimbing Lapangan</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_kasus = mysqli_num_rows($nilai_kasus);
			if ($cek_nilai_kasus<1)
			{
				echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_kasus=mysqli_fetch_array($nilai_kasus))
				{
					$tanggal_isi = tanggal_indo($data_kasus[tgl_isi]);
					$tanggal_mulai = tanggal_indo($data_kasus[tgl_mulai]);
					$tanggal_selesai = tanggal_indo($data_kasus[tgl_selesai]);
					$tanggal_approval = tanggal_indo($data_kasus[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>$data_kasus[lokasi]<br><i>Kasus: $data_kasus[kasus]</i><br><br><i>Nilai: $data_kasus[nilai_rata]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
					if ($data_kasus[status_approval]=='0') echo "<a href=\"approve_kasus.php?id=$data_kasus[id]\"><input type=\"button\" name=\"approve\".\"$data_kasus[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
					echo "</td>";
					echo "<td align=center>";
					if ($data_kasus[status_approval]=='0')
					{
						echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
						echo "<a href=\"edit_form_kasus.php?id=$data_kasus[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_kasus[id]\" value=\"EDIT\"></a><p>";
						echo "<a href=\"preview_form_kasus.php?id=$data_kasus[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_kasus[id]\" value=\"PREVIEW\"></a><p>";
						echo "<a href=\"hapus_form_kasus.php?id=$data_kasus[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_kasus[id]\" value=\"HAPUS\"></a>";
					}
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
						echo "<br><br><a href=\"view_form_kasus.php?id=$data_kasus[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_kasus[id]\" value=\"VIEW\"></a><p>";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table>";
			echo "<br><center><a href=\"tambah_laporan_kasus.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_kasus = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_kasus` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_kasus>0)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"cetak_laporan_kasus.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian Sikap/Perilaku
			echo "<a id=\"sikap\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Sikap/Perilaku</font></a><br><br>";
			$nilai_sikap = mysqli_query($con,"SELECT * FROM `kdk_nilai_sikap` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze2\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Instansi / Lokasi</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dokter Pembimbing</th>
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
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_sikap[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_sikap[instansi]<br><i>Lokasi: $data_sikap[lokasi]</i><br><br><i>Nilai: $data_sikap[nilai_rata]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
					if ($data_sikap[status_approval]=='0') echo "<a href=\"approve_sikap.php?id=$data_sikap[id]\"><input type=\"button\" name=\"approve\".\"$data_sikap[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
					echo "</td>";
					echo "<td align=center>";
					if ($data_sikap[status_approval]=='0')
					{
						echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
						echo "<a href=\"edit_form_sikap.php?id=$data_sikap[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_sikap[id]\" value=\"EDIT\"></a><p>";
						echo "<a href=\"preview_form_sikap.php?id=$data_sikap[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_sikap[id]\" value=\"PREVIEW\"></a><p>";
						echo "<a href=\"hapus_form_sikap.php?id=$data_sikap[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_sikap[id]\" value=\"HAPUS\"></a>";
					}
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
						echo "<br><br><a href=\"view_form_sikap.php?id=$data_sikap[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_sikap[id]\" value=\"VIEW\"></a><p>";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table>";
			echo "<br><center><a href=\"tambah_nilai_sikap.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_sikap = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_sikap` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_sikap>0)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"cetak_nilai_sikap.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Pengisian Formulir DOPS
			echo "<a id=\"dops\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir DOPS</font></a><br><br>";
			$nilai_dops = mysqli_query($con,"SELECT * FROM `kdk_nilai_dops` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze3\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Instansi / Lokasi</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dokter Pembimbing</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_dops = mysqli_num_rows($nilai_dops);
			if ($cek_nilai_dops<1)
			{
				echo "<tr><td colspan=6 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_dops=mysqli_fetch_array($nilai_dops))
				{
					$tanggal_isi = tanggal_indo($data_dops[tgl_isi]);
					$tanggal_mulai = tanggal_indo($data_dops[tgl_mulai]);
					$tanggal_selesai = tanggal_indo($data_dops[tgl_selesai]);
					$tanggal_approval = tanggal_indo($data_dops[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_dops[instansi]<br><i>Lokasi: $data_dops[lokasi]</i><br><br><i>Nilai: $data_dops[nilai_rata]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
					if ($data_dops[status_approval]=='0') echo "<a href=\"approve_dops.php?id=$data_dops[id]\"><input type=\"button\" name=\"approve\".\"$data_dops[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
					echo "</td>";
					echo "<td align=center>";
					if ($data_dops[status_approval]=='0')
					{
						echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
						echo "<a href=\"edit_form_dops.php?id=$data_dops[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_dops[id]\" value=\"EDIT\"></a><p>";
						echo "<a href=\"preview_form_dops.php?id=$data_dops[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_dops[id]\" value=\"PREVIEW\"></a><p>";
						echo "<a href=\"hapus_form_dops.php?id=$data_dops[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_dops[id]\" value=\"HAPUS\"></a>";
					}
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
						echo "<br><br><a href=\"view_form_dops.php?id=$data_dops[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_dops[id]\" value=\"VIEW\"></a><p>";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table>";
			echo "<br><center><a href=\"tambah_nilai_dops.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_dops = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_dops` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_dops>0)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"cetak_nilai_dops.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian MINI-CEX
			echo "<a id=\"minicex\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian MINI-CEX</font></a><br><br>";
			$nilai_minicex = mysqli_query($con,"SELECT * FROM `kdk_nilai_minicex` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze4\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:20%\">Instansi / Lokasi</th>
						<th style=\"width:5%\">No Ujian</th>
						<th style=\"width:15%\">Problem Pasien/Diagnosis</th>
						<th style=\"width:25%\">Penilai/DPJP</th>
						<th style=\"width:15%\">Status Approval</th>
						</thead>";
			$cek_nilai_minicex = mysqli_num_rows($nilai_minicex);
			if ($cek_nilai_minicex<1)
			{
				echo "<tr><td colspan=7 align=center><<< E M P T Y >>></td></tr>";
			}
			else
			{
				$no=1;
				$kelas = "ganjil";
				while ($data_minicex=mysqli_fetch_array($nilai_minicex))
				{
					$tanggal_isi = tanggal_indo($data_minicex[tgl_isi]);
					$tanggal_approval = tanggal_indo($data_minicex[tgl_approval]);
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_minicex[instansi]<br><i>Lokasi: $data_minicex[lokasi]</i><br><br><i>Nilai: $data_minicex[nilai_rata]</i></td>";
					echo "<td align=center>$data_minicex[no_ujian]</td>";
					echo "<td>$data_minicex[diagnosis]</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
					if ($data_minicex[status_approval]=='0') echo "<a href=\"approve_minicex.php?id=$data_minicex[id]\"><input type=\"button\" name=\"approve\".\"$data_minicex[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
					echo "</td>";
					echo "<td align=center>";
					if ($data_minicex[status_approval]=='0')
					{
						echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
						echo "<a href=\"edit_form_minicex.php?id=$data_minicex[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_minicex[id]\" value=\"EDIT\"></a><p>";
						echo "<a href=\"preview_form_minicex.php?id=$data_minicex[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_minicex[id]\" value=\"PREVIEW\"></a><p>";
						echo "<a href=\"hapus_form_minicex.php?id=$data_minicex[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_minicex[id]\" value=\"HAPUS\"></a>";
					}
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
						echo "<br><br><a href=\"view_form_minicex.php?id=$data_minicex[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_minicex[id]\" value=\"VIEW\"></a><p>";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table>";
			echo "<br><center><a href=\"tambah_nilai_minicex.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_minicex = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_minicex` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_minicex>0)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"cetak_nilai_minicex.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"CETAK\"></a>";
			echo "</center><br><br>";

			//Pengisian Formulir Penilaian Presensi / Kehadiran
			echo "<a id=\"presensi\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Pengisian Formulir Penilaian Presensi / Kehadiran</font></a><br><br>";
			$nilai_presensi = mysqli_query($con,"SELECT * FROM `kdk_nilai_presensi` WHERE `nim`='$_COOKIE[user]'");
			echo "<table id=\"freeze5\" style=\"width:100%\">";
			echo "<thead>
						<th style=\"width:5%\">No</th>
						<th style=\"width:15%\">Tanggal Pengisian</th>
						<th style=\"width:25%\">Instansi / Lokasi</th>
						<th style=\"width:15%\">Periode<br>(Mulai - Selesai)</th>
						<th style=\"width:25%\">Dokter Pembimbing</th>
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
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_presensi[dosen]'"));
					echo "<tr class=\"$kelas\">";
					echo "<td align=center>$no</td>";
					echo "<td>$tanggal_isi</td>";
					echo "<td>Instansi: $data_presensi[instansi]<br><i>Lokasi: $data_presensi[lokasi]</i><br><br><i>Nilai: $data_presensi[nilai_total]</i></td>";
					echo "<td align=center>$tanggal_mulai<br>s.d.<br>$tanggal_selesai</td>";
					echo "<td>$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])<br><br>";
					if ($data_presensi[status_approval]=='0') echo "<a href=\"approve_presensi.php?id=$data_presensi[id]\"><input type=\"button\" name=\"approve\".\"$data_presensi[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
					echo "</td>";
					echo "<td align=center>";
					if ($data_presensi[status_approval]=='0')
					{
						echo "<font style=\"color:red\">BELUM DISETUJUI</font><br><br>";
						echo "<a href=\"edit_form_presensi.php?id=$data_presensi[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data_presensi[id]\" value=\"EDIT\"></a><p>";
						echo "<a href=\"preview_form_presensi.php?id=$data_presensi[id]\"><input type=\"button\" class=\"submit2\" name=\"preview\".\"$data_presensi[id]\" value=\"PREVIEW\"></a><p>";
						echo "<a href=\"hapus_form_presensi.php?id=$data_presensi[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data_presensi[id]\" value=\"HAPUS\"></a>";
					}
					else
					{
						echo "<font style=\"color:green\">DISETUJUI</font><br>";
						echo "per tanggal<br>";
						echo "$tanggal_approval";
						echo "<br><br><a href=\"view_form_presensi.php?id=$data_presensi[id]\"><input type=\"button\" class=\"submit2\" name=\"view\".\"$data_presensi[id]\" value=\"VIEW\"></a><p>";
					}
					echo "</td>";
					echo "</tr>";
					$no++;
					if ($kelas=="ganjil") $kelas="genap";
					else $kelas="ganjil";
				}
			}
			echo "</table>";
			echo "<br><center><a href=\"tambah_nilai_presensi.php\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a>";
			$cek_approved_presensi = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `kdk_nilai_presensi` WHERE `nim`='$_COOKIE[user]' AND `status_approval`='1'"));
			if ($cek_approved_presensi>0)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"cetak_nilai_presensi.php\" target=\"_BLANK\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"CETAK\"></a>";

			echo "<br><br><br><a href=\"cetak_nilai_kdk.php\" target=\"_BLANK\"><input type=\"button\" id=\"cetak_nilai\" class=\"submit1\" name=\"cetak_nilai\" value=\"CETAK NILAI\"></a>";
			echo "</center><br><br>";

		}


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
		 $("#freeze5").freezeHeader();
  });
</script>
<!--</body></html>-->
</BODY>
</HTML>
