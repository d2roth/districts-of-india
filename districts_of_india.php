<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>

<?php require( 'images/districts_of_india.svg' );?>

<script>
  var order = [
    'Adilabad',
    'Agra',
    'Ahmadabad',
    'Ahmadnagar',
    'Aizawl',
    'Ajmer',
    'Akola',
    'Alappuzha',
    'Aligarh',
    'Alirajpur',
    'Allahabad',
    'Almora',
    'Alwar',
    'Ambala',
    'Ambedkar Nagar',
    'Amravati',
    'Amreli',
    'Amritsar',
    'Anand',
    'Anantapur',
    'Anantnag',
    'Anjaw',
    'Anugul',
    'Anuppur',
    'Araria',
    'Ariyalur',
    'Arwal',
    'Ashoknagar',
    'Auraiya',
    'Aurangabad',
    'Aurangabad',
    'Azamgarh',
  ];

var index = 0;
let map = document.querySelector( '#india-map' );

// map.querySelectorAll('g[entity_type="state"]').forEach(function(g){
//   g.setAttribute( 'transition', 'all .5s' );
// });
function run(){

  // First remove all active classes
  map.querySelectorAll( '.active' ).forEach(function(e){
    e.classList.remove('active');
    e.removeAttribute( 'transform', 'translate(0 0) scale( 1 1 )');
  });
  
  let district = map.querySelector('path[title="' + order[index] + '"]');
  let state = district.closest('g');

  // Move the state to the end
  map.appendChild( state );

  state.classList.add( 'active' );
  district.classList.add( 'active' );

  var bbox = state.getBoundingClientRect();

  var scaleX = 1.1;
  var scaleY = 1.1;
  var translateX = ( bbox.width / 2 ) + ( bbox.x );
  var translateY = ( bbox.height / 2 ) + ( bbox.y );
  var translateX1 = translateX * -1;
  var translateY1 = translateY * -1;


  var transformValue = "";
      transformValue += " translate(" + translateX + " " + translateY + ")";
      transformValue += " scale( " + scaleX + " " + scaleY + " )";
      transformValue += " translate(" + ( translateX1 ) + " " + ( translateY1 ) + ")";

  let d = state;
  if (!d.scale)
    d.scale=1;
  d.x =  Math.max(maxNodeSize, Math.min(width - (d.imgwidth / 2 || 16), d.x));
  d.y =  Math.max(maxNodeSize, Math.min(height - (d.imgheight / 2 || 16), d.y));
  transformValue = "translate(" + d.x + "," + d.y + ")scale(" +d.scale+ ")";

  // state.setAttribute( 'width', bbox.width );
  // state.setAttribute( 'height', bbox.height );
  state.setAttribute( 'transform', transformValue );
  // console.log( bbox, state.getBoundingClientRect() )
  // console.log( translateX, translateX1, translateY, translateY1, transformValue );

  index++;
  if( index > order.length )
    index = 0;
}


</script>

  <script src="https://d3js.org/d3.v5.min.js"></script>

  <script>
    
  </script>
</body>
</html>