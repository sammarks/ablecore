<?php

/**
 * The menu helper for Drupal applications.
 *
 * This class is better than what core has to offer for the following
 * reasons:
 *
 * - Allows more customization options (id and class) without using
 *   theme() functions.
 * - Chainability.
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace AbleCore;

/**
 * Menu
 *
 * This is a menu helper for Drupal applications. It provides several
 * useful functions for rendering and displaying custom menus.
 *
 * This class is better than what core has to offer for the following
 * reasons:
 *
 * - Allows more customization options (id and class) without using
 *   theme() functions.
 * - Chainability.
 *
 * See [the documentation](/docs/php-libraries/rendering-menus) for more information.
 *
 * @package  Able Core
 * @author   Samuel Marks <sam@sammarks.me>
 */
class Menu
{

	/**
	 * Name
	 *
	 * The name of the menu.
	 * @var string
	 */
	public $name;

	/**
	 * Menu
	 *
	 * The parent menu.
	 * @var object
	 */
	private $menu;

	/**
	 * Parent
	 *
	 * The parent MLID for the menu.
	 * @var integer
	 */
	private $parent = 0;

	/**
	 * ID
	 *
	 * The HTML ID attribute for the menu.
	 * @var string
	 */
	private $id = '';

	/**
	 * Classes
	 *
	 * The HTML classes attribute for the menu.
	 * @var array
	 */
	private $classes = array();

	/**
	 * Depth
	 *
	 * How deep the menu should render. 1-based.
	 * @var integer
	 */
	private $depth = MENU_MAX_DEPTH;

	/**
	 * Expand All
	 *
	 * Whether or not every item in the menu should
	 * be expanded, regardless if the user is in that
	 * section or not.
	 * @var boolean
	 */
	private $expand_all = false;

	/**
	 * Include Parent as First
	 *
	 * Whether or not to include the parent menu item
	 * as the first item in the menu.
	 * @var bool
	 */
	private $include_parent_as_first = false;

	/**
	 * Link Theme
	 *
	 * A Drupal theme to use when rendering the menu links.
	 *
	 * @var string
	 */
	private $link_theme = '';

	/**
	 * Menu Theme
	 *
	 * A Drupal theme to use when rendering the menu itself.
	 *
	 * @var string
	 */
	private $menu_theme = '';

	/**
	 * Constructor
	 *
	 * Creates a new Menu class. This will throw an
	 * exception if the menu being loaded does not
	 * exist.
	 *
	 * @param string $menu_name The name of the menu to use.
	 * @throws \Exception
	 */
	public function __construct($menu_name)
	{
		$this->name = $menu_name;
		$m = \menu_load($menu_name);
		if ($m === false) {
			throw new \Exception("The menu: $menu_name doesn't exist!");
		}
		$this->menu = $m;
	}

	/**
	 * Load
	 *
	 * Loads a menu. This function serves as a shortcut for
	 * chainability.
	 *
	 * @param  string $menu_name The name of the menu to load.
	 *
	 * @return Menu              The AbleCore\Menu object.
	 */
	public static function load($menu_name)
	{
		return new Menu($menu_name);
	}

