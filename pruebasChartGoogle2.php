<?php
//https://www.tutorialspoint.com/highcharts/highcharts_line_charts.htm
?><html>
<head>
<title></title>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
   <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
<div id="container" style="width: 948px; height: 400px; margin: 0 auto"></div>
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
      type: 'category',
      labels: {
         rotation: -90,
         style: {
            fontSize: '13px',
            fontFamily: 'Verdana, sans-serif'
         }
      }
   };
   var yAxis = {
	min: 0,
      title: {
         text: ''
      },
      plotLines: [{
         value: 0,
         width: 1,
         color: '#666666'
      }]
   };

   var tooltip = {
      valueSuffix: ''
   };
   var credits = {
      enabled: false
   };
   var legend = {
      layout: 'horizontal',
      align: 'top',
      verticalAlign: 'top',
      borderWidth: 0
   };

   var series =  [
      {
         name: 'Perfil Requerido',
         data: [5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5]
      },
      {
         name: 'Resultados Manager',
         data: [2,4,6,2,3,3,5,3,3,1,2,7,7,1,4,7,1,1,6,6]
      },
      {
         name: 'Resultados Autopercepción',
         data: [2,4,7,2,5,3,4,6,3,7,5,5,2,7,1,1,7,5,1,7]
      }
      ,
      {
         name: 'Resultados AC',
         data: [2,4,6.5,7,2,1,2,3,2,7,7,3,4,4,2,3,6,5,7,7]
      }
   ];

   var json = {};

   json.title = title;
   json.subtitle = subtitle;
   json.xAxis = xAxis;
   json.yAxis = yAxis;
   json.tooltip = tooltip;
   json.legend = legend;
   json.series = series;
   json.credits = credits;
   json.series = series;
   $('#container').highcharts(json);
});
</script>
</body>
</html>
