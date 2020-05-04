<?php
class functbobot {

function buatindex($konek) {
		mysqli_query($konek ,"TRUNCATE TABLE tblterm");
		mysqli_query($konek ,"ALTER TABLE tblterm AUTO_INCREMENT = 1");

		//ambil semua berita (teks)
		$resTweet = mysqli_query($konek,"SELECT * FROM tbltweet ORDER BY id_tweet");
		$num_rows = mysqli_num_rows($resTweet);

		while($row = mysqli_fetch_array($resTweet)) {
			$id_tweet = $row['id_tweet'];
			$tweet = $row['tweet'];
			$tweetPrepro = $row['preproses'];
			 			
  			//simpan ke inverted index (tbindex)
  			$atweet = explode(" ", trim($tweetPrepro));

  			foreach ($atweet as $j => $value) {
				//hanya jika Term tidak null atau nil, tidak kosong
				if ($atweet[$j] != "") {

					//berapa baris hasil yang dikembalikan query tersebut?
					$rescount = mysqli_query($konek,"SELECT count FROM tblterm WHERE term = '$atweet[$j]'");
					$num_rows = mysqli_num_rows($rescount);

					//jika sudah ada idtweet dan Term tersebut	, naikkan Count (+1)
					if ($num_rows > 0) {
						$rowcount = mysqli_fetch_array($rescount);
						$count = $rowcount['count'];
						$count++;

						mysqli_query($konek,"UPDATE tblterm SET count = $count WHERE term = '$atweet[$j]'");

					}else {
						mysqli_query($konek,"INSERT INTO tblterm (term, count) VALUES ('$atweet[$j]', 1)");
						//mysqli_query($konek,"DELETE FROM tblterm WHERE term='dc'");
						//mysqli_query($konek,"DELETE FROM tblterm WHERE term='universe'");
						
					}
				} 
			} 
  		} 
} 
//----------------------------------------------------------------------

//----------------------------------------------------------------------
function hitungIDF($preproses, $vocab, $D){
  		$idf=array();
		foreach ($vocab as $kata) {
	    	$f=0;
	    	foreach ($preproses as $term) {
	        	$term=explode(" ", $term);
	        	if (in_array($kata, $term)) {
	            	$f++;
	        	}
	    	}
	    	$idf[]=log($D+1/$f);
		}
		return $idf;
	}
//----------------------------------------------------------------------
function hitungbobot($konek) {
	
	//berapa jumlah id_tweet total?, n
	$resn = mysqli_query($konek,"SELECT DISTINCT id_tweet FROM tbltweet");
	$n = mysqli_num_rows($resn);

	//ambil setiap record dalam tabel tbltweet
	//hitung bobot untuk setiap Term dalam setiap id_tweet
	 $queryDE=mysqli_query($konek,"SELECT * FROM tblterm");
    foreach ($queryDE as $forB) {
      $idB= $forB['id'];
      $idfB= $forB['idf'];
      $tfB= $forB['count'];


		$tfidf = log($tfB+1) * $idfB;

		//update bobot dari term tersebut
		$resUpdateBobot = mysqli_query($konek,"UPDATE tblterm SET bobot = $tfidf WHERE id = $idB");
	}
}    
  		

//----------------------------------------------------------------------
function informpos($konek){
			
$infopos = array();
$resTweets = mysqli_query($konek,"SELECT * FROM tbltweet WHERE kategori='positive' ORDER BY id_tweet");
foreach ($resTweets as $infor)
{
	$tweetPrepros = $infor['preproses'];
	
    $extract = explode(" ", $tweetPrepros);
    $infopos = array_merge($infopos, $extract);
}



//$kata   = explode(" ", $tweetPrepro);
$hasil  = count($infopos);
$datapos   = array_count_values($infopos);
		
foreach($datapos as $x => $x_value) {
  //  echo $x." : ".$x_value;
 $queryup1 =  mysqli_query($konek,"UPDATE tblterm SET cenPOS=$x_value WHERE term='$x'");
}
}
//----------------------------------------------------------------------
function informneg($konek){
			
$infoneg = array();
$resTweets = mysqli_query($konek,"SELECT * FROM tbltweet WHERE kategori='negative' ORDER BY id_tweet");
foreach ($resTweets as $infor)
{
	$tweetPrepros = $infor['preproses'];
	
    $extract = explode(" ", $tweetPrepros);
    $infoneg = array_merge($infoneg, $extract);
}




//$kata   = explode(" ", $tweetPrepro);
$hasil  = count($infoneg);
$dataneg   = array_count_values($infoneg);
		
foreach($dataneg as $x => $x_value) {
    //echo $x." : ".$x_value;
$queryup1 =  mysqli_query($konek,"UPDATE tblterm SET cenNEG=$x_value WHERE term='$x'");
}

}
//----------------------------------------------------------------------
function informnet($konek){
			
$infonet = array();
$resTweets = mysqli_query($konek,"SELECT * FROM tbltweet WHERE kategori='neutral' ORDER BY id_tweet");
foreach ($resTweets as $infor)
{
	$tweetPrepros = $infor['preproses'];
	
    $extract = explode(" ", $tweetPrepros);
    $infonet = array_merge($infonet, $extract);
}



//$kata   = explode(" ", $tweetPrepro);
$hasil  = count($infonet);
$datanet   = array_count_values($infonet);
		
foreach($datanet as $x => $x_value) {
   // echo $x." : ".$x_value;
   $queryup1 =  mysqli_query($konek,"UPDATE tblterm SET cenNET=$x_value WHERE term='$x'");
}
}
//----------------------------------------------------------------------
function cenderung($konek){
$cenTerm = mysqli_query($konek,"SELECT * FROM tblterm");
foreach ($cenTerm as $cenTerms)
{
	$termS = $cenTerms['term'];
	$cendeS = $cenTerms['cenderung'];
	$cenPOSs = $cenTerms['cenPOS'];
	$cenNEGs = $cenTerms['cenNEG'];
	//$cenNETs = $cenTerms['cenNET'];
$max = max($cenPOSs, $cenNEGs);

if ($max == $cenPOSs) {
	$queryup2 =  mysqli_query($konek,"UPDATE tblterm SET cenderung='positive' WHERE term='$termS'");
}elseif ($max == $cenNEGs) {
	$queryup3 =  mysqli_query($konek,"UPDATE tblterm SET cenderung='negative' WHERE term='$termS'");
}
}
}
//----------------------------------------------------------------------
}
?>
