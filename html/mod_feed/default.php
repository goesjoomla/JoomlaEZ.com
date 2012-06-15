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

if ( $feed != false ) :
	//image handling
	$iUrl 	= isset($feed->image->url) ? $feed->image->url : null;
	$iTitle = isset($feed->image->title) ? $feed->image->title : null;

	$actualItems = count( $feed->items );
	$setItems = $params->get('rssitems', 5);

	if ($setItems > $actualItems)
		$totalItems = $actualItems;
	else
		$totalItems = $setItems;

	$titleTag = (!is_null( $feed->title ) && $params->get('rsstitle', 1)) ? 'h5' : 'h4';
?>
<div class="mod_feed<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<?php if (!is_null( $feed->title ) && $params->get('rsstitle', 1)) : // feed description ?>
	<h4>
		<a href="<?php echo str_replace( '&', '&amp', $feed->link ); ?>" title="<?php echo $feed->title; ?>" target="_blank"><?php echo $feed->title; ?></a>
	</h4>
	<?php endif;

	if ($params->get('rssdesc', 1) || ($params->get('rssimage', 1) && $iUrl)) : ?>
	<p class="info clearfix">
		<?php if ($params->get('rssdesc', 1)) // feed description
			echo $feed->description;

		if ($params->get('rssimage', 1) && $iUrl) : // feed image ?>
		<image src="<?php echo $iUrl; ?>" alt="<?php echo @$iTitle; ?>" class="right" />
		<?php endif; ?>
	</p>
	<?php endif; ?>
	<ul class="listing">
	<?php $words = $params->def('word_count', 0);
	for ($j = 0; $j < $totalItems; $j++) :
		$currItem = & $feed->items[$j]; ?>
	<li>
		<?php if (!is_null($currItem->get_link())) : // item title
			echo "<$titleTag>"; ?>
		<a href="<?php echo $currItem->get_link(); ?>" title="<?php echo $currItem->get_title(); ?>" target="_blank"><?php echo $currItem->get_title(); ?></a>
		<?php echo "</$titleTag>";
		endif;

		if ($params->get('rssitemdesc', 1)) : // item description
			$text = $currItem->get_description();
			$text = str_replace('&apos;', "'", $text);

			if ($words) { // word limit check
				$texts = explode(' ', $text);
				$count = count($texts);
				if ($count > $words) {
					$text = '';
					for ($i = 0; $i < $words; $i ++)
						$text .= ' '.$texts[$i];

					$text .= '...';
				}
			}
		?>
		<p class="intro">
			<?php echo $text; ?>
		</p>
		<?php endif;

		if ($j + 1 < $totalItems) : // article separator ?>
		<span class="article_separator">&nbsp;</span>
		<?php endif; ?>
	</li>
	<?php endfor; ?>
	</ul>
</div>
<?php endif; ?>
