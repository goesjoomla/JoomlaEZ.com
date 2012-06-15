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
	'fontSet', 'layoutUnit', 'tplWidth', 'hdHeight', 'logoWidth', 'logoHeight',
	'topHeight', 'leftWidth', 'rightWidth', 'internalLinks'
);

foreach ($varsName AS $varName)
	${$varName} = isset($vars[$varName]) ? $vars[$varName] : '';

unset($vars);

$css = '';

/********** Font Set **********/
$fonts = array (
	'corp' => array (
		'content' => 'font-family: Arial, Helvetica, sans-serif;',
		'heading' => 'font-family: Verdana, Geneva, sans-serif;'
	),
	'blog' => array (
		'content' => 'font-family: "Trebuchet MS", Helvetica, sans-serif;',
		'heading' => 'font-family: Georgia, Serif, serif;'
	),
	'news' => array(
		'content' => 'font-family: "Times New Roman", Times, serif;
}
#jezComponent, div[class^=mod_], form[class^=mod_], p[class^=mod_], a[class^=mod_], ul.menu, ul.menu-nav {
	font-size: 1.167em;',
		'heading' => 'font-family: "Palatino Linotype", Palatino, serif;'
	)
);

if ($fontSet) {
	$css .= '
body {
	'.$fonts[$fontSet]['content'].'
}
/* general heading & caption */
h1, h2, h3, h4, h5, h6, caption,
#ie6Warning,
/* module heading & label */
#modUser1 .jezRounded6Imgs h3,
#jezLocal .jezRounded6Imgs h3,
#modUser2 .jezRounded6Imgs h3,
#jezSub .jezRounded6Imgs h3,
#jezExtras .moduletable h3,
#modUser4.special h4, #modUser4.special a,
#modUser2 .mod_login label, #modUser2 .mod_login label,
#modUser2 .mod_login a,
.mod_poll h4,
/* article info & caption */
.content_article .info span,
.img_caption p,
/* navigation */
ul.menu li a, ul.menu li .separator,
ul.menu-nav li a, ul.menu-nav li .separator,
#modUser3 ul.menu > li > a, #modUser3 ul.menu > li > .separator,
#modUser3 ul.menu-nav > li > a, #modUser3 ul.menu-nav > li > .separator,
#modUser3 ul.menu ul a, #modUser3 ul.menu ul .separator,
#modUser3 ul.menu-nav ul a, #modUser3 ul.menu-nav ul .separator,
#modUser9 ul.menu a, #modUser9 ul.menu .separator,
#modUser9 ul.menu-nav a, #modUser9 ul.menu-nav .separator,
#modBreadcrumb,
/* button */
.readon a,
.mod_poll button, .mod_poll a.button,
#modUser2 .mod_login button, #modUser2 .mod_login a.button {
	'.$fonts[$fontSet]['heading'].'
}
';
}

/********** Layout Customization **********/
$units = array(
	'fixed' => 'px',
	'elastic' => 'em',
	'fluid' => '%'
);

if ($tplWidth) {
	$css .= '
body {
	min-width: '.(preg_match('/^[\d\.]+$/', $tplWidth) ? $tplWidth.$units[$layoutUnit] : $tplWidth).';
}
.wrapper {
	width: '.(preg_match('/^[\d\.]+$/', $tplWidth) ? $tplWidth.$units[$layoutUnit] : $tplWidth).';
}
';
}

// columns
if (
	($leftWidth || $rightWidth)
	&&
	(
		(preg_match('/^[\d\.]+$/', $tplWidth) && preg_match('/^[\d\.]+$/', $leftWidth) && preg_match('/^[\d\.]+$/', $rightWidth))
		||
		(substr($tplWidth, -1) == '%' && substr($leftWidth, -1) == '%' && substr($rightWidth, -1) == '%')
		||
		(substr($tplWidth, -2) == substr($leftWidth, -2) && substr($leftWidth, -2) == substr($rightWidth, -2))
	)
) {
	$unit = preg_match('/^[\d\.]+$/', $leftWidth) ? $units[$layoutUnit] : substr($leftWidth, -2);

	if ($leftWidth == '')
		$leftWidth = $rightWidth;

	if ($rightWidth == '')
		$rightWidth = $leftWidth;

	$tplWidth = floatval($tplWidth);
	$leftWidth = floatval($leftWidth);
	$rightWidth = floatval($rightWidth);
	$centerWidth = $tplWidth - $leftWidth - $rightWidth;

	if ($unit == 'em') {
		// recalculate center column width
		$centerWidth -= (2.5 * 2) /* far-left and far-right spacing */ + (0.417 * 2) /* spacing between columns */;

		$css .= '
#modUser4, #jezMain {
	margin-left: '.(2.5 + $leftWidth + 0.417).'em;
}
#modUser1, #jezLocal {
	width: '.$leftWidth.'em;
}
#modUser2, #jezSub {
	width: '.$rightWidth.'em;
}

