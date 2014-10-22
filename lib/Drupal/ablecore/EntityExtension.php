<?php

namespace Drupal\ablecore;

abstract class EntityExtension extends Entity implements EntityExtensionInterface {

	/**
	 * Get Current Entity Type
	 *
	 * Gets the entity type the current class represents.
	 *
	 * @return string The entity type.
	 * @throws \Exception
	 */
	protected static function getCurrentEntityType()
	{
		$current_class = get_called_class();
		if (is_subclass_of($current_class, __CLASS__)) {
			return forward_static_call(array($current_class, 'getEntityType'));
		}
		throw new \Exception("You're trying to call an entity function on a class that is not an extension of the entity class.");
	}

	/**
	 * Load
	 *
	 * Loads basic entity information from the database.
	 *
	 * @param int $entity_id The ID of the entity.
	 *
	 * @return EntityExtension|bool The loaded entity on success, or false on failure.
	 * @throws \Exception
	 */
	public static function load($entity_id)
	{
		return static::loadWithType(static::getCurrentEntityType(), $entity_id);
	}

	/**
	 * Load by UUID
	 *
	 * @param string $entity_uuid The UUID of the entity to load.
	 *
	 * @return EntityExtension|bool The loaded entity on success, else false.
	 */
	public static function loadByUUID($entity_uuid)
	{
		return static::loadWithTypeByUUID(static::getCurrentEntityType(), $entity_uuid);
	}

	/**
	 * Current (with type)
	 *
	 * Gets the entity representing the current page.
	 *
	 * @param int $position       The position in the path where the ID for the entity lies.
	 *                            For example, for 'node/1', this value would be '1'.
	 *                            Defaults to 1.
	 *
	 * @return EntityExtension|bool The loaded entity or false on error.
	 */
	public static function current($position = 1)
	{
		return static::currentWithType(static::getCurrentEntityType(), $position);
	}

	/**
	 * Map
	 *
	 * Given an array of entity IDs, returns an array of those loaded entities.
	 *
	 * @param array $entity_ids An ID of entity IDs to load.
	 *
	 * @return array The loaded entities.
	 */
	public static function map(array $entity_ids = array())
	{
		return static::mapWithType(static::getCurrentEntityType(), $entity_ids);
	}

	/**
	 * Map Query
	 *
	 * Given a select query, executes the query and returns an array of Entity
	 * objects representing the result.
	 *
	 * @param \SelectQueryInterface $query The query.
	 * @param int                   $index Passed to fetchCol(), represents the column to fetch.
	 *
	 * @return array An array of EntityExtension objects.
	 */
	public static function mapQuery(\SelectQueryInterface $query, $index = 0)
	{
		return static::mapQueryWithType(static::getCurrentEntityType(), $query, $index);
	}

	/**
	 * Get Latest Revision ID
	 *
	 * Gets the latest revision ID for the specified entity from the database.
	 *
	 * @param int  $entity_id The ID for the entity to check.
	 * @param bool $reset     Whether or not to reset the cached results.
	 *
	 * @return int|bool The revision ID on success, false on error.
	 */
	public static function getLatestRevisionID($entity_id, $reset = false)
	{
		return static::getLatestRevisionIDWithType(static::getCurrentEntityType(), $entity_id, $reset);
	}

	/**
	 * Delete
	 *
	 * Deletes an entity.
	 *
	 * @param int $entity_id The ID of the entity to delete.
	 *
	 * @return bool The results of entity_delete()
	 * @see entity_delete()
	 */
	public static function delete($entity_id)
	{
		return static::deleteWithType(static::getCurrentEntityType(), $entity_id);
	}

}
