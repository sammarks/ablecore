<?php

/**
 * Drupal Views extension class.
 *
 * @author  Samuel Marks <sam@sammarks.me>
 * @package Able Core
 */

/**
 * ==== BETTER THAN CORE ====
 *
 * This class is better than what core has to offer for the following
 * reasons:
 *
 *  - The views API is powerful, but not chainable. The main advantage
 *    to this class is the chainability.
 *  - Better than views_get_rendered_fields because it is
 *    self-documenting.
 *  - Adds set_filter, get_filter, set_sort and get_sort - functions that
 *    the views API doesn't offer.
 *
 */

namespace Drupal\ablecore;

/**
 * Drupal View Extension Class
 *
 * This class provides helper functions for managing views. Here
 * are a couple of common examples for what can be done with this
 * class:
 *
 *     // Grab the contents of a view.
 *     $data = View::get('view_name');
 *
 *     // OR
 *     $data = View::load('view_name')->data();
 *
 *     // Grab some contents of a specific display of a view.
 *     $data = View::get('view_name', 'display_name');
 *
 *     // OR
 *     $data = View::load('view_name')->set_display('display_name')->data();
 *
 *     // Pass some arguments to that view.
 *     $data = View::get('view_name', 'display_name', 'arg_1', 'arg_2');
 *
 *     // OR
 *     $data = View::load('view_name')
 *         ->set_arguments('arg1/arg2')
 *         ->data();
 *
 * See [the documentation](/docs/php-libraries/using-drupal-views) for more information.
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 * @author  Graham Wheeler <graham@ableengine.com>
 *
 */
class View extends DrupalExtension
{

	/**
	 * The name of the current display.
	 * @var string
	 */
	private $current_display;

	/**
	 * Constructor
	 *
	 * Creates a new View instance.
	 *
	 * @param string $view_name The name of the view.
	 */
	public function __construct($view_name)
	{
		if (($view = views_get_view($view_name))) {
			$this->base = $view;
		} else {
			throw new \Exception('The base view: ' . $view_name . ' does not exist!');
		}
	}

	/**
	 * Get
	 *
	 * Shorthand for `rendered_fields()` - given the `$view_name` and
	 * `$display`, processes the view and returns the rendered fields.
	 *
	 * @param  string $view_name The name of the view to use.
	 * @param  string $display   The name of the display to use. Defaults to 'default'
	 *
	 * @return array             The rendered results of the view.
	 */
	public static function get($view_name, $display = 'default')
	{

		$view = new View($view_name);

		$view = $view->set_display($display);

		$arguments = func_get_args();
		array_shift($arguments); // View Name
		if ($arguments !== null) {
			array_shift($arguments);
		} // Display
		if ($arguments !== null) {
			$view = $view->set_arguments($arguments);
		}

		return $view->data();

	}

	/**
	 * Load
	 *
	 * Opens a view and prepares it for processing. Use this function, and
	 * chain other functions off of this.
	 *
	 * @param  string $view_name The name of the view to use.
	 *
	 * @return View            The view object.
	 */
	public static function load($view_name)
	{
		return new View($view_name);
	}

	/**
	 * Data
	 *
	 * Returns the rendered fields for the current view.
	 *
	 * @return array The rendered fields for the current view.
	 */
	public function data()
	{
		$this->base->set_display($this->current_display);
		$this->pre_execute();
		$this->execute();
		$this->render();

		if (isset($this->style_plugin->rendered_fields)) {
			return $this->style_plugin->rendered_fields;
		} else {
			return array();
		}
	}

	/* ==== NEW FUNCTIONS ==== */

	/**
	 * Get Filter
	 *
	 * Retrieves the filter with the specified $name.
	 *
	 * @param  string $name The name of the filter.
	 *
	 * @return array        The filter.
	 */
	public function get_filter($name)
	{
		if (isset($this->base->display[$this->current_display]->display_options['filters'][$name])) {
			return $this->base->display[$this->current_display]->display_options['filters'][$name];
		} else {
			if (isset($this->base->display['default']->display_options['filters'][$name])) {
				return $this->base->display['default']->display_options['filters'][$name];
			} else {
				return null;
			}
		}
	}

	/**
	 * Set Filter
	 *
	 * Sets the filter to the specified values. There are two
	 * modes for this function: shorthand and complete.
	 *
	 * - **Shorthand** - Sets the operator of the filter
	 *   to `$operator`, and the value of the filter to $value.
	 *
	 * - **Complete** - Sets the filter config array to `$operator`.
	 *
	 * @param string              $name        The name of the filter to modify.
	 * @param mixed               $operator    The operator (or the new filter data in
	 *                                         complete mode)
	 * @param View $value       The View object.
	 */
	public function set_filter($name, $operator, $value = null)
	{
		// Find the matching display.
		$display_name = $this->current_display;
		if (!isset($this->base->display[$display_name]->display_options['filters'][$name])) {
			if (isset($this->base->display['default']->display_options['filters'][$name])) {
				$display_name = 'default';
			} else {
				return $this;
			}
		}

		// Which mode are we in...? Default to shorthand.
		$mode = 'shorthand';

		// If an array is supplied to operator, we assume modifying the entire filter.
		if (is_array($operator) && $value === null) {
			$mode = 'complete';
		}

		switch ($mode) {
			case 'shorthand':
				$this->base->display[$display_name]->display_options['filters'][$name]['operator'] = $operator;
				$this->base->display[$display_name]->display_options['filters'][$name]['value'] = $value;
				break;
			case 'complete':
				$this->base->display[$display_name]->display_options['filters'][$name] = $operator;
				break;
		}

		return $this;
	}

