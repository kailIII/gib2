<?php
if($_POST['submit']) {
  require('php/Auth.php');
  $auth = new Auth();
  if($auth->logIn($_POST['email'],$_POST['password'])) {
    header('Location: admin.php');
  }


} else {
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

        </div><!--/span-->
        <div class="span8">
          <div class="well">
            <div class="row-fluid">
              <div class="span4">
              </div>
                <div class="span4">
            <form name="login-form" id="login-form" action="login.php" method="post"
enctype="multipart/form-data">
                <h4>Logg inn:</h4>
             
                <p>
                  E-post:
                </p>
                <p>
                  <input type="text" name="email">
                </p>
                <p>
                  Passord:
                </p>
                  <input type="password" name="password">
                <p>
                  <?php
                  if($_GET['wronguserorpw']) {
                    print '<div class="alert alert-error">
                        Feil e-post eller passord.
                    </div>';

                  }
                  ?>
                </p>
                <p>
                  <input type="submit" name="submit" value="Logg inn!" class="btn btn-success span12">
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