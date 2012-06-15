<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Wrappers :: raw component output
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

// mark raw component output page
define( 'RAW_OUTPUT', 1 );

// customize template
define( 'TEMPLATE_PATH', dirname(__FILE__) );
require_once(TEMPLATE_PATH.DS.'custom.php');

// Should not output the XML declaration to prevent IE6 from using quirks mode.
// See http://en.wikipedia.org/wiki/Quirks_mode#Comparison_of_document_types for details.
// echo '<?xml version="1.0" encoding="utf-8"?'.">\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
</head>

<body<?php echo $this->params->get('_standardPage') ? '' : ' class="raw"'; ?>>
	<?php
	if ($this->params->get('rawShowHeader') && (($this->params->get('_modLogo') || $this->params->get('logo') != '' || $this->params->get('_navCount'))))
		jezWrapper($this->params->get('wrapperHeader'), 'header', $this->params, 'jezHeader');

	jezWrapper($this->params->get('wrapperPage'), 'page', $this->params, 'jezPage');

	if ($this->params->get('rawShowFooter') && ($this->params->get('_modFooter') || $this->params->get('_debug')))
		jezWrapper($this->params->get('wrapperFooter'), 'footer', $this->params, 'jezFooter');
	?>
</body>
</html>
