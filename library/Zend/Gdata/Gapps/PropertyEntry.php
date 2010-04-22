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
 * @see Zend_Gdata_Entry
 */
require_once 'Zend/Gdata/Entry.php';

/**
 * @see Zend_Gdata_Gapps_Extension_Property
 */
require_once 'Zend/Gdata/Gapps/Extension/Property.php';

/**
 * Base data model class for a Google Apps Group Entry.
 *
 * Each group, group member, or group owner entry describes utilizes common 
 * feed mechanisms such as the user of Property elements.  This class abstracts 
 * many of those mechanisms.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Gapps
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Gapps_PropertyEntry extends Zend_Gdata_Entry
{
    /**
     * <apps:property> elements used to hold information about this group.
     *
     * @var Zend_Gdata_Gapps_Extension_Property
     */
    protected $_properties = null;

    /**
     * Create a new instance.
     *
     * @param DOMElement $element (optional) DOMElement from which this
     *          object should be constructed.
     */
    public function __construct($element = null)
    {
        $this->registerAllNamespaces(Zend_Gdata_Gapps::$namespaces);
        parent::__construct($element);
    }

    /**
     * Retrieves a DOMElement which corresponds to this element and all
     * child properties.  This is used to build an entry back into a DOM
     * and eventually XML text for application storage/persistence.
     *
     * @param DOMDocument $doc The DOMDocument used to construct DOMElements
     * @return DOMElement The DOMElement representing this element and all
     *          child properties.
     */
    public function getDOM($doc = null, $majorVersion = 1, $minorVersion = null)
    {
        $element = parent::getDOM($doc, $majorVersion, $minorVersion);
        if ($this->_properties !== null) {
            foreach ($this->_properties as $property) {
                $element->appendChild(
                        $property->getDOM($element->ownerDocument));
            }
        }
        return $element;
    }

    /**
     * Creates individual Entry objects of the appropriate type and
     * stores them as members of this entry based upon DOM data.
     *
     * @param DOMNode $child The DOMNode to process
     */
    protected function takeChildFromDOM($child)
    {
        $absoluteNodeName = $child->namespaceURI . ':' . $child->localName;

        switch ($absoluteNodeName) {
            case $this->lookupNamespace('apps') . ':' . 'property';
                if ($this->_properties === null) {
                    $this->_properties = array();
                }
                $property = new Zend_Gdata_Gapps_Extension_Property();
                $property->transferFromDOM($child);
                $this->_properties[] = $property;
                break;
            default:
                parent::takeChildFromDOM($child);
                break;
        }
    }

    /**
     * Get a specific property of this entry out of $this->_properties.
     *
     * @return Zend_Gdata_Gapps_Extension_Property The requested object.
     */
    protected function getProperty($name)
    {
        $retval = null;
        foreach ($this->_properties as $property) {
            if ($name == $property->getName()) {
                $retval = $property;
                break;
            }
        }
        return $retval;
    }

    /**
     * Set a specific property of this entry out of $this->_properties
     * to the given value.
     *
     * @param String $value The name of the property to set
     * @param String $value The value to give the property
     * @return Zend_Gdata_Gapps_Extension_Property The object to revalue.
     */
    protected function setProperty($name, $value)
    {
        $retval = null;
        $numProperties = count($this->_properties);
        for ($i = 0; $i < $numProperties; $i++) {
            if ($name == $this->_properties[$i]->getName()) {
                $this->_properties[$i]->setValue($value);
                $retval = $this->_properties[$i];
                break;
            }
        }
        return $retval;
    }
}
