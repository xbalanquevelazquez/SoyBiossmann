<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
$pagerParent = isset($_GET['parentpaginadespliegue'])?'&parentpaginadespliegue='.$_GET['parentpaginadespliegue']:'';
$vars = "$page&$page2&id=".$_GET['id'].$pagerParent;

if(isset($_GET['delete'])){ 	include_once("adm.$page.$page2.delete.php");}
else if(isset($_GET['fast'])){ 		include_once("adm.$page.$page2.fast.php");}
else{
	include("plantilla.inc.php");
	$first = true;
	$i=1;
	
	$tema = reset($myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM foro_tema WHERE kid_tema=".$_GET['id'])));

	
	$queryT = "SELECT COUNT(*) as total FROM foro_respuesta WHERE fid_tema=".$_GET['id'];
	$total = $myAdmin->conexion->fetch($myAdmin->conexion->query("$queryT"));
	/*
	kid_tema INT NOT NULL AUTO_INCREMENT,
	titulo VARCHAR(255) NOT NULL,
	texto LONGTEXT NULL,
	autor VARCHAR(255) NULL,
	fecha DATETIME NOT NULL,
	visible INT NOT NULL DEFAULT 1,
	*/
	
	if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);
?>
<script type="text/javascript">
	function confirmar(){
		<?php if(BORRADO){ ?>
		var textoMensaje = 'Esta acción borrará el archivo asociado y la información de la resolución, ¿desea continuar?';
		<?php }else{ ?>
		var textoMensaje = 'Esta acción borrará la información de la resolución, ¿desea continuar?';
		<?php } ?>
		if(confirm(textoMensaje)){
			return true;
		}else{
			return false;
		}
	}
</script>
<div>
<table width="90%" align="center">
	<tr>
		<td><h1><?php echo $tema['titulo'] ?></h1></td>
	</tr>
	<tr>
		<td><h2><?php echo $tema['autor'] ?></h2></td>
	</tr>
	<tr>
		<td><h3><?php echo $tema['fecha'] ?></h3></td>
	</tr>
</table>
<?php #print_r($tema); ?>
</div>
<table class="datos" cellspacing="0">
	<thead>
	<tr>
		<th>Id</th>
		<th>T&iacute;tulo</th>
		<th>Autor</th>
		<th>Fecha</th>
		<th>visible</th>
		<th colspan="3">Operaciones</th>
	</tr>
	</thead>
	<tbody>
	<?php
	#echo $total[0]['total'];
	if($total[0]['total']<=0){
		?>
		<tr>
		<td colspan="8" align="center" class="aviso">No se encontraron datos</td>
		</tr>
	<?php
	}else{
	
	$paginacion = $myAdmin->paginador->paginar($queryT,10,10,"$vars");
	#print_r($paginacion);
	$query = $myAdmin->conexion->query("SELECT * FROM foro_respuesta WHERE fid_tema=".$_GET['id']." ORDER BY kid_respuesta DESC LIMIT ".$paginacion['LIMIT']);

	$arr = $myAdmin->conexion->fetch($query);
	if(count($arr) <= 0){
	}else{
	#print_r($arr);
	foreach($arr as $reg){
	#if(count($arrRes) < 1){
		?>
		<?php
		#}else{
			#foreach($arrRes as $res){
			?>
			<tr class="<?php  echo $reg['visible']==0?' inactivo':''?>" >
				<td><?php echo $reg['kid_respuesta']; ?></td>
				<td><?php echo $reg['autor']; ?></td>
				<td><?php echo str_replace("\\","",stripslashes(html_entity_decode($reg['texto']))); ?></td>
				<td><?php echo $reg['fecha']; ?></td>
				<td><a href="?<?php echo $page; ?>&<?php echo $page2; ?>&fast=<?php echo $reg['visible']==1?'invisible':'visible'; ?>&parent=<?php echo $_GET['id'] ?>&id=<?php echo $reg['kid_respuesta'] ?><?php echo $pager ?><?php echo $pagerParent; ?>" title="Hacer <?php echo $reg['visible']==1?'invisible':'visible'; ?>"><img src="img/ico/sem-<?php echo $reg['visible']==1?'vd':'gr'; ?>.gif" /></a></td>
				<td><a href="?<?php echo $page; ?>&<?php echo $page2; ?>&delete&id=<?php echo $reg['kid_respuesta'] ?>&parent=<?php echo $_GET['id'] ?><?php echo $pager ?><?php echo $pagerParent; ?>" onclick="return confirmar();" title="Borrar tema"><img src="img/ico/delete.png" /></a></td>
			</tr>
			<?php
			$i++;
		}
	}
	}
	?>
	</tbody>
</table>
<br />
<?php
	if($total[0]['total']>0) echo '<div class="divPaginador">'.$paginacion['HTML'].'</div>';
	include("plantillaFoot.inc.php");
}
?>