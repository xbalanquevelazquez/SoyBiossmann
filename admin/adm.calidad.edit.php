<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['res'])) {

/*echo "<pre>";
print_r($_FILES);
die(print_r($_POST));
echo "</pre>";*/
/**
CREATE TABLE noticias(
	kid_noticia INT AUTO_INCREMENT,
	titulo TEXT NOT NULL,
	resumen TEXT NULL,
	fecha DATE NOT NULL,
	link VARCHAR(255) NULL,
	visible INT NOT NULL DEFAULT 1,
*/			
	if($_POST['opcion']=='file'){
		
		if(isset($_FILES['imagen']['name']) && $_FILES['imagen']['name'] != '') {
		$arrExts = array("jpg","gif","png","JPG","GIF","PNG","bmp","BMP");
		$extF = explode(".",$_FILES['imagen']['name']);
		$extension = end($extF);
			if(in_array($extension,$arrExts)){
				$filename = validarArchivo(APP_LOGO_PATH,convertirDatoSeguro($_FILES['imagen']['name']));
				if(copy($_FILES['imagen']['tmp_name'],APP_LOGO_PATH.$filename));else die('Ocurrió un error al copiar el archivo');
				generaThumbnail($filename,APP_LOGO_PATH);
				$_POST['res']['imagen'] = $filename;
			}else{
				//Formato de archivo no adminitdo
			}
		}

	}else if($_POST['opcion']=='borrar'){
		$_POST['res']['imagen']='';
	}
	
	isset($_POST['res']['activo'])? $_POST['res']['activo'] = 1:$_POST['res']['activo'] = 0;
	/*---*/
	
	
	#$_POST['res']['fecha'] = date("Y-m-d H:i:s");
	if(isset($_POST['r']['id']) && is_numeric($_POST['r']['id'])){
		$id = $_POST['r']['id'];
		if($myAdmin->conexion->update(PREFIJO.'calidad_empresa',$_POST['res'],"WHERE kid_empresa=$id",'HTML')){
				header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=1");
		}else{
			die("Error: al insertar registro");
		}
	}else{
		die("Error: proporcionar id");
	}			

}
if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
$res = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."calidad_empresa WHERE kid_empresa='".$_GET['id']."'"));
$res = $res[0];
#print_r($res);
include("plantilla.inc.php");
?>
<script type="text/javascript" src="js/validacion_formulario.js"></script>
<script type="text/javascript">
	var data = Array();
	data[0] = Array('res[nombre_empresa]','texto','"Nombre de la empresa"');
	data[1] = Array('res[orden]','texto','"Orden"');
//	data[1] = Array('res[texto]','texto','"Texto"');
	/*data[2] = Array('res[res_anio]','combo','"Año"');
	data[3] = Array('res[fk_clas_id]','combo','"Clasificación"');*/
</script>


<!------------------------------------------------>
<form method="post" onsubmit="return validarFormulario(data);" enctype="multipart/form-data">
<table class="form" cellspacing="0">
	<input type="hidden" name="r[id]" value="<?php echo $res['kid_empresa']; ?>" />
	<tr>
		<th><label for="res[nombre_empresa]">Nombre de la empresa</label></th>
		<td><input type="text" size="40" name="res[nombre_empresa]" id="nombre_empresa" value="<?php echo $res['nombre_empresa']; ?>" /></td>
	</tr>
	<tr>
		<th><label for="res[orden]">Orden</label></th>
		<td><input type="text" maxlength="10" size="10" name="res[orden]" id="orden" value="<?php echo $res['orden']; ?>" /> 
		</td>
	</tr>
	<tr>
		<th><label for="res[activo]">Activo</label></th>
		<td>
			<input type="checkbox" name="res[activo]" value="1" <?php echo $res['activo']==1?'checked="checked"':''; ?> /></td>
	</tr>
	<tr>
		<th></th>
		<td><input type="radio" name="opcion" value="none" checked="checked" onclick="change('none')" id="btnnomodificar" /><label for="btnnomodificar">No modificar</label> <input type="radio" name="opcion" value="borrar" onclick="change('borrar')" id="btnborrar" /><label for="btnborrar">Borrar</label> 
		  <input type="radio" name="opcion" value="file" onclick="change('file')" id="btnsubirimg" />
		  <label for="btnsubirimg">Subir una imagen (si hab&iacute;a un archivo asociado anteriormente, ser&aacute; reemplazado)</label> </td>
	</tr>
	<tr>
		<th valign="top"><label>Imagen</label></th>
		<td><?php if($res['imagen']!=''){?><img src="<?php echo WEB_LOGO_PATH."".$res['imagen']; ?>" width="175" class="imagenActual" /><?php }else{ ?>No hay imagen asociada.<?php } ?></td>
	</tr>
	<tr>
		<th><label for="imagen" class="contenedorFile">Logotipo (175 x 175 pixeles)</label></th>
		<td><input type="file" name="imagen" value="" id="imagen" size="90" class="contenedorFile" /></td>
	</tr>
	<tr>
		<th></th>
		<td><input type="submit" value="Aceptar" /><button onclick="document.location.href='?<?php echo $page; ?><?php echo $pager ?>'">Cancelar</button></td>
	</tr>
</table>
</form>

<!------------------------------------------------>

<script type="text/javascript">
	var obj = document.getElementById('nombre_empresa');
	obj.focus();
</script>
<script>
	/*var objUrl=document.getElementById('contenedorUrl');
	var objFile=document.getElementById('contenedorFile');*/
	function ocultarDiv(){
		/*obj.style.display='none';*/
		$(".contenedorFile").hide();
		$(".imagenActual").show();
	}
	function mostrarDiv(){
		/*obj.style.display='block';*/
		$(".contenedorFile").show();
		$(".imagenActual").hide();
	}
	function quitarImg(){
		$(".contenedorFile").hide();
		$(".imagenActual").hide();
	}
	function change(type){
		if(type=='url'){
			ocultarDiv();
		}else if(type=='file'){
			mostrarDiv();
		}else if(type=='borrar'){
			quitarImg();
		}else if(type=='none'){
			ocultarDiv();
		}
	}
	change('none');
</script>

<?php include("plantillaFoot.inc.php"); ?>
<?php }else{
?>Faltó el id<?php 
} ?>
