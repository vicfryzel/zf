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
require_once 'Zend/Gdata/Gapps/GroupMemberQuery.php';

/**
 * @category   Zend
 * @package    Zend_Gdata_Gapps
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Gapps
 */
class Zend_Gdata_Gapps_GroupMemberQueryTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->query = new Zend_Gdata_Gapps_GroupMemberQuery("example.com", "us-sales");
    }

    // Test to make sure that URI generation works
    public function testDefaultQueryURIGeneration()
    {
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales/member",
                $this->query->getQueryUrl());
    }

    // Test to make sure that the domain accessor methods work and propagate
    // to the query URI.
    public function testCanSetQueryDomain()
    {
        $this->assertEquals("example.com", $this->query->getDomain());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales/member",
                $this->query->getQueryUrl());


        $this->query->setDomain("my.domain.com");
        $this->assertEquals("my.domain.com", $this->query->getDomain());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com/us-sales/member",
                $this->query->getQueryUrl());

        $this->query->setDomain("hello.world.baz");
        $this->assertEquals("hello.world.baz", $this->query->getDomain());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/hello.world.baz/us-sales/member",
                $this->query->getQueryUrl());
    }

    public function testCanSetGroupIdProperty()
    {
        $this->assertEquals("us-sales", $this->query->getGroupId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales/member",
                $this->query->getQueryUrl());

        $this->query->setGroupId("mygroup");
        $this->assertEquals("mygroup", $this->query->getGroupId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/example.com/mygroup/member",
                $this->query->getQueryUrl());
    }

    public function testCanSetMemberIdProperty()
    {
        $this->query->setMemberId("joe@example.com");
        $this->assertEquals("joe@example.com", $this->query->getMemberId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales/member/joe%40example.com",
                $this->query->getQueryUrl());

        $this->query->setMemberId("eric@example.com");
        $this->assertEquals("eric@example.com", $this->query->getMemberId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales/member/eric%40example.com",
                $this->query->getQueryUrl());

        $this->query->setMemberId(null);
        $this->assertEquals(null, $this->query->getMemberId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales/member",
                $this->query->getQueryUrl());
    }

    public function testCanSetStartMemberIdProperty()
    {
        $this->query->setStartMemberId("joe@example.com");
        $this->assertEquals("joe@example.com", $this->query->getStartMemberId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales/member?start=joe%40example.com",
                $this->query->getQueryUrl());

        $this->query->setStartMemberId(null);
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/example.com/us-sales/member",
                $this->query->getQueryUrl());
    }

    /**
     * Test to make sure that all parameters can be set simultaneously with no
     * ill effects.
     */
    public function testCanSetAllParameters()
    {
        $this->query->setDomain("my.domain.com");
        $this->query->setGroupId("admins");
        $this->query->setMemberId("jim@my.domain.com");
        $this->query->setStartMemberId("lou@my.domain.com");
        $this->assertEquals("my.domain.com", $this->query->getDomain());
        $this->assertEquals("admins", $this->query->getGroupId());
        $this->assertEquals("jim@my.domain.com", $this->query->getMemberId());
        $this->assertEquals("lou@my.domain.com",
                $this->query->getStartMemberId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/my.domain.com/admins/member/jim%40my.domain.com?start=lou%40my.domain.com",
                $this->query->getQueryUrl());

        $this->query->setDomain("bake.ry");
        $this->query->setGroupId("donuts");
        $this->query->setMemberId("blueberry@bake.ry");
        $this->query->setStartMemberId("muffins@bake.ry");
        $this->assertEquals("bake.ry", $this->query->getDomain());
        $this->assertEquals("donuts", $this->query->getGroupId());
        $this->assertEquals("blueberry@bake.ry", $this->query->getMemberId());
        $this->assertEquals("muffins@bake.ry", $this->query->getStartMemberId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/group/2.0/bake.ry/donuts/member/blueberry%40bake.ry?start=muffins%40bake.ry",
                $this->query->getQueryUrl());
    }

}
