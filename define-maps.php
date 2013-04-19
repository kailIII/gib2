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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<link href="./css/bootstrap.css" rel="stylesheet">
  <link href="./css/common.css" rel="stylesheet">
  <script src="js/Control.FullScreen.js"></script>
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
        <div class="span8" id="map">
            <script src="js/common.js"></script>
            <script src="js/definearea.js"></script>
        </div>
        <div class="span2">
          <div class="well">
            <div class="progress progress-striped">
                <div class="bar" style="width: 100%;"></div>
              </div>
            <h5>Steg 3: Legg inn ferdige kartutsnitt (valgfritt)</h5>
            <p>Tegn inn kartutsnitt du vil at enkelt skulle kunne velges og bestilles.</p>
            <p>Etter at utsnittet er opprettet kan du i boksene under redigere navn og beskrivelse.</p>
            <form action="savemaps.php" name="area-edit-form" method="post">
              <input type="button" onclick="submitMaps()" value="Neste..." class="btn btn-success">
            </form>
          </div>
          <div class="well">
            <h4>Kartinfo:</h4>
            <h5>Navn:</h5>
            <p><input name="mapName" id="map-name" onchange="updateName()"></p>
            <h5>Beskrivelse:</h5>
            <p><textarea name="mapDescription" id="map-desc" onchange="updateDescription()"></textarea></p>  
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