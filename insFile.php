<?php 
if(isset($_POST['do'])){
	define('VIEWABLE',true);
}

if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
include('admin/cnf/configuracion.cnf.php');
?>
<div class="fixed"></div>
<div class="titulo"><a href="#"><img src="<?php echo WEB_PATH; ?>admin/img/ico/more.gif" border="0" /></a> Visor de archivos</div>
<?php
$basefolder = 'webfiles';
$basePath = "$basefolder/";
if(isset($_REQUEST['dir'])) {
	$path=$basePath.$_REQUEST['dir'].'/';
}else{
	$path=$basePath;
}
$path = str_replace("//","/",$path);
//es folder base u otro
?>
<!--a href="#" onclick="alert('abc');return false;" id="clicker">click</a-->
<a id="clicker" href="#" onclick="ajax_getData('<?php echo WEB_PATH; ?>insFile.php','dir=<?php echo isset($_REQUEST['dir'])?$_REQUEST['dir']:''; ?>&do=true','filecont','POST');return false;">Recargar</a>
<div class="pathLocator"><?php echo $path; ?></div>
<div class="fixed"></div>
<?php
if(file_exists($path)){ echo "";}else{ die("No puede ver este folder desde este administrador de imágenes.");}//Existe el folder

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
<div class="contenedorImg">
<?php


$directorio=dir($path);
$arrImageFormats = array("doc","ppt","txt","pdf","xls");
$arrFolders = array();
$arrImagenes = array();
$arrNum = 0;

function parseUpPath($path,$tipo = 'normal'){
	$arr = split('[/]',$path);
	$niveles = count($arr);
	$salida = '';
	
	if($tipo == 'up') {$topLevel =  ($niveles-2);}else{ $topLevel = $niveles;}
	
	for($i=1;$i < $topLevel;$i++){
		$salida .= $arr[$i];
		if($i != ($topLevel-1)) {$salida .= '/';}
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
				$arrImagenes[$arrNum]['path'] = $path.''.$file['basename'];
				//$arrImagenes[]['extension'] = $file['extension'];
				$arrNum++;
			}
		}
   }
}
$directorio->close();
sort($arrFolders);
sort($arrImagenes);
?>
<link href="<?php echo WEB_PATH; ?>admin/css/imgadmin.css" type="text/css" rel="stylesheet" />
<table border="0" class="tableCatalog" width="100%">
		<?php
			foreach($arrFolders as $folder){
		?>
	<tr class="folder">
		<?php
		$actualPath = $path.$folder;
		if($folder == '..'){
			$actualPath = parseUpPath($actualPath,'up');
				$ico = 'up.gif';
		}else{
			$actualPath = parseUpPath($actualPath,'normal');
				$ico = 'folder-sm.gif';
		}
		
		#$link = $_SERVER['PHP_SELF'];
		if($actualPath != '' || $actualPath == '/'){
			$link = "dir=".$actualPath;
		}

		?>
		<th width="13%"><a href="#<?php #echo $link; ?>" onclick="ajax_getData('<?php echo WEB_PATH; ?>insFile.php','<?php echo $link; ?>&do=true','filecont','POST');return false;"><img src="img/ico/<?php echo $ico; ?>" border="0" /></a></th>
		<td width="75%"><a href="#<?php #echo $link; ?>" onclick="ajax_getData('<?php echo WEB_PATH; ?>insFile.php','<?php echo $link; ?>&do=true','filecont','POST');return false;"><?php echo $folder; ?></a></td>
		<td width="12%"></td>
	</tr>
		<?php
		}
			foreach($arrImagenes as $archivo){
		?>
	<tr class="image">
		<th><a href="#" onclick="tinyMCE.execCommand('mceReplaceContent',false,'<a href='+'<?php echo $archivo['path']; ?>'+' target=_blank>{$selection}</a>');return false;"><img src="img/ico/<?php echo $archivo['extension']; ?>.gif" border="0" /></a></th>
		<td><a href="#" onclick="tinyMCE.execCommand('mceReplaceContent',false,'<a href='+'<?php echo $archivo['path']; ?>'+' target=_blank>{$selection}</a>');return false;"><?php echo $archivo['nombre']; ?></a></td>
		<td><?php echo $archivo['extension']; ?></td>
	</tr>
		<?php
			}
		?>
</table>
<script type="text/javascript">
function contenido(){
	return 'abc';
}
</script>
		<iframe src="<?php echo WEB_PATH; ?>uploader.php?dir=<?php echo $path; ?>" scrolling="no" frameborder="0" width="100%" height="100" marginheight="0" marginwidth="0"></iframe>
</div>