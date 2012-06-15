<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Wrappers :: dev mode panel
*
* @package		JEZ Rego
* @version		1.5.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://www.joomlaez.com/ for more information
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!(isset($_COOKIE['jezTplName']) && isset($_COOKIE['jezTplDir']))) {
	// get template directory
	$tpl_dir = dirname(__FILE__);
	setcookie('jezTplDir', $tpl_dir);

	// get the active template
	$template = basename($tpl_dir);
	setcookie('jezTplName', $template);
} else {
	$tpl_dir = $_COOKIE['jezTplDir'];
	$template = $_COOKIE['jezTplName'];
}

// is language loaded?
if ( preg_match('/\?*JEZ_REGO\?*/', JText::_('JEZ_REGO')) ) {
	$lang =& JFactory::getLanguage();
	$lang->load( "tpl_{$template}", $tpl_dir );
}

// require JEZ Rego helper
define( 'TEMPLATE_PATH', dirname(__FILE__) );
require_once(TEMPLATE_PATH.DS.'helper.php');

// define parameters
$xmlDoc =& JFactory::getXMLparser();
$xmlDoc->resolveErrors(true);

$success = $xmlDoc->loadXML_utf8(TEMPLATE_PATH.DS.'templateDetails.xml');
if ($success && is_object($xmlDoc->documentElement)) {
	// get parameters from XML document
	$xmlItems = $xmlDoc->getElementsByTagName('param');
	unset($xmlDoc);

	// parse and define parameters if neccessary
	for ($i = 0, $n = $xmlItems->getLength(); $i < $n; $i++) {
		$item =& $xmlItems->item($i);

		// get parameter
		$pname = $item->getAttribute('name');
		if (substr($pname, 0, 1) != '@')
			$this->params->def($pname, $item->getAttribute('default'));
	}
}

if ( !$this->params->get('dev') )
	die( 'Restricted access' );

// load custom theme?
$theme = $this->params->get('theme');

if ( $this->params->get('switchThemeViaPageClassSuffix') && preg_match('/tpl_theme\@.+/i', $this->params->get('pageclass_sfx')) )
	$theme = strtolower(preg_replace('/tpl_theme\@/i', '', $this->params->get('pageclass_sfx')));

if ( $this->params->get('switchThemeViaUrl') && jezThemeBaseHelper::jezGetDevParam('tpl_theme', 1, 0) != null )
	$theme = strtolower(jezThemeBaseHelper::jezGetDevParam('tpl_theme', 1, 0));

if ($theme != '') {
	// load selected theme parameters
	if ( @is_readable(TEMPLATE_PATH.DS.'themes'.DS.$theme.DS.'params.ini') ) {
		unset($this->params);
		$this->params = new JParameter( file_get_contents(TEMPLATE_PATH.DS.'themes'.DS.$theme.DS.'params.ini') );
	}
}

// do not override custom selected theme parameters if it is loaded the first time in this browsing session
if ( isset($_COOKIE['jezThemeBaseThemeLoaded']) )
	$themeLoaded = unserialize( rawurldecode($_COOKIE['jezThemeBaseThemeLoaded']) );

if ( !isset($themeLoaded) || empty($themeLoaded) )
	$themeLoaded = array();

if ( $theme != '' && !in_array($theme, $themeLoaded) ) {
	// set cookie to indicate that theme is loaded
	$themeLoaded[] = $theme;
	setcookie( 'jezThemeBaseThemeLoaded', rawurlencode( serialize($themeLoaded) ) );
} else {
	// get the raw data of defined parameters
	$rawParams = get_object_vars($this->params->_registry['_default']['data']);

	// check request / cookie data for custom template parameters
	$opts = array_keys($rawParams);
	foreach ($opts AS $opt) {
		// theme parameter is already processed, skip it
		if ($opt != 'theme') {
			$value = jezThemeBaseHelper::jezGetDevParam( 'tpl_'.$opt, $this->params->get('dev_rememberParams'), (int) $this->params->get('dev_cookieLifetime') );
			if ($value != null)
				$this->params->set($opt, $value);
		}
	}
}

// build form
JHTML::_('behavior.mootools');
JHTML::_('script', 'joomla.javascript.js', 'includes/js/');

ob_start();
?>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
window.addEvent('domready', function () {
	var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
});

