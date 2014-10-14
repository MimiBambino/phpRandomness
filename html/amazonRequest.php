<?php
include 'aws_signed_request.php';
include 'mysql.connect.php';
$sql = "SELECT `isbn` FROM `book` WHERE `year` > 2012\n";

if(!$books = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

while($book = $books->fetch_assoc()){
  $ISBN = $book['isbn'];


// i.e., ItemSearch, ItemLookup, SimilarityLookup, BrowseNodeLookup, CartAdd, 
// CartClear, CartCreate, CartGet, CartModify
  $public_key = 'AKIAJYEJUMMIADYKMK3A';
  $private_key = 'Lj+qXJFbvn6MEOPUq6RghKPNKFJVtJNKgU9A7cTx';
  $associate_tag = 'wwwmimibambin-20';
  $params['Operation'] = 'ItemLookup';
  $params['SearchIndex'] = 'Books';
  $params['IdType'] = 'EAN';
  $params['ItemId'] = $ISBN;
  $params['ResponseGroup'] = 'Images';

  // generate signed URL
  $request = aws_signed_request('com', $params, $public_key, $private_key, $associate_tag);

  // do request (you could also use curl etc.)
  $response = @file_get_contents($request);
  if ($response === FALSE) {
      echo "Request failed.\n";
  } else {
      $xml = simplexml_load_string($response);
      $json = json_encode($xml);
      $array = json_decode($json,TRUE);
  }

    if(!empty($array)){
      if(isset($array['Items']['Item']['MediumImage']['URL'], $array['Items']['Item']['LargeImage']['URL'])){

        $MediumImage = trim($array['Items']['Item']['MediumImage']['URL']);
        $LargeImage = trim($array['Items']['Item']['LargeImage']['URL']);

        if(!empty($MediumImage) && !empty($LargeImage)){

          $insert = $db->prepare("INSERT INTO amazon (isbn, large_img, small_img) VALUES (?, ?, ?)");
          $insert->bind_param('sss', $ISBN, $LargeImage, $MediumImage);
          $insert->execute();
          } else { 
            echo "at least one field in $i is empty<br>";
          }
        }
      }
    }
  fclose($file);  
?>