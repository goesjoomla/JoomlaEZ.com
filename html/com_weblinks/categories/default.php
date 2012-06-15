<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Output Overrides
*
* @package		JEZ Thema
* @version		1.1.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://joomlaez.com/ for more information
*/

/*----------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="weblinks_categories<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif;

if ( ($this->params->def('image', -1) != -1) || $this->params->def('show_comp_description', 1) ) : ?>
<p class="info clearfix">
	<?php
	if ( isset($this->image) )
		echo preg_replace('/align="([^"]+)"/i', 'class="\\1"', $this->image);

	echo $this->params->get('comp_description');
	?>
</p>
<?php endif; ?>
<ul class="listing">
<?php foreach ( $this->categories as $category ) : ?>
<li>
	<h2>
		<a href="<?php echo $category->link; ?>" title="<?php echo $this->escape($category->title);?>">
			<?php echo $this->escape($category->title);?></a>
		&nbsp;<span class="small">(<?php echo $category->numlinks;?>)</span>
	</h2>
</li>
<?php endforeach; ?>
</ul>
</div>
