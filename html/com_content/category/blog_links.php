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
?>
<div>
	<strong><?php echo JText::_( 'More Articles...' ); ?></strong>
</div>
<ul>
<?php foreach ($this->links as $link) : ?>
<li>
	<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($link->slug, $link->catslug, $link->sectionid)); ?>" title="<?php echo $link->title; ?>">
		<?php echo $this->escape($link->title); ?></a>
</li>
<?php endforeach; ?>
</ul>
