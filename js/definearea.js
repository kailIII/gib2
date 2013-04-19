		var result;

		map.on('draw:created', function (e) {
			
				layer = e.layer;
			currentSelectedLayer = layer;
			layer.setStyle(myStyle);
			drawnItems.addLayer(layer);
			layer.predefined = false;
			layer.on("mouseover", function (e) {
					e.target.setStyle(hiStyle);

				});
			layer.on("mouseout", function (e) {
					e.target.setStyle(myStyle);

				});
			layer.on("click", function (e) {
					currentSelectedLayer = e.target;
					map.invalidateSize();
					map.fitBounds(e.target.getBounds());
					editLayer(e.target);
					

			});
			drawnItemsCounter++;
			layer.name = "Egendefinert "+drawnItemsCounter;
		
		});	
		function editLayer(layer) {
			var nameField = document.getElementById("map-name");
			nameField.value = layer.name;
			var descField = document.getElementById("map-desc");
			descField.value = layer.description;
		}
		function saveNameField(value) {
			currentSelectedLayer.name = value;
		}
		function saveDescriptionField(value) {
			currentSelectedLayer.description = value;
		}
		function closeMapEdit() {
			$("#editMap").addClass("hidden");
			$("#editMap").removeClass("span3");
			$("#mapContainer").addClass("span7");
			$("#mapContainer").removeClass("span4");
			map.invalidateSize();
			map.fitBounds(drawnItems.getBounds());
		}
		
		function submitForm() {
			var theForm = document.forms["area-edit-form"];
			drawnItems.eachLayer(function (layer) {
			    var latLngs = layer.getLatLngs();
			    var latlngInput = document.createElement('input');
			    latlngInput.type = 'hidden';
			    latlngInput.name = 'map-latlngs[]';
			    latlngInput.value = latLngsToMysqlString(layer.getLatLngs());
			    var latLngs = layer.getLatLngs();
			    theForm.appendChild(latlngInput);
			    
			});
			var areaIdInput = document.createElement('input');
			    areaIdInput.type = 'hidden';
			    areaIdInput.name = 'areaId';
			    areaIdInput.value = areaId;
			theForm.appendChild(areaIdInput);
			theForm.submit();
		
		}
		function submitForm() {
			var theForm = document.forms["area-edit-form"];
			drawnItems.eachLayer(function (layer) {
			    var latLngs = layer.getLatLngs();
			    var latlngInput = document.createElement('input');
			    latlngInput.type = 'hidden';
			    latlngInput.name = 'map-latlngs[]';
			    latlngInput.value = latLngsToMysqlString(layer.getLatLngs());
			    theForm.appendChild(latlngInput);
			    
			});
			var areaIdInput = document.createElement('input');
			    areaIdInput.type = 'hidden';
			    areaIdInput.name = 'areaId';
			    areaIdInput.value = areaId;
			theForm.appendChild(areaIdInput);
			theForm.submit();
		
		}
		function submitMaps() {
			var theForm = document.forms["area-edit-form"];

			drawnItems.eachLayer(function (layer) {
			    var latLngs = layer.getLatLngs();
			    var name = layer.name;
				var desc =layer.description;
			    var latlngInput = document.createElement('input');
			    latlngInput.type = 'hidden';
			    latlngInput.name = 'map-latlngs[]';
			    latlngInput.value = latLngsToMysqlString(layer.getLatLngs());
			    theForm.appendChild(latlngInput);
			    var nameInput = document.createElement('input');
			    nameInput.type = 'hidden';
			    nameInput.name = 'map-names[]';
			    nameInput.value = name;
			    theForm.appendChild(nameInput);
			    var descInput = document.createElement('input');
			    descInput.type = 'hidden';
			    descInput.name = 'map-desc[]';
			    descInput.value = desc;
			    theForm.appendChild(descInput);
			    
			});
			var areaIdInput = document.createElement('input');
			    areaIdInput.type = 'hidden';
			    areaIdInput.name = 'areaId';
			    areaIdInput.value = areaId;
			theForm.appendChild(areaIdInput);
			theForm.submit();
		
		}
		map.addControl(drawControl);
		function loadTiles(areaId) {
			var osmUrl = 'tiles/'+areaId+'/{z}_{x}_{y}.png';
			var newTileMap = L.tileLayer(osmUrl, {
			      
			    });
			newTileMap.addTo(map);

		}
		function clearDisplayedMaps() {
			if(map.hasLayer(geoJson)) {
				map.removeLayer(geoJson);
				geoJson.eachLayer(function (layer) {
					map.removeLayer(layer.tileLayer);
				});
			}

		}
		var geoJson;
			function addAreaGeoJsonData(area) {	
			clearDisplayedMaps();
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
		}
		function updateName() {
			var nameField = document.getElementById("map-name");
			currentSelectedLayer.name = nameField.value;
		}
		function updateDescription() {
			var descField = document.getElementById("map-desc");
			currentSelectedLayer.description = descField.value;
		}
		var hash = window.location.hash.substring(1);
		if(hash.indexOf("tileid-")>-1) {
			areaId = parseInt(hash.replace("tileid-", ""));
			loadTiles(areaId);
		}
		if(hash.indexOf("areaid-")>-1) {
			areaId = parseInt(hash.replace("areaid-", ""));
			retriveGeoJsonArea(areaId);
		}