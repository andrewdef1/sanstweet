<?php
function preproses($teks) {
include "../koneksi.php";
  require_once __DIR__ . '/../vendor/autoload.php';
  $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
  $stemmer  = $stemmerFactory->createStemmer();


  //ubah ke huruf kecil
  $teks = strtolower(trim($teks));
  //end casefolding

	
 $teks = str_replace('dc universe', 'dcuniverse', $teks);
	
  
  /*start cleansing*/
    $teks = explode(' ', $teks);   
    $tweet_hasil = []; 
    foreach ($teks as $tweet_kata) { 
      if ($teks = preg_match('/pic.twitter.com/', $tweet_kata)) {       
        $tweet_kata = "";     
        } elseif ($teks = preg_match('/twitter.com/', $tweet_kata)) {       
        $tweet_kata = "";  
        } elseif ($teks = preg_match('/youtu.be/', $tweet_kata)) {       
        $tweet_kata = ""; 
        } elseif ($teks = preg_match('/t.co/', $tweet_kata)) {       
        $tweet_kata = "";  
        } elseif ($teks = preg_match('/youtube.com/', $tweet_kata)) {       
        $tweet_kata = "";   
        } elseif ($teks = preg_match('/facebook.com/', $tweet_kata)) {       
        $tweet_kata = "";   
        } elseif ($teks = preg_match('/bumilangit.com/', $tweet_kata)) {       
        $tweet_kata = "";     
        } elseif ($teks = preg_match('/instagram.com/', $tweet_kata)) {       
        $tweet_kata = "";  
        } elseif ($teks = preg_match('/antaranews.com/', $tweet_kata)) {       
        $tweet_kata = "";    
        } elseif ($teks = preg_match('/kompas.com/', $tweet_kata)) {       
        $tweet_kata = "";  
        } elseif ($teks = preg_match('/dlvr.it/', $tweet_kata)) {       
        $tweet_kata = ""; 
        } elseif ($teks = preg_match('/vice.com/', $tweet_kata)) {       
        $tweet_kata = ""; 
        } elseif ($teks = preg_match('/tvline.com/', $tweet_kata)) {       
        $tweet_kata = ""; 
        } elseif ($teks = preg_match('/urbanasia.com/', $tweet_kata)) {       
        $tweet_kata = ""; 
        } elseif ($teks = preg_match('/ezdlc.com/', $tweet_kata)) {       
        $tweet_kata = ""; 
        } elseif ($teks = preg_match('/businessinsider.com/', $tweet_kata)) {       
        $tweet_kata = ""; 
        } elseif ($teks = preg_match('/tribunnews.com/', $tweet_kata)) {       
        $tweet_kata = ""; 
        } elseif ($teks = preg_match('/worldofhero.com/', $tweet_kata)) {       
        $tweet_kata = ""; 
        } elseif ($teks = preg_match('/mediaindonesia.com/', $tweet_kata)) {       
        $tweet_kata = ""; 
        } elseif ($teks = preg_match('/ow.ly/', $tweet_kata)) {       
        $tweet_kata = ""; 
		} elseif ($teks = preg_match('/asdpoi.com/', $tweet_kata)) {       
        $tweet_kata = ""; 
      } else {       
          array_push($tweet_hasil, $tweet_kata);     
        }
      }
  $teks = implode(' ', $tweet_hasil); 
  $teks = strip_tags($teks);
  $teks = preg_replace('@<(\w+)\b.*?>.*?</\1>@si', ' ', $teks);
  $teks = str_replace(array('0','1','2','3','4','5','6','7','8','9','(','-',')',',','.','=',';','!','?'), ' ', $teks);
  $teks = preg_replace('/@[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i',' ', $teks);   
  $teks = preg_replace('/#[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i',' ', $teks);   
  $teks = preg_replace('/\b(https?|ftp|file|http):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i',' ', $teks);   
  $teks = preg_replace('/rt | â€¦/i', ' ', $teks); 
  /*end cleansing*/
  
  //convert emoji
  $teks = str_replace("☹️", "emotsedih", $teks);
  $teks = str_replace("😀", "emotsenang", $teks);           
  $teks = str_replace("🙂", "emotsenang", $teks); 
  $teks = str_replace("😄", "emotsenang", $teks); 
  $teks = str_replace("😭", "emotsedih", $teks); 
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
  $query_singkat = mysqli_query($konek,"SELECT * FROM tblsingkatan WHERE singkat = '".$kata_hasil."'"); 
  if ($row = mysqli_fetch_array($query_singkat)) {       
  $kata_tweet[$i] = $row[2];   
  } 
  $query_slang= mysqli_query($konek,"SELECT * FROM tblslang WHERE slang = '".$kata_hasil."'"); 
  if ($row = mysqli_fetch_array($query_slang)) {       
  $kata_tweet[$i] = $row[2];   
  }
  $query_baku = mysqli_query($konek,"SELECT * FROM tblbaku WHERE nonbaku = '".$kata_hasil."'"); 
  if ($row = mysqli_fetch_array($query_baku)) {       
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
   $stoplist = array();   
   $query = mysqli_query($konek,"SELECT * FROM tblstopword"); 
   while ($key = mysqli_fetch_array($query)) { 
	$stoplist[]= $key['stopword'];   
 }   
 $teks = preg_replace('/\b('.implode('|',$stoplist).')\b/','',$teks); 

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
?>