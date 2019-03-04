<?php 
if(!defined('VIEWABLE')) {
	die('Inaccesible');
}
include_once(ADMIN_PATH."cnf/cnfg.calidad.php");
define("CALIDAD_URL", 'http://www.intranet.biossmann.com/intranet/');
$actualCat = isset($data2) && $data2 != '' && is_numeric($data2) && $data2>0?$data2:0;

$query = "SELECT * FROM nuke_downloads_categories WHERE parentid=$actualCat ORDER BY title ASC";
$cats = $connCalidad->fetch($connCalidad->query($query));
$buffer = '';
/* 
cid 
| title
| cdescription
| parentid*/
$buffer .= "<div class='calidadModule'>";
if($actualCat != 0){
	$queryActualCat = "SELECT * FROM nuke_downloads_categories WHERE cid=$actualCat";
	$actual = $connCalidad->fetch($connCalidad->query($queryActualCat));
	$actual = $actual[0];
	$titleActual = utf8_encode(normalizarNuke($actual['title']));
	$buffer .= "<h2>$titleActual</h2>";
	$queryDocs = "SELECT * FROM nuke_downloads_downloads WHERE cid={$actual['cid']}";
	$documents = $connCalidad->fetch($connCalidad->query($queryDocs));
	if(count($documents)>0){
		#echo $queryDocs;
		foreach ($documents as $doc) {
			$docTitle = utf8_encode(normalizarNuke($doc['title']));
			$docDesc = utf8_encode(normalizarNuke($doc['description']));
			$buffer .= "<div class='material setpadding5 bloqueArchivos'>";
			$buffer .= "<a href='".CALIDAD_URL."{$doc['url']}'>{$docTitle}</a>";
			$buffer .= "<div class='info'>Descripción: {$docDesc}<br />";//descripción description 
			$buffer .= "Versión: {$doc['version']}<br />";#" &nbsp; Tamaño del archivo: {$doc['filesize']} <br />";//versión | Tamaño del archivo
			$buffer .= "Agregado el: ".formatoFechaTextual($doc['date']);//agregado el 
			$buffer .= "</div></div><div class='espaciar'></div>";
		}
	}
}

foreach($cats as $cat){
	$title = utf8_encode(normalizarNuke($cat['title']));
	$catID = $cat['cid'];
	$subquery = "SELECT * FROM nuke_downloads_categories WHERE parentid={$catID} ORDER BY title ASC";
	$subcats = $connCalidad->fetch($connCalidad->query($subquery));
	//GET NUM FILES
	$queryNumFilesCat = "SELECT COUNT(*) AS total FROM nuke_downloads_downloads WHERE cid=$actualCat";
	$numFilesCat = $connCalidad->fetch($connCalidad->query($queryNumFilesCat));
	$numFilesCat = $numFilesCat[0]['total'];
	//
	$buffer .= "<div class='listaLinks listaColorazul material bordeGris'>
	<h3><a href='".CURRENT_SECCION."$catID'>$title ($numFilesCat)</a></h3>
	<ul class='bold'>";
	foreach ($subcats as $subcat) {
		$subtitle = utf8_encode(normalizarNuke($subcat['title']));
		$subID = $subcat['cid'];
		//GET NUM FILES SUB
		$queryNumFilesSubcat = "SELECT COUNT(*) AS total FROM nuke_downloads_downloads WHERE cid=$subID";
		$numFilesSubcat = $connCalidad->fetch($connCalidad->query($queryNumFilesSubcat));
		$numFilesSubcat = $numFilesSubcat[0]['total'];
		//
		$buffer .= 	"<li><a href='".CURRENT_SECCION."$subID'>$subtitle ($numFilesSubcat)</a></li>";#<span class='desc'>{$subcat['cdescription']}</span>
	}
	$buffer .= "</ul>
</div><div class='espaciador'></div>";
}
$buffer .= "</div>";
?>