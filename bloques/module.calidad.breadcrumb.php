<?php 
if(!defined('VIEWABLE')) {
	die('Inaccesible');
}
include_once(ADMIN_PATH."cnf/cnfg.calidad.php");

$actualCat = isset($data2) && $data2 != '' && is_numeric($data2) && $data2>0?$data2:0;

$query = "SELECT * FROM nuke_downloads_categories WHERE parentid=$actualCat ORDER BY title ASC";
$cats = $connCalidad->fetch($connCalidad->query($query));
$bufferBread = '';
$bufferBread .= "<div class='breadcrumb right'>";

$code = "<a href='".APP_URL."'>Inicio</a><span class='separador'><i class='fa fa-caret-right'></i></span> ";
if($actualCat==0){
	$code .= "Calidad";
}else{
	$code .= "<a href='".CURRENT_SECCION."'>Calidad</a>";
}
$bufferBread .= getLinkBreadCalidad($actualCat,TRUE,$code);

$bufferBread .= "</div>";

function getLinkBreadCalidad($currentID,$currentSeccion,$buffer=''){
	global $connCalidad;
	$enlace = $buffer.'';

	$query = "SELECT * FROM nuke_downloads_categories WHERE cid=$currentID";
	$actual = $connCalidad->fetch($connCalidad->query($query));
	
	if(count($actual)>0){
		$actual = $actual[0];
		$titleActual = utf8_encode(normalizarNuke($actual['title']));
		$idActual = $currentID;
		$padreDelActual = $actual['parentid'];
		
		if($padreDelActual != 0){
			$enlace .= getLinkBreadCalidad($padreDelActual,FALSE,'');
		}
		$enlace .= "<span class='separador'><i class='fa fa-caret-right'></i></span>";
		if(!$currentSeccion){
			$enlace .= "<a href='".CURRENT_SECCION."$idActual'>";
		}
		$enlace .= "$titleActual";# (padre:$padreDelActual)
		if(!$currentSeccion){
			$enlace .= 	"</a>";
		}
	}
	return $enlace;

}
?>