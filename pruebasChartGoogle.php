<?php
//https://www.tutorialspoint.com/highcharts/highcharts_line_charts.htm
?><html>
<head>
<title></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script type="text/javascript" src="canvg-master/rgbcolor.js"></script>
    <script type="text/javascript" src="canvg-master/StackBlur.js"></script>
    <script type="text/javascript" src="canvg-master/canvg.js"></script>
</head>
<body>
<button id='b'>Run Code</button>

<!-- Highchart container -->
<div id="CP" style="width: 900px; height: 400px; margin: 0 auto"></div>
<!-- canvas tag to convert SVG -->
<canvas id="canvasCP" ></canvas>
<!-- Save chart as image on the server -->
<input type="button" id="save_img" value="saveImage"/>


<script language="JavaScript">
$(document).ready(function() {
   var chart = {
      type: 'line'
   };
   var title = {
      text: ''
   };
   var subtitle = {
      text: ''
   };
   var xAxis = {
     categories:
         ['Mentalidad Empresarial', 'Orientación y Satisfacción del Cliente', 'Orientación a Objetivos', 'Habilidad Social', 'Valores Personales' , 'Identificación con Audi'],
      type: 'category',
      labels: {
         //rotation: -90,
         style: {
            fontSize: '10px',
            fontFamily: 'sans-serif'
         }
      }
   };
   var yAxis = {
      gridZIndex: -1,
	    min: 0,
      max: 7,
      tickInterval: 1,
      title: {
         text: ''
      },
      plotLines: [{
         value: 5,
         width: 7,
         color: '#f19a9d'
      }]
   };

   var tooltip = {
       shared: true
   };
   var credits = {
      enabled: false
   };
   var legend = {

      layout: 'horizontal',
      align: 'center',
      verticalAlign: 'top',
      borderWidth: 0,
      symbolWidth: 10

   };
   var series= [{ name: 'Resultados Manager',
                  color: '#000000',
                  lineWidth: 4,
                  marker: { radius: 6, symbol: 'square' },
                  data: [5.56, 5.27, 5.25, 5.27, 5.55, 5] },
                { name: 'Resultados Autopercepción',
                  color: '#cccccc',
                  lineWidth: 4,
                  marker: { radius: 9, symbol: 'diamond' },
                  data: [5, 5.45, 4.25, 5.17, 5.09, 5.2] },
                { name: 'Resultados AC',
                  color: '#cc0033',
                  lineWidth: 4,
                  marker: { radius: 6, symbol: 'circle' },
                  data: [4.86, 4.68, 4.8, 4.89, 5.25, 5] }
              ];

   var json = {};
   json.chart = chart;
   json.title = title;
   json.subtitle = subtitle;
   json.xAxis = xAxis;
   json.yAxis = yAxis;
   json.tooltip = tooltip;
   json.legend = legend;
   json.credits = credits;
   json.series = series;
   $('#CP').highcharts(json);

   setTimeout(function(){
     //$("#save_img").click(function(){
             var svg = document.getElementById('CP').children[0].innerHTML;
             canvg(document.getElementById('canvasCP'),svg);
             var img = canvasCP.toDataURL("image/png"); //img is data:image/png;base64
             img = img.replace('data:image/png;base64,', '');
             var data = "bin_data=" + img;
             $.ajax({
               type: "POST",
               url: "savecharts.php",
               data: data,
               success: function(data){
                 alert(data);
               }
             });
    //});
  }, 1000);

});
</script>
</body>
</html>
