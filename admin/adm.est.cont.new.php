<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

include("plantilla.inc.php");
?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	// General options
	language : 'es',
	mode : "textareas",
	theme : "advanced",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",

	// Theme options
	//theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,|,visualchars",
	//theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,preview,|,forecolor,backcolor",
	//theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,|,fullscreen",
	//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
	//theme_advanced_buttons4 : 
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

	// Example content CSS (should be your site CSS)
	content_css : "css/contenido.css",

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js",

	// Replace values for the template plugin
	template_replace_values : {
		username : "Some User",
		staffid : "991234"
	}
});
</script>
<table class="layout" summary="layout" width="100%">
				<tr>
					<td id="botonesVert">
					<div class="botones">
						<a href="?est&edit&id=<?php echo $_GET['fid']; ?>"><img src="img/ico/cancel.gif" border="0" alt="Cancelar" /></a>
						<div class="fixed"></div>
						</div>
					</td>
					<td id="workArea">
						<?php if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);?>
						
						<div>
						<form method="POST" action="?est&cont&save">
						<input name="id" type="hidden" value="new" />
						<input name="fid" type="hidden" value="<?php echo $_GET['fid']; ?>" />
						<table class="layout abierto" summary="layout">
							<tr>
								<td><label for="">T&iacute;tulo:</label></td>
								<td><input name="titulo" type="text" value="" size="43" /></td>
							</tr>
							<tr>
								<td><label for="">Inicio de vigencia:</label></td>
								<td><input type="text" name="fecha" id="fecha" size="21" value="" />
<!--link href="css/designBuscador.css" type="text/css" rel="stylesheet" /-->
<script type="text/javascript" src="js/browser.js"></script>
<script type="text/javascript" src="js/calendar.js"></script>
	<script type="text/javascript">
        calendario1 = new dynCalendar({'obj':'fecha'});
     </script>	 </td>
							</tr>
							<tr>
								<td><label for="">Fin de vigencia:</label></td>
								<td><input type="text" name="fechaFin" id="fechaFin" size="21" value="" />
	<script type="text/javascript">
			calendario2 = new dynCalendar({'obj':'fechaFin'});
		</script></td>
							</tr>
							<tr>
							  <td><label>Consecutivo</label></td>
							  <td><select name="consecutivo">
							  <?php for($i=1;$i <= 20; $i++){?>
							  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							  <?php } ?>
							  </select></td>
						  </tr>
							  <?php
							  $usePlantilla = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT (SELECT filepath FROM ".PREFIJO."plantilla WHERE plantilla=kid_plantilla) as plantilla,(SELECT nombre FROM ".PREFIJO."plantilla WHERE plantilla=kid_plantilla) as nombre FROM ".PREFIJO."estructura WHERE kid_pagina=".$_REQUEST['fid']));
							  ?>
							<tr>
								<td><label>Plantilla:</label></td>
								<td><?php echo $usePlantilla[0]['nombre']; ?></td>
							</tr>
							  <?php
							 $ubicaciones = obtenerUbicaciones(TEMPLATE_PATH."templates/".$usePlantilla[0]['plantilla'].".tpl");
							  ?>
							<tr>
							  <td><label>Ubicaci&oacute;n</label></td>
							  <td>
							  <?php isset($_GET['ubic'])?$myibic=$_GET['ubic']:$myibic=''; ?>
							  <select name="ubicacion">
							   <option value="">--Sin asignar--</option>
							   <?php foreach($ubicaciones as $ubic){ ?>
							  <option value="<?php echo $ubic; ?>" <?php echo $myibic==$ubic?'selected="selected"':""; ?> ><?php echo $ubic; ?></option>
							  <?php } ?>
							  </select>
							  </td>
						  </tr>
						</table>
						<br />
						<label>Contenido:</label>
							<textarea name="contenido" rows="50" class="dynamicText">
							
							</textarea>
						</form>
						</div>
						
						<div class="btnContainer fleft">
						<a href="GRABAR" class="btn" onclick="tinyMCE.execCommand('mceSave');return false;">
						<div class="inner">
							<div class="crnl">
							<div class="crnr">
								<img src="img/ico/save.png" width="18" height="16" />
								<div class="text">Guardar</div>
								<div class="fixed"></div>
							</div>
							</div>
						</div>
						</a>
						<div class="fixed"></div>
						</div>
						
						
				  </td>
				  <td valign="top" width="220" class="backSubTools">
					<script type="text/javascript" language="javascript" src="js/x_ajax.js"></script>
				  	<div id="imgcont"></div>
					<div class="fixed"></div>
					<div id="filecont"></div>
					<script type="text/javascript" language="javascript">
								//page          datosURL    capa   tipoEnvio)
					  ajax_getData('<?php echo WEB_PATH; ?>insImage.php','','imgcont','POST');
					  ajax_getData('<?php echo WEB_PATH; ?>insFile.php','','filecont','POST');
					</script>
				  </td>
				</tr>
			</table>
			<div class="fixed">&nbsp;</div>

<?php 

#$res = $myAdmin->obtenerResolucion($_GET['id']);

/*?>
<script type="text/javascript" src="js/validacion_formulario.js"></script>
<script type="text/javascript">
	var data = Array();
	data[0] = Array('res[res_folio]','numero','"Folio de la resoluci�n"');
	//data[1] = Array('arch','texto','"Archivo"');
	data[1] = Array('res[res_anio]','combo','"A�o"');
	data[2] = Array('res[fk_clas_id]','combo','"Clasificaci�n"');
</script>
<form method="post" enctype="multipart/form-data" onsubmit="return validarFormulario(data);">

</form>



<?php */
#}else{
#	echo "<div class='aviso'>No indic� la p�gina a editar</div>";
#}
include("plantillaFoot.inc.php");
?>