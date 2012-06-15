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

$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="newsfeeds_categories<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif;

if ( ($this->params->get('image') != -1) || $this->params->get('show_comp_description') ) : ?>
<p class="info clearfix">
	<?php
	if (isset($this->image))
		echo preg_replace('/align="([^"]+)"/i', 'class="\\1"', $this->image);

	echo $this->escape($this->params->get('comp_description'));
	?>
</p>
<?php endif; ?>
<ul class="listing">
<?php foreach ( $this->categories as $category ) : ?>
<li>
	<h2>
		<a href="<?php echo $category->link; ?>" title="<?php echo $this->escape($category->title);?>"><?php echo $this->escape($category->title);?></a>
		<?php if ( $this->params->get( 'show_cat_items' ) ) : ?>
		&nbsp;<span class="small">( <?php echo $category->numlinks ." ". JText::_( 'items' );?> )</span>
		<?php endif; ?>
	</h2>
	<?php if ( $this->params->get( 'show_cat_description' ) && $category->description ) : ?>
	<p class="intro"><?php echo $category->description; ?></p>
	<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
</div>
