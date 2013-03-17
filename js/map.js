		var map = L.map('map').setView([63.379, 10.48576], 12);
		var basket = Array();
		
		L.tileLayer(' http://opencache.statkart.no/gatekeeper/gk/gk.open_gmaps?layers=norges_grunnkart&zoom={z}&x={x}&y={y}', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="http://kartverket.no">Kartverket</a>'
		}).addTo(map);
		var drawnItems = new L.FeatureGroup();
		map.addLayer(drawnItems);
		var drawControl = new L.Control.Draw({
			draw: {
				position: 'topleft',
				circle: false,
				marker: false,
				polyline: false,
				polygon: {
					title: 'Draw a sexy polygon!',
					allowIntersection: true,
					drawError: {
						color: '#b00b15',
						timeout: 1000
					},
					shapeOptions: {
						color: '#bada55'
					}
				}
				

			},
			edit: {
				featureGroup: drawnItems
			}
		});
		var DrawControlPresent = 0;

		function addToBasket(polygon) {
			if($.inArray(polygon, basket) == -1) {
				basket.push(polygon);
				var polygonId = basket.length - 1;
				var polygonTekst = basket.length.toString();
				var basketLi = document.createElement("li");
				basketLi.id = "asda"+polygonId.toString();
				var basketTekst = document.createTextNode("Område "+polygonTekst);
				
				var link = document.createElement("a");
				link.href = "javascript:showFromBasket("+polygonId.toString()+");";
				link.appendChild(basketTekst);
				
				basketLi.appendChild(link);
				$("#allBasket").before(basketLi);
			}
			
		}

		function addDrawControl(){
			hideAllFromBasket();
			removePresetMaps();
			if(DrawControlPresent == 0) {
				map.addControl(drawControl);
				DrawControlPresent = 1;
			}
		}
		function saveDrawings() {
			drawnItems.eachLayer(function (layer){
				addToBasket(layer);
			});
		}
		function deleteDrawings() {
			drawnItems.clearLayers();
		}
		function removeDrawControl() {

		}
		map.setMaxBounds(map.getBounds());
		function showAllFromBasket() {
			removePresetMaps();
			deleteDrawings();
			
				map.setView([63.379, 10.48576], 12);
			
			
			for (var i = 0; i < basket.length; i++) {
				map.addLayer(basket[i]);
			}
		}
		function hideAllFromBasket() {
			for (var i = 0; i < basket.length; i++) {
				map.removeLayer(basket[i]);
			}
		}
		var presetColors = new Array("#25d500","#00a08a","#Ff6a00", "#f60018", "#CD0074");
		var presets = new Array();
		presets[0] = L.polygon([[ 63.387215824464946, 10.5413818359375 ], [ 63.395827017673106, 10.546875 ], [ 63.39690323517898, 10.53863525390625 ], [ 63.39459700538517, 10.527305603027344 ], [ 63.402130003245084, 10.524215698242188 ], [ 63.4070484446444, 10.504989624023438 ], [ 63.41104405736332, 10.490226745605469 ], [ 63.41135138913175, 10.4644775390625 ], [ 63.40858528464326, 10.447998046875 ], [ 63.40182257265643, 10.447998046875 ], [ 63.398286884093864, 10.443191528320312 ], [ 63.39029154719126, 10.427742004394531 ]] );
			presets[1] = L.polygon([[ 63.39013776888175, 10.427742004394531 ], [ 63.38613924365176, 10.435295104980469 ], [ 63.38167869302983, 10.510826110839844 ], [ 63.38383214881458, 10.540351867675781 ], [ 63.390752877176645, 10.5084228515625 ], [ 63.39106042638103, 10.484733581542969 ], [ 63.39152174400882, 10.445594787597654 ]] );
			presets[2] = L.polygon([[ 63.38552403648902, 10.540351867675781 ], [ 63.38706202967769, 10.434951782226562 ], [ 63.38106339028353, 10.435638427734375 ], [ 63.37506349750804, 10.430145263671875 ], [ 63.36937013274793, 10.439071655273438 ], [ 63.37306325468648, 10.541725158691406 ]] );
			presets[3] = L.polygon([[ 63.36952402230788, 10.440101623535156 ], [ 63.37321712446416, 10.5413818359375 ], [ 63.3598273700392, 10.515975952148438 ], [ 63.35951948621985, 10.479240417480469 ], [ 63.36013525056051, 10.457267761230467 ], [ 63.362752101837486, 10.444908142089844 ]]);
			presets[4] = L.polygon([[ 63.36259817600515, 10.445594787597654 ], [ 63.35690234059391, 10.441131591796875 ], [ 63.35259122831163, 10.440101623535156 ], [ 63.35028143792574, 10.446624755859373 ], [ 63.34720142876015, 10.448341369628906 ], [ 63.34612334761205, 10.45623779296875 ], [ 63.343813037629594, 10.465164184570312 ], [ 63.346739398931135, 10.481986999511719 ], [ 63.34966546248425, 10.497779846191406 ], [ 63.35443892702454, 10.506706237792969 ], [ 63.35967342854179, 10.515289306640625 ], [ 63.35998131071211, 10.457954406738281 ]] );
		function addPresetMaps(e) {
			if(e ==  1) {
				map.setView([63.379, 10.48576], 12);
			}
			else {
				map.setView([63.379, 10.48576], 12);	
			}
			removePresetMaps();
			for (var i = 0; i < presets.length; i++) {
				addPresetMap(i);
			}
			removeDrawControl();
			hideAllFromBasket();
		}
		function addPresetMap(i) {
			if(i > 4) {
				j =i -5;
			}
			else {
				j = i;
			}
			presets[i].addTo(map);
				var defaultStyle = {
		            color: presetColors[j],
		            weight: 2,
		            opacity: 0.6,
		            fillOpacity: 0.1,
		            fillColor: presetColors[j]
        		};
        		var hoverStyle =  {
		            color: presetColors[j],
		            weight: 2,
		            opacity: 1,
		            fillOpacity: 0.3,
		            fillColor: presetColors[j]
        		};
        		presets[i].setStyle(defaultStyle);
        	presets[i].on("click", function (e) {
        		addToBasket(e.target);
        	})
        	presets[i].on("mouseover", function (e) {
        		e.target.setStyle(hoverStyle);
        	})
        	presets[i].on("mouseout", function (e) {
        		e.target.setStyle(defaultStyle);
        	})

		}
		function showSingleMap(i) {
			hideAllFromBasket();
			removePresetMaps();
			addPresetMap(i);
			map.fitBounds(presets[i].getBounds());
			removeDrawControl();
		}
		function showFromBasket(i) {
			hideAllFromBasket();
			removePresetMaps();
			map.addLayer(basket[i]);
			map.fitBounds(basket[i].getBounds());
			removeDrawControl();
		}
