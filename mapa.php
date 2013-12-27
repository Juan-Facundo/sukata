<html><body>
  <div id="mapdiv"></div>

<?php
$lat = -38.954;
$lon = -68.078;
?>
  <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
  <script>
    var lon            = <?php echo $lon; ?>;//-68.078;
    var lat            = <?php echo $lat; ?>;//-38.954;
    var zoom           = 16;
 
    var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
    var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
    var position       = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);
 
    map = new OpenLayers.Map("mapdiv");
    var mapnik         = new OpenLayers.Layer.OSM();
    map.addLayer(mapnik);
 
    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);
    markers.addMarker(new OpenLayers.Marker(position));
 
    map.setCenter(position, zoom);



  </script>
</body></html>
