<?php
$colours = [
  'default' => 'rgba(227,207,243, 1)',
  'active' => 'rgba(255,189,189, 1)',
  'hover' => 'rgba(255,213,247, 1)',
  'own' => 'rgba(159,101,255, 1)',
  'other' => 'rgba(241,203,154, 1)',
];
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <style>
    g {
      fill: <?= $colours['default'];?>;
      stroke: <?= $colours['default2'];?>;
    }
    g.active{
      fill: <?= $colours['active'];?>;
    }
    path.active {
      fill: <?= $colours['active'];?>;
      stroke: <?= $colours['active'];?>;
    }
    svg .other {
      fill: <?= $colours['other'];?>;
      stroke: <?= $colours['other'];?>;
    }
    svg .own {
      fill: <?= $colours['own'];?>;
      stroke: <?= $colours['own'];?>;
    }
    div.other {
      background-color: <?= $colours['other'];?>;
    }
    div.own {
      background-color: <?= $colours['own'];?>;
    }
  </style>
  <style>
    svg {
      max-width: 100%;
      max-height: 100vh;
    }
    #india-map {
      fill: #38381B;
    }
    #india-map g:hover {
      fill: <?= $colours['hover'];?>;
      stroke: <?= $colours['hover'];?>;
    }
    #info-box{
      position: absolute;
      z-index: 9;
      box-shadow: 0 0 3px black;
      min-width: 250px;
    }
    #info-box svg {
      background: white;
      max-width: 150px;
      max-height: 150px;
    }
    #timer {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 100px;
      background: black;
      color: #fff;
      padding: 5px;
      display: none;
    }
    #legend {
      position: fixed;
      top: 0;
      right: 0;
      margin-right: 15px;
    }
    #legend span{
      font-weight: 700;
    }
    #legend ul {
      padding-left: 0;
      margin-top: 5px;
    }
    #legend li {
      list-style: none;
      padding: 5px;
      margin-bottom: 5px;
    }
    #districts_wrapper{
      position: absolute;
      bottom: 0;
      right: 0;
      max-height: 50vh;
    }
  </style>
</head>
<body>

<?php
require( 'images/districts_of_india.svg' );
?>

<div id="info-box"></div>
<div id="timer">0</div>
<div id="legend">
  <span>Legend</span>
  <ul>
    <?php
    foreach( ['BHI' => 'own', 'Independant' => 'other'] as $title => $key ):
      printf( '<li style="background-color:%s">%s District</li>', $colours[$key], $title );
    endforeach;
    ?>
  </ul>
</div>

<div id="districts_wrapper">
  <ol id="districts_inner"></ol>
</div>

