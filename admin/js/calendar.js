/**
* Filename.......: calendar.js
* Project........: Popup Calendar
* Last Modified..: $Date: 2002/07/22 18:17:05 $
* CVS Revision...: $Revision: 1.2 $
* Copyright......: 2001, 2002 Richard Heyes
*/

/**
* Global variables
*/
	dynCalendar_layers          = new Array();
	dynCalendar_mouseoverStatus = false;
	dynCalendar_mouseX          = 0;
	dynCalendar_mouseY          = 0;
/**
* The calendar constructor
*
* @access public
* @param string objName      Name of the object that you create
* @param string callbackFunc Name of the callback function
* @param string OPTIONAL     Optional layer name
* @param string OPTIONAL     Optional images path
*/
	function dynCalendar()
	{
		/**
        * Properties
        */
		// Todays date
		
		//alert(arguments[0]['obj']);
		this.today          = new Date();
		var obj = arguments[0]['obj'];
		var myobj = document.getElementById(obj);
		
		if(myobj.value == ''){
			this.date           = this.today.getDate();
			this.month          = this.today.getMonth();
			this.year           = this.today.getFullYear();
		}else{
			var valor = myobj.value;
			var fechaActual = valor.split(" ");
			fechaActual = fechaActual[0].split("-");
			this.date           = parseInt(fechaActual[2]);
			this.month          = parseInt(fechaActual[1])-1;
			this.year           = parseInt(fechaActual[0]);
		}
		this.objEle			= arguments[0]['obj'];
		this.callbackFunc   = arguments[0]['callbackFunc'] ? 'dynCalendar_Format'+arguments[0]['callbackFunc'] : 'dynCalendar_Format1';
		this.imagesPath     = arguments[0]['imagesPath'] ? arguments[0]['imagesPath'] : 'img/calendar/';
		this.layerID        = arguments[0]['layerID'] ? arguments[0]['layerID'] : 'dynCalendar_layer_' + dynCalendar_layers.length;
		this.objName        = arguments[0]['objName'] ? arguments[0]['objName'] : 'calendario'+(dynCalendar_layers.length+1);
		//alert(this.layerID);
		this.offsetX        = 5;
		this.offsetY        = 5;

		this.useMonthCombo  = true;
		this.useHoursInput	= true;
		this.useYearCombo   = true;
		this.yearComboRange = 5;

		this.currentMonth   = this.month;
		this.currentYear    = this.year;

		/**
        * Public Methods
        */
		this.show              = dynCalendar_show;
		this.writeHTML         = dynCalendar_writeHTML;

		// Accessor methods
		this.setOffset         = dynCalendar_setOffset;
		this.setOffsetX        = dynCalendar_setOffsetX;
		this.setOffsetY        = dynCalendar_setOffsetY;
		this.setImagesPath     = dynCalendar_setImagesPath;
		this.setMonthCombo     = dynCalendar_setMonthCombo;
		this.setYearCombo      = dynCalendar_setYearCombo;
		this.setHoursInput     = dynCalendar_setHoursInput;
		this.setCurrentMonth   = dynCalendar_setCurrentMonth;
		this.setCurrentYear    = dynCalendar_setCurrentYear;
		this.setYearComboRange = dynCalendar_setYearComboRange;

		/**
        * Private methods
        */
		// Layer manipulation
		this._getLayer         = dynCalendar_getLayer;
		this._hideLayer        = dynCalendar_hideLayer;
		this._showLayer        = dynCalendar_showLayer;
		this._setLayerPosition = dynCalendar_setLayerPosition;
		this._setHTML          = dynCalendar_setHTML;

		// Miscellaneous
		this._getDaysInMonth   = dynCalendar_getDaysInMonth;
		this._mouseover        = dynCalendar_mouseover;

		/**
        * Constructor type code
        */
		dynCalendar_layers[dynCalendar_layers.length] = this;
		this.writeHTML();
	}
	
	function dynCalendary_time()
	{	var Stamp = new Date();
		var Hours = Stamp.getHours();
		var Mins = Stamp.getMinutes();
		//var Hours = 0;
		//var Mins  = 0;
		if(arguments[0])
		{	var tmpDate = arguments[0].split(' ');
			var tmpTime = tmpDate[1]?tmpDate[1].split(':'):{};
			Hours = tmpTime[0]?parseInt(tmpTime[0],10):Hours;
			Mins  = tmpTime[1]?parseInt(tmpTime[1],10):Mins;
		}
		Mins =Mins <10?'0'+Mins:Mins;
		Hours=Hours<10?'0'+Hours:Hours;
		return {h:Hours,i:Mins};		
	}

