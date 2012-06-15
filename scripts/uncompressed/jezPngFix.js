/*
 * JoomlaEZ.com's JavaScript Tools
 *
 * @package		IE6 PNG-24 Fix
 * @version		1.0.0
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
 * @license		http://www.opensource.org/licenses/mit-license.html
 *
 * Please visit http://www.joomlaez.com/ for more information
 */

/*

pngfix.js. Version 1.3.

Copyright (c) 2002-2004 Ewan Mellor. All rights reserved.

This software is covered by the MIT Licence
<http://www.opensource.org/licenses/mit-license.html>:

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to
	deal in the Software without restriction, including without limitation the
	rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
	sell copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

		The above copyright notice and this permission notice shall be included in
		all copies or substantial portions of the Software.

		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
		IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
		FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
		THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
		LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
		FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
		DEALINGS IN THE SOFTWARE.


This file may be obtained from <http://www.ewanmellor.org.uk/javascript/>.

This file depends upon sniffer.js by Eric Krok, Andy King, and Michel
Plungjan.

To use this file, simply call fixPNGs from your page's onload event handler:

	<html>
  <head>
		<script type="text/javascript" src="sniffer.js"></script>
		<script type="text/javascript" src="pngfix.js"></script>
  </head>
  <body onload="javascript:fixPNGs()">
		...


All images must have their size specified in the <img> tag. There must also
be a single pixel transparent GIF available as given below.

*/

function jezFixPNGs() {
	if (window.attachEvent && jezIEVer <= 6) { // client browser is IE <= 6
		var images = document.images;
		for (var i = 0; i < images.length; i++)
			jezFixPNG(images[i]);
		setTimeout("jezShowPNGs()", 200);
	}
}

function jezFixPNG(element) {
	var imgName = element.src.toUpperCase();
	if ((!selective && imgName.substring(imgName.length-3, imgName.length) == "PNG") || (selective && (new RegExp('\s*' + pngClass + '\s*')).test(element.className))) {
		if (element.style.visibility == "hidden")
			element.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + element.src + "',sizingMethod='scale') " + "revealTrans(transition=12,duration=0.5)";
		else
			element.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + element.src + "',sizingMethod='scale')";

		if (!element.style.width || element.style.width == 'auto')
			element.style.width = element.offsetWidth + 'px';
		if (!element.style.height || element.style.height == 'auto')
			element.style.height = element.offsetHeight + 'px';

		element.src = SPACER;
	}
}

function jezShowPNGs() {
	var images = document.images;
	for (var i = 0; i < images.length; i++)
		jezShowPNG(images[i]);
}

function jezShowPNG(element) {
	var imgName = element.src.toUpperCase();
	if ((!selective && imgName.substring(imgName.length-3, imgName.length) == "PNG") || (selective && (new RegExp('\s*' + pngClass + '\s*')).test(element.className))) {
		if (element.style.visibility == "hidden") {
			element.filters[1].Apply();
			element.style.visibility = "visible";
			element.filters[1].Play();
		}
	}
}