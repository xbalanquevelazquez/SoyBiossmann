<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
$vars = $page;
$pager = isset($_GET['paginadespliegue'])?'&paginadespliegue='.$_GET['paginadespliegue']:'';
if(		isset($_GET['new'])){ 		include_once("adm.$page.new.php");}
else if(isset($_GET['edit'])){ 		include_once("adm.$page.edit.php");}
else if(isset($_GET['delete'])){ 	include_once("adm.$page.delete.php");}
else if(isset($_GET['fast'])){ 		include_once("adm.$page.fast.php");}
else{
	include("plantilla.inc.php");
	$first = true;
	$i=1;

	$queryT = "SELECT COUNT(*) as total FROM como_dice";
	
	$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
	
	if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
	
	if($continuar){
		$paginacion = $myAdmin->paginador->paginar($queryT,10,10,"$vars");
		
		$query = $myAdmin->conexion->query("SELECT * FROM como_dice ORDER BY fecha DESC LIMIT ".$paginacion['LIMIT']);
	}
	
	/*
	kid_dice INT AUTO_INCREMENT,
	texto VARCHAR(255) NULL,
	autor VARCHAR(50) NULL,
	link VARCHAR(255) NULL,
	fecha DATETIME NOT NULL,
	visible INT NOT NULL DEFAULT 1,
	*/
	
	if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);
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
<div class="divPaginador">
<?php echo $paginacion['HTML']; ?>
</div>
<table class="layout" width="90%" align="center">
	<tr>
		<td>
		<div class="btnContainer fright">
		<a href="?<?php echo $page; ?>&new<?php echo $pager; ?>" class="btn">
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
<?php if($continuar){?>
<table class="datos" cellspacing="0">
	<thead>
	<tr>
		<th>Id</th>
		<th>Autor</th>
		<th width="450">Texto</th>
		<th width="60">Link</th>
		<th>Fecha</th>
		<th>visible</th>
		<th colspan="2" width="80">Operaciones</th>
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
	#if(count($arrRes) < 1){
		?>
		<?php
		#}else{
			#foreach($arrRes as $res){
			?>
			<tr class="<?php  echo $reg['visible']==0?' inactivo':''?>">
				<td><?php echo $reg['kid_dice']; ?></td>
				<td><?php echo $reg['autor']; ?></td>
				<td><?php echo html_entity_decode($reg['texto']); ?></td>
				<td><?php if($reg['link']!=''){ ?><a href="<?php echo $reg['link']; ?>" title="<?php echo $reg['link']; ?>" target="_blank"><img src="img/ico/link.png" /></a><?php } ?></td>
				<td><?php echo $reg['fecha']; ?></td>
				<td><a href="?<?php echo $page; ?>&fast=<?php echo $reg['visible']==1?'invisible':'visible'; ?>&id=<?php echo $reg['kid_dice'] ?><?php echo $pager; ?>" title="Hacer <?php echo $reg['visible']==1?'invisible':'visible'; ?>"><img src="img/ico/sem-<?php echo $reg['visible']==1?'vd':'gr'; ?>.gif" /></a></td>
				<td><a href="?<?php echo $page; ?>&edit&id=<?php echo $reg['kid_dice'] ?><?php echo $pager; ?>" title="Editar tema"><img src="img/ico/edit.png" /></a></td>
				<td><a href="?<?php echo $page; ?>&delete&id=<?php echo $reg['kid_dice'] ?><?php echo $pager; ?>" onclick="return confirmar();" title="Borrar tema"><img src="img/ico/delete.png" /></a></td>
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
}//no imprimir no hay registros
	include("plantillaFoot.inc.php");
}
?>