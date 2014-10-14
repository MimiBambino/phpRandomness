<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
include 'aws_signed_request2.php';
include 'mysql.connect.php';

$sql = "SELECT `isbn` FROM `image` WHERE `large_img` IS NULL";

if(!$books = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

while($book = $books->fetch_assoc()){
  $ISBN = $book['isbn'];


// i.e., ItemSearch, ItemLookup, SimilarityLookup, BrowseNodeLookup, CartAdd, 
// CartClear, CartCreate, CartGet, CartModify

  $params['Operation'] = 'ItemLookup';
  $params['SearchIndex'] = 'Books';
  $params['IdType'] = 'EAN';
  $params['ItemId'] = $ISBN;
  $params['ResponseGroup'] = 'Images';

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
///// INSERT INTO DATABASE

    if(!empty($array)){
      if(isset($array['Items']['Item']['MediumImage']['URL'], $array['Items']['Item']['LargeImage']['URL'])){

        $MediumImage = trim($array['Items']['Item']['MediumImage']['URL']);
        $LargeImage = trim($array['Items']['Item']['LargeImage']['URL']);

        if(!empty($MediumImage) && !empty($LargeImage)){

          $insert = $db->prepare("INSERT INTO image (large_img, small_img) VALUES (?, ?)");
          $insert->bind_param('ss', $LargeImage, $MediumImage);
          $insert->execute();
          echo "one row updated";
        } else { 
            echo "at least one field in $i is empty<br>";
        }
      }
    }
  }
  //fclose($file);  
?>