<?php
/*
 * JEZ Thema Joomla! 1.5 Theme Base :: Custom Module Chromes
 *
 * @package		JEZ Thema
 * @version		1.1.0
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
 * @license		Free for personal use with link back
 * @license		For other purposes, please purchase commercial license
 * @link		http://joomlaez.com/
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// module chrome that creates rounded corners using schillmania.com's "More Rounded Corners With CSS" technique
function modChrome_jezRounded6Imgs($module, &$params, &$attribs) {
	// prepare individual module styling
	if ( !$params->get('moduleclass_sfx') )
		$params->set( 'moduleclass_sfx', ' '.preg_replace(array('/[^\w^\d^\s]/', '/\s+/'), array('', '-'), strtolower($module->title)) );
	elseif ( preg_match('/^_(menu|text)$/', $params->get('moduleclass_sfx')) )
		$params->set( 'moduleclass_sfx', preg_replace('/^_(menu|text)$/', ' \\1', $params->get('moduleclass_sfx')) );

	if (isset($attribs['nested']) && $attribs['nested']) { ?>
<div class="jezRounded6ImgsNested<?php echo $params->get('moduleclass_sfx'); ?>"><div class="in1"><div class="in2"><div class="in3"><div class="in4"><div class="in5 tr">
	<?php } else { ?>
<div class="jezRounded6Imgs<?php echo $params->get('moduleclass_sfx'); ?>"><div class="hd"><div class="c"></div></div><div class="bd"><div class="c"><div class="s tr">
	<?php }

	if ($module->showtitle) { ?>
		<h3><?php echo $module->title; ?></h3>
	<?php }

	echo $module->content;

	if (isset($attribs['nested']) && $attribs['nested']) { ?>
</div></div></div></div></div></div>
	<?php } else { ?>
</div></div></div><div class="ft"><div class="c"></div></div></div>
	<?php }
}

// module chrome that creates rounded corners using schillmania.com's "Even More Rounded Corners With CSS" technique
function modChrome_jezRounded1Img($module, &$params, &$attribs) {
	// prepare individual module styling
	if ( !$params->get('moduleclass_sfx') )
		$params->set( 'moduleclass_sfx', ' '.preg_replace(array('/[^\w^\d^\s]/', '/\s+/'), array('', '-'), strtolower($module->title)) );
	elseif ( preg_match('/^_(menu|text)$/', $params->get('moduleclass_sfx')) )
		$params->set( 'moduleclass_sfx', preg_replace('/^_(menu|text)$/', ' \\1', $params->get('moduleclass_sfx')) );

	$cOpen = isset($attribs['scrollable']) && $attribs['scrollable'] ? '"><div class="wrapper' : '';
	$cClose = isset($attribs['scrollable']) && $attribs['scrollable'] ? '></div' : ''; ?>
<div class="jezRounded1Img<?php echo $params->get('moduleclass_sfx'); ?>"><div class="inner<?php echo $cOpen; ?> tr"><div class="t"></div>
	<?php if ($module->showtitle) { ?>
	<h3><?php echo $module->title; ?></h3>
	<?php }

	echo $module->content; ?>
</div<?php echo $cClose; ?>><div class="b"><div></div></div></div>
<?php } ?>
