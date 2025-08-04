<!doctype html>
<html lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="../estilos/estilos-comunes.css" type="text/css" />
	<link rel="stylesheet" href="estilos/estilos-candidato.css" type="text/css" />
     <script src="codigo/codigo.js"></script>
     <script src="codigo/comun.js"></script>
	 <script src="codigo/common.js"></script>
	 <script src="codigo/eventos.js"></script>
	 <script src="codigo/noback.js"></script>
	 <script src="codigo/jquery-1.7.1.min.js"></script>
	 <script src="codigo/jquery.requireScript-1.2.1.js"></script>

	<script  >
        function autoComplete()
        {
                var i = 0;
                // Recorres los elementos INPUT del documento
                for(var node; node = document.getElementsByTagName('input')[i]; i++){
                // Obtienes el tipo de INPUT
                var type = node.getAttribute('type').toLowerCase();
                // Si es del tipo TEXT deshabilitas su autocompletado
                if(type == 'text'){
                    node.setAttribute('autocomplete', 'off');
                }
            }
        }
    </script>
		<script language="javascript" type="text/javascript">
		$.requireScript('<?php echo constant("HTTP_SERVER");?>codigo/jquery.pstrength-min.1.2.js', function() {
		    $('.password').pstrength();
		});
		</script>
	<script   >
//	<![CDATA[
	function cambiaIdioma(){
		var f=document.forms[0];
		var url_string = this.location.toString();
		var url = new URL(url_string);
		var h = url.searchParams.get("h");
		location.replace("<?php echo constant("HTTP_SERVER");?>verify.php?h=" + h + "&fLang=" + f.fIdiomas.value);
	}
//	]]>
	</script>
<script   >
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
function validaForm()
{
	var f=document.forms[0];
	var msg="";

	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO1");?>:",f.fApellido1.value,255,true);
	msg +=vEmail("<?php echo constant("STR_MAIL");?>:",f.fMailCan.value,255,true);
//	msg +=vString("<?php echo constant("STR_DNI");?>:",f.fNifCan.value,255,true);
	msg +=vString("<?php echo constant("STR_PASSWORD");?>:",f.fPasswordCan.value,255,true);
	msg +=vString("<?php echo constant("STR_CONF_PASSWORD");?>:",f.fConfPasswordCan.value,255,true);

	if (msg == "") {
		if (f.fPasswordCan.value != f.fConfPasswordCan.value){
			msg +="\n<?php echo constant("STR_PASSWORD");?> y <?php echo constant("STR_CONF_PASSWORD");?> No coinciiden."
		}
	}

if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	return false;
}else return true;
}
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
		return true;
	}else	return false;
}
function noBack(){
////////////////////////////////
	window.history.forward();
}
window.history.forward();
//]]>
</script>
</head>
<body onload="autoComplete();" onunload="">

<div id="pagina">
    <div id="head" class="candidato">

        <div class="logo">
					<?php
						if ($cProcesos->getProcesoConfidencial() != "1"){
							echo $sLogo;
						}
					?>
        </div><!-- Fin de logo -->
    <h1><?php echo constant("STR_CANDIDATO");?></h1>

    <div id="cuerpo">
        <div id="accesos" class="acc_cand">
        <h2><?php echo constant("STR_REGISTRO");?></h2>
           <p><?php echo constant("STR_TEXTO_ACCESO_VERIFY");?></p>
	<form method="post" name="login" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar();" style="width: auto;">
       <div id="banderas">
<!--             <ul class="band_portada"> -->
            	<select name="fIdiomas" class="<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" onchange="javascript:cambiaIdioma();" >


            	<?php
		            while (!$listaIdiomas->EOF)
		            {
		            ?>
                  		<option title="<?php echo $listaIdiomas->fields['nombre'];?>" value="<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" <?php echo ($sLang == $listaIdiomas->fields['codIdiomaIso2']) ? "selected=\"selected\"" : "";?> ><?php echo $listaIdiomas->fields['nombre'];?></option>
<!--                        <li class="<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>"><a href="<?php echo constant("HTTP_SERVER") ;?>?fLang=<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" title="<?php echo $listaIdiomas->fields['nombre'];?>"><?php echo $listaIdiomas->fields['nombre'];?></a></li>-->
                    <?php
		              $listaIdiomas->MoveNext();
		            }
                ?>
            	</select>
