// Javascript for the CMS CSS Menu Module
// Copyright (c) 2005 Alexander Endresen
// Released under General Public Licence
// This script will emulate the css :hover effect on the menu elements in IE

// The variables
var cssid = "menunav"; // CSS ID for the menuwrapper
var menuadd = " hover";  // Character to be added to the specific classes upon hovering. So for example, if this is set to "h", class "menuparent" will become "menuparenth" when hovered over.
var menuh = "hover"; // Classname for hovering over all other menu items

if (window.attachEvent) window.attachEvent("onload", cssHover);

function cssHover() {
	//alert('init');
	var sfEls = document.getElementById(cssid).getElementsByTagName("li");
	for (var i=0; i<sfEls.length; i++) {

		sfEls[i].onmouseover=function() {
			if (this.className != "") {
				this.className = this.className + menuadd;
				
			}
			else {	
				this.className = menuh;
			}
		}

		sfEls[i].onmouseout=function() {
			if (this.className == menuh) {
				this.className = "";
			}
			else {
				this.className = this.className.replace(new RegExp(menuadd + "$"), "");
			}
		}
	}
}