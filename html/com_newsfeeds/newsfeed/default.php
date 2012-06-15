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
<div id="jezComOutput" class="newsfeed<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr" style="direction: <?php echo $this->newsfeed->rtl ? 'rtl' :'ltr'; ?>; text-align: <?php echo $this->newsfeed->rtl ? 'right' :'left'; ?>">
<h2 class="componentheading">
	<?php if ($this->params->get('page_title') != '') : ?>
	<span class="<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?> /</span>
	<?php
	endif;
	$cTitle = str_replace('&apos;', "'", $this->newsfeed->channel['title']);
	?>
	<a href="<?php echo $this->newsfeed->channel['link']; ?>" title="<?php echo $cTitle; ?>" target="_blank">
		<?php echo $cTitle; ?>
	</a>
</h2>
<?php if (
	$this->params->get( 'show_feed_description' )
	||
	(isset($this->newsfeed->image['url']) && isset($this->newsfeed->image['title']) && $this->params->get( 'show_feed_image' ))
) : ?>
<p class="info clearfix">
	<?php
	if ($this->params->get( 'show_feed_description' ))
		echo str_replace('&apos;', "'", $this->newsfeed->channel['description']);

	if ( isset($this->newsfeed->image['url']) && isset($this->newsfeed->image['title']) && $this->params->get( 'show_feed_image' ) ) : ?>
	<img src="<?php echo $this->newsfeed->image['url']; ?>" alt="<?php echo $this->newsfeed->image['title']; ?>" class="right" />
	<?php endif; ?>
</p>
<?php endif; ?>
<ul class="listing">
<?php for ($i = 0; $i < count($this->newsfeed->items); $i++) :
	$item = $this->newsfeed->items[$i]; ?>
<li class="tr">
	<?php if ( !is_null( $item->get_link() ) ) : ?>
	<h3><a href="<?php echo $item->get_link(); ?>" title="<?php echo $item->get_title(); ?>" target="_blank"><?php echo $item->get_title(); ?></a></h3>
	<?php endif;

	if ($this->params->get( 'show_item_description' ) && $item->get_description()) : ?>
	<p class="intro">
		<?php
		$text = $this->limitText($item->get_description(), $this->params->get( 'feed_word_count' ));
		echo str_replace('&apos;', "'", $text);
		?>
	</p>
	<?php endif;

	if ($i + 1 < count($this->newsfeed->items)) : ?>
	<span class="article_separator">&nbsp;</span>
	<?php endif; ?>
</li>
<?php endfor; ?>
</ul>
</div>
