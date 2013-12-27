<?php get_header(); ?>

<?php
global $themify; ?>

<?php if( have_posts() ) while ( have_posts() ) : the_post(); ?>

<div class="contenedor_centro">
  <div class="desc_centro">

  <h1>
    Gimnasio
    <?php the_title(); ?>
  </h1>

  <?php 
    $field_values = simple_fields_values("numero,calle,cpostal,ciudad,pais,instructor");
    $members_values = simple_fields_values("miembro");
    $numero =  simple_fields_value('numero');
    $calle =  simple_fields_value('calle');
    $cpostal =  simple_fields_value('cpostal');
    $ciudad =  simple_fields_value('ciudad');
    $pais =  simple_fields_value('pais');
    $instructor =  simple_fields_value('instructor');
    $url_map =  simple_fields_value('url_mapa');
  ?>

  <h6>
    Domicilio:
  </h6>

  <?php
    echo $calle.' '.$numero.', '.$ciudad.', '.$cpostal.', '.$pais;
  ?>

  <h6>
    Instructor a Cargo:
  </h6>

  <?php echo $instructor; ?>

  <?php the_content(); ?>

  <?php 
  $ctx = stream_context_create(array(
    'http' => array(
      'timeout' => 10
    )
  )
  );
  $lugar_query = 'http://nominatim.openstreetmap.org/search?street='.$numero.'+'.$calle.'&city='.$ciudad.'&country='.$pais.'&format=json&limit=1';
  $lugar_query = str_replace(' ', '+', $lugar_query);
  $lugar_result = file_get_contents($lugar_query, 0, $ctx);
  $lugar = json_decode($lugar_result);

  $area=( $lugar[0]->boundingbox );
  $lat=$lugar[0]->lat;
  $lon=$lugar[0]->lon;
  $izq=( $area[2]);
  $arr=( $area[1]);
  $der=( $area[3]);
  $aba=( $area[0]);

  $query="http://www.openstreetmap.org/export/embed.html?bbox=$izq,$aba,$der,$arr&amp;layer=mapnik&amp;marker=$lat,$lon";

?>
  </div><!-- desc_centro -->
  <div id="mapa_centro" scrolling="no">
  </div>

  <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
  <script>
    var lon            = <?php echo $lon; ?>;
    var lat            = <?php echo $lat; ?>;
    var zoom           = 16;
    var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
    var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
    var position       = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);
    map = new OpenLayers.Map("mapa_centro");
    var mapnik         = new OpenLayers.Layer.OSM();
    map.addLayer(mapnik);
    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);
    markers.addMarker(new OpenLayers.Marker(position));
    map.setCenter(position, zoom);

  </script>

  <a class="mg_link" target="_blank" href="<?php echo $query; ?>;">Ver en mapa grande</a>
<?php endwhile; ?>

<div class="miembros_centro">
  <h5>
    Miembros de <?php the_title(); ?>:
  </h5>
  <?php
    foreach ($members_values as $mvalue) {
      $nivel = get_cimyFieldValue($mvalue, 'NIVEL');

      $user_info = get_userdata($mvalue);
        $user_email = $user_info->user_email;
        $first_name = $user_info-> user_firstname;
        $last_name = $user_info-> user_lastname;
  ?>
  <div class="miembro">
    <div class="img_cont">
      <?php echo get_avatar( $mvalue, full ); ?>
    </div>

    <div class="datos_cont">
    <?php
      echo '<br />'.$first_name.' '.$last_name;
      echo '<br />'.$user_email;
      //echo '<br />Nivel: '.$nivel;
  ?>
    </div>
  </div><!-- miembro -->

  <?php
    }
  ?>

</div><!-- miembros_centro -->

</div><!-- contenedor_centro -->
<?php //if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>
<?php get_footer(); ?>