/**
* Shows the calendar, or updates the layer if
* already visible.
*
* @access public
* @param integer month Optional month number (0-11)
* @param integer year  Optional year (YYYY format)
*/
	function dynCalendar_show()
	{
		// Variable declarations to prevent globalisation
		var month, year, monthnames, numdays, thisMonth, firstOfMonth, Hours;
		var ret, row, i, cssClass, linkHTML, previousMonth, previousYear;
		var nextMonth, nextYear, prevImgHTML, prevLinkHTML, nextImgHTML, nextLinkHTML;
		var monthComboOptions, monthCombo, yearComboOptions, yearCombo, html;
		var dynTime=dynCalendary_time(dynO(this.objEle).value);
		
		this.currentMonth = month = arguments[0] != null ? arguments[0] : this.currentMonth;
		this.currentYear  = year  = arguments[1] != null ? arguments[1] : this.currentYear;

		monthnames = new Array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		numdays    = this._getDaysInMonth(month, year);

		thisMonth    = new Date(year, month, 1);
		firstOfMonth = thisMonth.getDay();

		// First few blanks up to first day
		ret = new Array(new Array());
		for(i=0; i<firstOfMonth; i++){
			ret[0][ret[0].length] = '<td>&nbsp;</td>';
		}

		// Main body of calendar
		row = 0;
		i   = 1;
		while(i <= numdays){
			if(ret[row].length == 7){
				ret[++row] = new Array();
			}

			/**
            * Generate this cells' HTML
            */
			cssClass = (i == this.date && month == this.month && year == this.year) ? 'dynCalendar_today' : 'dynCalendar_day';
			linkHTML = '<a href="javascript: ' + this.callbackFunc + '(\''+ this.objEle + '\',' + i + ', ' + (Number(month) + 1) + ', ' + year + ','+( this.useHoursInput?('+dynO(\''+this.layerID+'_hora\').value + \':\'+dynO(\''+this.layerID+'_minutos\').value + \':00\''):'\'\'')+'); ' + this.objName + '._hideLayer()" class="dynCalendar_daynumber">' + (i++) + '</a>';
			ret[row][ret[row].length] = '<td align="center" class="' + cssClass + '">' + linkHTML + '</td>';
		}

		// Format the HTML
		for(i=0; i<ret.length; i++){
			ret[i] = ret[i].join('\n') + '\n';
		}

		previousYear  = thisMonth.getFullYear();
		previousMonth = thisMonth.getMonth() - 1;
		if(previousMonth < 0){
			previousMonth = 11;
			previousYear--;
		}
		
		nextYear  = thisMonth.getFullYear();
		nextMonth = thisMonth.getMonth() + 1;
		if(nextMonth > 11){
			nextMonth = 0;
			nextYear++;
		}

		prevImgHTML  = '<img src="' + this.imagesPath + 'prev.gif" alt="<<" border="0" />';
		prevLinkHTML = '<a href="javascript: ' + this.objName + '.show(' + previousMonth + ', ' + previousYear + ')">' + prevImgHTML + '</a>';
		nextImgHTML  = '<img src="' + this.imagesPath + 'next.gif" alt=">>" border="0" />';
		nextLinkHTML = '<a href="javascript: ' + this.objName + '.show(' + nextMonth + ', ' + nextYear + ')">' + nextImgHTML + '</a>';

		/**
        * Build month combo
        */
		if (this.useMonthCombo) {
			monthComboOptions = '';
			for (i=0; i<12; i++) {
				selected = (i == thisMonth.getMonth() ? 'selected="selected"' : '');
				monthComboOptions += '<option value="' + i + '" ' + selected + '>' + monthnames[i] + '</option>';
			}
			monthCombo = '<select name="months" onchange="' + this.objName + '.show(this.options[this.selectedIndex].value, ' + this.objName + '.currentYear)">' + monthComboOptions + '</select>';
		} else {
			monthCombo = monthnames[thisMonth.getMonth()];
		}
		
		/**
        * Build year combo
        */
		if (this.useYearCombo) {
			yearComboOptions = '';
			for (i = thisMonth.getFullYear() - this.yearComboRange; i <= (thisMonth.getFullYear() + this.yearComboRange); i++) {
				selected = (i == thisMonth.getFullYear() ? 'selected="selected"' : '');
				yearComboOptions += '<option value="' + i + '" ' + selected + '>' + i + '</option>';
			}
			yearCombo = '<select name="years" onchange="' + this.objName + '.show(' + this.objName + '.currentMonth, this.options[this.selectedIndex].value)">' + yearComboOptions + '</select>';
		} else {
			yearCombo = thisMonth.getFullYear();
		}

		html = '<!--aqui podria ser--><table border="0" cellspacing="0" cellpadding="0" class="dynCalendar_main">';
		html += '<tr><td class="dynCalendar_header">' + prevLinkHTML + '</td><td colspan="5" align="center" class="dynCalendar_header">' + monthCombo + ' ' + yearCombo + '</td><td align="right" class="dynCalendar_header">' + nextLinkHTML + '</td></tr>';
		html += '<tr class="dynCalendar_dayname">';
		diasSemana = new Array('Dom','Lun','Mar','Mie','Jue','Vie','Sab');
		for (i=0;i<diasSemana.length;i++){
			html += '<td>'+diasSemana[i]+'</td>';
		} 
		html += '</tr>';
		html += '<tr>' + ret.join('</tr>\n<tr>') + '</tr>';
		if(this.useHoursInput) {
		html += '<tr><td colspan="7" align="center">'+'<input id="'+this.layerID+'_hora" name="'+this.layerID+'_hora" type="text" size="2" maxlength="2" onKeyUp="dynCalendar_numeric(event.keyCode,23,this);" />&nbsp;:&nbsp;<input id="'+this.layerID+'_minutos" name="'+this.layerID+'_minutos" type="text" size="2" maxlength="2" onKeyUp="dynCalendar_numeric(event.keyCode,59,this);"/></td></tr>';
		}
		html += '</table>';
		
		this._setHTML(html);
		if (!arguments[0] && !arguments[1]) {
			this._showLayer();
			this._setLayerPosition();
		}		
		
		if(this.useHoursInput) {
		dynO(this.layerID+'_hora').value    = dynTime.h;
		dynO(this.layerID+'_minutos').value = dynTime.i;
		}
		
	}