<script>
  var order = [
    {
      name: 'Kancheepuram',
      relationship: 'own',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'Vellore',
      relationship: 'own',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'Tirunelveli',
      relationship: 'own',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'Kanniyakumari',
      relationship: 'own',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'Chennai',
      relationship: 'own',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'Dingugul',
      relationship: 'own',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'Hugli',
      relationship: 'own',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'New Delhi',
      relationship: 'other',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'Coimbatore',
      relationship: 'other',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'Lucknow',
      relationship: 'other',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'Dehradun',
      relationship: 'own',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
    {
      name: 'Bangalore',
      relationship: 'own',
      population: 0,
      demographics: {
        literacy: '25%',
        sex_ratio: 1000,
      },
    },
  ];

var index = 0;
let map = document.querySelector( '#india-map' );
let districts_inner = document.querySelector( '#districts_inner' );
let iteration = 0;

// Open more information for each district on click
order.forEach(function(e, i){
  let district = map.querySelector('path[title="' + order[i].name + '"]');
  // district.classList.add( order[i].relationship );
  district.onclick = function( event ){
    showInfo( order[i], event, district );
  };
});

function resetState(state){
  TweenMax.to(state, 3, {
    scale: 1,
  });
}

function hoverState(state){
  let scale = 2;
  TweenMax.to(state, 3, {
    scale: scale,
    transformOrigin: 'center',
  });
}

let lastDroppedDistrict = 0;
let firstRun = true;
let loop = false;
function dropDistricts(){
  // Some calculations suggest I can screen capture about 13 frames/second
  // for (let i = 0;i < order.length; i++ ){
    // setTimeout(function(){
      let district_object = order[lastDroppedDistrict];
      let district = map.querySelector('path[title="' + district_object.name + '"]');
      let state = district.closest('g');

      map.appendChild(state);
      state.appendChild(district);
      if( firstRun )
        districts_inner.insertAdjacentHTML("beforeend", "<li class='" + district_object.relationship + "'>" + district_object.name + " (" + state.attributes.title.value + ")</li>"); 

      TweenMax.to( district, 0, {
        scale: 3,
        transformOrigin: 'center',
        onComplete: () => {
          district.classList.add( district_object.relationship );
        }
      });
      TweenMax.to( district, 1, {
        scale: 1,
        transformOrigin: 'center',
        onComplete: () => {
          lastDroppedDistrict++;
          if( loop || firstRun ){
            if( lastDroppedDistrict == order.length){
              firstRun = false;
              lastDroppedDistrict = 0;
              if( !loop )
                return;
            }
            dropDistricts();
          }
        },
      });
    // }, i * 1500);
  // }
}

function run(scale, offset){

  // First remove all active classes
  map.querySelectorAll( '.active' ).forEach(function(e){
    TweenMax.to(e, 1, {scale: 1})
  });
  
  let district = map.querySelector('path[title="' + order[index].name + '"]');
  let state = district.closest('g');

  // Move the state to the end
  map.appendChild( state );
  state.appendChild( district );

  district.classList.add('active');
  state.classList.add('active');
  
  let scaleDistrict = function(){
    TweenMax.to(state, 1, {
      scale: 1.1,
      onComplete:function(){
        TweenMax.to(district, .5, {
          scale: scale,
          transformOrigin: 'center'
        });
      }
    });
  }

  TweenMax.to(state, .5, {
    scale: scale,
    transformOrigin: 'center',
    onComplete: scaleDistrict
  });
  

  index++;
  if( index > order.length )
    index = 0;
}

function trimSvgWhitespace( svg ) {
  const box = svg.getBBox(), // <- get the visual boundary required to view all children
        viewBox = [box.x, box.y, box.width, box.height].join(" ");

  // set viewable area based on value above
  svg.setAttribute("viewBox", viewBox);
}

function showInfo( data, event, district ){
  let state = district.parentNode.cloneNode(true);

  state.querySelectorAll('path').forEach(function(e){
    e.removeAttribute('class');
    if( e == district )
      e.classList.add('active');
  });

  console.log( state );

  const template = `
  <div>
    <h1>${data.name}</h1>
    <svg viewBox="50 200 950 1100">${state.outerHTML}</svg>
    <h2>Population: ${data.population}</h2>
    <h3>Demographics</h3>
    <ul>
      <li>Literacy: ${data.demographics.literacy}</li>
      <li>Sex Ratio: ${data.demographics.sex_ratio}</li>
    </ul>
  </div>
  `
  const infoBox = document.querySelector('#info-box');
  const districtBox = district.getBoundingClientRect();

  infoBox.className = "";
  infoBox.classList.add( data.relationship );
  infoBox.innerHTML = template;
  infoBox.style.display = "block";
  infoBox.style.x = districtBox.x + districtBox.width + 10 + "px";
  infoBox.style.y = districtBox.y + districtBox.height + 10 + "px";
  // Yeah, we have the SVG in place but let's trim out the whitespace so the distrct is more clear
  trimSvgWhitespace( infoBox.querySelector('svg') );
}

// const timer = document.querySelector('#timer');
// let initialValue = timer.innerText;
// setInterval(function(){
//   timer.innerText = initialValue++;
// }, 1);


function snap(){
  var event = new CustomEvent('snap', { detail: 'ok' });
  window.dispatchEvent( event );
}
</script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.2/TweenMax.min.js"></script> -->
<script src="js/TweenMax.min.js"></script>

</body>
</html>