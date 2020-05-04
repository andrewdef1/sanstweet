<?php
class functNB{

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
  $query_typo = mysqli_query($konek,"SELECT * FROM tbltypo WHERE typo = '".$kata_hasil."'"); 
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

//--------------------------------------------------------------------------------------------
function insertprepros($konek) {
		
		//include "../koneksi.php";

		//ambil semua tweet (teks)
		$resTweetP = mysqli_query($konek,"SELECT * FROM tbltweet ORDER BY id_tweet");

		while($row = mysqli_fetch_array($resTweetP)) {
			$id_tweet = $row['id_tweet'];
			$tweets = $row['tweet'];

			//terapkan preprocessing
  			$atweets = preproses($tweets);

		mysqli_query($konek,"UPDATE tbltweet SET preproses='$atweets' WHERE id_tweet = $id_tweet");
  		} //end while
  		
		
}
//----------------------------------------------------------------------

//----------------------------------------------------------------------

function bobotbayestraining($konek) {
		//include "../koneksi.php";
		
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
    		$pPosi = $posiCount / $totalCount; // (no of documents classified as spam / total no of documents)
			
    		//p(Negatif)
    		$pNeg = $negCount / $totalCount; // (no of documents classified as ham / total no of documents)

    		//p(Netral)
    		$pNeu = $netCount/$totalCount; // (no of documents classified as ham / total no of documents)
			


	$resVocab = mysqli_query($konek,"SELECT COUNT(DISTINCT term) as Vocab FROM tblterm");
	$rowVocab = mysqli_fetch_assoc($resVocab);
	$Vocab = $rowVocab['Vocab'];

//positif
		$resPos = mysqli_query($konek,"SELECT COUNT(kategori) as posi FROM tblterm WHERE kategori='positive'");
		$rowPos = mysqli_fetch_assoc($resPos);
		$Posi = $rowPos['posi'];

		$sqlPOS=mysqli_query($konek,"SELECT * FROM tblterm ");
    	
		foreach ($sqlPOS as $forPOS) {
		$posTERM = $forPOS['term'];
		$idPOSTERM = $forPOS['id'];
		$idtweetPOSTERM = $forPOS['id_tweet'];
		$katePOSTERM = $forPOS['kategori'];
		
		
		$sqlPOS_=mysqli_query($konek,"SELECT *, COALESCE(SUM(count),0) as total FROM tblterm where term='$posTERM' AND kategori = 'positive'");
		$wordCountPOS = mysqli_fetch_array($sqlPOS_);
		$wordCountPOS = $wordCountPOS['total'];
		
		if ($katePOSTERM=='negative') {
		$nbPOS = (0+1)/($Posi+$Vocab);
		$resUpdateNBPOS = mysqli_query($konek,"UPDATE tblterm SET pos = $nbPOS WHERE term='$posTERM' AND kategori='negative'");
		}elseif($katePOSTERM=='neutral') {
    	$nbPOS = (0+1)/($Posi+$Vocab);
    	$resUpdateNBPOS = mysqli_query($konek,"UPDATE tblterm SET pos = $nbPOS WHERE term='$posTERM' AND kategori='neutral'");
   		}elseif($katePOSTERM=='positive') {
    	$nbPOS = ($wordCountPOS+1)/($Posi+$Vocab);
    	$resUpdateNBPOS = mysqli_query($konek,"UPDATE tblterm SET pos = $nbPOS WHERE term='$posTERM' AND kategori='positive'");
   		 } 	 	
		}
		
		
//negatif
		$resNeg = mysqli_query($konek,"SELECT COUNT(kategori) as nega FROM tblterm WHERE kategori='negative'");
		$rowNeg = mysqli_fetch_assoc($resNeg);
		$Nega = $rowNeg['nega'];

		$sqlNEG=mysqli_query($konek,"SELECT * FROM tblterm ");
		
		foreach ($sqlNEG as $forNEG) {
		$negTERM = $forNEG['term'];
		$idNEGTERM = $forNEG['id'];
		$idtweetNEGTERM = $forNEG['id_tweet'];
		$kateNEGTERM = $forNEG['kategori'];
			
		$sqlNEG_=mysqli_query($konek,"SELECT *, COALESCE(SUM(count),0) as total FROM tblterm where term='$negTERM' AND kategori = 'negative'");
		$wordCountNEG = mysqli_fetch_array($sqlNEG_);
		$wordCountNEG = $wordCountNEG['total'];

		if ($kateNEGTERM=='positive') {
			$nbNEG = (0+1)/($Nega+$Vocab);
			$resUpdateNBNEG = mysqli_query($konek,"UPDATE tblterm SET neg = $nbNEG WHERE term='$negTERM' AND kategori='positive'");
		}elseif($kateNEGTERM=='neutral'){
			$nbNEG = (0+1)/($Nega+$Vocab);
			$resUpdateNBNEG = mysqli_query($konek,"UPDATE tblterm SET neg = $nbNEG WHERE term='$negTERM' AND kategori='neutral'");
		}elseif($kateNEGTERM=='negative'){
			$nbNEG = ($wordCountNEG+1)/($Nega+$Vocab);
			$resUpdateNBNEG = mysqli_query($konek,"UPDATE tblterm SET neg = $nbNEG WHERE term='$negTERM' AND kategori='negative'");
		}
		}
		
		
//netral
		$resNeu = mysqli_query($konek,"SELECT COUNT(kategori) as neut FROM tblterm WHERE kategori='neutral'");
		$rowNeu = mysqli_fetch_assoc($resNeu);
		$Neut = $rowNeu['neut'];
	
		$sqlNET=mysqli_query($konek,"SELECT * FROM tblterm ");
		
		foreach ($sqlNET as $forNET) {
		$netTERM = $forNET['term'];
		$idNETTERM = $forNET['id'];
		$idtweetNETTERM = $forNET['id_tweet'];
		$kateNETTERM = $forNET['kategori'];
		
		$sqlNET_=mysqli_query($konek,"SELECT *, COALESCE(SUM(count),0) as total FROM tblterm where term='$netTERM' AND kategori = 'neutral'");
		$wordCountNET = mysqli_fetch_array($sqlNET_);
		$wordCountNET = $wordCountNET['total'];
		
		if ($kateNETTERM=='positive') {
			$nbNET = (0+1)/($Neut+$Vocab);
			$resUpdateNBNET = mysqli_query($konek,"UPDATE tblterm SET neut = $nbNET WHERE term='$netTERM' AND kategori = 'positive'");
		}elseif($kateNETTERM=='negative'){
			$nbNET = (0+1)/($Neut+$Vocab);
			$resUpdateNBNET = mysqli_query($konek,"UPDATE tblterm SET neut = $nbNET WHERE term='$netTERM' AND kategori = 'negative'");
		}elseif($kateNETTERM=='neutral'){
			$nbNET = ($wordCountNET+1)/($Neut+$Vocab);
			$resUpdateNBNET = mysqli_query($konek,"UPDATE tblterm SET neut = $nbNET WHERE term='$netTERM' AND kategori = 'neutral'");
		}
		}
		
}

//----------------------------------------------------------------------
}
?>
