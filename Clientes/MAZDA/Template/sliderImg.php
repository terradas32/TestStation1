<?php
	include_once '../include/Configuracion.php';
	include_once('../include/Idiomas.php');
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include_once(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once('../' . constant("DIR_WS_COM") . 'adodb-pager.inc.php');

	require_once('../' . constant("DIR_WS_COM") . "Utilidades.php");
	require_once('../' . constant("DIR_WS_COM") . "Web_slider/Web_sliderDB.php");
	require_once('../' . constant("DIR_WS_COM") . "Web_slider/Web_slider.php");


	include_once('../include/conexion.php');


  	$cUtilidades= new Utilidades();


  	  	/*
	 Iniciamos Home
	 */

  	$cWeb_slider = new Web_slider();
	$cWeb_sliderDB = new Web_sliderDB($conn);
	$cWeb_slider->setCode('MAZDA');
	$cWeb_slider->setOrderBy('orden');
	$cWeb_slider->setOrder('ASC');
	$sqlWeb_slider = $cWeb_sliderDB->readLista($cWeb_slider);
	$listaWeb_slider = $conn->Execute($sqlWeb_slider);

?>
<?php
if($listaWeb_slider->recordCount() > 0){
?>
<div id="sliderImgs">
	<ul class="bxslider">
		<?php
		while (!$listaWeb_slider->EOF){
			/*
			Preguntamos si existe la imagen.
			*/
			$ImgName = basename($listaWeb_slider->fields['pathImagen']);
			$sPathName = $listaWeb_slider->fields['pathImagen'];
			$img=@getimagesize(constant("DIR_WS_GESTOR") . $sPathName);
			$bIimg = (empty($img)) ? 0 : 1;
			if($bIimg){
				$PathImg = constant("DIR_WS_GESTOR") . $sPathName	;
				$urlEnlace = $listaWeb_slider->fields['urlDestino'];
				if(!empty($urlEnlace)){
					 $urlEnlace = $cUtilidades->addhttp($urlEnlace);
					?>
				  	<li>
				  		<a href="<?php echo $urlEnlace;?>" title="<?php echo $listaWeb_slider->fields['titulo'];?>" target="_blank">
				  			<img class="opacity" alt="<?php echo $listaWeb_slider->fields['titulo'];?>" src="<?php echo $PathImg;?>" />
				  		</a>
				  	</li>
				  	<?php
				}else{
					?>
				  	<li><img class="opacity" alt="<?php echo $listaWeb_slider->fields['titulo'];?>" src="<?php echo $PathImg;?>" /></li>
				  	<?php
				}
			}
	  	$listaWeb_slider->moveNext();
		}
	  	?>
	</ul>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#sliderImgs').fadeIn('fast', function(){
			$('.bxslider').bxSlider({
				auto             : 'true',
				preloadImages    : 'all',
				autoHover        : 'true',
				pause            : '4000',
				speed            : '800',
				mode             : 'fade',
				hideControlOnEnd : 'true',
				adaptiveHeight   : 'true',
				controls         : 'false',
				pager            : 'false',
				video			 : 'true',
				onSliderLoad     : function(){
					$( "ul.bxslider li img" ).removeClass("opacity");
				}
			});
		})
	})
</script>
<?php
}
?>
