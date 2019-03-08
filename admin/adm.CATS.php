<?php 
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
$pager = (isset($data2) && $data2 == 'pag')?'/pag/'.$data3:'';

if(		$data2=='EDOS'){ 	include_once("adm.$data1.$data2.php");}
else if($data2=='PROPS'){ 	include_once("adm.$data1.$data2.php");}
else if($data2=='PROVEELOG'){ 	include_once("adm.$data1.$data2.php");}
else{
	
?>
<nav class="navbar internalNav">
	<ul class="navbar-nav">
		<li class="nav-item"><button class="btn bg-primary text-white" id="btnEDOS">ENTIDADES FEDERATIVAS Y MUNICIPIOS</button></li>
		<li><button class="btn bg-primary text-white" id="btnPROPS">PROPOSITOS Y RESULTADOS</button></li>
		<li><button class="btn bg-primary text-white" id="btnPROVEELOG">PROVEEDORES LOGÍSTICOS</button></li>
	</ul>
</nav>
<script type="text/javascript">
	$(document).ready(function(){
		var webpage = '<?php echo ADMIN_URL; ?>';
		$('#btnEDOS').click(function(){
			document.location.href=webpage+'CATS/EDOS/';
		});
		$('#btnPROPS').click(function(){
			document.location.href=webpage+'CATS/PROPS/';
		});
		$('#btnPROVEELOG').click(function(){
			document.location.href=webpage+'CATS/PROVEELOG/';
		});
	});
</script>
<?php
}
?>