<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

$vars = $page;
$pager = isset($_GET['paginadespliegue'])?'&paginadespliegue='.$_GET['paginadespliegue']:'';

if(		$page2=='new'){ 	include_once("adm.$page.new.php");}
else if($page2=='edit'){ 	include_once("adm.$page.edit.php");}
else if($page2=='delete'){ 	include_once("adm.$page.delete.php");}
else{

	include("plantilla.inc.php");
	$first = true;
	$i=1;
	
	$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."perfil";
	$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
	if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
	
	if($continuar){
		$paginacion = $myAdmin->paginador->paginar($queryT,10,10,"$vars");
		$query = $myAdmin->conexion->query("SELECT * FROM ".PREFIJO."perfil ORDER BY kid_perfil ASC LIMIT ".$paginacion['LIMIT']);
	}
	if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);
?>
<script type="text/javascript">
	function confirmar(){
		var textoMensaje = 'Esta acción borrará el usuario, ¿desea continuar?';
		if(confirm(textoMensaje)){
			return true;
		}else{
			return false;
		}
	}
</script>
<div class="divPaginador">
<?php echo $paginacion['HTML']; ?>
</div>
<table class="layout" width="90%" align="center">
	<tr>
		<td>
		<div class="btnContainer fright">
		<a href="?<?php echo $page ?>&new<?php echo $pager ?>" class="btn">
		<div class="inner">
			<div class="crnl">
			<div class="crnr">
				<img src="img/ico/new.png" />
				<div class="text">Nuevo perfil </div>
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
<table class="datos" cellspacing="0">
	<thead>
	<tr>
		<th>#</th>
		<th>Nombre</th>
		<th>Accesos</th>
		<th colspan="2">Operaciones</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$arr = $myAdmin->conexion->fetch($query);
	if(count($arr) <= 0){
		?>
		<tr>
		<td colspan="5" align="center" class="aviso">No se encontraron datos</td>
		</tr>
	<?php
	}else{
		foreach($arr as $res){
			?>
			<tr<?php if($first){ echo ' class="first"'; $first=false; }else if($i%2){ echo ' class="z"'; } ?> >
				<td><?php echo "{$res['kid_perfil']}"; ?></td>
				<td><?php echo "{$res['nombre']}"; ?></td>
				<td><?php /*echo $res['bit_acceso']."  ";print_r(obtenerPermisos($res['bit_acceso']));*/ ?><?php echo mostrarAccesosGraf(obtenerPermisos($res['bit_acceso'])); ?></td>
				<td><a href="?<?php echo $page ?>&edit&id=<?php echo $res['kid_perfil'] ?><?php echo $pager ?>"><img src="img/ico/edit.png" /></a></td>
				<td><a href="?<?php echo $page ?>&delete&id=<?php echo $res['kid_perfil'] ?><?php echo $pager ?>" onclick="return confirmar();"><img src="img/ico/delete.png" /></a></td>
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
<?php echo $paginacion['HTML']; ?>
</div>
<?php
	include("plantillaFoot.inc.php");
}
?>