var jezDevParams = {};
function submitchange() {
	// checking for valid parameters combination
	if (typeof jezDevParams['tpl_layoutUnit'] != 'undefined') {
		if (typeof jezDevParams['tpl_tplWidth'] == 'undefined' || typeof jezDevParams['tpl_leftWidth'] == 'undefined' || typeof jezDevParams['tpl_rightWidth'] == 'undefined') {
			if (!confirm("You have changed layout unit but leave either template, left or right column width unchanged which might cause layout broken.\nAre you sure you want to submit changes?"))
				return;
		}
	}

	var currentHref = (typeof opener != 'undefined' && opener != null) ? opener.location.href : parent.location.href;
	jezDevQString = '';

	$each(jezDevParams, function(v, k) {
		// clear previous instance of recently changed param
		currentHref = currentHref.replace(new RegExp('(\\?|\\&)' + k + '=[^\\&]+', 'g'), '');

		if (jezDevQString != '')
			jezDevQString += '&';

		jezDevQString += k + '=' + v;
	});

	// get segment
	var segment = '';
	if (currentHref.indexOf('#') > -1) {
		segment = currentHref.substring(currentHref.indexOf('#'));
		currentHref = currentHref.replace(segment, '');
	}

	if (currentHref.indexOf('?') > -1)
		jezDevQString = '&' + jezDevQString;
	else if (currentHref.indexOf('&') > -1) {
		currentHref = currentHref.replace('&', '?');
		jezDevQString = '&' + jezDevQString;
	}
	else
		jezDevQString = '?' + jezDevQString;

	if (typeof opener != 'undefined' && opener != null)
		opener.location.href = currentHref + jezDevQString + segment;
	else
		parent.location.href = currentHref + jezDevQString + segment;
}
// ]]></script>
<form>
	<?php
	$this->params = new JParameter( $this->params->toString(), TEMPLATE_PATH.DS.'templateDetails.xml' );
	echo $this->params->render('params');
	?>
	<input type="button" name="submit_change" value="Submit Change" onclick="submitchange();" style="position:fixed;top:18px;right:18px" />
</form>
<?php
$form = ob_get_contents();
ob_end_clean();

// prepare parameters form
$form = preg_replace('/select name="params\[([^\]]+)\]"/i', 'select name="tpl_\\1" onchange="jezDevParams[this.name] = this.options[this.selectedIndex].value;"', $form);
$form = preg_replace('/input type="radio" name="params\[([^\]]+)\]"/i', 'input type="radio" name="tpl_\\1" onclick="jezDevParams[this.name] = this.value;"', $form);
$form = preg_replace('/input type="text" name="params\[([^\]]+)\]"/i', 'input type="text" name="tpl_\\1" onchange="jezDevParams[this.name] = this.value;"', $form);
$form = preg_replace('/<tr>[\r\n]<td class="paramlist_value" colspan="2"><input type="hidden"([^>]+)><\/td>[\r\n]<\/tr>/i', '<input type="hidden"\\1>', $form);

if (strtoupper(JRequest::getCmd('hl')) == 'PRO') {
	// pro only parameters
	$pro_params = array(
		'tpl_fontSet', 'tpl_layoutUnit', 'tpl_tplWidth', 'tpl_leftWidth', 'tpl_rightWidth', 'tpl_hdHeight', 'tpl_topHeight',
		'tpl_theme', 'tpl_switchThemeViaPageClassSuffix', 'tpl_switchThemeViaUrl', 'tpl_showThemeSwitcher',
		'tpl_logoAlt', 'tpl_logoWidth', 'tpl_logoHeight', 'tpl_topNoCenterExpand', 'tpl_dev_panel'
	);

	// mark pro only parameters
	foreach ($pro_params AS $pro_param) {
		if (preg_match('/<select name="'.$pro_param.'"[^\r^\n]*>[^\r^\n]+<\/select>/', $form))
			$form = preg_replace('/<select name="'.$pro_param.'"([^\r^\n]*)>([^\r^\n]+)<\/select>/', '<select name="'.$pro_param.'"\\1>\\2</select> <span class="pro-only">PRO ONLY</span>', $form);
		elseif (preg_match('/<input type="text" name="'.$pro_param.'"[^\r^\n]*\/>/', $form))
			$form = preg_replace('/<input type="text" name="'.$pro_param.'"([^\r^\n]*)\/>/', '<input type="text" name="'.$pro_param.'"\\1/> <span class="pro-only">PRO ONLY</span>', $form);
		elseif (preg_match('/<input type="radio" name="'.$pro_param.'"[^\r^\n]*\/>[\r\n][\t\s]*<label for="[^\r^\n]+">[^\r^\n]+<\/label>[\r\n]<\/td>/', $form))
			$form = preg_replace('/<input type="radio" name="'.$pro_param.'"([^\r^\n]*)\/>([\r\n][\t\s]*<label for="[^\r^\n]+">[^\r^\n]+<\/label>[\r\n])<\/td>/', '<input type="radio" name="'.$pro_param.'"\\1/>\\2 <span class="pro-only">PRO ONLY</span>'."\n</td>", $form);
	}
}

// set form as component output
$this->setTitle( JText::_('JEZ Rego Dev Mode Panel') );
$this->setBuffer($form, 'component');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
	<link href="administrator/templates/khepri/css/template.css" rel="stylesheet" type="text/css" />
	<?php if (strtoupper(JRequest::getCmd('hl')) == 'PRO') : ?>
	<style type="text/css"><!--
		.pro-only {
			background-color: #ff0;
			font-size: 12px;
			line-height: 1.5em;
			margin-left: .25em;
			padding: 0 .5em;
			-moz-border-radius: .5em;
			-webkit-border-radius: .5em;
		}
	--></style>
	<?php endif; ?>
</head>

<body>
	<jdoc:include type="component" />
	<br style="clear:both" />
</body>
</html>