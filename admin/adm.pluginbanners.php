<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
include("plantilla.inc.php");
?>
<h1>Banners</h1>

<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/banners/enviaAJAX.js"></script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/banners/x_ajax.js"></script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/banners/validacion_formulario.js"></script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/libs.js"></script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">
	function confirmar(vars){
		var textoMensaje = 'Esta acci�n borrar� la informaci�n, �desea continuar?';
		if(confirm(textoMensaje)){
			ajax_getData('<?php echo WEB_PATH; ?>admin/adm.banners.php',vars,'elemContenedor','GET');
			//return true;
		}else{
			return false;
		}
	}
</script>
<script type="text/javascript">
	function confirmarGroup(vars){
		var textoMensaje = 'Esta acci�n borrar� la informaci�n, �desea continuar?';
		if(confirm(textoMensaje)){
			ajax_getData('<?php echo WEB_PATH; ?>admin/adm.bannergroups.php',vars,'groupContenedor','GET');
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
<h2>Admin. grupos de banners </h2>
<div id="groupContenedor"></div>
</div>
</td>
	</tr>
</table>
<script type="text/javascript">
ajax_getData('<?php echo WEB_PATH; ?>admin/adm.banners.php','','elemContenedor','GET');
ajax_getData('<?php echo WEB_PATH; ?>admin/adm.bannergroups.php','','groupContenedor','GET');
$(document).ready(function(){
	
});
</script>

<?php
include("plantillaFoot.inc.php");
?>