map.on('draw:created', function (e) {
			var type = e.layerType,
				layer = e.layer;
			drawnItems.addLayer(layer);
		});
		function removePresetMaps() {
			for (var i = 0; i < presets.length; i++) {
				map.removeLayer(presets[i]);
			}
		}
		
		var drawing;
		
	


function setActive(clicked) {
	$(".active").removeClass("active");
	clicked.parentNode.setAttribute("class", "active");
}
function createInputFromBasket() {
	var inputArr = Array();
	for (var i = 0; i < basket.length; i++) {
		var input = document.createElement("input");
		input.type = "hidden";
		input.name = "polygon";
		input.value = latLngsToString(basket[i].getLatLngs());
		inputArr.push(input);
	}
	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "polygon";
		input.value = "empty";
		inputArr.push(input);
	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "polygon";
		input.value = "empty";
		inputArr.push(input);
	return inputArr;
}
function latLngsToString(latlngs) {
	var string = "";
	for(var i =0; i < latlngs.length; i++) {
		string = string+""+latlngs[i].lng+", "+latlngs[i].lat+"|";
	}
	string = string+latlngs[0].lng+", "+latlngs[0].lat;
	return string;
}
var orderFormVisible = 0;

function showOrderForm() {
	if(orderFormVisible == 0) {
		$("#mapContainer").removeClass("span9");
		$("#mapContainer").addClass("span6");
		var orderForm = "<form id=\"OrderForm\" action=\"python/okart.cgi\" method=\"post\" name=\"OrderForm\">"+
		"<h4>Bestillingsskjema:</h4>"+
		"<p>Navn:</p>"+
		"<input type=\"text\" name=\"name\">"+
		"<p>E-post:</p>"+
		"<input type=\"text\" name=\"email\">"+
		"<p>Gateadresse</p>"+
		"<input type=\"text\" name=\"street\">"+
		"<p>Postnummer:</p>"+
		"<input type=\"text\" name=\"postcode\">"+
		"<p>Sted:</p>"+
		"<input type=\"text\" name=\"place\">"+
		"<p>Kommentar:</p><textarea name=\"comments\"></textarea><p><input type=\"submit\"></p></form>";
		$("#mapContainer").after('<div class="span3" id="orderFormContainer"><div class="well">'+orderForm+'</div></div><!--/span-->');
		var polygonsInputs = createInputFromBasket();
		var form = document.getElementById("OrderForm");
		for(var i = 0; i < polygonsInputs.length; i++) {
			form.appendChild(polygonsInputs[i]);
		}
		showAllFromBasket();
		map.invalidateSize(true);
		orderFormVisible =  1;
	}
	else {
		orderFormVisible = 0;
		$("#orderFormContainer").remove();
		$("#mapContainer").removeClass("span6");
		$("#mapContainer").addClass("span9");
		map.invalidateSize(true);
	}
}
addPresetMaps(0);