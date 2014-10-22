<?php

/**
 * A block manager for Drupal.
 *
 * @author  Samuel Marks <sam@sammarks.me>
 * @package Able Core (Module Helpers)
 */

namespace Drupal\ablecore\Modules;

/**
 * Block Manager
 *
 * A block manager for Drupal.
 *
 * #### Sample Usage
 *
 *     function MODULE_block_info()
 *     {
 *
 *         return AbleCore\BlockManager::init()
 *
 *             // Basic examples
 *             ->define('header', 'Header')
 *             ->define('footer', 'Footer')
 *
 *             // More advanced...
 *             ->define('slideshow', 'Slideshow', array(
 *                 'visibility' => BLOCK_VISIBILITY_LISTED,
 *                 'pages' => '<front>',
 *             ))
 *             ->define('home_grid', 'Home Overview Grid', array(
 *                 'visibility' => BLOCK_VISIBILITY_LISTED,
 *                 'pages' => '<front>',
 *             ))
 *
 *             // And finish...
 *             ->fin();
 *
 *     }
 *
 * See [the documentation](/docs/modules/blocks) for more information.
 *
 * @author  Samuel Marks <sam@sammarks.me>
 * @package Able Core (Module Helpers)
 */
class BlockManager
{

	/**
	 * The generated block configuration.
	 * @var array
	 */
	private $generated = array();

	/**
	 * Define
	 *
	 * Defines a new block.
	 *
	 * @param  string $identifier   The machine name for the block (delta).
	 * @param  string $title        The human-readable title for the block.
	 * @param  string $region       The region for the block to go in.
	 * @param  int    $weight       The weight of the block. Defaults to 0.
	 * @param  array  $extra_config Any extra configuration options for the block.
	 *
	 * @return BlockManager
	 */
	function define($identifier, $title, $region = '', $weight = 0, $extra_config = array())
	{
		$default_configuration = array(
			'info' => $title,
			'title' => $title,
			'cache' => DRUPAL_CACHE_GLOBAL,
			'weight' => $weight,
		);

		if ($region != '') {
			$default_configuration['region'] = $region;
			$default_configuration['status'] = 1;
		}

		$configuration = array_replace_recursive($default_configuration, $extra_config);

		if (array_key_exists($identifier, $this->generated)) {
			trigger_error("The identifier {$identifier} already exists in the Blocks array." .
			" You might want to fix that.",
				E_USER_WARNING);
		}

		$this->generated[$identifier] = $configuration;

		return $this;
	}

	/**
	 * Init
	 *
	 * Creates a new BlockManager.
	 * @return BlockManager
	 */
	public static function init()
	{
		return new BlockManager();
	}

	/**
	 * Finish
	 *
	 * Finishes the configuration.
	 * @return array The block configuration output.
	 */
	public function fin()
	{
		return $this->generated;
	}

}
