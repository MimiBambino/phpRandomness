<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
include '/var/www/html/aws_signed_request2.php';

//$isbn = $_GET['isbn'];

// i.e., ItemSearch, ItemLookup, SimilarityLookup, BrowseNodeLookup, CartAdd, 
// CartClear, CartCreate, CartGet, CartModify

  $params['Operation'] = 'ItemLookup';
  $params['SearchIndex'] = 'Books';
  $params['IdType'] = 'EAN';
  //$params['ItemId'] = $isbn;  UNCOMMENT WHEN HOOKED BACK UP TO PinterestTheme.php
  $params['ItemId'] = 9781783280438; //REMOVE

  $params['ResponseGroup'] = 'ItemAttributes';

  // generate signed URL
  $request = aws_signed_request($params);

  // do request (you could also use curl etc.)
  $response = @file_get_contents($request);
  if ($response === FALSE) {
      echo "Request failed.\n";
  } else {
      $xml = simplexml_load_string($response);
      $json = json_encode($xml);
      $array = json_decode($json,TRUE);
  }

if ($array){
  $book = $array['Items']['Item'];
}

  //Interesting Attributes
  $asin          = $book['ASIN'];
  $author        = $book['ItemAttributes']['Author'];
  $binding       = $book['ItemAttributes']['Binding'];
  $ean           = $book['ItemAttributes']['EAN'];
  $releaseDate   = $book['ItemAttributes']['ReleaseDate'];  
  $edition       = $book['ItemAttributes']['Edition'];
  $isbn          = $book['ItemAttributes']['ISBN'];
  $listPrice     = $book['ItemAttributes']['ListPrice']['FormattedPrice'];
  $numberOfPages = $book['ItemAttributes']["NumberOfPages"];
  $productGroup  = $book['ItemAttributes']["ProductGroup"];
  $publisher     = $book['ItemAttributes']['Publisher'];
  $title         = $book['ItemAttributes']["Title"];

  print "ASIN:          " .$asin ."<br>";
  print "Author:        " .$author ."<br>";
  print "Binding:       " .$binding ."<br>";
  print "EAN:           " .$ean ."<br>";
  print "Release Date:  " .$releaseDate ."<br>";
  print "Edition:       " .$edition ."<br>";
  print "ISBN:          " .$isbn ."<br>";
  print "List Price:    " .$listPrice ."<br>";
  print "Pages:         " .$numberOfPages ."<br>";
  print "Product Group: " .$productGroup ."<br>";
  print "Publisher:     " .$publisher ."<br>";
  print "Title:         " .$title; 
?>