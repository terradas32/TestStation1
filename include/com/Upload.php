<?php 
@require_once("gif.php");
class Upload
{

	var $bImg						= false;
	var $aFirma						= array('firma'		=>	'',
											'tipo'		=>	'H',
											'bgColor'	=>	'#ffffff',
											'fontColor'	=>	'#000000',
											'fuente'	=>	'./verdana.ttf',
											'fontSize'	=>	'8');  // La fuente no tiene .ttf el tamaño en GD2 es pt y en GD<2 es px
	var $iUploadMaxSize				= 2097152; //2M en bytes
	var $iSpConsumido				= 0;
	var $bUpload					= false;
	var $bUploaded					= false;
	var $bAutoRenombrar				= false;
	var $bSobreEscribir				= true;
	
	var $src_file					= "";
	var $src_name					= "";
	var $src_type					= "";
	var $src_size					= 0;
	var $image_src_x				= 0;
	var $image_src_y				= 0;
	
	var $dst_name					= "";
	var $dst_pathname				= "";
	var $dst_pathname_mini			= "";
	var $image_dst					= "";
	var $image_dst_x				= 0;
	var $image_dst_y				= 0;
	var $image_dst_Pos_firma		= "BL";
	var $img_agua					= '';
	var $iTransparency 				= 60;
	
	var $dst_path_WS				= ""; //Directorio destino para wl Web Server.
	
	//miñatura.
	var $image_mini					= true;	// crear mini.
	var $image_dst_mini				= "";
	var $image_dst_mini_x			= 0;
	var $image_dst_mini_y			= 0;
	var	$image_mini_x				= 25;	// Ancho por defecto de la miñatura.
	var $prefijo_mini				= "m";
	
	var $image_resize				= true;	// redimensionar la imagen
	var	$image_convert				= '';	// convertir imagen. valores :''; 'png'; 'jpg'; 'gif'
	var	$image_x					= 150;	// Ancho por defecto
	var	$jpeg_quality				= 75;
	
	var $msg_Error					= array();
	var $tmpConvertFile				= "";
	var $gd_version					= 0;
	
	function __construct($sFormName){
		$this->src_file				= isset($_FILES[$sFormName]['tmp_name']) ? $_FILES[$sFormName]['tmp_name'] : '';
		$this->gd_version			= $this->gd_version();
		if (($this->src_file != '') && ($this->src_file != 'none')){
			$this->src_name			= $_FILES[$sFormName]['name'];
			$this->src_type			= $_FILES[$sFormName]['type'];
			$this->src_size			= $_FILES[$sFormName]['size'];
			$this->bImg				= $this->getEsImagen($_FILES[$sFormName]['tmp_name']);
			$this->iUploadMaxSize	= (substr(trim(ini_get('upload_max_filesize')),0,-1)*1024*1024);
			$this->iSpConsumido		= $this->getSpace();
			$this->bUpload			= $this->getUpload();
			$this->tmpConvertFile	= constant("DIR_FS_DOCUMENT_ROOT") . '/tmpIMG/';
		}else	$this->bUploaded	= true; //Cuando es modificación vendrá vacio el source.
	}