<!--            </ul> -->
        </div><!-- Fin de las banderas -->
				<div id="verify">
					<table>
						<tr>
							<td>
				        <label style="padding-right: 15px !important;margin-right: 10px !important;"><?php echo constant("STR_NOMBRE");?></label>
				        <input type="text" name="fNombre" class="obliga" />
							</td>
						</tr>
						<tr>
							<td>
								<label style="padding-right: 15px !important;margin-right: 10px !important;"><?php echo constant("STR_APELLIDO1");?></label>
				        <input type="text" name="fApellido1" class="obliga" />
							</td>
						</tr>

						<tr>
							<td>
								<label style="padding-right: 15px !important;margin-right: 10px !important;"><?php echo constant("STR_LOGIN");?></label>
				        <input type="text" name="fMailCan" class="obliga" />
							</td>
						</tr>
						<!--
						<tr>
							<td>
								<label style="padding-right: 15px !important;margin-right: 10px !important;"><?php echo constant("STR_DNI");?></label>
								<input type="text" name="fNifCan" class="obliga" />
							</td>
						</tr>
						-->
					</td>
				</tr>
				<tr>
					<td>
						<label style="padding-right: 15px !important;margin-right: 10px !important;"><?php echo constant("STR_PASSWORD");?></label>
						<input type="password" name="fPasswordCan" class="obliga password" />
					</td>
				</tr>
				<tr>
					<td>
						<label style="padding-right: 15px !important;margin-right: 10px !important;"><?php echo constant("STR_CONF_PASSWORD");?></label>
						<input type="password" name="fConfPasswordCan" class="obliga" />
					</td>
				</tr>
			</table>
			</div>

        <input type="hidden" name="fLang" id="fLang" value="<?php echo $sLang;?>" />
        <input name="fGo" type="submit" class="btn_acceder" value="<?php echo constant("STR_ACEPTAR");?>" />
        <input type="hidden" name="fIdEmpresa" value="<?php echo (!empty($_POST['fIdEmpresa'])) ? $_POST['fIdEmpresa'] : "";?>" />
				<input type="hidden" name="fIdProceso" value="<?php echo (!empty($_POST['fIdProceso'])) ? $_POST['fIdProceso'] : "";?>" />
        </form>
        <div id="error"><p><?php echo $strMensaje;?>&nbsp;</p></div>
        </div><!-- Fin de accesos -->
    </div><!-- Fin de cuerpo -->
    <div id="pie">
        <p class="dweb"><a href="<?php echo constant("HTTP_SERVER") ;?>soporte.php?fLang=<?php echo $sLang;?>" target="_blank" title="<?php echo constant("STR_NECESITA_AYUDA_SOPORTE_AL_USUARIO");?>"><?php echo constant("STR_NECESITA_AYUDA_SOPORTE_AL_USUARIO");?></a></p>
        <p class="copy dweb"><a href="https://www.people-experts.com" target="_blank" title="Expertos en personas"><?php echo constant("NOMBRE_EMPRESA");?></a> - <?php echo constant("STR_DERECHOS_RESERVADOS");?></p>
        <!-- <p class="copy dweb"><a href="<?php echo constant("HTTP_SERVER")?>legal.html" target="_blank" title="<?php echo constant("STR_AVISO_LEGAL");?>"><?php echo constant("STR_AVISO_LEGAL");?></a></p> -->
    </div><!-- Fin de pie -->
</div><!-- Fin de la pagina -->
<script  >// Script para Autocompletar "off" y que valide con la W3C
	autoComplete();
</script>
</body>
</html>
