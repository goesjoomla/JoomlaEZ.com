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

$n = count($list);
?>
<ul class="listing tr gr<?php echo $n; ?> mod_newsflash horiz">
<?php for ($i = 0, $n = count($list); $i < $n; $i++) : ?>
<li class="fl<?php echo ($i + 1 == $n) ? '' : ' tc'; ?>">
	<?php modNewsFlashHelper::renderItem($list[$i], $params, $access); ?>
</li>
<?php endfor; ?>
</ul>
