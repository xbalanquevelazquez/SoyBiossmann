function ajax_setDataParent(capa,text)
{	
	if (document.getElementById)
	{	
		parent.document.getElementById(capa).innerHTML = text;
	}
	else if (document.all)
	{	x = parent.document.all[capa];
		x.innerHTML = text;
	}	
	else if (document.layers)
	{	x = parent.document.layers[capa];
		x.parent.document.open();
		x.parent.document.write(text);
		x.parent.document.close();
	}
}
function ajax_setData(capa,text)
{	
	if (document.getElementById)
	{	
		document.getElementById(capa).innerHTML = text;
	}
	else if (document.all)
	{	x = document.all[capa];
		x.innerHTML = text;
	}	
	else if (document.layers)
	{	x = document.layers[capa];
		x.document.open();
		x.document.write(text);
		x.document.close();
	}
}
function ajax_getData(page,datosURL,capa,tipoEnvio)
{       var xmlHttp=false;
		if(typeof(XMLHttpRequest)!='undefined')
		{	try { xmlHttp = new XMLHttpRequest();  } 
			catch(exepcion){  }
        }    
		else
		{	try {  xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');  } 
			catch(excepcion){  xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');  }
        }
		var url = '';
		//------------GET--------------
		if(tipoEnvio=='GET'){ 
				url = '&'+datosURL; //debe iniciar con &
				
				try{ xmlHttp.open('GET',page+'?hash=' + Math.random() * 123456789 + url,true ); 
					 xmlHttp.onreadystatechange=function() 
					 { switch(xmlHttp.readyState)
						{ 	case 1: ajax_setDataParent(capa,'<img src="img/ico/cargador.gif" border="0" />'); break;
							case 4: ajax_setDataParent(capa,xmlHttp.responseText); break;				
						}		
					 }
				   }
				catch(excepcion){  return false;    }
				xmlHttp.send(null);
       	//------------POST-------------
		}else if(tipoEnvio=='POST'){
				try{ xmlHttp.open('POST',page+'?hash=' + Math.random() * 123456789,true ); 
					 xmlHttp.onreadystatechange=function() 
					 { switch(xmlHttp.readyState)
						{ 	//case 1: ajax_setDataParent(capa,'<div id="'+capa+'"><img src="img/ico/cargador.gif" border="0" /></div>'); break;
							case 4: ajax_setData(capa,xmlHttp.responseText); break;				
						}		
					 }
				   }
				catch(excepcion){  return false;    }
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xmlHttp.send(datosURL);
		}
		
		return true;      
}