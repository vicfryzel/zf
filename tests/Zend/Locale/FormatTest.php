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
 * @package    Zend_Format
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * Zend_Locale_Format
 */
require_once 'Zend/Locale/Format.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';


/**
 * @package    Zend_Locale
 * @subpackage UnitTests
 */
class Zend_Locale_FormatTest extends PHPUnit_Framework_TestCase
{
    /**
     * test getNumber
     * expected integer
     */
    public function testGetNumber()
    {
        $this->assertEquals(Zend_Locale_Format::getNumber(0), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber(-1234567), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber(1234567), 1234567, "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber(0.1234567), 0.1234567, "value 0.1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber(-1234567.12345), -1234567.12345, "value -1234567.12345 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber(1234567.12345), 1234567.12345, "value 1234567.12345 expected");
        $options = array('locale' => 'de');
        $this->assertEquals(Zend_Locale_Format::getNumber('0', $options), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber('-1234567', $options), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber('1234567', $options), 1234567, "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber('0,1234567', $options), 0.1234567, "value 0.1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber('-1.234.567,12345', $options), -1234567.12345, "value -1234567.12345 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber('1.234.567,12345', $options), 1234567.12345, "value 1234567.12345 expected");
        $options = array('locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::getNumber('0', $options), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber('-1234567', $options), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber('1.234.567', $options), 1234567, "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber('0,1234567', $options), 0.1234567, "value 0.1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber('-1.234.567,12345', $options), -1234567.12345, "value -1234567.12345 expected");
        $this->assertEquals(Zend_Locale_Format::getNumber('1.234.567,12345', $options), 1234567.12345, "value 1234567.12345 expected");
    }


    /**
     * test to number
     * expected string
     */
    public function testToNumber()
    {
        $this->assertEquals(Zend_Locale_Format::toNumber(0), '0', "string 0 expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(0, array('locale' => 'de')), '0', "string 0 expected");
        $options = array('locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::toNumber(0, $options), '0', "string 0 expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(-1234567, $options), '-1.234.567', "string -1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(1234567, $options), '1.234.567', "string 1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(0.1234567, $options), '0,1234567', "string 0,1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(-1234567.12345, $options), '-1.234.567,12345', "string -1.234.567,12345 expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(1234567.12345, $options), '1.234.567,12345', "value 1.234.567,12345 expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(1234567.12345, array('locale' => 'ar_QA')), '1234567٫12345', "value 1234567٫12345 expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(-1234567.12345, array('locale' => 'ar_QA')), '1234567٫12345-', "value 1234567٫12345- expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(1234567.12345, array('locale' => 'dz_BT')), '12,34,567.12345', "value 12,34,567.12345 expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(-1234567.12345, array('locale' => 'mk_MK')), '-(1.234.567,12345)', "value -(1.234.567,12345) expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(452.25, array('locale' => 'en_US')), '452.25', "value 452.25 expected");
        $this->assertEquals(Zend_Locale_Format::toNumber(54321.1234, array('locale' => 'en_US')), '54,321.1234', "value 54,321.1234 expected");
    }


    /**
     * test isNumber
     * expected boolean
     */
    public function testIsNumber()
    {
        $this->assertEquals(Zend_Locale_Format::isNumber('-1.234.567,12345', array('locale' => 'de_AT')), true, "true expected");
        $this->assertEquals(Zend_Locale_Format::isNumber('textwithoutnumber', array('locale' => 'de_AT')), false, "false expected");
    }


    /**
     * test getFloat
     * expected exception
     */
    public function testgetFloat()
    {
        try {
            $value = Zend_Locale_Format::getFloat('nocontent');
            $this->fail("exception expected");
        } catch (Zend_Locale_Exception $e) {
            // success
        }

        $this->assertEquals(Zend_Locale_Format::getFloat(0), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat(-1234567), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat(1234567), 1234567, "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat(0.1234567), 0.1234567, "value 0.1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat(-1234567.12345), -1234567.12345, "value -1234567.12345 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat(1234567.12345), 1234567.12345, "value 1234567.12345 expected");
        $options = array('locale' => 'de');
        $this->assertEquals(Zend_Locale_Format::getFloat('0', $options), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('-1234567', $options), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('1234567', $options), 1234567, "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('0,1234567', $options), 0.1234567, "value 0.1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('-1.234.567,12345', $options), -1234567.12345, "value -1234567.12345 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('1.234.567,12345', $options), 1234567.12345, "value 1234567.12345 expected");
        $options = array('locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::getFloat('0', $options), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('-1234567', $options), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('1.234.567', $options), 1234567, "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('0,1234567', $options), 0.1234567, "value 0.1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('-1.234.567,12345', $options), -1234567.12345, "value -1234567.12345 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('1.234.567,12345', $options), 1234567.12345, "value 1234567.12345 expected");
        $options = array('precision' => 2, 'locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::getFloat('0', $options), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('-1234567', $options), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('1.234.567', $options), 1234567, "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('0,1234567', $options), 0.12, "value 0.12 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('-1.234.567,12345', $options), -1234567.12, "value -1234567.12 expected");
        $this->assertEquals(Zend_Locale_Format::getFloat('1.234.567,12345', $options), 1234567.12, "value 1234567.12 expected");
        $options = array('precision' => 7, 'locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::getFloat('1.234.567,12345', $options), '1234567.12345', "value 1234567.12345 expected");
    }


    /**
     * test toFloat
     * expected string
     */
    public function testToFloat()
    {
        $this->assertEquals(Zend_Locale_Format::toFloat(0), '0', "string 0 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(0, array('locale' => 'de')), '0', "string 0 expected");
        $options = array('locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::toFloat(0, $options), '0', "string 0 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(-1234567, $options), '-1.234.567', "string -1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(1234567, $options), '1.234.567', "string 1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(0.1234567, $options), '0,1234567', "string 0,1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(-1234567.12345, $options), '-1.234.567,12345', "string -1.234.567,12345 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(1234567.12345, $options), '1.234.567,12345', "value 1.234.567,12345 expected");
        $options = array('locale' => 'ar_QA');
        $this->assertEquals(Zend_Locale_Format::toFloat(1234567.12345, $options), '1234567٫12345', "value 1234567٫12345 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(-1234567.12345, $options), '1234567٫12345-', "value 1234567٫12345- expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(1234567.12345, array('locale' => 'dz_BT')), '12,34,567.12345', "value 12,34,567.12345 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(-1234567.12345, array('locale' => 'mk_MK')), '-(1.234.567,12345)', "value -(1.234.567,12345) expected");
        $options = array('precision' => 2, 'locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::toFloat(0, $options), '0,00', "value 0,00 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(-1234567, $options), '-1.234.567,00', "value -1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(1234567, $options), '1.234.567,00', "value 1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(0.1234567, $options), '0,12', "value 0,12 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(-1234567.12345, $options), '-1.234.567,12', "value -1.234.567,12 expected");
        $this->assertEquals(Zend_Locale_Format::toFloat(1234567.12345, $options), '1.234.567,12', "value 1.234.567,12 expected");
        $options = array('precision' => 7, 'locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::toFloat(1234567.12345, $options), '1.234.567,1234500', "value 1.234.567,12345 expected");
    }

    
    /**
     * test isFloat
     * expected boolean
     */
    public function testIsFloat()
    {
        $this->assertEquals(Zend_Locale_Format::isFloat('-1.234.567,12345', array('locale' => 'de_AT')), true, "true expected");
        $this->assertEquals(Zend_Locale_Format::isFloat('textwithoutnumber', array('locale' => 'de_AT')), false, "false expected");
    }


    /**
     * test getInteger
     * expected integer
     */
    public function testgetInteger()
    {
        $this->assertEquals(Zend_Locale_Format::getInteger(0), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger(-1234567), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger(1234567), 1234567, "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger(0.1234567), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger(-1234567.12345), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger(1234567.12345), 1234567, "value 1234567 expected");
        $options = array('locale' => 'de');
        $this->assertEquals(Zend_Locale_Format::getInteger('0', $options), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger('-1234567', $options), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger('1234567', $options), 1234567, "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger('0,1234567', $options), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger('-1.234.567,12345', $options), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger('1.234.567,12345', $options), 1234567, "value 1234567 expected");
        $options = array('locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::getInteger('0', $options), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger('-1234567', $options), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger('1.234.567', $options), 1234567, "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger('0,1234567', $options), 0, "value 0 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger('-1.234.567,12345', $options), -1234567, "value -1234567 expected");
        $this->assertEquals(Zend_Locale_Format::getInteger('1.234.567,12345', $options), 1234567, "value 1234567 expected");
    }


    /**
     * test toInteger
     * expected string
     */
    public function testtoInteger()
    {
        $this->assertEquals(Zend_Locale_Format::toInteger(0), '0', "string 0 expected");
        $this->assertEquals(Zend_Locale_Format::toInteger(0, array('locale' => 'de')), '0', "string 0 expected");
        $options = array('locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::toInteger(0, $options), '0', "string 0 expected");
        $this->assertEquals(Zend_Locale_Format::toInteger(-1234567, $options), '-1.234.567', "string -1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toInteger(1234567, $options), '1.234.567', "string 1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toInteger(0.1234567, $options), '0', "string 0 expected");
        $this->assertEquals(Zend_Locale_Format::toInteger(-1234567.12345, $options), '-1.234.567', "string -1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toInteger(1234567.12345, $options), '1.234.567', "value 1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toInteger(1234567.12345, array('locale' => 'ar_QA')), '1234567', "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toInteger(-1234567.12345, array('locale' => 'ar_QA')), '1234567-', "value 1234567- expected");
        $this->assertEquals(Zend_Locale_Format::toInteger(1234567.12345, array('locale' => 'dz_BT')), '12,34,567', "value 12,34,567 expected");
        $this->assertEquals(Zend_Locale_Format::toInteger(-1234567.12345, array('locale' => 'mk_MK')), '-(1.234.567)', "value -(1.234.567) expected");
    }


    /**
     * test isInteger
     * expected boolean
     */
    public function testIsInteger()
    {
        $this->assertEquals(Zend_Locale_Format::isInteger('-1.234.567,12345', array('locale' => 'de_AT')), TRUE, "TRUE expected");
        $this->assertEquals(Zend_Locale_Format::isInteger('textwithoutnumber', array('locale' => 'de_AT')), FALSE, "FALSE expected");
    }


    /**
     * test getDate
     * expected array
     */
    public function testgetDate()
    {
        try {
            $value = Zend_Locale_Format::getDate('no content');
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            // success
        }
        $this->assertEquals(is_array(Zend_Locale_Format::getDate('10.10.06')), true, "array expected");
        $this->assertEquals(count(Zend_Locale_Format::getDate('10.10.06', array('dateformat' => 'dd.MM.yy'))), 5, "array with 5 tags expected");

        $value = Zend_Locale_Format::getDate('10.11.06', array('dateformat' => 'dd.MM.yy'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 06, 'Year 06 expected');

        $value = Zend_Locale_Format::getDate('10.11.2006', array('dateformat' => 'dd.MM.yy'));
        $this->assertSame($value['day'], 10, 'Day 10 expected');
        $this->assertSame($value['month'], 11, 'Month 11 expected');
        $this->assertSame($value['year'], 2006, 'Year 2006 expected');

        try {
            $value = Zend_Locale_Format::getDate('2006.13.01', array('dateformat' => 'dd.MM.yy'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('2006.13.01', array('dateformat' => 'dd.MM.yy', 'fixdate' => true));
        $this->assertEquals($value['day'], 13, 'Day 13 expected');
        $this->assertEquals($value['month'], 1, 'Month 1 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');


        try {
            $value = Zend_Locale_Format::getDate('2006.01.13', array('dateformat' => 'dd.MM.yy'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('2006.01.13', array('dateformat' => 'dd.MM.yy', 'fixdate' => true));
        $this->assertEquals($value['day'], 13, 'Day 13 expected');
        $this->assertEquals($value['month'], 1, 'Month 1 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        $value = Zend_Locale_Format::getDate('101106', array('dateformat' => 'ddMMyy'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 6, 'Year 6 expected');

        $value = Zend_Locale_Format::getDate('10112006', array('dateformat' => 'ddMMyyyy'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        $value = Zend_Locale_Format::getDate('10 Nov 2006', array('dateformat' => 'dd.MMM.yy', 'locale' => 'de_AT'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        $value = Zend_Locale_Format::getDate('10 November 2006', array('dateformat' => 'dd.MMM.yy', 'locale' => 'de_AT'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        try {
            $value = Zend_Locale_Format::getDate('November 10 2006', array('dateformat' => 'dd.MMM.yy', 'locale' => 'de_AT'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('November 10 2006', array('dateformat' => 'dd.MMM.yy', 'locale' => 'de_AT', 'fixdate' => true));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');


        try {
            $value = Zend_Locale_Format::getDate('Nov 10 2006', array('dateformat' => 'dd.MMM.yy', 'locale' => 'de_AT'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('Nov 10 2006', array('dateformat' => 'dd.MMM.yy', 'locale' => 'de_AT', 'fixdate' => true));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');


        try {
            $value = Zend_Locale_Format::getDate('2006 10 Nov', array('dateformat' => 'dd.MMM.yy', 'locale' => 'de_AT'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('2006 10 Nov', array('dateformat' => 'dd.MMM.yy', 'locale' => 'de_AT', 'fixdate' => true));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

// @todo failed test, auto completion doesnt work for this case
//        $value = Zend_Locale_Format::getDate('2006 Nov 10','dd.MMM.yy', 'de_AT');
//        $this->assertEquals($value['day'], 10, 'Day 10 expected');
//        $this->assertEquals($value['month'], 11, 'Month 11 expected');
//        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        $value = Zend_Locale_Format::getDate('10.11.06', array('dateformat' => 'yy.dd.MM'));
        $this->assertEquals($value['day'], 11, 'Day 11 expected');
        $this->assertEquals($value['month'], 6, 'Month 6 expected');
        $this->assertEquals($value['year'], 10, 'Year 10 expected');

        $value = Zend_Locale_Format::getDate('10.11.06', array('dateformat' => 'dd.yy.MM'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 6, 'Month 6 expected');
        $this->assertEquals($value['year'], 11, 'Year 11 expected');

        $value = Zend_Locale_Format::getDate('10.11.06', array('locale' => 'de_AT'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 6, 'Year 6 expected');

        $value = Zend_Locale_Format::getDate('10.11.2006', array('locale' => 'de_AT'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');


        try {
            $value = Zend_Locale_Format::getDate('2006.13.01', array('locale' => 'de_AT'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('2006.13.01', array('locale' => 'de_AT', 'fixdate' => true));
        $this->assertEquals($value['day'], 13, 'Day 13 expected');
        $this->assertEquals($value['month'], 1, 'Month 1 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        try {
            $value = Zend_Locale_Format::getDate('2006.01.13', array('locale' => 'de_AT'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('2006.01.13', array('locale' => 'de_AT', 'fixdate' => true));
        $this->assertEquals($value['day'], 13, 'Day 13 expected');
        $this->assertEquals($value['month'], 1, 'Month 1 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        $value = Zend_Locale_Format::getDate('101106', array('locale' => 'de_AT'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 6, 'Year 6 expected');

        $value = Zend_Locale_Format::getDate('10112006', array('locale' => 'de_AT'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        $value = Zend_Locale_Format::getDate('10 Nov 2006', array('locale' => 'de_AT'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        $value = Zend_Locale_Format::getDate('10 November 2006', array('locale' => 'de_AT'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        try {
            $value = Zend_Locale_Format::getDate('November 10 2006', array('locale' => 'de_AT'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('November 10 2006', array('locale' => 'de_AT', 'fixdate' => true));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        try {
            $value = Zend_Locale_Format::getDate('April 10 2006', array('locale' => 'de_AT'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('April 10 2006', array('locale' => 'de_AT', 'fixdate' => true));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 4, 'Month 4 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        try {
            $value = Zend_Locale_Format::getDate('Nov 10 2006', array('locale' => 'de_AT'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('Nov 10 2006', array('locale' => 'de_AT', 'fixdate' => true));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');


        try {
            $value = Zend_Locale_Format::getDate('Nov 10 2006', array('locale' => 'de_AT'));
            $this->fail("no date expected");
        } catch (Zend_Locale_Exception $e) {
            $this->assertRegexp('/unable.to.parse/i', $e->getMessage());
            // success
        }
        $value = Zend_Locale_Format::getDate('2006 10 Nov', array('locale' => 'de_AT', 'fixdate' => true));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        $value = Zend_Locale_Format::getDate('01.April.2006', array('dateformat' => 'dd.MMMM.yy', 'locale' => 'de_AT'));
        $this->assertEquals($value['day'], 1, 'Day 1 expected');
        $this->assertEquals($value['month'], 4, 'Month 4 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        $value = Zend_Locale_Format::getDate('Montag, 01.April.2006', array('dateformat' => 'EEEE, dd.MMMM.yy', 'locale' => 'de_AT'));
        $this->assertEquals($value['day'], 1, 'Day 1 expected');
        $this->assertEquals($value['month'], 4, 'Month 4 expected');
        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');

        try {
            $value = Zend_Locale_Format::getDate('13.2006.11', array('dateformat' => 'dd.MM.yy'));
            $this->fail();
        } catch (Zend_Locale_Exception $e) {
            // success
        }

        Zend_Locale_Format::setOptions(array('type' => 'php'));
        $value = Zend_Locale_Format::getDate('10.11.06', array('dateformat' => 'd.m.Y'));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 6, 'Year 6 expected');

        $value = Zend_Locale_Format::getDate('10.11.06', array('dateformat' => 'd.m.Y', 'fixdate' => true));
        $this->assertEquals($value['day'], 10, 'Day 10 expected');
        $this->assertEquals($value['month'], 11, 'Month 11 expected');
        $this->assertEquals($value['year'], 6, 'Year 6 expected');
        
        $this->assertEquals(is_array(Zend_Locale_Format::getTime('13:14:55', array('dateformat' => 'HH:mm:ss'))), true, "array expected");
        Zend_Locale_Format::setOptions(array('type' => 'iso'));
        
// @todo failed test, auto completion doesnt work for this case
//        $value = Zend_Locale_Format::getDate('2006 Nov 10', false, 'de_AT');
//        $this->assertEquals($value['day'], 10, 'Day 10 expected');
//        $this->assertEquals($value['month'], 11, 'Month 11 expected');
//        $this->assertEquals($value['year'], 2006, 'Year 2006 expected');
    }


    /**
     * test getTime
     * expected array
     */
    public function testgetTime()
    {
        try {
            $value = Zend_Locale_Format::getTime('no content');
            $this->fail("no time expected");
        } catch (Zend_Locale_Exception $e) {
            // success
        }

        $this->assertEquals(is_array(Zend_Locale_Format::getTime('13:14:55', array('dateformat' => 'HH:mm:ss'))), true, "array expected");
        $options = array('dateformat' => 'h:mm:ss a', 'locale' => 'en');
        $this->assertEquals(is_array(Zend_Locale_Format::getTime('11:14:55 am', $options)), true, "array expected");
        $this->assertEquals(is_array(Zend_Locale_Format::getTime('12:14:55 am', $options)), true, "array expected");
        $this->assertEquals(is_array(Zend_Locale_Format::getTime('11:14:55 pm', $options)), true, "array expected");
        $this->assertEquals(is_array(Zend_Locale_Format::getTime('12:14:55 pm', $options)), true, "array expected");

        try {
            $value = Zend_Locale_Format::getTime('13:14:55', array('dateformat' => 'nocontent'));
            $this->fail("no time expected");
        } catch (Zend_Locale_Exception $e) {
            // success
        }

        try {
            $value = Zend_Locale_Format::getTime('13:14:55', array('dateformat' => 'ZZZZ'));
            $this->fail("no time expected");
        } catch (Zend_Locale_Exception $e) {
            // success
        }

        try {
            $value = Zend_Locale_Format::getTime('13:14:55', array('dateformat' => 'HH:mm:ss.x'));
            $this->fail("no time expected");
        } catch (Zend_Locale_Exception $e) {
            // success
        }

        $this->assertEquals(count(Zend_Locale_Format::getTime('13:14:55', array('dateformat' => 'HH:mm:ss'))), 5, "array with 5 tags expected");

        $value = Zend_Locale_Format::getTime('13:14:55', array('dateformat' => 'HH:mm:ss'));
        $this->assertEquals($value['hour'], 13, 'Hour 13 expected');
        $this->assertEquals($value['minute'], 14, 'Minute 14 expected');
        $this->assertEquals($value['second'], 55, 'Second 55 expected');

        $value = Zend_Locale_Format::getTime('131455', array('dateformat' => 'HH:mm:ss'));
        $this->assertEquals($value['hour'], 13, 'Hour 13 expected');
        $this->assertEquals($value['minute'], 14, 'Minute 14 expected');
        $this->assertEquals($value['second'], 55, 'Second 55 expected');
    }


    /**
     * test isDate
     * expected boolean
     */
    public function testIsDate()
    {
        $this->assertTrue(Zend_Locale_Format::isDate('13.Nov.2006', array('locale' => 'de_AT')), "true expected");
        $this->assertFalse(Zend_Locale_Format::isDate('13.XXX.2006', array('locale' => 'ar_EG')), "false expected");
        $this->assertFalse(Zend_Locale_Format::isDate('nodate'), "false expected");

        $this->assertFalse(Zend_Locale_Format::isDate('20.01.2006', array('dateformat' => 'M-d-y')), "false expected");
        $this->assertTrue(Zend_Locale_Format::isDate('20.01.2006', array('dateformat' => 'd-M-y')), "true expected");
    }


    /**
     * test isTime
     * expected boolean
     */
    public function testIsTime()
    {
        $this->assertTrue(Zend_Locale_Format::isTime('13:10:55', array('locale' => 'de_AT')), "true expected");
        $this->assertTrue(Zend_Locale_Format::isTime('11:10:55 am', array('locale' => 'ar_EG')), "true expected");
        $this->assertFalse(Zend_Locale_Format::isTime('notime'), "false expected");
    }


    /**
     * test toNumberSystem
     * expected string
     */
    public function testToNumberSystem()
    {
        try {
            $value = Zend_Locale_Format::convertNumerals('١١٠', 'xxxx');
            $this->fail("no conversion expected");
        } catch (Zend_Locale_Exception $e) {
            // success
        }

        try {
            $value = Zend_Locale_Format::convertNumerals('١١٠', 'Arab', 'xxxx');
            $this->fail("no conversion expected");
        } catch (Zend_Locale_Exception $e) {
            // success
        }

        $this->assertEquals(Zend_Locale_Format::convertNumerals('١١٠', 'Arab'), '110', "110 expected");
        $this->assertEquals(Zend_Locale_Format::convertNumerals('١١٠', 'Arab', 'Deva'), '११०', "११० expected");
        $this->assertEquals(Zend_Locale_Format::convertNumerals('110', 'Latin', 'Arab'), '١١٠', "١١٠ expected");
    }
    
    /**
     * test toNumberFormat
     * expected string
     */
    public function testToNumberFormat()
    {
        $locale = new Zend_Locale('de_AT');
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(0), '0', "string 0 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(0, array('locale' => 'de')), '0', "string 0 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(0, array('locale' => $locale)), '0', "string 0 expected");
        $options = array('locale' => 'de_AT');
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(-1234567, $options), '-1.234.567', "string -1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567, $options), '1.234.567', "string 1.234.567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(0.1234567, $options), '0,1234567', "string 0,1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(-1234567.12345, $options), '-1.234.567,12345', "string -1.234.567,12345 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567.12345, $options), '1.234.567,12345', "value 1.234.567,12345 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567.12345, array('numberformat' => '##0', 'locale' => 'de_AT')), '1234567', "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567.12345, array('numberformat' => '#,#0', 'locale' => 'de_AT')), '1.23.45.67', "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567.12345, array('numberformat' => '##0.00', 'locale' => 'de_AT')), '1234567,12', "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567.12345, array('numberformat' => '##0.###', 'locale' => 'de_AT')), '1234567,12345', "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567.12345, array('numberformat' => '#,#0', 'locale' => 'de_AT')), '1.23.45.67', "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567.12345, array('numberformat' => '#,##,##0', 'locale' => 'de_AT')), '12.34.567', "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567.12345, array('numberformat' => '#,##0.00', 'locale' => 'de_AT')), '1.234.567,12', "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567.12345, array('numberformat' => '#,#0.00', 'locale' => 'de_AT')), '1.23.45.67,12', "value 1234567 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(-1234567.12345, array('numberformat' => '##0;##0-', 'locale' => 'de_AT')), '1234567-', "string -1.234.567,12345 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567.12345, array('numberformat' => '##0;##0-', 'locale' => 'de_AT')), '1234567', "string -1.234.567,12345 expected");
        $this->assertEquals(Zend_Locale_Format::toNumberFormat(1234567, array('numberformat' => '#,##0.00', 'locale' => 'de_AT')), '1.234.567,00', "value 1234567,00 expected");
    }


    /**
     * test setOption
     * expected boolean
     */
    public function testSetOption()
    {
        $this->assertEquals(count(Zend_Locale_Format::setOptions(array('type' => 'php'))), 6);
        $this->assertTrue(is_array(Zend_Locale_Format::setOptions()));
        try {
            $this->assertTrue(Zend_Locale_Format::setOptions(array('type' => 'xxx')));
        } catch (Zend_Locale_Exception $e) {
            // success
        }
        try {
            $this->assertTrue(Zend_Locale_Format::setOptions(array('myformat' => 'xxx')));
        } catch (Zend_Locale_Exception $e) {
            // success
        }
    }


    /**
     * test convertPhpToIso
     * expected boolean
     */
    public function testConvertPhpToIso()
    {
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('d'), 'dd');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('D'), 'EEE');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('j'), 'd');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('l'), 'EEEE');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('N'), 'e');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('S'), 'SS');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('w'), 'eee');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('z'), 'D');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('W'), 'w');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('F'), 'MMMM');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('m'), 'MM');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('M'), 'MMM');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('n'), 'M');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('t'), 'ddd');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('L'), 'l');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('o'), 'YYYY');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('Y'), 'yyyy');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('y'), 'yy');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('a'), 'a');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('A'), 'a');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('B'), 'B');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('g'), 'h');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('G'), 'H');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('h'), 'hh');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('H'), 'HH');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('i'), 'mm');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('s'), 'ss');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('e'), 'zzzz');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('I'), 'I');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('O'), 'Z');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('P'), 'ZZZZ');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('T'), 'z');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('Z'), 'X');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('c'), 'yyyy-MM-ddTHH:mm:ssZZZZ');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('r'), 'r');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('U'), 'U');
        $this->assertSame(Zend_Locale_Format::convertPhpToIsoFormat('His'), 'HHmmss');
    }
}
