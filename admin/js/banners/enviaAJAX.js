function activarLinks(){
	///////////////////////////////////////////
	////////////////ALTA/////////////////////
	///////////////////////////////////////////
	$("#btnAltaBanner").click(function(e){
		e.preventDefault();

		var id 		= $('#id').val();
		var img		= $('#img')[0].files[0];//.val();
		var titulo	= $('#titulo').val();
		var alt		= $('#alt').val();
		var grupo	= $('#grupo').val();//grupo.options[grupo.selectedIndex].value;
		var link	= $('#link').val();
		var posicion= $('#posicion').val();
		var visible = 0;
		var color	= $('#color').val();
		if($('#visible').prop( "checked" )) visible = '1';
		//var visible	= $('#visible').val();//
		var tipo	= $('#tipo').val();
		
		if(img == undefined){
			alert("No haz seleccionado la imagen a subir");
		}else{
			if(img.type.toLowerCase().match(/image.*/) != null){//ES IMAGEN
				if(img.type.toLowerCase().match(/jpg/) == null && img.type.toLowerCase().match(/jpeg/) == null && img.type.toLowerCase().match(/png/) == null && img.type.toLowerCase().match(/gif/) == null){//NO ES DEL TIPO ADECUADO
					alert("La imagen no es del tipo adecuado, selecciona una imagen JPG, JPEG, PNG o GIF");
				}else{//SI ES DEL TIPO ADECUADO
					
					//alert('datos:id:'+id+'\nimg:'+img+'\nalt:'+alt+'\ngrupo:'+grupo+'\nlink:'+link+'\nposicion:'+posicion+'\nvisible:'+visible+'\ntipo:'+tipo);

					var envioData = new FormData();
					envioData.append(tipo,'');
					envioData.append("accion",	'insertar');
					envioData.append("id",		id);
					envioData.append("img",		img);
					envioData.append("titulo",		titulo);
					envioData.append("alt",		alt);
					envioData.append("grupo",	grupo);
					envioData.append("link",	link);
					envioData.append("posicion",posicion);
					envioData.append("visible",	visible);
					envioData.append("color",	color);

					$.ajax({
						url: "adm.banners.php",
						type:"POST",
						processData: false,//tanto processData como contentType deben estar en false para que funcione FormData
						contentType: false,
						cache:false,
						 //async: false,
						data:envioData,
						dataType:"json",
						success: function(respuesta){
							var texto = '';
							if(respuesta.success){
								//if(respuesta.data.mensaje=='Registro correcto'){
									
									ajax_getData('adm.banners.php','&grupo='+grupo,'elemContenedor','GET');
									$(".resultsBox").html(respuesta.data.mensaje);
								//}
								//texto = respuesta.data.mensaje + '<br/>' + respuesta.data.img;
								//walk simulator	window.location.href='encuesta/?id='+respuesta.data.qr+'-<?php echo ENCUESTA_ACTIVA ?>';
							}else{
								texto = respuesta.error;

							}
							console.log(texto);
							$(".resultsBox").html(texto);
						},
						error: function(respuesta) {
		                    console.log(respuesta);
		                    alert("Error: "+respuesta);
		                }
					});



				}
			}else{
				//NO ES IMAGEN
				alert("Debes seleccionar un archivo de imagen para subir");
			}
			
		}//END IF img

	});

	///////////////////////////////////////////
	////////////////EDITAR/////////////////////
	///////////////////////////////////////////
	$("#btnEditarBanner").click(function(e){
		e.preventDefault();

		var id 		= $('#id').val();
		var img		= $('#img')[0].files[0];//.val();
		if(img == undefined) img = 0;
		var titulo	= $('#titulo').val();
		var alt		= $('#alt').val();
		var grupo	= $('#grupo').val();//grupo.options[grupo.selectedIndex].value;
		var link	= $('#link').val();
		var posicion= $('#posicion').val();
		var visible = 0;
		if($('#visible').prop( "checked" )) visible = '1';
		//var visible	= $('#visible').val();//
		var tipo	= $('#tipo').val();
		var color	= $('#color').val();


		//if(img == undefined){
		//	alert("No haz seleccionado la imagen a subir");
		//}else{
			//if(img.type.toLowerCase().match(/image.*/) != null){//ES IMAGEN
				//if(img.type.toLowerCase().match(/jpg/) == null && img.type.toLowerCase().match(/jpeg/) == null && img.type.toLowerCase().match(/png/) == null && img.type.toLowerCase().match(/gif/) == null){//NO ES DEL TIPO ADECUADO
				//	alert("La imagen no es del tipo adecuado, selecciona una imagen JPG, JPEG, PNG o GIF");
				//}else{//SI ES DEL TIPO ADECUADO
					
					//alert('datos:id:'+id+'\nimg:'+img+'\nalt:'+alt+'\ngrupo:'+grupo+'\nlink:'+link+'\nposicion:'+posicion+'\nvisible:'+visible+'\ntipo:'+tipo);

					var envioData = new FormData();
					envioData.append(tipo,'');
					envioData.append("accion",	'editar');
					envioData.append("id",		id);
					envioData.append("img",		img);
					envioData.append("titulo",	titulo);
					envioData.append("alt",		alt);
					envioData.append("grupo",	grupo);
					envioData.append("link",	link);
					envioData.append("posicion",posicion);
					envioData.append("visible",	visible);
					envioData.append("color",	color);

					$.ajax({
						url: "adm.banners.php",
						type:"POST",
						processData: false,//tanto processData como contentType deben estar en false para que funcione FormData
						contentType: false,
						cache:false,
						 //async: false,
						data:envioData,
						dataType:"json",
						success: function(respuesta){
							var texto = '';
							if(respuesta.success){
								//if(respuesta.data.mensaje=='Registro correcto'){
									ajax_getData('adm.banners.php','&grupo='+grupo,'elemContenedor','GET');
									$(".resultsBox").html(respuesta.data.mensaje);
								//}
								//texto = respuesta.data.mensaje + '<br/>' + respuesta.data.img;
								//walk simulator	window.location.href='encuesta/?id='+respuesta.data.qr+'-<?php echo ENCUESTA_ACTIVA ?>';
							}else{
								texto = respuesta.error;

							}
							console.log(texto);
							$(".resultsBox").html(texto);
						},
						error: function(respuesta) {
		                    console.log(respuesta);
		                    alert("Error: "+respuesta);
		                }
					});


				//}
			//}else{
				//NO ES IMAGEN
			//	alert("Debes seleccionar un archivo de imagen para subir");
			//}
			
		//}//END IF img

	});

}



function enviaAJAX__OLD(url){
	var data = Array();
	data[0] = Array('res[alt]','texto','"Texto alternativo"');
	if(validarFormulario(data)){
		var id = document.getElementById('r[id]').value;
		var img = document.getElementById('res[img]');
		img = img.options[img.selectedIndex].value;
		var alt = document.getElementById('res[alt]').value;
		var grupo = document.getElementById('res[grupo]');
		grupo = grupo.options[grupo.selectedIndex].value;
		var liga = document.getElementById('res[link]').value;
		var posicion = document.getElementById('res[posicion]').value;
		var visible = document.getElementById('res[visible]');
		if(visible.checked) visible = '1'; else visible = '0';
		var tipo = document.getElementById('tipo').value;
		
		var vars='&r[id]='+id+"&res[img]="+img+"&res[grupo]="+grupo+"&res[link]="+liga+"&res[alt]="+alt+"&res[posicion]="+posicion+"&res[visible]="+visible;
		//alert(url);
		ajax_getData(url+'?'+tipo+'&grupo='+grupo+'&',vars,'elemContenedor','POST');
	}
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