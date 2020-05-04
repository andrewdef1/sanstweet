<?php
  //Time Handler
    ini_set('max_execution_time', 0); // for infinite time of execution 
    $time_start = microtime(true);  //Start Timer
include '../koneksi.php';
?>
<html lang="en">
<head>
  <link rel="icon" href="../LogoV2.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="UTF-8">
  <title>Dashboard -SANS SENTIMENT-</title>
  
  <!-- CSS & JS -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/animate.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome-all.min.css">
    <script src='../js/jquery.min.js'></script>
	<script src="../js/index.js"></script>
	<script src="../js/bootstrap.min.js"></script>

</head>
<body>
  <div class="header">
  <div class="logo">
    <img src="../LogoV2.png" style="width: 50px; height: 45px;">
    Dashboard -SANS SENTIMENT-
  </div>
</div>


<div class="sidebar">
  <ul>
    <li><a href="../dashboard.php"><i class="fa fa-home"></i><span>Home</span></a></li>
	
	 <li><a href="#pageSubtraining" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-tasks"></i><strong><span>Data Training</span></strong><i class="fas fa-caret-square-down"></i></a>
      <ul class="collapse list-unstyled" id="pageSubtraining">
	  <li><a href="indexIMP.php"><i class="fas fa-file-import"></i><span>Import Data Training</span></a></li>
    <li><a href="../training/dashboard.php"><i class="fas fa-database"></i><span>Dataset Training</span></a></li>
    <li><a href="../bobot/dashboard.php"><i class="fas fa-balance-scale"></i><span>Pembobotan Term</span></a></li>
    <li><a href="../training/dashboardBayes.php"><i class="fas fa-cookie"></i><span>Naive Bayes Data Training</span></a></li>
    </li>
    </ul>
   
     <li><a href="#pageSubtesting" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-tasks"></i><strong><span>Data Testing</span></strong><i class="fas fa-caret-square-down"></i></a>
      <ul class="collapse list-unstyled" id="pageSubtesting">
    <li><a href="../testing/dashboard.php"><i class="fas fa-database"></i><span>Dataset Testing</span></a></li>
    <li><a href="../testing/dashboardPRE.php"><i class="fas fa-spinner"></i><span>Preprocessing Data Testing</span></a></li>
    <li><a href="../testing/dashboardKLASIFIKASI.php"><i class="fas fa-cookie"></i><span>Klasifikasi Data Testing</span></a></li>
     </li>
   </ul>
    
    
</div>

<!-- Content -->
<div class="main">
		<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			
			<h3 align="center">Form Import Data</h3>
			<hr>

			<!-- Buat sebuah tag form dan arahkan action nya ke file ini lagi -->
			<form method="post" action="" enctype="multipart/form-data">
				<div align="center">
				<a href="formatDATALATIH.csv" class="btn btn-default">
					<span class="glyphicon glyphicon-download"></span>
					Download Format
				</a><br><br></div>
				<!--
				-- Buat sebuah input type file
				-- class pull-left berfungsi agar file input berada di sebelah kiri
				-->
				<div align="center">
				<input type="file" name="file" class="pull-center">
