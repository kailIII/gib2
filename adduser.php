<?php
require('php/Auth.php');
$auth = new Auth();
if($auth->check()) {
  if($_POST['newUser']) {
    if($_POST['alert']) {
      $alert = 1;
    } else {
      $alert = 0;
    }
    $auth->newUser($_POST['email'],$_POST['password'],$_POST['name'], $alert);
  }
  if($_GET['delete']) {
    $auth->deleteUser($_GET['delete']);
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
            <form name="new-user-form" id="new-user-form" action="adduser.php" method="post"
enctype="multipart/form-data">
                <h4>Ny bruker:</h4>
                <p>
                  Navn:
                </p>
                <p>
                  <input type="text" name="name">
                </p>
                <p>
                  E-post:
                </p>
                
                  <input type="text" name="email">
                
                <p>
                  Passord:
                </p>
                <p>
                  <input type="password" name="password">
                </p>
                <p>
                 
                </p>
                <p>
                  Varsle meg om nye bestillinger <input type="checkbox" name="alert" value="1" checked>
                </p>
                <p>
                  <input type="submit" name="newUser" value="Legg til..." class="btn btn-success span12">
                </p>
              <p>
              </p>
            </form>
          
        </div>
        <div class="span4">
              </div>
      </div>
      <hr>
      <div class="row-fluid">
              <div class="span12">
                <h4>Eksisterende brukere:</h4>
                <?php
                require('php/sql.php');
                $sql = "SELECT * FROM okart_users";
                $sth = $dbh->prepare($sql);
                $sth->execute();
                while($results = $sth->fetch()) {
                  print '<div class="row-fluid">';
                  print '<div class="span4">';
                  print "<p>".$results['name']."</p>";
                  print "</div>";
                  print '<div class="row-fluid">';
                  print '<div class="span4">';
                  print "<p>".$results['email']."</p>";
                  print "</div>";
                  print '<div class="row-fluid">';
                  print '<div class="span4">';
                  print '<a href="adduser.php?delete='.$results['id'].'">Slett</a>';
                  print "</div>";
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