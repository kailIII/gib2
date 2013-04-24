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
  <script src="js/Control.FullScreen.js"></script>
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
          <div class="nav-collapse collapse pull-right">
            <ul class="nav">
              <li class=""><a href="login.php">Logg inn</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid height100">
        <div class="span2" id="menus">
        
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              
              <?php
require('php/sql.php');
$sql = "SELECT *\n"
. "FROM `okart_areas`\n"
. "ORDER BY `okart_areas`.`fylke` ASC, `okart_areas`.`kommune` ASC, `okart_areas`.`name` ASC LIMIT 0, 30 ";
$sth = $dbh->prepare($sql);
$sth->execute();
$areas = $sth->fetchAll(PDO::FETCH_ASSOC);
$kommune = $areas[0]['kommune'];
$fylke = $areas[0]['fylke'];
print '<li class="nav-header nav-fylke">'.$fylke.'</li>';
print '<li class="nav-header nav-kommune">'.$kommune.'</li>';
foreach($areas as $area) {
  if($fylke != $area['fylke'] && $kommune != $area['kommune']) {
    $fylke = $area['fylke'];
    $kommune = $area['kommune'];
    print '';
    print '<li class="nav-header nav-fylke>'.$fylke.'</li>';
    print '<li class="nav-header nav-kommune">'.$kommune.'</li>';
  }
  if($kommune != $area['kommune']) {
    $kommune = $area['kommune'];
    print '<li class="nav-header nav-kommune">'.$area['kommune'].'</li>';
  }
  print '<li id="list-area-'.$area['id'].'"><a id="link-area-'.$area['id'].'" href="#area-'.$area['id'].'" OnClick="retriveGeoJsonArea('.$area['id'].');">'.$area['name'].'</a></li>';
}
print '</ul>';
?>
            </ul>
          </div><!--/.well -->
          <div class="well alert-important" id="helpBox">
          </div>
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Kurv</li>
              <li id="basketControls"><a  href="javascript:void(0)" onclick="showOrders();">Se alle</a> <a href="javascript:void(0)" onclick="emptyBasket();">Tøm</a>
            </ul>
          </div>
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li id="basketControls"><a  href="javascript:void(0)" onclick="showOrderForm();">Send bestilling</a>
            </ul>
          </div>
        </div>
        
          
          
        
        <div class="hidden" id="orderFormContainer">
          <div class="well" id="orderForm">
             <div class="row-fluid">
                <div class="span11">
                  <h3>Send bestilling:</h3>
                </div>
                <div class="span1">
                  <a href="javascript:void(null)" onclick="closeOrderForm()">Lukk</a>
                </div>
             </div>
            <div class="row-fluid"> 
            <form action="submitOrder.php" method="post" name="submitOrder">
              <div id="orderForm1" class="span6">
                <h5>Navn: *</h5>
                <input type="text" id="orderName" name="orderName">
                <h5>Gateadresse: *</h5>
                <input type="text" id="orderStreet" name="orderStreet">
                <h5>Postnummer: *</h5>
                <input type="text" id="orderPostnr" name="orderPostnr">
                <h5>Sted: *</h5>
                <input type="text" id="orderPlace" name="orderPlace">
              </div>
              <div id="orderForm2" id="" class="span6">
                <h5>E-post: *</h5>
                <input type="text" id="orderEmail" name="orderEmail">
                <h5>Telefon: *</h5>
                <input type="text" id="orderTel" name="orderTel">
                <h5>Klubb:</h5>
                <input type="text" id="orderClub" name="orderClub">
                <h5>Dato kart(ene) skal brukes:</h5>
                <input type="text" id="orderDate" name="orderDate" placeholder="ÅÅÅÅ-MM-DD">
              </div>
            
            <p>
              * Påkrevd
            </p>
            <div class="row-fluid" id="mapList">
              
              <div class="span9">
                <h5>Kart til bestilling:</h5>
              </div>
              <div class="span2">
                <h5>Antall:</h5>
              </div>
              <div class="span1">
                
              </div>
            </div>
          
            <div class="row-fluid">

              <div id="orderForm3" class="span12">
                <hr>
                <h5>Kommentar:</h5>
                <textarea name="orderComments"></textarea>
              </div>
              </div>
              <div class="row-fluid">

              <div class="span2">
                <a href="javascript:void(0)" onclick="submitOrderForm()" class="btn">Send</a>
              </div>
              <div class="span8">

              </div>
              <div class="span2">

              </div>
              </div>

          </form>
          </div>
          
        </div><!--/span-->
                <!--/span-->
      </div><!--/row-->

     
  <div id="map" class="span10" ></div>
            <script src="js/common.js"></script>
            <script src="js/map.js"></script>
          
    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


</body>
</html>
