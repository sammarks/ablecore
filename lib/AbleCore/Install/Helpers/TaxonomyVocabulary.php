<?php
/**
 * @file TaxonomyVocabulary.php
 */
namespace AbleCore\Install\Helpers;

class TaxonomyVocabulary {

	/**
	 * The definition.
	 * @var \stdClass|null
	 */
	public $definition = null;
	protected $name = '';
	protected $machine_name = '';

	public function __construct($machine_name, $name, $definition = null)
	{
		$this->definition = $definition;
		$this->name = $name;
		$this->machine_name = $machine_name;

		$this->definition->name = $name;
		$this->definition->machine_name = $machine_name;
	}

	/**
	 * Create
	 *
	 * @param string $machine_name The machine name of the vocabulary to create.
	 * @param string $name         The name of the vocabulary.
	 *
	 * @return static
	 */
	public static function create($machine_name, $name)
	{
		$vocab = taxonomy_vocabulary_machine_name_load($machine_name);
		if ($vocab) {
			$instance = static::load($machine_name);
			$instance->definition->name = $name;
			$instance->definition->machine_name = $machine_name;
			return $instance;
		} else {
			return new static($machine_name, $name, new \stdClass());
		}
	}

	/**
	 * Load
	 *
	 * @param string $machine_name The name of the vocabulary to load.
	 *
	 * @return static
	 */
	public static function load($machine_name)
	{
		$vocab = taxonomy_vocabulary_machine_name_load($machine_name);
		return new static($machine_name, $vocab->name, $vocab);
	}

	/**
	 * Sets the description of the taxonomy vocabulary.
	 *
	 * @param string $description The description of the vocabulary.
	 *
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->definition->description = $description;
		return $this;
	}

	/**
	 * Seed
	 *
	 * Provide some default terms for the vocabulary.
	 *
	 * @param array $terms A single dimensional array of term names.
	 *
	 * @return $this
	 */
	public function seed(array $terms)
	{
		$this->refresh();
		if (!empty($this->definition->vid)) {
			if (!static::vocabularyHasTerms($this->definition->vid)) {
				$weight = 0;
				foreach ($terms as $index => $term) {
					$this->seedTerm($index, $term, $weight);
				}
			}
		}

		return $this;
	}

	/**
	 * Seeds an individual term.
	 *
	 * @param string $index       The index of the term. Either a string or a number.
	 * @param mixed  $term        The term itself. Either the name of the term or an array representing
	 *                            additional configuration options.
	 * @param int    $weight      The current weight to be applied to terms.
	 * @param int    $parent_term The TID of the parent term to add to the current term.
	 */
	protected function seedTerm($index, $term, &$weight, $parent_term = 0)
	{
		$label = is_numeric($index) ? $term : $index;
		$new_term = (object)array(
			'vid' => $this->definition->vid,
			'name' => $label,
			'weight' => $weight,
			'parent' => $parent_term,
		);
		if (is_array($term)) {
			foreach ($term as $field => $value) {
				if ($field == 'children') continue;
				$new_term->$field = $value;
			}
		}
		taxonomy_term_save($new_term);
		$weight++;

		// If the term has children, add them.
		if ($new_term->tid && is_array($term) && array_key_exists('children', $term) && count($term['children'])) {
			foreach ($term['children'] as $child_index => $child_term) {
				$this->seedTerm($child_index, $child_term, $weight, $new_term->tid);
			}
		}
	}

	/**
	 * Vocabulary Has Terms
	 *
	 * @param int $vid The vocabulary ID to check.
	 *
	 * @return bool
	 */
	public static function vocabularyHasTerms($vid)
	{
		$query = db_select('taxonomy_term_data', 'td');
		$query->addField('td', 'tid');
		$query->addField('td', 'name');
		$query->condition('vid', $vid);
		$results = $query->execute()->fetchAll();

		return (count($results) > 0);
	}

	/**
	 * Refresh the vocabulary from the database.
	 */
	protected function refresh()
	{
		if (empty($this->definition->vid)) {
			$this->definition = taxonomy_vocabulary_machine_name_load($this->machine_name);
		}
	}

	/**
	 * Save
	 *
	 * Save the vocabulary.
	 *
	 * @return $this
	 */
	public function save()
	{
		taxonomy_vocabulary_save($this->definition);
		return $this;
	}

} 
