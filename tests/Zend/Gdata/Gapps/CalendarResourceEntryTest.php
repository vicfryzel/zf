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
require_once 'Zend/Gdata/Gapps/CalendarResourceEntry.php';
require_once 'Zend/Gdata/Gapps.php';

/**
 * @category   Zend
 * @package    Zend_Gdata_Gapps
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Gapps
 */
class Zend_Gdata_Gapps_CalendarResourceEntryTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        $this->entryText = file_get_contents(
                'Zend/Gdata/Gapps/_files/CalendarResourceEntryDataSample1.xml',
                true);
        $this->entry = new Zend_Gdata_Gapps_CalendarResourceEntry();
    }

    private function createProperty($name, $value) {
        return new Zend_Gdata_Gapps_Extension_Property($name, $value);
    }

    private function verifyAllSamplePropertiesAreCorrect ($entry) {
        $this->assertEquals('https://apps-apis.google.com/a/feeds/calendar/resource/2.0/example.com/CR-NY-14-12-BR',
                $entry->getLink('self')->href);
        $this->assertEquals('self', $entry->getLink('self')->rel);
        $this->assertEquals('application/atom+xml',
                $entry->getLink('self')->type);
        $this->assertEquals('https://apps-apis.google.com/a/feeds/calendar/resource/2.0/example.com/CR-NY-14-12-BR',
                $entry->getLink('edit')->href);
        $this->assertEquals('edit', $entry->getLink('edit')->rel);
        $this->assertEquals('application/atom+xml',
                $entry->getLink('edit')->type);
        $this->assertEquals($this->createProperty('resourceId',
                'CR-NY-14-12-BR'), $entry->getResourceId());
        $this->assertEquals($this->createProperty('resourceCommonName', 'Boardroom'),
                $entry->getCommonName());
        $this->assertEquals($this->createProperty('resourceDescription',
                'This conference room is in New York City, building 14, floor 12, Boardroom'),
                $entry->getDescription());
        $this->assertEquals($this->createProperty('resourceType', 'CR'),
                $entry->getResourceType());
    }

    public function testEmptyEntryShouldHaveNoExtensionElements() {
        $this->assertTrue(is_array($this->entry->extensionElements));
        $this->assertTrue(count($this->entry->extensionElements) == 0);
    }

    public function testEmptyEntryShouldHaveNoExtensionAttributes() {
        $this->assertTrue(is_array($this->entry->extensionAttributes));
        $this->assertTrue(count($this->entry->extensionAttributes) == 0);
    }

    public function testSampleEntryShouldHaveNoExtensionAttributes() {
        $this->entry->transferFromXML($this->entryText);
        $this->assertTrue(is_array($this->entry->extensionAttributes));
        $this->assertTrue(count($this->entry->extensionAttributes) == 0);
    }

    public function testEmptyCalendarResourceEntryToAndFromStringShouldMatch() {
        $entryXml = $this->entry->saveXML();
        $newEntry = new Zend_Gdata_Gapps_CalendarResourceEntry();
        $newEntry->transferFromXML($entryXml);
        $newEntryXml = $newEntry->saveXML();
        $this->assertTrue($entryXml == $newEntryXml);
    }

    public function testSamplePropertiesAreCorrect() {
        $this->entry->transferFromXML($this->entryText);
        $this->verifyAllSamplePropertiesAreCorrect($this->entry);
    }

    public function testConvertCalendarResourceEntryToAndFromString() {
        $this->entry->transferFromXML($this->entryText);
        $entryXml = $this->entry->saveXML();
        $newEntry = new Zend_Gdata_Gapps_CalendarResourceEntry();
        $newEntry->transferFromXML($entryXml);
        $this->verifyAllSamplePropertiesAreCorrect($newEntry);
        $newEntryXml = $newEntry->saveXML();
        $this->assertEquals($entryXml, $newEntryXml);
    }

}
