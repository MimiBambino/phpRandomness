 <?php

function ApiPage($term, $num){
//$term is a search term for an IT topic
//$num is the estimated number of books to be appended
//Call page of results from ITeBooks
  $url = "http://it-ebooks-api.info/v1/search/$term/page/$num";
  echo "Calling $url<br>";
  $handle = fopen($url, 'r') or die ("Can't open webpage.");
  $string = fgets($handle);
  $dataArray = json_decode($string, true);
  $Books = $dataArray['Books'];
  fclose($handle);
  return $Books;
}

function AppendBooks($term, $num, $file){
  //$term is a search term for an IT topic
  //$num is the estimated number of books to be appended
  //$file is the file to which the results will be appended
    for ($i = 1; $i < $num; $i+=8){
    echo "call number $i <br>";
    $Books = ApiPage($term, $i);
  //Add Book IDs to $file one page at a time
  ////***The ITeBooks API says each page is limited to 10 books, but each
  ////call seems to return only one new book and 9 from the previous call.***
    for ($j=0; $j<10; $j++){  
      echo "writing number $j";
      $handle = fopen($file, 'a') or die ("Can't open file.");
      fwrite($handle, $Books[$j]['ID']. "\n");
    }
    fclose($handle);
  }
  //Use command line `sudo sort -u [$file] > [newfile]` to remove 
  //repeated book IDs.
}

function InsertBooks($file, $term, $num){
  //Read $id line by line from $file and call API for book info, 
  //$Subject is the same as $term in AppendToBookList() above.
  $handle1 = fopen($file, 'r');
  $i = 1;
  while($i<=$num) {
    $id = fgets($handle1);
    $id = (int)$id;
    $url = "http://it-ebooks-api.info/v1/book/$id";
    echo "making call number $i \n";
    $handle2 = fopen($url, 'r') or die ("Can't open webpage.");
    $string = fgets($handle2);
    $bookData = json_decode($string, true);
    $i++;
    //Save info to database
    include 'mysql.connect.php';
    if(!empty($bookData)){
      if(isset($bookData['ID'], $bookData['Title'], $bookData['ISBN'], $bookData['Author'], 
            $bookData['Year'], $bookData['Image'], $bookData['Download'])){

        $ID = trim($bookData['ID']);
        $Title = trim($bookData['Title']);
        $ISBN = trim($bookData['ISBN']);
        $Description = trim($bookData['Description']);
        $Author = trim($bookData['Author']);
        $Year = trim($bookData['Year']);
        $Image = trim($bookData['Image']);
        echo $ID . "\n" . $Title . "\n" . $ISBN . "\n" . $Description . "\n" . $Author. "\n" .$Year. "\n" .$Image. "\n";

        if(!empty($ID) && !empty($Title) && !empty($ISBN) && !empty($Description) && 
              !empty($Author) && !empty($Year)){

          $insert = $db->prepare("INSERT INTO book (id, title, isbn, description, 
                author, year, subject) VALUES (?, ?, ?, ?, ?, ?, ?)");
          if (!$insert){
            echo "Insert must be false.";
          }
          $insert->bind_param('issssis', $ID, $Title, $ISBN, $Description, $Author, 
                $Year, $term);
          $insert->execute();


          ////////////////////////////////////////////////NEW CODE

          $amazon = $db->prepare("INSERT INTO image (isbn, med_img) VALUES (?, ?)");
          $amazon->bind_param('ss', $ISBN, $Image);
          $amazon->execute();

          ///////////////////////////////////////////////END NEW CODE
          } else { 
            echo "at least one field in $i is empty<br>";
          }
        }
      }
    }
  fclose($file);
  }    

?>