<div id="header">
	<?php if($page=='login'){ ?>
	<div class="version"></div>
	<?php } ?>
	<div class="fleft">
	<?php if($page=='login'){ ?>
	<!--img src="img/segob.gif" width="146" height="49" /--></div>
	<?php }else{ ?>
	<!--img src="img/segob.gif" width="146" height="49" /--></div>
	<?php } ?>
	<?php if($myAdmin->comprobarSesion()){ ?>
	<div class="dataweb fright">
		<div class="inner">
		<div class="ini">
			<table class="layout" summary="layout">
				<tr>
					<td> &nbsp; &nbsp;<?php echo $myAdmin->obtenerUsr('nombre'); ?></td>
					<td width="35"></td>
					<td><a href="salir.php" class="btnSalir"></a></td>
				</tr>
			</table>
			<div class="fixed"></div>
		</div>
		</div>
	</div>
	<?php } ?>
	<div class="fixed"></div>
</div>
<div class="fixed"></div>
<!--div class="separador"></div-->