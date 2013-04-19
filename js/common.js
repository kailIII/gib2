    // Default styles for polygon borders

    var myStyle = {
        "color": "#FF3100",
        "weight": 3,
        "fillOpacity": 0,
         "opacity": 0.8
    };
    var areaStyle = {
        "color": "#0772a1",
        "weight": 1,
        "fillOpacity": 0,
         "opacity": 0.8
    };
     var hiStyle = {
        "color": "#0772a1",
        "weight": 6,
        "fillOpacity": 0,
         "opacity": 0.8
    };
    var clickStyle = {
        "color": "#80E800",
        "weight": 4,
        "fillOpacity": 0,
         "opacity": 0.8
    };
    //http://{s}.tile.osm.org/{z}/{x}/{y}.png
     var bgUrl = 'http://opencache.statkart.no/gatekeeper/gk/gk.open_gmaps?layers=norges_grunnkart&zoom={z}&x={x}&y={y}';
     var osmUrl = 'http://opencache.statkart.no/gatekeeper/gk/gk.open_gmaps?layers=norges_grunnkart&zoom={z}&x={x}&y={y}';
    //var bgUrl = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';
    //var osmUrl = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';
    var  osmAttribution = '';

    var map =L.map('map',{
      maxZoom: 17
    }
      ).setView([63.37908, 10.43305], 10);
    
    var bgmap = L.tileLayer(bgUrl, {
      attribution: 'Map data &copy; Kartverket, Wing OK',
      key: 'BC9A493B41014CAABB98F0471D759707'
    });

    function retriveGeoJsonArea(id) {
      areaId = id;
      osmUrl = 'tiles/'+id+'/{z}_{x}_{y}.png';
      $.get('php/sql2geojson.php?id='+id, function (data) {
           addAreaGeoJsonData(geoarea);
        }, "script");
      
    }

    bgmap.addTo(map);
  
    var currentSelectedLayer;
        var drawnItemsCounter = 0;
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
    var fullScreen = new L.Control.FullScreen(); 
  map.addControl(fullScreen);