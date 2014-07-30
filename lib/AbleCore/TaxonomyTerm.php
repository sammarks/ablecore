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
		$query->orderBy('weight');
		return static::mapQuery($query);
	}

}