<br>
				<button type="submit" name="preview" class="btn btn-success btn-sm pull-center">
					<span class="glyphicon glyphicon-eye-open"></span> Preview
				</button>
				</div>
			</form>

			<hr>

			<!-- Buat Preview Data -->
			<?php
			// Jika user telah mengklik tombol Preview
			if(isset($_POST['preview'])){
				$nama_file_baru = 'data.csv';

				// Cek apakah terdapat file data.xlsx pada folder tmp
				if(is_file('tmp/'.$nama_file_baru)) // Jika file tersebut ada
					unlink('tmp/'.$nama_file_baru); // Hapus file tersebut

				$nama_file = $_FILES['file']['name']; // Ambil nama file yang akan diupload
				$tmp_file = $_FILES['file']['tmp_name'];
				$ext = pathinfo($nama_file, PATHINFO_EXTENSION); // Ambil ekstensi file yang akan diupload

				// Cek apakah file yang diupload adalah file CSV
				if($ext == "csv"){
					// Upload file yang dipilih ke folder tmp
					move_uploaded_file($tmp_file, 'tmp/'.$nama_file_baru);

					// Load librari PHPExcel nya
					require_once '../PHPExcel/PHPExcel.php';

					$inputFileType = 'CSV';
					$inputFileName = 'tmp/data.csv';

					$reader = PHPExcel_IOFactory::createReader($inputFileType);
					$excel = $reader->load($inputFileName);
			
					// Buat sebuah tag form untuk proses import data ke database
					echo "<form method='post' action='import.php'>";
			
					/* Buat sebuah div untuk alert validasi kosong
					echo "<div class='alert alert-danger' id='kosong'>
					Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum lengkap diisi.
					</div>";
*/
					echo "<table class='table table-bordered'>
					<tr>
						<th colspan='5' class='text-center'>Preview Data</th>
					</tr>
					<tr>
					
						
						<th>Tweet</th>
						<th>Date Tweet</th>
						
						
						<th>Kategori</th>
					</tr>";

					$numrow = 1;
					
					$kosong = 0;
					$worksheet = $excel->getActiveSheet();
					foreach ($worksheet->getRowIterator() as $row) { // Lakukan perulangan dari data yang ada di csv
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
											
							
							$tweet = $get[0]; // Ambil data alamat
							$datetweet = $get[1]; // Ambil data jenis kelamin
							$kategori = $get[2]; // Ambil data alamat

							// Cek jika semua data tidak diisi
							if($tweet == "" && $datetweet == "" && $kategori == "")
								continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)

							// Validasi apakah semua data telah diisi
						
							
							$tweet_td = ( ! empty($tweet))? "" : " style='background: #E07171;'"; // Jika Alamat kosong, beri warna 
							$datetweet_td = ( ! empty($datetweet))? "" : " style='background: #E07171;'"; // Jika Jenis Kelamin kosong, beri warna merah
						
							
							$kategori_td = ( ! empty($kategori))? "" : " style='background: #E07171;'"; // Jika Alamat kosong, beri warna 

							// Jika salah satu data ada yang kosong
							if($tweet == "" or $datetweet == "" or $kategori == ""){
								$kosong++; // Tambah 1 variabel $kosong
							}
							
							echo "<tr>";
							
							
							
							echo "<td".$tweet_td.">".$tweet."</td>";
							echo "<td".$datetweet_td.">".$datetweet."</td>";
							
							
							echo "<td".$kategori_td.">".$kategori."</td>";
							echo "</tr>";
						}

						$numrow++; // Tambah 1 setiap kali looping
					}

					echo "</table>";

					// Cek apakah variabel kosong lebih dari 1
					// Jika lebih dari 1, berarti ada data yang masih kosong
					if($kosong > 1){
					?>
						<script>
						$(document).ready(function(){
							// Ubah isi dari tag span dengan id jumlah_kosong dengan isi dari variabel kosong
							$("#jumlah_kosong").html('<?php echo $kosong; ?>');

							$("#kosong").show(); // Munculkan alert validasi kosong
						});
						</script>
					<?php
					}else{ // Jika semua data sudah diisi
						echo "<hr>";

						// Buat sebuah tombol untuk mengimport data ke database
						echo "<button type='submit' name='import' class='btn btn-primary'><span class='glyphicon glyphicon-upload pull-right'></span> Import</button>";
					}

					echo "</form>";
				}else{ // Jika file yang diupload bukan File CSV
					// Munculkan pesan validasi
					echo "<div class='alert alert-danger'>
					Hanya File CSV (.csv) yang diperbolehkan
					</div>";
				}
			}
			?>
		</div>
  </div>



</div>

</body>
</html>
