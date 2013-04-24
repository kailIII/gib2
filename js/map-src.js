
		function addPredefinedMaps(area) {	
			predefinedMaps.addData(area);
			predefinedMaps.eachLayer(function (layer) {
				layer.name = layer.feature.properties.name;
				layer.description = layer.feature.properties.description;
				layer.areaId = layer.feature.properties.areaid;
				layer.predefined = true;
					layer.on("mouseover", function (e) {
							layer.setStyle(hiStyle);

						});
					layer.on("mouseout", function (e) {
							layer.setStyle(myStyle);

						});
					layer.on("click", function (e) {
							currentSelectedLayer = layer;
							layer.setStyle(clickStyle);
							map.fitBounds(layer.getBounds());
					});
					var popupContent = "<h5>"+layer.name+"</h5><p>"+layer.description+"</p><p><a href=\"javascript:void(0)\" onclick=\"addToBasket(currentSelectedLayer)\">Legg i kurv</a></p>";
					layer.bindPopup(popupContent);
			});
		}
		function addToBasket(layer) {
			drawnItems.removeLayer(layer);
			basketGroup.addLayer(layer);
			layer.on("mouseout", function (e) {
							layer.setStyle(showStyle);

				});
			layer.setStyle(showStyle);
			layer.closePopup();
			layer.basketId=basketId++;
			layer.numMaps = 1;
			var lastItem= $("#basketControls");
			var newLink = document.createElement("a");
			newLink.href = "javascript:void(0)";
			newLink.onclick = function() {
				showLayer(layer);
			};
			newLink.id =  "basketLayerLink-"+layer.basketId;
			newLink.innerHTML = layer.name;
			lastItem.prepend(newLink);
			$("#mapList").after(createMapDivs(layer));
			var popupContent = "<h4 class=\"mapTitle\">"+
						layer.name + "</h4><a href=\"javascript:void(0)\" onclick=\"removeFromBasket(currentSelectedLayer)\">Fjern fra kurv</a>";
			layer.bindPopup(popupContent);

		}
		function showLayer(layer) {
			if(!map.hasLayer(layer)) {
				layer.addTo(map);
				
			}			
			map.fitBounds(layer.getBounds());
		}
		function createMapDivs(layer) {
			/*
		<div class="row-fluid">
            <div class="span11">
              <p><a href="">Egendefinert 1</a></p>
            </div>
            <div class="span1">
              <p><a href="">Slett</a></p>
            </div>
        </div>
			*/

			var row = document.createElement("div");
			row.id = "orderMapRow-"+layer.basketId;
			row.className = "row-fluid";
			var col1 = document.createElement("div");
			col1.className = "span9";
			var col3 = document.createElement("div");
			col3.className = "span2";
			var numMaps = document.createElement("input");
			numMaps.type = "text";
			numMaps.value = "1";
			numMaps.className = "tinyInput"
			numMaps.onkeyup = function () {
				layer.numMaps = this.value;
			};
			
			col3.appendChild(numMaps);
			var col2 = document.createElement("div");
			col2.className = "span1";
			var mapLink = document.createElement("a");
			mapLink.innerHTML = layer.name;
			mapLink.href = "javascript:void(0)";
			mapLink.onclick = function () {
				showLayer(layer);
			};
			var deleteLink = document.createElement("a");
			deleteLink.innerHTML = "Slett";
			deleteLink.href = "javascript:void(0)";
			deleteLink.onclick = function () {
				removeFromBasket(layer);
			};

			var theForm = document.forms['submitOrder'];
			var par1 = document.createElement("p");
			par1.appendChild(mapLink);
			var par2 = document.createElement("p");
			par2.appendChild(deleteLink);
			col1.appendChild(par1);
			col2.appendChild(par2);
			row.appendChild(col1);
			row.appendChild(col3);
			row.appendChild(col2);
			return row;


		}
		function checkInputsIsSet(inputIds) {
			var set = true;
			for (var i = 0; i < inputIds.length; i++) {
				var input = document.getElementById(inputIds[i]);
				if(input.value == "") {
					$(input).addClass("missingInput");
					var set = false;
				}
			}
			
			return set;
		}
		function submitOrderForm() {
			var theForm = document.forms['submitOrder'];
			var hasLayers = false;
			basketGroup.eachLayer(function (layer) {
				var nameInput = document.createElement("input");
				nameInput.name = "mapNames[]";
				nameInput.value = layer.name;
				nameInput.type = "hidden";
				theForm.appendChild(nameInput);
				var areaInput = document.createElement("input");
				areaInput.name = "mapAreas[]";
				areaInput.value = layer.areaId;
				areaInput.type = "hidden";
				theForm.appendChild(areaInput);
				var polygonInput = document.createElement("input");
				polygonInput.name = "mapPolygons[]";
				polygonInput.value = latLngsToMysqlString(layer.getLatLngs());
				polygonInput.type = "hidden";
				theForm.appendChild(polygonInput);
				var numMaps = document.createElement("input");
				numMaps.name = "numMaps[]";
				numMaps.value = layer.numMaps;
				numMaps.type = "hidden";
				theForm.appendChild(numMaps);
				hasLayers = true;
			});
			var requiredInputs = ["orderName", "orderStreet","orderPostnr","orderPlace","orderEmail","orderTel"];
			if(checkInputsIsSet(requiredInputs) && hasLayers) {
				theForm.submit();
			}
		}
		function removeFromBasket(layer) {
			$("#basketLayerLink-"+layer.basketId).remove();
			$("#orderMapRow-"+layer.basketId).remove();
			basketGroup.removeLayer(layer);
			layer.on("mouseout", function (e) {
							layer.setStyle(myStyle);


				});
			layer.setStyle(myStyle);
			layer.closePopup();
			if(!layer.predefined) {
				var popupContent = "<a href=\"javascript:void(0)\" onclick=\"deleteDrawnLayer(currentSelectedLayer)\">Slett</a> - <a href=\"javascript:void(0)\" onclick=\"addToBasket(currentSelectedLayer)\">Legg i kurv</a>";
				drawnItems.addLayer(layer);
			}
			else {
				var popupContent = "<h5>"+layer.name+"</h5><p>"+layer.description+"</p><p><a href=\"javascript:void(0)\" onclick=\"addToBasket(currentSelectedLayer)\">Legg i kurv</a></p>";
				layerGroup.addLayer(layer);
			}
			layer.bindPopup(popupContent);
			
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
		
		function deleteDrawnLayer(layer) {
			
			drawnItems.removeLayer(layer);
		}
		function showOrders() {
			basketGroup.addTo(map);
			basketGroup.eachLayer(function (layer) {
				layer.setStyle(showStyle);
				var popupContent = "<h4 class=\"mapTitle\">"+
						layer.name + "</h4><a href=\"javascript:void(0)\" onclick=\"removeFromBasket(currentSelectedLayer)\">Remove from basket</a>";
				
				layer.bindPopup(popupContent);
				layer.on("mouseout", function (e) {
							layer.setStyle(showStyle);

				});

			});
			map.fitBounds(basketGroup.getBounds());


		}

		function emptyBasket() {
			basketGroup.eachLayer(function (layer) {	
				removeFromBasket(layer);
			});
			basketGroup.clearLayers();


		}
		function showOrderForm() {
			var mapContainer = $("#map");
			var orderForm = $("#orderFormContainer");
			var menus = $("#menus");
			menus.removeClass("span2");
			menus.addClass("hidden");
			mapContainer.removeClass("span10");
			mapContainer.addClass("span6");
			orderForm.removeClass("hidden");
			orderForm.addClass("span6");
			map.invalidateSize();
			showOrders();


		}
		function closeOrderForm() {
			var mapContainer = $("#map");
			var orderForm = $("#orderFormContainer");
			var menus = $("#menus");
			menus.addClass("span2");
			menus.removeClass("hidden");
			mapContainer.addClass("span10");
			mapContainer.removeClass("span6");
			orderForm.addClass("hidden");
			orderForm.removeClass("span6");
			map.invalidateSize();
			
		}

		
var geoJson;
var basketGroup = L.featureGroup();
var layerGroup = L.featureGroup();
var basketId= 0;
var predefinedMaps = L.geoJson("",{
				style: myStyle,
				minZoom: 10
			});
var areaId;
function doRest() {

		
		layerGroup.addTo(map);
		basketGroup.addTo(map);
		
		geoJson =  L.geoJson();
		
		drawControl.addTo(map);
		
		map.on('draw:drawstart', function (e) {
			map.removeLayer(predefinedMaps);
		});
		map.on('draw:drawstop', function (e) {
			map.addLayer(predefinedMaps);
		});
		map.on('draw:created', function (e) {
			var type = e.layerType,
				layer = e.layer;
			if(type === 'polyline') {
				var latLngs = layer.getLatLngs();
				var pointStr =  '';
				for (var i = 0; i < latLngs.length-1; i++) {
					pointStr += latLngs[i].lat+','+latLngs[i].lng+'|';
				};
				pointStr += latLngs[latLngs.length-1].lat+','+latLngs[latLngs.length-1].lng+'';
				alert(pointStr);
			}
			
				layer.setStyle(myStyle);
				layer.areaId = areaId;
				drawnItems.addLayer(layer);
				layer.predefined = false;
				
				layer.on("mouseover", function (e) {
						layer.setStyle(hiStyle);

					});
				layer.on("mouseout", function (e) {
						layer.setStyle(myStyle);

					});
				layer.on("click", function (e) {
						drawnItemToGeoJsonFeature(layer);
						currentSelectedLayer = layer;
				});

				drawnItemsCounter++;
				layer.name = "Egendefinert "+drawnItemsCounter;
				var popupContent = "<a href=\"javascript:void(0)\" onclick=\"deleteDrawnLayer(currentSelectedLayer)\">Slett</a> - <a href=\"javascript:void(0)\" onclick=\"addToBasket(currentSelectedLayer)\">Legg i kurv</a>";

				
				layer.bindPopup(popupContent);
				layer.openPopup();
				currentSelectedLayer = layer;
				drawnItems.bringToFront();
			
		});
			displayAllTiles();
		var infoMarker = L.marker();
		$("#helpBox").html(settings.textboks);
		
map.on("popupclose",function (e) {
	if(map.hasLayer(infoMarker)) {
		map.removeLayer(infoMarker);
	}
});
addPredefinedMaps(maps);
predefinedMaps.addTo(map);
predefinedMaps.bringToFront();
		
if(hash.indexOf("area-")>-1) {
			areaId = parseInt(hash.replace("area-", ""));
			window.setTimeout(retriveGeoJsonArea, 1000, areaId);
		} else {
				infoMarker = L.marker([parseFloat(settings.centerLat), parseFloat(settings.centerLong)]).addTo(map)
			    .bindPopup(settings.markertext)
			    .openPopup();
}

}