<?php
require_once('/var/www/html/mysql.connect.php');
$load = htmlentities(strip_tags($_POST['load'])) * 6;

$sql = "SELECT * FROM book INNER JOIN image ON book.isbn = image.isbn\n"
    . "WHERE `year` > 2012\n"
    . "AND `large_img` IS NOT NULL\n"
    . "OR `med_img` IS NOT NULL\n"
    . "ORDER BY `book`.`year` DESC\n"
    . "LIMIT $load, 6";

if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

while($book = $result->fetch_assoc()){
	if($book['med_img']){
        print "<div><a href='#' class='big-link' data-reveal-id='myModal' data-animation='fade'><img src='".$book['med_img']."' alt=' ".$book['title']."' class='my_box'></a></div>";
    } elseif ($book['large_img']) {
        print "<div><a href='#' class='big-link' data-reveal-id='myModal' data-animation='fade'><img src='".$book['large_img']."' alt=' ".$book['title']."' class='my_box'></a></div>";
    }
}
?>