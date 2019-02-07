function enviaAJAX(url){
	//alert('sending... '+url);
	//tinyMCE.execCommand('mceToggleEditor',false,'res[lista]');

	var data = Array();
	data[data.length] = Array('res[titulo]','texto','"Título"');
	data[data.length] = Array('res[caracteristicas]','texto','"Características"');
	data[data.length] = Array('res[slogan]','texto','"Slogan"');
	if(validarFormulario(data)){
		//alert('validando...');
		//if(tinyMCE.execCommand('mceToggleEditor',false,'res[lista]')) alert('si');else alert('no');
		tinyMCE.execCommand('mceRemoveControl',false,'res[lista]');
		//alert('remiving... ');
		setTimeout("continuarAjax('"+url+"')",1000);
	}
	
}
function continuarAjax(url){
	//alert('procesando');
	/*document.write('procesando...');*/
		var id = document.getElementById('r[id]').value;
		var foto = document.getElementById('res[foto]');
		foto = foto.options[foto.selectedIndex].value;
		var titulo = document.getElementById('res[titulo]').value;
		var caracteristicas = document.getElementById('res[caracteristicas]').value;
		var slogan = document.getElementById('res[slogan]').value;
		var lista = document.getElementById('res[lista]').value;
		var grupo = document.getElementById('res[grupo]');
		grupo = grupo.options[grupo.selectedIndex].value;
		var liga = document.getElementById('res[link]').value;
		var posicion = document.getElementById('res[posicion]').value;
		var visible = document.getElementById('res[visible]');
		if(visible.checked) visible = '1'; else visible = '0';
		var tipo = document.getElementById('tipo').value;
		
		var vars='&r[id]='+id+"&res[foto]="+foto+"&res[grupo]="+grupo+"&res[link]="+liga+"&res[titulo]="+titulo+"&res[caracteristicas]="+caracteristicas+"&res[slogan]="+slogan+"&res[lista]="+lista+"&res[posicion]="+posicion+"&res[visible]="+visible;
		//alert(vars);
		ajax_getData(url+'?'+tipo+'&grupo='+grupo+'&',vars,'elemContenedor','POST');

}
function enviaGrupoAJAX(url){
	var data = Array();
	data[0] = Array('res[identificador]','texto','"Identificador"');
	data[1] = Array('res[titulo]','texto','"Titulo"');
	if(validarFormulario(data)){
		var id = document.getElementById('r[id]').value;
		
		var titulo = document.getElementById('res[titulo]').value;
		var identificador = document.getElementById('res[identificador]').value;
		var selector = document.getElementById('res[selector]');
		selector = selector.options[selector.selectedIndex].value;
		var visible = document.getElementById('res[visible]');
		if(visible.checked) visible = '1'; else visible = '0';
		var tipo = document.getElementById('tipo').value;
		
		var vars='&r[id]='+id+"&res[identificador]="+identificador+"&res[titulo]="+titulo+"&res[selector]="+selector+"&res[visible]="+visible;
		//alert(url);
		ajax_getData(url+'?'+tipo+'&',vars,'groupContenedor','POST');
		
	}
}

function makeIdentificador(text){
		alert('identificador: '+text);
		var  target = document.getElementById('res[identificador]');
		var nuevoValor = text.toLowerCase();//todo a minúsculas
			nuevoValor = nuevoValor.replace(/á/g, "a");//cambiar caracteres especiales (acentos y ñ)
			nuevoValor = nuevoValor.replace(/é/g, "e");
			nuevoValor = nuevoValor.replace(/í/g, "i");
			nuevoValor = nuevoValor.replace(/ó/g, "o");
			nuevoValor = nuevoValor.replace(/ú/g, "u");
			nuevoValor = nuevoValor.replace(/ñ/g, "ni");
			nuevoValor = nuevoValor.replace(/'/g, "");
			nuevoValor = nuevoValor.replace(/´/g, "");
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
	
function htmlentities (string, quote_style) {
    // Convert all applicable characters to HTML entities  
    // 
    // version: 909.322
    // discuss at: http://phpjs.org/functions/htmlentities    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: nobbler
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Ratheous
    // -    depends on: get_html_translation_table
    // *     example 1: htmlentities('Kevin & van Zonneveld');    // *     returns 1: 'Kevin &amp; van Zonneveld'
    // *     example 2: htmlentities("foo'bar","ENT_QUOTES");
    // *     returns 2: 'foo&#039;bar'
    var hash_map = {}, symbol = '', tmp_str = '', entity = '';
    tmp_str = string.toString();    
    if (false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))) {
        return false;
    }
    hash_map["'"] = '&#039;';    for (symbol in hash_map) {
        entity = hash_map[symbol];
        tmp_str = tmp_str.split(symbol).join(entity);
    }
        return tmp_str;
}