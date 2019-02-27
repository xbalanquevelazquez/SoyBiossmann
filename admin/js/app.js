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
	$('.makeIO').each(function(){
		makeIOControl($(this));
	});
	$('.ioCheck').click(function(e){
		switchEstatusIO($(this));
	});
});
function makeIOControl(obj){
	var isChecked = obj.is(':checked');
	var originID = obj.attr('id');
	var currentEstatus = 'ioOFF';
	var currentValue = 0;
	if(isChecked){
		currentEstatus = 'ioON';
		currentValue = 1;
	}
	var controlIO = '<div class="ioCheck '+currentEstatus+'" estatus="'+currentValue+'" reference="'+originID+'"><div><span>&nbsp;</span></div><span class="onLabel">ON</span><span>OFF</span></div>';
	obj.after(controlIO);
	obj.css({display:'none'});
}
function switchEstatusIO(obj){
	var placa = obj.find('div');
	var reference = $('#'+obj.attr('reference'));
	var anchoElemento = obj.width();
	var anchoPlaca = placa.width();
	obj.toggleClass('ioOFF').toggleClass('ioON');
	if(obj.attr('estatus') == 0){//ESTA APAGADO -> ENCENDER
		obj.attr('estatus',1);
		reference.prop('checked', true);
	}else{//ESTA ENCENDIDO -> APAGAR
		obj.attr('estatus',0);
		reference.prop('checked', false);
	}
}
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