<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
include '/var/www/html/mysql.connect.php';
include '/var/www/html/aws_signed_request2.php';

$sql = "SELECT `isbn` FROM `book` WHERE year > 2010";

if(!$books = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

while($book = $books->fetch_assoc()){
  //Read line from database
  $isbn = $book['isbn'];

  $params['Operation'] = 'ItemLookup';
  $params['SearchIndex'] = 'Books';
  $params['IdType'] = 'EAN';
  $params['ItemId'] = $isbn;
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
  $asin          = trim($book['ASIN']);
  $ean           = trim($book['ItemAttributes']['EAN']);
  $binding       = trim($book['ItemAttributes']['Binding']);
  $releaseDate   = trim($book['ItemAttributes']['ReleaseDate']);  
  $edition       = trim($book['ItemAttributes']['Edition']);
  $isbn          = trim($book['ItemAttributes']['ISBN']);
  $listPrice     = trim($book['ItemAttributes']['ListPrice']['FormattedPrice']);
  $numberOfPages = trim($book['ItemAttributes']["NumberOfPages"]);
  $productGroup  = trim($book['ItemAttributes']["ProductGroup"]);
  $publisher     = trim($book['ItemAttributes']['Publisher']);
  $title         = trim($book['ItemAttributes']["Title"]);

  $count = 1;
  if(!empty($array)){
    echo $count;
    $insert = $db->prepare("INSERT INTO `amazon`(`asin`, `ean`, `binding`, `edition`, `isbn`, `listPrice`, `numberOfPages`, `productGroup`, `publisher`, `title`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param('iissssisss', $asin, $ean, $binding, $edition, $isbn, $listPrice, $numberOfPages, $productGroup, $publisher, $title);
    echo "Alles ok!";
    $insert->execute();
    echo "Success!!! \n";
    } else { 
    echo "ISBN $isbn not inserted. \n";
    echo $count ."\n";
    $count++
    }
  }

/*$sql = "SELECT image.isbn\n"
    . "FROM image\n"
    . "INNER JOIN book ON book.isbn = image.isbn\n"
    . "WHERE book.year >2010 && `image`.`large_img` IS NULL ";

if(!$books = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

while($book = $books->fetch_assoc()){
  //Read line from database
  $isbn = $book['isbn'];

  //Fetch data from Amazon
  $params['Operation'] = 'ItemLookup';
  $params['SearchIndex'] = 'Books';
  $params['IdType'] = 'EAN';
  $params['ItemId'] = $isbn;
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

  if ($array){
    $bookData = $array['Items']['Item'];
  }
  
  //Save info to database
  if(isset($bookData['MediumImage']['URL'], $bookData['LargeImage']['URL'])){
    $MediumImage = trim($bookData['MediumImage']['URL']);
    print $MediumImage . "\n";
    $LargeImage = trim($bookData['LargeImage']['URL']);
    print $LargeImage . "\n";

    $insert = $db->prepare("UPDATE image SET `large_img` = (?), `small_img` = (?) WHERE `isbn` = (?)");

    $insert->bind_param('sss', $LargeImage, $MediumImage, $isbn);
    $insert->execute();
    echo "Success!!! \n";
  } else { 
    echo "Images for $isbn not inserted. \n";
  }
}
*/

// TODO: After ITeBooks API is reset
/*
include '/var/www/data/book.php';

for ($i=0; $i<=$book.count(), $i++){
  $id = $book['id'];
  $url = "http://it-ebooks-api.info/v1/book/$id";
  //Fetch data from web
  echo "calling id number $id \n";
  $handle = fopen($url, 'r') or die ("Can't open webpage.");
  $string = fgets($handle);
  $bookData = json_decode($string, true);
  
  //Save info to database
  if(!empty($bookData)){
    if(isset($bookData['Image'])){

        $Image = trim($bookData['Image']);
        echo $Image . "\n";

        $insert = $db->prepare("UPDATE `image` SET `med_img`= (?) WHERE `isbn` = (?)"); 

          if (!$insert){
            echo "Insert must be false.";
          }
          $insert->bind_param('ss', $Image, $book['isbn']);
          $insert->execute();
          echo "Success!!! \n";
          } else { 
            echo "Image for $id not inserted. \n";
          }
        }
      }  
*/
?>