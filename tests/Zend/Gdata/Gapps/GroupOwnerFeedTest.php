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

require_once 'Zend/Gdata/Gapps.php';
require_once 'Zend/Gdata/Gapps/GroupOwnerFeed.php';

/**
 * @category   Zend
 * @package    Zend_Gdata_Gapps
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Gapps
 */
class Zend_Gdata_Gapps_GroupOwnerFeedTest extends PHPUnit_Framework_TestCase
{
    protected $feed = null;

    public function setUp()
    {
        $feedText = file_get_contents(
                'Zend/Gdata/Gapps/_files/GroupOwnerFeedDataSample1.xml',
                true);
        $this->feed = new Zend_Gdata_Gapps_GroupOwnerFeed($feedText);
        $this->emptyFeed = new Zend_Gdata_Gapps_GroupOwnerFeed();
    }

    public function testEmptyFeedShouldHaveNoExtensionElements() {
        $this->assertTrue(is_array($this->emptyFeed->extensionElements));
        $this->assertTrue(count($this->emptyFeed->extensionElements) == 0);
    }

    public function testEmptyFeedShouldHaveNoExtensionAttributes() {
        $this->assertTrue(is_array($this->emptyFeed->extensionAttributes));
        $this->assertTrue(count($this->emptyFeed->extensionAttributes) == 0);
    }

    public function testSampleFeedShouldHaveNoExtensionElements() {
        $this->assertTrue(is_array($this->feed->extensionElements));
        $this->assertTrue(count($this->feed->extensionElements) == 0);
    }

    public function testSampleFeedShouldHaveNoExtensionAttributes() {
        $this->assertTrue(is_array($this->feed->extensionAttributes));
        $this->assertTrue(count($this->feed->extensionAttributes) == 0);
    }

    /**
      * Convert sample feed to XML then back to objects. Ensure that
      * all objects are instances of GroupOwnerEntry and object count matches.
      */
    public function testXmlImportAndOutputAreNonDestructive()
    {
        $entryCount = 0;
        foreach ($this->feed as $entry) {
            $entryCount++;
            $this->assertTrue(
                    $entry instanceof Zend_Gdata_Gapps_GroupOwnerEntry);
        }
        $this->assertTrue($entryCount > 0);

        /* Grab XML from $this->feed and convert back to objects */
        $newFeed = new Zend_Gdata_Gapps_GroupOwnerFeed(
                $this->feed->saveXML());
        $newEntryCount = 0;
        foreach ($newFeed as $entry) {
            $newEntryCount++;
            $this->assertTrue(
                    $entry instanceof Zend_Gdata_Gapps_GroupOwnerEntry);
        }
        $this->assertEquals($entryCount, $newEntryCount);
    }

    /**
      * Ensure that the number of entries is correct.
      */
    public function testAllEntriesInFeedAreInstantiated()
    {
        $entryCount = 0;
        foreach ($this->feed as $entry) {
            $entryCount++;
        }
        $this->assertEquals(2, $entryCount);
    }

}
