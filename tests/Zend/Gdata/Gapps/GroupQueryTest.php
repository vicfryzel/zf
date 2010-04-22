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
require_once 'Zend/Gdata/Gapps/GroupQuery.php';

/**
 * @category   Zend
 * @package    Zend_Gdata_Gapps
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Gapps
 */
class Zend_Gdata_Gapps_GroupQueryTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->query = new Zend_Gdata_Gapps_GroupQuery();
    }

    // Test to make sure that URI generation works
    public function testDefaultQueryURIGeneration()
    {
        $this->query->setDomain("foo.bar.invalid");
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/foo.bar.invalid",
                $this->query->getQueryUrl());
    }

    // Test to make sure that the domain accessor methods work and propagate
    // to the query URI.
    public function testCanSetQueryDomain()
    {
        $this->query->setDomain("my.domain.com");
        $this->assertEquals("my.domain.com", $this->query->getDomain());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com",
                $this->query->getQueryUrl());

        $this->query->setDomain("hello.world.baz");
        $this->assertEquals("hello.world.baz", $this->query->getDomain());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/hello.world.baz",
                $this->query->getQueryUrl());
    }

    public function testCanSetGroupIdProperty()
    {
        $this->query->setDomain("my.domain.com");
        $this->query->setGroupId("mygroup");
        $this->assertEquals("mygroup", $this->query->getGroupId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com/mygroup",
                $this->query->getQueryUrl());

        $this->query->setGroupId(null);
        $this->assertEquals(null, $this->query->getGroupId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com",
                $this->query->getQueryUrl());
    }

    public function testCanSetMemberProperty()
    {
        $this->query->setDomain("my.domain.com");
        $this->query->setMember("joe");
        $this->assertEquals("joe", $this->query->getMember());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com?member=joe",
                $this->query->getQueryUrl());

        $this->query->setMember("eric");
        $this->assertEquals("eric", $this->query->getMember());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com?member=eric",
                $this->query->getQueryUrl());

        $this->query->setMember(null);
        $this->assertEquals(null, $this->query->getMember());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com",
                $this->query->getQueryUrl());
    }

    public function testCanSetDirectOnlyProperty()
    {
        $this->query->setDomain("my.domain.com");
        $this->query->setMember("urs");
        $this->query->setDirectOnly(true);
        $this->assertEquals("urs", $this->query->getMember());
        $this->assertEquals(true, $this->query->getDirectOnly());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com?member=urs&directOnly=true",
                $this->query->getQueryUrl());

        $this->query->setDirectOnly(false);
        $this->assertEquals(false, $this->query->getDirectOnly());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com?member=urs",
                $this->query->getQueryUrl());

        $this->query->setDirectOnly(null);
        $this->assertEquals(null, $this->query->getDirectOnly());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com?member=urs",
                $this->query->getQueryUrl());
    }

    public function testCanSetStartGroupIdProperty()
    {
        $this->query->setDomain("my.domain.com");
        $this->query->setStartGroupId("mygroup");
        $this->assertEquals("mygroup", $this->query->getStartGroupId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com?start=mygroup",
                $this->query->getQueryUrl());

        $this->query->setStartGroupId(null);
        $this->assertEquals(false, $this->query->getDirectOnly());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com",
                $this->query->getQueryUrl());
    }

    // Test to make sure that all parameters can be set simultaneously with no
    // ill effects.
    public function testCanSetAllParameters()
    {
        $this->query->setDomain("my.domain.com");
        $this->query->setGroupId("admins");
        $this->query->setMember("jim");
        $this->query->setDirectOnly(true);
        $this->query->setStartGroupId("users");
        $this->assertEquals("admins", $this->query->getGroupId());
        $this->assertEquals("jim", $this->query->getMember());
        $this->assertEquals(true, $this->query->getDirectOnly());
        $this->assertEquals("users", $this->query->getStartGroupId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com/admins?member=jim&directOnly=true&start=users",
                $this->query->getQueryUrl());

        $this->query->setGroupId("donuts");
        $this->query->setMember("blueberry");
        $this->query->setDirectOnly(false);
        $this->query->setStartGroupId("muffins");
        $this->assertEquals("donuts", $this->query->getGroupId());
        $this->assertEquals("blueberry", $this->query->getMember());
        $this->assertEquals(false, $this->query->getDirectOnly());
        $this->assertEquals("muffins", $this->query->getStartGroupId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com/donuts?member=blueberry&start=muffins",
                $this->query->getQueryUrl());
    }

}
