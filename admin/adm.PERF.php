<?php 
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
$pager = (isset($data2) && $data2 == 'pag')?'/pag/'.$data3:'';

if(		$data2=='new'){ 	include_once("adm.$data1.new.php");}
else if($data2=='edit'){ 	include_once("adm.$data1.edit.php");}
else if($data2=='delete'){ 	include_once("adm.$data1.delete.php");}
else if($data2=='fast'){ 	include_once("adm.$data1.fast.php");}
else{
	$i=1;
	
	$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."perfil";
	$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
	if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
	
	if($continuar){
		$paginacion = $myAdmin->paginador->paginar($queryT,10,10,"$data1");
		$query = $myAdmin->conexion->query("SELECT * FROM ".PREFIJO."perfil ORDER BY kid_perfil ASC LIMIT ".$paginacion['LIMIT']);
	}
	if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);
?>
<script type="text/javascript">
	function confirmar(){
		var textoMensaje = 'Esta acción borrará el perfil, ¿desea continuar?';
		if(confirm(textoMensaje)){
			return true;
		}else{
			return false;
		}
	}
</script>
<div class="paginador">
<?php echo $paginacion['HTML']; ?>
</div>
<a href="<?php echo CURRENT_SECCION ?>/new<?php echo $pager ?>" class="btn btn-primary"><i class="fa fa-plus"></i> &nbsp;Nuevo perfil</a>
<div class="fixed espaciador"></div>
<table class="table table-bordered table-striped table-hover">
	<thead class="bg-secondary text-white">
	<tr>
		<th>#</th>
		<th>Nombre</th>
		<th>Sección inicial</th>
		<th>Accesos</th>
		<th colspan="2">Operaciones</th>
	</tr>
	</thead>
	<tbody class="">
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
			$perfil_actual = $res['kid_perfil'];
			$queryP = "SELECT (SELECT acronimo_accion FROM ".PREFIJO."acciones WHERE fid_accion=kid_accion) as acronimo FROM ".PREFIJO."permisos WHERE fid_perfil='$perfil_actual'";
			$resPermisos =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryP));
			$permisos = '';
			for($p=0;$p < count($resPermisos);$p++){
				if($p != 0) $permisos .= ' | ';
				$permisos .= $resPermisos[$p]['acronimo'];
			}
			?>
			<tr>
				<td><?php echo $res['kid_perfil']; ?></td>
				<td><?php echo $res['nombre_perfil']; ?></td>
				<td><?php echo $res['seccion_inicial']; ?></td>
				<td><?php echo $permisos ?></td>
				<td><a href="<?php echo ADMIN_URL.$data1 ?>/edit/<?php echo $res['kid_perfil'] ?><?php echo $pager ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a></td>
				<td><a href="<?php echo ADMIN_URL.$data1 ?>/delete/<?php echo $res['kid_perfil'] ?><?php echo $pager ?>" onclick="return confirmar();" class="btn btn-danger"><i class="fa fa-trash-alt"></i></a></td>
			</tr>
			<?php
			$i++;
		}
	}
	?>
	</tbody>
</table>
<div class="fixed espaciador"></div>
<div class="paginador">
<?php echo $paginacion['HTML']; ?>
</div>
<?php

}
?>