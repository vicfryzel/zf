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
require_once 'Zend/Gdata/Gapps/GroupEntry.php';
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
class Zend_Gdata_Gapps_GroupEntryTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        $this->entryText = file_get_contents(
                'Zend/Gdata/Gapps/_files/GroupEntryDataSample1.xml',
                true);
        $this->entry = new Zend_Gdata_Gapps_GroupEntry();
    }

    private function createProperty($name, $value) {
        return new Zend_Gdata_Gapps_Extension_Property($name, $value);
    }

    private function verifyAllSamplePropertiesAreCorrect ($groupEntry) {
        $this->assertEquals('https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales%40example.com',
                $groupEntry->getLink('self')->href);
        $this->assertEquals('self', $groupEntry->getLink('self')->rel);
        $this->assertEquals('application/atom+xml',
                $groupEntry->getLink('self')->type);
        $this->assertEquals('https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales%40example.com',
                $groupEntry->getLink('edit')->href);
        $this->assertEquals('edit', $groupEntry->getLink('edit')->rel);
        $this->assertEquals('application/atom+xml',
                $groupEntry->getLink('edit')->type);
        $this->assertEquals($this->createProperty('groupId',
                'us-sales@example.com'), $groupEntry->getGroupId());
        $this->assertEquals($this->createProperty('groupName', 'us-sales'),
                $groupEntry->getName());
        $this->assertEquals($this->createProperty('description',
                'UnitedStatesSalesTeam'), $groupEntry->getDescription());
        $this->assertEquals($this->createProperty('emailPermission', 'Domain'),
                $groupEntry->getEmailPermission());
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

    public function testEmptyGroupEntryToAndFromStringShouldMatch() {
        $entryXml = $this->entry->saveXML();
        $newGroupEntry = new Zend_Gdata_Gapps_GroupEntry();
        $newGroupEntry->transferFromXML($entryXml);
        $newGroupEntryXml = $newGroupEntry->saveXML();
        $this->assertTrue($entryXml == $newGroupEntryXml);
    }

    public function testSamplePropertiesAreCorrect() {
        $this->entry->transferFromXML($this->entryText);
        $this->verifyAllSamplePropertiesAreCorrect($this->entry);
    }

    public function testConvertGroupEntryToAndFromString() {
        $this->entry->transferFromXML($this->entryText);
        $entryXml = $this->entry->saveXML();
        $newGroupEntry = new Zend_Gdata_Gapps_GroupEntry();
        $newGroupEntry->transferFromXML($entryXml);
        $this->verifyAllSamplePropertiesAreCorrect($newGroupEntry);
        $newGroupEntryXml = $newGroupEntry->saveXML();
        $this->assertEquals($entryXml, $newGroupEntryXml);
    }

}
