$(document).ready(function(){
	console.log('¡App lista!');
	$("input, textarea, button").focus(function(){
		$(this).addClass("glow");
	});
	$("input, textarea, button").blur(function(){
		$(this).removeClass("glow");
	});
	$('.btnSalir').click(function(){
		document.location.href = siteURL+'SALIR';
	});
});
function makeIdentificador(text){
		//alert('identificador: '+text);
		//var  target = document.getElementById('res[identificador]');
		var nuevoValor = text.toLowerCase();//todo a minúsculas
			nuevoValor = nuevoValor.replace(/á/g, "a");//cambiar caracteres especiales (acentos y ñ)
			nuevoValor = nuevoValor.replace(/é/g, "e");
			nuevoValor = nuevoValor.replace(/í/g, "i");
			nuevoValor = nuevoValor.replace(/ó/g, "o");
			nuevoValor = nuevoValor.replace(/ú/g, "u");
			nuevoValor = nuevoValor.replace(/ñ/g, "n");
			nuevoValor = nuevoValor.replace(/'/g, "");
			nuevoValor = nuevoValor.replace(/´/g, "");
			nuevoValor = nuevoValor.replace(/\s/g, "-");//cambiar espacios por guiones
			nuevoValor = nuevoValor.replace(/\u00e1/g, "a");
			nuevoValor = nuevoValor.replace(/\u00e9/g, "e");
			nuevoValor = nuevoValor.replace(/\u00ed/g, "i");
			nuevoValor = nuevoValor.replace(/\u00f3/g, "o");
			nuevoValor = nuevoValor.replace(/\u00fa/g, "u");
			nuevoValor = nuevoValor.replace(/\u00f1/g, "n");
			//indiceObjeto.value=nuevoValor;
		return nuevoValor;	
}
$.fn.Algo = function(){
	alert('algo');
};