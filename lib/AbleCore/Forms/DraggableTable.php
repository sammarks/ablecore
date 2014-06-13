<?php

namespace AbleCore\Forms;

class DraggableTable {

	/**
	 * The identifier for the table (and form).
	 * @var string
	 */
	protected $identifier;

	/**
	 * An array of fields to render from the form.
	 * @var array
	 */
	protected $fields;

	/**
	 * The header configuration for the table.
	 * @var array
	 */
	protected $columns;

	/**
	 * Text to display when there is no content available.
	 * @var string
	 */
	protected $empty_text;

	/**
	 * False if no add link is to be displayed. Else, the URL the link goes to.
	 * @var mixed
	 */
	protected $add_link;

	/**
	 * The text to display on the add link.
	 * @var string
	 */
	protected $add_text;

	public function __construct($identifier)
	{
		$this->identifier = $identifier;
	}

	/**
	 * Setup Table
	 *
	 * Sets up the fields to select and the columns to display for the table.
	 *
	 * @param array $fields  An array of fields to display.
	 * @param array $columns The header configuration for the table.
	 */
	public function setupTable($fields = array(), $columns = array())
	{
		$this->fields = $fields;
		$this->columns = $columns;
	}

	/**
	 * Setup Add Link
	 *
	 * Sets up the add link for the empty text.
	 *
	 * @param mixed  $add_link      If false, an add link will not be displayed. Else, the URL for the link to go to.
	 * @param string $add_link_text The text to display for the add link.
	 */
	public function setupAddLink($add_link = false, $add_link_text = 'Would you like to create one?')
	{
		$this->add_link = $add_link;
		$this->add_text = $add_link_text;
	}

	/**
	 * Setup Empty Text
	 *
	 * Sets up the empty text (text displayed when there is no content available).
	 *
	 * @param string $empty_text The text to display.
	 */
	public function setupEmptyText($empty_text = 'There are currently no items available.')
	{
		$this->empty_text = $empty_text;
	}

	/**
	 * Render
	 *
	 * Renders the table.
	 *
	 * @param array $form The form, passed through $variables in a form override theme.
	 *
	 * @return string The HTML contents of the table.
	 */
	public function render($form)
	{
		if (array_key_exists('#no-results', $form) && $form['#no-results'] === true) {
			$render_array = array(
				'#type' => 'container',
				'#attributes' => array(),
				array(
					'#theme' => 'html_tag',
					'#tag' => 'span',
					'#value' => $this->empty_text,
				),
			);
			if ($this->add_link) {
				$render_array[] = array(
					'#type' => 'link',
					'#href' => $this->add_link,
					'#title' => $this->add_text,
				);
			}

			$output = render($render_array);
			$form[$this->identifier]['#access'] = false;
			$output .= drupal_render_children($form);
			return $output;
		}

		$rows = array();
		foreach (element_children($form[$this->identifier]) as $id) {
			$form[$this->identifier][$id]['weight']['#attributes']['class'] = array($this->identifier . '-order-weight');
			$row = array(
				'data' => array(),
				'class' => array('draggable'),
			);
			foreach ($this->fields as $field) {
				$row['data'][] = drupal_render($form[$this->identifier][$id][$field]);
			}
			$rows[] = $row;
		}

		$output = theme('table', array(
			'header' => $this->columns,
			'rows' => $rows,
			'attributes' => array(
				'id' => $this->identifier,
			),
		));
		$output .= drupal_render_children($form);
		drupal_add_tabledrag($this->identifier, 'order', 'sibling', $this->identifier . '-order-weight');

		return $output;
	}

}