	/**
	 * Add Filter
	 *
	 * Adds the filter with the specified name. The specified filter must not
	 * already exist.
	 *
	 * @param  string $name   The name of the new filter.
	 * @param  array  $config The filter configuration.
	 *
	 * @return View             The view object.
	 */
	public function add_filter($name, $config)
	{
		// Return if the filter already exists. Use set_filter to update
		// existing filters.
		if (isset($this->base->display[$this->current_display]->display_options['filters'][$name]) ||
			isset($this->base->display['default']->display_options['filters'][$name])
		) {
			return $this;
		}

		// Set the filter to the new configuration.
		$this->base->display[$this->current_display]->display_options['filters'][$name] = $config;

		return $this;
	}

	/**
	 * Delete Filter
	 *
	 * Deletes the specified filter.
	 *
	 * @param  string $name The name of the filter to delete.
	 *
	 * @return View       The view object.
	 */
	public function delete_filter($name)
	{
		// Find the matching display.
		$display_name = $this->current_display;
		if (!isset($this->base->display[$display_name]->display_options['filters'][$name])) {
			if (isset($this->base->display['default']->display_options['filters'][$name])) {
				$display_name = 'default';
			} else {
				return $this;
			}
		}

		unset($this->base->display[$display_name]->display_options['filters'][$name]);

		return $this;
	}

	/**
	 * Get Sort
	 *
	 * Retrieves the sort with the specified $name.
	 *
	 * @param  string $name The name of the sort.
	 *
	 * @return array        The sort configuration array.
	 */
	public function get_sort($name)
	{
		// Find the matching display.
		$display_name = $this->current_display;
		if (!isset($this->base->display[$display_name]->display_options['sorts'][$name])) {
			if (isset($this->base->display['default']->display_options['sorts'][$name])) {
				$display_name = 'default';
			} else {
				return null;
			}
		}

		return $this->base->display[$display_name]->display_options['sorts'][$name];
	}

	/**
	 * Set Sort
	 *
	 * Sets the sort to the specified values. There are two
	 * modes for this function: shorthand and complete.
	 *
	 * - **Shorthand** - Sets the order of the sort to `$order`.
	 * - **Complete** - Sets the sort config array to `$order`.
	 *
	 * @param string $name        The name of the sort to modify.
	 * @param mixed  $order       The order (or the new sort data in
	 *                            complete mode)
	 * @param View   $value       The View object.
	 */
	public function set_sort($name, $order)
	{
		// Find the matching display.
		$display_name = $this->current_display;
		if (!isset($this->base->display[$display_name]->display_options['sorts'][$name])) {
			if (isset($this->base->display['default']->display_options['sorts'][$name])) {
				$display_name = 'default';
			} else {
				return $this;
			}
		}

		// Which mode are we in...? Default to shorthand.
		$mode = 'shorthand';

		// If an array is supplied to the order, we assume modifying the
		// entire sort.
		if (is_array($order)) {
			$mode = 'complete';
		}

		switch ($mode) {
			case 'shorthand':
				$this->base->display[$display_name]->display_options['sorts'][$name]['order'] = $order;
				break;
			case 'complete':
				$this->base->display[$display_name]->display_options['sorts'][$name] = $order;
				break;
		}
	}

	/**
	 * Delete Sort
	 *
	 * Deletes the specified sort.
	 *
	 * @param  string $name The name of the sort to delete.
	 *
	 * @return View         The View object, for chainability.
	 */
	public function delete_sort($name)
	{
		// Find the matching display.
		$display_name = $this->current_display;
		if (!isset($this->base->display[$display_name]->display_options['sorts'][$name])) {
			if (isset($this->base->display['default']->display_options['sorts'][$name])) {
				$display_name = 'default';
			} else {
				return $this;
			}
		}

		unset($this->base->display[$display_name]->display_options['sorts'][$name]);

		return $this;
	}

	/**
	 * Set Offset
	 *
	 * Sets the offset of the view.
	 *
	 * @param int $offset The offset.
	 */
	public function set_offset($offset)
	{
		// Find the matching display.
		$display_name = $this->current_display;
		if (!isset($this->base->display[$display_name]->display_options['pager']['options']['offset'])) {
			if (isset($this->base->display['default']->display_options['pager']['options']['offset'])) {
				$display_name = 'default';
			} else {
				return $this;
			}
		}

		$this->base->display[$display_name]->display_options['pager']['options']['offset'] = $offset;
		return $this;
	}

	/**
	 * Set Items Per Page
	 *
	 * Sets the items per page of the view.
	 *
	 * @param int $items_per_page The number of items that should be displayed
	 *                            per page.
	 */
	public function set_items_per_page($items_per_page)
	{
		// Find the matching display.
		$display_name = $this->current_display;
		if (!isset($this->base->display[$display_name]->display_options['pager']['options']['items_per_page'])) {
			if (isset($this->base->display['default']->display_options['pager']['options']['items_per_page'])) {
				$display_name = 'default';
			} else {
				return $this;
			}
		}

		$this->base->display[$display_name]->display_options['pager']['options']['items_per_page'] = $items_per_page;
		return $this;
	}

	/* ==== OVERRIDDEN FUNCTIONS ==== */

	/**
	 * Set Display
	 *
	 * Sets the active display for the current view. Make sure to call
	 * this function before calling any other functions.
	 *
	 * @param string $display The name of the display to use.
	 */
	public function set_display($display)
	{
		$this->current_display = $display;
		return $this;
	}

}
