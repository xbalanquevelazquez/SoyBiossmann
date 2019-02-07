<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
include_once(LIB_PATH."dirImages.inc.php");

$vars = $page;
$pager = isset($_GET['paginadespliegue'])?'&paginadespliegue='.$_GET['paginadespliegue']:'';

if(		isset($_REQUEST['new'])){ 		include_once("adm.$page.new.php");}
else if(isset($_REQUEST['edit'])){ 		include_once("adm.$page.edit.php");}
else if(isset($_REQUEST['delete'])){ 	include_once("adm.$page.delete.php");}
else if(isset($_REQUEST['fast'])){ 		include_once("adm.$page.fast.php");}
else{
	
	include("plantilla.inc.php");

$grupoSel = 1;

	$first = true;
	$i=1;

	$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."flashban WHERE grupo=$grupoSel";
	$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
	if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
	
	if($continuar){
		#$paginacion = $myAdmin->paginador->paginar($queryT,10,10,"$vars");
		
		$query = $myAdmin->conexion->query("SELECT * FROM ".PREFIJO."flashban WHERE grupo=$grupoSel ORDER BY visible DESC, posicion ASC ");#LIMIT ".$paginacion['LIMIT']
	}
	
	
	if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);
	

?>
<script type="text/javascript" charset="iso-8859-1">
	function confirmar(){
		var textoMensaje = 'Esta acci\u00F3n borrar\u00E1 la informaci\u00F3n, \u00BFdesea continuar?';
		if(confirm(textoMensaje)){
			return true;
		}else{
			return false;
		}
	}
</script>

<div class="divPaginador">
<?php #echo $paginacion['HTML']; ?>
</div>
<table class="layout" width="90%" align="center">
	<tr>
		<td>
		<div class="btnContainer fright">
		<a href="?<?php echo $page; ?>&new<?php echo $pager ?>" class="btn">
		<div class="inner">
			<div class="crnl">
			<div class="crnr">
				<img src="<?php echo WEB_PATH; ?>admin/img/ico/new.png" />
				<div class="text">Nuevo registro en este grupo</div>
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
		<th>Id</th>
		<th>Imagen c/link</th>
		<th>T&iacute;tulo</th>
		<th>Posici&oacute;n</th>
		<th>Visible</th>
		<th colspan="2" width="80">Operaciones</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if(!$continuar){
		?>
		<tr>
		<td colspan="7" align="center" class="aviso">No se encontraron datos</td>
		</tr>
	<?php
	}else{
	$arr = $myAdmin->conexion->fetch($query);
	#print_r($arr);
	foreach($arr as $reg){
	#if(count($arrRes) < 1){
		?>
		<?php
		#}else{
			#foreach($arrRes as $res){
			?>
			<tr class="<?php  echo $reg['visible']==0?' inactivo':''?>">
				<td><?php echo $reg['kid_banner']; ?></td>
				<td><?php if($reg['link']!=''){ ?><a href="<?php echo $reg['link']; ?>" title="<?php echo $reg['titulo']; ?>" target="_blank"><?php } ?><?php if($reg['foto']!='') {?><img src="<?php echo WEB_PATH."webimgs/flash/".$reg['foto']; ?>" alt="<?php echo $reg['titulo']; ?>" style="max-width:150px" /><?php } ?><?php if($reg['link']!=''){ ?></a><?php } ?></td>
				<td><?php echo $reg['titulo']; ?></td>
				<td><?php echo $reg['posicion']; ?></td>
				<td><a href="?<?php echo $page; ?>&fast=<?php echo $reg['visible']==1?'invisible':'visible'; ?>&id=<?php echo $reg['kid_banner'] ?><?php echo $pager ?>" title="Hacer <?php echo $reg['visible']==1?'invisible':'visible'; ?>"><img src="img/ico/sem-<?php echo $reg['visible']==1?'vd':'gr'; ?>.gif" /></a></td>
				<td><a href="?<?php echo $page; ?>&edit&id=<?php echo $reg['kid_banner'] ?><?php echo $pager ?>" title="Editar tema"><img src="img/ico/edit.png" /></a></td>
				<td><a href="?<?php echo $page; ?>&delete&id=<?php echo $reg['kid_banner'] ?><?php echo $pager ?>" onclick="return confirmar();" title="Borrar tema"><img src="img/ico/delete.png" /></a></td>
			</tr>
			<?php
			$i++;
	}
	?>
	</tbody>
</table>
<br />
<div class="divPaginador">
<?php #echo $paginacion['HTML']; ?>
</div>
<?php
}//no imprimir no hay registros
	include("plantillaFoot.inc.php");
}//end if continueGrupo
?>