	/**
	 * Render
	 *
	 * Renders the menu with the configuration options passed to
	 * the class.
	 *
	 * @return string The rendered menu.
	 */
	public function render()
	{
		$menu_tree_options = array();
		$menu_tree_options['max_depth'] = $this->depth;
		$menu_tree_options['active_trail'] = menu_get_active_trail();

		if (!$this->expand_all) {
			$parents = array();
			foreach (menu_get_active_trail() as $menu_item) {
				if (!array_key_exists('mlid', $menu_item)) continue;
				$parents[$menu_item['mlid']] = $menu_item['mlid'];
			}
			$menu_tree_options['expanded'] = $parents;
		}

		if ($this->parent) {
			$parent_link = menu_link_load($this->parent);
			$menu_tree_options['active_trail'] = array($parent_link['mlid']);
			$menu_tree_options['only_active_trail'] = false;
			$menu_tree_options['min_depth'] = $parent_link['depth'] + 1;
			$menu_tree_options['conditions'] = array(
				"p{$parent_link['depth']}" => $parent_link['mlid']
			);
		}

		$menu_tree = array();
		if ($this->include_parent_as_first && isset($parent_link)) {
			$menu_tree = menu_build_tree($this->menu['menu_name'], array(
				'conditions' => array('mlid' => $parent_link['mlid']),
			));
		}

		$menu_tree_no_parent = menu_build_tree($this->menu['menu_name'], $menu_tree_options);
		$menu_tree += $menu_tree_no_parent;

		// Now prepare the output.
		$output = menu_tree_output($menu_tree);

		// Prepare the active link classes.
		$output_no_parent = menu_tree_output($menu_tree_no_parent);
		$this->addActiveTrailClasses($output_no_parent);
		$output = array_replace_recursive($output, $output_no_parent);

		// Now add the themes to the output.
		$this->addLinkThemes($output);

		// Continue preparing the output.
		$output['#theme_wrappers'] = array('ablecore_menu_tree');
		if ($this->menu_theme) {
			$output['#theme_wrappers'][] = $this->menu_theme;
		}

		$attributes = array();
		if (!is_array($this->classes)) {
			$this->classes = array('menu');
		}
		if (array_search('menu', $this->classes) === false) {
			$this->classes[] = 'menu';
		}
		$attributes['class'] = implode(' ', $this->classes);
		if ($this->id) { $attributes['id'] = $this->id; }
		if (count($attributes) > 0) {
			$output['#attributes'] = $attributes;
		}

		return render($output);
	}

	protected function addLinkThemes(array &$output)
	{
		if (!$this->link_theme) return;
		foreach ($output as $key => $item) {
			if (!is_array($item)) continue;
			if (array_key_exists('#theme', $output[$key])) {
				$output[$key]['#theme'] = $this->link_theme;
			}
			if (array_key_exists('#below', $output[$key])) {
				$this->addLinkThemes($output[$key]['#below']);
			}
		}
	}

	protected function addActiveTrailClasses(array &$output, array $active_trail = array())
	{
		// Are we dealing with a link that goes to the front page?
		$active_link = menu_link_get_preferred();
		if ($active_link['href'] == '<front>' || drupal_is_front_page()) {
			foreach ($output as $key => $link) {
				if (!is_array($link) || !array_key_exists('#original_link', $link)) continue;
				if ($link['#original_link']['link_path'] == '<front>') {
					$output[$key]['#attributes']['class'][] = 'active';
				}
			}
		}

		if (count($active_trail) <= 0 || !$active_trail) {
			$active_trail = menu_get_active_trail();
		}

		// Now we need to find an entry point with the current tree.
		$active_output = false;
		$current_active_trail_index = 0;
		while ($active_output === false) {
			if (!array_key_exists($current_active_trail_index, $active_trail)) break;
			$current_active_trail_item = $active_trail[$current_active_trail_index];
			if (array_key_exists('mlid', $current_active_trail_item)) {
				if (array_key_exists($current_active_trail_item['mlid'], $output)) {
					$active_output = &$output[$current_active_trail_item['mlid']];
				}
			}
			$current_active_trail_index++;
		}

		// We failed to find an item in the menu tree that's part of the active trail.
		if (!is_array($active_output)) return;

		// Mark the menu item as active-trail if it doesn't already have the active class.
		if (!array_key_exists('#attributes', $active_output)) $active_output['#attributes'] = array();
		if (!array_key_exists('class', $active_output['#attributes'])) $active_output['#attributes']['class'] = array();

		// Check to see if the active link is the current link, or in the active trail.
		if ($active_output['#original_link']['mlid'] == $active_link['mlid'])
			$active_output['#attributes']['class'][] = 'active';
		else
			$active_output['#attributes']['class'][] = 'active-trail';

		// Now we check the children.
		if (array_key_exists('#below', $active_output)) {
			$this->addActiveTrailClasses($active_output['#below'], $active_trail);
		}
	}

	/* ==== MENU HELPER FUNCTIONS ==== */

