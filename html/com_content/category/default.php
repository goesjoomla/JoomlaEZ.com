<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Output Overrides
*
* @package		JEZ Rego
* @version		1.5.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://www.joomlaez.com/ for more information
*/

/*----------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

if (!(isset($_COOKIE['jezTplName']) && isset($_COOKIE['jezTplDir']))) {
	// get template directory
	$tpl_dir = dirname(dirname(dirname(dirname(__FILE__))));
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

$cparams =& JComponentHelper::getParams('com_media');
$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="content_category<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif;

if ($this->category->image || $this->category->description != '') : ?>
<p class="info clearfix">
	<?php if ($this->category->image) : ?>
	<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'. $this->category->image;?>" class="<?php echo $this->category->image_position;?>" alt="<?php echo JText::_('Image'); ?>" />
	<?php
	endif;

	echo $this->category->description;
	?>
</p>
<?php
endif;

$this->items =& $this->getItems();
echo $this->loadTemplate('items');

if ($this->access->canEdit || $this->access->canEditOwn)
	echo JHTML::_('icon.create', $this->category, $this->params, $this->access);
?>
</div>
