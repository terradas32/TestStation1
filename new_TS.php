<?php

  header('Content-Type: text/html; charset=utf-8');
  header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
  header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  header('Cache-Control: post-check=0, pre-check=0', false);
  header('Pragma: no-cache');
    
    
  require_once('include/Configuracion.php');
  
  define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
  include(constant("DIR_ADODB") . 'adodb.inc.php');
  
  include_once ('include/conexion.php');
  require_once(constant("DIR_WS_COM") . "Utilidades.php");

  $cUtilidades	= new Utilidades();

  $sql = "SELECT * FROM _tables_new_ts;";
  echo "<br />" . $sql;
  $lista = $conn->Execute($sql);
  $a_words = array();
  
  $bPruebas=false;


  if (!$bPruebas)
  {
    while (!$lista->EOF)
    {
      if($lista->fields['active'] == "0"){
        $sql = "DROP TABLE IF EXISTS `" . $lista->fields['table_old'] . "`;";
        echo "<br />" . $sql;
        $conn->Execute($sql);
      }else{
        if ($lista->fields['rename_it'] =="1"){
          $sql = "DROP TABLE IF EXISTS `" . $lista->fields['table_new'] . "`;";
          echo "<br />" . $sql;
          $conn->Execute($sql);
          
          $sql = "CREATE TABLE " . $lista->fields['table_new'] . " LIKE " . $lista->fields['table_old'] . ";";
          echo "<br />" . $sql;
          $conn->Execute($sql);
        }
          
        $sql = "DESCRIBE " . $lista->fields['table_new'] . ";";
        echo "<br />" . $sql;
        $describe = $conn->Execute($sql);
        
        while (!$describe->EOF)
        {
          
          $sql = "SELECT * FROM _words WHERE word_old = '" . $describe->fields['Field'] . "';";
          echo "<br />" . $sql;
          $words = $conn->Execute($sql);
          $word_new= '';
          
          if (!in_array($describe->fields['Field'], $a_words)) {
              array_push($a_words, $describe->fields['Field']);
          }
          $describe->MoveNext();
        }
      }
      $lista->MoveNext();
    }
  }
  
  /* Sólo lo ejecutamos si no esiste la tabla
  *********************************************/

  $sql = "SHOW TABLES LIKE '_words';";
  echo "<br />" . $sql;
  $rsWords = $conn->Execute($sql);

  if ($rsWords->NumRows() <= 0) {
    $sql = "TRUNCATE `_words`;";
    echo "<br />" . $sql;
    $conn->Execute($sql);

    $aTo_lower=array('BUSINESS_NAME', 'BUSINESS_CODE', 'BUSINESS_PASS', 'BUSINESS_COINC','CODIGO','EDAD', 'SEXO', 'NOMBRE', 'APELLIDO1', 'APELLIDO2', 'APELLIDO', 'RESULTADO', 'ORDEN', 'AREA', 'TERMINAL_TYPE', 'TERMINAL_TYPE', 'OPERATION_TYPE', 'URL_NOTIFY', 'URL_OK', 'URL_NOOK', 'SERVICE_ACTION', 'IP');
    //Recogemos el array de campos que están en camelCase y los pasamos a snake_case y guardamos los pares
    foreach ($a_words as $word) {
      if (in_array($word, $aTo_lower)) {
          $sWord = strtolower($word);
      } else {
          $sWord = camelCaseTOsnake_case($word);
      }
      $sql = "INSERT INTO `_words`( `word_old`, `word_new`) VALUES ('" . $word  . "','" . $sWord . "');";
      echo "<br />" . $sql;
      $conn->Execute($sql);
    }
  }
  /* FIN Sólo lo ejecutamos si no esiste la tabla */




    
  //Cambiamos los nombres de campo por los nuevos en las nueva tablas


  $lista->MoveFirst();
  while (!$lista->EOF) {
      if ($lista->fields['active'] == "1") {
        $sql = "DESCRIBE " . $lista->fields['table_new'] . ";";
        echo "<br />" . $sql;
        $describe = $conn->Execute($sql);
        while (!$describe->EOF)
        {
          $sql = "SELECT * FROM _words WHERE word_old = '" . $describe->fields['Field'] . "';";
          echo "<br />" . $sql;
          $words = $conn->Execute($sql);
          while (!$words->EOF)
          {
            $word_new = $words->fields['translated'];
            if (!empty($word_new)) {
              $null = "NULL";
              if ($describe->fields['Null'] == "NO") {
                  $null="NOT NULL";
              }
              $type = $describe->fields['Type'];
              if ($describe->fields['Field'] == "usuAlta" || $describe->fields['Field'] == "usuMod"){
                $type ="int(11) UNSIGNED";
                $null="NOT NULL";
              }
              $sql = "ALTER TABLE " . $lista->fields['table_new'] . " CHANGE `" . $describe->fields['Field'] . "` `" . $word_new . "` " . $type . " " . $null . ";";
              echo "<br />" . $sql;
              $conn->Execute($sql);
            }
            $words->MoveNext(); 
          }
          $describe->MoveNext();
        }

      }
      $lista->MoveNext();
  }
  

exit;

  // Buscamos las foreign en todas las tablas NUEVAS
  $lista->MoveFirst();

  $sql = "SELECT * FROM _tables_new_ts WHERE active=1;";
  echo "<br />" . $sql;
  $search = $conn->Execute($sql);

  $sql = "TRUNCATE `_foreign_key`;";
  echo "<br />" . $sql;
  $conn->Execute($sql);
  
  while (!$lista->EOF)
  {
    if ($lista->fields['active'] == "1")
    {
      $sql = "DESCRIBE " . $lista->fields['table_new'] . ";";
      echo "<br />" . $sql;
      $describe = $conn->Execute($sql);
        
      while (!$describe->EOF)
      {
        if ($describe->fields['Key'] == "PRI")
        {
          $source_table = $lista->fields['table_new'];
          echo "<br />*" . $source_table;
        
          $source_file_name= $describe->fields['Field'];
          echo "<br />*->" . $source_file_name;

          $target_table= "";
          $target_file_name= "";
          $fk_name= "";

            $search->MoveFirst();
            
            //Busco en todas las tablas el id para construir la relación
            while (!$search->EOF)
            {
              //Si no es la misma?
              if ($lista->fields['table_new']  != $search->fields['table_new'])
              {
                  $sql = "DESCRIBE " . $search->fields['table_new'] . ";";
                  echo "<br />" . $sql;
                  $desc_search = $conn->Execute($sql);
              
                  while (!$desc_search->EOF) {
                    if ($desc_search->fields['Field'] == $describe->fields['Field']) {
                      $target_table= $search->fields['table_new'];
                      $target_file_name= $desc_search->fields['Field'];
                      $fk_name= "fk_" . $source_table . "_" . $source_file_name . "_" . $target_table . "_" . $target_file_name;
                      $sql= "INSERT INTO `_foreign_key`(`fk_name`, `source_table`, `target_table`, `source_file_name`, `target_file_name`) VALUES ('" . $fk_name . "','" . $source_table . "','" . $target_table . "','" . $source_file_name . "','" . $target_file_name . "');";
                      echo "<br />" . $sql;
                      $conn->Execute($sql);
                    }
                    $desc_search->MoveNext();
                  }
              }
              $search->MoveNext();
            }
            
        }
        $describe->MoveNext();
      }
    }
    $lista->MoveNext();
  }

    // Ponemos id como primer campo y primary de todas las tablas.
    // ALTER TABLE `activity_areas` ADD `id` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`); 
    echo "<br />Finalizado Listado";

  function camelCaseTOsnake_case($string)
  {
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
  }
?>
