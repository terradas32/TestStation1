<?php
if (! defined ( "DIR_FS_DOCUMENT_ROOT" )) {
	require_once ("Configuracion.php");
	require_once ("SeguridadTemplate.php");
}
?>
<!-- 	
	<meta http-equiv="refresh" content="1;url=<?php echo constant("HTTP_SERVER");?>noscript.php?fLang=<?php echo $sLang;?>" />
-->
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/codigo.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/comun.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/noback.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery-1.7.1.min.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.requireScript-1.2.1.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.validationEngine.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.validationEngine-<?php echo $sLang;?>.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery-ui-1.8.13.custom.min.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/i18n/jquery-ui-i18n.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.blockUI.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.alert.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/loader.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/toggle.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/sameHeight.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/prettyCheckboxes.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.dd.js"></script>
<!-- Codigo para combo jquery -->
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/test.js"></script>
<!-- Codigo para combo jquery -->
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/colorpicker.js"></script>
<!-- Codigo para elegir color -->
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.form.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.tooltip.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/bsn.AutoSuggest_2.1.3_comp.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.Jcrop.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.scrollTo-1.4.2-min.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/waypoints.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/slimScroll.min.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/fileinput.jquery.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/chosen.jquery.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.textareaCounter.plugin.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.tipTip.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery-notify.js"></script>
<!-- Upload Imagenes -->
<script type="text/javascript"
	src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/uploadImg/plupload.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/uploadImg/plupload.html5.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/uploadImg/plupload.browserplus.js"></script>
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/uploadImg/jquery.plupload.queue.js"></script>
<!-- Fin Upload Imagenes -->

<!-- Codigo para scroll en listas -->
<script language="javascript" type="text/javascript"
	src="<?php echo constant("HTTP_SERVER");?>codigo/jquery.tinyscrollbar.js"></script>
<!-- Fin Codigo para scroll en listas -->
<!-- Codigo para Banners -->
<script language="javascript" type="text/javascript"
	src="codigo/cycle.js"></script>
<script language="javascript" type="text/javascript">
            $.fn.cycle.defaults.speed = 800;
            $.fn.cycle.defaults.timeout = 8000;
            $(document).ready(function(){
				$('.bannerNegocia').cycle({
                	pause: true,
                    before: function(){
					if ($('.bannerNegocia a').is (':visible')){ setImpresion($(this).attr('name')); }}
                });
                $('.bannerNegocia img').click(function(){
					setClick($(this).attr('id'));
				})
            }); 
            function setImpresion(id){ $("#callBackBanners").show().load("jQuerySet.php",{sPG:"setImpresionBanner",id:id}).fadeIn("slow"); }
            function setClick(id){ $("#callBackBanners").show().load("jQuerySet.php",{sPG:"setClickBanner",id:id}).fadeIn("slow"); }
      </script>
