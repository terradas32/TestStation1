<script type="text/javascript" nonce="<?php echo $nonce; ?>">
//<![CDATA[

//Abre menu responsive
$(document).on('click', '.open-menu', function(){
	$('#top_bar').fadeOut( "fast", function(){
		$('.cabeceraResponsive').slideToggle( "slow");
	});			
})
$(document).on('click', '.open-options', function(){
	$('.cabeceraResponsive').fadeOut("fast", function(){
		$('#top_bar').slideToggle( "slow");
	});				
})

$(document).on('click', '#openLogin', function(){
	$('#register').fadeOut("fast", function(){
		$('#cover').fadeIn('slow',function(){
			$('#login').fadeIn( "slow");
		});
		
	});				
})

$(document).on('click', '#openRegister', function(){
	$('#login').fadeOut("fast", function(){
		$('#cover').fadeIn('slow', function(){
			$('#register').fadeIn( "slow");
		});
		
	});				
})

$(document).on('click', '.icon-close', function(){
	$('#register, #login').fadeOut('slow', function(){
		$('#cover').fadeOut('slow');
	})					
})
//function cargaYoutube(){
//	var $contenidoAjax = $('span.videos').html('<p style="text-align: center;"><img alt="Cargando" src="<?php echo constant('HTTP_SERVER')?>estilos/images/ajax-loader.png" /></p>');
//	$.ajax({
//		type: "POST",
//		cache: false,
//	    url : '<?php echo constant("HTTP_SERVER")?>include/cargaYoutube.php',
//        success : function (data){
//        	$contenidoAjax.html(data);
//        }
//	})
//}

//function cargaFlickr(){
//	var $contenidoAjax = $('span.listaFlickr').html('<p style="text-align: center;"><img alt="Cargando" src="<?php echo constant('HTTP_SERVER')?>estilos/images/ajax-loader.png" /></p>');
//	$.ajax({
//		type: "POST",
//		cache: false,
//	    url : '<?php echo constant("HTTP_SERVER")?>include/cargaFlickr.php',
//        success : function (data){
//        	$contenidoAjax.html(data);
//        }
//	})
//}

function cargaSlider(){
	var $contenidoAjax = $('div.cargaSlider');
	$.ajax({
		type: "POST",
		cache: false,
	    url : '<?php echo constant("HTTPS_SERVER")?>Template/sliderImg.php',
        success : function (data){
        	$contenidoAjax.html(data);        	
	    	
        }
	})
		
}


$(window).load(function(){
	$('.content-footer').css('height', 'auto');
	/**
	 * Conseguir columnas con la misma altura
	**/

	    $.fn.equalCols = function(){ //Asignamos la nueva función equalCols
	        var tallestHeight = 0; //Reinicia la variable que guarda la mayor altura
	        $(this).each(function(){ //Comprueba uno por uno los elementos con el selector indicado
	            var thisHeight = $(this).height(); //Guarda la altura del elemento
	            if (thisHeight > tallestHeight){ // Si la altura es mayor que la anterior altura guardada, se asigna a la variable tallesHeight
	                tallestHeight = thisHeight;
	            }
	        });
	        $(this).height(tallestHeight); //Se asigna la mayor altura a los elementos con el selector indicado
	    }
	    
	    $('.box-footer .item-footer').equalCols();

})

function abrirVentana(bImg, file){
	preurl ="<?php echo constant('HTTP_SERVER')?>view.php?bImg=" + bImg + "&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
	miv.focus();
}
//]]>
</script>
