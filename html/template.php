<!DOCTYPE html>
 
<?php 
include 'mysql.connect.php'; 

$sql = "SELECT * FROM `book` WHERE `year` > 2012\n"
    . "ORDER BY `book`.`year` DESC";

if(!$books = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

?>

<html lang="en">
<head>
	<!-- Latest compiled and minified CSS -->
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>

<!-- Optional theme -->
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css'>

<!-- Latest compiled and minified JavaScript -->
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<meta name='description' content=''>
  <meta name='author' content=''>
  <style type="text/css">
   body { background: #ccc; }
   .container { border: 1px solid black; 
         padding: 10px; }
   .description { padding: 10px;}
   .description p { padding: 5px; }
  </style>
	<title>Recent IT Books</title>
</head>

<body>

	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Recent IT Books</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">PHP Books</a></li>
            <li><a href="#">Web Services Books</a></li>
            <li><a href="#">Sorted By Date</a></li>            
            <li><a href="#">Info</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search for IT Topic...">
          </form>
        </div>
      </div>
    </div>
	<br><br><br><br>
  </div>
    <div class="container">
      <div class="row row-offcanvas row-offcanvas-right">

<?php


while($book = $books->fetch_assoc()){
  
  $num = $books->num_rows;
  $description = substr($book['description'], 0, 300)."...";

  print "<div class='col-6 col-sm-6 col-lg-4'>";
  print "<img src='".$book['image']."' alt='" .$book['description']. "' class='img-responsive img-rounded center-block'>";
  print "<h4 class='text-center'>".$book['title']."</h4><br>";
  print "<div class='description text-justify text-center'><p>".$description."</p>";
  print "<button type='button' class='btn btn-default navbar-btn center-block'>More</button></div>";
  print "</div>";
}
?>

        </div>
      </div>
    </div>
  <footer>
    <p>&copy;<?php echo date('Y'); ?> MimiBambino</p>
  </footer>

</body>
</html>