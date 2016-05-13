<?php

namespace AbleCore\Plugins\CTools;

use AbleCore\Forms\ICompleteForm;
use AbleCore\Plugins\Plugin;

abstract class ContentType extends Plugin implements ICompleteForm {

	#region Plugin Variables

	/**
	 * The title of the content type.
	 * @var string
	 */
	protected $title;

	/**
	 * The description of the content type.
	 * @var string
	 */
	protected $description;

	/**
	 * Whether or not this is the "final" version of the
	 * content type. In other words, whether or not this
	 * content type cannot have children. Defaults to true.
	 * @var bool
	 */
	protected $single = true;

	/**
	 * The default settings for the content type.
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * The path to the icon to use for this content type.
	 * @var string
	 */
	protected $icon;

	/**
	 * The category the content type will be displayed in.
	 * Defaults to 'Custom'
	 * @var string
	 */
	protected $category;

	/**
	 * Whether or not this content type is a top-level
	 * content type.
	 * @var bool
	 */
	protected $top_level = true;

	/**
	 * The attached JavaScript configuration to pass through
	 * drupal_process_attached()
	 * @var array
	 */
	protected $js = array();

	/**
	 * The attached CSS configuration to pass through
	 * drupal_process_attached()
	 * @var array
	 */
	protected $css = array();

	/**
	 * The required context.
	 * @var \ctools_context_required|null
	 */
	protected $required_context = null;

	/**
	 * The edit form for the content type.
	 * Defaults to the current content type.
	 * @var \AbleCore\Forms\ICompleteForm|null
	 */
	protected $edit_form = null;

	/**
	 * The add form for the content type.
	 * Defaults to the current content type.
	 * @var \AbleCore\Forms\ICompleteForm|null
	 */
	protected $add_form = null;

	/**
	 * An array containing 'js' and 'css' keys that will
	 * be passed to drupal_process_attached() when the
	 * content type is rendered.
	 * @var array
	 */
	protected $attached = array();

	/**
	 * The title of the rendered block. Defaults to ''.
	 * @var string
	 */
	protected $block_title = '';

	#endregion

	protected function __construct()
	{
		$this->category = t('Custom');
		$this->edit_form = $this;
		$this->add_form = $this;
	}

	protected function getPlugin()
	{
		$plugin = array();
		foreach (get_object_vars($this) as $key => $value) {
			$plugin[str_replace('_', ' ', $key)] = $value;
		}

		// Process the add and edit forms.
		// We need to get instances of the forms and make
		// sure they have valid build methods. If their build
		// methods return null, we can safely ignore the
		// property.
		foreach (array('add_form', 'edit_form') as $key) {
			if (!($plugin[$key] instanceof ICompleteForm)) continue;
			$this->processFormProperty($plugin, $key, $plugin[$key]);
		}

		// Set the render callback to the current function's
		// render method.
		$plugin['render callback'] = array($this, 'renderFull');

		// Unset internal properties...
		unset($plugin['attached']);
		unset($plugin['block_title']);

		return $plugin;
	}

	/**
	 * CTools callback for rendering the content type.
	 *
	 * @param $subtype
	 * @param $conf
	 * @param $args
	 * @param $context
	 *
	 * @return \stdClass The full block object.
	 */
	public function renderFull($subtype, $conf, $args, $context)
	{
		$block = new \stdClass();
		$block->content = $this->getRenderedContent($subtype, $conf, $args, $context);
		$block->title = $this->getTitle($subtype, $conf, $args, $context);

		$element = array('#attached' => $this->getAttached($subtype, $conf, $args, $context));
		drupal_process_attached($element);

		return $block;
	}

	/**
	 * Calls the render() function for the content type
	 * and runs the result through drupal_render() if
	 * they pass back an array.
	 *
	 * @param $subtype
	 * @param $conf
	 * @param $args
	 * @param $context
	 *
	 * @return string
	 */
	protected function getRenderedContent($subtype, $conf, $args, $context)
	{
		$content = $this->render($subtype, $conf, $args, $context);
		if (is_array($content)) {
			return drupal_render($content);
		} else {
			return $content;
		}
	}

	#region Rendering Functions

	/**
	 * CTools callback to render the block content of the
	 * content type.
	 *
	 * @param $subtype
	 * @param $conf
	 * @param $args
	 * @param $context
	 *
	 * @return string|array Either a render array or the
	 *                      rendered content.
	 */
	public abstract function render($subtype, $conf, $args, $context);

	/**
	 * Gets the attached information to pass through
	 * drupal_process_attached() for the content type
	 * (this is how you attach CSS and JS files to content
	 * types).
	 *
	 * @param $subtype
	 * @param $conf
	 * @param $args
	 * @param $context
	 *
	 * @return array
	 */
	public function getAttached($subtype, $conf, $args, $context)
	{
		return $this->attached;
	}

	/**
	 * Gets the block title for the content type.
	 *
	 * @param $subtype
	 * @param $conf
	 * @param $args
	 * @param $context
	 *
	 * @return string
	 */
	public function getTitle($subtype, $conf, $args, $context)
	{
		return $this->title;
	}

	#endregion

	#region Default Form Handlers

	/**
	 * Builds the contents of the form.
	 *
	 * @param array $form       The existing form array.
	 * @param array $form_state A reference to the existing form state.
	 *
	 * @return array A render array representing the form.
	 */
	public function build($form, &$form_state)
	{
		return null;
	}

	/**
	 * Handles submission of the form.
	 *
	 * @param array $form       The existing form array.
	 * @param array $form_state A reference to the existing form state.
	 */
	public function submit($form, &$form_state)
	{
		return;
	}

	/**
	 * Performs validation on the submitted form state.
	 *
	 * @param array $form       The existing form array.
	 * @param array $form_state A reference to the existing form state.
	 */
	public function validate($form, &$form_state)
	{
		return;
	}

	#endregion

}
