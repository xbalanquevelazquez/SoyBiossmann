function makeIdentificador(text){
		//alert('identificador: '+text);
		//var  target = document.getElementById('res[identificador]');
		var nuevoValor = text.toLowerCase();//todo a min�sculas
			nuevoValor = nuevoValor.replace(/�/g, "a");//cambiar caracteres especiales (acentos y �)
			nuevoValor = nuevoValor.replace(/�/g, "e");
			nuevoValor = nuevoValor.replace(/�/g, "i");
			nuevoValor = nuevoValor.replace(/�/g, "o");
			nuevoValor = nuevoValor.replace(/�/g, "u");
			nuevoValor = nuevoValor.replace(/�/g, "ni");
			nuevoValor = nuevoValor.replace(/'/g, "");
			nuevoValor = nuevoValor.replace(/�/g, "");
			nuevoValor = nuevoValor.replace(/\s/g, "-");//cambiar espacios por guiones
			nuevoValor = nuevoValor.replace(/\u00e1/g, "a");
			nuevoValor = nuevoValor.replace(/\u00e9/g, "e");
			nuevoValor = nuevoValor.replace(/\u00ed/g, "i");
			nuevoValor = nuevoValor.replace(/\u00f3/g, "o");
			nuevoValor = nuevoValor.replace(/\u00fa/g, "u");
			nuevoValor = nuevoValor.replace(/\u00f1/g, "ni");
			//indiceObjeto.value=nuevoValor;
		return nuevoValor;
		
}
function protectLink(text){
		var nuevoValor = text;//
		nuevoValor = nuevoValor.replace(/&/g, "-amp-");
		return nuevoValor;
}
function goTop(){
	$(document).scrollTop($('#anchorTop').scrollTop());
}