/**
* Writes HTML to document for layer
*
* @access public
*/
	function dynCalendar_writeHTML()
	{
		if (is_ie5up || is_nav6up || is_gecko) {
			document.write('<a href="javascript: ' + this.objName + '.show()"><img src="' + this.imagesPath + 'dynCalendar.gif" border="0" width="16" height="16" /></a>');
			document.write('<div class="dynCalendar"  id="' + this.layerID + '" style="position:absolute; width:300px; z-index:1;" onmouseover="' + this.objName + '._mouseover(true)" onmouseout="' + this.objName + '._mouseover(false)"></div>');
		}
	}

/**
* Sets the offset to the mouse position
* that the calendar appears at.
*
* @access public
* @param integer Xoffset Number of pixels for vertical
*                        offset from mouse position
* @param integer Yoffset Number of pixels for horizontal
*                        offset from mouse position
*/
	function dynCalendar_setOffset(Xoffset, Yoffset)
	{
		this.setOffsetX(Xoffset);
		this.setOffsetY(Yoffset);
	}

/**
* Sets the X offset to the mouse position
* that the calendar appears at.
*
* @access public
* @param integer Xoffset Number of pixels for horizontal
*                        offset from mouse position
*/
	function dynCalendar_setOffsetX(Xoffset)
	{
		this.offsetX = Xoffset;
	}

/**
* Sets the Y offset to the mouse position
* that the calendar appears at.
*
* @access public
* @param integer Yoffset Number of pixels for vertical
*                        offset from mouse position
*/
	function dynCalendar_setOffsetY(Yoffset)
	{
		this.offsetY = Yoffset;
	}
	
/**
* Sets the images path
*
* @access public
* @param string path Path to use for images
*/
	function dynCalendar_setImagesPath(path)
	{
		this.imagesPath = path;
	}

/**
* Turns on/off the month dropdown
*
* @access public
* @param boolean useMonthCombo Whether to use month dropdown or not
*/
	function dynCalendar_setMonthCombo(useMonthCombo)
	{
		this.useMonthCombo = useMonthCombo;
	}

/**
* Turns on/off the year dropdown
*
* @access public
* @param boolean useYearCombo Whether to use year dropdown or not
*/
	function dynCalendar_setYearCombo(useYearCombo)
	{
		this.useYearCombo = useYearCombo;
	}
	
	function dynCalendar_setHoursInput(ty)
	{
		this.useHoursInput = ty;
	}

/**
* Sets the current month being displayed
*
* @access public
* @param boolean month The month to set the current month to
*/
	function dynCalendar_setCurrentMonth(month)
	{
		this.currentMonth = month;
	}

/**
* Sets the current month being displayed
*
* @access public
* @param boolean year The year to set the current year to
*/
	function dynCalendar_setCurrentYear(year)
	{
		this.currentYear = year;
	}

/**
* Sets the range of the year combo. Displays this number of
* years either side of the year being displayed.
*
* @access public
* @param integer range The range to set
*/
	function dynCalendar_setYearComboRange(range)
	{
		this.yearComboRange = range;
	}

/**
* Returns the layer object
*
* @access private
*/
	function dynCalendar_getLayer()
	{
		var layerID = this.layerID;

		if (document.getElementById(layerID)) {

			return document.getElementById(layerID);

		} else if (document.all(layerID)) {
			return document.all(layerID);
		}
	}

/**
* Hides the calendar layer
*
* @access private
*/
	function dynCalendar_hideLayer()
	{
		this._getLayer().style.display = 'none';
	}

