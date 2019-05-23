<!DOCTYPE html>
<html>
<head>
  <title></title>
  <style>
    g.active{
      fill: orange;
    }
    path.active {
      fill: green;
    }
    svg .other {
      fill: brown;
    }
    svg .own {
      fill: blue;
    }
    div.other {
      background-color: brown;
    }
    div.own {
      background-color: blue;
    }
  </style>
  <style>
    svg {
      max-width: 400px;
    }
    #india-map {
      fill: #38381B;
    }
    #india-map g:hover {
      fill: orange;
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
    }
  </style>
</head>
<body>

<?php
require( 'images/districts_of_india.svg' );
?>

<div id="info-box"></div>
<div id="timer">0</div>

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
let iteration = 0;

// map.querySelectorAll('g[entity_type="state"]').forEach(function(g){
//   g.setAttribute( 'transition', 'all .5s' );
// });

order.forEach(function(e, i){
  let district = map.querySelector('path[title="' + order[i].name + '"]');
  district.classList.add( order[i].relationship );
  district.onclick = function( event ){
    showInfo( order[i], event, district );
  };
});

map.querySelectorAll('g').forEach(function(e){
  e.addEventListener('mouseover', () => hoverState(e), false);
  e.addEventListener('mouseout', () => resetState(e), false);
  // console.log(e);
});

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
  infoBox.style.left = districtBox.x + districtBox.width + 10 + "px";
  infoBox.style.top = districtBox.y + districtBox.height + 10 + "px";
  // Yeah, we have the SVG in place but let's trim out the whitespace so the distrct is more clear
  trimSvgWhitespace( infoBox.querySelector('svg') );
}

function resetState(state){
  TweenMax.to(state, 3, {
    scale: 1,
    xPercent: 0,
    yPercent: 0,
  });
}

function hoverState(state){
  let scale = 2;
  let offset = "50%";
  TweenMax.to(state, 3, {
    scale: scale,
    xPercent: offset,
    yPercent: offset,
  });
}

/*
  Animation Plan:
    1. 

*/

function playAnimations(){
  setTimeout(function(){
    map.querySelectorAll('g').forEach(function(e, i){
      setTimeout(function(){
        hoverState(e);
        snap();
        setTimeout(function(){
          resetState(e);  
          snap();
        }, 2500);
      }, 1000 * i);
    })
  }, 300)
}

function run(scale, offset){

  // First remove all active classes
  map.querySelectorAll( '.active' ).forEach(function(e){
    e.classList.remove('own');
    TweenMax.to(e, 1, {scale: 1, xPercent: 0, yPercent: 0})
    TweenMax.to(e, 1, {scale: 1, xPercent: 0, yPercent: 0})
  });
  
  console.log( order[index].name);
  let district = map.querySelector('path[title="' + order[index].name + '"]');

  let state = district.closest('g');

  // Move the state to the end
  map.appendChild( state );
  state.appendChild( district );

  state.classList.add( 'own' );
  district.classList.add( 'own' );

  // let scale = 1.5;
  // let xPercent = -25;
  // let yPercent = -25;

  // let scale = 1;
  let xPercent = scale > 1 ? scale * -13.33 : 0;
  let yPercent = xPercent;
  
  let scaleDistrict = function(){
    TweenMax.to(state, 1, {scale:1, xPercent: 0, yPercent: 0,
      onComplete:function(){
        TweenMax.to(district, .5, {scale:scale, xPercent: xPercent, yPercent: yPercent});
      }
    });
  }

  offset = scale != 1 ? scale / -13.33 : 0;
  console.log( scale, offset )
  TweenMax.to(state, .5, {
    scale: scale,
    xPercent: offset,
    yPercent: offset,
    onComplete: scaleDistrict
    // onComplete: function(){
    //   setTimeout( function(){
    //     TweenMax.to(state, .5, {scale:1, xPercent: 0, yPercent: 0});
    //   }, 1000)}
  });
  

  index++;
  if( index > order.length )
    index = 0;
}

const timer = document.querySelector('#timer');
let initialValue = timer.innerText;
setInterval(function(){
  timer.innerText = initialValue++;
}, 1);


function snap(){
  var event = new CustomEvent('snap', { detail: 'ok' });
  window.dispatchEvent( event );
}
</script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.2/TweenMax.min.js"></script> -->
<script src="js/TweenMax.min.js"></script>

</body>
</html>