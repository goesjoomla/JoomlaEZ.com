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
<a href="<?php echo $link; ?>" title="<?php echo $params->get('text') ?>" class="mod_syndicate<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<?php echo $params->get('text') ?></a>
