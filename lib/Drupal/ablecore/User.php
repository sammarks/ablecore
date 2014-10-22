<?php

/**
 * A user wrapper for Drupal applications.
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace Drupal\ablecore;

class User extends EntityExtension
{

	/**
	 * Gets the entity type of the current class.
	 *
	 * @return string The entity type.
	 */
	static function getEntityType()
	{
		return 'user';
	}

	/**
	 * Logged In
	 *
	 * @return Entity|bool The current logged in user.
	 */
	public static function loggedIn()
	{
		global $user;

		if (self::check() === false) {
			return false;
		} else {
			return static::import($user);
		}
	}

	/**
	 * Check
	 *
	 * Determines whether or not someone is logged in.
	 *
	 * @return boolean TRUE if they are logged in
	 *                 FALSE if they are not.
	 */
	public static function check()
	{
		global $user;
		return ($user->uid);
	}

	/**
	 * Admin?
	 *
	 * Determines whether or not the loaded user is admin.
	 *
	 * @return boolean TRUE if they are admin.
	 *                 FALSE if they are not.
	 */
	public function admin()
	{
		if (!empty($this->base->roles) && is_array($this->base->roles)) {
			return array_search('administrator', $this->base->roles) !== false;
		} else return false;
	}

	/**
	 * Role?
	 *
	 * Determines whether or not the loaded user has the specified
	 * role.
	 *
	 * @param  string $role The name of the role to check for.
	 *
	 * @return boolean        TRUE if they have the role.
	 *                        FALSE if they do not.
	 */
	public function role($role)
	{
		return user_access($role, $this->base);
	}

}
