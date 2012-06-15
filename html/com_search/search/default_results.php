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
?>
<ol class="listing clr">
<?php foreach ($this->results AS $result) : ?>
<li value="<?php echo ($this->pagination->limitstart + $result->count); ?>">
	<h2><?php if ( $result->href ) :
		if ( $result->browsernav == 1 ) : ?>
		<a href="<?php echo JRoute::_($result->href); ?>" title="<?php echo $this->escape($result->title); ?>" target="_blank">
		<?php else : ?>
		<a href="<?php echo JRoute::_($result->href); ?>" title="<?php echo $this->escape($result->title); ?>">
		<?php endif;
		endif;
		echo $this->escape($result->title);
		if ( $result->href ) : ?>
		</a>
	<?php endif; ?></h2>
	<?php if ( $result->section || $this->params->get( 'show_date' ) ) : ?>
	<p class="info">
		<?php if ($result->section) : ?>
		<span><?php echo $this->escape($result->section); ?></span>
		<?php endif;

		if ($this->params->get( 'show_date' )) : ?>
		<span><?php echo $result->created; ?></span>
		<?php endif; ?>
	</p>
	<?php endif; ?>
	<p class="intro">
		<?php echo $result->text; ?>
	</p>
</li>
<?php endforeach; ?>
</ol>
<?php if ($this->pagination->getPagesLinks() != '') : ?>
<div class="pages_links">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php endif; ?>
