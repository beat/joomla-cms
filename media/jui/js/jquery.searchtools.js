;(function ($, window, document, undefined) {

	// Create the defaults once
	var pluginName = "searchtools";

	var defaults = {
		clearBtnSelector        : '.js-stools-btn-clear',
		searchBtnSelector       : '.js-stools-btn-search',
		filterBtnSelector       : '.js-stools-btn-filter',
		containerSelector       : '.js-stools-container',
		filterContainerSelector : '.js-stools-container-filter',
		orderBtnSelector        : '.js-stools-btn-order',
		orderContainerSelector  : '.js-stools-container-order',
		searchInputSelector     : '.js-stools-search-string',
		filtersApplied          : false,
		searchString            : null
	};

	// The actual plugin constructor
	function Plugin(element, options) {
		this.element = element;
		this.options = $.extend({}, defaults, options);
		this._defaults = defaults;

		// Initialise selectors
		this.theForm        = this.element;

		// Filters
		this.filterButton    = $(this.options.filterBtnSelector);
		this.filterContainer = $(this.options.filterContainerSelector);

		// Orders
		this.orderButton     = $(this.options.orderBtnSelector);
		this.orderContainer  = $(this.options.orderContainerSelector);

		// Global container
		this.container       = $(this.options.containerSelector);

		// Search
		this.searchButton    = $(this.options.searchBtnSelector);
		this.searchInput     = $(this.options.searchInputSelector);

		this.searchString = $(this.options.searchString);

		this.clearButton     = $(this.options.clearBtnSelector);

		// Selector values
		this._name = pluginName;

		this.init();
	}

	Plugin.prototype = {
		init: function () {
			var self = this;

			self.filterButton.click(function(e) {
				self.toggleFilters();
				e.stopPropagation();
				e.preventDefault();
			});

			self.orderButton.click(function(e) {
				self.toggleOrder();
				e.stopPropagation();
				e.preventDefault();
			});

			// Do we need to add to mark filter as enabled?
			self.getFilters().each(function(i, element) {
				self.checkFilter(element);
				$(element).change(function () {
					self.checkFilter(element);
				});
			});

			self.clearButton.click(function(e) {
				self.clear();
			});

			if (this.options.filtersApplied) {
				self.toggleFilters();
			}
		},
		checkFilter: function (element) {
			var self = this;

			var option = $(element).find('option:selected');
			if (option.val() != '') {
				self.activeFilter(element);
			} else {
				self.deactiveFilter(element);
			}
		},
		clear: function () {
			var self = this;

			self.getFilters().each(function(i, element) {
				$(element).val('');
				self.checkFilter(element);
				$(element).trigger('liszt:updated');
			});

			self.searchInput.val('');
			self.theForm.submit();
		},
		activeFilter: function (element) {
			var self = this;

			$(element).addClass('active');
			var chosenId = '#' + $(element).attr('id') + '_chzn';
			$(chosenId).addClass('active');
		},
		deactiveFilter: function (element) {
			var self = this;

			$(element).removeClass('active');
			var chosenId = '#' + $(element).attr('id') + '_chzn';
			$(chosenId).removeClass('active');
		},
		getFilters: function () {
			var self = this;

			return self.container.find('select');
		},
		hideContainer: function () {
			var self = this;

			self.container.hide('fast');
			self.container.removeClass('shown');
		},
		showContainer: function () {
			var self = this;

			self.container.show('fast');
			self.container.addClass('shown');
		},
		hideFilters: function () {
			var self = this;

			self.filterContainer.hide('fast');
			self.filterContainer.removeClass('shown');
		},
		showFilters: function () {
			var self = this;

			self.filterContainer.show('fast');
			self.filterContainer.addClass('shown');
		},
		toggleFilters: function () {
			var self = this;

			if (self.container.hasClass('shown')) {
				self.hideContainer();
				self.filterButton.removeClass('btn-primary');
			} else {
				self.showContainer();
				self.filterButton.addClass('btn-primary');
			}
		},
		hideOrder: function () {
			var self = this;

			self.orderContainer.hide('fast');
			self.orderContainer.removeClass('shown');
		},
		showOrder: function () {
			var self = this;

			self.orderContainer.show('fast');
			self.orderContainer.addClass('shown');
		},
		toggleOrder: function () {
			var self = this;

			if (self.container.hasClass('shown')) {
				self.hideOrder();
				self.orderButton.removeClass('btn-inverse');
			} else {
				self.showOrder();
				self.orderButton.addClass('btn-inverse');
			}
		}
	};

	// A really lightweight plugin wrapper around the constructor,
	// preventing against multiple instantiations
	$.fn[pluginName] = function (options) {
		return this.each(function () {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName, new Plugin(this, options));
			}
		});
	};

})(jQuery, window, document);
