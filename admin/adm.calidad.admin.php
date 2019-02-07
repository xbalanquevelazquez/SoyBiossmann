<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
$vars = $page;
$secondPageName = 'Admin';
$currentPage = 'Administraci�n de ';
//$pager = isset($_GET['paginadespliegue'])?'&paginadespliegue='.$_GET['paginadespliegue']:'';
if(		isset($_GET['new'])){ 		include_once("adm.$page.admin.new.php");}
else if(isset($_GET['edit'])){ 		include_once("adm.$page.admin.edit.php");}
else if(isset($_GET['delete'])){ 	include_once("adm.$page.admin.delete.php");}
else if(isset($_GET['fast'])){ 		include_once("adm.$page.admin.fast.php");}
else{
	include("plantilla.inc.php");
	$first = true;
	$i=1;
	$kid_empresa = $_GET['id'];

	#$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."calidad_empresa";
	
	#$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
	
	#if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
	
	#if($continuar){
		#$paginacion = $myAdmin->paginador->paginar($queryT,10,10,"$vars");
		if($query = $myAdmin->conexion->query("SELECT * FROM ".PREFIJO."calidad_empresa WHERE kid_empresa='$kid_empresa'")){
			$continuar = true;
			$fetch = $myAdmin->conexion->fetch($query,'ASSOC');
			$reg = end($fetch);
			//$reg = $myAdmin->conexion->fetch_assoc
		}else {
			$continuar = false;
		}
	#}
	
	if($continuar){

	$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."calidad_categoria";
	
	$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
	
	if($resInit[0]['total'] == 0) $continuarDetalle = false; else $continuarDetalle = true;
		#$paginacion = $myAdmin->paginador->paginar($queryT,10,10,"$vars");
		if($queryDetalle = $myAdmin->conexion->query("SELECT * FROM ".PREFIJO."calidad_categoria WHERE fid_empresa='$kid_empresa'")){
			$continuarDetalle = true;
			$fetchDetalle = $myAdmin->conexion->fetch($queryDetalle,'ASSOC');
			$regDetalle = end($fetchDetalle);
			//$reg = $myAdmin->conexion->fetch_assoc
		}else {
			$continuarDetalle = false;
		}
	}
	
	if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);
	#print_r($reg);
?>
<script type="text/javascript">
	function confirmar(){
		var textoMensaje = 'Esta acci�n borrar� la informaci�n, �desea continuar?';
		if(confirm(textoMensaje)){
			return true;
		}else{
			return false;
		}
	}
</script>
<div class="actualCat">
	<div class="bloqueCalidadEmpresa"><img src="<?php echo WEB_LOGO_PATH.$reg['imagen']; ?>" alt="" width="75"></div><div class="bloqueCalidadEmpresaNombre"><?php echo html_entity_decode($reg['nombre_empresa']); ?> </div>
</div>
<div class="divPaginador">
<?php echo isset($paginacion['HTML'])?$paginacion['HTML']:''; ?>
</div>
<table class="layout" width="90%" align="center">
	<tr>
		<td>
		<div class="btnContainer fright">
		<a href="?<?php echo $page; ?>&new<?php echo $pager ?>" class="btn">
		<div class="inner">
			<div class="crnl">
			<div class="crnr">
				<img src="img/ico/new.png" />
				<div class="text">Nuevo registro</div>
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
<?php if($continuar && $continuarDetalle){?>
<table class="datos" cellspacing="0">
	<thead>
	<tr>
		<th>Id</th>
		<th>Nombre de Categor�a</th>
		<th>Visible</th>
		<th>Orden</th>
		<th colspan="3" width="80">Operaciones</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$arr = $myAdmin->conexion->fetch($query);
	if(count($arr) <= 0){
		?>
		<tr>
		<td colspan="6" align="center" class="aviso">No se encontraron datos</td>
		</tr>
	<?php
	}else{
	#print_r($arr);
	foreach($arr as $reg){
			?>
			<tr class="<?php echo $reg['activo']==0?' inactivo':''?>" >
				<td><?php echo $reg['kid_empresa']; ?></td>
				<td><?php echo html_entity_decode($reg['nombre_empresa']); ?></td>
				<td><img src="<?php echo WEB_LOGO_PATH.$reg['imagen']; ?>" alt="" width="75"></td>
				<td><a href="?<?php echo $page; ?>&fast=<?php echo $reg['activo']==1?'invisible':'visible'; ?>&id=<?php echo $reg['kid_empresa'] ?><?php echo $pager ?>" title="Hacer <?php echo $reg['activo']==1?'invisible':'visible'; ?>"><img src="img/ico/sem-<?php echo $reg['activo']==1?'vd':'gr'; ?>.gif" /></a></td>
				<td><?php echo $reg['orden']; ?></td>
				<td><a href="?<?php echo $page; ?>&edit&id=<?php echo $reg['kid_empresa'] ?><?php echo $pager ?>" title="Editar"><img src="img/ico/edit.png" /></a></td>
				<td><a href="?<?php echo $page; ?>&delete&id=<?php echo $reg['kid_empresa'] ?><?php echo $pager ?>" onclick="return confirmar();" title="Borrar"><img src="img/ico/delete.png" /></a></td>
				<td><a href="?<?php echo $page; ?>&admin&id=<?php echo $reg['kid_empresa'] ?><?php echo $pager ?>" title="Administrar"><img src="img/ico/nota.png" /></a></td>
			</tr>
			<?php
			$i++;
		}
	}
	?>
	</tbody>
</table>
<br />
<div class="divPaginador">
<?php echo isset($paginacion['HTML'])?$paginacion['HTML']:''; ?>
</div>
<?php
}//no imprimir no hay registros
echo "Fall� consulta de la informaci�n de la empresa";
	include("plantillaFoot.inc.php");
}
?>