	/**
	 * Current Link
	 *
	 * Gets the menu link representing the current page (if one exists).
	 *
	 * @return array The menu link.
	 */
	public static function current_link()
	{
		$link = false;

		// Check with ablecore_menu first.
		if (module_exists('ablecore_menu')) {
			$link = ablecore_menu_get_preferred_link();
		}

		if ($link === false) {
			$active_trail = \menu_get_active_trail();
			$link = $active_trail[count($active_trail) - 1];
		}

		return $link;
	}

	/**
	 * Current Section MLID
	 *
	 * Returns the MLID of the current section (2nd level page).
	 *
	 * @param  string $preferred_menu The name of the menu to prefer.
	 *
	 * @return integer The section MLID.
	 */
	public static function current_section_mlid($preferred_menu = 'main-menu')
	{
		$link = false;
		$found = array();

		// First, check to see if ablecore_menu is enabled and we have a match.
		if (module_exists('ablecore_menu')) {
			$link = ablecore_menu_get_preferred_link();
		}

		// Second, see if Drupal will give us one.
		if ($link === false) {
			$link = menu_link_get_preferred($_GET['q'], $preferred_menu);
		}

		if ($link !== false) {			
			while ($link['plid'] !== "0") {
				if (in_array($link['plid'], $found)) {
					break; // Abort loop if a circular reference is detected
				} else {
					$found[] = $link['plid'];
					$link = menu_link_load($link['plid']);
				}
			}
			return $link['mlid'];
		} else return false;
	}

	/**
	 * get_active_title
	 *
	 * Gets the active menu title for the current page. If there is an ablecore_menu override,
	 * it defaults to the title of the current page.
	 *
	 * @return bool|null|string
	 */
	public static function get_active_title()
	{
		if (module_exists('ablecore_menu') && ablecore_menu_get_active_title() !== false) {
			return ablecore_menu_get_active_title();
		} else return menu_get_active_title();
	}

	/* ==== SETTERS ==== */

	/**
	 * Set Parent
	 *
	 * Sets the $parent MLID of the menu.
	 *
	 * @param integer $parent The MLID of the parent link.
	 * @return Menu
	 */
	public function set_parent($parent)
	{
		$this->parent = $parent;
		return $this;
	}

	/**
	 * Set ID
	 *
	 * Sets the $id for the menu.
	 *
	 * @param string $id The content to put inside the id attribute
	 *                   for the menu.
	 * @return Menu
	 */
	public function set_id($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * Set Classes
	 *
	 * Sets the HTML class attribute for the menu.
	 *
	 * @param mixed $classes The classes to be added to the element.
	 * @return Menu
	 */
	public function set_classes($classes)
	{
		if (!is_array($classes)) {
			$classes = explode(' ', $classes);
		}
		$this->classes = $classes;
		return $this;
	}

	/**
	 * Set Expand All
	 *
	 * Sets the expand mode for the menu.
	 * - true - Expands all children of the menu.
	 * - false - Only expands children that are part of the active trail.
	 *
	 * @param bool $expand Whether or not to expand the entire menu.
	 * @return Menu
	 */
	public function set_expand_all($expand)
	{
		$this->expand_all = $expand;
		return $this;
	}

	/**
	 * Set Depth
	 *
	 * Sets the depth of the rendered menu.
	 *
	 * @param integer $depth The depth of the menu to render.
	 * @return Menu
	 */
	public function set_depth($depth)
	{
		$this->depth = $depth;
		return $this;
	}

	/**
	 * include_parent_as_first()
	 *
	 * Set whether or not to include the parent menu link as the first item in
	 * the list.
	 *
	 * @param bool $include Whether or not to include it.
	 *
	 * @return Menu
	 */
	public function include_parent_as_first($include = true)
	{
		$this->include_parent_as_first = $include;
		return $this;
	}

	/**
	 * set_menu_theme()
	 *
	 * Sets the theme used when rendering the entire menu.
	 *
	 * @param string $theme The name of the theme to use.
	 *
	 * @return $this
	 */
	public function set_menu_theme($theme)
	{
		$this->menu_theme = $theme;
		return $this;
	}

	/**
	 * set_link_theme()
	 *
	 * Sets the theme used when rendering individual menu links.
	 *
	 * @param $theme
	 *
	 * @return $this
	 */
	public function set_link_theme($theme)
	{
		$this->link_theme = $theme;
		return $this;
	}

}
