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
 * Data model class for a Google Apps Group Owner Entry.
 *
 * Each group owner entry describes a single owner of a Google Apps Group. Each
 * group may have zero or more owners.  Multiple entries are contained within
 * instances of Zend_Gdata_Gapps_GroupOwnerFeed.
 *
 * To transfer group owners to and from the Google Apps servers,
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
class Zend_Gdata_Gapps_GroupOwnerEntry extends Zend_Gdata_Gapps_PropertyEntry
{

    protected $_entryClassName = 'Zend_Gdata_Gapps_GroupOwnerEntry';

    /**
     * Get this owner's email address.
     *
     * @see setEmail
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    public function getEmail()
    {
        return $this->getProperty("email");
    }

    /**
     * Set the email of this owner entry.
     *
     * @see getEmail
     * @param String $value The new email for this owner entry.
     * @return Zend_Gdata_Gapps_GroupEntry Provides a fluent interface.
     */
    public function setEmail($value)
    {
        $this->setProperty("email", $value);
        return $this;
    }

}
