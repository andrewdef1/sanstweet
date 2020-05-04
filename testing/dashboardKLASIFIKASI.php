<?php

    //Time Handler
    ini_set('max_execution_time', 0); // for infinite time of execution 
    $time_start = microtime(true);  //Start Timer
include '../koneksi.php';
include "functTESTING.php";
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
	<script type="text/javascript" src="../js/Chart.js"></script>

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
    <li><a href="../bobot/dashboard.php"><i class="fas fa-balance-scale"></i><span>Pembobotan Term</span></a></li>
    <li><a href="../training/dashboardBayes.php"><i class="fas fa-cookie"></i><span>Naive Bayes Data Training</span></a></li>
    </li>
    </ul>
   
     <li><a href="#pageSubtesting" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-tasks"></i><strong><span>Data Testing</span></strong><i class="fas fa-caret-square-down"></i></a>
      <ul class="collapse list-unstyled" id="pageSubtesting">
	<li><a href="dashboard.php"><i class="fas fa-database"></i><span>Dataset Testing</span></a></li>
    <li><a href="dashboardPRE.php"><i class="fas fa-spinner"></i><span>Preprocessing Data Testing</span></a></li>
    <li><a href="dashboardKLASIFIKASI.php"><i class="fas fa-cookie"></i><span>Klasifikasi Data Testing</span></a></li>
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


  <?php 
  
  $query=mysqli_query($konek, "SELECT id_tweet FROM tbltesting WHERE predicted='positive';");
  $pos=mysqli_num_rows($query);
  $query=mysqli_query($konek, "SELECT id_tweet FROM tbltesting WHERE predicted='negative';");
  $neg=mysqli_num_rows($query);
  $query=mysqli_query($konek, "SELECT id_tweet FROM tbltesting WHERE predicted='neutral';");
  $net=mysqli_num_rows($query);
  $query=mysqli_query($konek, "SELECT * FROM tbltesting;"); 
  $tot=mysqli_num_rows($query);
  ?>

<div class="row">
    <div class="col-md-4">
        <div class="card-body bg-info">
            <h3 class="card-title text-center text-white">TOTAL TWEET : <?= $tot; ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-body bg-success">
            <h3 class="card-title text-center text-white">TWEET POSITIF : <?= $pos; ?></h3>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="card-body bg-danger">
            <h3 class="card-title text-center text-white">TWEET NEGATIF : <?= $neg; ?></h3>
        </div>
    </div>
   <div class="col-md-4 mb-2">
        <div class="card-body bg-warning">
            <h3 class="card-title text-center text-white">TWEET NETRAL : <?= $net; ?></h3>
        </div>
    </div>
  </div>

    <table class="table table-striped table-bordered data">
      <thead>
        <tr>
          <th>#</th>
          <th>TWEET</th>
          <th>PREPROSES</th>
          <th>PROBABILITAS POSITIF</th>
          <th>PROBABILITAS NEGATIF</th>
          <th>PROBABILITAS NETRAL</th>
          <th>KLASIFIKASI</th>
        </tr>

      </thead>
      <tbody>
<?php 

          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $query = mysqli_query($konek,"SELECT * FROM tbltesting ORDER BY id_tweet");
      
			$no = 1;


      while ($data = mysqli_fetch_assoc($query)) 
	  { // Ambil semua data dari hasil eksekusi $sql
    ?>
      <tr>
        <td><?php echo $no++; ?></td>         <!--menampilkan nomor dari variabel no-->
     <!--   <td><?//php echo $data['id_tweet'] ?></td>    menampilkan data id_karyawan dari tabel karyawan-->
        <td><?php echo $data['tweet']?></td>
        <td><?php echo $data['preproses'] ?></td>     
        <td><?php echo $data['pos'] ?></td>     
        <td><?php echo $data['neg'] ?></td>        
        <td><?php echo $data['net'] ?></td>        
        <td><?php echo $data['predicted'] ?></td>        
    
      </tr>
      <?php 

                            }
      ?>
      </tbody>
    </table>
	
	<!-- PROSES BOBOT TFIDF-->
<button style="float: left; margin-top:0px;" type="submit" name="klasifikasi" value="klasifikasi" class="btn btn-primary">PROSES KLASIFIKASI</button>
<?php
    if(isset($_POST['klasifikasi'])){
    $NBk=new functest();
    $NBk->klasifikasi_sentimen($konek);
   // $NBk->termKlasifikasi($konek);
   // $NBk->predictKlasifikasi($konek);
		?>
                                        <script type="text/javascript">
                                            alert('Data Berhasil di Klasifikasi');
                                            document.location.href="dashboardKLASIFIKASI.php";
                                        </script>
                                        <?php
	}
?>
	<!-- END PROSES BOBOT TFIDF-->
		
	
    </div>
  </div>
</form>
<br>

<br>
<br>
<section>
  <div style="width: 800px;margin: 0px auto;">
    <canvas id="myChart"></canvas>
  </div>


 
    <script>
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ["Positive", "Negative", "Neutral"],
        datasets: [{
          label: 'CHART PREDIKSI',
          data: [
          <?php 
          $jumlah_pos = mysqli_query($konek,"SELECT predicted FROM tbltesting WHERE predicted='positive' ");
          echo mysqli_num_rows($jumlah_pos);
          ?>, 
          <?php 
          $jumlah_neg = mysqli_query($konek,"SELECT predicted FROM tbltesting WHERE predicted='negative' ");
          echo mysqli_num_rows($jumlah_neg);
          ?>, 
          <?php 
          $jumlah_net = mysqli_query($konek,"SELECT predicted FROM tbltesting WHERE predicted='neutral'");
          echo mysqli_num_rows($jumlah_net);
          ?>
          ],
          backgroundColor: [
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 99, 132, 0.2)',          
          'rgba(255, 206, 86, 0.2)'         
          ],
          borderColor: [
          'rgba(54, 162, 235, 1)',
          'rgba(255,99,132,1)',         
          'rgba(255, 206, 86, 1)'         
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      }
    });
  </script>

</section>

<div class="footer">
  
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
