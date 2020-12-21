<?php
/**
 * Userpoint base class
 *
 * @package     Userpoint
 * @created     Aug 14th, 2009
 * @version     $Revision: $
 * @modifiedby  $LastChangedBy: $
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author      Billy Gunn
 * @copyright   Construct Aegis, LLC 2009
 * @link        http://www.constructaegis.com
 *
 *
 * Updated to Elgg 1.8 by iionly in 2011
 */

class Userpoint extends ElggObject {

	/**
	 * A single-word arbitrary string that defines what
	 * kind of object this is
	 *
	 * @var string
	 */
	const SUBTYPE = 'userpoint';

	/**
	 * {@inheritDoc}
	 * @see ElggObject::initializeAttributes()
	 */
	protected function initializeAttributes() {

		parent::initializeAttributes();

		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['access_id'] = ACCESS_PUBLIC;
	}

	/**
	 * {@inheritDoc}
	 * @see ElggEntity::canDelete()
	 */
	public function canDelete($user_guid = 0) {

		$user_guid = (int) $user_guid;
		if (empty($user_guid)) {
			$user = elgg_get_logged_in_user_entity();
		} else {
			$user = get_user($user_guid);
		}

		return $user->isAdmin();;
	}
}
