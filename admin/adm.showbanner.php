<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
$vars = $page;

//obtengo grupo
$queryGroup = "SELECT * FROM ".PREFIJO."grupo_banners WHERE identificador='$idBannerGroup' AND visible=1";
$group =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryGroup));
if(count($group)==1) $continuar = true; else $continuar = false;
if($continuar){
$grupoSel = $group[0]['kid_grupo'];

$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."banners WHERE grupo='$grupoSel'";
$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
	
if($continuar){
		#$paginacion = $myAdmin->paginador->paginar($queryT,10,10,"$vars");
		
		$query = $myAdmin->conexion->query("SELECT * FROM ".PREFIJO."banners WHERE grupo=$grupoSel AND visible=1 ORDER BY posicion ASC");#LIMIT ".$paginacion['LIMIT']
}
		
	$arr = $myAdmin->conexion->fetch($query);
	?><div class="listaBanners listaColor<?php echo $group[0]['selector']; ?>"><div class="titulo"><?php echo $group[0]['titulo']; ?></div><?php
	foreach($arr as $reg){
			?>
			<?php if($reg['link']!=''){ ?>
				<a href="<?php echo $reg['link']; ?>" title="<?php echo $reg['alt']; ?>" target="_blank"><?php } ?>
			<?php if($reg['img']!='') {?>
					<img src="<?php echo WEB_PATH."public/images/banners/".$reg['img']; ?>" alt="<?php echo $reg['alt']; ?>" />
				<?php } ?>
			<?php if($reg['link']!=''){ ?>
				</a>
			<?php } ?>
<?php  }//no imprimir no hay registros ?>
<div class="fixed"></div></div>
<?php }//no está el grupo ?>