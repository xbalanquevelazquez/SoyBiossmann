<?php 
if(isset($_POST['do'])){
	define('VIEWABLE',true);
}

if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
include('admin/cnf/configuracion.cnf.php');
?>
<div class="fixed"></div>
<div class="titulo"><a href="#" onclick="ajax_getData('<?php echo APP_URL; ?>insImage.php','oculto&do=true','imgcont','POST');return false;"><img src="<?php echo APP_URL; ?>admin/img/ico/more.gif" border="0" /></a> Visor de im&aacute;genes</div>
<?php
$basefolder = 'webimgs';
$basePath = "$basefolder/";
if(isset($_REQUEST['dir'])) {
	$path=$basePath.$_REQUEST['dir'].'/';
}else{
	$path=$basePath;
}//es folder base u otro
$path = str_replace("//","/",$path);
?>
<?php
if(file_exists($path)) echo "";else die("No puede ver este folder desde este administrador de imágenes.");//Existe el folder

#VISUALIZACIÓN DE MINIATURAS
if(isset($_REQUEST['mini']) && $_REQUEST['mini']=='true'){
	$mini = TRUE;
	$check = 'checked="checked"';
	$cambio = 'false';
}else{
	$mini = FALSE;
	$check = '';
	$cambio = 'true';
}
?>
<a id="clickerImg" href="#" onclick="ajax_getData('<?php echo APP_URL; ?>insImage.php','dir=<?php echo isset($_REQUEST['dir'])?$_REQUEST['dir']:''; ?>&mini=<?php echo isset($_REQUEST['mini'])?$_REQUEST['mini']:''; ?>&do=true','imgcont','POST');return false;">Recargar</a>
<div class="pathLocator"><?php echo $path; ?></div>
<div class="miniSelector">
<input type="checkbox" name="mini" onclick="ajax_getData('<?php echo APP_URL; ?>insImage.php','<?php echo "dir=".preg_replace('/$/','',str_replace("$basefolder/",'',$path)); ?>&mini=<?php echo $cambio; ?>&do=true','imgcont','POST');return false;" <?php echo $check; ?> /> Visualizar miniaturas
</div>
<div class="contenedorImg">
<?php


$directorio=dir($path);
$arrImageFormats = array("jpg","gif","bmp","png");
$arrFolders = array();
$arrImagenes = array();
$arrNum = 0;

function parseUpPath($path,$tipo = 'normal'){
	$path = str_replace("[//]","/",$path);
	$arr = split('[/]',$path);
	$niveles = count($arr);
	$salida = '';
	
	if($tipo == 'up') $topLevel =  ($niveles-2);else $topLevel = $niveles;
	
	for($i=1;$i < $topLevel;$i++){
		$salida .= $arr[$i];
		if($i != ($topLevel-1)) $salida .= '/';
	}
	return $salida;
}

if($path != $basePath){ $arrFolders[] = "..";}

while ($archivo = $directorio->read()){
	if($archivo != '.' && $archivo != '..'){
		//pathinfo #dirname #basename #extension
		$file = pathinfo($archivo);
		$tipo = filetype($path.'/'.$file['basename']);
		if($tipo == 'dir'){
				$arrFolders[] = $file['basename'];
		}else if($tipo == 'file'){
			if(in_array(strtolower($file['extension']),$arrImageFormats)){
				$arrImagenes[$arrNum]['nombre'] = $file['basename'];
				$arrImagenes[$arrNum]['extension'] = $file['extension'];
				$arrImagenes[$arrNum]['path'] = $path.$file['basename'];
				#$arrImagenes[]['extension'] = $file['extension'];
				$arrNum++;
			}
		}
   }
}
$directorio->close();
sort($arrFolders);
sort($arrImagenes);
?>
<link href="<?php echo APP_URL; ?>admin/css/imgadmin.css" type="text/css" rel="stylesheet" />
<table border="0" class="tableCatalog">
		<?php
			foreach($arrFolders as $folder){
		?>
	<tr class="folder">
		<?php
		$actualPath = $path.$folder;
		if($folder == '..'){
			$actualPath = parseUpPath($actualPath,'up');
			if($mini == TRUE) {
				$ico = 'up-md.gif';
			}else{
				$ico = 'up.gif';
			}
		}else{
			$actualPath = parseUpPath($actualPath,'normal');
			if($mini == TRUE) {
				$ico = 'folder-md.gif';
			}else{
				$ico = 'folder-sm.gif';
			}
		}
		
		#$link = $_SERVER['PHP_SELF'];
		if($actualPath != '' || $actualPath == '/'){
			$link = "dir=".$actualPath;
		}
		if($mini == TRUE) {
			$link .= '&mini=true';
		}

		?>
		<th><a href="#<?php #echo $link; ?>" onclick="ajax_getData('<?php echo APP_URL; ?>insImage.php','<?php echo $link; ?>&do=true','imgcont','POST');return false;"><img src="<?php echo APP_URL; ?>admin/img/ico/<?php echo $ico; ?>" border="0" /></a></th>
		<td><a href="#<?php #echo $link; ?>" onclick="ajax_getData('<?php echo APP_URL; ?>insImage.php','<?php echo $link; ?>&do=true','imgcont','POST');return false;"><?php echo $folder; ?></a></td>
		<td></td>
	</tr>
		<?php
		}
			foreach($arrImagenes as $imagen){
		?>
	<tr class="image">
		<?php if($mini == true) {
				/*Obtengo datos de la imagen*/
				$imgData = getimagesize($imagen['path']);
				//echo "ancho: $imgData[0], alto: $imgData[1]";
				if($imgData[0] >= $imgData[1]){
					$medida = 'width';
				}else{
					$medida = 'height';
				}
		?>
		<th><a href="#" onclick="tinyMCE.execCommand('mceInsertRawHTML',false,'<img src='+'<?php echo "../".$imagen['path']; ?>'+' />');return false;"><img src="<?php echo APP_URL.$imagen['path']; ?>" border="0" <?php echo $medida; ?>="35" /></a></th>
		<?php }else{ ?>
		<th><a href="#" onclick="tinyMCE.execCommand('mceInsertRawHTML',false,'<img src='+'<?php echo "../".$imagen['path']; ?>'+' />');return false;"><img src="<?php echo APP_URL; ?>admin/img/ico/image-sm.gif" border="0" /></a></th>
		<?php } ?>
		<td><a href="#" onclick="tinyMCE.execCommand('mceInsertRawHTML',false,'<img src=&quot;<?php echo "../".$imagen['path']; ?>&quot; />');return false;"><?php echo $imagen['nombre']; ?></a></td>
		<td><?php echo $imagen['extension']; ?></td>
	</tr>
		<?php
			}
		?>
</table>
		<iframe src="<?php echo APP_URL; ?>uploaderImg.php?dir=<?php echo $path; ?>" scrolling="no" frameborder="0" width="100%" height="100" marginheight="0" marginwidth="0"></iframe>
</div>