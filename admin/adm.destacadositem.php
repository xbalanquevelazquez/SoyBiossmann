<?php
define('VIEWABLE',TRUE);
$page= 'destacadositem';
include_once("cnf/configuracion.cnf.php");
include_once(CLASS_PATH."admin.class.php");
include_once(LIB_PATH."dirImages.inc.php");

$pager = isset($_GET['paginadespliegue'])?'&paginadespliegue='.$_GET['paginadespliegue']:'';
/*echo 'GET:: ';
print_r($_GET);
echo 'POST:: ';
print_r($_POST);*/
?>

<?php
if(		isset($_REQUEST['new'])){ 		include_once("adm.$page.new.php");}
else if(isset($_REQUEST['edit'])){ 		include_once("adm.$page.edit.php");}
else if(isset($_REQUEST['delete'])){ 	include_once("adm.$page.delete.php");}
else if(isset($_REQUEST['fast'])){ 		include_once("adm.$page.fast.php");}





$queryGroups = "SELECT COUNT(*) as total FROM ".PREFIJO."grupo_destacados";
$numGroups =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryGroups));
if($numGroups[0]['total'] == 0) $continuarGrupo = false; else $continuarGrupo = true;

if(!$continuarGrupo){
?>
No hay grupos de productos destacados para mostrar.
<?php
}else{
//obtengo grupos
$queryGroup = "SELECT * FROM ".PREFIJO."grupo_destacados";
$groups =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryGroup));


$grupoSel = isset($_GET['grupo'])?$_GET['grupo']:$groups[0]['kid_grupo'];

#echo $grupoSel;
echo "&nbsp;";
?>
<h2>Grupo de links:</h2>
<select onchange="ajax_getData('<?php echo WEB_PATH; ?>admin/adm.destacadositem.php','&grupo='+this.options[selectedIndex].value,'elemContenedor','GET');">
<?php foreach($groups as $grupo){ ?>
<option value="<?php echo $grupo['kid_grupo']; ?>" <?php echo $grupoSel==$grupo['kid_grupo']?'selected="selected"':'' ?>><?php echo ($grupo['titulo']); ?></option>
<?php } ?>
</select>
<hr />
<?php


	$first = true;
	$i=1;

	$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."destacados WHERE grupo=$grupoSel";
	$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
	if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
	
	if($continuar){
		#$paginacion = $myAdmin->paginador->paginar($queryT,10,10,"$vars");
		
		$query = $myAdmin->conexion->query("SELECT * FROM ".PREFIJO."destacados WHERE grupo=$grupoSel ORDER BY visible DESC, posicion ASC ");#LIMIT ".$paginacion['LIMIT']
	}
	
	
	if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);
	

?>

<div class="divPaginador">
<?php #echo $paginacion['HTML']; ?>
</div>
<table class="layout" width="90%" align="center">
	<tr>
		<td>
		<div class="btnContainer fright">
		<a style="cursor:pointer" onclick="ajax_getData('<?php echo WEB_PATH; ?>admin/adm.destacadositem.php','<?php echo $page; ?>&new&grupo=<?php echo $grupoSel; ?>','elemContenedor','GET');" class="btn">
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
		<th>Título</th>
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
				<td><?php echo $reg['kid_destacados']; ?></td>
				<td><?php if($reg['link']!=''){ ?><a href="<?php echo $reg['link']; ?>" title="<?php echo $reg['titulo']; ?>" target="_blank"><?php } ?><?php if($reg['img']!='') {?><img src="<?php echo WEB_PATH."webimgs/destacadas/".$reg['img']; ?>" width="145" alt="<?php echo $reg['alt']; ?>" /><?php } ?><?php if($reg['link']!=''){ ?></a><?php } ?></td>
				<td><?php echo $reg['titulo']; ?></td>
				<td><?php echo $reg['posicion']; ?></td>
				<td><a style="cursor:pointer" onclick="ajax_getData('<?php echo WEB_PATH; ?>admin/adm.destacadositem.php','<?php echo $page; ?>&fast=<?php echo $reg['visible']==1?'invisible':'visible'; ?>&grupo=<?php echo $grupoSel; ?>&id=<?php echo $reg['kid_destacados'] ?>','elemContenedor','GET');" title="Hacer <?php echo $reg['visible']==1?'invisible':'visible'; ?>"><img src="<?php echo WEB_PATH; ?>admin/img/ico/sem-<?php echo $reg['visible']==1?'vd':'gr'; ?>.gif" /></a></td>
				<td><a style="cursor:pointer" onclick="ajax_getData('<?php echo WEB_PATH; ?>admin/adm.destacadositem.php','<?php echo $page; ?>&edit&grupo=<?php echo $grupoSel; ?>&id=<?php echo $reg['kid_destacados'] ?>','elemContenedor','GET');" title="Editar tema"><img src="<?php echo WEB_PATH; ?>admin/img/ico/edit.png" /></a></td>
				<td><a style="cursor:pointer" id="&delete&grupo=<?php echo $grupoSel; ?>&id=<?php echo $reg['kid_destacados'] ?>" onclick="return confirmar(this.id);" title="Borrar tema"><img src="<?php echo WEB_PATH; ?>admin/img/ico/delete.png" /></a></td>
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


}//end if continueGrupo
?>