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
 * @see Zend_Gdata_Gapps_Extension_PropertyEntry
 */
require_once 'Zend/Gdata/Gapps/PropertyEntry.php';

/**
 * Data model class for a Google Apps Group Entry.
 *
 * Each group entry describes a single group within a Google Apps
 * hosted domain. Each domain may have several groups.
 * Multiple entries are contained within instances of
 * Zend_Gdata_Gapps_GroupFeed.
 *
 * To transfer group entries to and from the Google Apps servers,
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
class Zend_Gdata_Gapps_GroupEntry extends Zend_Gdata_Gapps_PropertyEntry
{

    protected $_entryClassName = 'Zend_Gdata_Gapps_GroupEntry';

    /**
     * The value of the emailPermission property for allowing only owners 
     * to email the group.
     */
    const EMAIL_PERMISSION_OWNER = "Owner";

    /**
     * The value of the emailPermission property for allowing only members
     * of the group to email the group.
     */
    const EMAIL_PERMISSION_MEMBER = "Member";

    /**
     * The value of the emailPermission property for allowing only domain
     * users to email the group.
     */
    const EMAIL_PERMISSION_DOMAIN = "Domain";

    /**
     * The value of the emailPermission property for allowing anyone to email 
     * the group.
     */
    const EMAIL_PERMISSION_ANYONE = "Anyone";

    /**
     * Get this group's ID.
     *
     * @see setGroupId
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getGroupId()
    {
        return $this->getProperty("groupId");
    }

    /**
     * Set the ID of this group.
     *
     * @see getGroupId
     * @param String $value The new ID for this group.
     * @return Zend_Gdata_Gapps_GroupEntry Provides a fluent interface.
     */
    public function setGroupId($value)
    {
        $this->setProperty("groupId", $value);
        return $this;
    }

    /**
     * Get the name of this group.
     *
     * @see setName
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getName()
    {
        return $this->getProperty("groupName");
    }

    /**
     * Set the name of this group to the given value.
     *
     * @see getName
     * @param String $value The new name for this group.
     * @return Zend_Gdata_Gapps_GroupEntry Provides a fluent interface.
     */
    public function setName($value)
    {
        $this->setProperty("groupName", $value);
        return $this;
    }

    /**
     * Get the description of this group.
     *
     * @see setDescription
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getDescription()
    {
        return $this->getProperty("description");
    }

    /**
     * Set the description of this group to the given value.
     *
     * @see getDescription
     * @param String $value The new description of this group.
     * @return Zend_Gdata_Gapps_GroupEntry Provides a fluent interface.
     */
    public function setDescription($value)
    {
        $this->setProperty("description", $value);
        return $this;
    }

    /**
     * Get the value of the emailPermission property for this group.
     *
     * @see setEmailPermission
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getEmailPermission()
    {
        return $this->getProperty("emailPermission");
    }

    /**
     * Set the value of the emailPermission property for this group.
     *
     * @see getEmailPermission
     * @param String $value The new emailPermission value for this group.
     *                      Valid values for this parameter are defined 
     *                      as class constants in this class, of the name 
     *                      EMAIL_PERMISSION_*.
     * @return Zend_Gdata_Gapps_GroupEntry Provides a fluent interface.
     */
    public function setEmailPermission($value)
    {
        $this->setProperty("emailPermission", $value);
        return $this;
    }
}