.gr3 #modUser4, .gr3 #jezMain {
	width: '.$centerWidth.'em;
}
.gr3 #modUser1, .gr3 #jezLocal {
	margin-left: -'.($leftWidth + 0.417 + $centerWidth).'em;
}

.gr2 #modUser4, .gr2 #jezMain {
	width: '.($centerWidth + 0.333 + 0.417 + $rightWidth).'em;
}
.gr2 #modUser4.tc, .gr2 #jezMain.tc {
	width: '.($leftWidth + 0.417 + 0.333 + $centerWidth).'em;
}
.gr2 #modUser1.expandLeft, .gr2 #jezLocal.expandLeft {
	width: '.($leftWidth + (0.417 - 0.333) + $centerWidth).'em;
}
.gr2 #modUser2.expandRight, .gr2 #jezSub.expandRight {
	width: '.($centerWidth + (0.417 - 0.333) + $rightWidth).'em;
}
.gr2 #modUser1, .gr2 #jezLocal {
	margin-left: -'.($leftWidth + 0.417 + $centerWidth + 0.333 + 0.417 + $rightWidth).'em;
}
.gr2 #modUser1.expandBoth, .gr2 #modUser2.expandBoth,
.gr2 #jezLocal.expandBoth, .gr2 #jezSub.expandBoth {
	width: '.round(($tplWidth - 5 - 0.833) / 2, 3).'em;
}
.gr2 #modUser2.expandRight, .gr2 #jezSub.expandRight {
	margin-left: '.(2.5 + $leftWidth + 0.417 + 0.333).'em;
}

.gr1 #modUser4, .gr1 #jezMain {
	width: '.($tplWidth - (2.167 * 2)).'em;
}
.gr1 #modUser1, .gr1 #modUser2, .gr1 #jezLocal, .gr1 #jezSub {
	width: '.($tplWidth - (2.5 * 2)).'em;
}
';
	} elseif ($unit == 'px') {
		// recalculate center column width
		$centerWidth -= (30 * 2) /* far-left and far-right spacing */ + (5 * 2) /* spacing between columns */;

		$css .= '
#modUser4, #jezMain {
	margin-left: '.(30 + $leftWidth + 5).'px;
}
#modUser1, #jezLocal {
	width: '.$leftWidth.'px;
}
#modUser2, #jezSub {
	margin-right: 30px;
	width: '.$rightWidth.'px;
}

.gr3 #modUser4, .gr3 #jezMain {
	width: '.$centerWidth.'px;
}
.gr3 #modUser1, .gr3 #jezLocal {
	margin-left: -'.($leftWidth + 5 + $centerWidth).'px;
}

.gr2 #modUser4, .gr2 #jezMain {
	width: '.($centerWidth + 4 + 5 + $rightWidth).'px;
}
.gr2 #modUser4.tc, .gr2 #jezMain.tc {
	margin-left: 26px;
	width: '.($leftWidth + 5 + 4 + $centerWidth).'px;
}
.gr2 #modUser1.expandLeft, .gr2 #jezLocal.expandLeft {
	width: '.($leftWidth + (5 - 4) + $centerWidth).'px;
}
.gr2 #modUser2.expandRight, .gr2 #jezSub.expandRight {
	width: '.($centerWidth + (5 - 4) + $rightWidth).'px;
}
.gr2 #modUser1, .gr2 #jezLocal {
	margin-left: -'.($leftWidth + 5 + $centerWidth + 4 + 5 + $rightWidth).'px;
}
.gr2 #modUser1.expandBoth, .gr2 #modUser1.expandLeft,
.gr2 #jezLocal.expandBoth, .gr2 #jezLocal.expandLeft {
	margin-left: 30px;
}
.gr2 #modUser1.expandBoth, .gr2 #modUser2.expandBoth,
.gr2 #jezLocal.expandBoth, .gr2 #jezSub.expandBoth {
	width: '.round(($tplWidth - 60 - 10) / 2).'px;
}
.gr2 #modUser2.expandRight, .gr2 #jezSub.expandRight {
	margin-left: '.(30 + $leftWidth + 5 + 4).'px;
	margin-right: 4px;
}

.gr1 #modUser4, .gr1 #jezMain {
	margin-left: 26px;
	width: '.($tplWidth - (26 * 2)).'px;
}
.gr1 #modUser1, .gr1 #modUser2, .gr1 #jezLocal, .gr1 #jezSub {
	margin-left: 30px;
	width: '.($tplWidth - (30 * 2)).'px;
}
';
	} else {
		// recalculate center column width
		$centerWidth = 100 - $leftWidth - $rightWidth;
		$centerWidth -= (3 * 2) /* far-left and far-right spacing */ + (0.5 * 2) /* spacing between columns */;

		$css .= '
#modUser4, #jezMain {
	margin-left: '.(3 + $leftWidth + 0.5).'%;
}
#modUser1, #jezLocal {
	width: '.$leftWidth.'%;
}
#modUser2, #jezSub {
	margin-right: 3%;
	width: '.$rightWidth.'%;
}

