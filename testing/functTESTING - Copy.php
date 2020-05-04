<?php
 // load library TwitterOAuth
 require_once __DIR__ . '/../vendor/autoload.php';
 use Abraham\TwitterOAuth\TwitterOAuth;
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
function preproses($teks) {
	include "../koneksi.php";
	require_once __DIR__ . '/../vendor/autoload.php';
 $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();


  //ubah ke huruf kecil
  $teks = strtolower(trim($teks));
  //end casefolding


  /*start cleansing*/
  $teks = strip_tags($teks);
  $teks = preg_replace('@<(\w+)\b.*?>.*?</\1>@si', ' ', $teks);
  $teks = str_replace(array('0','1','2','3','4','5','6','7','8','9','(','-',')',',','.','=',';','!','?'), ' ', $teks);
  $teks = rtrim(preg_replace('/@([\w-]+)/i', ' ', $teks)); // @mention
  $teks = rtrim(preg_replace('/#[^\\s]+\\s?/', ' ', $teks) ); // #tag
  $urlRegex = '~(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|twitter|pic|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))~';
  $teks = rtrim(preg_replace($urlRegex, ' ', $teks)); // remove urls
  /*end cleansing*/
  
  //convert emoticon
  $teks = str_replace("☹️", "emotsedih", $teks);
  $teks = str_replace("😀", "emotsenang", $teks);           
  $teks = str_replace("🙂", "emotsenang", $teks); 
  $teks = str_replace(":)", "emotsenang", $teks); 
  $teks = str_replace(":(", "emotsedih", $teks); 
  $teks = str_replace("😄", "emotsenang", $teks); 
  $teks = str_replace("😭", "emotsedih", $teks); 
  $teks = str_replace(":')", "emotsedih", $teks); 
  $teks = str_replace("😊", "emotsenang", $teks); 
  $teks = str_replace("😱", "emotkaget", $teks); 
  $teks = str_replace("☺", "emotsenang", $teks); 
  $teks = str_replace(":'\'", "emotsedih", $teks); 

  //convert negasi
$list = array( 'gak ' => 'gak', 'ga ' => 'ga', 'ngga ' => 'ngga',     
'tidak '  => 'tidak', 'bkn '=>'bkn', 'tida '=>'tida', 'tak '=>'tak',     
'jangan '=>'jangan', 'enggak '=>'enggak', 'gak  ' => 'gak',      
'ga  ' => 'ga', 'ngga  ' => 'ngga', 'tidak  '  => 'tidak',  
'bkn  '=>'bkn', 'tida  '=>'tida', 'tak  '=>'tak',  
'jangan  '=>'jangan', 'enggak  '=>'enggak'   
);   
$patterns = array();   
$replacement = array(); 
foreach ($list as $from => $to) { 
    $from = '/\b' . $from . '\b/';     
	$patterns[] = $from;     
	$replacement[] = $to;   
	}
	 $teks = preg_replace($patterns, $replacement, $teks); 
  
  
  /*start of tokenizing*/
   $teks = stripcslashes($teks);   
  $teks = trim($teks);   
  $teks = explode(" ",$teks); 

 
  //nomalize
  $kata_tweet = $teks;  
  $i = 0; 
  foreach ($kata_tweet as $kata_hasil) {  
    $query_typo= mysqli_query($konek,"SELECT * FROM tbltypo WHERE typo = '".$kata_hasil."'"); 
  if ($row = mysqli_fetch_array($query_typo)) {       
  $kata_tweet[$i] = $row[2];   
  }    
  $query_singkat = mysqli_query($konek,"SELECT * FROM tblslang WHERE slang = '".$kata_hasil."'"); 
  if ($row = mysqli_fetch_array($query_singkat)) {       
  $kata_tweet[$i] = $row[2];   
  }    
  $query_inggris = mysqli_query($konek,"SELECT * FROM tblkamus WHERE inggris = '".$kata_hasil."'"); 
  if ($row = mysqli_fetch_array($query_inggris)) {  
  $kata_tweet[$i] = $row[2]; 
  }   
  $i++; 
  } 
  $kata = implode(' ', $kata_tweet);
$teks= $kata;  

     
  //stopword
   $file = file_get_contents('stopwords.txt', FILE_USE_INCLUDE_PATH);
  $stopword = explode("\n", $file);

  $teks = preg_replace('/\b('.implode('|',$stopword).')\b/','',$teks);

//stemming
$teks = $stemmer->stem($teks);


return $teks;


} //end function preproses
//----------------------------------------------------------------------
function insertprepros($konek) {
		
		//include "../koneksi.php";

		//ambil semua tweet (teks)
		$resTweetP = mysqli_query($konek,"SELECT * FROM tbltesting ORDER BY id_tweet");

		while($row = mysqli_fetch_array($resTweetP)) {
			$id_tweet = $row['id_tweet'];
			$tweets = $row['tweet'];

			//terapkan preprocessing
  			$atweets = preproses($tweets);

		mysqli_query($konek,"UPDATE tbltesting SET preproses='$atweets' WHERE id_tweet = $id_tweet");
  		} //end while
  		
		
}
//----------------------------------------------------------------------
//----------------------------------------------------------------------
function termKlasifikasi($konek) {
		mysqli_query($konek ,"TRUNCATE TABLE tbltestterm");
		mysqli_query($konek ,"ALTER TABLE tbltestterm AUTO_INCREMENT=1");
		
		//ambil semua tweet (teks)
		$resTweetP = mysqli_query($konek,"SELECT * FROM tbltesting ORDER BY id_tweet");

		while($row = mysqli_fetch_array($resTweetP)) {
			$id_tweet = $row['id_tweet'];
			$tweet = $row['tweet'];
			$tweetPrepro = $row['preproses'];
  			
  			//simpan ke inverted index (tbindex)
  			$atweet = explode(" ", trim($tweetPrepro));

  			foreach ($atweet as $j => $value) {
				
		$respriTerm = mysqli_query($konek,"SELECT id, term, MAX(bobot) FROM tblterm GROUP BY term");
		while($rowpriTerm = mysqli_fetch_array($respriTerm)){
		$priTerm = $rowpriTerm['term'];
						
						//seleksi term dengan bobot yang tinggi dari data training
						if ($atweet[$j] == $priTerm) {
						mysqli_query($konek,"INSERT INTO tbltestterm (term, id_tweet) VALUES ('$atweet[$j]', $id_tweet)");
						
						}
					}
				} //end if
			 //end foreach
  		}
	
	/*	$resUpdate0= mysqli_query($konek,"UPDATE tbltestterm t JOIN tblterm p ON p.term = t.term SET t.pos = p.term 
			WHERE t.term LIKE '&' = p.term AND t.bobot = (select max(p.max) 
			FROM tbltesting p where p.term = t.term)");*/

		$resUpdate1= mysqli_query($konek,"UPDATE tbltestterm b 
INNER JOIN (SELECT
         id,pos,neg,neut,term,
         MAX(bobot) bobot
      FROM
         tblterm 
      GROUP BY id ORDER BY bobot ASC
     ) p
ON b.term = p.term 
SET b.pos = p.pos , b.neg = p.neg , b.neut = p.neut 
WHERE b.term LIKE '&' = p.term");
/*
		$resUpdate2= mysqli_query($konek,"UPDATE tbltestterm b 
JOIN (SELECT
         id,neg,term,
         MAX(bobot) bobot
      FROM
         tblterm 
      GROUP BY id
     ) p
ON b.term = p.term 
SET b.neg = p.neg 
WHERE b.term LIKE '&' = p.term");

		$resUpdate3= mysqli_query($konek,"UPDATE tbltestterm b 
JOIN (SELECT
         id,neut,term,
         MAX(bobot) bobot
      FROM
         tblterm 
      GROUP BY id
     ) p
ON b.term = p.term 
SET b.neut = p.neut 
WHERE b.term LIKE '&' = p.term"); */
}
//----------------------------------------------------------------------
function predictKlasifikasi($konek) {
			$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltweet WHERE  kategori = 'positive' ");
    		$posiCount = mysqli_fetch_assoc($sql);
    		$posiCount = $posiCount['total'];

    		$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltweet WHERE  kategori = 'negative' ");
    		$negCount = mysqli_fetch_assoc($sql);
    		$negCount = $negCount['total'];

    		$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltweet WHERE  kategori = 'neutral' ");
    		$netCount = mysqli_fetch_assoc($sql);
    		$netCount = $netCount['total'];
			
			$sql = mysqli_query($konek, "SELECT count(*) as total FROM tbltweet ");
    		$totalCount = mysqli_fetch_assoc($sql);
    		$totalCount = $totalCount['total'];

    		//p(Positif)
    		$pPosi = $posiCount / $totalCount; // (no of documents classified as spam / total no of documents)
			
    		//p(Negatif)
    		$pNeg = $negCount / $totalCount; // (no of documents classified as ham / total no of documents)

    		//p(Netral)
    		$pNeu = $netCount/$totalCount; // (no of documents classified as ham / total no of documents)

//predicted POS
	
		$sqlPOSPRE=mysqli_query($konek,"SELECT * FROM tbltestterm");
		
		foreach ($sqlPOSPRE as $forPOSPRE) {
		$negPORPRE = $forPOSPRE['term'];
		$idPOSPRE = $forPOSPRE['id'];
		$idtweetPOSPRE = $forPOSPRE['id_tweet'];
		$kateposNETPRE = $forPOSPRE['neut'];
		$kateposNEGPRE = $forPOSPRE['neg'];
		$kateposPOSPRE = $forPOSPRE['pos'];
 
		
		$sqlPOSPREh_=mysqli_query($konek,"SELECT *, $pPosi * EXP(SUM(LOG(pos))) as total FROM tbltestterm where id_tweet=$idtweetPOSPRE");
		$wordCountPOSPREh = mysqli_fetch_array($sqlPOSPREh_);
		$wordCountPOSPREh= $wordCountPOSPREh['total'];

		$nbPOSPREh = $wordCountPOSPREh;
		//echo"<br>"; echo $nbPOSPREh;
		$queryup =  mysqli_query($konek,"UPDATE tbltesting SET pos=$nbPOSPREh WHERE id_tweet = $idtweetPOSPRE");
		
		}
				

//predicted NEG
	
		$sqlNEGPRE=mysqli_query($konek,"SELECT * FROM tbltestterm");
		
		foreach ($sqlNEGPRE as $forNEGPRE) {
		$negNEGPRE = $forNEGPRE['term'];
		$idNEGPRE = $forNEGPRE['id'];
		$idtweetNEGPRE = $forNEGPRE['id_tweet'];
		$katenegNETPRE = $forNEGPRE['neut'];
		$katenegNEGPRE = $forNEGPRE['neg'];
		$katenegPOSPRE = $forNEGPRE['pos'];
		
		$sqlNEGPREh_=mysqli_query($konek,"SELECT *, $pNeg * EXP(SUM(LOG(neg))) as total FROM tbltestterm where id_tweet=$idtweetNEGPRE");
		$wordCountNEGPREh = mysqli_fetch_array($sqlNEGPREh_);
		$wordCountNEGPREh= $wordCountNEGPREh['total'];
		
		$nbNEGPREh = $wordCountNEGPREh;
		//echo"<br>"; echo $nbNEGPREh;
		$queryup =  mysqli_query($konek,"UPDATE tbltesting SET neg=$nbNEGPREh WHERE id_tweet = $idtweetNEGPRE");
		}
		
//predicted NET
	
		$sqlNETPRE=mysqli_query($konek,"SELECT * FROM tbltestterm");
		
		foreach ($sqlNETPRE as $forNETPRE) {
		$netNETPRE = $forNETPRE['term'];
		$idNETPRE = $forNETPRE['id'];
		$idtweetNETPRE = $forNETPRE['id_tweet'];
		$katenetNETPRE = $forNETPRE['neut'];
		$katenetNEGPRE = $forNETPRE['neg'];
		$katenetPOSPRE = $forNETPRE['pos'];

		$sqlNETPREh_=mysqli_query($konek,"SELECT *, $pNeu * EXP(SUM(LOG(neut))) as total FROM tbltestterm where id_tweet=$idtweetNETPRE");
		$wordCountNETPREh = mysqli_fetch_array($sqlNETPREh_);
		$wordCountNETPREh= $wordCountNETPREh['total'];
		
		$nbNETPREh = $wordCountNETPREh;
		//echo"<br>"; echo $nbNETPREh;
		$queryup =  mysqli_query($konek,"UPDATE tbltesting SET net=$nbNETPREh WHERE id_tweet = $idtweetNETPRE");
		}

		
		// INSERT PREDICT TO DB
$objects = array();
$objects = mysqli_query($konek,"SELECT * FROM tbltesting");
 
foreach ($objects as $data) {
		$idtweetobj = $data['id_tweet'];
		$katenetobj = $data['net'];
		$katenegobj = $data['neg'];
		$kateposobj = $data['pos'];
		$katepreobj = $data['predicted'];
		

	//$string=implode(', ', $data);
	//echo "<tr><td>". $string ."</td></tr>";
	//$max = max($katenetobj, $katenegobj, $kateposobj);

	$max = array_search(max($katenetobj, $katenegobj, $kateposobj),$data);
//	echo '<br> Nilai Terbesar dari array diatas ialah ' . $max;
	//array_search($max, $data);
	//$class = array_search($max, $data);
	//echo " ".$max;
if ($max == 'pos') {
	$queryup =  mysqli_query($konek,"UPDATE tbltesting SET predicted='positive' WHERE id_tweet=$idtweetobj");
}elseif ($max == 'neg') {
	$queryup =  mysqli_query($konek,"UPDATE tbltesting SET predicted='negative' WHERE id_tweet=$idtweetobj");
}elseif ($max == 'net') {
	$queryup =  mysqli_query($konek,"UPDATE tbltesting SET predicted='neutral' WHERE id_tweet=$idtweetobj");
}
//echo " The array in largest number :".$max."<br/>";
	//$queryup =  mysqli_query($konek,"UPDATE tbltweet SET predicted=$max WHERE id_tweet=$idtweetobj");

	}
	


}
?>