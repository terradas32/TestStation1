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
	<script type="text/javascript" charset="utf-8">
	if (eval("document.getElementById('tb120')") != null){
		//:: Export Aptitudinales
		eval("document.getElementById('tb120').style.display = 'none'");
	}
	if (eval("document.getElementById('tb136')") != null){

	 	eval("document.getElementById('tb136').style.display = 'none'");
	 }
	 if (eval("document.getElementById('tb110')") != null){
		//:: Menú ayuda
	 	eval("document.getElementById('tb110').style.display = 'none'");
	 }
	 if (eval("document.getElementById('tb134')") != null){
		//:: Videotutoriales
	 	eval("document.getElementById('tb134').style.display = 'none'");
	 }
	 if (eval("document.getElementById('tb42')") != null){
		//:: Gestión de Correos - Plantillas de correos
		eval("document.getElementById('tb42').style.display = 'none'");
	 }
	 if (eval("document.getElementById('tb117')") != null){
	  //:: Gestión de Correos - Buscar plantillas de correos
	  eval("document.getElementById('tb117').style.display = 'none'");
	 }
	 if (eval("document.getElementById('tb135')") != null){
	  //:: Export personalidad DESARROLLO - Eliminar
	  eval("document.getElementById('tb135').style.display = 'none'");
	 }

	</script>
