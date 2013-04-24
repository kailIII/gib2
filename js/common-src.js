var map =L.map('map',{
      maxZoom: 17
    }
);   

 // Default styles for polygon borders
    var myStyle = {
        "color": "#0772a1",
        "weight": 2,
        "fillOpacity": 0,
         "opacity": 1
    };
    var areaStyle = {
        "color": "#0772a1",
        "weight": 1,
        "fillOpacity": 0,
         "opacity": 0.8
    };
     var hiStyle = {
        "color": "#077271",
        "weight": 4,
        "fillOpacity": 0,
         "opacity": 0.8
    };
    var clickStyle = hiStyle;
    var showStyle = {
        "color": "#8f04a8",
        "weight": 3,
        "fillOpacity": 0,
         "opacity": 0.8
    };

    
    var currentSelectedLayer;
    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);
    var drawControl = new L.Control.Draw({
      draw: {
        position: 'topleft',
        circle: false,
        marker: false,
        polyline: false,
        polygon: {
          allowIntersection: true,
          shapeOptions: {
            color: '#BD63d4'
          }
        }
        

      },
      edit: {
        featureGroup: drawnItems
      }
      
    });
    
    
var fullScreen = new L.Control.FullScreen();   
var hash = window.location.hash.substring(1);
var settings;
var areas;
var maps;
var  osmAttribution = '';
var geoJsons;
var drawnItemsCounter = 0;
function init() {

  map.addControl(fullScreen);
      //http://{s}.tile.osm.org/{z}/{x}/{y}.png
     var bgUrl = 'http://opencache.statkart.no/gatekeeper/gk/gk.open_gmaps?layers=norges_grunnkart&zoom={z}&x={x}&y={y}';
     var osmUrl = 'http://opencache.statkart.no/gatekeeper/gk/gk.open_gmaps?layers=norges_grunnkart&zoom={z}&x={x}&y={y}';

  // var bgUrl = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';
  // var osmUrl = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';
  
  
  var bgmap = L.tileLayer(bgUrl, {
      attribution: 'Map data &copy; Kartverket, Wing OK',
      key: 'BC9A493B41014CAABB98F0471D759707'
    });
    bgmap.addTo(map);
    $.getJSON('php/sql2geojson_test.php', function (data) {
        settings = data.settings;
        areas = data.areas;
        maps = data.maps;
         map.setView([parseFloat(settings.centerLat), parseFloat(settings.centerLong)], parseInt(settings.defaultZoom));
       displayAllTiles();
        doRest();
    });
}
init();
function drawnItemToGeoJsonFeature(layer) {
      var latLngs = layer.getLatLngs();
      var coordinates = [];
      var points = [];
      for (var i = latLngs.length - 1; i >= 0; i--) {
        var point = [];
        point.push(latLngs[i].lng);
        point.push(latLngs[i].lat);
        points.push(point);
      }
      coordinates.push(points);
      var feature =  {
              "type": "Feature",
              "geometry": {
                  "type": "Polygon",
                  "coordinates": coordinates
              },
              "properties": {
                "name": layer.name,
                "description": layer.description
              }
          }

          return feature;

    }
    function latLngsToMysqlString(latlngs) {
      var str = "POLYGON((";
      for (i = latlngs.length - 1; i >= 0; i--) {
        str += latlngs[i].lat+" "+latlngs[i].lng+", ";
      };
      str += latlngs[latlngs.length - 1].lat+" "+latlngs[latlngs.length - 1].lng;
      str += "))";
      return str;
    }
    function addTiles() {
      
        map.zoomOut();
           geoJsons = L.geoJson(areas, { style: areaStyle });
           geoJsons.eachLayer(function (layer) {
              var tileUrl = 'tiles/'+layer.feature.properties.areaid+'/{z}_{x}_{y}.png';
              L.TileLayer.boundaryCanvas(tileUrl, {
              boundary: layer.getLatLngs(), 
              attribution: osmAttribution,
            minZoom: 10

            }).addTo(map);
          
            layer.on("click", function (e) {
              retriveGeoJsonArea(parseInt(layer.feature.properties.areaid));
              map.closePopup();
            });
        });
        geoJsons.addTo(map);
        map.zoomIn();
    }
    
    function displayAllTiles() {
      addTiles();
    }
    function retriveGeoJsonArea(id) {

      var tempLayer = L.featureGroup();
      geoJsons.eachLayer(function (layer) {
        if(id == parseInt(layer.feature.properties.areaid)) {
          tempLayer.addLayer(layer);
        }
      });
      map.fitBounds(tempLayer.getBounds());
      map.closePopup();
      
    }