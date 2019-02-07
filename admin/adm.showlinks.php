<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

$vars = $page;

//obtengo grupo
$queryGroup = "SELECT * FROM ".PREFIJO."grupo_links WHERE identificador='$idLinkGroup' AND visible=1";
$group =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryGroup));
if(count($group)==1) $continuar = true; else $continuar = false;
if($continuar){
$grupoSel = $group[0]['kid_grupo'];

$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."lista_links WHERE grupo='$grupoSel'";
$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
	
if($continuar){
		#$paginacion = $myAdmin->paginador->paginar($queryT,10,10,"$vars");
		
		$query = $myAdmin->conexion->query("SELECT * FROM ".PREFIJO."lista_links WHERE grupo=$grupoSel AND visible=1 ORDER BY posicion ASC");#LIMIT ".$paginacion['LIMIT']
}
		
	$arr = $myAdmin->conexion->fetch($query);
	?><div class="listaLinks listaColor<?php echo $group[0]['selector']; ?>"><div class="titulo"><?php echo $group[0]['titulo']; ?></div><ul><?php
	foreach($arr as $reg){
			?>
			<li class="<?php echo $reg['selector']; ?>">
				<?php if($reg['link']!=''){ ?><a href="<?php echo $reg['link']; ?>" title="<?php echo $reg['titulo']; ?>" <?php if($reg['icono']!='') {?>target="_blank"<?php } ?>><?php } ?>
				<?php echo html_entity_decode($reg['titulo']); ?>
				<?php if($reg['icono']!='') {?><img src="<?php echo WEB_PATH."public/images/".$reg['icono'].".gif"; ?>" alt="<?php echo $reg['icono']; ?>" /><?php } ?>
				<?php if($reg['link']!=''){ ?></a><?php } ?>
			</li>
<?php  }//no imprimir no hay registros ?>
</ul><div class="fixed"></div></div>
<?php }//no está el grupo ?>