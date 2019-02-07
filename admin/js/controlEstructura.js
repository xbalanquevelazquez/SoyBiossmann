//mostrar opciones
function mostrarOpciones(obj){
	ocultarOpciones();
	var oSelect = document.getElementById('op'+obj);
	oSelect.style.display='block';
}
function mostrarNueva(obj){
	ocultarOpciones();
	var oSelect = document.getElementById('n'+obj);
	oSelect.style.display='block';
}

function ocultarOpciones(){
	for(var i = 1; i < totalLinks; i++){
		var obj = document.getElementById('op'+i);
		obj.style.display='none';
		var obj = document.getElementById('nw'+i);
		obj.style.display='none';
	}
}
function confirmarBorrar(){
	var wcnf = confirm("¿Está seguro de querer borrar la página?\nTodas las páginas contenidas en ésta se borrarán también, junto con los contenidos asociados.");
	if(wcnf){
		return true;
	}else{
		return false;
	}
}