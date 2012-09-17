<?
if (isset($_SESSION['user_id']))
{
?>
	<div id="map" style="width:99%;height:750px;margin:10px auto;border:2px solid #000;"></div>
	
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false"></script>
    <script type="text/javascript">
	
	<?
		echo $markers;
	?>   
	var infowindow = null;
		infowindow = new google.maps.InfoWindow({
                content: "loading..."
            });
		
	
		
	
		function percentProjection() {};
		
		percentProjection.prototype.fromLatLngToPoint = function(latlng) 
		{
			// pixel size of tile at zoom 0
			var max = 256;
			
			// define bottom-right corner as (50, 50)
			var x = max * latlng.lng() / 50;
			var y = max * latlng.lat() / 50;
			return new google.maps.Point(x, y);
		};

		percentProjection.prototype.fromPointToLatLng = function(pixel) 
		{
			// inverse conversion
			var max = 256;
			var lng = pixel.x / max * 50;
			var lat = pixel.y / max * 50;
			return new google.maps.LatLng(lat, lng);
		};

	
			/*
			 * = Config
			 * ----------------
			 */
				//var pixelOrigin_ = new google.maps.Point(128, 128); 
				//var pixelsPerLonDegree_ = 256 / 360; 
				//var pixelsPerLonRadian_ = 256 / (2 * Math.PI);
				var repeatOnXAxis = false; // Do we need to repeat the image on the X-axis? Most likely you'll want to set this to false
				var blankTilePath = 'tiles/_empty.jpg'; // Path to a blank tile when repeat is set to false
				var maxZoom = 5; // Maximum zoom level. Set this to the first number of the last tile generated (eg. 5_31_31 -> 5)
				var marker = null;
			
			
			/*
			 * Helper function which normalizes the coords so that tiles can repeat across the X-axis (horizontally) like the standard Google map tiles.
			 * ----------------
			 */
			
				function getNormalizedCoord(coord, zoom) {
			
					if (!repeatOnXAxis) return coord;
			
					var y = coord.y;
					var x = coord.x;

					// tile range in one direction range is dependent on zoom level
					// 0 = 1 tile, 1 = 2 tiles, 2 = 4 tiles, 3 = 8 tiles, etc
					var tileRange = 1 << zoom;

					// don't repeat across Y-axis (vertically)
					if (y < 0 || y >= tileRange) {
						return null;
					}

					// repeat across X-axis
					if (x < 0 || x >= tileRange) {
						x = (x % tileRange + tileRange) % tileRange;
					}

					return {
						x: x,
						y: y
					};
		
				}


			/*
			 * Main Core
			 * ----------------
			 */

				window.onload = function() {
				
					// Define our custom map type
					var customMapType = new google.maps.ImageMapType({
						getTileUrl: function(coord, zoom) {
							var normalizedCoord = getNormalizedCoord(coord, zoom);
							if(normalizedCoord && (normalizedCoord.x < Math.pow(2, zoom)) && (normalizedCoord.x > -1) && (normalizedCoord.y < Math.pow(2, zoom)) && (normalizedCoord.y > -1)) {
								return 'tiles/lingor/' + zoom + '_' + normalizedCoord.x + '_' + normalizedCoord.y + '.png';
							} else {
								return blankTilePath;
							}
						},
						tileSize: new google.maps.Size(256, 256),
						maxZoom: maxZoom,
						name: 'Champagne in the park'
						
					});
					
					customMapType.projection = new percentProjection;
					
					
					// Creating a marker and positioning it on the map
					
			
					// Basic options for our map
					var myLatlng = new google.maps.LatLng(25,25);
					var myOptions = {
						center: myLatlng,
						zoom: 2,
						minZoom: 0,
						streetViewControl: false,
						mapTypeControl: false,
						mapTypeControlOptions: {
							mapTypeIds: ["custom"]
						}
					};

					// Init the map
					var map = new google.maps.Map(document.getElementById('map'), myOptions);
					//3996.34,4222.79,0.00
					//var x = 3996.34;
					//var y = 4222.79; 
					//var lingorx = (x / 100) / 2;
					//var lingory = (y / 100) / 2;
					
					for (i = 0; i < markers.length - 1; i++){
						var x = markers[i][2];//(markers[0][2]);
						var y = markers[i][3] - 1024; //(markers[0][3]);
						var lingorx = x / 200;
						var lingory = y / 200;
						var marker = new google.maps.Marker({
							position: new google.maps.LatLng(50 - lingory, lingorx), 
							map: map,
							title: markers[i][0],
							clickable: true,
							icon: markers[i][5],
							zIndex:  markers[i][4]
						});
						marker.setDraggable(true);
						google.maps.event.addListener(marker, 'click', (function(marker, i) {
							return function() {
								infowindow.setContent(markers[i][1]);
								infowindow.open(map, marker);
							}
						})(marker, i));
				}
					
					
					// Hook the our custom map type to the map and activate it
					map.mapTypes.set('custom', customMapType);
					map.setMapTypeId('custom');	
				}
		</script>
<?
}
else
{
	header('Location: index.php');
}
?>