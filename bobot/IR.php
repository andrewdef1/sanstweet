<?php

/*
* Information Retrievel

*/

define("DOC_ID", 0);
define("TERM_POSITION", 1);

class IR {

public $num_docs = 0;

public $corpus_terms = array();
public $doc = array();


function show_docs($doc) {
$jumlah_doc = count($doc);
for($i=0; $i < $jumlah_doc; $i++) {
echo "Dokumen ke-$i : ".$doc[$i]."<br>";
}
}


/*
* Membuat  Index
*/
function create_index($D) {
$this->num_docs = count($D);
for($doc_num=0; $doc_num < $this->num_docs; $doc_num++) {

$doc_terms = array();
// simplified word tokenization process
$doc_terms = explode(" ", $D[$doc_num]);

$num_terms = count($doc_terms);
for($term_position=0; $term_position < $num_terms; $term_position++) {
$term = strtolower($doc_terms[$term_position]);
$this->corpus_terms[$term][]=array($doc_num, $term_position);
}
}
}

/*
* Show Index
*
*/
function show_index() {

ksort($this->corpus_terms);

foreach($this->corpus_terms AS $term => $doc_locations) {
echo "<b>".$term ."</b> ";
foreach($doc_locations AS $doc_location)
echo "{".$doc_location[DOC_ID].", ".$doc_location[TERM_POSITION]."} ";
echo count($doc_locations);
echo "<br />";
}
}

/*
* Menghitung Term Frequency (TF)
*

*/
function tf($term) {
$term = strtolower($term);
return count($this->corpus_terms[$term]);
}

/*
* Menghitung Number Documents With
*
*/
function ndw($term) {
$term = strtolower($term);
$doc_locations = $this->corpus_terms[$term];
$num_locations = count($doc_locations);
$docs_with_term = array();
for($doc_locations=0; $doc_locations < $num_locations; $doc_locations++){
$docs_with_term[]++;
}
return count($docs_with_term);
}

/*
* Menghitung Inverse Document Frequency (IDF)
*
*/
function idf($term) {
return log($this->num_docs)/$this->ndw($term);
}


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

}

function array_mesh() {
	// Combine multiple associative arrays and sum the values for any common keys
	// The function can accept any number of arrays as arguments
	// The values must be numeric or the summed value will be 0
	
	// Get the number of arguments being passed
	$numargs = func_num_args();
	
	// Save the arguments to an array
	$arg_list = func_get_args();
	
	// Create an array to hold the combined data
	$out = array();

	// Loop through each of the arguments
	for ($i = 0; $i < $numargs; $i++) {
		$in = $arg_list[$i]; // This will be equal to each array passed as an argument

		// Loop through each of the arrays passed as arguments
		foreach($in as $key => $value) {
			// If the same key exists in the $out array
			if(array_key_exists($key, $out)) {
				// Sum the values of the common key
				$sum = $in[$key] + $out[$key];
				// Add the key => value pair to array $out
				$out[$key] = $sum;
			}else{
				// Add to $out any key => value pairs in the $in array that did not have a match in $out
				$out[$key] = $in[$key];
			}
		}
	}
	
	return $out;
}
?>