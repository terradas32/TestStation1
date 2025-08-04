<!--  Azul Pomodoro  -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('include/metatags.html');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/eventos.js"></script>
	<script type="text/javascript">
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
        window.history.forward();  
        function noBack(){ window.history.forward(); }
    </script>
    
	<style type="text/css">
		body{
			background-image: url(<?php echo constant("DIR_WS_GRAF");?>Homefondo.jpg);
			overflow: hidden;
		}
	</style>
</head>
<body bgcolor="#ffffff" onload="autoComplete();noBack();"  onunload="">
<form method="post" name="login" action="<?php echo $_SERVER['PHP_SELF'];?>" >
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" >
	<tr valign="top">
		<td>
			<table cellspacing="0" cellpadding="0" align="center" border="0">
				<tr>
					<td><img src="<?php echo constant("DIR_WS_GRAF");?>cabecera_entrada.jpg" width="500" height="95" border="0" alt="" /></td>
				</tr>
				<tr>
					<td>
						<table cellspacing="0" cellpadding="0" align="center" border="0" style="background-image: url(<?php echo constant("DIR_WS_GRAF");?>Homefondo2.jpg);background-repeat:no-repeat;">
							<tr>
								<td>
									<table cellspacing="0" cellpadding="0" align="center" border="0">
										<tr><td colspan="4"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="40" border="0" alt="" /></td></tr>
										<tr>
											<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="30" height="1" border="0" alt="" /></td>
											<td class="negrob"><?php echo constant("STR_LOGIN");?>:&nbsp;</td>
											<td>&nbsp;<input type="text" name="fLogin" class="obliga" style="width:120px;" /></td>
											<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="30" height="1" border="0" alt="" /></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
										<tr>
											<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="30" height="1" border="0" alt="" /></td>
											<td class="negrob"><?php echo constant("STR_PASSWORD");?>:&nbsp;</td>
											<td>&nbsp;<input type="password" name="fPwd" class="obliga" style="width:120px;" /></td>
											<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="30" height="1" border="0" alt="" /></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="35" border="0" alt="" /></td></tr>
										<tr>
											<td align="center" colspan="4"><input name="fGo" type="submit" class="botones" value="<?php echo constant("STR_ENTRAR");?>" /></td>
										</tr>
										<tr><td colspan="4" align="center"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="20" border="0" alt="" /></td></tr>
										<tr><td colspan="4" class="negro" align="center"><?php echo constant("STR_CONECTANDO_DESDE");?>: [<font class="rojo"><?php echo $_SERVER['REMOTE_ADDR'];?></font>]</td></tr>
										<tr><td colspan="4" align="center"><br /></td></tr>
										<tr><td colspan="4"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="40" border="0" alt="" /></td></tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr valign="top">
					<td align="center"><a href="password_olvidada.php" class="grisb" target="_blank"><?php echo constant("STR_RECUERDAME_LA_CLAVE");?></a></td>
				</tr>
				<tr valign="top">
					<td align="center">&nbsp;</td>
				</tr>
				<tr valign="top">
					<td align="center">
					<?php
		              while (!$listaIdiomas->EOF)
		              {
		            ?>
                        <a href="<?php echo constant("HTTP_SERVER") ;?>?fLang=<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" title="<?php echo $listaIdiomas->fields['nombre'];?>" ><img src="<?php echo $listaIdiomas->fields['pathImagen'];?>" alt="<?php echo $listaIdiomas->fields['nombre'];?>" title="<?php echo $listaIdiomas->fields['nombre'];?>" height="17" width="25" /></a><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="20" height="1" border="0" alt="" />
                    <?php
		              $listaIdiomas->MoveNext();
		            }
                    ?><input type="hidden" name="fLang" value="<?php echo $sLang;?>" />
                    </td>
				</tr>
				<tr>
					<td>
						<table cellspacing="0" cellpadding="0" align="center" border="0">
							<tr><td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr><td align="center" class="rojo"><?php echo $strMensaje;?></td></tr>
							<tr><td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr>
								<td class="negro" align="center" valign="middle"><a href="http://www.azulpomodoro.com" target="_blank" title="Diseño Web"><img src="<?php echo constant("DIR_WS_GRAF");?>logoPeque.png" width="100" height="20" alt="Powered By Azul Pomodoro ©2003-<?php echo date('Y');?>" title="Diseño Web" border="0" /></a></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<p >&nbsp;</p>
<p >&nbsp;</p>
<p >&nbsp;</p>
<p align="center"><a href="http://validator.w3.org/check?uri=referer"><img src="<?php echo constant("DIR_WS_GRAF");?>w3c_1.gif" alt="Valid XHTML 1.0 Transitional" width="82" height="16" border="0" /></a></p>
<script type="text/javascript">// Script para Autocompletar "off" y que valide con la W3C
	autoComplete();
</script>
</body>
</html>
