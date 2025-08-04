<?php

class SOAP_Service
{

	protected $autorizado = false;
	protected $sUsr_Empresa;
	protected $conn;

	/**
	 * Constructor
	 *
	 * @param string $p1
	 */
	function __construct() {
        $link=mysql_connect("snegocia1", "root", "nosferatu62" ) ;
        mysql_query("SET CHARACTER SET utf8");
        mysql_query("SET NAMES utf8");

        $this->conn			= $link;
	}

	/**
    * Input usuario String,
    * Input password String,
    * Return String of identificator.
    *
	* @param usuario String
	* @param password String
	* @exception Exception Error al ejecutar la acción
	* @return String
	*/
	public function login($usuario, $password)
	{
		$aux  = $this->conn;
		$retorno = "";
		try{
			mysql_select_db("teststation",$aux) OR DIE ("Error: Imposible Conectar" ) ;

			$sql = "SELECT * FROM empresas WHERE ";
			$sql  .="usuario='" . strip_tags($usuario) . "' AND ";
			//$sql  .="password= '" . md5(strip_tags($password)) . "' ";
			$rs=mysql_query($sql,$conn);
			if (!$rs) {
	    		throw new SoapFault("Login", "Error MySQL: " . mysql_error());
			}else{
				while($row=mysql_fetch_array($rs))
				{
					if (password_verify(strip_tags($password), $row['password'])){
						$this->$idEmpresa = $row["idEmpresa"];
	          $this->autorizado = true;
						$retorno= $this->$idEmpresa;
					}
				}
			}
		} catch(SoapFault $fault) {
			echo $fault;
		}
		return $retorno;
	}
	/**
    * Input idConvocatoria String
    * Return array
    *
	* @param idConvocatoria String
	* @exception Exception Error al ejecutar la acción
	* @return array
	*/
	public function convocatoriasList($idConvocatoria){
		$aux  = $this->conn;
		if ($this->autorizado && !empty($this->sUsr_Empresa)){
			mysql_select_db("teststation",$aux) OR DIE ("Error: Imposible Conectar" ) ;

			$sql="SELECT * FROM areas";
			$rs=mysql_query($sql, $aux);

			$aReturn = array();
			$i=0;
			while($row=mysql_fetch_array($rs))
			{
				foreach ($row as $as) {
	            	$aReturn[$i][key($row)] = addslashes($as);
	        	    next ($row);
    		    }
				$i++;
			}
			return $aReturn;
		}else{
			throw new SoapFault("convocatoriasList", "Usuario no válido.");
		}
	}
}

?>
