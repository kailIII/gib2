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
            <form name="tile-upload-form" id="tile-upload-form" action="tilecatcher.php" method="post"
enctype="multipart/form-data">
                <h4>Definer nytt område:</h4>
                <p>
                  Navn:
                </p>
                <p>
                  <input type="text" name="areaname">
                </p>
                <p>
                  Sted:
                </p>
                
                  <input type="text" name="areasted">
                
                <p>
                  Fylke:
                </p>
                <p>
                  <input type="text" name="areafylke">
                </p>
                <p>
                  Tileset:
                </p>
                <p>
                  <input type="file" name="file" id="file">
                </p>
              <p>
              </p>
          
        </div>
        <div class="span4">
              </div>
      </div>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span2">
          <div class="well">
              <div class="progress progress-striped">
                <div class="bar" style="width: 33.3%;"></div>
              </div>
            <h5>Steg 1: Last opp tiles</h5>
            <script>
            function submitForm() {
              var theForm = document.forms['tile-upload-form'];
              if(theForm.areaname.value != "" && theForm.areafylke.value != "" && theForm.areasted.value != "" && theForm.file.value != "") {
                theForm.submit();
              }
              else {
                $("#submitLink").removeClass("btn-success");
                $("#submitLink").addClass("btn-danger");
              }
            }
            </script>
                  <a href="javascript:void(0)" onclick="submitForm()" class="btn btn-success"  id="submitLink">Neste...</a>
                  </form>
        </div>
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