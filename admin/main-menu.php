<?php 
include(LIB_PATH."sitemap.inc.php");
if(isset($site[$page])){
	$pageName = $site[$page]['name'];
	$currentPage = $site[$page]['name'];
}
if($page2!='' && isset($site[$page][$page2])){
	$secondPageName = $site[$page][$page2]['name'];
	$currentPage = $site[$page][$page2]['name'];
}
if($page3!='' && isset($site[$page][$page2][$page3])){
	$thirdPageName = $site[$page][$page2][$page3]['name'];
	$currentPage = $site[$page][$page2][$page3]['name'];
}
#echo "$page    |   $page2";
?>
<div id="mainMenu">
	<!--div class="ini">
		<div class="end"-->
			<div class="menuinner">
				<table class="layout" align="left" cellspacing="0">				
					<tr>
						<?php
						if($myAdmin->comprobarSesion()){ 
							
						$secHabilitadas = implode(",",$myAdmin->permisosUsuario());
						$qSecc = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."seccion WHERE visible=1 AND kid_seccion in ($secHabilitadas)"));
						if(count($qSecc) <= 1){
							$label = "";
						}else{
							$label = "rrect";
						}
						$counter=1;
						foreach($qSecc as $secc){
							#echo validarPermiso(16,$myAdmin->permisosUsuario())?'si':'no';
							if($counter==1){}else if($counter == count($qSecc)){$label = "lrect";}else{$label = "drect";}
						?>
						<td class="<?php echo $label; ?>">
						<a href="<?php echo $_SERVER['PHP_SELF']."?{$secc['acronimo']}"; ?>" class="btn <?php echo $page==$secc['acronimo']?"naranja":"verde"; ?>" title="<?php echo $secc['nombre'] ?>">
							<div class="inner">
								<div class="crnl">
								<div class="crnr">
									<div class="img <?php echo $secc['image'] ?>"></div>
									<div class="fixed"></div>
								</div>
								</div>
							</div>
							<div class="fixed"></div>
							</a>
						</td>
						<?php 
							$counter++;
						} ?>
				<?php }#comprobar session ?>
					</tr>
				</table>
				
				
				<div class="fixed"></div>
			</div>
		<!--/div>
	</div-->
	<div class="fixed"></div>
</div>
<!-- Breadcrumb -->
<?php if($myAdmin->comprobarSesion()){ ?>
<div id="breadcrumb">
<a href="<?php echo $_SERVER['PHP_SELF']."?".$page ?><?php echo isset($_GET['parentpaginadespliegue'])?'&paginadespliegue='.$_GET['parentpaginadespliegue']:''?>"><?php echo $pageName ?></a> 
<?php if(isset($thirdPageName)){
		echo ' &rsaquo; <a href="'.$_SERVER['PHP_SELF'].'?'.$page.'&edit&id='.$_GET['fid'].'">'.$secondPageName.'</a>';
		echo ' &rsaquo; '.$thirdPageName;
	}else{
		echo isset($secondPageName)?' &rsaquo; '.$secondPageName:'';
	}
?>

</div>
<div id="ubicator">
	<div class="fleft"><?php echo $currentPage; ?></div>
	<?php if(isset($thirdPageName)){?>
	<div class="fright"><a href="<?php echo $_SERVER['PHP_SELF']."?".$page."&edit&id=".$_GET['fid'] ?>">Regresar</a></div>
	<?php }else if(isset($secondPageName)){?>
	<div class="fright"><a href="<?php echo $_SERVER['PHP_SELF']."?".$page ?><?php echo isset($_GET['parentpaginadespliegue'])?'&paginadespliegue='.$_GET['parentpaginadespliegue']:''?>">Regresar</a></div>
	<?php } ?>
	<div class="fixed"></div>
</div>
<?php } ?>