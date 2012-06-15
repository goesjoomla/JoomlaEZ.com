/*
 * JoomlaEZ.com's JavaScript Tools
 *
 * @package		Base
 * @version		1.0.0
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
 * @license		Commercial Proprietary
 *
 * Please visit http://joomlaez.com/ for more information
 */

/***** Dean Edwards's addEvent solution ***************************************/
// written by Dean Edwards, 2005
// with input from Tino Zijdel, Matthias Miller, Diego Perini
// http://dean.edwards.name/weblog/2005/10/add-event/
function jezAddEvent(element,type,handler){if(element.addEventListener){element.addEventListener(type,handler,false);}else{if(!handler.$$guid)handler.$$guid=jezAddEvent.guid++;if(!element.events)element.events={};var handlers=element.events[type];if(!handlers){handlers=element.events[type]={};if(element["on"+type]){handlers[0]=element["on"+type];}}
handlers[handler.$$guid]=handler;element["on"+type]=jezHandleEvent;}};jezAddEvent.guid=1;function jezRemoveEvent(element,type,handler){if(element.removeEventListener){element.removeEventListener(type,handler,false);}else{if(element.events&&element.events[type]){delete element.events[type][handler.$$guid];}}};function jezHandleEvent(event){var returnValue=true;event=event||jezFixEvent(((this.ownerDocument||this.document||this).parentWindow||window).event);var handlers=this.events[event.type];for(var i in handlers){this.$$handleEvent=handlers[i];if(this.$$handleEvent(event)===false){returnValue=false;}}
return returnValue;};function jezFixEvent(event){event.preventDefault=jezFixEvent.preventDefault;event.stopPropagation=jezFixEvent.stopPropagation;return event;};jezFixEvent.preventDefault=function(){this.returnValue=false;};jezFixEvent.stopPropagation=function(){this.cancelBubble=true;};
/******************************************************************** End *****/

var jezIEVer = navigator.appVersion.split("MSIE");
jezIEVer = parseFloat(jezIEVer[1]);
jezIEVer = !parseInt(jezIEVer) ? 7 : jezIEVer; // fix the comparison of 'NaN' (not a number) value with number value in Opera

function jezCreateCookie(name, value, days) {
    if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = "; expires=" + date.toGMTString();
    } else expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function jezReadCookie(name) {
    name = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(name) == 0)
			return c.substring(name.length, c.length); // return cookie data
    }
    return null;
}

function jezSwitchState(element, childTags, hoverClass, focusClass) {
	element = typeof element == 'object' ? element : (document.getElementById ? document.getElementById(element) : document.all[element]);

	// get all children matching childTags
	var elements = [], tmp;
	if (childTags) {
		childTags = typeof childTags == 'string' ? [childTags] : childTags;
		for (var i = 0; i < childTags.length; i++) {
			tmp = element.getElementsByTagName(childTags[i]);
			if (tmp.length > 0) {
				for (var j = 0; j < tmp.length; j++) {
					if (childTags[i].toLowerCase() != 'input' || tmp[j].type != 'hidden')
						elements.push(tmp[j]);
				}
			}
		}
	}

	if (elements.length > 0) {
		for (var i = 0; i < elements.length; i++) {
			// add hoverClass to elements onMouseOver
			if (hoverClass) {
				jezAddEvent(elements[i], 'mouseover', function() {
					this.className += " hover";
				});
				jezAddEvent(elements[i], 'mouseout', function() {
					this.className = this.className.replace(/ hover\b/, '');
				});
			}

			// add focusClass to elements onFocus
			if (focusClass) {
				jezAddEvent(elements[i], 'focus', function() {
					this.className += " focus";
				});
				jezAddEvent(elements[i], 'blur', function() {
					this.className = this.className.replace(/ focus\b/, '');
				});
			}
		}
	}
}

