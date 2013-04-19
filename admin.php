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
   
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="./js/html5shiv.js"></script>
    <![endif]-->
    <style>

     .mapRow {
       height: 500px;
     }
    </style>
   
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
          
       <div class="span10">
        <div class="hidden" id="mapRow">
          <div class="span12" id="map">
              <script src="js/common.js"></script>
              <script src="js/admin.js"></script>
          </div><!--/span-->
        </div>
        <div class="hidden" id="orderInfoRow">
        <div class="span12 well" id="orderInfo">
            <div class="row-fluid">
            <div class="span2">
               <h4>Bestiller:</h4>
               <p id="name"></p>
               <p id="adress"></p>
               <p id="post"></p>
             </div>
             <div class="span2">
               <h4>&nbsp;</h4>
               <p id="email"></p>
               <p id="tel"></p>
               <p id="club"></p>
             </div>
             <div class="span2">
               <h4>Bruksdato:</h4>
               <p id="rundate"></p>
             </div>
             <div class="span5" id="maps">
               <h4>Kart:</h4>
               <p><a>Kart 1</a></p>
             </div>
             <div class="span1">
                <button class="btn pull-right" onclick="closeOrderInfoAndMap();">Lukk</button>
             </div>
           </div>
           <div class="row-fluid">
           <div class="span9">
           </div>
           <div class="span2">
            
           </div>
          <div class="span1">
            <?php
            if($_GET['archive']) {
              print '<button class="btn btn-success  pull-right" id="finishButton">Gjennoprett</button>';
            }
            else {
              print '<button class="btn btn-success  pull-right" id="finishButton">Arkiver</button>';
            }
              
            ?>
           </div>
         </div>
        </div><!--/span-->
      </div>
      <div class="row-fluid">
        <div class="span12 well">
         
            <div class="row-fluid">
              <div class="span3">
                <h5>Ordrenummer:</h5>
              </div>
              <div class="span3">
                <h5>Ordredato:</h5>
              </div>
              <div class="span3">
                <h5>Bruksdato:</h5>
              </div>
              <div class="span3">
                <h5>Bestiller:</h5>
              </div>
            </div>
<?php
require('php/sql.php');
if($_GET['archive']) {
    $sql = "SELECT *\n"
  . "FROM `okart_orders`\n"
  . "WHERE  `archived` = 1\n"
  . "ORDER BY `okart_orders`.`id` ASC";
}
else {
  $sql = "SELECT *\n"
  . "FROM `okart_orders`\n"
  . "WHERE  `archived` = 0\n"
  . "ORDER BY `okart_orders`.`id` ASC";
}
$sth = $dbh->prepare($sql);
$sth->execute();
$orders = $sth->fetchAll(PDO::FETCH_ASSOC);
foreach($orders as $order) {

            print '<script>';
            print 'var tempOrderInfo = [];';
            print 'tempOrderInfo.push("'.$order['id'].'");';
            print 'tempOrderInfo.push("'.$order['orderdate'].'");';
            print 'tempOrderInfo.push("'.$order['name'].'");';
            print 'tempOrderInfo.push("'.$order['adress'].'");';
            print 'tempOrderInfo.push("'.$order['postcode'].'");';
            print 'tempOrderInfo.push("'.$order['place'].'");';
            print 'tempOrderInfo.push("'.$order['email'].'");';
            print 'tempOrderInfo.push("'.$order['tel'].'");';
            print 'tempOrderInfo.push("'.$order['club'].'");';
            print 'tempOrderInfo.push("'.$order['rundate'].'");';
            print 'tempOrderInfo.push("'.$order['comment'].'");';
            print 'orderInfo['.$order['id'].']= tempOrderInfo;';
            print '</script>';
            print ' <div class="row-fluid" id="orderRow'.$order['id'].'"><div class="span3"><a href="javascript:void(0)" onclick="retriveGeoJsonOrders(';
            print $order['id'];
            print ')">';
            print $order['id'];
            print '</a></div>
              <div class="span3">
                <a>';
            print $order['orderdate'];
            print '</a></div>
              <div class="span3">
                <a>';
            print $order['rundate'];
            print '</a>
              </div>
              <div class="span3">
                <a>';
            print $order['name'];
            print '</a>
              </div>
            </div>';
}

?>

          </div>
          
        </div><!--/span-->
      
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