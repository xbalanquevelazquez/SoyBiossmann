<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<div class="response"></div>
<script type="text/javascript" src="../js/jquery-3.0.0.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		busquedaResultados();
	});
function busquedaResultados(){
	$(".response").html('Obteniendo datos...');
	var myFormData = new FormData();
	myFormData.append('action','getListado');
	
	<?php 	
	$password = "2019#$&CA%SA?¿PLA!RRE.-*".date("Y-m-d")."'HG2019CASAPLARRE.";
	$token = sha1(md5($password));
 	?>

	myFormData.append('token','<?php echo $token; ?>');//8478348459d717c9958fe4025d5426fcb59bdce7
	myFormData.append("prt",'salvador');
	$.ajax({
		url: "http://www.casaplarre.com/CASA/SATI/wsdirrep/",
		type:"POST",
		processData: false,//tanto processData como contentType deben estar en false para que funcione FormData
		contentType: false,
		data:myFormData,
		cache:false,
		dataType:"json",
		success: function(response){
			console.log(response);
			if(response.status == 'OK'){
				$(".response").html(response.registros);
			}else{
				$(".response").html(response.mensaje);
			}

		}
	});
}
</script>

</body>
</html>