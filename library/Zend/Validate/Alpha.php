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
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';


/**
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Validate_Alpha extends Zend_Validate_Abstract
{
    /**
     * Validation failure message key for when the value contains non-alphabetic characters
     */
    const NOT_ALPHA = 'notAlpha';

    /**
     * Alphabetic filter used for validation
     *
     * @var Zend_Filter_Alpha
     */
    protected static $_filter = null;

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_ALPHA => "'%value%' has not only alphabetic characters"
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value contains only alphabetic characters
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $valueString = (string) $value;

        $this->_setValue($valueString);

        if (null === self::$_filter) {
            /**
             * @see Zend_Filter_Alpha
             */
            require_once 'Zend/Filter/Alpha.php';
            self::$_filter = new Zend_Filter_Alpha();
        }

        if ($valueString !== self::$_filter->filter($valueString)) {
            $this->_error();
            return false;
        }

        return true;
    }

}
