<?php
    header('Content-Type: text/html; charset=utf-8');
    header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    // No es compatible con noback header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
 
session_start();
require_once('include/Configuracion.php');
include_once('include/Idiomas.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "SeguridadCandidatos.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_WS_COM") . "Items/Items.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");
	
include_once ('include/conexion.php');
	
    $bLogado = isLogado($conn);
    if(isset($_SESSION['mensaje' . constant("NOMBRE_SESSION")])){
		unset($_SESSION['mensaje' . constant("NOMBRE_SESSION")]);
		$_SESSION['mensaje' . constant("NOMBRE_SESSION")]= "";
	}
    if (!$bLogado){
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");;
		header("Location: " . constant("HTTP_SERVER"));
		exit;
	}else{
		if (!isCandidatoActivo($conn))
		{
		  $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
		  header("Location: " . constant("HTTP_SERVER"));
		  exit;
		}
		$_cEntidadCandidatoTK = getCandidatoToken($conn);
        if(isset($_SESSION['_cEntidadCandidatoTK' . constant("NOMBRE_SESSION")])){
    		unset($_SESSION['_cEntidadCandidatoTK' . constant("NOMBRE_SESSION")]);
    		$_SESSION['_cEntidadCandidatoTK' . constant("NOMBRE_SESSION")]= "";
    		$_SESSION["_cEntidadCandidatoTK" . constant("NOMBRE_SESSION")]= $_cEntidadCandidatoTK;
    	}else{
            $_SESSION['_cEntidadCandidatoTK' . constant("NOMBRE_SESSION")]= "";
    		$_SESSION["_cEntidadCandidatoTK" . constant("NOMBRE_SESSION")]= $_cEntidadCandidatoTK;
        }
    }
    
    $cRespPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
	$cPruebasDB = new PruebasDB($conn);
    $cItemsDB = new ItemsDB($conn);
  	
    
    /****************************************************************
     * Comprobamos que nos llega una opción seleccionada(fIdOpcion)
     * y si además no es la carga inicial de la prueba
     * con lo que fOrdenOrigen llegaría vacío.
     * Estos dos campos son necesarios porque en este bloque se
     * realizan las inserciones de las respuestas por cada item.
     ****************************************************************/
    
    if(isset($_POST['fIdOpcion']) && $_POST['fIdOpcion']!=""){
	    if(isset($_POST['fOrdenOrigen']) && $_POST['fOrdenOrigen']!=""){
			$cItemsAnterior = new Items();
			$cItemsAnterior->setIdPrueba($_POST['fIdPrueba']);
			$cItemsAnterior->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cItemsAnterior->setOrden($_POST['fOrdenOrigen']);
			$cItemsAnterior = $cItemsDB->readEntidadOrden($cItemsAnterior);
			
			$cRespPruebasItems = new Respuestas_pruebas_items();
		    $cRespPruebasItems->setIdProceso($_POST['fIdProceso']);
		    $cRespPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
		    $cRespPruebasItems->setIdCandidato($_POST['fIdCandidato']);
		    $cRespPruebasItems->setIdPrueba($_POST['fIdPrueba']);
		    $cRespPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		    $cRespPruebasItems->setIdItem($cItemsAnterior->getIdItem());
			$cRespPruebasItems->setOrden($_POST['fOrdenOrigen']);
			
			$aValOp = explode("@" , $_POST['fIdOpcion']);
		    
		    $cRespPruebasItems->setIdOpcion($aValOp[1]);
		    
		    if($cItemsAnterior->getIdItem()!=""){
			    $cRespPruebasItemsDB->borrar($cRespPruebasItems);
			    $cRespPruebasItemsDB->insertar($cRespPruebasItems);
		    }
		}
    }
    
    /*Fin inserción*/
    
    $cCandidato = new Candidatos();
    $cCandidato  = $_cEntidadCandidatoTK;
    
    $cProceso_pruebas = new Proceso_pruebas();
    $cProceso_pruebasDB = new Proceso_pruebasDB($conn);

    
    $cPruebas = new Pruebas();
	$cItemListar = new Items();
    $cItemListar->setIdPrueba($_POST['fIdPrueba']);
    $cItemListar->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    $cItemListar->setOrderBy("orden");
    $cItemListar->setOrder("ASC");
    
    $sqlItems = $cItemsDB->readLista($cItemListar);
    $listaItems = $conn->Execute($sqlItems);
    
    $iTamaniolistaItems = $listaItems->recordCount();
    
    $cItems = new Items();
    
    
    /****************************************************************
     * Si se ha pulsado el botón de buscar preguntas sin contestar
     * nos llegará esta variable con un valor de 1 y aquí se buscará
     * la primera pregunta que no tenga nada respondido
     ****************************************************************/
    
    if(isset($_POST['fBuscaPrimera']) && $_POST['fBuscaPrimera']!=""){
    	$cRespBuscar = new Respuestas_pruebas_items();
		$cRespBuscar->setIdProceso($_POST['fIdProceso']);
		$cRespBuscar->setIdEmpresa($_POST['fIdEmpresa']);
		$cRespBuscar->setIdCandidato($_POST['fIdCandidato']);
		$cRespBuscar->setIdPrueba($_POST['fIdPrueba']);
		$cRespBuscar->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cRespBuscar->setOrderBy('idItem');
		$cRespBuscar->setOrder('ASC');
		$sqlBusc = $cRespPruebasItemsDB->readLista($cRespBuscar);
		
		//Creamos una lista con los items respondidos hasta este momento.
		
		$listaBuscar = $conn->Execute($sqlBusc);
		
		$i=1;
		
		//Miramos si hay alguna respuesta
		
		if($listaBuscar->recordCount()>0){
			while(!$listaBuscar->EOF){
				
				if($listaBuscar->fields['idItem'] != $i){
					$cItems->setOrden($i);
					break;
				}
				
				$i++;
				$listaBuscar->MoveNext();
			}
			
			//Miramos las que faltan por responder restando los items que tiene esa prueba
			// y el recuento de la lista de preguntas contestadas.
			$iSinResponder= $iTamaniolistaItems - $listaBuscar->recordCount();
			
			
			if($i > 1){
				//Si hay más de una respuesta
				
				if($iSinResponder <= 0 ){
					/*
					 * Si hay respuestas sin responder lo mandamos a la última pregunta seteando
					 * al campo orden el tamaño de la lista de items para esta prueba.
					 * Además cambiamos la selección en el combo, modificamos el campo Orden del
					 * formulario poniendolo en la última pregunta, escondemos el botón de búsqueda
					 */
					$cItems->setOrden($iTamaniolistaItems);?>
					<script>
						document.forms[0].fPreguntas.selectedIndex = <?php echo $iTamaniolistaItems?>-1;
						document.forms[0].fOrden.value=<?php echo $iTamaniolistaItems?>;
						document.getElementById("divbusca").style.display = 'none';
						ocultomuestro(1,0,1);
					</script>
				<?}else{
					if($i == $iTamaniolistaItems){
						$cItems->setOrden($i);?>
						<script>
							document.forms[0].fPreguntas.selectedIndex = <?php echo $i?>-1;
							document.forms[0].fOrden.value=<?php echo $i?>;
							ocultomuestro(1,0,1);
						</script>
					<?}else{
						$cItems->setOrden($i);?>
						<script>
							document.forms[0].fPreguntas.selectedIndex = <?php echo $i?>-1;
							document.forms[0].fOrden.value=<?php echo $i?>;
							ocultomuestro(1,1,0);
						</script>		
				<?php }
				}
			}else{
				//Si sólo hay una respuesta.
				$cItems->setOrden($i);?>
				<script>
					document.forms[0].fPreguntas.selectedIndex = <?php echo $i?>-1;
					document.forms[0].fOrden.value=<?php echo $i?>;
					ocultomuestro(0,1,0);
				</script>
			<?}
			
		}else{
			$cItems->setOrden('1');?>
			<script>
				document.forms[0].fOrden.value= 1;
				document.forms[0].fPreguntas.selectedIndex = 0;
				ocultomuestro(0,1,0);
			</script>
		<?}
    }else{
     
		if(isset($_POST['fOrden'])){
	//		echo "Orden : " . $_POST['fOrden'] . "<br />";
	//		echo "TamañoLista : " . $iTamaniolistaItems . "<br />";
			if($_POST['fOrden'] !="" && $_POST['fOrden'] !=1){
				if($_POST['fOrden'] == $iTamaniolistaItems){
					$cItems->setOrden($_POST['fOrden']);?>
					<script>
						ocultomuestro(1,0,1);
					</script>
				<?}else{
					$cItems->setOrden($_POST['fOrden']);?>
					<script>
						ocultomuestro(1,1,0);
					</script>		
			<?php }
			}else{
				$cItems->setOrden('1');?>
				<script>
					ocultomuestro(0,1,0);
				</script>
			<?}
		}else{
			$cItems->setOrden('1');?>
				<script>
					ocultomuestro(0,1,0);
				</script>
		<?}
    }
	$cItems->setIdPrueba($_POST['fIdPrueba']);
	$cItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cItems = $cItemsDB->readEntidadOrden($cItems);
	
	$cRespuestasPruebasItems = new Respuestas_pruebas_items();
 	$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
	$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
	$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
	$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
	$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cRespuestasPruebasItems->setIdItem($cItems->getIdItem());
	
	$cRespuestasPruebasItems = $cRespPruebasItemsDB->readEntidad($cRespuestasPruebasItems);
	
?>
<table cellspacing="0" cellpadding="0">
	<tr>
		<td class="negrob">
			PREGUNTA&nbsp;<?php echo $cItems->getOrden()?>
		</td>
	</tr>
	<tr>
		<td class="negrob">
			<?php echo $cItems->getDescripcion()?>
		</td>
	</tr>
	<tr>
		<td valign="top">
		<?php if($cItems->getPath2()!=""){?>
				<img src="<?php echo constant('DIR_WS_GESTOR') . $cItems->getPath2()?>" />
				<?php }?>
				<?php if($cItems->getPath1()!=""){?>
				<img src="<?php echo constant('DIR_WS_GESTOR') . $cItems->getPath1()?>" />
				<?php }?>

				<?php if($cItems->getPath3()!=""){?>
				<img src="<?php echo constant('DIR_WS_GESTOR') . $cItems->getPath3()?>" />
				<?php }?>
				<?php if($cItems->getPath4()!=""){?>
				<img src="<?php echo constant('DIR_WS_GESTOR') . $cItems->getPath4()?>" />
				<?php }?>
		</td>
	</tr>
	<tr>
		<td class="negrob">
			<?php echo $cItems->getEnunciado()?>
		</td>
	</tr>
	<tr>
		<td>
			<?php 
				$cOpciones = new Opciones();
				$cOpcionesDB = new OpcionesDB($conn);
				$cOpciones->setIdPrueba($cItems->getIdPrueba());
				$cOpciones->setCodIdiomaIso2($cItems->getCodIdiomaIso2());
				$cOpciones->setIdItem($cItems->getIdItem());
				$cOpciones->setBajaLog("0");
				$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
				$listaOpciones = $conn->Execute($sqlOpciones);
			?>
			<ul>
				<?php 
				if($listaOpciones->recordCount()>0){
					while(!$listaOpciones->EOF){?>
						
						<li class="opciones">
							<?php if($cRespuestasPruebasItems->getIdOpcion()== $listaOpciones->fields['idOpcion']){?>
								<input type="radio" checked="checked" name="fIdOpcion" value="<?php echo $listaOpciones->fields['codigo'] . "@" . $listaOpciones->fields['idOpcion']?>" id="opcion<?php echo $listaOpciones->fields['codigo']?>"/> 
							<?}else{?>
								<input type="radio" name="fIdOpcion" value="<?php echo $listaOpciones->fields['codigo'] . "@" . $listaOpciones->fields['idOpcion']?>" id="opcion<?php echo $listaOpciones->fields['codigo']?>"/>
							<?}?>
								<label for="opcion<?php echo $listaOpciones->fields['codigo']?>"><?php echo $listaOpciones->fields['descripcion']?></label>
								
						</li>
				
						<?php 	$listaOpciones->MoveNext();
					}
				} ?>	
			</ul>
		</td>
	</tr>
</table>
<input type="hidden" name="fIdItem" value="<?php echo $cItems->getIdItem()?>" />
