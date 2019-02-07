<?php
include("plantilla.inc.php");
$msg = '';
?>
<div class="loginBackL">
	<div class="loginBackR">
		<form method="POST">
			<table class="form">
				<tr>
					<td colspan="6"><?php echo $msg; ?></td>
				</tr>
				<tr>
					<td><label for="cmp[usrlogin]">Usuario</label></td>
					<td><div class="loginInput"><input type="text" name="cmp[usrlogin]" maxlength="15" /></div></td>
					<td><label for="cmp[pswlogin]">Contrase&ntilde;a</label></td>
					<td><div class="loginInput"><input type="password" name="cmp[pswlogin]" maxlength="15" /></div></td>
					<td colspan="2"><input type="image" src="img/btn/login.gif" value="Ingresar" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<?php include("plantillaFoot.inc.php"); ?>