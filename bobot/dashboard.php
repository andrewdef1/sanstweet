<?php
    //Time Handler
    ini_set('max_execution_time', 0); // for infinite time of execution 
    $time_start = microtime(true);  //Start Timer
include '../koneksi.php';
include "functBOBOT.php";
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
    <link rel="stylesheet" href="../datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../datatables/css/jquery.dataTables.css">

    <script src='../js/jquery.min.js'></script>
	  <script src="../js/index.js"></script>
	  <script src="../js/bootstrap.min.js"></script>
    <script src="../datatables/js/jquery.dataTables.min.js"></script>
    <script src="../datatables/js/dataTables.bootstrap4.min.js"></script>

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
	  <li><a href="../import/indexIMP.php"><i class="fas fa-file-import"></i><span>Import Data Training</span></a></li>
    <li><a href="../training/dashboard.php"><i class="fas fa-database"></i><span>Dataset Training</span></a></li>
    <li><a href="dashboard.php"><i class="fas fa-balance-scale"></i><span>Pembobotan Term</span></a></li>
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

<form method="post" action="">
<!-- Content -->
<div class="main">
  <div class="hipsum">
  
</div> 

<div class="row row-centered">
  <div class ="col-md-4 col-centered">
  <?php
    $time_end = microtime(true); //End Timer
    //Calculate and Print Timer
    $execution_time = ($time_end - $time_start);
    echo '<b>Total Execution Time:</b> '.$execution_time.' Seconds';
?>
</div>
  
</div>

    <table class="table table-striped table-bordered data">
      <thead>
        <tr>
          <th>#</th>
          <th>TERM</th>
		  
          <th>TF</th>
          <th>IDF</th>
          <th>TF-IDF</th>
          
        </tr>

      </thead>
      <tbody>
<?php 

      
          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $query = mysqli_query($konek,"SELECT * FROM tblterm ORDER BY term ASC");
          $no=1;
      while ($data = mysqli_fetch_assoc($query)) 
        
	  { // Ambil semua data dari hasil eksekusi $sql

    ?>
      <tr>
        <td><?php echo $no++; ?></td>         <!--menampilkan nomor dari variabel no-->
      
        <td><?php echo $data['term'] ?></td>
		
        <td><?php echo $data['count'] ?></td>     
        <td><?php echo $data['idf']  ?></td>     
           
        <td><?php echo $data['bobot'] ?></td>      
   
             
    
      </tr>
      <?php 
                            }

      ?>
      </tbody>
    </table>

	
	<!-- PROSES BOBOT TFIDF-->
<button style="float: left; margin-top:0px;" type="submit" name="bobot" value="bobot" class="btn btn-primary">PROSES BOBOT TERM</button>
<?php
    if(isset($_POST['bobot'])){
		
		//hitungbobot($konek);
    $idff=new functbobot();

    $idff->buatindex($konek);
	
    //ambil semua bobot idf
        $query=mysqli_query($konek, "SELECT term, idf FROM tblterm;");
        $idfkata=array();
        while ($data=mysqli_fetch_array($query)) {
          $idfkata[]=$data['term'];
        }

    //ambil semua tweet dan melakukan preprocessing sehingga didapatkan kalimat yang bersih
        $query=mysqli_query($konek,"SELECT preproses FROM tbltweet");
        $preproses=array();
        while ($data=mysqli_fetch_array($query)) {
          $k=$data['preproses'];
            $preproses[]=$k;
        }

        //MENGHITUNG NILAI IDF
        $idfbobot=array();
        $D=count($preproses);
        $idfbobot=$idff->hitungIDF($preproses, $idfkata, $D);
        $idfkata=array_combine($idfkata, $idfbobot);
        foreach ($idfkata as $kata => $idf) {
          mysqli_query($konek, "UPDATE tblterm SET idf=$idf WHERE term='$kata'");
        }

        //Menghitung nilaiu TFIDF
        $idff->hitungbobot($konek);
		
		//kecenderungan
		$idff->informpos($konek);
		$idff->informneg($konek);
		$idff->cenderung($konek);


		?>
                                        <script type="text/javascript">
                                            alert('Term berhasil diberi Bobot TF-IDF');
                                            document.location.href="dashboard.php";
                                        </script>
                                        <?php
	}
?>
	<!-- END PROSES BOBOT TFIDF-->
		
	
    </div>
  </div>
</form>

<div class="footer navbar-fixed-bottom">


</div>

</div>
  <script type="text/javascript">
  $(document).ready(function(){
    $('.data').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        "language":{
          "order": [[ 1, "desc" ]],
          "decimal":        "",
          "emptyTable":     "Tidak ada data pada tabel ini",
          "info":           "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
          "infoEmpty":      "Menampilkan 0 hingga 0 dari 0 data",
          "infoFiltered":   "( Disaring dari _MAX_ total data)",
          "infoPostFix":    "",
          "thousands":      ",",
          "lengthMenu":     "Tampilkan _MENU_ data",
          "loadingRecords": "Memuat...",
          "processing":     "Memproses...",
          "search":         "Cari:",
          "zeroRecords":    "Tidak ada data yang ditemukan",
          "paginate": {
              "first":      "Pertama",
              "last":       "Terakhir",
              "next":       "Selanjutnya",
              "previous":   "Sebelumnya"
          },
          "aria": {
              "sortAscending":  ": activate to sort column ascending",
              "sortDescending": ": activate to sort column descending"
          }
      }
      });
  });
  </script>

</body>
</html>
