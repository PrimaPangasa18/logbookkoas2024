<?php

    include 'connect.php';
	include 'fungsi.php';
	

	//error_reporting(E_ALL ^ E_NOTICE);
   
		//error_reporting(E_ALL ^ E_NOTICE);

		$nim_mhsw = $_GET['nim'];
		$id_stase = $_GET['stase'];
		$stase_id = "stase_".$id_stase;
		$include_id = "include_".$id_stase;
		$target_id = "target_".$id_stase;

		$data_mhsw = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw'"));
		$data_stase = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'"));
		$nama_stase = mysqli_fetch_array(mysqli_query($conn,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$pdf = new Cezpdf_forandroid('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('fonts/Helvetica.afm');

		$delete_dummy_penyakit = mysqli_query($conn,"DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$nim_mhsw'");
		$delete_dummy_ketrampilan = mysqli_query($conn,"DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$nim_mhsw'");

		$filter_penyakit = "SELECT * FROM `jurnal_penyakit` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `status`='1'";
		$jurnal_penyakit = mysqli_query($conn,$filter_penyakit);
		while ($data=mysqli_fetch_array($jurnal_penyakit))
		{
			$insert_dummy = mysqli_query($conn,"INSERT INTO `jurnal_penyakit_dummy`
				(`id`, `nim`, `nama`,`angkatan`, `grup`,
					`hari`, `tanggal`, `stase`,
					`jam_awal`, `jam_akhir`, `kelas`,
					`lokasi`, `kegiatan`,
					`penyakit1`, `penyakit2`, `penyakit3`, `penyakit4`,
					`dosen`, `status`, `evaluasi`,`username`)
				VALUES
				('$data[id]', '$data[nim]','','$data[angkatan]', '$data[grup]',
					'$data[hari]', '$data[tanggal]', '$data[stase]',
					'$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]',
					'$data[lokasi]', '$data[kegiatan]',
					'$data[penyakit1]', '$data[penyakit2]', '$data[penyakit3]', '$data[penyakit4]',
					'$data[dosen]', '$data[status]', '$data[evaluasi]','$nim_mhsw')");
		}
		$filter_ketrampilan = "SELECT * FROM `jurnal_ketrampilan` WHERE `nim`='$data_mhsw[nim]' AND `stase`='$id_stase' AND `status`='1'";
		$jurnal_ketrampilan = mysqli_query($conn,$filter_ketrampilan);
		while ($data=mysqli_fetch_array($jurnal_ketrampilan))
		{
			$insert_dummy = mysqli_query($conn,"INSERT INTO `jurnal_ketrampilan_dummy`
				(`id`, `nim`, `nama`,`angkatan`, `grup`,
					`hari`, `tanggal`, `stase`,
					`jam_awal`, `jam_akhir`, `kelas`,
					`lokasi`, `kegiatan`,
					`ketrampilan1`, `ketrampilan2`, `ketrampilan3`, `ketrampilan4`,
					`dosen`, `status`, `evaluasi`,`username`)
				VALUES
				('$data[id]', '$data[nim]','','$data[angkatan]', '$data[grup]',
					'$data[hari]', '$data[tanggal]', '$data[stase]',
					'$data[jam_awal]', '$data[jam_akhir]', '$data[kelas]',
					'$data[lokasi]', '$data[kegiatan]',
					'$data[ketrampilan1]', '$data[ketrampilan2]', '$data[ketrampilan3]', '$data[ketrampilan4]',
					'$data[dosen]', '$data[status]', '$data[evaluasi]','$nim_mhsw')");
		}

		//-----------------------------------
		//Rekap Nilai
		//-----------------------------------
		//Grade Jurnal Penyakit
		$grade_penyakit = ketuntasan_penyakit($conn,$include_id,$target_id,$data_mhsw['nim'],$nim_mhsw);

		//Grade Jurnal Ketrampian
		$grade_ketrampilan = ketuntasan_ketrampilan($conn,$include_id,$target_id,$data_mhsw['nim'],$nim_mhsw);


		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Penyakit Dalam
		//--------------------------------------------------------------------
		if ($id_stase=="M091")
		{
			//Nilai Bagian
			//---------------------
			//Rekap Nilai Mini-Cex
			//---------------------
			//Nilai Rata Mini-Cex
			$daftar_minicex = mysqli_query($conn,"SELECT `id` FROM `ipd_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'");
			$jumlah_minicex = mysqli_num_rows($daftar_minicex);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `ipd_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if ($jumlah_minicex>0) $rata_minicex =  number_format($jum_nilai[0]/7,2);
			else $rata_minicex = 0.00;
			//---------------------

			//-------------------------------
			//Rekap Nilai Penyajian Kasus Besar
			//-------------------------------
			$kasus = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ipd_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_kasus = number_format($kasus['nilai_rata'],2);

			//-------------------------------
			//Rekap Nilai Ujian Akhir
			//-------------------------------
			$ujian = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ipd_nilai_ujian` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_ujian = number_format($ujian['nilai_rata'],2);

			//-------------------------------
			//Rekap Nilai Test
			//-------------------------------
			$mcq = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ipd_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='6' AND `status_approval`='1'"));
			$nilai_mcq = number_format($mcq[0],2);

			$nilai_bagian = number_format(0.2*$rata_minicex+0.2*$nilai_kasus+0.2*$nilai_mcq+0.4*$nilai_ujian,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Penyakit Dalam
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Neurologi
		//--------------------------------------------------------------------
		if ($id_stase=="M092")
		{
			//Nilai Bagian
			//-------------------------------
			//Rekap Nilai Penilaian Kasus CBD
			$kasus_cbd = mysqli_query($conn,"SELECT DISTINCT `kasus_ke` FROM `neuro_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'");
			$jumlah_nilai = 0;
			while ($data_cbd = mysqli_fetch_array($kasus_cbd))
			{
				$nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai_rata`) FROM `neuro_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `kasus_ke`='$data_cbd[kasus_ke]'"));
				$jumlah_nilai = $jumlah_nilai + $nilai[0];
			}
			$nilai_cbd = $jumlah_nilai/5;
			//Rekap Nilai Penilaian Journal Reading
			$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai_total`) FROM `neuro_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_jurnal = $data_nilai_jurnal[0];
			//Rekap Nilai Penilaian Ujian SPV
			$data_nilai_spv = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `neuro_nilai_spv` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_spv = $data_nilai_spv[0];
			//Rekap Nilai Ujian Mini-CEX
			$data_nilai_minicex = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai_rata`) FROM `neuro_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_minicex = $data_nilai_minicex[0];
			//Rekap Nilai Test
			$nilai_osce = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `neuro_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='5'"));
			$nilai_mcq = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `neuro_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='6'"));
			$nilai_test = ($nilai_osce[0]+$nilai_mcq[0])/2;
			//Total Nilai
			$nilai_bagian = number_format(0.1*$nilai_cbd+0.1*$nilai_jurnal+0.15*$nilai_spv+0.15*$nilai_minicex+0.5*$nilai_test,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Neurologi
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Psikiatri
		//--------------------------------------------------------------------
		if ($id_stase=="M093")
		{
			//Nilai Bagian
			//-------------------------------
			//Rekap Nilai Penilaian Journal Reading
			$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `psikiatri_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_jurnal = $data_nilai_jurnal['nilai_rata'];
			//Rekap Nilai Penilaian CBD
			$data_nilai_cbd = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `psikiatri_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_cbd = $data_nilai_cbd['nilai_rata'];
			//Rekap Nilai Penilaian Ujian MINI-CEX
			$data_nilai_minicex = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `psikiatri_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_minicex = $data_nilai_minicex['nilai_rata'];
			//Rekap Nilai Ujian OSCE
			$data_nilai_osce = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `psikiatri_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_osce = $data_nilai_osce['nilai_rata'];
			//Rekap Nilai Test
			$nilai_pretest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `psikiatri_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='1'"));
			$nilai_posttest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `psikiatri_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='2'"));
			$nilai_test = ($nilai_pretest[0]+$nilai_posttest[0])/2;
			//Total Nilai
			$nilai_bagian = number_format(0.25*$nilai_jurnal+0.125*$nilai_cbd+0.125*$nilai_minicex+0.25*$nilai_osce+0.25*$nilai_test,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Psikiatri
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) IKFR
		//--------------------------------------------------------------------
		if ($id_stase=="M094")
		{
			//Nilai Bagian
			//-------------------------------
			//-------------------------------
			//Rekap Nilai Penilaian Diskusi Kasus
			//-------------------------------
			$data_nilai_kasus = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ikfr_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_kasus = $data_nilai_kasus['nilai_rata'];

			//-------------------------------
			//Rekap Nilai Penilaian Ujian Mini-Cex
			//-------------------------------
			$data_nilai_minicex = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ikfr_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_minicex = $data_nilai_minicex['nilai_rata'];

			//-------------------------------
			//Rekap Nilai Test
			//-------------------------------
			$pretest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ikfr_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='1' AND `status_approval`='1'"));
			$posttest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ikfr_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='2' AND `status_approval`='1'"));
			$sikap = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ikfr_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
			$osce = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ikfr_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
			$nilai_pretest = $pretest[0];
			$nilai_posttest = $posttest[0];
			$nilai_sikap = $sikap[0];
			$nilai_osce = $osce[0];

			//Total Nilai
			$nilai_bagian = number_format(0.2*$nilai_kasus+0.2*$nilai_minicex+0.2*$nilai_osce+0.1*$nilai_pretest+0.2*$nilai_posttest+0.1*$nilai_sikap,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) IKFR
		//--------------------------------------------------------------------


		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) IKM-KP
		//--------------------------------------------------------------------
		if ($id_stase=="M095")
		{
			//Nilai Bagian
			//-------------------------------
			//Rekap Nilai Penilaian Kegiatan di PKBI
			$data_nilai_pkbi = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_total` FROM `ikmkp_nilai_pkbi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_pkbi = $data_nilai_pkbi['nilai_total'];
			//Rekap Nilai Penilaian Kegiatan di P2UKM Mlonggo
			//-------------------------------
			//Kegiatan Evaluasi Manajemen Puskesmas
			$data_nilai_p2ukm_emp = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai_total`) FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$data_mhsw[nim]' AND `jenis_penilaian`='Evaluasi Manajemen Puskesmas' AND `status_approval`='1'"));
			$nilai_p2ukm_emp = $data_nilai_p2ukm_emp[0];
			//Kegiatan Evaluasi Program Kesehatan
			$data_nilai_p2ukm_epk = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai_total`) FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$data_mhsw[nim]' AND `jenis_penilaian`='Evaluasi Program Kesehatan' AND `status_approval`='1'"));
			$nilai_p2ukm_epk = $data_nilai_p2ukm_epk[0];
			//Kegiatan Diagnosis Komunitas
			$data_nilai_p2ukm_dk = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai_total`) FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$data_mhsw[nim]' AND `jenis_penilaian`='Diagnosis Komunitas' AND `status_approval`='1'"));
			$nilai_p2ukm_dk = $data_nilai_p2ukm_dk[0];
			//Nilai Rata P2UKM
			$nilai_p2ukm = ($nilai_p2ukm_emp+$nilai_p2ukm_epk+$nilai_p2ukm_dk)/3;
			//Rekap Nilai Penugasan dan Test
			//-------------------------------
			//Nilai Post-test (jenis_test=2)
			$nilai_terbaik = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ikmkp_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='2'"));
			$nilai_test = $nilai_terbaik[0];
			//Rekap Nilai Ujian Komprehensip
			//-------------------------------
			$data_nilai_komprehensip = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_total` FROM `ikmkp_nilai_komprehensip` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_komprehensip = $data_nilai_komprehensip['nilai_total'];
			//Total Nilai
			$nilai_bagian = number_format(($nilai_pkbi+2*$nilai_p2ukm+$nilai_test+4*$nilai_komprehensip)/8,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) IKM-KP
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Jantung dan Pembuluh Darah
		//--------------------------------------------------------------------
		if ($id_stase=="M096")
		{
			//Nilai Bagian
			//-------------------------------

			//Total Nilai
			$nilai_bagian = "0.00";
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Jantung dan Pembuluh Darah
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Bedah
		//--------------------------------------------------------------------
		if ($id_stase=="M101")
		{
			//Nilai Bagian
			//-------------------------------
			//-------------------------------
			//Rekap Nilai Mentoring
			//-------------------------------
			//Nilai Rata Mentoring
			$data_mentor1 = mysqli_query($conn,"SELECT * FROM `bedah_nilai_mentor` WHERE `nim`='$data_mhsw[nim]' AND `bulan_ke`='1' AND `status_approval`='1'");
			$jum_mentor1 = mysqli_num_rows($data_mentor1);
			$mentor1 = mysqli_fetch_array($data_mentor1);
			if ($jum_mentor1>0) $nilai_mentor1 = number_format($mentor1['nilai_rata'],2);
			else $nilai_mentor1 = "0.00";
			$data_mentor2 = mysqli_query($conn,"SELECT * FROM `bedah_nilai_mentor` WHERE `nim`='$data_mhsw[nim]' AND `bulan_ke`='2' AND `status_approval`='1'");
			$jum_mentor2 = mysqli_num_rows($data_mentor2);
			$mentor2 = mysqli_fetch_array($data_mentor2);
			if ($jum_mentor2>0) $nilai_mentor2 = number_format($mentor2['nilai_rata'],2);
			else $nilai_mentor2 = "0.00";
			//---------------------

			//-------------------------------
			//Rekap Nilai Test
			//-------------------------------
			$pre_test = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='1' AND `status_approval`='1'"));
			$post_test = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='2' AND `status_approval`='1'"));
			$osce = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
			$skill_lab = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='15' AND `status_approval`='1'"));

			$nilai_pre_test = number_format($pre_test[0],2);
			$nilai_post_test = number_format($post_test[0],2);
			$nilai_osce = number_format($osce[0],2);
			$nilai_skill_lab = number_format($skill_lab[0],2);

			$nilai_total = number_format(0.25*$nilai_mentor1+0.25*$nilai_mentor2+0.05*$nilai_pre_test+0.1*$nilai_post_test+0.25*$nilai_osce+0.1*$nilai_skill_lab,2);
			$nilai_total = number_format($nilai_total,2);

			$nilai_bagian = number_format($nilai_total,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Bedah
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Anestesi
		//--------------------------------------------------------------------
		if ($id_stase=="M102")
		{
			//Nilai Bagian
			//-------------------------------
			//-------------------------------
			//Rekap Nilai DOPS
			//-------------------------------
			$data_nilai_dops = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `anestesi_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_dops = $data_nilai_dops['nilai_rata'];
			$nilai_dops = number_format($nilai_dops,2);

			//-------------------------------
			//Rekap Nilai Ujian OSCE
			//-------------------------------
			$data_nilai_osce = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_total` FROM `anestesi_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_osce = $data_nilai_osce['nilai_total'];
			$nilai_osce = number_format($nilai_osce,2);

			//-------------------------------
			//Rekap Nilai Test
			//-------------------------------
			$data_nilai_pretest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='1'"));
			$data_nilai_posttest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='2'"));
			$data_nilai_sikap = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='3'"));
			$data_nilai_tugas = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='4'"));
			$data_nilai_mcq = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `anestesi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1' AND `jenis_test`='6'"));

			$nilai_pretest = number_format($data_nilai_pretest[0],2);
			$nilai_posttest = number_format($data_nilai_posttest[0],2);
			$nilai_sikap = number_format($data_nilai_sikap[0],2);
			$nilai_tugas = number_format($data_nilai_tugas[0],2);
			$nilai_mcq = number_format($data_nilai_mcq[0],2);

			//Total Nilai
			$nilai_bagian = number_format(0.1*$nilai_dops+0.25*$nilai_osce+0.1*$nilai_pretest+0.1*$nilai_posttest+0.15*$nilai_mcq+0.1*$nilai_tugas+0.2*$nilai_sikap,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Anestesi
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Radiologi
		//--------------------------------------------------------------------
		if ($id_stase=="M103")
		{
			//Nilai Bagian
			//-------------------------------
			//Rekap Nilai Penilaian CBD - Radiodiagnostik
			$data_nilai_cbd1 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `radiologi_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Radiodiagnostik' AND `status_approval`='1'"));
			$nilai_cbd1 = $data_nilai_cbd1['nilai_rata'];
			//Rekap Nilai Penilaian CBD - Radioterapi
			$data_nilai_cbd2 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `radiologi_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Radioterapi' AND `status_approval`='1'"));
			$nilai_cbd2 = $data_nilai_cbd2['nilai_rata'];
			//Rekap Nilai Penilaian Journal Reading
			$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `radiologi_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_jurnal = $data_nilai_jurnal['nilai_rata'];
			//Rekap Nilai Test MCQ
			$data_nilai_mcq = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `radiologi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='6' AND `status_approval`='1'"));
			$nilai_mcq = $data_nilai_mcq[0];
			//Rekap Nilai Test OSCE
			$data_nilai_osce = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `radiologi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
			$nilai_osce = $data_nilai_osce[0];
			//Rekap Nilai Sikap dan Perilaku
			$data_nilai_sikap = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `radiologi_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
			$nilai_sikap = $data_nilai_sikap[0];
			//Total Nilai
			$nilai_bagian = number_format(0.10*$nilai_cbd1+0.10*$nilai_cbd2+0.10*$nilai_jurnal+0.30*$nilai_mcq+0.35*$nilai_osce+0.05*$nilai_sikap,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Radiologi
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Kesehatan Mata
		//--------------------------------------------------------------------
		if ($id_stase=="M104")
		{
			//Nilai Bagian
			//-------------------------------
			//Rekap Nilai Presentasi Kasus Besar
			$data_nilai_presentasi = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_total` FROM `mata_nilai_presentasi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_presentasi = $data_nilai_presentasi['nilai_total'];
			//Rekap Nilai Journal Reading
			$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_total` FROM `mata_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_jurnal = $data_nilai_jurnal['nilai_total'];
			//Rekap Nilai Penyanggah
			$jml_penyanggah = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `mata_nilai_penyanggah` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$jml_nilai_penyanggah = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai`) FROM `mata_nilai_penyanggah` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if ($jml_penyanggah>0) $nilai_penyanggah = $jml_nilai_penyanggah[0]/$jml_penyanggah;
			else $nilai_penyanggah = 0;
			//Rekap Nilai Ujian Mini-Cex
			$data_nilai_minicex = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `mata_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_minicex = $data_nilai_minicex['nilai_rata'];
			//Rekap Nilai Test MCQ
			$data_nilai_mcq = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `mata_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='6'"));
			$nilai_mcq = $data_nilai_mcq[0];
			//Rekap Nilai Test OSCE
			$data_nilai_osce = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `mata_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5'"));
			$nilai_osce = $data_nilai_osce[0];
			//Total Nilai
			$nilai_bagian = number_format(0.15*$nilai_presentasi+0.05*$nilai_penyanggah+0.10*$nilai_jurnal+0.20*$nilai_minicex+0.25*$nilai_mcq+0.25*$nilai_osce,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Kesehatan Mata
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Kesehatan THT-KL
		//--------------------------------------------------------------------
		if ($id_stase=="M105")
		{
			//Penilaian Bagian
			//Rekap Nilai Penilaian Presentasi Kasus
			$data_nilai_presentasi = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `thtkl_nilai_presentasi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_presentasi = $data_nilai_presentasi['nilai_rata'];
			//Rekap Nilai Penilaian Responsi Kasus Kecil
			$data_nilai_responsi = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `thtkl_nilai_responsi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$jml_data_responsi = mysqli_num_rows(mysqli_query($conn,"SELECT `nilai_rata` FROM `thtkl_nilai_responsi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if ($jml_data_responsi==0) $nilai_responsi = 0;
			else $nilai_responsi = $data_nilai_responsi[0]/$jml_data_responsi;
			//Rekap Nilai Penilaian Journal Reading
			$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `thtkl_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$jml_data_jurnal = mysqli_num_rows(mysqli_query($conn,"SELECT `nilai_rata` FROM `thtkl_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if ($jml_data_jurnal==0) $nilai_jurnal = 0;
			else $nilai_jurnal = $data_nilai_jurnal[0]/$jml_data_jurnal;
			//Rekap Nilai Test
			$nilai_pretest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `thtkl_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='1' AND `status_approval`='1'"));
			$nilai_posttest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `thtkl_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='2' AND `status_approval`='1'"));
			$nilai_sikap = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `thtkl_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
			$nilai_tugas = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `thtkl_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='4' AND `status_approval`='1'"));
			$nilai_test = ($nilai_pretest[0]+$nilai_posttest[0]+$nilai_sikap[0]+$nilai_tugas[0])/4;
			//Rekap Nilai Ujian OSCE
			$nilai_osce_laringfaring = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `jenis_ujian`='Laring Faring' AND `status_approval`='1'"));
			$nilai_osce_otologi = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `jenis_ujian`='Otologi' AND `status_approval`='1'"));
			$nilai_osce_rinologi = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `jenis_ujian`='Rinologi' AND `status_approval`='1'"));
			$nilai_osce_onkologi = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `jenis_ujian`='Onkologi' AND `status_approval`='1'"));
			$nilai_osce_alergiimunologi = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `thtkl_nilai_osce` WHERE `nim`='$data_mhsw[nim]' AND `jenis_ujian`='Alergi Imunologi' AND `status_approval`='1'"));
			$nilai_osce = ($nilai_osce_laringfaring[0]+$nilai_osce_otologi[0]+$nilai_osce_rinologi[0]+$nilai_osce_onkologi[0]+$nilai_osce_alergiimunologi[0])/5;
			//Total Nilai
			$nilai_bagian = number_format(0.0476*$nilai_presentasi+0.0476*$nilai_responsi+0.0476*$nilai_jurnal+0.1905*$nilai_test+0.6667*$nilai_osce,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Kesehatan THT-KL
		//--------------------------------------------------------------------


		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) IKGM
		//--------------------------------------------------------------------
		if ($id_stase=="M106")
		{
			//Nilai Bagian
			//-------------------------------
			//-------------------------------
			//Rekap Nilai Penilaian Laporan Kasus
			//-------------------------------
			$data_nilai_kasus = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_total` FROM `ikgm_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_kasus = $data_nilai_kasus['nilai_total'];
			$nilai_kasus = number_format($nilai_kasus,2);

			//-------------------------------
			//Rekap Nilai Penilaian Journal Reading
			//-------------------------------
			$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_total` FROM `ikgm_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_jurnal = $data_nilai_jurnal['nilai_total'];
			$nilai_jurnal = number_format($nilai_jurnal,2);

			//-------------------------------
			//Rekap Nilai Penilaian Responsi Kasus Kecil
			//-------------------------------
			$data_nilai_responsi = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `ikgm_nilai_responsi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$jml_data_responsi = mysqli_num_rows(mysqli_query($conn,"SELECT `nilai_rata` FROM `ikgm_nilai_responsi` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if ($jml_data_responsi==0) $nilai_responsi = 0;
			else $nilai_responsi = $data_nilai_responsi[0]/$jml_data_responsi;
			$nilai_responsi = number_format($nilai_responsi,2);

			//-------------------------------
			//Rekap Nilai Test
			//-------------------------------
			$nilaisikap = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ikgm_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
			$nilaiosca = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ikgm_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
			$nilai_sikap = number_format($nilaisikap[0],2);
			$nilai_osca = number_format($nilaiosca[0],2);

			//Total Nilai
			$nilai_bagian = number_format(0.125*$nilai_kasus+0.125*$nilai_jurnal+0.3*$nilai_responsi+0.3*$nilai_osca+0.15*$nilai_sikap,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) IKGM
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan
		//--------------------------------------------------------------------
		if ($id_stase=="M111")
		{
			//Nilai Bagian
			//---------------------
			//Rekap Nilai Mini-Cex
			//---------------------
			//Nilai Rata Mini-Cex
			$data_minicex = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `obsgyn_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if (!empty($data_minicex)) $nilai_minicex = $data_minicex['nilai_rata'];
			else $nilai_minicex = 0;
			$nilai_minicex = number_format($nilai_minicex,2);
			//---------------------

			//-------------------------------
			//Rekap Nilai CBD
			//-------------------------------
			$daftar_cbd = mysqli_query($conn,"SELECT `id` FROM `obsgyn_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'");
			$jumlah_cbd = mysqli_num_rows($daftar_cbd);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `obsgyn_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if ($jumlah_cbd>0) $rata_cbd =  $jum_nilai[0]/4;
			else $rata_cbd = 0;
			$rata_cbd = number_format($rata_cbd,2);

			//---------------------
			//Nilai Journal Reading
			$data_jurnal = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `obsgyn_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if (!empty($data_jurnal)) $nilai_jurnal = $data_jurnal['nilai_rata'];
			else $nilai_jurnal = 0;
			$nilai_jurnal = number_format($nilai_jurnal,2);
			//---------------------

			//-------------------------------
			//Rekap Nilai Test
			//-------------------------------
			$mcq = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `obsgyn_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='6' AND `status_approval`='1'"));
			$nilai_mcq = number_format($mcq[0],2);
			$dops_osce = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `obsgyn_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='13' AND `status_approval`='1'"));
			$nilai_dops_osce = number_format($dops_osce[0],2);
			$minipat = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `obsgyn_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='14' AND `status_approval`='1'"));
			$nilai_minipat = number_format($minipat[0],2);

			$nilai_bagian = number_format(0.2*$nilai_minicex+0.15*$rata_cbd+0.05*$nilai_jurnal+0.3*$nilai_dops_osce+0.2*$nilai_mcq+0.1*$nilai_minipat,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Forensik
		//--------------------------------------------------------------------
		if ($id_stase=="M112")
		{
			//Nilai Bagian
			//-------------------------------
			//-------------------------------
			//Rekap Nilai Penilaian Visum Bayangan
			//-------------------------------
			$data_nilai_visum1 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='1' AND `status_approval`='1'"));
			$data_nilai_visum2 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='2' AND `status_approval`='1'"));
			$data_nilai_visum3 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='3' AND `status_approval`='1'"));
			$data_nilai_visum4 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='4' AND `status_approval`='1'"));
			$nilai_visum1 = number_format($data_nilai_visum1['nilai_rata'],2);
			$nilai_visum2 = number_format($data_nilai_visum2['nilai_rata'],2);
			$nilai_visum3 = number_format($data_nilai_visum3['nilai_rata'],2);
			$nilai_visum4 = number_format($data_nilai_visum4['nilai_rata'],2);
			$nilai_visum = number_format(($nilai_visum1+$nilai_visum2+$nilai_visum3+$nilai_visum4)/4,2);

			//-------------------------------
			//Rekap Nilai Penilaian Kegiatan Jaga
			//-------------------------------
			$data_nilai_jaga1 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='1' AND `status_approval`='1'"));
			$data_nilai_jaga2 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='2' AND `status_approval`='1'"));
			$data_nilai_jaga3 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='3' AND `status_approval`='1'"));
			$data_nilai_jaga4 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='4' AND `status_approval`='1'"));
			$nilai_jaga1 = number_format($data_nilai_jaga1['nilai_rata'],2);
			$nilai_jaga2 = number_format($data_nilai_jaga2['nilai_rata'],2);
			$nilai_jaga3 = number_format($data_nilai_jaga3['nilai_rata'],2);
			$nilai_jaga4 = number_format($data_nilai_jaga4['nilai_rata'],2);
			$nilai_jaga = number_format(($nilai_jaga1+$nilai_jaga2+$nilai_jaga3+$nilai_jaga4)/4,2);

			//-------------------------------
			//Rekap Nilai Penilaian Substase
			//-------------------------------
			$data_nilai_substase1 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='1' AND `status_approval`='1'"));
			$data_nilai_substase2 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='2' AND `status_approval`='1'"));
			$data_nilai_substase3 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='3' AND `status_approval`='1'"));
			$data_nilai_substase4 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$data_mhsw[nim]' AND `dosen_ke`='4' AND `status_approval`='1'"));
			$nilai_substase1 = number_format($data_nilai_substase1['nilai_rata'],2);
			$nilai_substase2 = number_format($data_nilai_substase2['nilai_rata'],2);
			$nilai_substase3 = number_format($data_nilai_substase3['nilai_rata'],2);
			$nilai_substase4 = number_format($data_nilai_substase4['nilai_rata'],2);
			$nilai_substase = number_format(($nilai_substase1+$nilai_substase2+$nilai_substase3+$nilai_substase4)/4,2);

			//-------------------------------
			//Rekap Nilai Penilaian Referat
			//-------------------------------
			$data_nilai_referat = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `forensik_nilai_referat` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			$nilai_referat = $data_nilai_referat['nilai_rata'];
			$nilai_referat = number_format($nilai_referat,2);

			//-------------------------------
			//Rekap Nilai Test
			//-------------------------------
			$pretest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='1' AND `status_approval`='1'"));
			$sikap = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
			$kompre1 = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='7' AND `status_approval`='1'"));
			$kompre2 = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='8' AND `status_approval`='1'"));
			$kompre3 = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='9' AND `status_approval`='1'"));
			$kompre4 = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='10' AND `status_approval`='1'"));
			$kompre5 = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='11' AND `status_approval`='1'"));
			$nilai_pretest = number_format($pretest[0],2);
			$nilai_sikap = number_format($sikap[0],2);
			$nilai_kompre1 = number_format($kompre1[0],2);
			$nilai_kompre2 = number_format($kompre2[0],2);
			$nilai_kompre3 = number_format($kompre3[0],2);
			$nilai_kompre4 = number_format($kompre4[0],2);
			$nilai_kompre5 = number_format($kompre5[0],2);
			$nilai_kompre = number_format(($nilai_kompre1+$nilai_kompre2+$nilai_kompre3+$nilai_kompre4+$nilai_kompre5)/5,2);

			$nilai_bagian = number_format(0.09*$nilai_visum+0.06*$nilai_jaga+0.12*$nilai_substase+0.24*$nilai_referat+0.1*$nilai_pretest+0.03*$nilai_sikap+0.36*$nilai_kompre,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Forensik
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Kesehatan Anak
		//--------------------------------------------------------------------
		if ($id_stase=="M113")
		{
			//Nilai Bagian
			//---------------------
			//-------------------------------
			//Rekap Nilai Mini-CEX

			//Rata Nilai Mini-CEX Infeksi
			$minicex_infeksi1 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='1' AND `status_approval`='1'"));
			$minicex_infeksi2 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='2' AND `status_approval`='1'"));
			$minicex_infeksi3 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='3' AND `status_approval`='1'"));
			$minicex_infeksi4 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='4' AND `status_approval`='1'"));
			if (!empty($minicex_infeksi1)) $nilai_minicex_infeksi1 = $minicex_infeksi1['nilai_rata'];
			else $nilai_minicex_infeksi1 = 0;
			$nilai_minicex_infeksi1 = number_format($nilai_minicex_infeksi1,2);
			if (!empty($minicex_infeksi2)) $nilai_minicex_infeksi2 = $minicex_infeksi2['nilai_rata'];
			else $nilai_minicex_infeksi2 = 0;
			$nilai_minicex_infeksi2 = number_format($nilai_minicex_infeksi2,2);
			if (!empty($minicex_infeksi3)) $nilai_minicex_infeksi3 = $minicex_infeksi3['nilai_rata'];
			else $nilai_minicex_infeksi3 = 0;
			$nilai_minicex_infeksi3 = number_format($nilai_minicex_infeksi3,2);
			if (!empty($minicex_infeksi4)) $nilai_minicex_infeksi4 = $minicex_infeksi4['nilai_rata'];
			else $nilai_minicex_infeksi4 = 0;
			$nilai_minicex_infeksi4 = number_format($nilai_minicex_infeksi4,2);

			$nilai_rata_minicex_infeksi = ($nilai_minicex_infeksi1 + $nilai_minicex_infeksi2 + $nilai_minicex_infeksi3 + $nilai_minicex_infeksi4)/4;
			$nilai_rata_minicex_infeksi = number_format($nilai_rata_minicex_infeksi,2);

			//Rata Nilai Mini-CEX Non-Infeksi
			$minicex_noninfeksi1 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='5' AND `status_approval`='1'"));
			$minicex_noninfeksi2 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='6' AND `status_approval`='1'"));
			$minicex_noninfeksi3 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='7' AND `status_approval`='1'"));
			$minicex_noninfeksi4 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='8' AND `status_approval`='1'"));
			$minicex_noninfeksi5 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='9' AND `status_approval`='1'"));
			$minicex_noninfeksi6 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='10' AND `status_approval`='1'"));
			$minicex_noninfeksi7 = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='11' AND `status_approval`='1'"));
			if (!empty($minicex_noninfeksi1)) $nilai_minicex_noninfeksi1 = $minicex_noninfeksi1['nilai_rata'];
			else $nilai_minicex_noninfeksi1 = 0;
			$nilai_minicex_noninfeksi1 = number_format($nilai_minicex_noninfeksi1,2);
			if (!empty($minicex_noninfeksi2)) $nilai_minicex_noninfeksi2 = $minicex_noninfeksi2['nilai_rata'];
			else $nilai_minicex_noninfeksi2 = 0;
			$nilai_minicex_noninfeksi2 = number_format($nilai_minicex_noninfeksi2,2);
			if (!empty($minicex_noninfeksi3)) $nilai_minicex_noninfeksi3 = $minicex_noninfeksi3['nilai_rata'];
			else $nilai_minicex_noninfeksi3 = 0;
			$nilai_minicex_noninfeksi3 = number_format($nilai_minicex_noninfeksi3,2);
			if (!empty($minicex_noninfeksi4)) $nilai_minicex_noninfeksi4 = $minicex_noninfeksi4['nilai_rata'];
			else $nilai_minicex_noninfeksi4 = 0;
			$nilai_minicex_noninfeksi4 = number_format($nilai_minicex_noninfeksi4,2);
			if (!empty($minicex_noninfeksi5)) $nilai_minicex_noninfeksi5 = $minicex_noninfeksi5['nilai_rata'];
			else $nilai_minicex_noninfeksi5 = 0;
			$nilai_minicex_noninfeksi5 = number_format($nilai_minicex_noninfeksi5,2);
			if (!empty($minicex_noninfeksi6)) $nilai_minicex_noninfeksi6 = $minicex_noninfeksi6['nilai_rata'];
			else $nilai_minicex_noninfeksi6 = 0;
			$nilai_minicex_noninfeksi6 = number_format($nilai_minicex_noninfeksi6,2);
			if (!empty($minicex_noninfeksi7)) $nilai_minicex_noninfeksi7 = $minicex_noninfeksi7['nilai_rata'];
			else $nilai_minicex_noninfeksi7 = 0;
			$nilai_minicex_noninfeksi7 = number_format($nilai_minicex_noninfeksi7,2);

			$nilai_rata_minicex_noninfeksi = ($nilai_minicex_noninfeksi1 + $nilai_minicex_noninfeksi2 + $nilai_minicex_noninfeksi3 + $nilai_minicex_noninfeksi4 + $nilai_minicex_noninfeksi5 + $nilai_minicex_noninfeksi6 + $nilai_minicex_noninfeksi7)/7;
			$nilai_rata_minicex_noninfeksi = number_format($nilai_rata_minicex_noninfeksi,2);

			//Rata Nilai Mini-CEX ERIA
			$minicex_eria = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='12' AND `status_approval`='1'"));
			if (!empty($minicex_eria)) $nilai_minicex_eria = $minicex_eria['nilai_rata'];
			else $nilai_minicex_eria = 0;
			$nilai_minicex_eria = number_format($nilai_minicex_eria,2);

			//Rata Nilai Mini-CEX Perinatologi
			$minicex_perinatologi = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='13' AND `status_approval`='1'"));
			if (!empty($minicex_perinatologi)) $nilai_minicex_perinatologi = $minicex_perinatologi['nilai_rata'];
			else $nilai_minicex_perinatologi = 0;
			$nilai_minicex_perinatologi = number_format($nilai_minicex_perinatologi,2);

			//Rata Nilai Mini-CEX RS Jejaring
			$minicex_jejaring = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `evaluasi`='14' AND `status_approval`='1'"));
			if (!empty($minicex_jejaring)) $nilai_minicex_jejaring = $minicex_jejaring['nilai_rata'];
			else $nilai_minicex_jejaring = 0;
			$nilai_minicex_jejaring = number_format($nilai_minicex_jejaring,2);

			//End of Rekap Nilai Mini-CEX
			//-------------------------------

			//-------------------------------
			//Rekap Nilai Penilaian DOPS
			//-------------------------------
			$data_nilai_dops = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if (!empty($data_nilai_dops)) $nilai_dops = $data_nilai_dops['nilai_rata'];
			else $nilai_dops = 0;
			$nilai_dops = number_format($nilai_dops,2);

			//-------------------------------
			//Rekap Nilai Penilaian CBD
			//-------------------------------
			$data_nilai_cbd = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if (!empty($data_nilai_cbd)) $nilai_cbd = $data_nilai_cbd['nilai_rata'];
			else $nilai_cbd = 0;
			$nilai_cbd = number_format($nilai_cbd,2);

			//-------------------------------
			//Rekap Nilai Penilaian Kasus Besar
			//-------------------------------
			$data_nilai_kasus = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if (!empty($data_nilai_kasus)) $nilai_kasus = $data_nilai_kasus['nilai_rata'];
			else $nilai_kasus = 0;
			$nilai_kasus = number_format($nilai_kasus,2);

			//-------------------------------
			//Rekap Nilai Penilaian Journal Reading
			//-------------------------------
			$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_jurnal` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if (!empty($data_nilai_jurnal)) $nilai_jurnal = $data_nilai_jurnal['nilai_rata'];
			else $nilai_jurnal = 0;
			$nilai_jurnal = number_format($nilai_jurnal,2);

			//-------------------------------
			//Rekap Nilai Penilaian Mini-PAT
			//-------------------------------
			$data_nilai_minipat = mysqli_fetch_array(mysqli_query($conn,"SELECT `nilai_rata` FROM `ika_nilai_minipat` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if (!empty($data_nilai_minipat)) $nilai_minipat = $data_nilai_minipat['nilai_rata'];
			else $nilai_minipat = 0;
			$nilai_minipat = number_format($nilai_minipat,2);

			//-------------------------------
			//Rekap Nilai Penilaian Ujian Akhir
			//-------------------------------
			$data_nilai_ujian = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai_rata`) FROM `ika_nilai_ujian` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if (!empty($data_nilai_ujian)) $nilai_ujian = $data_nilai_ujian[0];
			else $nilai_ujian = 0;
			$nilai_ujian = number_format($nilai_ujian,2);


			//-------------------------------
			//Rekap Nilai Test
			//-------------------------------
			$pretest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ika_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='1' AND `status_approval`='1'"));
			$posttest = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ika_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='2' AND `status_approval`='1'"));
			$osce = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `ika_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
			$nilai_pretest = number_format($pretest[0],2);
			$nilai_posttest = number_format($posttest[0],2);
			$nilai_osce = number_format($osce[0],2);

			$nilai_bagian = number_format(0.025*$nilai_pretest+0.075*$nilai_posttest+0.02*$nilai_rata_minicex_infeksi+0.02*$nilai_rata_minicex_noninfeksi+0.01*$nilai_minicex_eria+0.01*$nilai_minicex_perinatologi+0.02*$nilai_minicex_jejaring+0.02*$nilai_cbd+0.1*$nilai_dops+0.1*$nilai_kasus+0.1*$nilai_jurnal+0.1*$nilai_minipat+0.1*$nilai_osce+0.3*$nilai_ujian,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Kesehatan Anak
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin
		//--------------------------------------------------------------------
		if ($id_stase=="M114")
		{
			//Nilai Bagian
			//-------------------------------
			//---------------------
			//Rekap Nilai CBD
			//---------------------
			//Nilai Rata CBD
			$daftar_cbd = mysqli_query($conn,"SELECT * FROM `kulit_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'");
			$jumlah_cbd = mysqli_num_rows($daftar_cbd);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kulit_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if ($jumlah_cbd>0) $total_cbd =  $jum_nilai[0]/$jumlah_cbd;
			else $total_cbd = 0;
			//---------------------

			//-------------------------------
			//Rekap Nilai Test
			//-------------------------------
			$sikap = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `kulit_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='3' AND `status_approval`='1'"));
			$osce = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `kulit_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='5' AND `status_approval`='1'"));
			$ujian = mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(`nilai`) FROM `kulit_nilai_test` WHERE `nim`='$data_mhsw[nim]' AND `jenis_test`='12' AND `status_approval`='1'"));
			$nilai_sikap = number_format($sikap[0],2);
			$nilai_osce = number_format($osce[0],2);
			$nilai_ujian = number_format($ujian[0],2);

			$nilai_rata = number_format(($total_cbd+$nilai_osce+$nilai_ujian)/3,2);
			if ($nilai_sikap>=70) $nilai_total = $nilai_rata;
			if ($nilai_sikap<70 AND $nilai_sikap>=60) $nilai_total = 0.8*$nilai_rata;
			if ($nilai_sikap<60) $nilai_total = 0.5*$nilai_rata;

			$nilai_bagian = number_format($nilai_total,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin
		//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		//Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Komprehensip dan Kedokteran Keluarga (KDK)
		//--------------------------------------------------------------------
		if ($id_stase=="M121")
		{
			//Nilai Bagian
			//-------------------------------
			//A. Nilai Komprehensip
			//-------------------------------
			//Nilai Rata Laporan
			$laporan = mysqli_query($conn,"SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'");
			$jumlah_laporan = mysqli_num_rows($laporan);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata_ind`), SUM(`nilai_rata_kelp`)
				FROM `kompre_nilai_laporan` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			if ($jumlah_laporan>0) $total_laporan_ind_puskesmas =  $jum_nilai[0]/$jumlah_laporan;
			else $total_laporan_ind_puskesmas = 0;
			if ($jumlah_laporan>0) $total_laporan_kelp_puskesmas =  $jum_nilai[1]/$jumlah_laporan;
			else $total_laporan_kelp_puskesmas = 0;

			//---------------------
			//Rekap Nilai Rumah Sakit
			//---------------------
			//Nilai Rata laporan
			$laporan = mysqli_query($conn,"SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'");
			$jumlah_laporan = mysqli_num_rows($laporan);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata_ind`), SUM(`nilai_rata_kelp`)
				FROM `kompre_nilai_laporan` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'"));
			if ($jumlah_laporan>0) $total_laporan_ind_rumkit =  $jum_nilai[0]/$jumlah_laporan;
			else $total_laporan_ind_rumkit = 0;
			if ($jumlah_laporan>0) $total_laporan_kelp_rumkit =  $jum_nilai[1]/$jumlah_laporan;
			else $total_laporan_kelp_rumkit = 0;

			$rata_laporan_puskesmas = 0.5*$total_laporan_ind_puskesmas + 0.5*$total_laporan_kelp_puskesmas;
			$rata_laporan_rumkit = 0.5*$total_laporan_ind_rumkit + 0.5*$total_laporan_kelp_rumkit;
			$rata_nilai_laporan = 0.5*$rata_laporan_puskesmas+0.5*$rata_laporan_rumkit;
			//--------------------

			//-------------------------------
			//Rekap Nilai Sikap
			//---------------------
			//Rekap Nilai Puskesmas
			//---------------------
			//Nilai Rata Sikap
			$sikap = mysqli_query($conn,"SELECT * FROM `kompre_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'");
			$jumlah_sikap = mysqli_num_rows($sikap);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kompre_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			if ($jumlah_sikap>0) $total_sikap_puskesmas =  $jum_nilai[0]/$jumlah_sikap;
			else $total_sikap_puskesmas = 0;

			//---------------------
			//Rekap Nilai Rumah Sakit
			//---------------------
			//Nilai Rata Sikap
			$sikap = mysqli_query($conn,"SELECT * FROM `kompre_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'");
			$jumlah_sikap = mysqli_num_rows($sikap);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kompre_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'"));
			if ($jumlah_sikap>0) $total_sikap_rumkit =  $jum_nilai[0]/$jumlah_sikap;
			else $total_sikap_rumkit = 0;

			$rata_nilai_sikap = 0.5*$total_sikap_puskesmas+0.5*$total_sikap_rumkit;
			//---------------------

			//---------------------
			//Rekap Nilai CBD
			//---------------------
			//Nilai Rata CBD
			$daftar_cbd = mysqli_query($conn,"SELECT * FROM `kompre_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'");
			$jumlah_cbd = mysqli_num_rows($daftar_cbd);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kompre_nilai_cbd` WHERE `nim`='$data_mhsw[nim]' AND `status_approval`='1'"));
			if ($jumlah_cbd>0) $total_cbd =  $jum_nilai[0]/$jumlah_cbd;
			else $total_cbd = 0;
			//---------------------

			//---------------------
			//Rekap Presensi / Kehadiran
			//---------------------
			//---------------------
			//Rekap Nilai Puskesmas
			//---------------------
			//Nilai Rata Presensi
			$presensi = mysqli_query($conn,"SELECT * FROM `kompre_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'");
			$jumlah_presensi = mysqli_num_rows($presensi);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_total`) FROM `kompre_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			if ($jumlah_presensi>0) $rata_nilai_total_puskesmas =  $jum_nilai[0]/$jumlah_presensi;
			else $rata_nilai_total_puskesmas = 0;
			$total_presensi_puskesmas=$rata_nilai_total_puskesmas;

			//---------------------
			//Rekap Nilai Rumah Sakit
			//---------------------
			//Nilai Rata Presensi
			$presensi = mysqli_query($conn,"SELECT * FROM `kompre_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'");
			$jumlah_presensi = mysqli_num_rows($presensi);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_total`) FROM `kompre_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Rumah Sakit' AND `status_approval`='1'"));
			if ($jumlah_presensi>0) $rata_nilai_total_rumkit =  $jum_nilai[0]/$jumlah_presensi;
			else $rata_nilai_total_rumkit = 0;
			$total_presensi_rumkit=$rata_nilai_total_rumkit;

			$rata_nilai_presensi = 0.5*$total_presensi_puskesmas + 0.5*$total_presensi_rumkit;

			$rata_nilai_kompre = 0.2*$rata_nilai_laporan+0.3*$rata_nilai_sikap+0.2*$total_cbd+0.3*$rata_nilai_presensi;
			//---------------------

			//-------------------------------
			//B. Nilai KDK
			//-------------------------------
			//-------------------------------
			//Rekap Nilai DPL / Laporan Kasus
			//-------------------------------
			//Nilai Rata Kasus Ibu
			$kasus = mysqli_query($conn,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Ibu' AND `status_approval`='1'");
			$jumlah_kasus = mysqli_num_rows($kasus);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Ibu' AND `status_approval`='1'"));
			if ($jumlah_kasus>0) $total_kasus_ibu =  $jum_nilai[0]/$jumlah_kasus;
			else $total_kasus_ibu = 0;

			//Nilai Rata Kasus Bayi/Balita
			$kasus = mysqli_query($conn,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Bayi/Balita' AND `status_approval`='1'");
			$jumlah_kasus = mysqli_num_rows($kasus);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Bayi/Balita' AND `status_approval`='1'"));
			if ($jumlah_kasus>0) $total_kasus_bayi =  $jum_nilai[0]/$jumlah_kasus;
			else $total_kasus_bayi = 0;

			//Nilai Rata Kasus Remaja
			$kasus = mysqli_query($conn,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Remaja' AND `status_approval`='1'");
			$jumlah_kasus = mysqli_num_rows($kasus);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Remaja' AND `status_approval`='1'"));
			if ($jumlah_kasus>0) $total_kasus_remaja =  $jum_nilai[0]/$jumlah_kasus;
			else $total_kasus_remaja = 0;

			//Nilai Rata Kasus Dewasa
			$kasus = mysqli_query($conn,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Dewasa' AND `status_approval`='1'");
			$jumlah_kasus = mysqli_num_rows($kasus);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Dewasa' AND `status_approval`='1'"));
			if ($jumlah_kasus>0) $total_kasus_dewasa =  $jum_nilai[0]/$jumlah_kasus;
			else $total_kasus_dewasa = 0;

			//Nilai Rata Kasus Lansia
			$kasus = mysqli_query($conn,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Lansia' AND `status_approval`='1'");
			$jumlah_kasus = mysqli_num_rows($kasus);
			$jum_nilai = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$data_mhsw[nim]' AND `kasus`='Lansia' AND `status_approval`='1'"));
			if ($jumlah_kasus>0) $total_kasus_lansia =  $jum_nilai[0]/$jumlah_kasus;
			else $total_kasus_lansia = 0;

			$rata_nilai_kasus = ($total_kasus_ibu+$total_kasus_bayi+$total_kasus_remaja+$total_kasus_dewasa+$total_kasus_lansia)/5;
			$rata_nilai_dpl = $rata_nilai_kasus;

			//-----------------
			//Rekap Nilai Klinik
			//-----------------
			//Rekap Nilai Sikap
			$jumlah_sikap_klinik = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `kdk_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
			$nilai_sikap_klinik = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
			if ($jumlah_sikap_klinik>0) $rata_nilai_sikap_klinik = $nilai_sikap_klinik[0]/$jumlah_sikap_klinik;
			else $rata_nilai_sikap_klinik = 0;
			//Rekap Nilai DOPS
			$jumlah_dops_klinik = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `kdk_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
			$nilai_dops_klinik = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
			if ($jumlah_dops_klinik>0) $rata_nilai_dops_klinik = $nilai_dops_klinik[0]/$jumlah_dops_klinik;
			else $rata_nilai_dops_klinik = 0;
			//Rekap Nilai Mini-CEX
			$jumlah_minicex_klinik = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `kdk_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
			$nilai_minicex_klinik = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
			if ($jumlah_minicex_klinik>0) $rata_nilai_minicex_klinik = $nilai_minicex_klinik[0]/$jumlah_minicex_klinik;
			else $rata_nilai_minicex_klinik = 0;
			//Rekap Nilai Presensi Klinik
			$jumlah_presensi_klinik = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `kdk_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
			$nilai_presensi_klinik = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_total`) FROM `kdk_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
			if ($jumlah_presensi_klinik>0) $rata_nilai_presensi_klinik = $nilai_presensi_klinik[0]/$jumlah_presensi_klinik;
			else $rata_nilai_presensi_klinik = 0;

			$rata_nilai_klinik = (2*$rata_nilai_sikap_klinik + 1*$rata_nilai_dops_klinik + 2*$rata_nilai_minicex_klinik+2*$rata_nilai_presensi_klinik)/7;

			//-----------------
			//Rekap Nilai Puskesmas
			//-----------------
			//Rekap Nilai Sikap
			$jumlah_sikap_puskesmas = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `kdk_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			$nilai_sikap_puskesmas = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_sikap` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			if ($jumlah_sikap_puskesmas>0) $rata_nilai_sikap_puskesmas = $nilai_sikap_puskesmas[0]/$jumlah_sikap_puskesmas;
			else $rata_nilai_sikap_puskesmas = 0;
			//Rekap Nilai DOPS
			$jumlah_dops_puskesmas = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `kdk_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			$nilai_dops_puskesmas = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_dops` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			if ($jumlah_dops_puskesmas>0) $rata_nilai_dops_puskesmas = $nilai_dops_puskesmas[0]/$jumlah_dops_puskesmas;
			else $rata_nilai_dops_puskesmas = 0;
			//Rekap Nilai Mini-CEX
			$jumlah_minicex_puskesmas = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `kdk_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			$nilai_minicex_puskesmas = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_rata`) FROM `kdk_nilai_minicex` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			if ($jumlah_minicex_puskesmas>0) $rata_nilai_minicex_puskesmas = $nilai_minicex_puskesmas[0]/$jumlah_minicex_puskesmas;
			else $rata_nilai_minicex_puskesmas = 0;
			//Rekap Nilai Presensi Puskesmas
			$jumlah_presensi_puskesmas = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `kdk_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			$nilai_presensi_puskesmas = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(`nilai_total`) FROM `kdk_nilai_presensi` WHERE `nim`='$data_mhsw[nim]' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
			if ($jumlah_presensi_puskesmas>0) $rata_nilai_presensi_puskesmas = $nilai_presensi_puskesmas[0]/$jumlah_presensi_puskesmas;
			else $rata_nilai_presensi_puskesmas = 0;

			$rata_nilai_sikap = ($rata_nilai_sikap_klinik+$rata_nilai_sikap_puskesmas)/2;
			$rata_nilai_dops = ($rata_nilai_dops_klinik+$rata_nilai_dops_puskesmas)/2;
			$rata_nilai_minicex = ($rata_nilai_minicex_klinik+$rata_nilai_minicex_puskesmas)/2;
			$rata_nilai_presensi = ($rata_nilai_presensi_klinik+$rata_nilai_presensi_puskesmas)/2;

			$rata_nilai_klinik =  (2*$rata_nilai_sikap_klinik + 1*$rata_nilai_dops_klinik + 2*$rata_nilai_minicex_klinik+2*$rata_nilai_presensi_klinik)/7;
			$rata_nilai_puskesmas =  (2*$rata_nilai_sikap_puskesmas + 1*$rata_nilai_dops_puskesmas + 2*$rata_nilai_minicex_puskesmas+2*$rata_nilai_presensi_puskesmas)/7;
			$rata_nilai_kdk = 0.3*$rata_nilai_dpl+0.35*$rata_nilai_puskesmas+0.35*$rata_nilai_klinik;
			//--------------------------------

			//Nilai Rata Kompre dan KDK
			$nilai_rata_kompre_kdk = ($rata_nilai_kompre+$rata_nilai_kdk)/2;
			$nilai_bagian = number_format($nilai_rata_kompre_kdk,2);
		}
		//--------------------------------------------------------------------
		//End of Rekap Nilai Akhir Bagian / Kepaniteraan (Stase) Komprehensip dan Kedokteran Keluarga (KDK)
		//--------------------------------------------------------------------

		//Nilai Akhir
		$ketuntasan = ($grade_penyakit+$grade_ketrampilan)/2;
		if ($ketuntasan >= 70) $rasio_ketuntasan = 1;
		if ($ketuntasan<70 AND $ketuntasan>=60) $rasio_ketuntasan = 0.8;
		if ($ketuntasan<60 AND $ketuntasan>=50) $rasio_ketuntasan = 0.6;
		if ($ketuntasan<50) $rasio_ketuntasan = 0.4;
		$persen_ketuntasan = $rasio_ketuntasan*100;

		$ketuntasan = number_format($ketuntasan,2);

		$nilai_akhir = $rasio_ketuntasan*$nilai_bagian;
		$nilai_akhir = number_format($nilai_akhir,2);

		if ($nilai_akhir<=100 AND $nilai_akhir>=80) $grade_akhir = "A";
		if ($nilai_akhir<80 AND $nilai_akhir>=70) $grade_akhir = "B";
		if ($nilai_akhir<70 AND $nilai_akhir>=60) $grade_akhir = "C";
		if ($nilai_akhir<60 AND $nilai_akhir>=50) $grade_akhir = "D";
		if ($nilai_akhir<50) $grade_akhir = "E";

		//Judul Rekap Total
		$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
		$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
		$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;
		$nama_kepaniteraan = strtoupper($nama_stase['kepaniteraan']);

		$kolom1 = array ('item'=>"");
		$tabel1[1] = array ('item'=>"REKAP NILAI KEPANITERAAN (STASE) $nama_kepaniteraan");
		$tabel1[2] = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
		$tabel1[3] = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
		$pdf->ezTable($tabel1,$kolom1,"",
		array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		'cols'=>array('item'=>array('justification'=>'left'))));

		//Data Mahasiswa
		$pdf->ezSetDy(-20,'');
		$kolom2 = array ('item'=>"",'isi'=>"");
		$tabel2[1] = array ('item'=>"Nama Mahasiswa", 'isi'=>": "."$data_mhsw[nama]");
		$tabel2[2] = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
		$tabel2[3] = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."$nama_stase[kepaniteraan]");
		$tabel2[4] = array ('item'=>"Periode", 'isi'=>": "."$periode");
		$pdf->ezTable($tabel2,$kolom2,"",
		array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		'cols'=>array('item'=>array('justification'=>'left','width'=>110))));
		$pdf->ezSetDy(-20,'');

		if ($id_stase!="M121")
		{
			//Header Tabel Rekap
			$kolom3 = array ('NO'=>'','ITEM'=>'','NILAI'=>'');
			$tabel3[1] = array ('NO'=>'<b>No</b>','ITEM'=>'<b>Item Penilaian</b>','NILAI'=>'<b>Nilai / Grade</b>');
			$pdf->ezTable($tabel3,$kolom3,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ITEM'=>array('justification'=>'center'),'NILAI'=>array('width'=>150,'justification'=>'center'))));

			//Isi Tabel Rekap
			$kolom4 = array ('NO'=>'','ITEM'=>'','NILAI'=>'');
			$tabel4[1] = array ('NO'=>'1','ITEM'=>"Grade Ketuntasan, rata-rata dari:\r\na. Grade Jurnal Penyakit = $grade_penyakit\r\nb. Grade Jurnal Ketrampilan = $grade_ketrampilan",'NILAI'=>"$ketuntasan %");
			$tabel4[2] = array ('NO'=>'2','ITEM'=>'Penilaian Bagian','NILAI'=>$nilai_bagian);
			$pdf->ezTable($tabel4,$kolom4,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'NILAI'=>array('width'=>150,'justification'=>'center'))));

			//Nilai Total Rata-rata
			$kolom6 = array ('TOTAL'=>'','NILAI'=>'');
			$tabel6[1] = array ('TOTAL'=>"Nilai Akhir ( $persen_ketuntasan% x $nilai_bagian ):",'NILAI'=>"<b>$nilai_akhir</b>");
			$tabel6[2] = array ('TOTAL'=>'Ekivalensi Nilai Huruf:','NILAI'=>"<b>$grade_akhir</b>");
			$pdf->ezTable($tabel6,$kolom6,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>150,'justification'=>'center'))));

			//Rumus Nilai
			$kolom4a = array ('ITEM'=>"");
			$tabel4a[1] = array ('ITEM'=>"\r\n<b>Nilai Akhir = Rasio Ketuntasan x Nilai Bagian</b>");
			$tabel4a[2] = array ('ITEM'=>"");
			$tabel4a[3] = array ('ITEM'=>"  Grade Ketuntasan >= 70% --> Rasio Ketuntasan = 100%");
			$tabel4a[4] = array ('ITEM'=>"  60% <= Grade Ketuntasan < 70% --> Rasio Ketuntasan = 80%");
			$tabel4a[5] = array ('ITEM'=>"  50% <= Grade Ketuntasan < 60% --> Rasio Ketuntasan = 60%");
			$tabel4a[6] = array ('ITEM'=>"  Grade Ketuntasan < 50% --> Rasio Ketuntasan = 40%");
			$tabel4a[7] = array ('ITEM'=>"");
			$tabel4a[8] = array ('ITEM'=>"  <i>Ctt: Grade Ketuntasan = (Grade Jurnal Penyakit + Grade Jurnal Ketrampilan)/2</i>");
			$tabel4a[9] = array ('ITEM'=>"");
			$pdf->ezTable($tabel4a,$kolom4a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>1,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'left'))));
			$pdf->ezSetDy(-20,'');


			//Persetujuan Kordik
			$kode_stase = substr($id_stase, 1, 3);
			$kode_kordik = "K"."$kode_stase";
			$nip_kordik = mysqli_fetch_array(mysqli_query($conn,"SELECT `username` FROM `admin` WHERE `stase`='$kode_kordik'"));
			$data_kordik = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
			$kordik = $data_kordik[nama].", ".$data_kordik[gelar];
			$kolom6a = array ('ITEM'=>'');
			$tabel6a[1] = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
			$tabel6a[2] = array ('ITEM'=>'pada tanggal _____________________');
			$tabel6a[3] = array ('ITEM'=>"Kordik Kepaniteraan (Stase) $nama_stase[kepaniteraan]");
			$tabel6a[4] = array ('ITEM'=>'');
			$tabel6a[5] = array ('ITEM'=>'');
			$tabel6a[6] = array ('ITEM'=>$kordik);
			$tabel6a[7] = array ('ITEM'=>'NIP: '.$data_kordik[nip]);
			$pdf->ezTable($tabel6a,$kolom6a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'right'))));
		}
		else
		{
			//Header Tabel Rekap
			$kolom3 = array ('NO'=>'','ITEM'=>'','NILAI'=>'');
			$tabel3[1] = array ('NO'=>'<b>No</b>','ITEM'=>'<b>Item Penilaian</b>','NILAI'=>'<b>Nilai / Grade</b>');
			$pdf->ezTable($tabel3,$kolom3,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ITEM'=>array('justification'=>'center'),'NILAI'=>array('width'=>150,'justification'=>'center'))));

			//Isi Tabel Rekap
			$nilai_kompre = number_format($rata_nilai_kompre,2);
			$nilai_kdk = number_format($rata_nilai_kdk,2);
			$kolom4 = array ('NO'=>'','ITEM'=>'','NILAI'=>'');
			$tabel4[1] = array ('NO'=>'1','ITEM'=>"Grade Ketuntasan, rata-rata dari:\r\na. Grade Jurnal Penyakit = $grade_penyakit\r\nb. Grade Jurnal Ketrampilan = $grade_ketrampilan",'NILAI'=>"$ketuntasan %");
			$tabel4[2] = array ('NO'=>'2','ITEM'=>"Penilaian Bagian, rata-rata dari:\r\na. Nilai Komprehensip = $nilai_kompre\r\nb. Nilai Kedokteran Keluarga = $nilai_kdk",'NILAI'=>$nilai_bagian);
			$pdf->ezTable($tabel4,$kolom4,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'NILAI'=>array('width'=>150,'justification'=>'center'))));

			//Nilai Total Rata-rata
			$kolom6 = array ('TOTAL'=>'','NILAI'=>'');
			$tabel6[1] = array ('TOTAL'=>"Nilai Akhir ( $persen_ketuntasan% x $nilai_bagian ):",'NILAI'=>"<b>$nilai_akhir</b>");
			$tabel6[2] = array ('TOTAL'=>'Ekivalensi Nilai Huruf:','NILAI'=>"<b>$grade_akhir</b>");
			$pdf->ezTable($tabel6,$kolom6,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>150,'justification'=>'center'))));

			//Rumus Nilai
			$kolom4a = array ('ITEM'=>"");
			$tabel4a[1] = array ('ITEM'=>"\r\n<b>Nilai Akhir = Rasio Ketuntasan x Nilai Bagian</b>");
			$tabel4a[2] = array ('ITEM'=>"");
			$tabel4a[3] = array ('ITEM'=>"  Grade Ketuntasan >= 70% --> Rasio Ketuntasan = 100%");
			$tabel4a[4] = array ('ITEM'=>"  60% <= Grade Ketuntasan < 70% --> Rasio Ketuntasan = 80%");
			$tabel4a[5] = array ('ITEM'=>"  50% <= Grade Ketuntasan < 60% --> Rasio Ketuntasan = 60%");
			$tabel4a[6] = array ('ITEM'=>"  Grade Ketuntasan < 50% --> Rasio Ketuntasan = 40%");
			$tabel4a[7] = array ('ITEM'=>"");
			$tabel4a[8] = array ('ITEM'=>"  <i>Ctt: Grade Ketuntasan = [(Grade Jurnal Penyakit + Grade Jurnal Ketrampilan)/2] %</i>");
			$tabel4a[9] = array ('ITEM'=>"");
			$pdf->ezTable($tabel4a,$kolom4a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>1,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'left'))));
			$pdf->ezSetDy(-30,'');

			//Persetujuan Kordik
			$kode_stase = substr($id_stase, 1, 3);
			$kode_kordik1 = "K121";
			$kode_kordik2 = "K122";
			$nip_kordik1 = mysqli_fetch_array(mysqli_query($conn,"SELECT `username` FROM `admin` WHERE `stase`='$kode_kordik1'"));
			$data_kordik1 = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `dosen` WHERE `nip`='$nip_kordik1[username]'"));
			$nip_kordik2 = mysqli_fetch_array(mysqli_query($conn,"SELECT `username` FROM `admin` WHERE `stase`='$kode_kordik2'"));
			$data_kordik2 = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `dosen` WHERE `nip`='$nip_kordik2[username]'"));
			$kordik1 = $data_kordik1[nama].", ".$data_kordik1[gelar];
			$kordik2 = $data_kordik2[nama].", ".$data_kordik2[gelar];
			$kolom6a = array ('ITEM1'=>'','ITEM2'=>'');
			$tabel6a[1] = array ('ITEM1'=>'Status: <b>DISETUJUI</b>','ITEM2'=>'Status: <b>DISETUJUI</b>');
			$tabel6a[2] = array ('ITEM1'=>'pada tanggal _____________________','ITEM2'=>'pada tanggal _____________________');
			$tabel6a[3] = array ('ITEM1'=>"Kordik Kepaniteraan Kedokteran Keluarga",'ITEM2'=>"Kordik Kepaniteraan Komprehensip");
			$tabel6a[4] = array ('ITEM1'=>'','ITEM2'=>'');
			$tabel6a[5] = array ('ITEM1'=>'','ITEM2'=>'');
			$tabel6a[6] = array ('ITEM1'=>$kordik1,'ITEM2'=>$kordik2);
			$tabel6a[7] = array ('ITEM1'=>'NIP: '.$data_kordik1[nip],'ITEM2'=>'NIP: '.$data_kordik1[nip]);
			$pdf->ezTable($tabel6a,$kolom6a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
			'cols'=>array('ITEM1'=>array('width'=>240,'justification'=>'right'),'ITEM2'=>array('width'=>255,'justification'=>'right'))));
		}


		$pdf->ezSetDy(-10,'');
		$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Akhir $nama_stase[kepaniteraan]    <i>[hal 1]</i>");
		$pdf->ezStream();

		$delete_dummy_penyakit = mysqli_query($conn,"DELETE FROM `jurnal_penyakit_dummy` WHERE `username`='$nim_mhsw'");
		$delete_dummy_ketrampilan = mysqli_query($conn,"DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username`='$nim_mhsw'");

?>