	function UploadFile($server_path){
		$bRetorno = true;
		if ($this->bUploaded) return true;
		if (!$this->bUpload) return false;
		//Se colocan por defecto el valor del nombre y destino.
		$this->dst_path	= (substr($server_path, -1, 1) != '/') ? $server_path . '/' : $server_path;
		$this->dst_path_WS = str_replace (DIR_FS_DOCUMENT_ROOT, '', $this->dst_path);
		$this->dst_path_WS = (substr($this->dst_path_WS, 0, 1) == '/') ? substr($this->dst_path_WS, 1) : $this->dst_path_WS;
		if (!$this->chk_dir($this->dst_path)){
			return false;
		}
		$this->dst_name = str_replace(' ', '_', $this->src_name);
		if (!$this->checkea_nombre($this->dst_name)){
			return false;
		}
		$this->dst_pathname = $this->dst_path . $this->dst_name;
		if ($this->bImg){ //Miramos a ver si hay que hacer algo con ella.
			if ($this->image_convert != '') { //Vemos si hay que hacer algun proceso de conversión.
				ereg('\.([^\.]*$)', $this->dst_name, $extension);
				$this->dst_name = substr($this->dst_name, 0, ((strlen($this->dst_name) - strlen(strtolower($extension[1]))))-1) . '.' . $this->image_convert;
				$this->dst_pathname = $this->dst_path . $this->dst_name;
			}
			if ($this->image_resize || 
					$this->image_convert != '' ||
						$this->aFirma['firma'] !='' ||
							$this->image_mini )
			{
				$mime=substr($this->src_type, -3);
				switch($mime) {
					case 'peg':
					case 'jpg':
						if (!function_exists('imagecreatefromjpeg')) {
							$bRetorno=false;
							$this->msg_Error[] = 'La funcion crear para JPEG no está soportada.';
						} else {
							$image_src  = @imagecreatefromjpeg($this->src_file);
							if (!$image_src) {
								$bRetorno=false;
								$this->msg_Error[] = 'No esta soportada la lectura de JPEG';
							}
						}
						break;
					case 'png':
						if (!function_exists('imagecreatefrompng')) {
							$bRetorno=false;
							$this->msg_Error[] = 'La funcion crear para PNG no está soportada.';
						} else {
							$image_src  = @imagecreatefrompng($this->src_file);
							if (!$image_src) {
								$bRetorno=false;
								$this->msg_Error[] = 'No esta soportada la lectura de PNG';
							}
						}
						break;
					case 'gif':
						if (!function_exists('imagecreatefromgif'))
						{
							//Intentamos pasar la a un formato para su tratamiento.
							//Si no tenemos creado un directorio temporal, se crea.
							if (!$this->chk_dir($this->tmpConvertFile)){
								$bRetorno=false;
								$this->msg_Error[] = 'La funcion crear para GIF no está soportada.';
								$this->msg_Error[] = 'Ha sido imposible emular el tratamiento.';
							}else{
								//Copiamos el fichero al directorio temporal.
								if (!copy($this->src_file, $this->tmpConvertFile . $this->dst_name))
								{
									$this->msg_Error[] = 'La funcion crear para GIF no está soportada.';
									$this->msg_Error[] = '1.- No se ha podido tratar el fichero.';
									$bRetorno=false;
								}else{
									if($gif = gif_loadFile($this->tmpConvertFile . $this->dst_name))
									{
										//Cambiamos la extension del fichero a convertir.
										ereg('\.([^\.]*$)', $this->dst_name, $extension);
										$dst_name = substr($this->dst_name, 0, ((strlen($this->dst_name) - strlen(strtolower($extension[1]))))-1) . '.png';
										if(gif_outputAsPng($gif, $this->tmpConvertFile . $dst_name))
										{
											$image_src  = @imagecreatefrompng($this->tmpConvertFile . $dst_name);
											//Borramos la imagen .gif
											@unlink($this->tmpConvertFile . $this->dst_name);
											//Borramos la imagen .png
											@unlink($this->tmpConvertFile . $dst_name);
											$this->dst_pathname = $this->dst_path . $dst_name;  //Ponemos el pathname con la extesión del fichero correcto.
											$this->dst_name = $dst_name;
											$this->msg_Error[] = 'Se ha convertido el fichero ' . $this->dst_name . ' -> ' . $dst_name;
											if (!$image_src) {
												$bRetorno=false;
												$this->msg_Error[] = '->No esta soportada la lectura de GIF';
											}else{$this->image_convert = 'png';}
										}else{
											$this->msg_Error[] = 'La funcion crear para GIF no está soportada.';
											$this->msg_Error[] = '2.- No se ha podido tratar el fichero.';
											$bRetorno=false;
										}
									}
								}
							}
						}else{
							$image_src  = @imagecreatefromgif($this->src_file);
							if (!$image_src) {
								$bRetorno=false;
								$this->msg_Error[] = 'No esta soportada la lectura de GIF';
							}
						}
						break;
					default:
						$bRetorno=true;
						$this->msg_Error[] = 'No se ha podido Tratar la imagen.';
						$this->msg_Error[] = 'La imagen ha sido copiada en el servidor como la original.';
				}
				if ($bRetorno && $image_src)
				{
					$this->image_dst = $image_src;
					//Si hay que ridemsionar la imagen.
					if ($this->image_resize)
					{
						$this->getResizeTo($image_src);
						
						if ($this->gd_version >= 2){
							$this->image_dst = imagecreatetruecolor($this->image_dst_x, $this->image_dst_y);
							$res = imagecopyresampled($this->image_dst, $image_src, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y, $this->image_src_x, $this->image_src_y);
						} else {
							$this->image_dst = imagecreate($this->image_dst_x, $this->image_dst_y);
							$bg = imagecolorallocate($this->image_dst, 255, 255, 255);
							$res = imagecopyresized($this->image_dst, $image_src, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y, $this->image_src_x, $this->image_src_y);
						}
						//Miramos si además hay que firmarla.
						if (!empty($this->aFirma['firma']))
						{
							if ($this->creaFirma()){
								require_once("Rwatermark.php");
								$handle = new RWatermark($this->image_dst, ImageSX($this->image_dst), ImageSY($this->image_dst));
								$handle->SetPosition($this->image_dst_Pos_firma);
								$handle->SetTransparentColor(255, 0, 255);
								$handle->SetTransparency($this->iTransparency);
								$handle->AddWatermark(HANDLE, $this->img_agua);
								$img_firmada = $handle->GetMarkedImage();
								$sType= (empty($this->image_convert)) ? substr($this->src_type, -3) : $this->image_convert;
								$bRetorno=$this->crearImg($sType, $img_firmada, $this->dst_path . $this->dst_name);
								$handle->Destroy();
							}else{
								$this->msg_Error[] = 'Se ha subido la imagen sin firmar.';
								$sType= (empty($this->image_convert)) ? substr($this->src_type, -3) : $this->image_convert;
								$bRetorno=$this->crearImg($sType, $this->image_dst, $this->dst_pathname);
							}
						}else{
							$sType= (empty($this->image_convert)) ? substr($this->src_type, -3) : $this->image_convert;
							$bRetorno=$this->crearImg($sType, $this->image_dst, $this->dst_pathname);
						}
						if ($this->image_mini && $bRetorno){
							$this->getResizeTo($image_src, $this->image_mini);
							//La miñatura no va firmada.
							if ($this->gd_version >= 2){
								$this->image_dst_mini = imagecreatetruecolor($this->image_dst_mini_x, $this->image_dst_mini_y);
								$res = 	imagecopyresampled($this->image_dst_mini, $image_src, 0, 0, 0, 0, $this->image_dst_mini_x, $this->image_dst_mini_y, $this->image_src_x, $this->image_src_y);
							} else {
								$this->image_dst_mini = imagecreate($this->image_dst_mini_x, $this->image_dst_mini_y);
								$bg = imagecolorallocate($this->image_dst_mini, 255, 255, 255);
								$res = imagecopyresized($this->image_dst_mini, $image_src, 0, 0, 0, 0, $this->image_dst_mini_x, $this->image_dst_mini_y, $this->image_src_x, $this->image_src_y);
							}
							$sType= (empty($this->image_convert)) ? substr($this->src_type, -3) : $this->image_convert;
							$this->dst_pathname_mini = $this->dst_path . $this->prefijo_mini . $this->dst_name;
							$bRetorno=$this->crearImg($sType, $this->image_dst_mini, $this->dst_pathname_mini);
						}
					}else{
						//Miramos si hay que firmarla.
						if (!empty($this->aFirma['firma']))
						{
							if ($this->creaFirma()){
								@require_once("Rwatermark.php");
								$handle = new RWatermark($this->image_dst, ImageSX($this->image_dst), ImageSY($this->image_dst));
								$handle->SetPosition($this->image_dst_Pos_firma);
								$handle->SetTransparentColor(255, 0, 255);
								$handle->SetTransparency($this->iTransparency);
								$handle->AddWatermark(HANDLE, $this->img_agua);
								$img_firmada = $handle->GetMarkedImage();
								$sType= (empty($this->image_convert)) ? substr($this->src_type, -3) : $this->image_convert;
								$bRetorno=$this->crearImg($sType, $img_firmada, $this->dst_path . $this->dst_name);
								$handle->Destroy();
							}else{
								$this->msg_Error[] = 'Se ha subido la imagen sin firmar.';
								$sType= (empty($this->image_convert)) ? substr($this->src_type, -3) : $this->image_convert;
								$bRetorno=$this->crearImg($sType, $this->image_dst, $this->dst_pathname);
							}
						}else{
							$sType= (empty($this->image_convert)) ? substr($this->src_type, -3) : $this->image_convert;
							$bRetorno=$this->crearImg($sType, $this->image_dst, $this->dst_pathname);
						}
					}
				}else{
					$bRetorno = $this->copiarFichero();
				}
			}else{
				$bRetorno = $this->copiarFichero();
			}
		}else{	//Es cualquier otro fichero.
			$bRetorno = $this->copiarFichero();
		}
		$this->borrarTmp();
		return $bRetorno;
	}
	/**
	 * Borrar el fichero uploaded de la localización temporal
	 *
	 * @access public
	 */
	function borrarTmp() {
		if(file_exists($this->src_file)){
			@unlink($this->src_file);
		}
	}
	/**
	* Chequea si el directorio existe, si no, lo crea con atributos (chmod=777).
	* @param String destpath
	* @return boolean
	*/
	function chk_dir($path, $mode = 0777) //creates directory tree recursively
	{
		$dirs = explode('/', $path);
		$pos = strrpos($path, ".");
		if ($pos === false) { // note: three equal signs
			// not found, means path ends in a dir not file
			$subamount=0;
		}else	$subamount=1;
		
		for ($c=0;$c < count($dirs) - $subamount; $c++) {
			$thispath="";
			for ($cc=0; $cc <= $c; $cc++)
				$thispath .= $dirs[$cc] . '/';
			if (!file_exists($thispath)){
				$oldumask = umask(0);
				mkdir($thispath, $mode);
				umask($oldumask);
			}
		}
		return true;
	}

