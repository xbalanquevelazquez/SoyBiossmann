<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
if(		$page2=='new'){ 	include_once("adm.est.new.php");}
else if($page2=='newsite'){ include_once("adm.est.newsite.php");}
else if($page2=='newpage'){ include_once("adm.est.newpage.php");}
else if($page2=='edit'){ 	include_once("adm.est.edit.php");}
else if($page2=='save'){ 	include_once("adm.est.save.php");}
else if($page2=='change'){ 	include_once("adm.est.change.php");}
else if($page2=='delete'){ 	include_once("adm.est.delete.php");}
else if($page2=='cont'){ 	include_once("adm.est.cont.php");}
else{

	include("plantilla.inc.php");
$sites = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE nivel=0 ORDER BY kid_pagina"));

?>
<table class="layout" summary="layout" width="100%">
	<tr>
		<td id="subMenu">
			<a href="?est&newsite"><img src="img/ico/website-big.gif" alt="Crear un nuevo sitio web" /><div>Crear sitio web</div></a>
			<?php if(count($sites)!=0){ ?><a href="?est&newpage"><img src="img/ico/webpage-big.gif" alt="Crear una p&aacute;gina web" /><div>Crear p&aacute;gina web</div></a><?php } ?>
		</td>
		<td id="workArea">
		<?php 
		foreach($sites as $site){
			$id = $site['kid_pagina'];
			
			$resultado = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."view_estructura where kid_pagina={$id} OR iniPadre={$id} OR lvl1={$id} OR lvl2={$id} OR lvl3={$id} OR lvl4={$id} OR lvl5={$id} OR lvl6={$id} OR lvl7={$id} OR lvl8={$id} OR lvl9={$id} OR lvl10={$id}"));
		?>
		<div class="mapsite"><?php $estructura->crearEstructura($resultado); ?></div>
		<?php }	?>
		
		</td>
	</tr>
</table>
<?php
	//echo $paginacion['HTML'];
	include("plantillaFoot.inc.php");
}
?>