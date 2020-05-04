<?php
/*
-- Source Code from My Notes Code (www.mynotescode.com)
--
-- Follow Us on Social Media
-- Facebook : http://facebook.com/mynotescode
-- Twitter  : http://twitter.com/mynotescode
-- Google+  : http://plus.google.com/118319575543333993544
--
-- Terimakasih telah mengunjungi blog kami.
-- Jangan lupa untuk Like dan Share catatan-catatan yang ada di blog kami.
*/

// Load file koneksi.php

session_start();
if (!isset($_SESSION['nim'])) {
  header("location: ../index.php");
}
  //Time Handler
    ini_set('max_execution_time', 0); // for infinite time of execution 
    $time_start = microtime(true);  //Start Timer
include '../koneksi.php';

if(isset($_POST['import'])){ // Jika user mengklik tombol Import
	// Load librari PHPExcel nya
	require_once '../PHPExcel/PHPExcel.php';

	$inputFileType = 'CSV';
	$inputFileName = 'tmp/data.csv';

	$reader = PHPExcel_IOFactory::createReader($inputFileType);
	$excel = $reader->load($inputFileName);

	$numrow = 1;
	$worksheet = $excel->getActiveSheet();
	foreach ($worksheet->getRowIterator() as $row) {
		// Cek $numrow apakah lebih dari 1
		// Artinya karena baris pertama adalah nama-nama kolom
		// Jadi dilewat saja, tidak usah diimport
		if($numrow > 1){
			// START -->
			// Skrip untuk mengambil value nya
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

			$get = array(); // Valuenya akan di simpan kedalam array,dimulai dari index ke 0
			foreach ($cellIterator as $cell) {
				array_push($get, $cell->getValue()); // Menambahkan value ke variabel array $get
			}
			// <-- END

			// Ambil data value yang telah di ambil dan dimasukkan ke variabel $get
			
			
			$tweet = $get[0]; // Ambil data tweet
			$datetweet = $get[1]; // Ambil data date
			
			
			$kategori = $get[2]; // Ambil data kategori

			// Cek jika semua data tidak diisi
			if($tweet == "" && $datetweet == "" && $kategori == "")
				continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)

			// Tambahkan value yang akan di insert ke variabel $query
			// Buat query Insert
			$query = "INSERT INTO tbltweet (tweet, tweet_bulan, kategori) VALUES('".$tweet."','".$datetweet."','".$kategori."')";
			mysqli_query($konek, $query);
		}

		$numrow++; // Tambah 1 setiap kali looping
	}
}

header('location: indexIMP.php'); // Redirect ke halaman awal
?>
