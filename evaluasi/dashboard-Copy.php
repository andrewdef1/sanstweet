<?php
//Time Handler
    ini_set('max_execution_time', 0); // for infinite time of execution 
    $time_start = microtime(true);  //Start Timer
include '../koneksi.php';
include "confuse.php";
?>
<html lang="en">
<head>
  <link rel="icon" href="../LogoV2.png">
  <script type="text/javascript" src="../js/Chart.js"></script>
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
    <li><a href="dashboard.php"><i class="fas fa-percent"></i><span>Evaluasi</span></a></li>
    <li><a href="../logout.php"><i class="fa fa-power-off" style="color:red"></i><span>Logout</span></a></li>
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
	<br>
	<div align="center">
	<?php
			//TP
			$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltesting WHERE kategori = 'positive' AND predicted='positive'");
    		$tpCount = mysqli_fetch_assoc($sql);
    		$tpCount = $tpCount['total'];
        echo "TP".$tpCount."<br>";
			
			//TN
    		$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltesting WHERE kategori = 'negative' AND predicted='negative'");
    		$tnCount = mysqli_fetch_assoc($sql);
    		$tnCount = $tnCount['total'];
echo "TN".$tnCount."<br>";
    		//TNet
    		$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltesting WHERE kategori = 'neutral' AND predicted='neutral'");
    		$tnetCount = mysqli_fetch_assoc($sql);
    		$tnetCount = $tnetCount['total'];
			echo "TNet".$tnetCount."<br>";
			//FP
    		$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltesting WHERE kategori = 'positive' AND predicted NOT LIKE '%positive%'");
    		$fpCount = mysqli_fetch_assoc($sql);
    		$fpCount = $fpCount['total'];
			echo "FP".$fpCount."<br>";
			//FN
			$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltesting WHERE kategori = 'negative' AND predicted NOT LIKE '%negative%'");
    		$fnCount = mysqli_fetch_assoc($sql);
    		$fnCount = $fnCount['total'];
    					echo "FN".$fnCount."<br>";
			//FNet
			$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltesting WHERE kategori = 'neutral' AND predicted NOT LIKE '%neutral%'  ");
    		$fnetCount = mysqli_fetch_assoc($sql);
    		$fnetCount = $fnetCount['total'];
			echo "FNet".$fnetCount."<br>";
			
			//HITUNG CONFUSE Accuracy 
			$akurasi  = @(($tpCount+$tnCount+$tnetCount)/($tpCount+$tnCount+$tnetCount+$fpCount+$fnCount+$fnetCount)*100);
			echo "<br><strong>Akurasi = ".$akurasi."%</strong>";
			
			/*
			echo"<br>";
			//HITUNG CONFUSE Precisson 
			$presisi = ($tpCount)/($fpCount+$tpCount)*100;
			echo "Presisi = ".$presisi."%";
			
			echo"<br>";
			//HITUNG CONFUSE Recall
			$recall = ($tpCount)/($fnCount+$tpCount)*100;
			echo "Recall = ".$recall."%";
			*/
			
			$positive      = mysqli_query($konek, "SELECT COUNT(predicted) FROM tbltweet WHERE predicted='positive' ORDER BY id_tweet asc");
			$negative = mysqli_query($konek, "SELECT COUNT(predicted) FROM tbltweet WHERE predicted='negative' ORDER BY id_tweet asc");
			$neutral = mysqli_query($konek, "SELECT COUNT(predicted) FROM tbltweet WHERE predicted='neutral' ORDER BY id_tweet asc");
?>
</div>

	<!-- PROSES PREPROCESSING-->
<button style="float: left; margin-top:0px;" type="submit" name="evaluasi" value="PROSESEVALUASI" class="btn btn-primary">PROSES EVALUASI AKURASI</button>
<?php
    if(isset($_POST['evaluasi'])){
    $eva=new evaluasi();
    $eva->akurasi($konek);
		//akurasi($konek);
		?>
                                        <script type="text/javascript">
                                            alert('Data berhasil di evaluasi');
                                            document.location.href="dashboard.php";
                                        </script>
                                        <?php
	}
?>
	<!-- END PROSES PREPROCESSING-->
	
</form>

<div class="footer">
   

</div>

</div>

</body>
</html>
