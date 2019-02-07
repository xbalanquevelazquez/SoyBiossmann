<?php
//requiere se haya llamado la clase mysql = $mysqlconn
class PaginacionB
{
	var $regInicial = 0;
	var $printResult = 0;
	var $numRegistros = 0;
	var $pagActual = 0;
	var $conexion = '';
	
	function paginar($queryPagTotal = '',$regXPag,$paginasAMostrar,$pagVar='',$conn)//SELECT COUNT(id) as total FROM mensaje-"SELECT * FROM mensaje"-REGXPAG SHOWPAG 
	{
	$this->conexion = $conn;
	$resultadoHTML = '';
	//PAGINACION
	$totalRegs = $this->conexion->query($queryPagTotal);
	if (isset($_REQUEST['paginadespliegue'])) $pagActual = $_REQUEST['paginadespliegue']; else $pagActual = 1;
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
					$vinculosHTML .= '<a href="'.$_SERVER['PHP_SELF'].'?'.$pagVar.'&paginadespliegue='.$i.'">'.$i.'</a>  &nbsp;&nbsp;';
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
						$resultadoHTML .= '<img src="img/pager/page-off-start.gif" border="0"> &nbsp; <img src="img/pager/page-off-prev.gif" border="0">';
					 } else {
					 	$prePag = $pagActual-1;
						$resultadoHTML .= '<a href="'.$_SERVER['PHP_SELF'].'?'.$pagVar.'&paginadespliegue=1'.'"><img src="img/pager/page-start.gif" border="0"></a> &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?'.$pagVar.'&paginadespliegue='.$prePag.'>"><img src="img/pager/page-prev.gif" border="0"></a>';
					 }
						$resultadoHTML .= '</td>
						<td width="70%" align="center">'.$vinculosHTML.'</td>
						<td width="15%">';
					
					 if($pagActual == $numPags) {
						$resultadoHTML .= '<img src="img/pager/page-off-next.gif" border="0"> &nbsp; <img src="img/pager/page-off-end.gif" border="0">';
					 } else {
					 	$sigPag = $pagActual+1;
						$resultadoHTML .= '<a href="'.$_SERVER['PHP_SELF'].'?'.$pagVar.'&paginadespliegue='.$sigPag.'"><img src="img/pager/page-next.gif" border="0"></a> &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?'.$pagVar.'&paginadespliegue='.$numPags.'"><img src="img/pager/page-end.gif" border="0"></a>';
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