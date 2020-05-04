<?php

 // load library TwitterOAuth
 require_once __DIR__ . '/../vendor/autoload.php';
 use Abraham\TwitterOAuth\TwitterOAuth;

 class functest {

function crawltesting($konek) {
mysqli_query($konek ,"TRUNCATE TABLE tbltesting");
 // menentukan keyword yang akan di cari
 $keyword = 'DC Universe';

 // ganti dengan API twitter anda
 $key = 'SzoT7vexriXbp4nx60ojbhnGn';
 $secret_key = 'XfIhJinubF7Wbt5gYPXxnfJw0dY8Xsl6NkBEnrYRzfVpWGKtQI';
 $token = '1039077944222609409-XC8rNwZbNSKUyeldGR9NofYAGtQarW';
 $secret_token = 'fKsv39pIzMKAcRvxrQAqUh1QKaYZVwsKpSYntrgHaV8kI';

 // membuka koneksi
 $conn = new TwitterOAuth($key, $secret_key, $token, $secret_token);

 // menagmbil tweet berdasarkan keyword yang di tentukan
 // anda bisa merubah jumlah tweet yang akan di tampilkanb dengan merubah angka pada count
 $tweets = $conn->get('search/tweets', array('q'=>$keyword, 'count'=>1500, 'lang'=>'in', 'tweet_mode'=>'extended'));


 // menampilkan hasil keyword yang di tentukan
 //
 foreach ($tweets->statuses as $tweet) {
  $str_id = $tweet->id_str;
  $user = $tweet->user->screen_name;
  $text = $tweet->full_text;
  $date = date('Y-m-d', strtotime($tweet->created_at));
  
  
$queryinsttest =  mysqli_query($konek,"INSERT INTO tbltesting (tweet, tanggal) VALUES ('$text', '$date')");
  //echo '</strong>'.$date.'</strong>< br />';
  //echo '<strong>'.$user.'</strong> : '.$text.'< br /><hr />< br />';
 }
}
//----------------------------------------------------------------------

function klasifikasi_sentimen($konek) {  

 		    $sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltweet WHERE  kategori = 'positive' ");
    		$posiCount = mysqli_fetch_assoc($sql);
    		$posiCount = $posiCount['total'];

    		$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltweet WHERE  kategori = 'negative' ");
    		$negCount = mysqli_fetch_assoc($sql);
    		$negCount = $negCount['total'];

    		$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltweet WHERE  kategori = 'neutral' ");
    		$netCount = mysqli_fetch_assoc($sql);
    		$netCount = $netCount['total'];
			
			$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltweet");
    		$totalCount = mysqli_fetch_assoc($sql);
    		$totalCount = $totalCount['total'];

    		//p(Positif)
    		$pPosi = $posiCount / $totalCount; 
			//p(Negatif)
    		$pNeg = $negCount / $totalCount; 
    		//p(Netral)
    		$pNeu = $netCount/$totalCount; 

	$query_dok = mysqli_query($konek,"SELECT id_tweet,  preproses FROM  tbltesting ORDER BY id_tweet"); 
	while ($row_dok = mysqli_fetch_array($query_dok)) {     
		$prob_kata_positif = []; $prob_kata_negatif = [];  $prob_kata_netral= [];    
		$kata_dok = $row_dok['preproses'];     
		$id_dok = $row_dok['id_tweet'];     
		$kata_hasil = explode(" ", $kata_dok); 
		foreach ($kata_hasil as $key) {       
			$query_bobot_kata = mysqli_query($konek,"SELECT id,        
				term, pos, neg, neut FROM        
				tblterm WHERE term = '".$key."'"); 
			while ($row_kata = mysqli_fetch_array($query_bobot_kata)) { 
				if ($key == $row_kata['term']) {           
					$prob_kata_positif[$key] =  round($row_kata['pos'], 8);           
					$prob_kata_negatif[$key] =  round($row_kata['neg'], 8);         
					$prob_kata_netral[$key] =  round($row_kata['neut'], 8);         
				} else {           
					$prob_kata_positif[$key] = 1; $prob_kata_negatif[$key] = 1;  $prob_kata_netral[$key] = 1;         
				}     
				
			}     
		}     
		$prob_dokumen_positif = $pPosi; 
		foreach ($prob_kata_positif as $kata_prob => $value) {       
			$prob_dokumen_positif *= $value;     
		}     
		$prob_dokumen_negatif = $pNeg; 
		foreach ($prob_kata_negatif as $kata_prob => $value) {       
			$prob_dokumen_negatif *= $value;     
		} 
		$prob_dokumen_netral = $pNeu; 
		foreach ($prob_kata_netral as $kata_prob => $value) {       
			$prob_dokumen_netral *= $value;     
		} 

		$queryup1 =  mysqli_query($konek,"UPDATE tbltesting SET pos='".$prob_dokumen_positif."' WHERE id_tweet=$id_dok");  
		$queryup2 =  mysqli_query($konek,"UPDATE tbltesting SET neg='".$prob_dokumen_negatif."' WHERE id_tweet=$id_dok");  
		$queryup3 =  mysqli_query($konek,"UPDATE tbltesting SET net='".$prob_dokumen_netral."' WHERE id_tweet=$id_dok");  

$max = max($prob_dokumen_positif, $prob_dokumen_negatif, $prob_dokumen_netral);

if ($max == $prob_dokumen_positif) {
	$queryup4 =  mysqli_query($konek,"UPDATE tbltesting SET predicted='positive' WHERE id_tweet=$id_dok");
}elseif ($max == $prob_dokumen_negatif) {
	$queryup5 =  mysqli_query($konek,"UPDATE tbltesting SET predicted='negative' WHERE id_tweet=$id_dok");
}elseif($max == $prob_dokumen_netral){
	$queryup6 =  mysqli_query($konek,"UPDATE tbltesting SET predicted='neutral' WHERE id_tweet=$id_dok");
	}  
} 

}




}
?>