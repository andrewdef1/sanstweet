<?php
class functNB{

//----------------------------------------------------------------------

function get_jml_kata_positif($konek){
	$fetch1 = mysqli_query($konek, "SELECT preproses FROM tbltweet WHERE kategori='positive'");

	while ($data=mysqli_fetch_array($fetch1)) {
            $ftf=$data['preproses'];
            $op=explode(" ", $ftf);
     $poscount[] = count($op);
     $possum=array_sum($poscount);
    
}
return $possum;
}

function get_jml_kata_negative($konek){
	$fetch1 = mysqli_query($konek, "SELECT preproses FROM tbltweet WHERE kategori='negative'");

	while ($data=mysqli_fetch_array($fetch1)) {
            $ftf=$data['preproses'];
            $op=explode(" ", $ftf);
     $negcount[] = count($op);
     $negsum=array_sum($negcount);
    
}
return $negsum;
}

function get_jml_kata_neutral($konek){
	$fetch1 = mysqli_query($konek, "SELECT preproses FROM tbltweet WHERE kategori='neutral'");

	while ($data=mysqli_fetch_array($fetch1)) {
            $ftf=$data['preproses'];
            $op=explode(" ", $ftf);
     $netcount[] = count($op);
     $netsum=array_sum($netcount);
    
}
return $netsum;
}


function set_probabilitas_kata_positif($konek) {   


	$resVocab = mysqli_query($konek,"SELECT COUNT(DISTINCT term) as Vocab FROM tblterm");
	$rowVocab = mysqli_fetch_array($resVocab);
	$Vocab = $rowVocab['Vocab'];

	$query = mysqli_query($konek,"SELECT id, term FROM    
		tblterm ORDER BY id ASC"); 
	while ($row_kata = mysqli_fetch_array($query)) {     
		$ni = 0;     
		$n = $this->get_jml_kata_positif($konek);   
		$kosakata = $Vocab;    
		$query_dokumen = mysqli_query($konek,"SELECT id_tweet, preproses FROM tbltweet WHERE kategori =      
			'positive' ORDER BY id_tweet ASC"); 
		while ($row_dok = mysqli_fetch_array($query_dokumen)) {       
			$kata_dok = explode(" ",$row_dok['preproses']);       
			foreach ($kata_dok as $key) { 
				if ($row_kata['term'] == $key) {           
				$ni += 1;         
			}  }     
		}     
		$probabilitas_p = ($ni+1)/($n+$kosakata);     
		$query_simpan = mysqli_query($konek,"UPDATE tblterm SET pos = $probabilitas_p WHERE    
			id = ".$row_kata['id']."");   
	} 
}

function set_probabilitas_kata_negatif($konek) {   


	$resVocab = mysqli_query($konek,"SELECT COUNT(DISTINCT term) as Vocab FROM tblterm");
	$rowVocab = mysqli_fetch_array($resVocab);
	$Vocab = $rowVocab['Vocab'];

	$query = mysqli_query($konek,"SELECT id, term FROM    
		tblterm ORDER BY id ASC"); 
	while ($row_kata = mysqli_fetch_array($query)) {     
		$ni = 0;     
		$n = $this->get_jml_kata_negative($konek);   
		$kosakata = $Vocab;    
		$query_dokumen = mysqli_query($konek,"SELECT id_tweet, preproses FROM tbltweet WHERE kategori =      
			'negative' ORDER BY id_tweet ASC"); 
		while ($row_dok = mysqli_fetch_array($query_dokumen)) {       
			$kata_dok = explode(" ",$row_dok['preproses']);       
			foreach ($kata_dok as $key) { 
				if ($row_kata['term'] == $key) {           
				$ni += 1;         
			}  }     
		}     
		$probabilitas_n = ($ni+1)/($n+$kosakata);     
		$query_simpan = mysqli_query($konek,"UPDATE tblterm SET neg = $probabilitas_n WHERE    
			id = ".$row_kata['id']."");   
	} 
}


function set_probabilitas_kata_netral($konek) {   


	$resVocab = mysqli_query($konek,"SELECT COUNT(DISTINCT term) as Vocab FROM tblterm");
	$rowVocab = mysqli_fetch_array($resVocab);
	$Vocab = $rowVocab['Vocab'];

	$query = mysqli_query($konek,"SELECT id, term FROM    
		tblterm ORDER BY id ASC"); 
	while ($row_kata = mysqli_fetch_array($query)) {     
		$ni = 0;     
		$n = $this->get_jml_kata_neutral($konek);   
		$kosakata = $Vocab;    
		$query_dokumen = mysqli_query($konek,"SELECT id_tweet, preproses FROM tbltweet WHERE kategori =      
			'neutral' ORDER BY id_tweet ASC"); 
		while ($row_dok = mysqli_fetch_array($query_dokumen)) {       
			$kata_dok = explode(" ",$row_dok['preproses']);       
			foreach ($kata_dok as $key) { 
				if ($row_kata['term'] == $key) {           
				$ni += 1;         
			}  }     
		}     
		$probabilitas_nn = ($ni+1)/($n+$kosakata);     
		$query_simpan = mysqli_query($konek,"UPDATE tblterm SET neut = $probabilitas_nn WHERE    
			id = ".$row_kata['id']."");   
	} 
}

//----------------------------------------------------------------------
}
?>
