<?php
require('php/Auth.php');
$auth = new Auth();
if($auth->check()) {
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
  <script src="js/Control.FullScreen.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<link href="./css/bootstrap.css" rel="stylesheet">
    <link href="./css/common.css" rel="stylesheet">
    <style>
      #map {
        min-height: 480px;
      }
      #mapWell,#mapContainer {
        height: 100%;
      }

    </style>
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

    <div class="container-fluid height100">
      <div class="row-fluid height100">
        <div class="span2">
         <?php
         include('menu.php');
         ?>
        </div><!--/span-->
        
        <div class="span8 height100" id="map">
            <script src="js/common.js"></script>
            <script src="js/definearea.js"></script>
        </div>
        <div class="span2">
          <div class="well">
            <div class="progress progress-striped">
                <div class="bar" style="width: 66.6%;"></div>
              </div>
            <form action="savearea.php" name="area-edit-form" method="post">
              <h5>Steg 2: Tegn omrisset til området.</h5>
                <p>Zoom og panorer inn på kartet til området du har tilet i forrige steg. Bruk så verktøyene til å tegne omrisset til området.</p>
              <a href="#nrequire('php/sql.php');t" onclick="submitForm()" class="btn btn-success">Neste...</a>
            </form>
          </div>
          </div>
        
          </div>
          
        </div><!--/span-->

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