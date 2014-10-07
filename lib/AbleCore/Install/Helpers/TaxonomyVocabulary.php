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
			$instance = static::load($vocab);
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
	 * Seed
	 *
	 * Provide some default terms for the vocabulary.
	 *
	 * @param array $terms A single dimensional array of term names.
	 *
	 * @return $this
	 */
	public function seed(array $terms = array())
	{
		$this->refresh();
		if (!empty($this->definition->vid)) {
			if (!$this->vocabularyHasTerms($this->definition->vid)) {
				foreach ($terms as $term) {
					$new_term = (object)array(
						'vid' => $this->definition->vid,
						'name' => $term,
					);
					taxonomy_term_save($new_term);
				}
			}
		}

		return $this;
	}

	/**
	 * Vocabulary Has Terms
	 *
	 * @param int $vid The vocabulary ID to check.
	 *
	 * @return bool
	 */
	protected function vocabularyHasTerms($vid)
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
