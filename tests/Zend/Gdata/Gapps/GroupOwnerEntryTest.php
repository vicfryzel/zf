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
require_once 'Zend/Gdata/Gapps/GroupOwnerEntry.php';
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
class Zend_Gdata_Gapps_GroupOwnerEntryTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        $this->entryText = file_get_contents(
                'Zend/Gdata/Gapps/_files/GroupOwnerEntryDataSample1.xml',
                true);
        $this->entry = new Zend_Gdata_Gapps_GroupOwnerEntry();
    }

    private function createProperty($name, $value) {
        return new Zend_Gdata_Gapps_Extension_Property($name, $value);
    }

    private function verifyAllSamplePropertiesAreCorrect($entry) {
        $this->assertEquals('https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales/owner/suejones%40example.com',
                $entry->getLink('self')->href);
        $this->assertEquals('self', $entry->getLink('self')->rel);
        $this->assertEquals('application/atom+xml',
                $entry->getLink('self')->type);
        $this->assertEquals('https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales/owner/suejones%40example.com',
                $entry->getLink('edit')->href);
        $this->assertEquals('edit', $entry->getLink('edit')->rel);
        $this->assertEquals('application/atom+xml',
                $entry->getLink('edit')->type);
        $this->assertEquals($this->createProperty('email',
                'suejones@example.com'), $entry->getEmail());
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

    public function testEmptyEntryToAndFromStringShouldMatch() {
        $entryXml = $this->entry->saveXML();
        $newEntry = new Zend_Gdata_Gapps_GroupOwnerEntry();
        $newEntry->transferFromXML($entryXml);
        $newEntryXml = $newEntry->saveXML();
        $this->assertTrue($entryXml == $newEntryXml);
    }

    public function testSamplePropertiesAreCorrect() {
        $this->entry->transferFromXML($this->entryText);
        $this->verifyAllSamplePropertiesAreCorrect($this->entry);
    }

    public function testConvertEntryToAndFromString() {
        $this->entry->transferFromXML($this->entryText);
        $entryXml = $this->entry->saveXML();
        $newEntry = new Zend_Gdata_Gapps_GroupOwnerEntry();
        $newEntry->transferFromXML($entryXml);
        $this->verifyAllSamplePropertiesAreCorrect($newEntry);
        $newEntryXml = $newEntry->saveXML();
        $this->assertEquals($entryXml, $newEntryXml);
    }

}
