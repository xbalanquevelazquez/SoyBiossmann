<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

// print_r($_POST);
// print_r($_FILES);
if(isset($_POST['accion']) && $_POST['accion']=='insertar') {
	header("Content-Type: application/json; charset=utf-8", true);

	$success = FALSE;
	$error   = 'No especificado';
	$data    = array();
	$action  = $_POST['accion'];

	$tempError = '';
	$titulo 	= isset($_POST['titulo']) && trim($_POST['titulo']) != ''?utf8_decode(trim($_POST['titulo'])):'';
	$alt 	= isset($_POST['alt']) && trim($_POST['alt']) != ''?utf8_decode(trim($_POST['alt'])):'';
	$grupo 	= isset($_POST['grupo']) && trim($_POST['grupo']) != ''?trim($_POST['grupo']):'Sin especificar';
	$link 	= isset($_POST['link']) && trim($_POST['link']) != ''?trim($_POST['link']):'';
	$posicion = isset($_POST['posicion']) && trim($_POST['posicion']) != '' && is_numeric($_POST['posicion']) ?trim($_POST['posicion']):'0';
	$visible 	= isset($_POST['visible']) && trim($_POST['visible']) != ''?trim($_POST['visible']):'0';
	$color 	= isset($_POST['color']) && trim($_POST['color']) != '' && is_numeric($_POST['color'])?trim($_POST['color']):'1';

	$img 	= isset($_FILES["img"]["tmp_name"]) && ($_FILES["img"]["error"] == 0) ?$_FILES["img"]:'Sin especificar';

	if($grupo == 'Sin especificar'){
		$tempError .= 'No se especificó grupo. ';
	}
	if($img == 'Sin especificar'){
		$tempError .= 'Hubo problemas al subir la imagen - code: '.$_FILES["img"]["error"];
	}

	
	if($tempError != ''){
		$success = FALSE;
		$error   = $tempError;
		$data    = array();	
	}else{
		//$queryMail = "SELECT count(*) as cuenta FROM usuarios WHERE email='$email'";
/**

  kid_banner INT(11) NOT NULL AUTO_INCREMENT,
  img VARCHAR(255) NOT NULL DEFAULT '',
  grupo INT(11) NOT NULL DEFAULT '1',
  link TEXT,
  titulo VARCHAR(255),
  alt VARCHAR(255) DEFAULT NULL,
  posicion INT(11) NOT NULL DEFAULT '1',
  visible INT(11) NOT NULL DEFAULT '1',
  color INT(11) NOT NULL DEFAULT '1',

  Array
(
    [img] => Array
        (
            [name] => foto-medica.png
            [type] => image/png
            [tmp_name] => C:\XiuhBalam\wamp\tmp\php505F.tmp
            [error] => 0
            [size] => 959545
        )

)
*/		

  	//if(isset($_FILES["img"]["type"])){
		$validextensions = array("jpeg", "jpg", "png", "gif");
		$validtypes = array("image/jpeg", "image/jpg", "image/png", "image/gif");
		$temporary = explode(".", $_FILES["img"]["name"]);
		$file_extension = end($temporary);
		$file_type = $_FILES["img"]["type"];
		if (in_array($file_type, $validtypes) && in_array($file_extension, $validextensions)) {
		//Hasta 2 MB files can be uploaded.
			if($_FILES["img"]["size"] < (2 * 1024 * 1024)){

				$nombreBanner = validarArchivo(BANNER_PATH,$_FILES["img"]["name"]);

				$sourcePath = $_FILES['img']['tmp_name']; // Storing source path of the file in a variable
				$targetPath = BANNER_PATH.$nombreBanner; // Target path where file is to be stored
				if(move_uploaded_file($sourcePath,$targetPath)){// Moving Uploaded file
					
					$datos = array('img'=>$nombreBanner,'grupo'=>$grupo,'link'=>$link,'titulo'=>$titulo,'alt'=>$alt,'posicion'=>$posicion,'visible'=>$visible,'color'=>$color);
					if($myAdmin->conexion->insert(PREFIJO.'banners',$datos,'HTML')){
						$success = TRUE;
						$error   = '';
						$data    = array('mensaje'=>'Registro correcto');	
							
					}else{
						$success = FALSE;
						$error   = $myAdmin->conexion->error;
						$data    = array();	
						//die("Error: al insertar registro");
					}

				}else{
					$error = error_get_last();
					$success = FALSE;
					$error   = 'No se pudo mover el archivo en el servidor.'.$error['message'];
					$data    = array();	
				}
			}else{
				$success = FALSE;
				$error   = 'Tamaño de archivo demasiado grande (límite 2 MB).';
				$data    = array();	
			}
		}else{
			$success = FALSE;
			$error   = 'Tipo de archivo incorrecto.';
			$data    = array();	
		}
		
	//}
		/**/


		//$resNoMail = mysql_fetch_assoc(mysql_query($queryMail));
		
	}//SIN tempError


	$respuesta = array('action'=>$action,'success'=>$success,'error'=>$error,'data'=>$data);
	echo json_encode($respuesta);
		

}else{
#if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
$grupoSel = isset($_GET['grupo'])?$_GET['grupo']:1;
#print_r($res);
#include("../banners/plantilla.inc.php");
?>

<h2>Nuevo banner</h2>
<hr />
<form>
<table class="form" cellspacing="0">
	<input type="hidden" id="tipo" value="new" />
	<input type="hidden" name="id" id="id" value="" />
	<tr>
		<th><label for="img">Imagen</label></th>
		<?php 
		#$imagenes = mostrarImagenesBan();
		?>		<td>

					<input type="file" name="img" id="img">

		</td>
	</tr>
	<tr>
		<th><label for="grupo">Grupo</label></th>
		<td>
		<?php 
		$queryGroup = "SELECT * FROM ".PREFIJO."grupo_banners";
		$groups =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryGroup));
		#$grupoSel = $res['grupo'];
		?>
		<select name="grupo" id="grupo">
		<?php foreach($groups as $grupo){ ?>
		<option value="<?php echo $grupo['kid_grupo']; ?>" <?php echo $grupoSel==$grupo['kid_grupo']?'selected="selected"':'' ?>><?php echo $grupo['titulo']; ?></option>
		<?php } ?>
		</select>
		</td>
	</tr>
	<tr>
		<th><label for="link">Link</label></th>
		<td><input type="text" size="45" name="link" id="link" onchange="this.value=protectLink(this.value);"/></td>
	</tr>
	<tr>
		<th><label for="titulo">Título</label></th>
		<td><input type="text" maxlength="200" size="45" name="titulo" id="titulo" /></td>
	</tr>
	<tr>
		<th><label for="alt">Texto alternativo</label></th>
		<td><input type="text" maxlength="200" size="45" name="alt" id="alt" /></td>
	</tr>
	<tr>
		<th><label for="color">Color:</label></th>
		<td><select name="color" id="color">
			<?php for($c=1;$c<=6;$c++){
				$colorData = getColor($c);
			 ?>
			<option value="<?php echo $c; ?>"><?php echo $colorData['colorName']; ?></option>
			<?php } ?>
		</select></td>
	</tr>
	<tr>
		<th><label for="posicion">Posici&oacute;n</label></th>
		<td><input type="text" maxlength="200" size="5" name="posicion" id="posicion" /></td>
	</tr>
	<tr>
		<th><label for="visible">Visible</label></th>
		<td>
			<input type="checkbox" checked="checked" name="visible" id="visible" value="1" />		</td>
	</tr>
	<tr>
		<th></th>
		<td>
			<a class="btnForm" id="btnAltaBanner">Aceptar</a>
			<!--enviaAJAX('<?php #echo WEB_PATH; ?>admin/adm.banners.php')-->
			<a onclick="ajax_getData('<?php echo WEB_PATH; ?>admin/adm.banners.php','&grupo=<?php echo $grupoSel; ?>','elemContenedor','GET');" class="btnForm">Cancelar</a>
		</td>
	</tr>
</table>
</form>
<hr />

<?php #}
} ?>
<?php #include("../banners/plantillaFoot.inc.php"); ?>