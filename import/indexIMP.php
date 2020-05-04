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
  <div class="hipsum">
  
</div> 
  <!-- 
      -- Buat sebuah tombol untuk mengarahkan ke form import data
      -- Tambahkan class btn agar terlihat seperti tombol
      -- Tambahkan class btn-success untuk tombol warna hijau
      -- class pull-right agar posisi link berada di sebelah kanan
      -->

        <a href="form.php" class="btn btn-success pull-left">
        <span class="glyphicon glyphicon-upload"></span> Import Data
      </a>  
      <h3 align="center">Import Data Training</h3>
      
      <hr>
      
      <!-- Buat sebuah div dan beri class table-responsive agar tabel jadi responsive -->
      
         <table id="example" class="table table-striped table-bordered" style="width:100%;">
      <thead>
        <tr>
          <th>#</th>
            
            <th>Tweet</th>
            <th>Bulan Tweet</th>
            <th>Kategori</th>
        </tr>

      </thead>
      <tbody>
<?php 

          
          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $query = mysqli_query($konek,"SELECT * FROM tbltweet ORDER BY id_tweet ");
      
      $no = 1;


      while ($data = mysqli_fetch_assoc($query)) 
    { // Ambil semua data dari hasil eksekusi $sql
    ?>
      <tr>
        <td><?php echo $no++; ?></td>         <!--menampilkan nomor dari variabel no-->
     <!--   <td><?//php echo $data['id_tweet'] ?></td>    menampilkan data id_karyawan dari tabel karyawan-->
        
        <td><?php echo $data['tweet'] ?></td>     
        <td><?php echo $data['tweet_bulan'] ?></td>     
        <td><?php echo $data['kategori'] ?></td>        
    
      </tr>
      <?php 

                            }
      ?>
      </tbody>
    </table>

    
      
    </div>
  </div>



<div class="footer">


</div>

</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
</body>
</html>
