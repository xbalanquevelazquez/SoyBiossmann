<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

include("plantilla.inc.php");

//if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
$num = $myAdmin->conexion->fetch(	$myAdmin->conexion->query("SELECT COUNT(*) as total FROM ".PREFIJO."estructura WHERE nivel=0")	);
$num = $num[0]['total'];
/*echo "<pre>";
print_r($resultado);
echo "</pre>";*/

/*if($page3 == 'type'){
	if($_GET['type']=='inf'){
		echo 'inf';
		$nivel = $resultado['nivel'];
		$padre = $resultado['padre'];
		$orden = $resultado['orden'];
		/*SUMAR A LOS DEMAS hacia arriba +1 a orden (incluyendo el actual)* /
	}else if($_GET['type']=='sup'){
		echo 'sup';
		$nivel = $resultado['nivel'];
		$padre = $resultado['padre'];
		$orden = $resultado['orden']+1;
		/*SUMAR A LOS DEMAS hacia arriba +1 a orden (excluyendo el actual)* /
	}else if($_GET['type']=='child'){
		echo 'child';
		$nivel = $resultado['nivel']+1;
		$padre = $resultado['kid_pagina'];
		/*query para saber que orden le toca (debe ser el máximo +1* /
		$totalChilds = $myAdmin->conexion->fetch(	$myAdmin->conexion->query("SELECT MAX(orden) as max FROM ".PREFIJO."estructura WHERE padre=".$padre)	);
		$orden = $totalChilds[0]['max']+1;
		#!!!!!!!!!!!!!!$orden = $resultado['orden']+1;
	}
}*/



/*echo "<pre>";
print_r($arrProcesados);
echo "</pre>";*/

?>
<table class="layout" summary="layout" width="100%">
	<tr>
		<td id="botonesVert">
			<div class="botones">
						<a href="?est"><img src="img/ico/cancel.gif" border="0" alt="Cancelar" /></a>						
						<div class="fixed"></div>
					</div>
		</td>
		<td>
		<div id="workArea">
		<?php if(isset($_GET['msg'])){
		echo  mostrarMensaje($_GET['msg']);
		} ?>
		<form method="POST" action="?est&save&new" name="formPagina">
						<table class="layout abierto" summary="layout">
							<input type="hidden" name="nivel" value="0" />
							<input type="hidden" name="padre" value="0" />
							<input type="hidden" name="orden" value="<?php echo $num+1; ?>" />
							<input type="hidden" name="oldId" value="<?php #echo $_GET['id'] ?>" />
							<input name="plantilla" type="hidden" value="1">
							<input name="newsite" type="hidden" value="1">
							<input name="contenido" type="hidden" value="">
							<!--tr>
								<td>Nivel: <?php echo 0 ?><br />
								Padre: <?php echo 0 ?><br />
								Orden: <?php echo $num+1; ?><br />
								Plantilla: <?php echo 1; ?></td>
							</tr-->
							<tr>
								<td class="label"><label for="">T&iacute;tulo:</label></td>
								<td><input name="titulo" type="text" value="" size="43" /></td>
							</tr>
							<tr>
								<td class="label"><label for="">Alias:</label></td>
								<td><input name="alias" type="text" value="" size="43" onchange="this.value=makeIdentificador(this.value)" /></td>
							</tr>
							<tr>
								<td class="label"><label for="">Visible:</label></td>
								<td><input name="visible" type="checkbox" checked="checked" value="1" /></td>
							</tr>
							<tr>
								<td class="label"><label for="">Publicado:</label></td>
								<td><input name="publicado" type="checkbox" checked="checked" value="1" /></td>
							</tr>
							<tr>
								<td colspan="2">
								<div class="btnContainer fleft">
						<a href="#GUARDAR" class="btn" onclick="document.formPagina.submit();return false;">
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
							</tr>
						</table>
						</form>
		</div>
		</td>
	</tr>
</table>
<?php
/*}else{
	echo "<div class='aviso'>No indicó la página a editar</div>";
}*/
include("plantillaFoot.inc.php");
?>