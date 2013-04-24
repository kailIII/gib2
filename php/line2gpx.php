<?php
print '<?xml version="1.0" encoding="UTF-8"?>
<gpx xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:gpxdata="http://www.cluetrust.com/XML/GPXDATA/1/0" xmlns="http://www.topografix.com/GPX/1/0" xsi:schemaLocation="http://www.topografix.com/GPX/1/0 http://www.topografix.com/GPX/1/0/gpx.xsd http://www.cluetrust.com/XML/GPXDATA/1/0 http://www.cluetrust.com/Schemas/gpxdata10.xsd" version="1.0" creator="http://ridewithgps.com/">
  <trk>
    <trkseg>';
	  // <trkpt lat="58.234870847650441" lon="7.9426922503709"></trkpt>
    	$pointstr = "63.39090665219075,10.18157958984375|63.35520876644145,10.342254638671873|63.32008311937006,10.405426025390625";
    	$points = explode("|", $pointstr);

    	for ($i=0; $i < count($points)-1; $i++) { 
    		$point = explode(',', $points[$i]);

    		print  '<trkpt lat="'.$point[0].'" lon="'.$point[1].'"></trkpt>';
    	}
    	$point = explode(',', $points[count($points)-1]);
    	print  '<trkpt lat="'.$point[0].'" lon="'.$point[1].'"></trkpt>';

print "   </trkseg>
  </trk>
</gpx>";
?>