/**
* Shows the calendar layer
*
* @access private
*/
	function dynCalendar_showLayer()
	{
		this._getLayer().style.display = 'block';
	}

/**
* Sets the layers position
*
* @access private
*/
	function dynCalendar_setLayerPosition()
	{
		if (document.all){ var scrollY = window.document.documentElement.scrollTop; } else { var scrollY = 0; }
		this._getLayer().style.top  = ((dynCalendar_mouseY + this.offsetY)+scrollY) + 'px';
		this._getLayer().style.left = (dynCalendar_mouseX + this.offsetX) + 'px';
	}

/**
* Sets the innerHTML attribute of the layer
*
* @access private
*/
	function dynCalendar_setHTML(html)
	{
		this._getLayer().innerHTML = html;
	}

/**
* Returns number of days in the supplied month
*
* @access private
* @param integer month The month to get number of days in
* @param integer year  The year of the month in question
*/
	function dynCalendar_getDaysInMonth(month, year)
	{
		monthdays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
		if (month != 1) {
			return monthdays[month];
		} else {
			return ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0 ? 29 : 28);
		}
	}

/**
* onMouse(Over|Out) event handler
*
* @access private
* @param boolean status Whether the mouse is over the
*                       calendar or not
*/
	function dynCalendar_mouseover(status)
	{
		dynCalendar_mouseoverStatus = status;
		return true;
	}

/**
* onMouseMove event handler
*/
	dynCalendar_oldOnmousemove = document.onmousemove ? document.onmousemove : new Function;

	document.onmousemove = function ()
	{
		if (is_ie5up || is_nav6up || is_gecko) {
			if (arguments[0]) {
				dynCalendar_mouseX = arguments[0].pageX;
				dynCalendar_mouseY = arguments[0].pageY;
			} else {
				dynCalendar_mouseX = event.clientX + document.body.scrollLeft;
				dynCalendar_mouseY = event.clientY + document.body.scrollTop;
				arguments[0] = null;
			}
	
			dynCalendar_oldOnmousemove();
		}
	}

/**
* Callbacks for document.onclick
*/
	dynCalendar_oldOnclick = document.onclick ? document.onclick : new Function;

	document.onclick = function ()
	{
		if (is_ie5up || is_nav6up || is_gecko) {
			if(!dynCalendar_mouseoverStatus){
				for(i=0; i<dynCalendar_layers.length; ++i){
					dynCalendar_layers[i]._hideLayer();
				}
			}
	
			dynCalendar_oldOnclick(arguments[0] ? arguments[0] : null);
		}
	}
function ponerCeros(dato){
	if(dato < 10){
		return '0'+dato;
	}else{
		return dato;
	}
}
function dynCalendar_Format1(ele,dd,mm,aa,hh)
{	idx=0;
	dd=ponerCeros(dd);
	mm=ponerCeros(mm);
	while(document.forms[idx])
	{	if(document.forms[idx].elements[ele])
		{ 	document.forms[idx].elements[ele].value=aa + '-' + mm + '-' + dd+' '+hh;
			break;
		} idx++;	
	}
}

function dynCalendar_Format2(ele,dd,mm,aa,hh)
{	idx=0;
	dd=ponerCeros(dd);
	mm=ponerCeros(mm);
	while(document.forms[idx])
	{	if(document.forms[idx].elements[ele])
		{ 	document.forms[idx].elements[ele].value= aa + '-' + mm + '-' + dd;
			break;
		} idx++;	
	}

}

function dynCalendar_Format3(ele,dd,mm,aa,hh)
{	idx=0;
	while(document.forms[idx])
	{	if(document.forms[idx].elements[ele])
		{ 	document.forms[idx].elements[ele].value= aa + mm + dd;
			break;
		} idx++;	
	}
}

function dynCalendar_Format4(ele,dd,mm,aa,hh)
{	idx=0;
	while(document.forms[idx])
	{	if(document.forms[idx].elements[ele])
		{ 	document.forms[idx].elements[ele].value= aa + '/' + mm + '/' + dd;
			break;
		} idx++;	
	}
}

function dynO(id) { return document.getElementById(id); 
					if (document.getElementById)return document.getElementById(id);
					else if (document.all) return document.all(id);
					return null;					
				   } 

function dynCalendar_numeric(ee,nMax,num)
{ 	//var nn=parseInt(num.value,10);
	var nn=num.value;	
	if(isNaN(nn))
	{	alert(nMax==23?'La hora no es Correcta':'Los minutos no son correctos');	
		num.value='';		
	}
	else if(parseInt(nn,10) > nMax)
	{	alert(nMax==23?'La hora no es Correcta':'Los minutos no son correctos');	
		num.value=num.value.substr(0,1);				
	}

	//else num.value = nn;
}