<?php
class Conexion{
	var $host			= 'localhost';
	var $usr			= 'root';
	var $psw			= '';
	var $dbname			= '';
	var $id				= false;
	var $resultado		= '';
	var $resultadoData	= '';
	var $error			= '';
	var $debug			= false;
	var $columnas		= array();
	var $datos			= array();
	var $numcols		= 0;
	var $numfilas		= 0;
	var $lastQuery 		= '';
	
	function __construct(){
		
	}
	function conectar($host='',$usr='',$psw='',$dbname=''){
		#asignar los datos de la función, o los default de la clase.
		$host 	= empty($host)	?$this->host	:$host;
		$usr 	= empty($usr)	?$this->usr		:$usr;
		$psw 	= empty($psw)	?$this->psw		:$psw;
		$dbname	= empty($dbname)?$this->dbname	:$dbname;
		#verificar que se asigne un usuario.
		if($usr == '') { $this->error = 'Debe indicar un usuario de conexión'; return false; }
		#realizo la conexión, que PHP no despliegue error, se imprimirá en caso de que suceda.
		if(!$this->id = mysqli_connect($host,$usr,$psw,$dbname)){
			$this->error=mysqli_connect_errno().": ".mysqli_connect_error();
			#si está activado el debug, mostrar error
			if($this->debug) echo $this->error;
            return false;
		}else{
			#si todo es correcto regreso el identificador de la conexión
			return $this->id;
		}
	}
	function query($sql='',$debug=FALSE){
		if($debug){ $this->lastQuery = $sql; }
		
		if($this->id){
			if($sql==''){
				$this->error = 'No especificó el sql a ejecutar';
				return false;
			}else{
				if(!$this->resultado = mysqli_query($this->id,$sql)){
					#echo mysql_real_escape_string($sql);
					echo $sql;
					$this->error=mysqli_errno($this->id).": ".mysqli_error($this->id); 
					#si está activado el debug, mostrar error
					if($this->debug) die($this->error);
					return false;
				}else{
					return $this->resultado;
				}
			}
		}
	}
	function num_rows($resultado){
		return mysqli_num_rows($resultado);
	}
	function num_fields(){
		return mysqli_field_count($this->id);
	}
	function last_id(){
		return mysqli_insert_id ($this->id);
	}	
	function fetch($resultado,$opcion = 'ASSOC'){
		if(empty($resultado)) die("El identificador no tiene datos.");
		$this->numfilas = $this->num_rows($resultado);
		$this->numcols 	= $this->num_fields($resultado);
		$arr = array();
		$arrKeys = array();
		$fetch_fields = mysqli_fetch_fields($resultado);
		for($i=0;$i<$this->numcols;$i++){
			$arrKeys[] = $fetch_fields[$i];
		}
		switch($opcion){
			case 'NUM':#guarda en array num
				while($result = mysqli_fetch_array($resultado,MYSQL_NUM)){
					$arr[] = $result;
				}
				break;
			case 'ARRAY':#guarda tanto key como num
				while($result = mysqli_fetch_array($resultado)){
					$arr[] = $result;
				}
				break;
			case 'ASSOC':#guarda en assoc
			default:
				while($result = mysqli_fetch_assoc($resultado)){
					$arr[] = $result;
				}
				break;
		}
		$this->columnas	= $arrKeys;
		$this->datos	= $arr;
		$this->free();#se limpia la memoria de la consulta
		return $arr;
	}
	function insert($tabla,$datos,$type='HTML',$debug=FALSE){
		$llaves=NULL;
		$valores=NULL;
		foreach($datos as $key => $value){
			@$llaves .= (isset($llaves)?',':'').$key;
			@$valores .= (isset($valores)?',':'')."'".(strtoupper($type)=='HTML'?htmlentities($value,ENT_QUOTES,'ISO-8859-1'):addslashes($value))."'";
		}

		return $this->query("INSERT INTO $tabla ($llaves) VALUES ($valores)");
	}
	function update($tabla,$datos,$condicion='',$type='HTML',$debug=FALSE){
		#echo "$tabla,$datos,$condicion='',$type='HTML'";
		/*echo "<pre>";
		print_r($datos);
		echo "</pre>";*/
		if(!is_array($datos)) die('falta un array de datos adecuado');
		foreach($datos as $key => $value){
			if($value === NULL){
				@$valores .= (isset($valores)?',':'')."$key=NULL";
			}else{
				@$valores .= (isset($valores)?',':'')."$key='".(strtoupper($type)=='HTML'?htmlentities($value,ENT_QUOTES,'ISO-8859-1'):addslashes($value))."'";
			}
		}
		#echo "UPDATE $tabla SET $valores $condicion";
		return $this->query("UPDATE $tabla SET $valores $condicion",$debug);
	}
	function delete($tabla,$condicion){
		return $this->query("DELETE FROM $tabla WHERE $condicion");
	}
	function free(){
		return mysqli_free_result($this->resultado);
	}
	function close(){
		return mysqli_close($this->id);
	}
}
?>