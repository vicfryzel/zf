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
 * Data model class for a Google Apps Calendar Resource Entry.
 *
 * Each calendar resource entry describes a single calendar resource within a
 * Google Apps hosted domain. Each domain may have several groups.
 * Multiple entries are contained within instances of
 * Zend_Gdata_Gapps_GroupFeed.
 *
 * To transfer calendar resource entries to and from the Google Apps servers,
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
class Zend_Gdata_Gapps_CalendarResourceEntry extends Zend_Gdata_Gapps_PropertyEntry
{

    protected $_entryClassName = 'Zend_Gdata_Gapps_CalendarResourceEntry';

    /**
     * Get this calendar resource's ID.
     *
     * @see setResourceId
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getResourceId()
    {
        return $this->getProperty("resourceId");
    }

    /**
     * Set the ID of this calendar resource.
     *
     * @see getResourceId
     * @param String $value The new ID for this resource.
     * @return Zend_Gdata_Gapps_CalendarResourceEntry Provides a fluent
     *         interface.
     */
    public function setResourceId($value)
    {
        $this->setProperty("resourceId", $value);
        return $this;
    }

    /**
     * Get the common name of this resource.
     *
     * @see setCommonName
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getCommonName()
    {
        return $this->getProperty("resourceCommonName");
    }

    /**
     * Set the common name of this resource to the given value.
     *
     * @see getCommonName
     * @param String $value The new common name for this resource.
     * @return Zend_Gdata_Gapps_CalendarResourceEntry Provides a fluent
     *         interface.
     */
    public function setCommonName($value)
    {
        $this->setProperty("resourceCommonName", $value);
        return $this;
    }

    /**
     * Get the description of this resource.
     *
     * @see setDescription
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getDescription()
    {
        return $this->getProperty("resourceDescription");
    }

    /**
     * Set the description of this resource to the given value.
     *
     * @see getDescription
     * @param String $value The new description of this resource.
     * @return Zend_Gdata_Gapps_CalendarResourceEntry Provides a fluent
     *         interface.
     */
    public function setDescription($value)
    {
        $this->setProperty("resourceDescription", $value);
        return $this;
    }

    /**
     * Get the value of the resourceType  property for this resource.
     *
     * @see setResourceType
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getResourceType()
    {
        return $this->getProperty("resourceType");
    }

    /**
     * Set the value of the resourceType property for this group.
     *
     * @see getResourceType
     * @param String $value The new resourceType value for this calendar 
     *         resource.
     * @return Zend_Gdata_Gapps_CalendarResourceEntry Provides a fluent
     *         interface.
     */
    public function setResourceType($value)
    {
        $this->setProperty("resourceType", $value);
        return $this;
    }
}
