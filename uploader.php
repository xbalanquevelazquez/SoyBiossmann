<?php 
if(!isset($_GET['dir'])){
	exit();
}
define("VIEWABLE",true);
include('admin/cnf/configuracion.cnf.php');
include(LIB_PATH."functions.inc.php");
$dir = $_GET['dir']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>uploader</title>
<style>
body{margin:0px;padding:0px;font-size:.7em}
*{font-family:Verdana, Arial, Helvetica, sans-serif;}
input{font-size:.9em}
</style>
</head>

<body>
<hr />
<?php 
if(isset($_POST['do']) && $_FILES['upload']['name'] != '') {
	#print_r($_FILES);
	#echo APP_PATH.$dir;
	$arrExts = array("doc","pdf","xls","ppt","pps","txt","DOC","PDF","XLS","PPT","PPS","TXT","docx","xlsx","pptx","jpg","gif","png","JPG","GIF","PNG","bmp","BMP");
	$extension = end(explode(".",$_FILES['upload']['name']));
	#echo $extension;
	if(in_array($extension,$arrExts)){
	#print_r($_FILES);
		$filename = validarArchivo(APP_PATH.$dir,convertirDatoSeguro($_FILES['upload']['name']));
		#$_POST['res']['link']= WEB_FILE_PATH.$filename;
		#die($filename);
		if(copy($_FILES['upload']['tmp_name'],APP_PATH.$dir.$filename));else die('Ocurrió un error al copiar el archivo');
	}else{
		echo "Formato de archivo no adminitdo.";	
	}
	?>
	<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/parent_ajax.js"></script>
	<script type="text/javascript">
					//page          datosURL    capa   tipoEnvio)
					 // ajax_getData('<?php echo WEB_PATH; ?>insImage.php','&do=true','imgcont','POST');
					 //parent.document.doit();
					 var obj = parent.document.getElementById('clicker');
					 if (navigator.appName.indexOf('Microsoft Internet Explorer') !=-1) {
						obj.click();
					} else {
						obj.onclick();
					}

					 //obj.click();
					// obj.onclick();
					  //ajax_getData('<?php echo WEB_PATH; ?>insFile.php','&do=true','filecont','POST');
					  //var obj = parent.document.getElementById('filecont');
					  //obj.style.display='none';
	</script>
	<?php
	#print_r($_POST);
}
?>
	<table border="0" cellpadding="0" cellspacing="0">
	<form action="<?php echo $_SERVER['PHP_SELF']."?dir=".$dir; ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="do" value="true" />
	<input type="hidden" name="dir" value="<?php echo $dir; ?>" />
	<tr>
		<td colspan="3"><label for="upload">Subir un archivo...</label></td>
	</tr>
	<tr>
		<td colspan="3"><input type="file" name="upload" size="19" /><br /><input type="submit" value="Subir" /></td>
	</tr>
	</form>
	</table>
</body>
</html>
