<?php
//https://www.tutorialspoint.com/highcharts/highcharts_line_charts.htm
?><html>
<head>
<title></title>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
   <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
   <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
   <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
</head>
<body>
<div id="CP" style=" margin: 0 auto"></div>
<script language="JavaScript">
$(document).ready(function() {
  Morris.Line({
          element:'CP',
          data: [
                    {week:'Mentalidad Empresarial', "PR":'5',  "RM":'5.56',  "RA":'5', "RAC": '4.86'},
                    {week:'Orientación y Satisfacción del Cliente', "PR":'5',  "RM":'5.27',  "RA":'5.45', "RAC": '4.68'},
                    {week:'Orientación a Objetivos', "PR":'5',  "RM":'5.25',  "RA":'4.25', "RAC": '4.8'},
                    {week:'Habilidad Social', "PR":'5',  "RM":'5.27',  "RA":'5.17', "RAC": '4.89'},
                    {week:'Valores Personales', "PR":'5',  "RM":'5.55',  "RA":'5.09', "RAC": '5.29'},
                    {week:'Identificación con Audi', "PR":'5',  "RM":'5',  "RA":'5.2', "RAC": '5'},
           ],
           lineColors: ['#f19a9d', '#000000', '#CCCCCC', '#cc0033'],
           lineWidth: ['5px', '3px', '3px', '3px'],
           xkey: 'week',
           ykeys: ['PR','RM', 'RA', 'RAC'],
           labels: ['Perfil Requerido', 'Resultados Manager', 'Resultados Autopercepción', 'Resultados AC'],
           xLabelAngle: 90,
           parseTime:false,
           hideHover:true,
           stacked: true,
           resize:true
        }).on('click', function(i, row){
          console.log(row);
    });
});
</script>
</body>
</html>
