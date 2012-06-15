/*
 * JoomlaEZ.com's JavaScript Tools
 *
 * @package		Menu Effects
 * @version		1.0.0
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
 * @license		Commercial Proprietary
 *
 * Please visit http://www.joomlaez.com/ for more information
 */

/*
Class: jezMenuFx
	Custom class to create dropdown / flyout effects for a (Son of) Suckerfish menu.
*/
var jezMenuFx = {
	initialize: function(element, options) {
		this.setOptions({
			rootItemSelector: 'li.level0',
				// css selector to get items of root menu
			firstSubSelector: 'ul.level1',
				// css selector to get first-level submenus
			deeperSubSelector: 'ul.level1 ul',
				// css selector to get submenus from second-level
			subItemSelector: 'li.parent li',
				// css selector to get items of submenus

			fxDuration: 500,
				// duration, in miliseconds, for every effect to complete
			rootItemActiveCss: {},
				// style to apply to item of root menu when hovered
			rootItemInactiveCss: {},
				// style to apply to item of root menu when lost hover
			rootItemActiveFx: {},
				// effect parameters when item of root menu is hovered
			rootItemInactiveFx: {},
				// effect parameters when item of root menu is lost hover

			firstSubDirection: 'both',
				// direction to apply to dropdown / flyout effect of first level submenu, options: vertical | horizontal | both | manual
				// - vertical: the script will automatically add extra style to firstSubActiveCss and firstSubInactiveCss to increase the
				//             submenu's height from 0 to its original value when activating and vice versa when deactivating
				// - horizontal: the script will automatically add extra style to firstSubActiveCss and firstSubInactiveCss to increase the
				//               submenu's width from 0 to its original value when activating and vice versa when deactivating
				// - both: the script will automatically add extra style to firstSubActiveCss and firstSubInactiveCss to increase the
				//         submenu's width and height from 0 to their original values when activating and vice versa when deactivating
				// - manual: the script will leave firstSubActiveCss and firstSubInactiveCss as passed in
			firstSubActiveCss: {},
				// style to apply to first level submenu when active
			firstSubInactiveCss: {},
				// style to apply to first level submenu when inactive
			firstSubActiveFx: {transition: Fx.Transitions.expoIn},
				// effect parameters when first level submenu is active
			firstSubInactiveFx: {transition: Fx.Transitions.expoOut},
				// effect parameters when first level submenu is inactive

			deeperSubDirection: 'both',
				// direction to apply to dropdown / flyout effect of submenu from second level, options: vertical | horizontal | both | manual
				// - vertical: the script will automatically add extra style to deeperSubActiveCss and deeperSubInactiveCss to increase the
				//             submenu's height from 0 to its original value when activating and vice versa when deactivating
				// - horizontal: the script will automatically add extra style to deeperSubActiveCss and deeperSubInactiveCss to increase the
				//               submenu's width from 0 to its original value when activating and vice versa when deactivating
				// - both: the script will automatically add extra style to deeperSubActiveCss and deeperSubInactiveCss to increase the
				//         submenu's width and height from 0 to their original values when activating and vice versa when deactivating
				// - manual: the script will leave deeperSubActiveCss and deeperSubInactiveCss as passed in
			deeperSubActiveCss: {},
				// style to apply to submenu from second level when active
			deeperSubInactiveCss: {},
				// style to apply to submenu from second level when inactive
			deeperSubActiveFx: {transition: Fx.Transitions.expoIn},
				// effect parameters when submenu from second level is active
			deeperSubInactiveFx: {transition: Fx.Transitions.expoOut},
				// effect parameters when submenu from second level is inactive

			subItemActiveCss: {},
				// style to apply to item of submenu when hovered
			subItemInactiveCss: {},
				// style to apply to item of submenu when lost hover
			subItemActiveFx: {},
				// effect parameters when item of submenu is hovered
			subItemInactiveFx: {},
				// effect parameters when item of submenu is lost hover

			skipFxFor: {'id': 'current', 'class': 'active'}
				// skip effect for menu items matching certain attributes
		}, options);

		// store menu container
		this.element = element;

		// construct menu effect
		this.construct();
	},
	construct: function() {
		// get all menu items
		var rootItems = this.element.getElements(this.options.rootItemSelector);
		var subItems = this.element.getElements(this.options.subItemSelector);

		// get submenus
		var subMenus = {};
		subMenus.first = this.element.getElements(this.options.firstSubSelector);
		subMenus.deeper = this.element.getElements(this.options.deeperSubSelector);

		// create mouse enter/leave switcher effect for items of root menu
		$extend(this.options.rootItemActiveFx, {duration: this.options.fxDuration});
		$extend(this.options.rootItemInactiveFx, {duration: this.options.fxDuration});
		new jezFxStyles(rootItems, {
			activeCss: this.options.rootItemActiveCss,
			inactiveCss: this.options.rootItemInactiveCss,
			activeFx: this.options.rootItemActiveFx,
			inactiveFx: this.options.rootItemInactiveFx
		});

		// create mouse enter/leave switcher effect for items of submenus
		$extend(this.options.subItemActiveFx, {duration: this.options.fxDuration});
		$extend(this.options.subItemInactiveFx, {duration: this.options.fxDuration});
		new jezFxStyles(subItems, {
			activeCss: this.options.subItemActiveCss,
			inactiveCss: this.options.subItemInactiveCss,
			activeFx: this.options.subItemActiveFx,
			inactiveFx: this.options.subItemInactiveFx
		});

		// create mouse enter/leave switcher effect for submenus
		$each(subMenus, function(data, type) {
			for (var i = 0; i < data.length; i++) {
				if (this.options[type + 'SubDirection'] != 'manual') {
					// create appropriate css style for selected direction
					switch (this.options[type + 'SubDirection']) {
						case 'vertical':
							$extend(this.options[type + 'SubActiveCss'], {'height': data[i].offsetHeight - (parseInt(data[i].getStyle('border-top')) + parseInt(data[i].getStyle('padding-top')) + parseInt(data[i].getStyle('padding-bottom')) + parseInt(data[i].getStyle('border-bottom')))});
							$extend(this.options[type + 'SubInactiveCss'], {'height': 0});
							break;
						case 'horizontal':
							$extend(this.options[type + 'SubActiveCss'], {'width': data[i].offsetWidth - (parseInt(data[i].getStyle('border-left')) + parseInt(data[i].getStyle('padding-left')) + parseInt(data[i].getStyle('padding-right')) + parseInt(data[i].getStyle('border-right')))});
							$extend(this.options[type + 'SubInactiveCss'], {'width': 0});
							break;
						case 'both':
						default:
							$extend(this.options[type + 'SubActiveCss'], {
								'width': data[i].offsetWidth - (parseInt(data[i].getStyle('border-left')) + parseInt(data[i].getStyle('padding-left')) + parseInt(data[i].getStyle('padding-right')) + parseInt(data[i].getStyle('border-right'))),
								'height': data[i].offsetHeight - (parseInt(data[i].getStyle('border-top')) + parseInt(data[i].getStyle('padding-top')) + parseInt(data[i].getStyle('padding-bottom')) + parseInt(data[i].getStyle('border-bottom')))
							});
							$extend(this.options[type + 'SubInactiveCss'], {'width': 0, 'height': 0});
							break;
					}
				}

				// create mouse enter/leave switcher effect for this submenu
				$extend(this.options[type + 'SubActiveFx'], {duration: this.options.fxDuration});
				$extend(this.options[type + 'SubInactiveFx'], {duration: this.options.fxDuration});
				var subMenu = new jezFxStyle(data[i], {
					activeCss: this.options[type + 'SubActiveCss'],
					activeFx: this.options[type + 'SubActiveFx'],
					onActivate: function() { this.setStyles({'left': 'auto', 'overflow': 'hidden'}); }.bind(data[i]),
					onActivated: function() { this.setStyle('overflow', 'visible'); }.bind(data[i]),
					inactiveCss: this.options[type + 'SubInactiveCss'],
					inactiveFx: this.options[type + 'SubInactiveFx'],
					onDeactivate: function() { this.setStyles({'left': 'auto', 'overflow': 'hidden'}); }.bind(data[i]),
					onDeactivated: function() { this.setStyle('left', '-999em'); }.bind(data[i])
				}, data[i].getParent());
			}
		}, this);
	}
};
jezMenuFx = new Class(jezMenuFx);
jezMenuFx.implement(new Options);