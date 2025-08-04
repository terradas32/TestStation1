						<table cellspacing="5" align="center" border="0">	
							<tr>
								<td>
									<select name="fPreguntas" onchange="javascript:veapregunta();">
										<?php // Cargamos el combo de selección de pregunta. 
											
											if($iPaginas>0){
												$i=0;
												$iOrden = 1;
												while($i<$iPaginas){
													if($i==0){
														$iOrden = 1;
													}else{
														$iOrden = $iOrden+$sPreguntasPorPagina;
													}?>
												
													<option value="<?php echo $iOrden?>"><?php echo constant("IR_A_PAGINA");?> <?php echo $i+1 ?></option>
												
										<?php		$i++;
											}
										}?>
									</select>
									
								</td>
								<td>
								
								<?php //echo "páginas: " . intval($iPaginas);
								//Comprobamos la página que llega de forma inicial para la 
								//carga de los botones de navegación de la prueba.
								if(isset($_POST['fOrden'])){
									if($_POST['fOrden'] !="" && $_POST['fOrden'] !=1){
										if($_POST['fOrden'] == $iPaginas){
											$displayAtras="block";	
											$displaySig="none";
											$displayFin="block";
										}else{
											$displayAtras="block";	
											$displaySig="block";
											$displayFin="none";
										}
									}else{
										$displayAtras="none";	
										$displaySig="block";
										$displayFin="none";
									}
								}else{
									$displayAtras="none";	
									$displaySig="block";
									$displayFin="none";	
								}?>
									<div id="divatras" style="display: <?php echo $displayAtras?>;">
										<table>	
											<tr>
												<td>
													<input type="button" name="fAtras" value="Anterior" onclick="javascript:anterior();"/>
												</td>
											</tr>
										</table>
									</div>
								</td>
								<td>
									<div id="divsiguiente" style="display: <?php echo $displaySig?>;">
										<table>	
											<tr>
												<td>
													<input type="button" name="fSigue" value="<?php echo constant("STR_SIGUIENTE");?>" onclick="javascript:siguiente();"/>
												</td>
											</tr>
										</table>
									</div>
								</td>
								<td>
									<div id="divfin" style="display: <?php echo $displayFin?>;">
										<table>	
											<tr>
												<td>
													<input type="button" name="fSigue" value="<?php echo constant("FINALIZAR");?>" />
												</td>
											</tr>
										</table>
									</div>
								</td>
								<td>
									<div id="divbusca">
										<table>	
											<tr>
												<td>
													<input type="button" name="fSigue" value="<?php echo constant("BUSCAR_PRIMERA_SIN_RESPONDER");?>" onclick="javascript:buscaprimera();" />
												</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>