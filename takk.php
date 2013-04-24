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
<script>
function retriveGeoJsonOrders(id) {
      $.get('php/order2geojson.php?type=order&id='+id, function (data) {
           addAreaGeoJsonOrder(geoarea);
        }, "script");
    }
    
function addAreaGeoJsonOrder(area) {  
      var test =  L.geoJson();
      test =  L.geoJson(area, {
        style: myStyle
      });
      test.addTo(map);
      test.bringToFront();
      
      
      
}
function doRest() {
  if(hash.indexOf("orderid-")>-1) {
    var orderid = parseInt(hash.replace("orderid-", ""));
    retriveGeoJsonOrders(orderid);
  }
}
</script>
</head>
<body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="brand" href="./"> <img alt="Foo" src="images/map-2-48.png" style="height:16px"> Okart</a>
        </div>
      </div>
    </div>

  


      <div class="row-fluid height100">
        <div class="span2">
        </div>
        <div class="span8 height100">
          <div class="row-fluid height50">
            <div class="span12 height100" id="map">
              <script src="js/common.js"></script>
            </div>
          </div>
          <div class="row-fluid height50">
            <div class="span12 height100 well">
              <div class="span2">
              </div>
              <div class="span8">
              <h2>Takk for din bestilling!</h2>
              <p>I kartet over kan du se en oversikt over kartene du bestilte. Dersom noe ikke stemmer vennligst ta kontakt så fort som mulig.</p>
              <p>Du vil bli kontaktet så fort kartene er klare til produksjon.</p>
              <p></p>
              <p><a href="./" class="btn btn-primary btn-large">Tilbake til hovedsiden &raquo;</a></p>
            </div>
            <div class="span2">
              </div>
            </div>
          </div>
      </div>
      
      <div class="span2">
      </div>
      </div>

  

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

</body>
</html>
