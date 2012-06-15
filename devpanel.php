<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: dev mode panel
*
* @package		JEZ Thema
* @version		1.1.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://joomlaez.com/ for more information
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// require JEZ Thema helper
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
var jezDevQString = '';
function submitchange() {
	var currentHref = opener.location.href;
	if (jezDevQString != '') {
		currentHref = currentHref.replace(jezDevQString, '');
		jezDevQString = '';
	}

	$each(jezDevParams, function(v, k) {
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
	else
		jezDevQString = '?' + jezDevQString;

	opener.location.href = currentHref + jezDevQString + segment;
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

// set form as component output
$this->setTitle( JText::_('JEZ Thema Dev Mode Panel') );
$this->setBuffer($form, 'component');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
	<link href="administrator/templates/khepri/css/template.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<jdoc:include type="component" />
	<br style="clear:both" />
</body>
</html>