/*
 * JoomlaEZ.com's JavaScript Tools
 *
 * @package		Base Effects
 * @version		1.0.0
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
 * @license		Commercial Proprietary
 *
 * Please visit http://www.joomlaez.com/ for more information
 */

/*
Class: jezFxStyle
	Custom class to create and switch over effects for a HTML element as of its state such as: onMouseOver/onMouseOut, onMouseDown/onMouseUp, etc.
*/
var jezFxStyle = {
	initialize: function(element, options, switcher) {
		this.setOptions({
			addEventHandler: true,
			activateOn: 'mouseenter',
			deactivateOn: 'mouseleave',
			preset: 'inactive',
			activeCss: {},
			inactiveCss: {},
			activeFx: {},
			inactiveFx: {}
		}, options);

		this.element = $(element);
		if (this.element) {
			// do we have switcher?
			this.switcher = $(switcher);
			if (!this.switcher)
				this.switcher = this.element;

			this.construct();
		}
	},
	construct: function() {
		// add event handler to activate/deactivate effect
		if (this.options.addEventHandler) {
			if (this.options.activateOn == this.options.deactivateOn) {
				// use same event to toggle effects, need to save current status
				this.active = this.options.preset == 'active' ? true : false;
				this.switcher.addEvent(this.options.activateOn, function() {
					if (this.active) {
						this.deactivate();
						this.active = false;
					} else {
						this.activate();
						this.active = true;
					}
				}.bind(this));
			} else {
				this.switcher.addEvent(this.options.activateOn, function() {
					this.activate();
				}.bind(this));
				this.switcher.addEvent(this.options.deactivateOn, function() {
					this.deactivate();
				}.bind(this));
			}
		}

		// create effect object
		this.fxStyles = new Fx.Styles(this.element, {});

		// preset styles
		this.set(this.options.preset);

		// copy $extend to options arguments of the Fx.Styles object so we can update it later
		this.fxStyles.options.extend = $extend;
	},
	set: function(state) {
		if (state == 'active') {
			this.fireEvent('onActivate');
			this.fxStyles.set(this.options.activeCss);
			this.fireEvent('onActivated');
		} else {
			this.fireEvent('onDeactivate');
			this.fxStyles.set(this.options.inactiveCss);
			this.fireEvent('onDeactivated');
		}
	},
	activate: function() {
		this.fireEvent('onActivate');

		// set active effect arguments then start the effect
		this.fxStyles.options.extend(this.options.activeFx);
		this.fxStyles.stop().start(this.options.activeCss);

		this.fireEvent('onActivated', null, this.fxStyles.options.duration);

		// return the Fx.Styles object so we can chain other function
		return this.fxStyles;
	},
	deactivate: function() {
		this.fireEvent('onDeactivate');

		// set active effect arguments then start the effect
		this.fxStyles.options.extend(this.options.inactiveFx);
		this.fxStyles.stop().start(this.options.inactiveCss);

		this.fireEvent('onDeactivated', null, this.fxStyles.options.duration);

		// return the Fx.Styles object so we can chain other function
		return this.fxStyles;
	}
}
jezFxStyle = new Class(jezFxStyle);
jezFxStyle.implement(new Options, new Events);

/*
Class: jezFxStyles
	Custom class to create jezFxStyle for many HTML elements at a time.
*/
jezFxStyles = new Class({
	initialize: function(elements, options, separated, switchers) {
		this.options = options;
		this.separated = separated ? true : false;
		this.switchers = switchers;
		this.objects = [];

		// create jezFxStyle object for all passed in elements
		this.last = 0;
		elements.each(function(e, i) {
			var option, switcher;
			if (this.separated) {
				// prepare options
				if ($defined(this.options[i])) {
					option = this.options[i];
					this.last = i;
				} else
					option = this.options[this.last];

				// prepare switcher
				if (!$defined(this.switchers[i])) {
					option.addEventHandler = false;
					switcher = null;
				} else
					switcher = this.switchers[i];

				// create jezFxStyle object
				this.objects.extend([new jezFxStyle(e, option, switcher)]);
			} else
				this.objects.extend([new jezFxStyle(e, this.options)]);
		}, this);
	},
	item: function(i) {
		return this.objects[i];
	}
});

/*
transition effects initializing
*/
jezFxTransInit = new Abstract ({
	fade: function(els, duration) {
		var objs = new jezFxStyles(els, {
			addEventHandler: false,
			activateOn: null,
			deactivateOn: null,
			preset: 'inactive',
			activeCss: {'opacity': 1},
			inactiveCss: {'opacity': 0},
			activeFx: {'transition': Fx.Transitions.linear, 'duration': duration},
			inactiveFx: {'transition': Fx.Transitions.linear, 'duration': duration},
			onActivate: function() { this.element.setStyle('display', 'block'); },
			onDeactivated: function() { this.element.setStyle('display', 'none'); }
		});

		return objs;
	},
	crossfade: function(els, duration) {
		return jezFxTransInit.fade(els, duration);
	},
	fadebg: function(els, duration) {
		return jezFxTransInit.fade(els, duration);
	},
	accordion: function(els, duration) {
		var switchers = [], options = [];
		for (var i = 0; i < els.length; i++) {
			els[i].addClass('accordion');

			// get switchers
			switchers[i] = els[i].getFirst();
			if (!(/h[1-6]/.test(switchers[i].getTag()))) {
				while (switchers[i].getNext()) {
					if (/h[1-6]/.test(switchers[i].getTag()))
						break;
				}
			}

			// create switchers if not exists
			if (!(/h[1-6]/.test(switchers[i].getTag())))
				switchers[i] = new Element('h3', {'class': 'switcher'}).setHTML('&nbsp;').injectTop(els[i]);
			else
				switchers[i].addClass('switcher');

			// (re)set position to relative and overflow to hidden
			if (els[i].getStyle('position') == 'absolute')
				els[i].setStyle('position', 'relative');

			// create options
			options[i] = {
				addEventHandler: true,
				activateOn: 'click',
				deactivateOn: 'click',
				preset: 'inactive',
				activeCss: {'height': els[i].offsetHeight},
				inactiveCss: {'height': switchers[i].offsetHeight},
				activeFx: {'transition': Fx.Transitions.expoIn, 'duration': duration},
				inactiveFx: {'transition': Fx.Transitions.expoOut, 'duration': duration},
				onActivated: function() { this.switcher.addClass('active'); this.element.setStyle('opacity', 1); },
				onDeactivated: function() { this.switcher.removeClass('active'); this.element.setStyles({'opacity': 1, 'overflow': 'hidden'}); }
			}
		}

		var objs = new jezFxStyles(els, options, true, switchers);

		return objs;
	}
});

/*
transition effects processing
*/
jezFxTransProcess = new Abstract ({
	fade: function(oldFx, newFx) {
		newFx.set('active');
		return oldFx.deactivate();
	},
	crossfade: function(oldFx, newFx) {
		oldFx.deactivate();
		return newFx.activate();
	},
	fadebg: function(oldFx, newFx) {
		return oldFx.deactivate().chain(newFx.activate.bind(newFx));
	},
	accordion: function(oldFx, newFx) {
		return jezFxTransProcess.crossfade(oldFx, newFx);
	}
});