function jezGetVertRhythm(element) {
	var original = element = typeof element == 'object' ? element : (document.getElementById ? document.getElementById(element) : document.all[element]);
	var fontSize, curFontSize, lineHeight;

	while (element.tagName.toLowerCase() != 'html') { // repeat until we get font size in px unit
		// get current element's font size
		if (document.defaultView)
			curFontSize = document.defaultView.getComputedStyle(element, null).getPropertyValue("font-size");
		else if (element.currentStyle)
			curFontSize = element.currentStyle['fontSize'];

		if (!fontSize) { // just store the font size
			if (/\%$/.test(curFontSize))
				fontSize = (curFontSize.replace(/[^\d^\.^,]+$/, '') / 100) + 'em';
			else
				fontSize = curFontSize;
		} else if (element.parentNode.tagName.toLowerCase() != 'html') { // (re)calculate font size
			if (/\%$/.test(curFontSize))
				fontSize = (fontSize.replace(/[^\d^\.^,]+$/, '') * curFontSize.replace(/[^\d^\.^,]+$/, '') / 100) + 'em';
			else if (/em$/.test(curFontSize))
				fontSize = (fontSize.replace(/[^\d^\.^,]+$/, '') * curFontSize.replace(/[^\d^\.^,]+$/, '')) + 'em';
			else
				fontSize = (curFontSize.replace(/[^\d^\.^,]+$/, '') * fontSize.replace(/[^\d^\.^,]+$/, '')) + 'px';
		}

		if (/px$/.test(fontSize))
			break;
		else // still not have font size in px unit, try with the element's parent node
			element = element.parentNode;
	}

	if (!(/px$/.test(fontSize))) {
		// we need to manually calculate font size in px unit
		if (/\%$/.test(curFontSize)) // default 100% font size is 16px in almost all browsers
			fontSize = fontSize.replace(/[^\d^\.^,]+$/, '') * (16 * curFontSize.replace(/[^\d^\.^,]+$/, '') / 100);
		else if (/em$/.test(curFontSize)) // default 1em font size is 16px in almost all browsers
			fontSize = fontSize.replace(/[^\d^\.^,]+$/, '') * (16 * curFontSize.replace(/[^\d^\.^,]+$/, ''));
		else // simply ignore the font size in pt unit
			fontSize = 12;
	} else
		fontSize = fontSize.replace(/[^\d^\.^,]+$/, '');

	// get original element's line height
	if (document.defaultView)
		lineHeight = document.defaultView.getComputedStyle(original, null).getPropertyValue("line-height");
	else if (original.currentStyle)
		lineHeight = original.currentStyle['lineHeight'];

	if (/(\%|em)$/.test(lineHeight)) // calculate line height in px unit from the relative value
		lineHeight = /\%$/.test(lineHeight) ? (fontSize * lineHeight.replace(/[^\d^\.^,]+$/, '') / 100) : (fontSize * lineHeight.replace(/[^\d^\.^,]+$/, ''));
	else if (!(/px$/.test(lineHeight))) // simply ignore the line height in pt unit
		lineHeight = fontSize * 1.5;
	else
		lineHeight = lineHeight.replace(/[^\d^\.^,]+$/, '');

	return {'fontSize': fontSize, 'lineHeight': lineHeight};
}

function jezKeepVertRhythm(elements, matching) {
	elements = typeof elements == 'object' ? (typeof elements.length != 'undefined' ? elements : [elements]) : document.getElementsByTagName(elements);
	var eT, eI, eC, pT, pI, pC, ppC, vR, elHeight, elWidth;

	for (var i = 0; i < elements.length; i++) {
		if (!matching || (new RegExp('\s*' + matching + '\s*')).test(elements[i].className)) {
			eT = elements[i].tagName.toLowerCase();
			eI = elements[i].id;
			eC = elements[i].className;
			pT = elements[i].parentNode.tagName.toLowerCase();
			pI = elements[i].parentNode.id;
			pC = elements[i].parentNode.className;
			ppC = elements[i].parentNode.parentNode.className;
			if (eT != 'img' || (eT == 'img' && (
				(!eI || !(/^recaptcha_/.test(eI))) && (pI != 'recaptcha_image')
				&&
				(!eC || !(/\s*(logo|calendar)\s*/.test(eC))) && !(/\s*button\s*/.test(pT))
				&&
				(pT != "a" || (pT == "a" && pC != "button" && !(/\s*(buttonheading|banneritem)\s*/.test(ppC))))
			))) {
				// get element's font size and line height
				vR = jezGetVertRhythm(elements[i]);

				elHeight = elements[i].style.height ? parseInt(elements[i].style.height) : (elements[i].height ? elements[i].height : elements[i].offsetHeight);
				if (elHeight > vR.lineHeight && (parseInt(elHeight / vR.lineHeight) * vR.lineHeight) != elHeight) {
					// we need to set margin bottom for this element to keep the typography's vertical rhythm
					elements[i].style.marginBottom = ((((parseInt(elHeight / vR.lineHeight) + 1) * vR.lineHeight) - elHeight) / vR.fontSize) + 'em';
				}
			}
		}
	}
}