<!-- FIN Codigo para Banners -->
<script language="javascript" type="text/javascript">
	//<![CDATA[
    var options = {
        	type:			'POST',
        	target:			'#contenido',   	
        	clearForm:		true,
        	resetForm:		true,
        	cache:          false,
        	beforeSubmit:  	showRequest,
            success:       	showResponse  // post-submit callback 
        };
    function showRequest(){
        $('form').validationEngine('hideAll');
        lon();
        return true;
	} 
    function showResponse(data){
    	iTotalCabecera = anchoCabecera();
		loff();
    } 
	function enviarMenu(pagina, modo, clicado){
		var f=document.forms[0];
		if (pagina != 'null'){
			f.MODO.value = modo;
			if (f.ORIGEN != null){
				f.ORIGEN.value = "";
			}
			if (f.Clicado != null){
				f.Clicado.value = clicado;
			}
			f.action = pagina;
	        $("form").ajaxSubmit(options);
		}
	}
	function getMenuClicado(){
		var f=document.forms[0];
		var sMenus = "<?php echo (!empty($_POST["Clicado"])) ? $_POST["Clicado"] : "";?>";
		var aMenus = "";
		if (sMenus != ""){
			aMenus = sMenus.split("-");
			$(".cont0, .cont1, .cont2, .cont3").hide();
			for(i=0; i <= aMenus.length; i++){
				if (i == 0){
					$('.header0').removeClass('active0');
					$('.top').removeClass('selected');
					$('.header1').removeClass('active1');
					$('.header2').removeClass('active2');
					$('.header3').removeClass('active3');
					//$(".cont0").slideUp();
					if ($("#m" + aMenus[i]).siblings('.cont0').is (':hidden')) {
						$("#m" + aMenus[i]).parents('div.top').addClass('selected');
						$("#m" + aMenus[i]).siblings('.cont0').slideDown();	
					}else{
						$("#m" + aMenus[i]).siblings('.cont0').slideUp();
					}
					$("#m" + aMenus[i]).addClass('active0');
				}
				if (i == 1){
					$(".cont1, .cont2, .cont3").slideUp();
					$('.header1').removeClass('active1');
					$('.header2').removeClass('active2');
					if ($("#m" + aMenus[0] + "-" + aMenus[i]).parent().siblings('.cont1').is (':hidden')) {
						$("#m" + aMenus[0] + "-" + aMenus[i]).parent().siblings('.cont1').slideDown();
					}
					$("#m" + aMenus[0] + "-" + aMenus[i]).addClass('active1');
				}
				if (i == 2){
					$(".cont2, .cont3").slideUp();
					$('.header2').removeClass('active2');
					$("#m" + aMenus[i]).addClass('active2');
					if ($("#m" + aMenus[0] + "-" + aMenus[1] + "-" + aMenus[i]).parent().siblings('.cont2').is (':hidden')) {
						$("#m" + aMenus[0] + "-" + aMenus[1] + "-" + aMenus[i]).parent().siblings('.cont2').slideDown();	
					}
					$("#m" + aMenus[0] + "-" + aMenus[1] + "-" + aMenus[i]).addClass('active2');
				}
				if (i == 3){
					$(".cont3").slideUp();
					$('.header3').removeClass('active3');
					$("#m" + aMenus[i]).addClass('active3');
					if ($("#m" + aMenus[0] + "-" + aMenus[1] + "-" + aMenus[2] + "-" + aMenus[i]).parent().siblings('.cont3').is (':hidden')) {
						$("#m" + aMenus[0] + "-" + aMenus[1] + "-" + aMenus[2] + "-" + aMenus[i]).parent().siblings('.cont3').slideDown();	
					}
					$("#m" + + aMenus[0] + "-" + aMenus[1] + "-" + aMenus[2] + "-" + aMenus[i]).addClass('active3');
				}
			}
		}
	}
	function abrirVentana(bImg,file){
		preurl = "view.php?bImg="+ bImg +"&File=" + file;
		prename = "File";
		var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no");
		miv.focus();
	}
	function setTitulo(titulo){
		if (eval("document.getElementById('TituloSup').innerHTML") != null){
			document.getElementById('TituloSup').innerHTML=titulo;
			document.forms[0].TituloSupOpcion.value=titulo;
		}
	}
	function autoComplete(){
        var i = 0;
        for(var node; node = document.getElementsByTagName('input')[i]; i++){
	        var type = node.getAttribute('type').toLowerCase();
	        if(type == 'text'){
    	        node.setAttribute('autocomplete', 'off');
        	}
    	}
	}
	function volver(){
		$("#fVolver").val("1");
		$("form").ajaxSubmit(options);
	}
	function _body_onload(){	loff();	}
	function _body_onunload(){	lon();	}
	<?php include_once("msg_error_JS.php");?>
	//]]>
	</script>


