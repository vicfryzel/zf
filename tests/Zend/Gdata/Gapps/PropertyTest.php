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
 * @package    Zend_Gdata_Gapps
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id $
 */

require_once 'Zend/Gdata/Gapps/Extension/Property.php';
require_once 'Zend/Gdata.php';

/**
 * @category   Zend
 * @package    Zend_Gdata_Gapps
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Gapps
 */
class Zend_Gdata_Gapps_PropertyTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        $this->propertyText = file_get_contents(
                'Zend/Gdata/Gapps/_files/PropertyElementSample1.xml',
                true);
        $this->property = new Zend_Gdata_Gapps_Extension_Property();
    }

    public function testEmptyPropertyShouldHaveNoExtensionElements() {
        $this->assertTrue(is_array($this->property->extensionElements));
        $this->assertTrue(count($this->property->extensionElements) == 0);
    }

    public function testEmptyPropertyShouldHaveNoExtensionAttributes() {
        $this->assertTrue(is_array($this->property->extensionAttributes));
        $this->assertTrue(count($this->property->extensionAttributes) == 0);
    }

    public function testSamplePropertyShouldHaveNoExtensionElements() {
        $this->property->transferFromXML($this->propertyText);
        $this->assertTrue(is_array($this->property->extensionElements));
        $this->assertTrue(count($this->property->extensionElements) == 0);
    }

    public function testSamplePropertyShouldHaveNoExtensionAttributes() {
        $this->property->transferFromXML($this->propertyText);
        $this->assertTrue(is_array($this->property->extensionAttributes));
        $this->assertTrue(count($this->property->extensionAttributes) == 0);
    }

    public function testNormalPropertyShouldHaveNoExtensionElements() {
        $this->property->name = "HairColor";
        $this->property->value = "Red";

        $this->assertEquals("HairColor", $this->property->name);
        $this->assertEquals("Red", $this->property->value);

        $this->assertEquals(0, count($this->property->extensionElements));
        $newProperty = new Zend_Gdata_Gapps_Extension_Property();
        $newProperty->transferFromXML($this->property->saveXML());
        $this->assertEquals(0, count($newProperty->extensionElements));
        $newProperty->extensionElements = array(
                new Zend_Gdata_App_Extension_Element('foo', 'atom', null, 'bar'));
        $this->assertEquals(1, count($newProperty->extensionElements));
        $this->assertEquals("HairColor", $newProperty->name);
        $this->assertEquals("Red", $newProperty->value);

        /* try constructing using magic factory */
        $gdata = new Zend_Gdata_Gapps();
        $newProperty2 = $gdata->newProperty();
        $newProperty2->transferFromXML($newProperty->saveXML());
        $this->assertEquals(1, count($newProperty2->extensionElements));
        $this->assertEquals("HairColor", $newProperty2->name);
        $this->assertEquals("Red", $newProperty2->value);
    }

    public function testEmptyPropertyToAndFromStringShouldMatch() {
        $propertyXml = $this->property->saveXML();
        $newProperty = new Zend_Gdata_Gapps_Extension_Property();
        $newProperty->transferFromXML($propertyXml);
        $newPropertyXml = $newProperty->saveXML();
        $this->assertTrue($propertyXml == $newPropertyXml);
    }

    public function testPropertyWithValueToAndFromStringShouldMatch() {
        $this->property->name = "HairColor";
        $this->property->value = "Red";
        $propertyXml = $this->property->saveXML();
        $newProperty = new Zend_Gdata_Gapps_Extension_Property();
        $newProperty->transferFromXML($propertyXml);
        $newPropertyXml = $newProperty->saveXML();
        $this->assertEquals($propertyXml, $newPropertyXml);
        $this->assertEquals("HairColor", $this->property->name);
        $this->assertEquals("Red", $this->property->value);
    }

    public function testExtensionAttributes() {
        $extensionAttributes = $this->property->extensionAttributes;
        $extensionAttributes['foo1'] = array('name'=>'foo1', 'value'=>'bar');
        $extensionAttributes['foo2'] = array('name'=>'foo2', 'value'=>'rab');
        $this->property->extensionAttributes = $extensionAttributes;
        $this->assertEquals('bar', $this->property->extensionAttributes['foo1']['value']);
        $this->assertEquals('rab', $this->property->extensionAttributes['foo2']['value']);
        $propertyXml = $this->property->saveXML();
        $newProperty = new Zend_Gdata_Gapps_Extension_Property();
        $newProperty->transferFromXML($propertyXml);
        $this->assertEquals('bar', $newProperty->extensionAttributes['foo1']['value']);
        $this->assertEquals('rab', $newProperty->extensionAttributes['foo2']['value']);
    }

    public function testConvertFullPropertyToAndFromString() {
        $this->property->transferFromXML($this->propertyText);
        $this->assertEquals("groupId", $this->property->name);
        $this->assertEquals("us-sales", $this->property->value);
    }
}
