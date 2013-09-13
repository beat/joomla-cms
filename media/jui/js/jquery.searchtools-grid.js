;(function ($, window, document, undefined) {

	// Create the defaults once
	var pluginName = "stoolsGrid";

	var defaults = {
		formSelector           : '#adminForm',
		orderingSelector       : '.js-ordering',
		directionSelector      : '.js-direction',
		activeOrdering         : '',
		activeDirection        : 'asc',
		orderColSelector       : '.js-order-col',
		orderingFieldSelector  : '#list_ordering',
		directionFieldSelector : '#list_direction'
	};

	// The actual plugin constructor
	function Plugin(element, options) {
		this.element = element;
		this.options = $.extend({}, defaults, options);
		this._defaults = defaults;

		// Initialise selectors
		this.theForm        = this.element;
		this.orderCols      = $(this.options.orderColSelector);
		this.orderDir       = $(this.options.orderDirSelector);
		this.orderField     = $(this.options.orderingFieldSelector);
		this.directionField = $(this.options.directionFieldSelector);

		// Selector values
		this._name = pluginName;

		this.init();
	}

	Plugin.prototype = {
		init: function () {
			var main = this;

			console.log(main.orderField);

			// Check/create ordering field
			this.createOrderingField();

			// Check/create direction field
			this.createDirectionField();

			this.orderCols.click(function() {

				// Order to set
				var newOrderCol = $(this).attr('data-order');
				var newDirection = 'ASC';

				// The data-order attrib is required
				if (newOrderCol.length)
				{
					if (newOrderCol !== main.orderCol)
					{
						// Update the order field
						main.updateFieldValue(main.orderField, newOrderCol);
						main.updateFieldValue(main.directionField, 'ASC');
					}
					else
					{
						main.toggleDirection();
					}

					main.theForm.submit();
				}

			});
		},
		toggleDirection: function () {

			var newDirection = 'ASC';

			if (this.direction.toUpperCase() == 'ASC')
			{
				newDirection = 'DESC';
			}

			this.updateFieldValue(this.directionField, newDirection);
		},
		createOrderingField: function () {

			var main = this;

			if (!this.orderField.length)
			{
				this.orderField = $('<input>').attr({
				    type: 'hidden',
				    id: 'js-order-field',
				    class: 'js-order-field',
				    name: main.options.orderingFieldName
				});

				this.orderField.appendTo(this.theForm);
			}

			// Add missing columns to the order select
			if (this.orderField.is('select'))
			{
				this.orderCols.each(function(){
					var value = $(this).attr('data-order');
					var name = $(this).attr('data-name');

					if (value.length)
					{
						var option = main.findOption(main.orderField, value);

						if (!option.length)
						{
							var option = $('<option>');
							option.text(name).val(value);

							// If it is the active option select it
							if ($(this).hasClass('active'))
							{
								option.attr('selected', 'selected');
							}

							// Append the option an repopulate the chosen field
							main.orderField.append(option);
						}
					}

				});

				this.orderField.trigger('liszt:updated');
			}

			this.orderCol  = this.orderField.val();
		},
		createDirectionField: function () {

			if (!this.orderField.length)
			{
				this.orderField = $('<input>').attr({
				    type: 'hidden',
				    id: 'js-direction-field',
				    class: 'js-direction-field',
				    name: 'filter_order',
				    value: 'ASC'
				});

				this.orderField.appendTo(this.theForm);
			}

			this.direction = this.directionField.val();
		},
		updateFieldValue: function (field, newValue) {

			var type = field.attr('type');

			if (type === 'hidden' || type === 'text')
			{
				field.attr('value', newValue);
			}
			else if (field.is('select'))
			{
				// Select the option result
				var desiredOption = field.find('option').filter(function () { return $(this).val() == newValue; });

				if (desiredOption.length)
				{
					desiredOption.attr('selected', 'selected');
				}
				// If the option does not exist create it on the fly
				else
				{
					var option = $('<option>');
					option.text(newValue).val(newValue);
					option.attr('selected','selected');

					// Append the option an repopulate the chosen field
					field.append(option);
				}
				// Trigger the chosen update
				field.trigger('liszt:updated');

			}
		},
		findOption: function(select, value) {
			return select.find('option').filter(function () { return $(this).val() == value; });
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