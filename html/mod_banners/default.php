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
<div class="mod_banners<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<?php if ($headerText) : ?>
	<div class="bannerheader"><?php echo $headerText ?></div>
	<?php endif;

	foreach($list as $item) : ?>
	<div class="banneritem">
		<?php echo modBannersHelper::renderBanner($params, $item); ?>
	</div>
	<?php endforeach;

	if ($footerText) : ?>
	<div class="bannerfooter"><?php echo $footerText ?></div>
	<?php endif; ?>
</div>
