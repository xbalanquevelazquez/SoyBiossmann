<?php 
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
$pager = (isset($data2) && $data2 == 'pag')?'/pag/'.$data3:'';

if(		$data2=='new'){ 	include_once("adm.$data1.new.php");}
else if($data2=='edit'){ 	include_once("adm.$data1.edit.php");}
else if($data2=='delete'){ 	include_once("adm.$data1.delete.php");}
else if($data2=='fast'){ 	include_once("adm.$data1.fast.php");}
else{
	$i=1;
	
	$query = $myAdmin->obtenerUsuarios('',1);
	$paginacion = $myAdmin->paginador->paginar($query,10,10,"$data1");
	$arrRes = $myAdmin->obtenerUsuarios($paginacion['LIMIT'],0);
	if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);

	
	$findme   = 'msgErr';
	$errorType = '';
	if(strpos($data2, $findme) === false){
		if(strpos($data4, $findme) === false){
		}else{
			$errorType = $data4;
		}
	}else{
		$errorType = $data2;
	} 

	if($errorType != ''){
		switch ($errorType) {
			case 'msgErrPass':
				echo "<div class='bg-warning'>la contrase&ntilde;a no es igual en los dos campos, por lo cual no se actualizó</div>";
				break;

			case 'msgErrUpdatePass':
				echo "<div class='bg-warning'>Error al actualizar la contraseña</div>";
				break;
			
			default:
				# code...
				break;
		}
	}

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
<div class="paginador">
<?php echo $paginacion['HTML']; ?>
</div>
<a href="<?php echo APP_URL.$data1 ?>/new<?php echo $pager ?>" class="btn btn-primary"><i class="fa fa-user-plus"></i> &nbsp;Nuevo usuario</a>
<div class="fixed espaciador"></div>
<table class="table table-bordered table-striped table-hover">
	<thead class="bg-secondary text-white">
	<tr>
		<th>#</th>
		<th>Usuario</th>
		<th>Nombre</th>
		<th>Correo</th>
		<th>Activo</th>
		<th>Administrador</th>
		<th>Perfil</th>
		<th colspan="2">Operaciones</th>
	</tr>
	</thead>
	<tbody class="">
	<?php
	if(count($arrRes) < 1){
		?>
		<td colspan="8" align="center" class="bg-warning">No se encontraron datos</td>
		<?php
		}else{
			foreach($arrRes as $res){
			?>
			<tr>
				<td><?php echo "{$res['kid_usr']}"; ?></td>
				<td><?php echo "{$res['usr_login']}"; ?></td>
				<td><?php echo "{$res['usr_nombre']}"; ?></td>
				<td><?php echo "{$res['usr_correo']}"; ?></td>
				<?php if($res['fid_perfil'] == 1){ ?>
				<td><span title="Este usuario no puede desactivarse"><i class="fa fa-toggle-on text-success"></i></span></td>
				<?php }else{ ?>
				<td><a href="<?php echo APP_URL.$data1 ?>/fast/<?php echo $res['usr_activo']==1?'invisible':'visible'; ?>/<?php echo $res['kid_usr'] ?><?php echo $pager ?>" title="<?php echo $res['usr_activo']==1?'Desactivar':'Activar'; ?>"><?php if($res['usr_activo'] == 1){ ?><i class="fa fa-toggle-on text-success"></i><?php }else{ ?><i class="fa fa-toggle-off text-muted"></i><?php } ?></a></td>
				<?php } ?>
				<td><?php if($res['fid_perfil'] == 1){ ?><i class="fa fa-toggle-on text-success"></i><?php }else{ ?><i class="fa fa-toggle-off text-muted"></i><?php } ?></td>
				<td><?php echo $res['perfil'] ?></td>
				<td><a href="<?php echo APP_URL.$data1 ?>/edit/<?php echo $res['kid_usr'] ?><?php echo $pager ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a></td>
				<td><?php if($res['fid_perfil'] != 1){ ?><a href="<?php echo APP_URL.$data1 ?>/delete/<?php echo $res['kid_usr'] ?><?php echo $pager ?>" onclick="return confirmar();" class="btn btn-danger"><i class="fa fa-trash-alt"></i></a><?php } ?></td>
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