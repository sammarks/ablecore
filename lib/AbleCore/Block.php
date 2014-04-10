<?php

/**
 * Drupal Block Extension Class
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace AbleCore;

/**
 * Drupal Block Extension Class
 *
 * This class contains helper functions that aid in the
 * inclusion and rendering of blocks.
 *
 * **Helpful Tip:** The two parameters can be found by going to the block
 * listing page and looking at the URLs for configuring them. They will look
 * something like this:
 *
 * `/admin/structure/block/manage/fb_connect/login_FBConnect/configure`
 *
 * - `$delta` is the url segment just before configure (`login_FBConnect`).
 * - `$module` is the url segment just before the $delta (`fb_connect`).
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */
class Block extends DrupalExtension
{

	/**
	 * Constructor
	 *
	 * Creates a new block object.
	 *
	 * @param string $module The module the block belongs to.
	 * @param string $delta  The name of the block within the
	 *                       module.
	 */
	public function __construct($module, $delta)
	{
		$block = block_load($module, $delta);
		if (!$block) {
			throw new \Exception("The block ($module, $delta) doesn't exist!");
		}
		$this->base = $block;
	}

	/**
	 * Static Constructor
	 *
	 * Creates a new block object statically.
	 *
	 * @param  string $module The module the block belongs to.
	 * @param  string $delta  The name of the block within the
	 *                        module.
	 *
	 * @return Block          The new Block object.
	 */
	public static function load($module, $delta)
	{
		return new Block($module, $delta);
	}

	/**
	 * Render
	 *
	 * Renders the loaded block.
	 *
	 * @return string The rendered content of the block.
	 */
	public function render()
	{
		$output = '';
		try {
			$first_child = new \stdClass();
			foreach (_block_render_blocks(array($this->base)) as $item) {
				$first_child = $item;
				break;
			}
			$output = drupal_render($first_child->content);
		} catch (\Exception $ex) {
			watchdog('ac_base', 'Block insertion exception: <pre>' . print_r($ex, 1) . '</pre>');
			$output = '<pre>There was an error inserting the block. Details: ' . print_r($ex, 1) . '</pre>';
		}

		return $output;
	}

}
