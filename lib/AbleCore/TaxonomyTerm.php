<?php

namespace AbleCore;

class TaxonomyTerm extends EntityExtension {

	/**
	 * Gets the entity type of the current class.
	 *
	 * @return string The entity type.
	 */
	static function getEntityType()
	{
		return 'taxonomy_term';
	}

	/**
	 * By Vocabulary
	 *
	 * Gets all taxonomy terms by the specified vocabulary.
	 *
	 * @param string $vocabulary_machine_name The machine name of the vocabulary.
	 *
	 * @return array An array of TaxonomyTerm items.
	 */
	public static function byVocabulary($vocabulary_machine_name)
	{
		$query = db_select('taxonomy_term_data', 'td');
		$query->addJoin('inner', 'taxonomy_vocabulary', 'tv', 'tv.vid = td.vid');
		$query->condition('tv.machine_name', $vocabulary_machine_name);
		$query->addField('td', 'tid');
		$query->orderBy('td.weight');
		return static::mapQuery($query);
	}

	/**
	 * Tree by Vocabulary
	 *
	 * @param string $vocabulary_machine_name The vocabulary machine name to get the
	 *                                        taxonomy term tree for.
	 *
	 * @return array A better (heirarchical) array of taxonomy terms.
	 */
	public static function treeByVocabulary($vocabulary_machine_name)
	{
		$tree = &drupal_static(__FUNCTION__ . ':' . $vocabulary_machine_name, null);
		if ($tree === null) {

			// Load the vocabulary.
			$vocabulary = taxonomy_vocabulary_machine_name_load($vocabulary_machine_name);
			if (!$vocabulary) {
				return array();
			}

			// Get the original, flat tree.
			$flat_tree = taxonomy_get_tree($vocabulary->vid);

			// Get the new heirarchical tree of terms.
			$tree = static::getTermTree($flat_tree);

		}

		return $tree;
	}

	/**
	 * Get Term Tree
	 *
	 * @param array $flat_tree The flat tree returned by taxonomy_get_tree()
	 * @param int   $parent_id The TID of the parent to get children for.
	 *
	 * @return array A heirarchical array of taxonomy terms, as configured in
	 *               the taxonomy term administration.
	 */
	protected static function getTermTree(array $flat_tree, $parent_id = 0)
	{
		$terms = array();
		foreach ($flat_tree as $term) {
			if (array_search($parent_id, $term->parents) !== false) {
				$terms[$term->tid] = $term;
			}
			if ($children = static::getTermTree($flat_tree, $term->tid)) {
				$terms[$term->tid]->children = $children;
			}
		}
		return $terms;
	}

}
