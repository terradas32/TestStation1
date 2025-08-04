<table cellspacing="5" cellpadding="0">
		<tr>
			<td valign="top">
				&nbsp;
			</td>
			<td valign="top">
				<?php echo constant("STR_MEJOR");?>
			</td>
			<td valign="top">
				<?php echo constant("STR_PEOR");?>
			</td>
		</tr>	
	<?php 
	
	while(!$listEjemplos->EOF){
	?>
		<tr>
			<td class="negrob">
				<?php echo $listEjemplos->fields['enunciado']?>
			</td>
			<?php 
				
			 	for($i==0;$i<3;$i++){?>
					<td>
						<?php 
						if($i==1){?>	
							
								<input type="radio" checked="checked" name="fIdOpcionMejor" value="" id="" disabled="disabled" /> 
								<input type="radio" name="fIdOpcionPeor" value="" id="" disabled="disabled" />
						<?php } ?>
						
						<?php 
						if($i==2){?>	
							
								<input type="radio" name="fIdOpcionMejor" value="" id="" disabled="disabled" /> 
								<input type="radio" checked="checked" name="fIdOpcionPeor" value="" id="" disabled="disabled" />
						<?php } ?>
					</td>
			<?php } ?>			
		</tr>
<?php $listEjemplos->MoveNext();
	}?>
	</table>