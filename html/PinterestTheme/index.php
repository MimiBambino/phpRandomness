<!DOCTYPE html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include '/var/www/html/mysql.connect.php'; 

$sql = "SELECT * FROM book INNER JOIN image ON book.isbn = image.isbn\n"
    . "WHERE `year` > 2012\n"
    . "AND `large_img` IS NOT NULL\n"
    . "OR `med_img` IS NOT NULL\n"
    . "ORDER BY `book`.`year` DESC\n"
    . "LIMIT 0, 50";

if(!$books = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}
?>

<html lang="en" class="no-js">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Newest Tech Books</title>
    <meta name="description" content="All of the newest tech books" />
    <meta name="keywords" content="new tech books, new technology books, new IT books, IT, tech, books" />
    <link rel="shortcut icon" href="../favicon.ico"> 
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
        <!-- Attach Reveal Documents for Modal and hope for no conflicts! -->
    <link rel="stylesheet" href="css/reveal.css">

        <!--Reveal Scripts--> 
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.reveal.js"></script>

    <script src="js/modernizr.custom.js"></script>

<!--Add document ready function-->
    <script>
      $(document).ready(function(){
        $('.loader').hide();
        $( "li" ).hover(function() {
            $( this ).fadeOut( 100 );
            $( this ).fadeIn( 500 );
        });
      });
    </script>

    <style>
      .my_box {
        -webkit-box-shadow: 2px 2px 1px 0px rgba(10, 10, 10, 0.3);
        -moz-box-shadow:    2px 2px 1px 0px rgba(10, 10, 10, 0.3);
        box-shadow:         2px 2px 1px 0px rgba(10, 10, 10, 0.3);
        margin:0 auto;
      }
      .big-link { 
        display:block; margin-top: 100px; 
        text-align: center; 
        font-size: 70px; color: #06f; }
    </style> 
  </head>
  <body>
    <div class="container">
      <!-- Top Navigation -->
      <div class="codrops-top clearfix">
        <a class="codrops-icon codrops-icon-prev" href="index.html"><span>All of the Newest Tech Books</span></a>
        <span class="right"><a href="#">Some Interesting Link</a><a class="codrops-icon codrops-icon-drop" href="#"><span>Even More Stuff</span></a></span>
      </div>
      <header>
        <h1>All of the Newest Tech Books <span>Click on Topic to Filter</span></h1> 
        <nav class="codrops-demos">
          <a href="#">Databases</a>
          <a href="#">Programming Languages</a>
          <a href="#">Big Data</a>
          <a href="#">Operating Systems</a>
          <a href="#">Mobile Web</a>
          <a href="#">Web Development</a>
          <a href="#">Entrepreneurship</a>
        </nav>
      </header>
      <div class="images">
        <ul class="grid effect-1" id="grid">

          <?php
          while($book = $books->fetch_assoc()){
            if($book['med_img']){
              print "<li><a href='#' class='big-link' data-reveal-id='myModal' data-animation='fade'><img src='".$book['med_img']."' alt=' ".$book['title']."' class='my_box'></a></li>";
            } elseif ($book['large_img']) {
              print "<li><a href='#' class='big-link' data-reveal-id='myModal' data-animation='fade'><img src='".$book['large_img']."' alt=' ".$book['title']."' class='my_box'></a></li>";
            }
          }
          ?>
        </ul>
      </div>

    <div id="myModal" class="reveal-modal">
      <h1><?php echo $book['title']; ?></h1>
      <p>This is a default modal in all its glory, but any of the styles here can easily be changed in the CSS.</p>
      <a class="close-reveal-modal">&#215; </a>
    </div>

      <!--Masonry Scripts -->
    <script src="js/masonry.pkgd.min.js"></script>
    <script src="js/imagesloaded.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/AnimOnScroll.js"></script>
    <script>
      new AnimOnScroll( document.getElementById( 'grid' ), {
        minDuration : 0.4,
        maxDuration : 0.7,
        viewportFactor : 0.2
      } );
    </script>
  
      <script>
            var $container = $('container');
      //initialize
      $container.masonry({
        columnWidth: 200,
        itemSelector: '.item' 
      });

      var msnry = $container.data('masonry');
      </script>
    <!-- For the demo ad 
    <script src="http://tympanus.net/codrops/adpacks/demoad.js"></script> -->
  </body>
</html>