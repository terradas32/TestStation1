<?php

/**
 * Generate javaScript code to request or/and send informations via HTTP using XML HTTP Request object method (AJAX).
 * 
 * @author    Laurentiu Tanase <expertphp@yahoo.com> http://expert.no-ip.org/?free=Ajax&class
 * @credits   Jim Ley <jim@jibbering.com> http://jibbering.com/2002/4/httprequest.html
 * @version   2.4
 */

class Ajax
{
	/**
	 * A random (not unique) variable name.
     *
     * @var      string
     * @access   private
     */
	var $_rand;

	/**
	 * URL or file name.
     *
     * @var      string
     * @access   private
     */
	var $_url = "/";

	/**
	 * HTTP request method (HEAD, GET or POST).
     *
     * @var      string
     * @access   private
     */
	var $_type = "GET";

	/**
	 * Request type of header information (HEADER or HEADERS).
     *
     * @var      string
     * @access   private
     */
	var $_http = "";

	/**
	 * Set response type.
     *
     * @var      string
     * @access   private
     */
	var $_info = "responseText";

	/**
	 * Set send information.
     *
     * @var      string
     * @access   private
     */
	var $_send = "null";

	/**
	 * Initialize $_rand value.
     *
     * @var      string
     * @access   private
     */
	var $_head;

	/**
	 * Add header informations.
     *
     * @var      string
     * @access   private
     */
	var $_header = "";

	/**
	 * If is set XML HTTP Request object
     *
     * @var      bool
     * @access   private
     */
	var $_sethead = false;

	/**
	 * Constructor
	 */
	function __construct(){

		$this->_rand = "ajax_".rand(1, 999);
		$this->_head = <<<EOJAVASCRIPT
<script  >
var $this->_rand
/*@cc_on @*/
/*@if (@_jscript_version >= 5)
  try {
  $this->_rand=new ActiveXObject("Msxml2.XMLHTTP")
 } catch (e) {
  try {
    $this->_rand=new ActiveXObject("Microsoft.XMLHTTP")
  } catch (E) {
   $this->_rand=false
  }
 }
@else
 $this->_rand=false
@end @*/
if (!$this->_rand && typeof XMLHttpRequest!='undefined') {
 try {
  $this->_rand = new XMLHttpRequest();
 } catch (e) {
  $this->_rand=false
 }
}
</script>

EOJAVASCRIPT;

	}

	/**
	 * Set default request information.
	 *
	 * @access  public
	 * @param   string  $url    URL or file name
	 * @param   string  $type   HTTP request method
	 * @param   string  $http   Request type of header information
	 * @param   string  $resp   Header name
	 * @return  string
	 */
	function request($url, $type = "GET", $http = "", $resp = ""){

		$this->_url = $url;
		$this->_type = $type;
		$this->_http = $http;

		if($http == "HEADERS") $this->_info = "getAllResponseHeaders()";
		elseif($http == "HEADER") $this->_info = "getResponseHeader(\"".
			str_replace(array("\r", "\n", "\""), array('', '', '\"'), $resp)."\")";

	}

	/**
	 * Default ajax object value.
	 *
	 * @access  public
	 * @return  string
	 */
	function head(){
		$this->_sethead = true;
		return $this->_head;
	}

	/**
	 * Set header information.
	 *
	 * @access  public
	 * @param   array()  $arr  Header parameters and values
	 * @return  string
	 */
	function headers($arr){

		if(is_array($arr) && count($arr) > 0){
			$addhead = "";
			foreach($arr as $data){
				if(isset($data[0], $data[1]) && $data[0] != ""){
					$addhead .= "
".$this->_rand.".setRequestHeader('".$data[0]."','".$data[1]."')";
				}
			}
			$this->_header = ($addhead != "") ? $addhead : "";
		}else $this->_header = "";

	}

	/**
	 * Set send information.
	 *
	 * @access  public
	 * @param   string  $data
	 * @return  string
	 */
	function send($data = ""){
		$this->_send = ($data != "") ? '"'.str_replace(array("\r", "\n"), "", $data).'"' : 'null';
	}

	/**
	 * Set send information.
	 *
	 * @access  public
	 * @param   string  $cmd     JavaScript code
	 * @param   mixed   $fc      JavaScript function name
	 * @param   array   $param   JavaScript function $fc parameters name
	 * @return  string
	 */
	function get($cmd = array(), $fc = false, $param = array()){

		$addcmd = "";
		if(is_array($cmd) && count($cmd) > 0){
			$cntcmd = 0;
			$another = "";
			$defstat = false;
			foreach($cmd as $valarr){
				if(isset($valarr[0]) && $valarr[0] != ""){
					$valcmd = str_replace(array("{ajax}", "{stat}"), 
						array($this->_rand.".".$this->_info, $this->_rand.".status"), $valarr[0]);
					if(isset($valarr[1]) && is_int($valarr[1]) && $valarr[1] >= 100 && $valarr[1] < 600){
						$compad = $cntcmd ? " else" : "";
						$addcmd .= <<<EOJAVASCRIPT
$compad if ($this->_rand.status==$valarr[1]) {
		$valcmd
	   }
EOJAVASCRIPT;
						$cntcmd++;
					}elseif(isset($valarr[1]) && $valarr[1] == "DEFSTAT"){
						$defstat = $valcmd;
					}else{
						$another .= <<<EOJAVASCRIPT

	   $valcmd
EOJAVASCRIPT;
					}
				}
			}
			if($defstat){
				$addelse1 = $cntcmd ? " else {" : "";
				$addelse2 = $cntcmd ? "	   }" : "";
				$valcmd1 = str_replace(array("{ajax}", "{stat}"), 
					array($this->_rand.".".$this->_info, $this->_rand.".status"), $defstat);
				$addcmd .= <<<EOJAVASCRIPT
$addelse1
		$valcmd1
$addelse2
EOJAVASCRIPT;
			}
			$addcmd .= $another;
		}

		$fc1 = $fc2 = "";
		if($fc && $fc != ""){
			$addpar = "";
			if(is_array($param) && count($param) > 0){
				foreach($param as $valpar) $addpar .= trim($valpar).",";
				$addpar = substr($addpar, 0, -1);
			}
			$fc1 = "function ".trim($fc)."(".$addpar."){";
			$fc2 = "}";
		}

		if($addcmd == "") $addcmd = "//";

		$ret = <<<EOJAVASCRIPT
<script  >
$fc1
$this->_rand.open("$this->_type","$this->_url",true);
$this->_rand.onreadystatechange=function() {
  if ($this->_rand.readyState==4) {
	  $addcmd
  }
}
$this->_header
$this->_rand.send($this->_send)
$fc2
</script>

EOJAVASCRIPT;

		return $ret;

	}

}

?>