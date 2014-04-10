<?php

/**
 * A user wrapper for Drupal applications.
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace AbleCore;

/**
 * User
 *
 * A user wrapper for Drupal applications.
 *
 * See [the documentation](/docs/php-libraries/user-helpers) for more information.
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */
class User extends DrupalExtension
{

	/**
	 * Construct
	 *
	 * Creates a new User object.
	 *
	 * @param integer $uid The UID for the user.
	 */
	public function __construct($uid)
	{
		$user = user_load($uid);
		if (!$user) {
			throw new \Exception("The user ($uid) doesn't exist!");
		}
		$this->base = $user;
	}

	/**
	 * Load
	 *
	 * Loads the user with the given UID.
	 *
	 * @param  integer $uid The user ID.
	 *
	 * @return User         The AbleCore\User object.
	 */
	public static function load($uid)
	{
		return new User($uid);
	}

	/**
	 * Current
	 *
	 * Loads the current user.
	 *
	 * @return User The AbleCore\User object.
	 */
	public static function current()
	{
		global $user;

		if (self::check() === false) {
			return false;
		} else {
			return new User($user->uid);
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
		try {
			if (array_search('administrator', $this->roles) !== false) {
				return true;
			} else {
				return false;
			}
		} catch (\Exception $ex) {
			return false;
		}
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
		// We were trying to reinvent the wheel before...
		return user_access($role, $this->base);
	}

}
