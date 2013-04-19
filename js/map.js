
		var geoJson;
		var basketGroup = L.featureGroup();
		basketGroup.addTo(map);
		var basketId= 0;
		var predefinedMaps = L.geoJson();
		
		function clearDisplayedMaps() {
			if(map.hasLayer(geoJson)) {
				map.removeLayer(geoJson);
				geoJson.eachLayer(function (layer) {
					map.removeLayer(layer.tileLayer);
				});
			}
			if(map.hasLayer(predefinedMaps)) {
				map.removeLayer(predefinedMaps);
				predefinedMaps.eachLayer(function (layer) {
					map.removeLayer(layer.tileLayer);
				});
			}

		}
		function addAreaGeoJsonData(area) {	
			
			geoJson =  L.geoJson(area, {
				style: areaStyle
			});
			
			geoJson.eachLayer(function (layer) {
				
				layer.predefined = true;
				layer.tileLayer = L.TileLayer.boundaryCanvas(osmUrl, {
	    			boundary: layer.getLatLngs(), 
	    			attribution: osmAttribution,
	    			minZoom: 8
				});
				
				if(!map.hasLayer(layer.tileLayer)) {
					layer.tileLayer.addTo(map);
				}
				
			});

			geoJson.addTo(map);
			map.fitBounds(geoJson.getBounds());
			map.zoomIn();
			predefinedMaps.bringToFront();
		}
		
		function addPredefinedMaps(area) {	
			
			predefinedMaps =  L.geoJson(area, {
				style: myStyle
			});
			
			predefinedMaps.eachLayer(function (layer) {
				layer.name = layer.feature.properties.name;
				layer.description = layer.feature.properties.description;
				layer.areaId = areaId;
				layer.predefined = true;
				layer.tileLayer = L.TileLayer.boundaryCanvas(osmUrl, {
	    			boundary: layer.getLatLngs(), 
	    			attribution: osmAttribution,
	    			minZoom: 8
				});
				
					layer.on("mouseover", function (e) {
							layer.setStyle(hiStyle);

						});
					layer.on("mouseout", function (e) {
							layer.setStyle(myStyle);

						});
					layer.on("click", function (e) {
							currentSelectedLayer = layer;
					});
					var popupContent = "<h5>"+layer.name+"</h5><p>"+layer.description+"</p><p><a href=\"javascript:void()\" onclick=\"addToBasket(currentSelectedLayer)\">Legg i kurv</a></p>";
					layer.bindPopup(popupContent);
					
					
			});

			predefinedMaps.addTo(map);
			predefinedMaps.bringToFront();
			
			
		}


		function addToBasket(layer) {
			drawnItems.removeLayer(layer);
			basketGroup.addLayer(layer);
			map.addLayer(layer.tileLayer);
			layer.closePopup();
			layer.basketId=basketId++;
			var lastItem= $("#basketControls");
			var newLink = document.createElement("a");
			newLink.href = "javascript:void()";
			newLink.onclick = function() {
				showLayer(layer);
			};
			newLink.id =  "basketLayerLink-"+layer.basketId;
			newLink.innerHTML = layer.name;
			var deleteLink = document.createElement("a");
			deleteLink.href = "javascript:void()";
			deleteLink.onclick = function() {
				removeLayer(layer);
			};
			deleteLink.id =  "basketDeleteLayerLink-"+layer.basketId;
			deleteLink.innerHTML = "x";
			lastItem.prepend(newLink);
			$("#mapList").after(createMapDivs(layer));
			var popupContent = "<h4 class=\"mapTitle\">"+
						layer.name + "</h4><a href=\"javascript:void()\" onclick=\"removeFromBasket(currentSelectedLayer)\">Fjern fra kurv</a>";
			layer.bindPopup(popupContent);

		}
		function showLayer(layer) {
			if(!map.hasLayer(layer)) {
				layer.addTo(map);
				if(!map.hasLayer(layer.tileLayer)) {
					layer.tileLayer.addTo(map);
				}
			}

			
			map.fitBounds(layer.getBounds());
		}

		map.addControl(drawControl);

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
			col1.className = "span11";
			var col2 = document.createElement("div");
			col2.className = "span1";
			var mapLink = document.createElement("a");
			mapLink.innerHTML = layer.name;
			mapLink.href = "javascript:void()";
			mapLink.onclick = function () {
				showLayer(layer);
			};
			var deleteLink = document.createElement("a");
			deleteLink.innerHTML = "Slett";
			deleteLink.href = "javascript:void()";
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
			map.removeLayer(layer.tileLayer)
			layer.closePopup();
			map.removeLayer(layer);
		}
		var areaId;
		function retriveGeoJsonArea(id) {
			clearDisplayedMaps();
			map.removeLayer(infoMarker);
			areaId = id;
			osmUrl = 'tiles/'+id+'/{z}_{x}_{y}.png';
			$.get('php/sql2geojson.php?id='+id, function (data) {
			     addAreaGeoJsonData(geoarea);
			  }, "script");
			retriveMaps(id);
			
		}
		function retriveMaps(id) {
			areaId = id;
			$.get('php/sql2geojson.php?type=map&id='+id, function (data) {
			     addPredefinedMaps(geoarea);
			  }, "script");
			
		}
		map.on('draw:drawstart', function (e) {
			map.removeLayer(predefinedMaps);
		});
		map.on('draw:drawstop', function (e) {
			map.addLayer(predefinedMaps);
		});
		map.on('draw:created', function (e) {
			var type = e.layerType,
				layer = e.layer;
			layer.setStyle(myStyle);
			layer.areaId = areaId;
			drawnItems.addLayer(layer);
			layer.predefined = false;
			layer.tileLayer = L.TileLayer.boundaryCanvas(osmUrl, {
    			boundary: layer.getLatLngs(), 
    			attribution: osmAttribution
			});
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
			var popupContent = "<a href=\"javascript:void()\" onclick=\"deleteDrawnLayer(currentSelectedLayer)\">Slett</a> - <a href=\"javascript:void()\" onclick=\"addToBasket(currentSelectedLayer)\">Legg i kurv</a>";

			
			layer.bindPopup(popupContent);
			layer.openPopup();
			currentSelectedLayer = layer;
		});
		function deleteDrawnLayer(layer) {
			map.removeLayer(layer.tileLayer);
			drawnItems.removeLayer(layer);
		}
		function showOrders() {
			basketGroup.addTo(map);
			basketGroup.eachLayer(function (layer) {
				if(!map.hasLayer(layer.tileLayer)) {
					layer.tileLayer.addTo(map);
				}
				layer.tileLayer.redraw();
				var popupContent = "<h4 class=\"mapTitle\">"+
						layer.name + "</h4><a href=\"javascript:void()\" onclick=\"removeFromBasket(currentSelectedLayer)\">Remove from basket</a>";
				
				layer.bindPopup(popupContent);

			});
			map.fitBounds(basketGroup.getBounds());


		}

		function emptyBasket() {
			basketGroup.eachLayer(function (layer) {
				map.removeLayer(layer.tileLayer);
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
			mapContainer.addClass("span5");
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
			mapContainer.removeClass("span5");
			orderForm.addClass("hidden");
			orderForm.removeClass("span6");
			map.invalidateSize();
			
		}
		var infoMarker = L.marker();
		var hash = window.location.hash.substring(1);
		if(hash.indexOf("area-")>-1) {
			areaId = parseInt(hash.replace("area-", ""));
			retriveGeoJsonArea(areaId);
		} else {
			infoMarker = L.marker([63.37908, 10.43305]).addTo(map)
		    .bindPopup('<h3>Velkommen til Okart!</h3><h5>Før du begynner må du trykke på et område i listen til venstre for å se det i kartet.</h5><h6>Etter det kan du trykke på et kartutsnitt markert med rødt for å se mer informasjon og for å legge det til bestilling.</h6><h6>Du kan også bruke verktøyene til venstre for å tegne ditt eget.</h6>')
		    .openPopup();
		}

