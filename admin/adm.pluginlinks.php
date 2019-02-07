<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
include("plantilla.inc.php");
?>
<h1>Lista de Links</h1>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/links/x_ajax.js"></script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/links/validacion_formulario.js"></script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/links/enviaAJAX.js"></script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/libs.js"></script>
<script type="text/javascript">
	function confirmar(vars){
		var textoMensaje = 'Esta acción borrará la información, ¿desea continuar?';
		if(confirm(textoMensaje)){
			ajax_getData('<?php echo WEB_PATH; ?>admin/adm.links.php',vars,'elemContenedor','GET');
			//return true;
		}else{
			return false;
		}
	}
</script>
<script type="text/javascript">
	function confirmarGroup(vars){
		var textoMensaje = 'Esta acción borrará la información, ¿desea continuar?';
		if(confirm(textoMensaje)){
			ajax_getData('<?php echo WEB_PATH; ?>admin/adm.linkgroups.php',vars,'groupContenedor','GET');
			//return true;
		}else{
			return false;
		}
	}
</script>
<table>
	<tr>
		<td width="55%" valign="top"><div id="elemContenedor"></div></td>
		<td width="15"></td>
		<td width="35%" valign="top">
<div class="box">
<h2>Admin. grupos de links</h2>
<div id="groupContenedor"></div>
</td>
	</tr>
</table>
<script type="text/javascript">
ajax_getData('<?php echo WEB_PATH; ?>admin/adm.links.php','','elemContenedor','GET');
</script>
<script type="text/javascript">
ajax_getData('<?php echo WEB_PATH; ?>admin/adm.linkgroups.php','','groupContenedor','GET');
</script>

<?php
include("plantillaFoot.inc.php");
?>