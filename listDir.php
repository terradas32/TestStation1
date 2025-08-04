<?php

function listarArchivos( $path ){
  $dir = opendir($path);
  $files = array();
  while ($elemento = readdir($dir)){
    if( $elemento != "." && $elemento != ".."){
    
      if( is_dir($path.$elemento) ){
        listarArchivos( $path.$elemento.'/' );
      }
      else{
        $files[] = $elemento;
      }
    
    }
  }
  echo "<br>Dir::" . $path; //Directorio
  $aExcludes = array('PHPMailerAutoload.php');
  for($x=0; $x<count( $files ); $x++){
    if (!in_array($files[$x], $aExcludes))
    {
      //echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;" . $files[$x];
      $content = file_get_contents( $path.$files[$x]);
      $aClass = explode(".", $files[$x]);
      $findme   = '<!--  cambiado por nacho para aparezca el editor básico -->
	<script>
			var oFCKeditor = new FCKeditor(\'fCuerpoNew\') ;
			oFCKeditor.BasePath = "fckeditor/" ;
			oFCKeditor.ToolbarSet = "Basic" ;
			
			oFCKeditor.ReplaceTextarea() ;
	</script>';
      $pos = strpos($content, $findme);
      
      // Nótese el uso de ===. Puesto que == simple no funcionará como se espera
      // porque la posición de 'a' está en el 1° (primer) caracter.
      if ($pos === false) {
          //echo "<br>La cadena '$findme' no fue encontrada en '$files[$x]'";
      } else {
          echo "<br>--------------->La cadena '$findme' fue encontrada en '$files[$x]' y existe en la posición $pos";
          //modificamos el constructor
          $content = str_replace($findme, "",$content);
          $miArchivo = fopen($path.$files[$x], "w") or die("No se puede abrir/crear el archivo!");

          fwrite($miArchivo, $content);
          fclose($miArchivo);
      }
    }
  }
  echo "<BR>";
}

listarArchivos( $_SERVER["DOCUMENT_ROOT"] . "" );

?>