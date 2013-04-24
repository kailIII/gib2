<?php

require('php/Auth.php');
$auth = new Auth();
if($auth->check()) {
 if($_POST['newUser']) {
  require('php/sql.php');
  $sql = "UPDATE `okart_settings` SET `textboks` = ?, `markertext` = ?, `centerLat` = ?, `centerLong` = ?, `defaultZoom` = ? LIMIT 1;";
  $sth = $dbh->prepare($sql);
  $sth->execute(array(html_entity_decode($_POST['textboks']),html_entity_decode($_POST['markertext']),$_POST['lat'],$_POST['long'],$_POST['zoom']));
 }

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
	<title>Okart</title>

	<link rel="stylesheet" href="lib/leaflet/leaflet.css" />
	<link rel="stylesheet" href="css/leaflet.draw.css" />
	
	<!--[if lte IE 8]>
		<link rel="stylesheet" href="lib/leaflet/leaflet.ie.css" />
		<link rel="stylesheet" href="leaflet.draw.ie.css" />
	<![endif]-->
	<link rel="icon" 
      type="image/png" 
      href="images/map-2-64.png">
	<script src="lib/leaflet/leaflet.js"></script>
	<script src="js/leaflet.draw.js"></script>
  <script src="js/BoundaryCanvas.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<link href="./css/bootstrap.css" rel="stylesheet">
  <link href="./css/common.css" rel="stylesheet">
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="./js/html5shiv.js"></script>
    <![endif]-->
   
</head>
<body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="./"> <img alt="Foo" src="images/map-2-48.png" style="height:16px"> Okart</a>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2">
          <?php
          include('menu.php');
          ?>
        </div><!--/span-->
        <div class="span8">
          <div class="well">
            <div class="row-fluid">
              <div class="span4">
              </div>
                <div class="span4">

            <form name="new-user-form" id="new-user-form" action="" method="post"
enctype="multipart/form-data">
            <?php
                
require('php/sql.php'); 
                $sql = "SELECT * FROM `okart_settings` LIMIT 1";
                $sth = $dbh->prepare($sql);
                $sth->execute();
                
                $results = $sth->fetch();
                
                ?>
                <h4>Endre innstillinger:</h4>
                <h6>
                  Tekstboks: 
                </h6>
                <p>
                  <textarea type="text" name="textboks"><?php print $results['textboks']; ?></textarea>
                </p>
                <h6>
                  Markørtekst:
                </h6>
                
                  <textarea type="text" name="markertext"><?php print $results['markertext']; ?></textarea>
                
                <h6>
                  Startpunkt:
                </h6>
                <p>
                  Lat: <input type="text" name="lat" value="<?php print $results['centerLat']; ?>">
                  Grader på desimalform
                </p>
                <p>
                  Long: <input type="text" name="long"  value="<?php print $results['centerLong']; ?>">
                  Grader på desimalform
                </p>
                <p>
                  Zoom: <input type="text" name="zoom"  value="<?php print $results['defaultZoom']; ?>">
                  Heltall mellom 8-17
                </p>
                
                 <hr>
                
                <p>
                  <input type="submit" name="newUser" value="Lagre..." class="btn btn-success span12">
                </p>
              <p>
              </p>
            </form>
          
        </div>
        <div class="span4">
              </div>
      </div>
      
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span2">
          
        </div>
      </div><!--/row-->

     

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


</body>
</html>
<?php
}
?>