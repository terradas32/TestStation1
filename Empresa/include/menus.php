	<div id="navigation">
		<h2><?php echo constant("STR_MENU");?></h2>
		<div>	
			<?php echo $sMenus;?>
			   <input type="hidden" name="MODO" value="<?php echo $_POST["MODO"]?>" />
               <input type="hidden" name="sTK" value="<?php echo $_POST["sTK"]?>"  />
               <input type="hidden" name="_TituloOpcion" value="<?php echo (!empty($_POST["_TituloOpcion"])) ? $_POST["_TituloOpcion"] : ""?>" />
               <input type="hidden" name="_block" value="<?php echo (!empty($_POST["_block"])) ? $_POST["_block"] : "0"?>" />
               <input type="hidden" name="_clicado" value="<?php echo (!empty($_POST["_clicado"])) ? $_POST["_clicado"] : "-1"?>" />
               <input type="hidden" name="fLang" value="<?php echo (!empty($_POST["fLang"])) ? $_POST["fLang"] : constant("LENGUAJEDEFECTO")?>" />
               <input type="hidden" name="sPG" value="" />
               <input type="hidden" name="AddIdioma" value="0" />
			<?php
			if ($_cEntidadUsuarioTK->getpower_bi_token() != ""  &&
				$_cEntidadUsuarioTK->getpower_bi_active() == "1")
			{
            ?>
               <table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tbody><tr onmouseover="javascript:overTR(this,'White');cambia(195,1);" onmouseout="javascript:outTR(this,'#C0C0C0');cambia(195,0)" style="cursor: default;" bgcolor="#C0C0C0">
							<td style="background-color:#C0C0C0;" title="TALENT ANALYTICS">
								<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tbody><tr>
										<td onclick="javascript:cambia('195',2);block('3');setTitulo(':: TALENT ANALYTICS');enviarMenu('power_bi.php','0');" valign="top"><img src="/Empresa/graf/flecha.gif" style="height:10px" name="a195" alt="" width="10" border="0"></td>
											<td onclick="javascript:cambia('195',2);block('3');setTitulo(':: TALENT ANALYTICS');enviarMenu('power_bi.php','0');" onmouseout="window.status='';return true" onmouseover="window.status=':: TALENT ANALYTICS';return true" class="negrob" width="100%">:: TALENT ANALYTICS</td>
										<td onclick="javascript:cambia('195',2);block('3');setTitulo(':: TALENT ANALYTICS');enviarMenu('power_bi.php','0');"><img src="graf/sp.gif" style="height:9px" alt="" width="9" border="0"></td>		</tr>
								</tbody></table>
							</td>
						</tr>
						<tr>
							<td><img src="graf/sp.gif" style="height:5px" alt="" width="1" border="0"></td>
						</tr>
					</tbody>
				</table>
			<?php
			 }
			?>

			<?php
			if ($_cEntidadUsuarioTK->getpower_bi_token_fit() != ""  &&
				$_cEntidadUsuarioTK->getpower_bi_active_fit() == "1")
			{
            ?>
               <table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tbody><tr onmouseover="javascript:overTR(this,'White');cambia(196,1);" onmouseout="javascript:outTR(this,'#C0C0C0');cambia(196,0)" style="cursor: default;" bgcolor="#C0C0C0">
							<td style="background-color:#C0C0C0;height:100%" title="FIT COMPETENCIAL">
								<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tbody><tr>
										<td onclick="javascript:cambia('196',2);block('3');setTitulo(':: FIT COMPETENCIAL');enviarMenu('power_bi_fit.php','0');" valign="top"><img src="/Empresa/graf/flecha.gif" style="height:10px" name="a196" alt="" width="10" border="0"></td>
										<td onclick="javascript:cambia('196',2);block('3');setTitulo(':: FIT COMPETENCIAL');enviarMenu('power_bi_fit.php','0');" onmouseout="window.status='';return true" onmouseover="window.status=':: FIT COMPETENCIAL';return true" class="negrob" width="100%">:: FIT COMPETENCIAL</td>
										<td onclick="javascript:cambia('196',2);block('3');setTitulo(':: FIT COMPETENCIAL');enviarMenu('power_bi_fit.php','0');"><img src="graf/sp.gif" style="height:9px" alt="" width="9" border="0"></td>		</tr>
								</tbody></table>
							</td>
						</tr>
						<tr>
							<td><img src="graf/sp.gif" style="height:5px" alt="" width="1" border="0"></td>
						</tr>
					</tbody>
				</table>
			<?php
			 }
			?>
		</div>
	</div>