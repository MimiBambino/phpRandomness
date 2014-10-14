<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
//error_reporting(E_ALL | E_STRICT | E_DEPRECATED);
include 'functions.php';

/*  STEP 1: Visit url: http://it-ebooks-api.info/v1/search/$term and 
determine how many results there are for a $term.
    STEP 2: Delete b.txt and books.txt.
    STEP 3: Call AppendBooks() from command line with `php -f cli.php`
        $term is a search term for an IT topic
        $num is the number of results found in the result
        $file is the file to which the results will be appended 
    STEP 4: Run `wc -l b.txt` 
        Determines number of lines appended
    STEP 5: Run `sort -u b.txt > books.txt` 
        Removes duplicate books
    STEP 6: Run `wc -l books.txt` 
        Determines number of lines in books.txt which is the 3rd param in InsertBooks()
    STEP 7: Edit cli.php to comment out AppendBooks() and change params for InsertBooks()
    STEP 8: Call InsertBooks() from command line with `php -f cli.php`
    STEP 9: Repeat for each topic until API limit exceeded (1000 calls).  API resets daily at 1:00pm.

*/

//AppendBooks('career', 12, 'b.txt');


//mobile, icloud, java, team, raspberry pi, google

InsertBooks('books.txt','career', 12);

?>