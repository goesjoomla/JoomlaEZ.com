/*
 * JoomlaEZ.com's JavaScript Tools
 *
 * @package		Text Based Slideshow
 * @version		1.0.0
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
 * @license		Commercial Proprietary
 *
 * Please visit http://www.joomlaez.com/ for more information
 */

/*
Class: jezTextShow
	Text based slideshow class
*/
var jezTextShow = jezSlideshow.extend({
	initialize: function (element, options) {
		this.setOptions({
			// data population option
			itemSelector: "li.jezTextShowItem",

			// control buttons
			controlOrientation: 'horizontal',
			controlPosHoriz: 'bottom',
			controlAlignHoriz: 'center',
			controlPosVert: 'right',
			controlAlignVert: 'middle',

			// index panel
			indexesType: 'numeric',
			jTips: false,
			indexControlRel: 'after',

			indexPosHoriz: 'bottom',
			indexAlignHoriz: 'center',
			indexPosVert: 'right',
			indexAlignVert: 'middle',

			// styling option
			slideshowCssClass: 'jezTextShow'
		}, options);

		// set default options
		this.options.extend = $extend;
		this.options.extend({
			imageSelector: null,
			thumbSelector: null,
			linkSelector: null,
			showIndexInfo: false,
			showIndexButton: false,
			indexFx: false,
			showInfo: false,
			infoFx: false,
			onCreateSlide: this.setContent,
			onCreateIndex: this.setIndex
		});

		// pass options to base slideshow class
		this.parent(element, this.options);

		// preset index scroller option
		if (this.options.showIndex && this.index.indexesScroller)
			this.index.indexesScroller.options.area = 50;

		// set control and index panels position
		if (this.control)
			this.positionPanel('control');

		if (this.options.showIndex)
			this.positionPanel('index');
	},
	setContent: function (i) {
		var content = {};

		if (!$defined(this.contentHeight))
			this.contentHeight = 0;

		// create item title
		if (this.items[i].title != '') {
			new Element(this.options.titleTag, {
				'class': this.options.itemCssClass + 'Title'
			}).setHTML(this.items[i].title).injectInside(this.slides[i]);
		}

		// create item content
		content.elm = new Element('div', {
			'class': this.options.itemCssClass + 'Content'
		}).injectInside(this.slides[i]);

		// IE compatible?
		if (window.ie) {
			content.tmp = document.createElement('div');
			content.tmp.innerHTML = this.items[i].content;
			content.elm.appendChild(content.tmp);
		} else {
			content.elm.setHTML(this.items[i].content);
		}

		// if content has form, stop slideshow when any form element is focused
		if (this.options.autoStop && this.slides[i].getElement('form')) {
			var form = this.slides[i].getElement('form');
			var formEls = form.getElementsBySelector('input, select, textarea, button, a.button');

			for (var j = 0; j < formEls.length; j++) {
				formEls[j].addEvents({
					'click': function () {
						this.stop();
					}.bind(this),
					'focus': function () {
						this.stop();
					}.bind(this)
				});
			}
		}

		// set slideshow item's width
		//this.slides[i].setStyle('width', this.itemsContainer.offsetWidth - (parseInt(this.slides[i].getStyle('margin-left')) + parseInt(this.slides[i].getStyle('margin-right'))));

		// calculate slideshow height
		this.items[i].spacing = parseInt(this.slides[i].getStyle('margin-top')) + parseInt(this.slides[i].getStyle('margin-bottom'));
		if (
			this.options.transitionFx != 'accordion'
			&&
			this.itemsContainer.getStyle('overflow') != 'hidden'
			&&
			this.itemsContainer.getStyle('overflow-y') != 'hidden'
			&&
			(this.slides[i].offsetHeight + this.items[i].spacing) > this.contentHeight
		) {
			this.contentHeight = this.slides[i].offsetHeight + this.items[i].spacing;
			this.itemsContainer.setStyle('height', this.contentHeight);
		}
	},
	setIndex: function (i) {
		// create title
		this.index.thumbs[i].setProperty('title', this.items[i].title);
		if (this.options.indexesType == 'title')
			new Element('span').setHTML(this.items[i].title != '' ? this.items[i].title : i + 1).injectInside(this.index.thumbs[i]);
		else if (this.options.indexesType == 'both')
			new Element('span').setHTML((i + 1) + (this.items[i].title != '' ? '. ' + this.items[i].title : '')).injectInside(this.index.thumbs[i]);
		else {
			new Element('span').setHTML(i + 1).injectInside(this.index.thumbs[i]);

			// use jTips?
			if (this.options.jTips && $defined(Tips)) {
				this.index.thumbs[i].setProperty('title', '::' + this.index.thumbs[i].getProperty('title'));
				new Tips(this.index.thumbs[i]);
			}
		}
	},
	start: function () {
		this.parent();
		this.updateHeight(true);
	},
	changeItem: function (n, ignoreFx) {
		this.parent(n, ignoreFx);
		this.updateHeight(ignoreFx);
	},
	scrollPage: function (item) {
		var el;

		// browser bookmarkable / navigable enabled?
		if (this.options.navigableBrowsing) {
			// determine element to scroll to
			if (
				this.control
				&&
				this.options.showIndex
				&&
				this.options.controlOrientation == 'horizontal'
				&&
				this.options.indexesOrientation == 'horizontal'
				&&
				this.options.controlPosHoriz == 'top'
				&&
				this.options.indexPosHoriz == 'top'
			) {
				if (this.options.indexControlRel == 'after')
					el = this.control.panel;
				else
					el = this.index.panel;
			} else if (this.control && this.options.controlOrientation == 'horizontal' && this.options.controlPosHoriz == 'top')
				el = this.control.panel;
			else if (this.options.showIndex && this.options.indexesOrientation == 'horizontal' && this.options.indexPosHoriz == 'top')
				el = this.index.panel;
			else
				el = this.slides.item(item).element;

			// start scrolling
			this.scroller.stop().toElement(el);
		}
	},
	updateHeight: function (ignoreFx) {
		if (
			this.options.transitionFx != 'accordion'
			&&
			(!this.control || (this.options.controlOrientation == 'horizontal' && this.options.controlPosHoriz == 'top'))
			&&
			(!this.options.showIndex || (this.options.indexesOrientation == 'horizontal' && this.options.indexPosHoriz == 'top'))
		) {
			var item = this.slides.item(this.curItem).element;

			// get current slideshow item's height
			item.setProperty('_display', item.getStyle('display')).setStyle('display', 'block');
			var newHeight = item.offsetHeight + this.items[this.curItem].spacing;
			item.setStyle('display', item.getProperty('_display')).removeProperty('_display');

			// reset slideshow items container's height
			if (parseInt(this.itemsContainer.getStyle('height')) != newHeight) {
				if (!ignoreFx) {
					if (!$defined(this.itemsContainerFx)) {
						this.itemsContainerFx = new Fx.Styles(this.itemsContainer, {
							duration: this.options.fxDuration
						});
					}

					this.itemsContainerFx.stop().start({
						height: newHeight
					});
				} else
					this.itemsContainer.setStyle('height', newHeight);
			}
		}
	},
	positionPanel: function (which) {
		var spacing;

		// set orientation to control panel styling class
		if (which == 'control')
			this.control.panel.setProperty('class', this.options.controlCssClass + '-' + this.options.controlOrientation);

		if (this.options[(which == 'control' ? 'control' : 'indexes') + 'Orientation'] == 'vertical') {
			// set panel position
			this[which].panel.addClass(this.options[which + 'PosVert']);
			this.itemsContainer.addClass(this.options[which + 'PosVert'] == 'left' ? 'right' : 'left');

			if (this.options[which + 'PosVert'] == 'left')
				this[which].panel.injectBefore(this.itemsContainer);

			// update slideshow items container's width
			spacing = parseInt(this.itemsContainer.getStyle('margin-left')) + parseInt(this.itemsContainer.getStyle('margin-right'));
			spacing += parseInt(this[which].panel.getStyle('margin-left')) + parseInt(this[which].panel.getStyle('margin-right'));
			this.itemsContainer.setStyle('width', this.itemsContainer.offsetWidth - this[which].panel.offsetWidth - spacing);

			// get longest slideshow item in height
			var contentHeight = 0, item;
			for (var i = 0; i < this.items.length; i++) {
				item = this.slides.item(i).element;
				item.setProperty('_display', item.getStyle('display')).setStyle('display', 'block');
				if (item.offsetHeight > contentHeight)
					contentHeight = item.offsetHeight + this.items[i].spacing;
				item.setStyle('display', item.getProperty('_display')).removeProperty('_display');
			}

			// update slideshow items container's height
			this.itemsContainer.setStyle('height', contentHeight);

			// calculate panel height
			if (which == 'control')
				var panelHeight = this.control.panel.offsetHeight;
			else {
				var panelHeight = $pick(this.index.scroller.element, this.index.scroller).offsetHeight > this.index.indexes.offsetHeight ? this.index.indexes.offsetHeight : $pick(this.index.scroller.element, this.index.scroller).offsetHeight;
				if (this.index.scroller.element && this.options.showScrollingButtons)
					panelHeight += parseInt(this.index.wrapper.getStyle('margin-top')) + parseInt(this.index.wrapper.getStyle('margin-bottom'));
			}

			// set panel height
			if (panelHeight != this[which].panel.offsetHeight)
				this[which].panel.setStyle('height', panelHeight);

			// set panel alignment
			spacing = this.itemsContainer.offsetHeight - panelHeight;
			if (spacing > 0) {
				if (this.options[which + 'AlignVert'] == 'top') {
					this[which].panel.setStyles({
						'margin-top': 0,
						'margin-bottom': spacing
					});
				} else if (this.options[which + 'AlignVert'] == 'bottom') {
					this[which].panel.setStyles({
						'margin-bottom': 0,
						'margin-top': spacing
					});
				} else {
					spacing = parseInt(spacing / 2);
					this[which].panel.setStyles({
						'margin-top': spacing,
						'margin-bottom': spacing
					});
				}
			}
		} else {
			// set panel position
			this[which].panel.addClass(this.options[which + 'PosHoriz']);
			this.itemsContainer.addClass(this.options[which + 'PosHoriz'] == 'top' ? 'bottom' : 'top');

			// calculate panel width
			if (which == 'control')
				var panelWidth = this.control.panel.offsetWidth;
			else {
				var panelWidth = $pick(this.index.scroller.element, this.index.scroller).offsetWidth > this.index.indexes.offsetWidth ? this.index.indexes.offsetWidth : $pick(this.index.scroller.element, this.index.scroller).offsetWidth;
				if (this.index.scroller.element && this.options.showScrollingButtons)
					panelWidth += parseInt(this.index.wrapper.getStyle('margin-left')) + parseInt(this.index.wrapper.getStyle('margin-right'));
			}

			if (this.options[which + 'PosHoriz'] == 'top')
				this[which].panel.injectBefore(this.itemsContainer);

			// set panel width
			if (panelWidth != this[which].panel.offsetWidth)
				this[which].panel.setStyle('width', panelWidth);

			// set panel alignment
			spacing = this.itemsContainer.offsetWidth - panelWidth;
			if (spacing > 0) {
				if (this.options[which + 'AlignHoriz'] == 'left') {
					this[which].panel.setStyles({
						'margin-left': 0,
						'margin-right': spacing
					});
				} else if (this.options[which + 'AlignHoriz'] == 'right') {
					this[which].panel.setStyles({
						'margin-right': 0,
						'margin-left': spacing
					});
				} else {
					spacing = parseInt(spacing / 2);
					this[which].panel.setStyles({
						'margin-left': spacing,
						'margin-right': spacing
					});
				}
			}
		}

		// do we have both panel visible in same edge
		if (
			which == 'index'
			&&
			this.control
			&&
			this.options.indexesOrientation == this.options.controlOrientation
			&&
			this.options[this.options.indexesOrientation == 'vertical' ? 'indexPosVert' : 'indexPosHoriz'] == this.options[this.options.controlOrientation == 'vertical' ? 'controlPosVert' : 'controlPosHoriz']
		) {
			if (this.options.indexControlRel == 'before')
				this.control.panel.injectAfter(this.index.panel);
		}
	}
});
