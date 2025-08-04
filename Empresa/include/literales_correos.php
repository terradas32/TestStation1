<?php
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos_literales/Correos_literalesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos_literales/Correos_literales.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
 	$cCorreos_literales = new Correos_literales();
	$cCorreos_literalesDB = new Correos_literalesDB($conn);
	$cEmpresas = new Empresas();
	$cEmpresasDB = new EmpresasDB($conn);
	$cCiegosDB = new CandidatosDB($conn);
	if (!isset($sVisible)){
		$sVisible=1;
	}
	$cCorreos_literales->setVisible($sVisible);
	$cCorreos_literales->setCodIdiomaIso2($sLang);
	$sqlLiterales=$cCorreos_literalesDB->readLista($cCorreos_literales);
	$rsLiterales = $conn->Execute($sqlLiterales);
?>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" class="negrob" valign="top">&nbsp;</td>
		<td><strong>**<?php echo constant("STR_NOTA_IMPORTANTE");?></strong>: <?php echo constant("MSG_INSERTAR_TAG");?>:
			<ul>
			<?php
			while (!$rsLiterales->EOF)
			{?>
				<li style="list-style:disc;"><strong class="naranja"><?php echo $rsLiterales->fields['literal'];?></strong>:&nbsp;<?php echo $rsLiterales->fields['descripcion']?></li>
			<?php
				$rsLiterales->MoveNext();
			}
			?>
			</ul>
			<ul>
			<?php
			$_idEmpresa="";
			$_idProceso="";
			if (!empty($_POST["fIdEmpresa"])){
				$_idEmpresa=$_POST["fIdEmpresa"];
			}else{
				if (!empty($_POST["fIdEmpresaAsig"])){
				$_idEmpresa=$_POST["fIdEmpresaAsig"];
				}
			}
			if (!empty($_POST["fIdProceso"])){
				$_idProceso=$_POST["fIdProceso"];
			}else{
				if (!empty($_POST["fIdProcesoAsig"])){
					$_idProceso=$_POST["fIdProcesoAsig"];
				}
			}
			if (!empty($_idEmpresa) && !empty($_idProceso))
			{
				$cCiegos = new Candidatos();
				$cCiegos->setIdEmpresa($_idEmpresa);
				$cCiegos->setIdProceso($_idProceso);
				$sqlCCiegos = $cCiegosDB->readLista($cCiegos);
				$rsCCiegos = $conn->Execute($sqlCCiegos);
				$sCCiega="";
				while (!$rsCCiegos->EOF)
				{
					if (empty($rsCCiegos->fields['mail'])){
						$sCCiega="Sí";
					}
					$rsCCiegos->MoveNext();
				}
				$cEmpresas->setIdEmpresa($_idEmpresa);
				$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
				$sCiega = $cEmpresas->getAltaCiega();
				$iCiega = $cEmpresas->getDongles();

				$sDescuentaMatriz = $cEmpresas->getDescuentaMatriz();
				$cMatrizDng = new Empresas();
				if (!empty($sDescuentaMatriz)){
					$cMatrizDng->setIdEmpresa($sDescuentaMatriz);
					$cMatrizDng = $cEmpresasDB->readEntidad($cMatrizDng);
					$iCiega = $cMatrizDng->getDongles();
				}else{
					$iCiega = $cEmpresas->getDongles();
				}

				if (!empty($sCiega) && !empty($sCCiega) && !empty($iCiega)){
			?>
				<li style="list-style:lower-roman;"><strong class="naranja"><?php echo constant("STR_ALTAS_ANONIMAS");?></strong>:&nbsp;<?php echo constant("HTTP_SERVER_FRONT") . "Candidato/blind.php?h=" . str_replace("=","", base64_encode($_idEmpresa . constant("CHAR_SEPARA") .  $_idProceso));?></li>
			<?php
				}
			}
			?>
			</ul>
		</td>
	</tr>
