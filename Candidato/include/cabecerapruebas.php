<?php
function convertir_fecha($fecha_datetime){
	//Esta función convierte la fecha del formato DATETIME de SQL a formato DD-MM-YYYY HH:mm:ss
	$fecha = explode("-",$fecha_datetime);
	$hora = explode(":",$fecha[2]);
	$fecha_hora = explode(" ",$hora[0]);
	$fecha_convertida = $fecha_hora[0].'/'.$fecha[1].'/'.$fecha[0].' '.$fecha_hora[1].':'.$hora[1].':'.$hora[2];
	return $fecha_convertida;
}
require_once(constant("DIR_WS_COM") . "Pruebas_ayudas/Pruebas_ayudasDB.php");
require_once(constant("DIR_WS_COM") . "Pruebas_ayudas/Pruebas_ayudas.php");

$cPruebas_ayudasDB = new Pruebas_ayudasDB($conn);
$cPruebas_ayudas = new Pruebas_ayudas();
$cPruebas_ayudas->setIdPrueba($cPruebas->getIdPrueba());
$cPruebas_ayudas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
$cPruebas_ayudas->setIdAyuda("1");
$cPruebas_ayudas = $cPruebas_ayudasDB->readEntidad($cPruebas_ayudas);

require_once(constant("DIR_WS_COM") . 'Idiomas/Idiomas.php');
require_once(constant("DIR_WS_COM") . 'Idiomas/IdiomasDB.php');
$cIdiomasDB = new IdiomasDB($conn);
$cIdiomas = new Idiomas();
$cIdiomas->setActivoBack(1);
$cIdiomas->setOrder("ASC");
$cIdiomas->setOrderBy("orden");
$sqlIdiomas = $cIdiomasDB->readLista($cIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);
require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
$cEmpresasDB = new EmpresasDB($conn);
$cEmpresas = new Empresas();
$cEmpresas->setIdEmpresa($_cEntidadCandidatoTK->getIdEmpresa());
$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
$sLogo = '<img alt="' . constant("NOMBRE_EMPRESA") . '" src="' . constant("DIR_WS_GRAF") . 'logo.png" border="0" />';
if ($cEmpresas->getPathLogo() != ""){
	$altura = 85;
	$size = @getimagesize(constant("DIR_WS_GESTOR") . $cEmpresas->getPathLogo());
	if ($size){
		$anchura=$size[0];
		$altura=$size[1];
	}
	if ($altura > 85){
		$altura = 85;
	}
		$altura.="px";
		$sLogo = '<img title="' . $cEmpresas->getNombre() . '" alt="' . $cEmpresas->getNombre() . '" src="' . constant("DIR_WS_GESTOR") . $cEmpresas->getPathLogo() . '" height="' . $altura . '" />';
}else{
	$sLogo = $cEmpresas->getNombre();
}
?>

<div id="cabecera">
		<div id="cabecera-envoltura">
			<div id="cabecera-contenido">
				<div id="cabecera-contenido-superior">
					<div id="cabecera-nombre">
						<?php echo constant("NOMBRE_SITE");?>
					</div>
	            	<div id="cabecera-botones">
						<ul>
	            		<li class="salir"><a href="<?php echo constant("HTTP_SERVER_FRONT");?>index.php?fLang=<?php echo $sLang;?>" title="<?php echo constant("STR_SALIR");?>"><?php echo constant("STR_SALIR");?></a></li>
	            		</ul>
					</div>
					<div id="cabecera-menu-seleccionado">
						<div id="logoPruebas">
						<?php
						if ($cPruebas->getlogoPrueba() != ""){
						?>
							<img src="<?php echo constant("DIR_WS_GESTOR") . $cPruebas->getlogoPrueba()?>"  border="0" alt="<?php echo $cPruebas->getDescripcion()?>" title="<?php echo $cPruebas->getDescripcion()?>" />
						<?php
						}?>

						<br />
						<?php echo $cPruebas->getDescripcion()?>
						</div>
					</div>
				<?php
					//Si no tiene ayuda no la saco
					if (strip_tags($cPruebas_ayudas->getTexto()) !="")
					{?>

					<div id="triggers" style="position:absolute;left:40.5%;top:78px;">
						<ul>
							<li>
								<a href="#" rel="#ayuda" ><?php echo constant("STR_PINCHA_AQUI_PARA_OBTENER_AYUDA");?></a>
							</li>
						</ul>
					</div>
			<?php
					}
			?>
				</div>
				<div id="cabecera-contenido-inferior">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr >
			            	<td colspan="2" nowrap="nowrap" class="blanco"><?php echo constant("STR_CANDIDATO");?>:&nbsp;<font class="blancob"><?php echo $cCandidato->getNombre();?>&nbsp;<?php echo $cCandidato->getApellido1();?>&nbsp;<?php echo $cCandidato->getApellido2();?></font>&nbsp;&nbsp;&nbsp;&nbsp;
			            	<?php
			            	if ($cEmpresas->getVerCorreo() == ""){
			            		echo constant("STR_MAIL");?>:&nbsp;<font class="blancob"><?php echo $cCandidato->getMail();
			            	}?>
			            	</font></td>
							<td nowrap="nowrap" align="right" class="blanco"  style="display:none;"><?php echo constant("STR_ULTIMA_CONEXION");?>:&nbsp;<font class="blancob"><?php echo (is_null($cCandidato->getUltimoLogin())) ? constant("STR_NUNCA") : convertir_fecha($cCandidato->getUltimoLogin());?></font></td>
	                        <td nowrap="nowrap" align="right" valign="bottom" class="blanco"></td>
	            		</tr>
					</table>
				</div>
			</div>
		</div>
		<div id="cabecera-izquierda">
			<div id="cabecera-logo">
			<h2>
				<?php
					if ($cProcesos->getProcesoConfidencial() != "1"){
						echo $sLogo;
					}
				?>
			</h2>
			</div>
		</div>
		<div id="cabecera-derecha">

		</div>
	</div>
