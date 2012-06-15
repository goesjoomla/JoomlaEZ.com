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

$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="weblinks_category<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif;

if ($this->category->image || $this->category->description != '') : ?>
<p class="info clearfix">
	<?php
	if ( isset($this->category->image) )
		preg_replace('/align="([^"]+)"/i', 'class="\\1"', $this->category->image);

	echo $this->category->description;
	?>
</p>
<?php endif;

echo $this->loadTemplate('items');

if ($this->params->get('show_other_cats', 1) && count($this->categories) > 1): ?>
<ul class="listing">
<?php foreach ( $this->categories as $category ) :
	if ($category->id != $this->category->id) : ?>
	<li>
		<h2>
			<a href="<?php echo $category->link; ?>" title="<?php echo $this->escape($category->title);?>">
				<?php echo $this->escape($category->title);?></a>
			&nbsp;<span class="small">(<?php echo $category->numlinks;?>)</span>
		</h2>
	</li>
	<?php endif;
endforeach; ?>
</ul>
<?php endif; ?>
</div>
