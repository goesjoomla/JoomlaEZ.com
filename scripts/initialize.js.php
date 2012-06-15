<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Template Customizer
*
* @package		JEZ Rego
* @version		1.5.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://www.joomlaez.com/ for more information
*/

/********** Preparing Params **********/
foreach ($_GET AS $k => $v)
	$vars[str_replace('amp;', '', $k)] = str_replace(array('\\"', "\\'"), array('"', "'"), $v);

unset($_GET);

// parsing parameters
$varsName = array(
	'png24Fix', 'png24Fix_for', 'spacer', 'keepVR', 'navFx', 'navFxOpts', 'hasEditor',
	'modalPdf', 'modalPrint', 'modalEmail', 'modalRegex', 'modalExact', 'modalSize'
);

foreach ($varsName AS $varName)
	${$varName} = isset($vars[$varName]) ? $vars[$varName] : '';

unset($vars);

$js = '';

/********** Fix Editor's Height **********/
if ($hasEditor) {
	$js .= '
jezAddEvent(window, "load", function() {
	jezKeepVertRhythm("table", "mceEditor");
});
';
}

/********** Navigation **********/
if ($navFx) {
	$js .= '
window.addEvent("domready", function() {
	new jezMenuFx($("jezNav"), {
		'.($navFxOpts ? str_replace(array(':', ','), array(': ', ",\n\t\t"), $navFxOpts) : '').'
	});
});
';
}

/********** Fix PNG-24 for IE6 **********/
if ($png24Fix) {
	if ($spacer) {
		$js .= '
if (window.attachEvent && jezIEVer <= 6) { // client browser is IE <= 6
	var SPACER = "'.$spacer.'";';
	}

	if ($png24Fix_for == 'selective') {
		$js .= '
	var selective = true;
	var pngClass = "png24";';
	} else {
		$js .= '
	var selective = false;';
	}

	$js .= '
	jezAddEvent(window, "load", jezFixPNGs);
}';
}

/********** Keep Typography's Vertical Rhythm **********/
if ($keepVR) {
	$js .= '
jezAddEvent(window, "load", function() {
	jezKeepVertRhythm("img");
});
';
}

/********** Modal Links Creation **********/
if ($modalPdf || $modalPrint || $modalEmail || $modalRegex || $modalExact) {
	$js .= '
window.addEvent("load", function() {
	SqueezeBox.initialize();
	if (typeof window.innerWidth == "number") {
		// non-IE
		SqueezeBox.winWidth = window.innerWidth;
		SqueezeBox.winHeight = window.innerHeight;
	} else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
		// IE 6+ in standards compliant mode
		SqueezeBox.winWidth = document.documentElement.clientWidth;
		SqueezeBox.winHeight = document.documentElement.clientHeight;
	} else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
		// IE 4 compatible
		SqueezeBox.winWidth = document.body.clientWidth;
		SqueezeBox.winHeight = document.body.clientHeight;
	}';

	// modalize article's icons links
	foreach ( array('modalPdf', 'modalPrint', 'modalEmail') AS $modalIcon ) {
		if (${$modalIcon}) {
			list($w, $h) = preg_split("/\s*x\s*/i", ${$modalIcon}, 2);
			$js .= '
	$$("a.'.$modalIcon.'").each(function(el) {
		el.removeProperty("rel").addEvent("click", function(e) {
			new Event(e).stop();
			SqueezeBox.fromElement(el, {"handler": "iframe", "size": {"x" : '.(substr($w, -1) == '%' ? 'parseInt((SqueezeBox.winWidth / 100) * '.(int) $w.')' : $w).', "y" : '.(substr($h, -1) == '%' ? 'parseInt((SqueezeBox.winHeight / 100) * '.(int) $h.')' : $h).'}});
			return false;
		});
	});
';
		}
	}

	// match specified link attribute using regular expression
	if ($modalRegex) {
		for ($i = 0; $i < 10; $i++) {
			if (isset($modalRegex[$i])) {
				foreach ($modalRegex[$i] AS $attr => $pattern) {
					if ($attr == 'class')
						$attr = 'className';

					list($w, $h) = preg_split("/\s*x\s*/i", $modalSize[$i], 2);
					$tmp[] = 'if ($defined(el.'.$attr.') && el.'.$attr.'.match(/'.str_replace('\\\\', '\\', $pattern).'/i)) {
			el.removeProperty("rel").addEvent("click", function(e) {
				new Event(e).stop();
				SqueezeBox.fromElement(el, {"handler": "iframe", "size": {"x" : '.(substr($w, -1) == '%' ? 'parseInt((SqueezeBox.winWidth / 100) * '.(int) $w.')' : $w).', "y" : '.(substr($h, -1) == '%' ? 'parseInt((SqueezeBox.winHeight / 100) * '.(int) $h.')' : $h).'}});
				return false;
			});
		}';
				}
			}
		}

		if (isset($tmp)) {
			$js .= '
	$$("a").each(function(el) {
		'.implode(' else ', $tmp).'
	});
';
		}
	}

	// match specified link attribute exactly
	if ($modalExact) {
		for ($i = 0; $i < 10; $i++) {
			if (isset($modalExact[$i])) {
				foreach ($modalExact[$i] AS $attr => $pattern) {
					if ($attr == 'class')
						$attr = 'className';

					list($w, $h) = preg_split("/\s*x\s*/i", $modalSize[$i], 2);
					$js .= '
	$$("a['.$attr.'='.$pattern.']").each(function(el) {
		el.removeProperty("rel").addEvent("click", function(e) {
			new Event(e).stop();
			SqueezeBox.fromElement(el, {"handler": "iframe", "size": {"x" : '.(substr($w, -1) == '%' ? 'parseInt((SqueezeBox.winWidth / 100) * '.(int) $w.')' : $w).', "y" : '.(substr($h, -1) == '%' ? 'parseInt((SqueezeBox.winHeight / 100) * '.(int) $h.')' : $h).'}});
			return false;
		});
	});';
				}
			}
		}
	}

	$js .= '
});
';
}

/********** Send Data Back **********/
while (@ob_end_clean());
header('Content-type: text/javascript');
echo $js;
exit;
?>