<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Gapps
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Gdata_Gapps_Extension_Property
 */
require_once 'Zend/Gdata/Gapps/Extension/Property.php';

/**
 * @see Zend_Gdata_Gapps_PropertyEntry
 */
require_once 'Zend/Gdata/Gapps/PropertyEntry.php';


/**
 * Data model class for a Google Apps Group Member Entry.
 *
 * Each group entry describes a single member within a Google Apps Group. Each
 * group may have zero or more members.  Multiple entries are contained within
 * instances of Zend_Gdata_Gapps_GroupMemberFeed.
 *
 * To transfer group members to and from the Google Apps servers,
 * including creating new entries, refer to the Google Apps service class,
 * Zend_Gdata_Gapps.
 *
 * This class represents <atom:entry> in the Google Data protocol.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Gapps
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Gapps_GroupMemberEntry extends Zend_Gdata_Gapps_PropertyEntry
{

    protected $_entryClassName = 'Zend_Gdata_Gapps_GroupMemberEntry';

    /**
     * The value of the memberType property when this member is an actual domain
     * user.
     */
    const MEMBER_TYPE_USER = "User";

    /**
     * The value of the memberType property when this member is another domain 
     * group.
     */
    const MEMBER_TYPE_GROUP = "Group";

    /**
     * Get this member's user ID.
     *
     * @see setMemberId
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getMemberId()
    {
        return $this->getProperty("memberId");
    }

    /**
     * Set the user ID of this member entry.
     *
     * @see getMemberId
     * @param String $value The new user ID for this member entry.
     * @return Zend_Gdata_Gapps_GroupEntry Provides a fluent interface.
     */
    public function setMemberId($value)
    {
        $this->setProperty("memberId", $value);
        return $this;
    }

    /**
     * Get the memberType of this member entry. The returned property will have 
     * a value of either Zend_Gdata_Gapps_GroupMemberEntry::MEMBER_TYPE_USER or 
     * Zend_Gdata_Gapps_GroupMemberEntry::MEMBER_TYPE_GROUP.
     *
     * @see setMemberType
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getMemberType()
    {
        return $this->getProperty("memberType");
    }

    /**
     * Set the memberType of this member entry to the given value.
     *
     * @see getMemberType
     * @param String $value The new memberType for this group. either 
     *         Zend_Gdata_Gapps_GroupMemberEntry::MEMBER_TYPE_USER or
     *         Zend_Gdata_Gapps_GroupMemberEntry::MEMBER_TYPE_GROUP.
     * @return Zend_Gdata_Gapps_GroupEntry Provides a fluent interface.
     */
    public function setMemberType($value)
    {
        $this->setProperty("memberType", $value);
        return $this;
    }

    /**
     * Return whether or not this member entry represents a direct membership.
     * The returned value is a Property containing "true" or "false".
     *
     * @see setDirectMember
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getDirectMember()
    {
        return $this->getProperty("directMember");
    }

    /**
     * Set whether or not this member entry represents a direct membership.
     *
     * @see getDirectMember
     * @param String $value "true" if this is a direct membership, "false" if 
     *         otherwise.
     * @return Zend_Gdata_Gapps_GroupEntry Provides a fluent interface.
     */
    public function setDirectMember($value)
    {
        $this->setProperty("directMember", $value);
        return $this;
    }
}
