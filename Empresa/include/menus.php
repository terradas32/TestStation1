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
		</div>
	</div>