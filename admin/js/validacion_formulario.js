function validarFormulario(data){
			var campos = Array();
			var error = Array();
			var lastErrorId = '';
			campos = data;
			
			for (i=0; i<campos.length; i++){
				error[i] = validarCampo(campos[i][0],campos[i][1],campos[i][2]);
			}
			
			var cantidadErrores = 0;
			var mensajeError = '';
			for(j=0; j<error.length; j++){
				if(error[j] != 'ok') { 
				cantidadErrores++;
				mensajeError +=  error[j]+'\n';
				}
			}

			if (cantidadErrores>0){
				alert(acentos(mensajeError));
				return false;
			} else {
				return true;
			}
}
		
function validarCampo(idCampo,tipoValidacion,nombreCampo){
			
			if (tipoValidacion != 'radio'){
				var idObject = document.getElementById(idCampo);
				var idContenido = idObject.value;
			}
			//-----------------------------------------------------------------
			//-----------------------------------------------------------------			
			if (tipoValidacion == 'texto'){
				if (idContenido == ''){
					var mensaje = 'El campo '+nombreCampo+' no puede quedar vacío\n';
					lastErrorId = idCampo;
					setError(idCampo);
					return mensaje;
				} else {
					var mensaje = 'ok';
					return mensaje;
				}
			} else if(tipoValidacion == 'numero'){
				if (idContenido == ''){
					var mensaje = 'El campo '+nombreCampo+' no puede quedar vacío\n';
					lastErrorId = idCampo;
					setError(idCampo);
					return mensaje;
				} else {
					if (isNaN(idContenido)){
						var mensaje = 'El campo '+nombreCampo+' debe contener un número\n';
						lastErrorId = idCampo;
						setError(idCampo);
						return mensaje;
					} else {
						var mensaje = 'ok';
						return mensaje;
					}
				}
			} else if(tipoValidacion == 'combo'){
				var seleccion = idObject.options[idObject.selectedIndex].value;
				if(seleccion == 0){
					var mensaje = 'Debe elegir una opción en el campo '+nombreCampo+'\n';
					lastErrorId = idCampo;
					setError(idCampo);
					return mensaje;
				} else {
					var mensaje = 'ok';
					return mensaje;
				}
			} else if(tipoValidacion == 'radio') {
				var valorRadio = false;
				var valorNegativo = false;
				switch(idCampo){
					/*
					case 'radgenero':
						valorRadio = radgenero;
						valorNegativo = false; //no aplica
					break;
					case 'radp1':
						valorRadio = radp1;
						valorNegativo = radp1NO;
					break;
					case 'radp2':
						valorRadio = radp2;
						valorNegativo = radp2NO;
					break;
					case 'radp3':
						valorRadio = radp3;
						valorNegativo = radp3NO;
					break;
					case 'radp4':
						valorRadio = radp4;
						valorNegativo = radp4NO;
					break;
					case 'radp5':
						valorRadio = radp5;
						valorNegativo = radp5NO;
					break;
					case 'radp6':
						valorRadio = radp6;
						valorNegativo = radp6NO;
					break;
					case 'radp7':
						valorRadio = radp7;
						valorNegativo = radp7NO;
					break;
					*/
				}
				if(valorRadio){
					if(valorNegativo){
						var mensaje = 'Debe ACEPTAR el campo '+nombreCampo+' para poder continuar\n';
						lastErrorId = idCampo;
						setError(idCampo);
						return mensaje;
					}else{
						var mensaje = 'ok';
						return mensaje;
					}	
				}else{
					var mensaje = 'Debe elegir una opción en el campo '+nombreCampo+'\n';
					lastErrorId = idCampo;
					setError(idCampo);
					return mensaje;
				}
			} else if(tipoValidacion == 'email'){
					var regexpX = /^[A-Za-z0-9_-]+(\.[A-Za-z0-9_-]+)*@[A-Za-z0-9-_]+(\.[A-Za-z0-9-_]+)+$/;
					if (regexpX.test(idContenido)){
						var mensaje = 'ok';
						return mensaje;
					}else{
						var mensaje = 'Ingrese una dirección de correo válida\n';
						lastErrorId = idCampo;
						setError(idCampo);
						return mensaje;
					}
				}
}
function reemplazarString(indice){
			indiceObjeto = document.getElementById(indice);
			indiceValor = indiceObjeto.value;
			

			var nuevoValor = indiceValor.toLowerCase(indiceValor);//todo a minúsculas
			nuevoValor = nuevoValor.replace(/á/g, "a");//cambiar caracteres especiales (acentos y ñ)
			nuevoValor = nuevoValor.replace(/é/g, "e");
			nuevoValor = nuevoValor.replace(/í/g, "i");
			nuevoValor = nuevoValor.replace(/ó/g, "o");
			nuevoValor = nuevoValor.replace(/ú/g, "u");
			nuevoValor = nuevoValor.replace(/ñ/g, "ni");
			nuevoValor = nuevoValor.replace(/'/g, "");
			nuevoValor = nuevoValor.replace(/´/g, "");

			nuevoValor = nuevoValor.replace(/\s/g, "-");//cambiar espacios por guiones
			
			
			indiceObjeto.value=nuevoValor;
}
function acentos(texto){
	return texto;
	/*
			var nuevoValor = texto;
			nuevoValor = nuevoValor.replace(/á/g, "\u00e1");
			nuevoValor = nuevoValor.replace(/é/g, "\u00e9");
			nuevoValor = nuevoValor.replace(/í/g, "\u00ed");
			nuevoValor = nuevoValor.replace(/ó/g, "\u00f3");
			nuevoValor = nuevoValor.replace(/ú/g, "\u00fa");
			nuevoValor = nuevoValor.replace(/ñ/g, "\u00f1");
		
			nuevoValor = nuevoValor.replace(/Á/g, "\u00c1");//cambiar caracteres especiales (acentos y ñ)
			nuevoValor = nuevoValor.replace(/É/g, "\u00c9");
			nuevoValor = nuevoValor.replace(/Í/g, "\u00cd");
			nuevoValor = nuevoValor.replace(/Ó/g, "\u00d3");
			nuevoValor = nuevoValor.replace(/Ú/g, "\u00da");
			nuevoValor = nuevoValor.replace(/Ñ/g, "\u00d1");
			return nuevoValor;*/
}
function mayusculas(id){
			var indiceObjeto = document.getElementById(id);
			var indiceValor = indiceObjeto.value;
			
			var nuevoValor = indiceValor.toUpperCase(indiceValor);
			indiceObjeto.value = nuevoValor;
}
function encode_utf8(s) {
  return unescape(encodeURIComponent(s));
}

function decode_utf8(s) {
  return decodeURIComponent(escape(s));
}



// http://www.onicos.com/staff/iz/amuse/javascript/expert/utf.txt

/* utf.js - UTF-8 <=> UTF-16 convertion
 *
 * Copyright (C) 1999 Masanao Izumo <iz@onicos.co.jp>
 * Version: 1.0
 * LastModified: Dec 25 1999
 * This library is free.  You can redistribute it and/or modify it.
 */
function utf8ArrayToStr(array) {
  var out, i, len, c;
  var char2, char3;

  out = "";
  len = array.length;
  i = 0;

  // XXX: Invalid bytes are ignored
  while(i < len) {
    c = array[i++];
    if (c >> 7 == 0) {
      // 0xxx xxxx
      out += String.fromCharCode(c);
      continue;
    }

    // Invalid starting byte
    if (c >> 6 == 0x02) {
      continue;
    }

    // #### MULTIBYTE ####
    // How many bytes left for thus character?
    var extraLength = null;
    if (c >> 5 == 0x06) {
      extraLength = 1;
    } else if (c >> 4 == 0x0e) {
      extraLength = 2;
    } else if (c >> 3 == 0x1e) {
      extraLength = 3;
    } else if (c >> 2 == 0x3e) {
      extraLength = 4;
    } else if (c >> 1 == 0x7e) {
      extraLength = 5;
    } else {
      continue;
    }

    // Do we have enough bytes in our data?
    if (i+extraLength > len) {
      var leftovers = array.slice(i-1);

      // If there is an invalid byte in the leftovers we might want to
      // continue from there.
      for (; i < len; i++) if (array[i] >> 6 != 0x02) break;
      if (i != len) continue;

      // All leftover bytes are valid.
      return {result: out, leftovers: leftovers};
    }
    // Remove the UTF-8 prefix from the char (res)
    var mask = (1 << (8 - extraLength - 1)) - 1,
        res = c & mask, nextChar, count;

    for (count = 0; count < extraLength; count++) {
      nextChar = array[i++];

      // Is the char valid multibyte part?
      if (nextChar >> 6 != 0x02) {break;};
      res = (res << 6) | (nextChar & 0x3f);
    }

    if (count != extraLength) {
      i--;
      continue;
    }

    if (res <= 0xffff) {
      out += String.fromCharCode(res);
      continue;
    }

    res -= 0x10000;
    var high = ((res >> 10) & 0x3ff) + 0xd800,
        low = (res & 0x3ff) + 0xdc00;
    out += String.fromCharCode(high, low);
  }

  return {result: out, leftovers: []};
}
function setError(id){
	$('#'+id).addClass('errorHere').focus(function(e){
		borrarError($(this));
	});
}
function borrarError(obj){
	obj.removeClass('errorHere');
}