<script type="text/javascript">
function scrollListas(){
	var altoTabla = $('.overview table').height();
	var anchoTabla = $('.overview table').width();
	$('.viewport').css({
		'height': altoTabla + 4
	});
	$('.overview').css({
		'height': altoTabla + 4, 
		"width": anchoTabla
	});
	$('.contenedorTabla').tinyscrollbar({ axis: 'x'});	
}
scrollListas();
</script>
<script language="javascript" type="text/javascript">
//<![CDATA[
		function anchoSelect(){
			var sClass = $('.chzn-container').parent('p').children('select').attr('class');
			var $this = $('.chzn-container').parent('p').children('select');
			if (sClass != null){
				var idx = sClass.indexOf("required");
				if (idx != -1){
					$($this).next('.chzn-container').children('a.chzn-single, div.chzn-drop').addClass("required");	
				}
			}
	   	}
		function prettySelect(){
			$("form select").not('.noChoise').chosen(); 
			anchoSelect();
		}
		$(document).ready(function() {
			getMenuClicado();
			prettySelect();
	        $('.ocultaDerecha').click(function(){
		        	$(this).hide();
		        	 $('.abreDerecha').show();
			        		$('#banners').animate({
									opacity: '0'
				        		},500, function(){
					        		$('#derecha').animate({
						        		width: '25px',
										 height: '25px'
							       },500,function(){
										$('#contenido , .cabeceraFija ' ).animate({
											width: '71%'
											})
								       })
					        })
					        $('.subeTop').animate({
					        	right: '2%'
				        	});
					        $('.bannerNegocia').cycle('pause');
					        $('.scrollbar').animate({
								opacity: '0'
			        		})
					        setTimeout(function(){
					        	scrollListas();
					        	anchoSelect();
					        	$('.scrollbar').animate({
									opacity: '1'
				        		})
					        }, 1500);
					        
		        })
		     $('.abreDerecha').click(function(){
		    	 $(this).hide();
		    	 $('.ocultaDerecha').show();
		    	 $('#contenido , .cabeceraFija ' ).animate({
						width: '50%'
					},500,function(){
						$('#derecha').animate({
			        		width: '24%',
							 height: '100%'
				       },500,function(){
				    	   $('#banners').animate({
								opacity: '1'
			        		})
					    })
					})
					$('.subeTop').animate({
					       right: '23%'
				    });
					$('.bannerNegocia').cycle('resume');
					$('.scrollbar').animate({
						opacity: '0'
	        		});
					$('.scrollbar').hide();
					setTimeout(function(){
			        	scrollListas();
			        	anchoSelect();
			        	$('.scrollbar').animate({
							opacity: '1'
		        		});
			        	$('.scrollbar').show();
			        }, 1500);
			 }) 
	    });
	//]]>
	</script>
<script type="text/javascript">
	$(document).ready(function(){
			$('.tipTip').tipTip({maxWidth: "auto"});
			var altoVisto= $(window).height();
			var altoPie= $('#pie').height();
			var altoCabecera = $('#conCabecera').height();
			var altoMenu = altoVisto - altoPie - altoCabecera -50;
			var altoScroll = altoMenu;
			$('#menu').css({ 'height': altoMenu });
			var alto = $('#controlScroll').height();
			$('#controlScroll').slimScroll({
				  height: altoScroll,
				  width: '350px',
				  alwaysVisible: false,
				  size: '10px',
				  color: '#f1f1f1',
				  railVisible: true,
				  railColor: '#000',
				  railOpacity: 0.8,
				  distance: '0px'
			 });
		})
	function noSelect(){
			document.onselectstart=new Function ("return false")
			//Block Tknologyk
			if (window.sidebar){
			document.onmousedown=function(e){
			var obj=e.target;
			if (obj.tagName.toUpperCase() == "INPUT" || obj.tagName.toUpperCase() == "TEXTAREA" || obj.tagName.toUpperCase() == "PASSWORD")
			return true;
			else
			return false;
			}}
	}
	$(document).ready(function(){
		$(this).keypress(function(evt){
			//Deterime where our character code is coming from within the event
			var charCode = evt.charCode || evt.keyCode;
			if (charCode  == 13) { //Enter key's keycode
				var obj=evt.target;
				if (obj.tagName.toUpperCase() == "TEXTAREA" || obj.getAttribute('type').toUpperCase() == "PASSWORD")
				return true;
				else
				return false;
			}
		})
	})
	<?php //Marquesina Menú ?>
	$(document).ready(function(){
		$('.markesina').mouseover(function(){
	      var a_ancho = $(this).find('.marq_texto').width();
    	  var div_ancho = $(this).find('.marq_oculta').width();	
			if(a_ancho >= div_ancho){
				$(this).find('.marq_texto').stop(true).animate({marginLeft:- (a_ancho + 1) },10000, function(){
			        $(this).animate({marginLeft: 0 },3600);
					});			
			};
		});
		$('.markesina').mouseleave(function(){
			$(this).find('.marq_texto').stop(true).animate({marginLeft: 0 },500);
			});

		$('.markesina').click(function(){
			$(this).find('.marq_texto').stop();
			$(this).find('.marq_texto').stop(true).animate({marginLeft: 0 },3600);        
      });
	});
	<?php //Margin-left Contendio ?>
	function marginContenido(){
		var anchoMenu = $('#izquierda').width();
		$('#contenido').css({marginLeft: anchoMenu});
	}
	$(document).ready(function(){
		marginContenido();
	});
	$(window).resize(function(){
		marginContenido();
	  });
</script>