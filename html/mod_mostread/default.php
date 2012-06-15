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

defined('_JEXEC') or die;
?>
<ul class="listing mod_mostread<?php echo $params->get( 'moduleclass_sfx' ); ?>">
<?php foreach ($list as $item) : ?>
<li>
	<a href="<?php echo $item->link; ?>" title="<?php echo $item->text; ?>"><?php echo $item->text; ?></a>
</li>
<?php endforeach; ?>
</ul>
