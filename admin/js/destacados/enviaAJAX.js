function enviaAJAX(url){
	var data = Array();
	data[data.length] = Array('res[titulo]','texto','"Título"');
	if(validarFormulario(data)){
		var id = document.getElementById('r[id]').value;
		var img = document.getElementById('res[img]');
		img = img.options[img.selectedIndex].value;
		var titulo = document.getElementById('res[titulo]').value;
		var grupo = document.getElementById('res[grupo]');
		grupo = grupo.options[grupo.selectedIndex].value;
		var liga = document.getElementById('res[link]').value;
		var posicion = document.getElementById('res[posicion]').value;
		var visible = document.getElementById('res[visible]');
		if(visible.checked) visible = '1'; else visible = '0';
		var tipo = document.getElementById('tipo').value;
		
		var vars='&r[id]='+id+"&res[img]="+img+"&res[grupo]="+grupo+"&res[link]="+liga+"&res[titulo]="+titulo+"&res[posicion]="+posicion+"&res[visible]="+visible;
		//alert(url);
		ajax_getData(url+'?'+tipo+'&grupo='+grupo+'&',vars,'elemContenedor','POST');
	}
}

function enviaGrupoAJAX(url){
	var data = Array();
	//data[data.length] = Array('res[identificador]','texto','"Identificador"');
	data[data.length] = Array('res[titulo]','texto','"Titulo"');
	if(validarFormulario(data)){
		var id = document.getElementById('r[id]').value;
		
		var titulo = document.getElementById('res[titulo]').value;
		//var identificador = document.getElementById('res[identificador]').value;
		//var selector = document.getElementById('res[selector]');
		//selector = selector.options[selector.selectedIndex].value;
		var visible = document.getElementById('res[visible]');
		if(visible.checked) visible = '1'; else visible = '0';
		var cantidad = document.getElementById('res[cantidad]').value;
		var tipo = document.getElementById('tipo').value;
		
		var vars='&r[id]='+id+"&res[titulo]="+titulo+"&res[cantidad]="+cantidad+"&res[visible]="+visible;
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