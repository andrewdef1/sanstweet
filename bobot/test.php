<?php

include "IR.php";
include "../koneksi.php";
ini_set('max_execution_time', 0); // for infinite time of execution 
    $time_start = microtime(true);  //Start Timer
// document yang sebagai percobaan
/*
$fetch1 = mysqli_query($konek, "SELECT preproses FROM tbltweet");
$fetch2 = mysqli_query($konek, "SELECT term FROM tblterm");
    $idf=array();
    foreach ($fetch2 as $kata) {
      $ftcht = $kata['term'];
        $f=0;
        foreach ($fetch1 as $komen) {
          $ftchk = $komen['preproses'];
            $komenn=explode(" ", $ftchk);
            if (in_array($ftcht, $komenn)) {
                $f++;
            }
        }
        $idf[]=log(224+1/$f);

        echo $f;
    }

   $fetch1 = mysqli_query($konek, "SELECT preproses FROM tbltweet WHERE kategori='positive'");
    $fetch2 = mysqli_query($konek, "SELECT  term FROM tblterm");
    $netC=array();
    $jumlah=0;
    while ($data=mysqli_fetch_array($fetch1)) {
            $ftf=$data['preproses'];
            $op=explode(" ", $ftf);
     $typeTotals[] = count($op);
     $typesum=array_sum($typeTotals);
echo '<pre>'; print_r($op); echo '</pre>';


}

echo $typesum;




/*
$fetch1 = mysqli_query($konek, "SELECT preproses FROM tbltweet");

$D = array();
foreach ($fetch1 as $ftch) {
  $D[] = $ftch['preproses'];
}
   



*/
/*
$fetch1 = mysqli_query($konek, "SELECT preproses FROM tbltweet WHERE kategori='positive'");
    $fetch2 = mysqli_query($konek, "SELECT term FROM tblterm");
    $netC=array();
    
    while ($data=mysqli_fetch_array($fetch1)) {
            $ftf=$data['preproses'];
            $op=explode(" ", $ftf);
     $typeTotals[] = $op;
     $typesum=array_sum($typeTotals);



//echo '<pre>'; print_r($op); echo '</pre>';


}
     





$query_dok = mysqli_query($konek,"SELECT id_tweet,  preproses FROM tbltweet WHERE kategori='positive'"); 
  while ($row_dok = mysqli_fetch_array($query_dok)) {     
    $prob_kata_positif = []; $prob_kata_negatif = [];  $prob_kata_netral= [];    
    $kata_dok = $row_dok['preproses'];     
    $id_dok = $row_dok['id_tweet'];     
    $kata_hasil = explode(" ", $kata_dok); 

    foreach ($kata_hasil as $key => $v) {       
      $query_bobot_kata = mysqli_query($konek,"SELECT id,        
        term FROM  tblterm WHERE term = '".$key."'"); 
   
      while ($row_kata = mysqli_fetch_array($query_bobot_kata)) { 
        if ($key == $row_kata['term']) {           
    
          
          echo "Hari <b>".$key."</b> ada ".$v." buah di dalam array<br />";  

      
        } else {           
          $prob_kata_positif[$key] = 1; $prob_kata_negatif[$key] = 1;  $prob_kata_netral[$key] = 1;         
        }     
        
      }     
    }     
  }

$ff=log(13+1/2);
*/


		
		$teks = "Mari kita memulai belajar PHP, dengan PHP kita dapat belajar mengenai array dan fungsi lain yang dapat mempermudah pekerjaan yang dilakukan. fungsi seperti explode dapat membantu untuk mengubah string menjadi array.";
//bersihkan teks dari tanda baca
			
$resTweets = array();
$infopos = array();
$resTweets = mysqli_query($konek,"SELECT * FROM tbltweet WHERE kategori='negative' ORDER BY id_tweet");
foreach ($resTweets as $infor)
{
	$tweetPrepros = $infor['preproses'];
	
    $extract = explode(" ", $tweetPrepros);
    $infopos = array_merge($infopos, $extract);

}

echo '<pre>';
//print_r($infopos);

//$kata   = explode(" ", $tweetPrepro);
$hasil  = count($infopos);
$data   = array_count_values($infopos);
		
foreach($data as $x => $x_value) {
    echo $x." : ".$x_value;
	
    echo "<br>";
	//$queryup1 =  mysqli_query($konek,"UPDATE tblterm SET cenNEG=$x_value WHERE term='$x'");
}

echo "<hr>";



$resTerm = mysqli_query($konek,"SELECT * FROM tblterm");
foreach ($resTerm as $resTerms)
{
	$termS = $resTerms['term'];
	$cendeS = $resTerms['cenderung'];
	$cenPOSs = $resTerms['cenPOS'];
	$cenNEGs = $resTerms['cenNEG'];
	$cenNETs = $resTerms['cenNET'];
$max = max($cenPOSs, $cenNEGs, $cenNETs);

if ($max == $cenPOSs) {
	$queryup4 =  mysqli_query($konek,"UPDATE tblterm SET cenderung='positive' WHERE term='$termS'");
}elseif ($max == $cenNEGs) {
	$queryup5 =  mysqli_query($konek,"UPDATE tblterm SET cenderung='negative' WHERE term='$termS'");
}
}

//echo '<pre>'; print_r($new); echo '</pre>';
		
//echo $ff;

/*
$fetch1 = mysqli_query($konek, "SELECT preproses FROM tbltweet WHERE kategori='neutral'");
    $fetch2 = mysqli_query($konek, "SELECT  term FROM tblterm");
    $netC=array();
    $jumlah=0;
    while ($data=mysqli_fetch_array($fetch1)) {
            $ftf=$data['preproses'];
            $op=explode(" ", $ftf);
     $typeTotals[] = COUNT($op);
     $typesum=array_count_values($typeTotals);

echo '<pre>'; print_r($typesum); echo '</pre>';
}


$ir = new IR();

echo "<p><b>Corpus:</b></p>";
$ir->show_docs($D);

$ir->create_index($D);

echo "<p><b>Inverted Index:</b></p>";
$ir->show_index();

$term = "film";  //kata asyik yang akan menjadi pusat perhitungan kita
$tf  = $ir->tf($term);
$ndw = $ir->ndw($term);
$idf = $ir->idf($term);
echo "<p>";
echo "Term Frequency of '$term' is $tf<br />";
echo "Number Of Documents with $term is $ndw.<br />";
echo "Inverse Document Frequency of $term is ".$idf;
echo "</p>";
*/
?>