<?php
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos_literales/Correos_literalesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos_literales/Correos_literales.php");
 	$cCorreos_literales = new Correos_literales();
	$cCorreos_literalesDB = new Correos_literalesDB($conn);
	if (!isset($sVisible)){
		$sVisible=1;
	}
	$cCorreos_literales->setVisible($sVisible);
	$sqlLiterales=$cCorreos_literalesDB->readLista($cCorreos_literales);
	$rsLiterales = $conn->Execute($sqlLiterales);
?>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" class="negrob" valign="top">&nbsp;</td>
		<td><strong>NOTA IMPORTANTE</strong>: Para insertar parámetros en el cuerpo del email o del asunto se deben escribir los siguientes literales, que se sustituirán por su correpondiente valor al enviar el email:
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
		</td>
	</tr>