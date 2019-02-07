<?php
include_once("paginacion.class.php");
class Resoluciones extends Paginacion{
	function debug($var){
		$this->conexion->debug=$var;
	}
	function Resoluciones(){
		print_r($this);
	}
	#CLASIFICACIÓN
	function obtenerCatClasificacion(){
		$this->comprobar_conexion();
		$this->resultado = $this->conexion->query("SELECT clas_id,clas_nombre as clas_nombre FROM ".PREFIJO."cat_clasificacion WHERE clas_activo != 0");#CONVERT(clas_nombre USING utf8)
		$arr = $this->conexion->fetch($this->resultado);
		$this->resultado = $arr;
		return $this->resultado;#RETURN Array
	}
	function obtenerCatAnios(){
		$this->comprobar_conexion();
		$this->resultado = $this->conexion->query("SELECT * FROM ".PREFIJO."cat_anio WHERE anio_activo != 0 ORDER BY anio DESC");
		$arr = $this->conexion->fetch($this->resultado);
		$this->resultado = $arr;
		return $this->resultado;#RETURN Array
	}
	function obtenerResolucion($id){
		$this->comprobar_conexion();
		$where = " WHERE res_id = $id ";
		$query = "SELECT res.*,clas.clas_nombre as clasificacion FROM ".PREFIJO."resoluciones as res LEFT JOIN ".PREFIJO."cat_clasificacion as clas ON fk_clas_id=clas_id $where";
		$this->resultado = $this->conexion->query($query);
		$arr = $this->conexion->fetch($this->resultado);
		return $arr[0];
	}
	function obtenerResoluciones($orderby='', $orderdir='', $filter_clas=0, $filter_anio=0, $filter_folio=0, $limit='', $returnQuery = 0){
		$where = "";
		$order = " ORDER BY ";
		$folio = "";
		$limitquery = "";
		if($orderdir != ''){
			if($orderdir == 'ASC' || $orderdir == 'DESC'){	}else{	$orderdir = 'DESC';	}
		}else{
			$orderdir = 'DESC';
		}
		
		if($orderby == 'res_folio' || $orderby == ''){
			$orderFix = " + 0 ";
		}else{
			$orderFix = "";
		}
		
		if($orderby != '' && $returnQuery == 0){
			$order .= "$orderby $orderFix $orderdir";
		}else{
			$order .= "res_folio $orderFix DESC";
		}

		$initWhere = 0;
		
		#if($filter_clas != 0 || $filter_anio != 0 || $filter_folio != 0) {
		#	$where = "WHERE";
		#}
		
		if($filter_clas != 0){	
			if($initWhere==0) { $where.="WHERE";$initWhere++; }else{ $where.="AND"; }
			$where .= " res.fk_clas_id = $filter_clas ";
		}
		if($filter_anio != 0){
			if($initWhere==0) { $where.="WHERE";$initWhere++; }else{ $where.="AND"; }
			$where .= " res.res_anio = $filter_anio ";
		}
		
		
		if($filter_folio != '' && $filter_folio != 0){
			if($initWhere==0) { $where.="WHERE";$initWhere++; }else{ $where.="AND"; }
			$where .= " CAST(res_folio as CHAR) LIKE '%$filter_folio%' ";
		}
		
		
		
		if($limit != '') { $limitquery = ' LIMIT '.$limit; }
		
		$this->comprobar_conexion();

		if($returnQuery == 0) $tablesQuery = "res.*,clas.clas_nombre as clasificacion"; else  $tablesQuery = "COUNT(*) as total";

		$query = "SELECT $tablesQuery FROM ".PREFIJO."resoluciones as res LEFT JOIN ".PREFIJO."cat_clasificacion as clas ON fk_clas_id=clas_id $where $order $limitquery";
		#echo $query.'<br />';
		$this->resultado = $this->conexion->query($query);
		$this->resultado = $this->conexion->fetch($this->resultado);
		
		if($returnQuery == 0) return $this->resultado;else return $query;#RETURN Array
	}
	function query($query){
		$this->comprobar_conexion();
		return $this->conexion->query($query);
	}
	function insert($tabla,$datos,$type='HTML'){
		$this->comprobar_conexion();
		return $this->conexion->insert($tabla,$datos,$type);
	}
	function update($tabla,$datos,$condicion='',$type='HTML'){
		$this->comprobar_conexion();
		return $this->conexion->update($tabla,$datos,$condicion,$type);
	}
	function delete($tabla,$condicion){
		$this->comprobar_conexion();
		return $this->conexion->delete($tabla,$condicion);
	}
	function fetch($resultado,$opcion = 'ASSOC'){
		$this->comprobar_conexion();
		return $this->conexion->fetch($resultado,$opcion);
	}
	function cerrar(){
		return $this->conexion->close();
	}
	##-----USUARIOS----##
	function obtenerUsuarios($limit='',$returnQuery=0){
		$this->comprobar_conexion();
		$limitquery = '';
		
		if($returnQuery == 0) $tablesQuery = "*,(SELECT bit_acceso FROM ".PREFIJO."perfil WHERE usr_perfil = kid_perfil) as bit,(SELECT ".PREFIJO."perfil.nombre FROM ".PREFIJO."perfil WHERE usr_perfil = kid_perfil) as perfil"; else  $tablesQuery = "COUNT(*) as total";
		if($limit != '') { $limitquery = ' LIMIT '.$limit; }

		$query = "SELECT $tablesQuery FROM ".PREFIJO."usuarios as usr ORDER BY usr_id $limitquery";
		#echo $query.'<br />';
		$this->resultado = $this->conexion->query($query);
		$this->resultado = $this->conexion->fetch($this->resultado);
		
		if($returnQuery == 0) return $this->resultado;else return $query;#RETURN Array		
	}
	function obtenerUsuario($id){
		$this->comprobar_conexion();
		$where = " WHERE usr_id = $id ";
		$query = "SELECT * FROM ".PREFIJO."usuarios $where";
		$this->resultado = $this->conexion->query($query);
		$arr = $this->conexion->fetch($this->resultado);
		return $arr[0];
	}
	##-----CATEGORIAS----##
	function obtenerClasificaciones($limit='',$returnQuery=0){
		$this->comprobar_conexion();

		if($returnQuery == 0) $tablesQuery = "*"; else  $tablesQuery = "COUNT(*) as total";
		if($limit != '') { $limitquery = ' LIMIT '.$limit; }

		$query = "SELECT $tablesQuery FROM ".PREFIJO."cat_clasificacion as clas ORDER BY clas_id $limitquery";
		#echo $query.'<br />';
		$this->resultado = $this->conexion->query($query);
		$this->resultado = $this->conexion->fetch($this->resultado);
		
		if($returnQuery == 0) return $this->resultado;else return $query;#RETURN Array		
	}
	function obtenerClasificacion($id){
		$this->comprobar_conexion();
		$where = " WHERE clas_id = $id ";
		$query = "SELECT * FROM ".PREFIJO."cat_clasificacion $where";
		#echo $query;
		$this->resultado = $this->conexion->query($query);
		$arr = $this->conexion->fetch($this->resultado);
		return $arr[0];
	}
	##-----AÑOS----##
	function obtenerAnios($limit='',$returnQuery=0){
		$this->comprobar_conexion();

		if($returnQuery == 0) $tablesQuery = "*"; else  $tablesQuery = "COUNT(*) as total";
		if($limit != '') { $limitquery = ' LIMIT '.$limit; }

		$query = "SELECT $tablesQuery FROM ".PREFIJO."cat_anio as anio ORDER BY anio DESC $limitquery";
		#echo $query.'<br />';
		$this->resultado = $this->conexion->query($query);
		$this->resultado = $this->conexion->fetch($this->resultado);
		
		if($returnQuery == 0) return $this->resultado;else return $query;#RETURN Array		
	}
	function obtenerAnio($id){
		$this->comprobar_conexion();
		$where = " WHERE anio_id = $id ";
		$query = "SELECT * FROM ".PREFIJO."cat_anio $where";
		#echo $query;
		$this->resultado = $this->conexion->query($query);
		$arr = $this->conexion->fetch($this->resultado);
		return $arr[0];
	}	
}
?>