<?php
require('php/Auth.php');
$auth = new Auth();
if($auth->check()) {
  require('php/sql.php');
  if($_GET['deleteMap']) {
     $sql = "DELETE FROM `okart_maps` WHERE `id` = ?;";
      $sth = $dbh->prepare($sql);
      $sth->execute(array($_GET['deleteMap']));
  }
   if($_GET['deleteArea']) {
     $sql = "DELETE FROM `okart_areas` WHERE `id` = ?;";
      $sth = $dbh->prepare($sql);
      $sth->execute(array($_GET['deleteArea']));
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
              <div class="span12">
                
                <?php
                require('php/sql.php');
                $sql = "SELECT * FROM okart_areas ORDER BY name";
                $sth1 = $dbh->prepare($sql);
                $sth1->execute();
                while($results = $sth1->fetch()) {
                  $sql = "SELECT * FROM okart_maps WHERE areaid = ? ORDER BY name";
                  $sth2 = $dbh->prepare($sql);
                  $sth2->execute(array($results['id']));
                  print '<div class="row-fluid well">';
                  print '<div class="span4">';
                  print "<h4>".$results['name']."</h4>";
                  print '<a href="editareas.php?deleteArea='.$results['id'].'" class="pull-right">Slett</a>';
                  print "</div>";
                  print '<div class="span6 well">';
                  print '<h6>Utsnitt</h6>';

                  while($maps = $sth2->fetch()) {
                    print '<p>'.$maps['name'].' <a href="editareas.php?deleteMap='.$maps['id'].'" class="pull-right">Slett</a></p>';
                  }
                  print '<p><a href="define-maps.php#areaid-'.$results['id'].'">Legg til</a></p>';
                  print "</div>";
                  print '<div class="span4">';
                  
                  print "</div>";
                  print '</div>';
                }
                ?>
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