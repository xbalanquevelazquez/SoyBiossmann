<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

$vars = $page;
$pager = isset($_GET['paginadespliegue'])?'&paginadespliegue='.$_GET['paginadespliegue']:'';

if(		$page2=='new'){ 	include_once("adm.$page.new.php");}
else if($page2=='edit'){ 	include_once("adm.$page.edit.php");}
else if($page2=='delete'){ 	include_once("adm.$page.delete.php");}
else if($page2=='fast'){ 	include_once("adm.$page.fast.php");}
else{

?>
<?php
	include("plantilla.inc.php");
	$first = true;
	$i=1;
	
	$query = $myAdmin->obtenerUsuarios('',1);
	#echo $query;
	#VARIABLES :: res&orderby=res_anio&filter_clas=3&filter_anio=2008&orderdir=ASC
	#primero envío la paginación, para obtener el límite

	$paginacion = $myAdmin->paginador->paginar($query,10,10,"$vars");
	#obtengo las resoluciones
	$arrRes = $myAdmin->obtenerUsuarios($paginacion['LIMIT'],0);
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
				<div class="text">Nuevo usuario</div>
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
		<th>Usuario</th>
		<th>Nombre</th>
		<th>Activo</th>
		<th>Administrador</th>
		<th>Perfil</th>
		<th colspan="2">Operaciones</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if(count($arrRes) < 1){
		?>
		<td colspan="8" align="center" class="aviso">No se encontraron datos</td>
		<?php
		}else{
			foreach($arrRes as $res){
			?>
			<tr<?php if($first){ echo ' class="first"'; $first=false; }else if($i%2){ echo ' class="z"'; } ?> >
				<td><?php echo "{$res['usr_id']}"; ?></td>
				<td><?php echo "{$res['usr_login']}"; ?></td>
				<td><?php echo "{$res['usr_nombre']}"; ?></td>
				<?php if($res['bit'] == 0){ ?>
				<td><img src="img/ico/sem-vd.gif" alt="Este usuario no puede desactivarse" /></td>
				<?php }else{ ?>
				<td><a href="?<?php echo $page; ?>&fast=<?php echo $res['usr_activo']==1?'invisible':'visible'; ?>&id=<?php echo $res['usr_id'] ?><?php echo $pager ?>" title="<?php echo $res['usr_activo']==1?'Desactivar':'Activar'; ?>"><img src="img/ico/sem-<?php echo $res['usr_activo']==1?'vd':'gr'; ?>.gif" /></a></td>
				<?php } ?>
				<td><?php if($res['bit'] == 0){ ?><img src="img/ico/sem-vd.gif" /><?php }else{ ?><img src="img/ico/sem-gr.gif" /><?php } ?></td>
				<td><?php echo $res['perfil'] ?></td>
				<td><a href="?<?php echo $page; ?>&edit&id=<?php echo $res['usr_id'] ?><?php echo $pager ?>"><img src="img/ico/edit.png" /></a></td>
				<td><a href="?<?php echo $page; ?>&delete&id=<?php echo $res['usr_id'] ?><?php echo $pager ?>" onclick="return confirmar();"><img src="img/ico/delete.png" /></a></td>
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
/*}else{//no es admin
	header("Location:".$_SERVER['PHP_SELF']."?res");
}*/
?>