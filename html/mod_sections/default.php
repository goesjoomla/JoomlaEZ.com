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

defined('_JEXEC') or die;
?>
<ul class="mod_sections<?php echo $params->get( 'moduleclass_sfx' ); ?> listing">
<?php foreach ($list as $item) : ?>
<li>
	<a href="<?php echo JRoute::_(ContentHelperRoute::getSectionRoute($item->id)); ?>" title="<?php echo $item->title;?>"><?php echo $item->title;?></a>
</li>
<?php endforeach; ?>
</ul>
