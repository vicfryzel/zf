<?php
// Call Zend_View_Helper_HeadScriptTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_View_Helper_HeadScriptTest::main");
}

require_once dirname(__FILE__) . '/../../../TestHelper.php';

/** Zend_View_Helper_HeadScript */
require_once 'Zend/View/Helper/HeadScript.php';

/** Zend_View_Helper_Placeholder_Registry */
require_once 'Zend/View/Helper/Placeholder/Registry.php';

/** Zend_Registry */
require_once 'Zend/Registry.php';

/**
 * Test class for Zend_View_Helper_HeadScript.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 */
class Zend_View_Helper_HeadScriptTest extends PHPUnit_Framework_TestCase 
{
    /**
     * @var Zend_View_Helper_HeadScript
     */
    public $helper;

    /**
     * @var string
     */
    public $basePath;

    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite("Zend_View_Helper_HeadScriptTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $regKey = Zend_View_Helper_Placeholder_Registry::REGISTRY_KEY;
        if (Zend_Registry::isRegistered($regKey)) {
            $registry = Zend_Registry::getInstance();
            unset($registry[$regKey]);
        }
        $this->basePath = dirname(__FILE__) . '/_files/modules';
        $this->helper = new Zend_View_Helper_HeadScript();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->helper);
    }

    public function testNamespaceRegisteredInPlaceholderRegistryAfterInstantiation()
    {
        $registry = Zend_View_Helper_Placeholder_Registry::getRegistry();
        if ($registry->containerExists('Zend_View_Helper_HeadScript')) {
            $registry->deleteContainer('Zend_View_Helper_HeadScript');
        }
        $this->assertFalse($registry->containerExists('Zend_View_Helper_HeadScript'));
        $helper = new Zend_View_Helper_HeadScript();
        $this->assertTrue($registry->containerExists('Zend_View_Helper_HeadScript'));
    }

    public function testHeadScriptReturnsObjectInstance()
    {
        $placeholder = $this->helper->headScript();
        $this->assertTrue($placeholder instanceof Zend_View_Helper_HeadScript);
    }

    public function testSetPrependAppendAndOffsetSetThrowExceptionsOnInvalidItems()
    {
        try {
            $this->helper->append('foo');
            $this->fail('Append should throw exception with invalid item');
        } catch (Zend_View_Exception $e) { }
        try {
            $this->helper->offsetSet(1, 'foo');
            $this->fail('OffsetSet should throw exception with invalid item');
        } catch (Zend_View_Exception $e) { }
        try {
            $this->helper->prepend('foo');
            $this->fail('Prepend should throw exception with invalid item');
        } catch (Zend_View_Exception $e) { }
        try {
            $this->helper->set('foo');
            $this->fail('Set should throw exception with invalid item');
        } catch (Zend_View_Exception $e) { }
    }

    protected function _inflectAction($type)
    {
        return ucfirst(strtolower($type));
    }

    protected function _testOverloadAppend($type)
    {
        $action = 'append' . $this->_inflectAction($type);
        $string = 'foo';
        for ($i = 0; $i < 3; ++$i) {
            $string .= ' foo';
            $this->helper->$action($string);
            $values = $this->helper->getArrayCopy();
            $this->assertEquals($i + 1, count($values));
            if ('file' == $type) {
                $this->assertEquals($string, $values[$i]->attributes['src']);
            } elseif ('script' == $type) {
                $this->assertEquals($string, $values[$i]->source);
            }
            $this->assertEquals('text/javascript', $values[$i]->type);
        }
    }

    protected function _testOverloadPrepend($type)
    {
        $action = 'prepend' . $this->_inflectAction($type);
        $string = 'foo';
        for ($i = 0; $i < 3; ++$i) {
            $string .= ' foo';
            $this->helper->$action($string);
            $values = $this->helper->getArrayCopy();
            $this->assertEquals($i + 1, count($values));
            $first = array_shift($values);
            if ('file' == $type) {
                $this->assertEquals($string, $first->attributes['src']);
            } elseif ('script' == $type) {
                $this->assertEquals($string, $first->source);
            }
            $this->assertEquals('text/javascript', $first->type);
        }
    }

    protected function _testOverloadSet($type)
    {
        $action = 'set' . $this->_inflectAction($type);
        $string = 'foo';
        for ($i = 0; $i < 3; ++$i) {
            $this->helper->appendScript($string);
            $string .= ' foo';
        }
        $this->helper->$action($string);
        $values = $this->helper->getArrayCopy();
        $this->assertEquals(1, count($values));
        if ('file' == $type) {
            $this->assertEquals($string, $values[0]->attributes['src']);
        } elseif ('script' == $type) {
            $this->assertEquals($string, $values[0]->source);
        }
        $this->assertEquals('text/javascript', $values[0]->type);
    }

    protected function _testOverloadOffsetSet($type)
    {
        $action = 'offsetSet' . $this->_inflectAction($type);
        $string = 'foo';
        $this->helper->$action(5, $string);
        $values = $this->helper->getArrayCopy();
        $this->assertEquals(1, count($values));
        if ('file' == $type) {
            $this->assertEquals($string, $values[5]->attributes['src']);
        } elseif ('script' == $type) {
            $this->assertEquals($string, $values[5]->source);
        }
        $this->assertEquals('text/javascript', $values[5]->type);
    }

    public function testOverloadAppendFileAppendsScriptsToStack()
    {
        $this->_testOverloadAppend('file');
    }

    public function testOverloadAppendScriptAppendsScriptsToStack()
    {
        $this->_testOverloadAppend('script');
    }

    public function testOverloadPrependFileAppendsScriptsToStack()
    {
        $this->_testOverloadPrepend('file');
    }

    public function testOverloadPrependScriptAppendsScriptsToStack()
    {
        $this->_testOverloadPrepend('script');
    }

    public function testOverloadSetFileOverwritesStack()
    {
        $this->_testOverloadSet('file');
    }

    public function testOverloadSetScriptOverwritesStack()
    {
        $this->_testOverloadSet('script');
    }

    public function testOverloadOffsetSetFileWritesToSpecifiedIndex()
    {
        $this->_testOverloadOffsetSet('file');
    }

    public function testOverloadOffsetSetScriptWritesToSpecifiedIndex()
    {
        $this->_testOverloadOffsetSet('script');
    }

    public function testOverloadingThrowsExceptionWithInvalidMethod()
    {
        try {
            $this->helper->fooBar('foo');
            $this->fail('Invalid method should raise exception');
        } catch (Zend_View_Exception $e) {
        }
    }

    public function testOverloadingWithTooFewArgumentsRaisesException()
    {
        try {
            $this->helper->setScript();
            $this->fail('Too few arguments should raise exception');
        } catch (Zend_View_Exception $e) {
        }

        try {
            $this->helper->offsetSetScript(5);
            $this->fail('Too few arguments should raise exception');
        } catch (Zend_View_Exception $e) {
        }
    }

    public function testHeadScriptAppropriatelySetsScriptItems()
    {
        $this->helper->headScript('FILE', 'foo', 'set')
                     ->headScript('SCRIPT', 'bar', 'prepend')
                     ->headScript('SCRIPT', 'baz', 'append');
        $items = $this->helper->getArrayCopy();
        for ($i = 0; $i < 3; ++$i) {
            $item = $items[$i];
            switch ($i) {
                case 0:
                    $this->assertObjectHasAttribute('source', $item);
                    $this->assertEquals('bar', $item->source);
                    break;
                case 1:
                    $this->assertObjectHasAttribute('attributes', $item);
                    $this->assertTrue(isset($item->attributes['src']));
                    $this->assertEquals('foo', $item->attributes['src']);
                    break;
                case 2:
                    $this->assertObjectHasAttribute('source', $item);
                    $this->assertEquals('baz', $item->source);
                    break;
            }
        }
    }

    public function testToStringRendersValidHtml()
    {
        $this->helper->headScript('FILE', 'foo', 'set')
                     ->headScript('SCRIPT', 'bar', 'prepend')
                     ->headScript('SCRIPT', 'baz', 'append');
        $string = $this->helper->toString();

        $scripts = substr_count($string, '<script ');
        $this->assertEquals(3, $scripts);
        $scripts = substr_count($string, '</script>');
        $this->assertEquals(3, $scripts);
        $scripts = substr_count($string, 'src="');
        $this->assertEquals(1, $scripts);
        $scripts = substr_count($string, '><');
        $this->assertEquals(1, $scripts);

        $this->assertContains('src="foo"', $string);
        $this->assertContains('bar', $string);
        $this->assertContains('baz', $string);

        $doc = new DOMDocument;
        $dom = $doc->loadHtml($string);
        $this->assertTrue($dom !== false);
    }

    public function testCapturingCapturesToObject()
    {
        $this->helper->captureStart();
        echo 'foobar';
        $this->helper->captureEnd();
        $values = $this->helper->getArrayCopy();
        $this->assertEquals(1, count($values), var_export($values, 1));
        $item = array_shift($values);
        $this->assertContains('foobar', $item->source);
    }

    public function testIndentationIsHonored()
    {
        $this->helper->setIndent(4);
        $this->helper->appendScript('
var foo = "bar";
document.write(foo.strlen());');
        $this->helper->appendScript('
var bar = "baz";
document.write(bar.strlen());');
        $string = $this->helper->toString();

        $scripts = substr_count($string, '    <script');
        $this->assertEquals(2, $scripts);
        $this->assertContains('    //', $string);
        $this->assertContains('    var', $string);
        $this->assertContains('    document', $string);
    }
}

// Call Zend_View_Helper_HeadScriptTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_View_Helper_HeadScriptTest::main") {
    Zend_View_Helper_HeadScriptTest::main();
}
