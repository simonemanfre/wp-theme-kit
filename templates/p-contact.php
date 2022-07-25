<?php 
/* Template Name: Contatti */
get_header(); ?>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <?php //CONTAINER PER GOOGLE MAP ?>
        <section id="google-map" class="c-map">

        </section>

    <?php endwhile; endif; ?>

<?php get_footer(); ?>

<?php //INSERIRE API KEY ?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=XXXXXXXXXXXXXXXXXXXXXXXXX&callback=initMap"></script>

<?php //INTEGRAZIONE GOOGLE MAP (ESEMPI COMPLESSI SUL SITO LIU-JO E DIVENTA IMPRENDITORE) ?>
<script async>
function initMap() {
  var map;
  var center = {
    lat: 34.052235,
    lng: -118.243683
  };
  var locations = [
    ['Titolo<br>\ Indirizzo', 34.046438, -118.259653],
    ['Titolo<br>\ Indirizzo', 34.046438, -118.259653],
  ];
  var markers = [];

  //GOOGLE MAP
  map = new google.maps.Map(document.getElementById('google-map'), {
    zoom: 8,
    center: center
  });

  //MARKERS
  var infowindow = new google.maps.InfoWindow({});

  var marker, count;

  for (count = 0; count < locations.length; count++) {
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[count][1], locations[count][2]),
      map: map,
      title: locations[count][0]
    });

    //PER CENTRARE LA MAPPA IN BASE AI MARKERS (se la variabile center è impostata bene e la mappa non cambia può essere omesso)
    /*
    markers.push(marker);

    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < markers.length; i++) {
      bounds.extend(new google.maps.LatLng(markers[i].getPosition().lat(), markers[i].getPosition().lng()));
    }
    map.fitBounds(bounds);
    */

    //APRIRE INFOWINDOW AL CLICK
    google.maps.event.addListener(marker, 'click', (function(marker, count) {
      return function() {
        infowindow.setContent(locations[count][0]);
        infowindow.open(map, marker);
      }
    })(marker, count));
  }
};
</script>