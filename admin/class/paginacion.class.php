<?php
//requiere se haya llamado la clase mysql = $mysqlconn
class Paginacion
{
	var $regInicial = 0;
	var $printResult = 0;
	var $numRegistros = 0;
	var $pagActual = 0;
	var $conexion = '';
	var $data1 = '';
	var $data2 = '';
	var $data3 = '';
	var $data4 = '';


	function init($conexion){
		$this->conexion = $conexion;
		$this->data1 = NULL;
		if(isset($_GET['data1']) && $_GET['data1'] != ''){ $this->data1 = $_GET['data1']; }else{ $this->data1 = 'LOGIN'; }
		$this->data2 = NULL;
		if(isset($_GET['data2']) && $_GET['data2'] != ''){ $this->data2 = $_GET['data2']; }
		$this->data3 = NULL;
		if(isset($_GET['data3']) && $_GET['data3'] != ''){ $this->data3 = $_GET['data3']; }
		$this->data4 = NULL;
		if(isset($_GET['data4']) && $_GET['data4'] != ''){ $this->data4 = $_GET['data4']; }
	}

	function paginar($queryPagTotal = '',$regXPag,$paginasAMostrar,$pagVar='')//SELECT COUNT(id) as total FROM mensaje-"SELECT * FROM mensaje"-REGXPAG SHOWPAG 
	{
	$resultadoHTML = '';
	//PAGINACION
	$totalRegs = $this->conexion->query($queryPagTotal);
	if(isset($this->data2) && $this->data2 == 'pag'){
		$pagActual = $this->data3;
	}else if(isset($this->data3) && $this->data3 == 'pag'){
		$pagActual = $this->data4;
	}else if(isset($this->data4) && $this->data4 == 'pag'){
		$pagActual = $this->data5;
	}else{
		$pagActual = 1;
	}
	

	$totalRegs = $this->conexion->fetch($this->conexion->resultado);
	
	$numRegistros = $totalRegs[0]['total'];
	
	$numPags = ceil($numRegistros/$regXPag);//número de páginas, redondeo hacia arriba, obtengo solo páginas completas
	
	$resultadoHTML .= "<span>Total de registros:</span> $numRegistros<br />";
	
	$this->numRegistros = $numRegistros;
	if($numPags != 0){//hay páginas
	$this->printResult = 1;
		if ($pagActual > $numPags) $pagActual = $numPags;
		
		if($pagActual > floor($paginasAMostrar/2)){
			$pagInicial = $pagActual - floor($paginasAMostrar/2);
		} else {
			$pagInicial = 1;
		}
		
		$vinculosHTML = ' ';
		
		for($i=$pagInicial; $i<$pagInicial+$paginasAMostrar; $i++){
			if ($i <= $numPags){
				if($i != $pagActual){//no es la página actual
					$vinculosHTML .= '<a href="'.APP_URL.$pagVar.'/pag/'.$i.'">'.$i.'</a>  &nbsp;&nbsp;';
				} else {
					$vinculosHTML .= '<span>'.$i.'</span>  &nbsp;&nbsp;';
				}
			}
		}
		$registroInicial = ($pagActual-1)*$regXPag;
		$this->regInicial = $registroInicial;
		
		$resultadoHTML .= '<span>P&aacute;gina</span> '.$pagActual.' <span>de</span> '.$numPags.' <br /><br />
			
			<table border="0" cellpadding="0" cellspacing="0" width="45%" align="center">
					<tr class="paginacion">
						<td width="15%">';
					 if($pagActual == 1) { 
						$resultadoHTML .= '<span class="btn btn-light" disabled><i class="fa fa-angle-double-left"></i></span> &nbsp; <span class="btn btn-light" disabled><i class="fa fa-angle-left btnOn"></i></span>';
					 } else {
					 	$prePag = $pagActual-1;
						$resultadoHTML .= '<a href="'.APP_URL.$pagVar.'/pag/1" class="btn btn-primary"><i class="fa fa-angle-double-left"></i></a> &nbsp; <a href="'.APP_URL.$pagVar.'/pag/'.$prePag.'" class="btn btn-primary"><i class="fa fa-angle-left"></i></a>';
					 }
						$resultadoHTML .= '</td>
						<td width="70%" align="center">'.$vinculosHTML.'</td>
						<td width="15%">';
					
					 if($pagActual == $numPags) {
						$resultadoHTML .= '<span class="btn btn-light" disabled><i class="fa fa-angle-right"></i></span> &nbsp; <span class="btn btn-light" disabled><i class="fa fa-angle-double-right"></i></span>';
					 } else {
					 	$sigPag = $pagActual+1;
						$resultadoHTML .= '<a href="'.APP_URL.$pagVar.'/pag/'.$sigPag.'" class="btn btn-primary"><i class="fa fa-angle-right btnOn"></i></a> &nbsp; <a href="'.APP_URL.$pagVar.'/pag/'.$numPags.'" class="btn btn-primary"><i class="fa fa-angle-double-right btnOn"></a>';
					 }
						$resultadoHTML .= '</td>
					</tr>
			  </table>';
		
		$this->pagActual = $pagActual;
		$pagLimit = ($pagActual-1)*$regXPag;
		//devolver el arra de resultado
		$arrResultado = array("HTML"=>$resultadoHTML,"LIMIT"=>"$pagLimit,$regXPag");
		return $arrResultado;
		}//fin de hay páginas
	}
}
?>