	function borrar_dir($dir)
	{
		$stack = array($dir);
		while (count($stack))
		{
			# Leemos el ultimo directorio
			$dir = end($stack);
			$dh = @opendir($dir);
			if (!$dh) { //Puede no tener directorio creado con lo que no hay q borrarlo.
				return true;
			}
			while (($file = readdir($dh)) !== false) {
				if ($file == '.' or $file == '..') { 
					continue;
				}
				if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) { 
					$stack[] = $dir . DIRECTORY_SEPARATOR . $file;
				}elseif (is_file($dir . DIRECTORY_SEPARATOR . $file)) { 
					unlink($dir . DIRECTORY_SEPARATOR . $file);
				}else{
					$this->msg_Error[] = 'Ignorando ' . $dir . DIRECTORY_SEPARATOR . $file . '.';
					return false;
				}
			}
			if (end($stack) == $dir){
				closedir($dh);
				if (!@rmdir($dir)){
					$this->msg_Error[] = 'No se ha podido borrar el directorio ' . $dir . '.';
					return false;
				}
				array_pop($stack);
			}
		}
		return true;
	}

	/**
	* Monta la cadena HTML para visualizar los errores.
	* @return boolean
	*/
	function ver_errores() {
		$msg_string = "";
		foreach ($this->msg_Error as $value) {
			$msg_string .= $value."<br>\n";
		}
		return $msg_string;
	}

	/**
	* Devuelve los Warning y Errores.
	* @return Array
	*/
	function get_errores() {
		return $this->msg_Error;
	}

	/**
	* Chequea que el nombre del fichero sea valido para HTTP.
	* @param String the_name
	* @return boolean
	*/
	function checkea_nombre($the_name) {
		$bRetorno	= true;
		if ($the_name != "") {
			if (!ereg("^([a-zA-Z0-9_-]+\.)*[a-zA-Z0-9_-]+\.[a-zA-Z0-9]{1,4}$", $the_name)) {
				$this->msg_Error[]	= "El nombre del fichero contiene carácteres invalidos. (" . $the_name. ")";
				$this->msg_Error[]	= "Use solo carácteres alfanuméricos (excluido 'ñ') y separe (si es necesario) con un subrayado.";
				$this->msg_Error[]	= "Un nombre de fichero correcto acaba con un punto seguido de la extensión.(Mín: 1 Máx: 4)";
				$bRetorno	= false;
			}
		}else{
			$this->msg_Error[]	= "El nombre del fichero no puede estár vacio.";
			$bRetorno	= false;
		}
		return	$bRetorno;
	}

	/**
	* Devuelve si se puede subir el fichero por su tamaño
	* @return boolean
	*/
	function getUpload(){
		$bRetorno	= true;
		if ($this->src_size > $this->iUploadMaxSize){
			$this->msg_Error[]	= "El fichero es demasiado grande.";
			$this->msg_Error[]	= "El fichero máximo permitido es de " . ($this->iUploadMaxSize/1024/1024) . " MB";
			$bRetorno	= false;
		}
		if ((($this->src_size + $this->iSpConsumido)/1024/1024) > _QUOTA ){
			$this->msg_Error[]	= "Excedida la cuota máxima de espacio.";
			$this->msg_Error[]	= "Pongase en contacto con su administrador.";
			$bRetorno	= false;
		}
		return	$bRetorno;
	}
	/**
	* Devuelve si se puede subir el fichero
	* @return boolean
	*/
	function getUploadFile(){
		return	$this->bUpload;
	}

	/**
	* Devuelve si el fichero a tratar es una imagen
	* @param _FILES file
	* @return boolean
	*/
	function getEsImagen($file){
		$img=@getimagesize($file);
		return (!empty($img)) ? true : false;
	}

	/**
	* Fija si el fichero a tratar es una imagen
	* @param boolean bValor
	* @return void
	*/
	function setEsImagen($bValor=false){
		$this->bImg	= $bValor;
	}

	/**
	* Fija si se renombra el fichero
	* @param boolean bValor
	* @return void
	*/
	function setRenombrar($bValor=false){
		$this->bAutoRenombrar	= $bValor;
	}

	/**
	* Devuelve el nombre del fichero de destino
	* @return String
	*/
	function getNombreDestino(){
		return $this->dst_name;
	}

	/**
	* Devuelve un array con los colores RGB.
	* @access public
	* @param String  colorhex (#ffccdd)
	* @return Array
	*
	*/
	function getColorHexArray($sColorHex) {
		if (strlen($sColorHex) == 7){
			$r = hexdec(substr($sColorHex, 1, 2));
			$g = hexdec(substr($sColorHex, 3, 2));
			$b = hexdec(substr($sColorHex, 4, 2));
		}else{
			$r = hexdec(00);
			$g = hexdec(00);
			$b = hexdec(00);
		}
		return array($r,$g,$b);
	}

	/**
	* Retorna la version de la libreria GD
	* @access public
	* @return int versión
	*/
	function gd_version() {
		static $iGdVersion= null;
		if ($iGdVersion === null) {
			ob_start();
			phpinfo(8);
			$sModInf = ob_get_contents();
			ob_end_clean();
			if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $sModInf, $matches)){
				$iGdVersion = $matches[1];
			}else	$iGdVersion = 0;
		}
		return $iGdVersion;
	}

	/**
	* Retorna si el Sistema operativo es Windows
	* @access public
	* @return boolean OS
	*/
	function getSo(){
		return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
	}

	function getSpace(){
		$df=0;
		$aDir= explode(",", _DIR_CONSUMO);
		$bWin=$this->getSo();
		for ($i=0, $max = sizeof($aDir); $i < $max; $i++){
			$df+=$this->dskspace($aDir[$i], $bWin);
		}
		return $df;
	}

	function dskspace($dir, $win){
        $space = 0;
		if (is_dir($dir))
		{
			$s = stat($dir);
			$uid = $s["uid"];
			if ($win)	$space = 4096; //4Kb
			else{
				$space = (($s["blocks"]*512));
				if ($space < 0)
					$space = ($s["size"]);
				else	$space = 4096; //4Kb
			}
			$dh = opendir($dir); 
			while (($file = readdir($dh)) !== false)
			{
				if ($file != "." and $file != ".." ) 
				{
					$space += $this->dskspace($dir . DIRECTORY_SEPARATOR . $file, $win); 
				}
			}
			closedir($dh);
		}else{
			if (is_file($dir))
			{
				$s = stat($dir);
				$uid = $s["uid"];
				if ($win)	$space = filesize($dir);
				else{
					if ($uid > 0 ){
						$space = (($s["blocks"]*512));
						if ($space < 0)
							$space = ($s["size"]);
						else	$space = 4096; //4Kb
					}else{ 
						$space = 0;
					}
				}
			}
		}
		return $space; 
	}
	
	function getSobreEscribir($sDstPath='')
	{
		$sDstPath = (empty($sDstPath)) ? $this->dst_pathname : $sDstPath;
		$bRetorno=true;
		if (!$this->bSobreEscribir) {
			if (@file_exists($sDstPath)) {
				$this->msg_Error[]	= "El fichero ya esiste. Cambie el nombre del fichero.";
				$bRetorno	= false;
			}
		}
		return $bRetorno;
	}

	function copiarFichero(){
		$bRetorno = true;
		if ($this->bAutoRenombrar){
			$cpt = 1;
			while (@file_exists($this->dst_pathname)) {
				$this->dst_pathname = $this->dst_path . $cpt . '_' . $this->dst_name;
				$cpt++;
			}
			$this->dst_name = basename($this->dst_pathname);
		}
		if ($this->getSobreEscribir()){
			if (!copy($this->src_file, $this->dst_pathname)) {
				if (!move_uploaded_file($this->src_file, $this->dst_pathname)){
					$this->msg_Error[]	= "Error copiando el fichero(" . basename($this->dst_pathname) . ") en el servidor.";
					$bRetorno = false;
				}
			}
		}
		return $bRetorno;
	}

	function crearImg($mime, $img, $sDstPath)
	{
		if (!$this->getSobreEscribir($sDstPath))	return false;
		
		$bRetorno = true;
		$mime=(strlen($mime) > 3) ? substr($mime, -3) : $mime;
		switch($mime) {
			case 'peg':
			case 'jpg':
				if (!function_exists('imagejpeg')) {
					$bRetorno=false;
					$this->msg_Error[] = 'No hay soporte para crear JPEG';
				} else {
					$result = @imagejpeg ($img, $sDstPath, $this->jpeg_quality);
					if (!$result) {
						$bRetorno=false;
						$this->msg_Error[] = 'Ha sido imposible crear el fichero.';
					}
				}
				break;
			case 'png':
				if (!function_exists('imagepng')) {
					$bRetorno=false;
					$this->msg_Error[] = 'No hay soporte para crear PNG.';
				} else {
					$result = @imagepng ($img, $sDstPath);
					if (!$result) {
						$bRetorno=false;
						$this->msg_Error[] = 'Ha sido imposible crear el fichero.';
					}
				}
				break;
			case 'gif':
				if (!function_exists('imagegif')) {
					$bRetorno=false;
					$this->msg_Error[] = 'No hay soporte para crear GIF.';
				} else {
					$result = imagegif ($img, $sDstPath);
					if (!$result) {
						$bRetorno=false;
						$this->msg_Error[] = 'Ha sido imposible crear el fichero.';
					}
				}
				break;
			default:
				$bRetorno=false;
				$this->msg_Error[] = 'No se ha definido tipo de conversión.';
		}
		return $bRetorno;
	}

	function getResizeTo($img, $bMini=false)
	{
		$this->image_src_x = imagesx($img);
		$this->image_src_y = imagesy($img);
		
		if ($bMini){
			$imagemaxwidth = (!empty($this->image_mini_x)) ? $this->image_mini_x : $this->image_src_x;
		}else{
			$imagemaxwidth = (!empty($this->image_x)) ? $this->image_x : $this->image_src_x;
		}
		$imagemaxheight = $this->image_src_y;
		$imagemaxratio =  $imagemaxwidth / $imagemaxheight;
		$imageratio = $this->image_src_x / $this->image_src_y;
		
		if ($imageratio > $imagemaxratio){  
			$imageoutputwidth = $imagemaxwidth;  
			$imageoutputheight = ceil ($imagemaxwidth/$this->image_src_x*$this->image_src_y);  
		}else if ($imageratio < $imagemaxratio){  
			$imageoutputheight = $imagemaxheight;  
			$imageoutputwidth = ceil ($imagemaxheight/$this->image_src_y*$this->image_src_x);  
		}else{  
			$imageoutputwidth = $imagemaxwidth;  
			$imageoutputheight = $imagemaxheight;  
		}
		if ($bMini){
			$this->image_dst_mini_x=$imageoutputwidth;
			$this->image_dst_mini_y=$imageoutputheight;
		}else{
			$this->image_dst_x=$imageoutputwidth;
			$this->image_dst_y=$imageoutputheight;
		}
	}

	function creaFirma()
	{
		$retorno=true;
		@putenv('GDFONTPATH=' . realpath('.'));
		if ($this->gd_version >= 2){
			//En la versión >=2 de GD no hay que especificar la extensión de la fuente.
//			$this->aFirma['fuente'] = substr($this->aFirma['fuente'], 0, -4);
			//Para Gd2 la fuente la toma en pt por lo que la convertimos a px
			$this->aFirma['fontSize'] = ($this->aFirma['fontSize'] / 1.3);
		}
		//echo "*******" . $this->aFirma['fuente'];exit;
		$aDimension= imagettfbbox($this->aFirma['fontSize'], 0, $this->aFirma['fuente'], $this->aFirma['firma']);
		//echo "*******" . $aDimension;exit;
		if (!$aDimension) {
			$this->msg_Error[] = '1 - Ha ocurrido un error al crear la firma de la imagen.';
			return false;
		}
		$aSpace= imagettfbbox($this->aFirma['fontSize'], 0, $this->aFirma['fuente'], 'W');
		if (!$aSpace) {
			$this->msg_Error[] = '2 - Ha ocurrido un error al crear la firma de la imagen.';
			return false;
		}
		//echo "**......*" . $this->aFirma['tipo'];exit;
		switch ($this->aFirma['tipo']) 
		{
			case "H": // Horizontal
				$iImHeight=($aDimension[7]*-1) + ($aSpace[7]*-1);
				$iImWidth=$aDimension[2] + ($aSpace[2]);
				$iCentroAlto=($iImHeight/2) + (($aSpace[7]*-1)/2);
				$iCentroAncho=$aSpace[2]/2;
				$ratio=0;
				break;
			case "V": // Vertical arriba
				$iImWidth=($aDimension[7]*-1) + ($aSpace[7]*-1);
				$iImHeight=$aDimension[2] + ($aSpace[2]);
				$iCentroAncho=((($aSpace[7]*-1)/2) + ($aSpace[2]/2));
				$iCentroAlto=($iImHeight) - (($aSpace[7]*-1)/2);
				$ratio=89.7;
				break;
			default: // Horizontal
				$iImHeight=($aDimension[7]*-1) + ($aSpace[7]*-1);
				$iImWidth=$aDimension[2] + ($aSpace[2]);
				$iCentroAlto=($iImHeight/2) + (($aSpace[7]*-1)/2);
				$iCentroAncho=$aSpace[2]/2;
				$ratio=0;
				break;
		}

		if ($this->gd_version >= 2){
			$im = imagecreatetruecolor($iImWidth, $iImHeight);
		} else {
			$im = imagecreate($iImWidth, $iImHeight);
		}
		
		//Asignamos el color de fondo.
		$aBg = $this->getColorHexArray($this->aFirma['bgColor']);
		$bgColor = imagecolorallocate($im, $aBg[0], $aBg[1], $aBg[2]);
		//Asignamos el color de la fuente.
		$aFc = $this->getColorHexArray($this->aFirma['fontColor']);
		$fontColor = imagecolorallocate($im, $aFc[0], $aFc[1], $aFc[2]);
		ImageFilledRectangle($im, 0, 0, $iImWidth-1, $iImHeight-1, $bgColor);
		imagettftext($im, $this->aFirma['fontSize'], $ratio, $iCentroAncho, $iCentroAlto, $fontColor, $this->aFirma['fuente'], $this->aFirma['firma']);
		$this->img_agua = $im;
		return $retorno;
	}
	function getPath_WS(){
		return $this->dst_path_WS . $this->dst_name;
	}
}//Fin de la clase ?>