.gr3 #modUser4, .gr3 #jezMain {
	width: '.$centerWidth.'%;
}
.gr3 #modUser1, .gr3 #jezLocal {
	margin-left: -'.($leftWidth + 0.5 + $centerWidth).'%;
}

.gr2 #modUser4, .gr2 #jezMain {
	width: '.($centerWidth + 0.4 + 0.5 + $rightWidth).'%;
}
.gr2 #modUser4.tc, .gr2 #jezMain.tc {
	margin-left: 2.6%;
	width: '.($leftWidth + 0.5 + 0.4 + $centerWidth).'%;
}
.gr2 #modUser1.expandLeft, .gr2 #jezLocal.expandLeft {
	width: '.($leftWidth + (0.5 - 0.4) + $centerWidth).'%;
}
.gr2 #modUser2.expandRight, .gr2 #jezSub.expandRight {
	width: '.($centerWidth + (0.5 - 0.4) + $rightWidth).'%;
}
.gr2 #modUser1, .gr2 #jezLocal {
	margin-left: -'.($leftWidth + 0.5 + $centerWidth + 0.4 + 0.5 + $rightWidth).'%;
}
.gr2 #modUser1.expandBoth, .gr2 #modUser1.expandLeft,
.gr2 #jezLocal.expandBoth, .gr2 #jezLocal.expandLeft {
	margin-left: 3%;
}
.gr2 #modUser1.expandBoth, .gr2 #modUser2.expandBoth,
.gr2 #jezLocal.expandBoth, .gr2 #jezSub.expandBoth {
	width: '.round((100 - 6 - 1) / 2, 1).'%;
}
.gr2 #modUser2.expandRight, .gr2 #jezSub.expandRight {
	margin-left: '.(3 + $leftWidth + 0.5 + 0.4).'%;
	margin-right: 0.4%;
}

.gr1 #modUser4, .gr1 #jezMain {
	margin-left: 2.6%;
	width: '.(100 - (2.6 * 2)).'%;
}
.gr1 #modUser1, .gr1 #modUser2, .gr1 #jezLocal, .gr1 #jezSub {
	margin-left: 3%;
	width: '.(100 - (3 * 2)).'%;
}
';
	}
}

// header
if ( $hdHeight && substr($hdHeight, -1) != '%' ) {
	$hdHeight = preg_match('/^[\d\.]+$/', $hdHeight) ? $hdHeight.$units[$layoutUnit] : $hdHeight;
	$css .= '
#jezHeader, #jezNav {
	height: '.$hdHeight.';
}
#modUser3 {
	margin-top: '.(substr($hdHeight, -2) == 'px' ? (45 + (intval($hdHeight) - 75)).'px' : (3.75 + (floatval($hdHeight) - 6.25)).'em').';
}
';
}

// logo
if ( $logoWidth || $logoHeight ) {
	$css .= '
#jezLogo img.logo {
'.($logoHeight ? '	height: '.(preg_match('/^[\d\.]+$/', $logoHeight) ? $logoHeight.$units[$layoutUnit] : $logoHeight).";\n" : '')
.($logoWidth ? '	width: '.(preg_match('/^[\d\.]+$/', $logoWidth) ? $logoWidth.$units[$layoutUnit] : $logoWidth).";\n" : '').'
}
';
}

// top block
if ( $topHeight && substr($topHeight, -1) != '%' ) {
	$topHeight = preg_match('/^[\d\.]+$/', $topHeight) ? $topHeight.$units[$layoutUnit] : $topHeight;
	$css .= '
#jezPage.hasTop {
	padding-top: '.(substr($topHeight, -2) == 'px' ? (intval($topHeight) + 20).'px' : (floatval($topHeight) + 1.667).'em').';
}
#jezTop {
	height: '.$topHeight.';
}
#modUser1 .jezRounded6Imgs .bd .c .s,
#modUser2 .jezRounded6Imgs .bd .c .s,
#modUser4 .jezRounded6Imgs .bd .c .s {
	height: '.(substr($topHeight, -2) == 'px' ? (intval($topHeight) - 36).'px' : (floatval($topHeight) - 3).'em').';
}
';
}

/********** Reset Internal Link Style **********/
if ($internalLinks) {
	$inLinks = explode(',', $internalLinks);
	$css .= '
a[href^="http://'.implode('"],a[href^="http://', $inLinks).'"] {
	padding: 0;
	background: none;
}
';
}

/********** Send Data Back **********/
while (@ob_end_clean());
header('Content-type: text/css');
echo $css;
exit;
?>