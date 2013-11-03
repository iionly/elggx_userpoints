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
     * @access private
     */
    private $subtype = "userpoint";

    /**
     * Set up the subtype.
     *
     * @see engine/classes/ElggObject#initializeAttributes()
     */
    protected function initializeAttributes() {

        parent::initializeAttributes();

        $this->attributes['subtype'] = "userpoint";
    }


    /**
     * Class constructor
     *
     * @param integer  $guid The object guid
     * @param integer  $user_guid The users guid
     * @param string   $description The description (reason) for these points
     */
    public function __construct($guid=null, $user_guid=null, $description=null) {

        parent::__construct($guid);

        if ($guid) {
            return true;
        }

        if (!$user = get_entity($user_guid)) {
            return false;
        }

        $this->attributes['owner_guid'] = $user_guid;
        $this->attributes['container_guid'] = $user_guid;
        $this->attributes['description'] = $description;
    }
}
