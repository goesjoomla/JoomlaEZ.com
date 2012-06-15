<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Template Customizer
*
* @package		JEZ Thema
* @version		1.1.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
*
* Please visit http://joomlaez.com/ for more information
*/

/********** Preparing Params **********/
foreach ($_GET AS $k => $v)
	$vars[str_replace('amp;', '', $k)] = str_replace(array('\\"', "\\'"), array('"', "'"), $v);
unset($_GET);

// parsing parameters
$varsName = array(
	'png24Fix', 'png24Fix_for', 'spacer', 'keepVR', 'ie6Hover', 'nav', 'navFx', 'navFxOpts',
	'modalIcons', 'modalRegex', 'modalExact', 'modalSize', 'hasEditor'
);
foreach ($varsName AS $varName)
	${$varName} = isset($vars[$varName]) ? $vars[$varName] : '';
unset($vars);

$js = '';

/********** General **********/
if ($png24Fix) {
	if ($spacer) {
		$js .= '
var SPACER = "'.$spacer.'";';
	}

	if ($png24Fix_for == 'selective') {
		$js .= '
var selective = true;
var pngClass = "png24";';
	}
	$js .= '
jezAddEvent(window, "load", jezFixPNGs);
';
}

if ($keepVR) {
	$js .= '
jezAddEvent(window, "load", function() {
	jezKeepVertRhythm("img");
});
';
}

if ($ie6Hover) {
	$js .= '
if (window.attachEvent && jezIEVer <= 6) { // client browser is IE <= 6
	jezAddEvent(window, "load", function() {
		// add hover & focus states to form elements
		jezSwitchState("jezWrapper", ["input", "select", "textarea", "button"], true, true);
	});
}
';
}

/********** Navigation **********/
if ($nav) {
	$js .= '
if (window.attachEvent && jezIEVer <= 6) { // client browser is IE <= 6
	jezAddEvent(window, "load", function() {
		// add hover state to li elements of global nav
		jezSwitchState("jezNav", "li", true);
	});
}
';
	if ($navFx) {
		$js .= '
window.addEvent("domready", function() {
	new jezMenuFx($("jezNav"), {
		'.($navFxOpts ? str_replace(array(':', ','), array(': ', ",\n\t\t"), $navFxOpts)."," : '').'
		subItemActiveCss: {"background-color": "#444444"},
		subItemInactiveCss: {"background-color": "#888888"}
	});
});
';
	}
}

/********** Modal Links Creation **********/
if ($modalIcons || $modalRegex || $modalExact) {
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
	if ($modalIcons) {
		foreach ($modalIcons AS $className => $dimension) {
			$js .= '
	$$("a.'.$className.'").each(function(el) {
		el.removeProperty("rel").addEvent("click", function(e) {
			new Event(e).stop();
			SqueezeBox.fromElement(el, {"handler": "iframe", "size": {"x" : '.(substr($dimension['w'], -1) == '%' ? 'parseInt((SqueezeBox.winWidth / 100) * '.(int) $dimension['w'].')' : $dimension['w']).', "y" : '.(substr($dimension['h'], -1) == '%' ? 'parseInt((SqueezeBox.winHeight / 100) * '.(int) $dimension['h'].')' : $dimension['h']).'}});
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
				foreach ($modalRegex[$i] AS $attr => $patterns) {
					if ($attr == 'class')
						$attr = 'className';

					$tmp[] = 'if ($defined(el.'.$attr.') && (el.'.$attr.'.match(/'.implode('/i) || el.'.$attr.'.match(/', str_replace('\\\\', '\\', $patterns)).'/i))) {
			el.removeProperty("rel").addEvent("click", function(e) {
				new Event(e).stop();
				SqueezeBox.fromElement(el, {"handler": "iframe", "size": {"x" : '.(substr($modalSize[$i]['w'], -1) == '%' ? 'parseInt((SqueezeBox.winWidth / 100) * '.(int) $modalSize[$i]['w'].')' : $modalSize[$i]['w']).', "y" : '.(substr($modalSize[$i]['h'], -1) == '%' ? 'parseInt((SqueezeBox.winHeight / 100) * '.(int) $modalSize[$i]['h'].')' : $modalSize[$i]['h']).'}});
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
				foreach ($modalExact[$i] AS $attr => $patterns) {
					if ($attr == 'class')
						$attr = 'className';

					$js .= '
	$$("a['.$attr.'='.implode(']", "a['.$attr.'=', $patterns).']").each(function(el) {
		el.removeProperty("rel").addEvent("click", function(e) {
			new Event(e).stop();
			SqueezeBox.fromElement(el, {"handler": "iframe", "size": {"x" : '.(substr($modalSize[$i]['w'], -1) == '%' ? 'parseInt((SqueezeBox.winWidth / 100) * '.(int) $modalSize[$i]['w'].')' : $modalSize[$i]['w']).', "y" : '.(substr($modalSize[$i]['h'], -1) == '%' ? 'parseInt((SqueezeBox.winHeight / 100) * '.(int) $modalSize[$i]['h'].')' : $modalSize[$i]['h']).'}});
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

/********** Fix Editor's Height **********/
if ($hasEditor) {
	$js .= '
jezAddEvent(window, "load", function() {
	jezKeepVertRhythm("table", "mceEditor");
});
';
}

/********** Send Data Back **********/
while (@ob_end_clean());
header('Content-type: text/javascript');
echo $js;
exit;
?>