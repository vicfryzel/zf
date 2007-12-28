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
 * @package    Zend_Search_Lucene
 * @subpackage Search
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/** Zend_Search_Lucene_Index_Term */
require_once 'Zend/Search/Lucene/Index/Term.php';

/** Zend_Search_Lucene_Search_Query_Term */
require_once 'Zend/Search/Lucene/Search/Query/Term.php';

/** Zend_Search_Lucene_Search_Query_MultiTerm */
require_once 'Zend/Search/Lucene/Search/Query/MultiTerm.php';

/** Zend_Search_Lucene_Search_Query_Boolean */
require_once 'Zend/Search/Lucene/Search/Query/Boolean.php';

/** Zend_Search_Lucene_Search_Query_Phrase */
require_once 'Zend/Search/Lucene/Search/Query/Phrase.php';

/** Zend_Search_Lucene_Search_Query_Empty */
require_once 'Zend/Search/Lucene/Search/Query/Empty.php';

/** Zend_Search_Lucene_Search_Query_Insignificant */
require_once 'Zend/Search/Lucene/Search/Query/Insignificant.php';


/** Zend_Search_Lucene_Search_QueryLexer */
require_once 'Zend/Search/Lucene/Search/QueryLexer.php';

/** Zend_Search_Lucene_Search_QueryParserContext */
require_once 'Zend/Search/Lucene/Search/QueryParserContext.php';


/** Zend_Search_Lucene_FSM */
require_once 'Zend/Search/Lucene/FSM.php';

/** Zend_Search_Lucene_Exception */
require_once 'Zend/Search/Lucene/Exception.php';

/** Zend_Search_Lucene_Search_QueryParserException */
require_once 'Zend/Search/Lucene/Search/QueryParserException.php';


/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Search_Lucene_Search_QueryParser extends Zend_Search_Lucene_FSM
{
    /**
     * Parser instance
     *
     * @var Zend_Search_Lucene_Search_QueryParser
     */
    private static $_instance = null;


    /**
     * Query lexer
     *
     * @var Zend_Search_Lucene_Search_QueryLexer
     */
    private $_lexer;

    /**
     * Tokens list
     * Array of Zend_Search_Lucene_Search_QueryToken objects
     *
     * @var array
     */
    private $_tokens;

    /**
     * Current token
     *
     * @var integer|string
     */
    private $_currentToken;

    /**
     * Last token
     *
     * It can be processed within FSM states, but this addirional state simplifies FSM
     *
     * @var Zend_Search_Lucene_Search_QueryToken
     */
    private $_lastToken = null;

    /**
     * Range query first term
     *
     * @var string
     */
    private $_rqFirstTerm = null;

    /**
     * Current query parser context
     *
     * @var Zend_Search_Lucene_Search_QueryParserContext
     */
    private $_context;

    /**
     * Context stack
     *
     * @var array
     */
    private $_contextStack;

    /**
     * Query string encoding
     *
     * @var string
     */
    private $_encoding;

    /**
     * Query string default encoding
     *
     * @var string
     */
    private $_defaultEncoding = '';


    /**
     * Boolean operators constants
     */
    const B_OR  = 0;
    const B_AND = 1;

    /**
     * Default boolean queries operator
     *
     * @var integer
     */
    private $_defaultOperator = self::B_OR;


    /** Query parser State Machine states */
    const ST_COMMON_QUERY_ELEMENT       = 0;   // Terms, phrases, operators
    const ST_CLOSEDINT_RQ_START         = 1;   // Range query start (closed interval) - '['
    const ST_CLOSEDINT_RQ_FIRST_TERM    = 2;   // First term in '[term1 to term2]' construction
    const ST_CLOSEDINT_RQ_TO_TERM       = 3;   // 'TO' lexeme in '[term1 to term2]' construction
    const ST_CLOSEDINT_RQ_LAST_TERM     = 4;   // Second term in '[term1 to term2]' construction
    const ST_CLOSEDINT_RQ_END           = 5;   // Range query end (closed interval) - ']'
    const ST_OPENEDINT_RQ_START         = 6;   // Range query start (opened interval) - '{'
    const ST_OPENEDINT_RQ_FIRST_TERM    = 7;   // First term in '{term1 to term2}' construction
    const ST_OPENEDINT_RQ_TO_TERM       = 8;   // 'TO' lexeme in '{term1 to term2}' construction
    const ST_OPENEDINT_RQ_LAST_TERM     = 9;   // Second term in '{term1 to term2}' construction
    const ST_OPENEDINT_RQ_END           = 10;  // Range query end (opened interval) - '}'

    /**
     * Parser constructor
     */
    public function __construct()
    {
        parent::__construct(array(self::ST_COMMON_QUERY_ELEMENT,
                                  self::ST_CLOSEDINT_RQ_START,
                                  self::ST_CLOSEDINT_RQ_FIRST_TERM,
                                  self::ST_CLOSEDINT_RQ_TO_TERM,
                                  self::ST_CLOSEDINT_RQ_LAST_TERM,
                                  self::ST_CLOSEDINT_RQ_END,
                                  self::ST_OPENEDINT_RQ_START,
                                  self::ST_OPENEDINT_RQ_FIRST_TERM,
                                  self::ST_OPENEDINT_RQ_TO_TERM,
                                  self::ST_OPENEDINT_RQ_LAST_TERM,
                                  self::ST_OPENEDINT_RQ_END
                                 ),
                            Zend_Search_Lucene_Search_QueryToken::getTypes());

        $this->addRules(
             array(array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_WORD,             self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_PHRASE,           self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_FIELD,            self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_REQUIRED,         self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_PROHIBITED,       self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_FUZZY_PROX_MARK,  self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_BOOSTING_MARK,    self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_RANGE_INCL_START, self::ST_CLOSEDINT_RQ_START),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_RANGE_EXCL_START, self::ST_OPENEDINT_RQ_START),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_SUBQUERY_START,   self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_SUBQUERY_END,     self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_AND_LEXEME,       self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_OR_LEXEME,        self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_NOT_LEXEME,       self::ST_COMMON_QUERY_ELEMENT),
                   array(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_NUMBER,           self::ST_COMMON_QUERY_ELEMENT)
                  ));
        $this->addRules(
             array(array(self::ST_CLOSEDINT_RQ_START,      Zend_Search_Lucene_Search_QueryToken::TT_WORD,           self::ST_CLOSEDINT_RQ_FIRST_TERM),
                   array(self::ST_CLOSEDINT_RQ_FIRST_TERM, Zend_Search_Lucene_Search_QueryToken::TT_TO_LEXEME,      self::ST_CLOSEDINT_RQ_TO_TERM),
                   array(self::ST_CLOSEDINT_RQ_TO_TERM,    Zend_Search_Lucene_Search_QueryToken::TT_WORD,           self::ST_CLOSEDINT_RQ_LAST_TERM),
                   array(self::ST_CLOSEDINT_RQ_LAST_TERM,  Zend_Search_Lucene_Search_QueryToken::TT_RANGE_INCL_END, self::ST_COMMON_QUERY_ELEMENT)
                  ));
        $this->addRules(
             array(array(self::ST_OPENEDINT_RQ_START,      Zend_Search_Lucene_Search_QueryToken::TT_WORD,           self::ST_OPENEDINT_RQ_FIRST_TERM),
                   array(self::ST_OPENEDINT_RQ_FIRST_TERM, Zend_Search_Lucene_Search_QueryToken::TT_TO_LEXEME,      self::ST_OPENEDINT_RQ_TO_TERM),
                   array(self::ST_OPENEDINT_RQ_TO_TERM,    Zend_Search_Lucene_Search_QueryToken::TT_WORD,           self::ST_OPENEDINT_RQ_LAST_TERM),
                   array(self::ST_OPENEDINT_RQ_LAST_TERM,  Zend_Search_Lucene_Search_QueryToken::TT_RANGE_EXCL_END, self::ST_COMMON_QUERY_ELEMENT)
                  ));



        $addTermEntryAction             = new Zend_Search_Lucene_FSMAction($this, 'addTermEntry');
        $addPhraseEntryAction           = new Zend_Search_Lucene_FSMAction($this, 'addPhraseEntry');
        $setFieldAction                 = new Zend_Search_Lucene_FSMAction($this, 'setField');
        $setSignAction                  = new Zend_Search_Lucene_FSMAction($this, 'setSign');
        $setFuzzyProxAction             = new Zend_Search_Lucene_FSMAction($this, 'processFuzzyProximityModifier');
        $processModifierParameterAction = new Zend_Search_Lucene_FSMAction($this, 'processModifierParameter');
        $subqueryStartAction            = new Zend_Search_Lucene_FSMAction($this, 'subqueryStart');
        $subqueryEndAction              = new Zend_Search_Lucene_FSMAction($this, 'subqueryEnd');
        $logicalOperatorAction          = new Zend_Search_Lucene_FSMAction($this, 'logicalOperator');
        $openedRQFirstTermAction        = new Zend_Search_Lucene_FSMAction($this, 'openedRQFirstTerm');
        $openedRQLastTermAction         = new Zend_Search_Lucene_FSMAction($this, 'openedRQLastTerm');
        $closedRQFirstTermAction        = new Zend_Search_Lucene_FSMAction($this, 'closedRQFirstTerm');
        $closedRQLastTermAction         = new Zend_Search_Lucene_FSMAction($this, 'closedRQLastTerm');


        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_WORD,            $addTermEntryAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_PHRASE,          $addPhraseEntryAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_FIELD,           $setFieldAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_REQUIRED,        $setSignAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_PROHIBITED,      $setSignAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_FUZZY_PROX_MARK, $setFuzzyProxAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_NUMBER,          $processModifierParameterAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_SUBQUERY_START,  $subqueryStartAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_SUBQUERY_END,    $subqueryEndAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_AND_LEXEME,      $logicalOperatorAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_OR_LEXEME,       $logicalOperatorAction);
        $this->addInputAction(self::ST_COMMON_QUERY_ELEMENT, Zend_Search_Lucene_Search_QueryToken::TT_NOT_LEXEME,      $logicalOperatorAction);

        $this->addEntryAction(self::ST_OPENEDINT_RQ_FIRST_TERM, $openedRQFirstTermAction);
        $this->addEntryAction(self::ST_OPENEDINT_RQ_LAST_TERM,  $openedRQLastTermAction);
        $this->addEntryAction(self::ST_CLOSEDINT_RQ_FIRST_TERM, $closedRQFirstTermAction);
        $this->addEntryAction(self::ST_CLOSEDINT_RQ_LAST_TERM,  $closedRQLastTermAction);



        $this->_lexer = new Zend_Search_Lucene_Search_QueryLexer();
    }


    /**
     * Set query string default encoding
     *
     * @param string $encoding
     */
    public static function setDefaultEncoding($encoding)
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        self::$_instance->_defaultEncoding = $encoding;
    }

    /**
     * Get query string default encoding
     *
     * @return string
     */
    public static function getDefaultEncoding()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance->_defaultEncoding;
    }

    /**
     * Set default boolean operator
     *
     * @param integer $operator
     */
    public static function setDefaultOperator($operator)
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        self::$_instance->_defaultOperator = $operator;
    }

    /**
     * Get default boolean operator
     *
     * @return integer
     */
    public static function getDefaultOperator()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance->_defaultOperator;
    }

    /**
     * Parses a query string
     *
     * @param string $strQuery
     * @param string $encoding
     * @return Zend_Search_Lucene_Search_Query
     * @throws Zend_Search_Lucene_Search_QueryParserException
     */
    public static function parse($strQuery, $encoding = null)
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        self::$_instance->_encoding     = ($encoding !== null) ? $encoding : self::$_instance->_defaultEncoding;
        self::$_instance->_lastToken    = null;
        self::$_instance->_context      = new Zend_Search_Lucene_Search_QueryParserContext(self::$_instance->_encoding);
        self::$_instance->_contextStack = array();
        self::$_instance->_tokens       = self::$_instance->_lexer->tokenize($strQuery, self::$_instance->_encoding);

        // Empty query
        if (count(self::$_instance->_tokens) == 0) {
            return new Zend_Search_Lucene_Search_Query_Insignificant();
        }


        foreach (self::$_instance->_tokens as $token) {
            try {
                self::$_instance->_currentToken = $token;
                self::$_instance->process($token->type);

                self::$_instance->_lastToken = $token;
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'There is no any rule for') !== false) {
                    throw new Zend_Search_Lucene_Search_QueryParserException( 'Syntax error at char position ' . $token->position . '.' );
                }

                throw $e;
            }
        }

        if (count(self::$_instance->_contextStack) != 0) {
            throw new Zend_Search_Lucene_Search_QueryParserException('Syntax Error: mismatched parentheses, every opening must have closing.' );
        }

        return self::$_instance->_context->getQuery();
    }


    /*********************************************************************
     * Actions implementation
     *
     * Actions affect on recognized lexemes list
     *********************************************************************/

    /**
     * Add term to a query
     */
    public function addTermEntry()
    {
        $entry = new Zend_Search_Lucene_Search_QueryEntry_Term($this->_currentToken->text, $this->_context->getField());
        $this->_context->addEntry($entry);
    }

    /**
     * Add phrase to a query
     */
    public function addPhraseEntry()
    {
        $entry = new Zend_Search_Lucene_Search_QueryEntry_Phrase($this->_currentToken->text, $this->_context->getField());
        $this->_context->addEntry($entry);
    }

    /**
     * Set entry field
     */
    public function setField()
    {
        $this->_context->setNextEntryField($this->_currentToken->text);
    }

    /**
     * Set entry sign
     */
    public function setSign()
    {
        $this->_context->setNextEntrySign($this->_currentToken->type);
    }


    /**
     * Process fuzzy search/proximity modifier - '~'
     */
    public function processFuzzyProximityModifier()
    {
        $this->_context->processFuzzyProximityModifier();
    }

    /**
     * Process modifier parameter
     *
     * @throws Zend_Search_Lucene_Exception
     */
    public function processModifierParameter()
    {
        if ($this->_lastToken === null) {
            throw new Zend_Search_Lucene_Search_QueryParserException('Lexeme modifier parameter must follow lexeme modifier. Char position 0.' );
        }

        switch ($this->_lastToken->type) {
            case Zend_Search_Lucene_Search_QueryToken::TT_FUZZY_PROX_MARK:
                $this->_context->processFuzzyProximityModifier($this->_currentToken->text);
                break;

            case Zend_Search_Lucene_Search_QueryToken::TT_BOOSTING_MARK:
                $this->_context->boost($this->_currentToken->text);
                break;

            default:
                // It's not a user input exception
                throw new Zend_Search_Lucene_Exception('Lexeme modifier parameter must follow lexeme modifier. Char position .' );
        }
    }


    /**
     * Start subquery
     */
    public function subqueryStart()
    {
        $this->_contextStack[] = $this->_context;
        $this->_context        = new Zend_Search_Lucene_Search_QueryParserContext($this->_encoding, $this->_context->getField());
    }

    /**
     * End subquery
     */
    public function subqueryEnd()
    {
        if (count($this->_contextStack) == 0) {
            throw new Zend_Search_Lucene_Search_QueryParserException('Syntax Error: mismatched parentheses, every opening must have closing. Char position ' . $this->_currentToken->position . '.' );
        }

        $query          = $this->_context->getQuery();
        $this->_context = array_pop($this->_contextStack);

        $this->_context->addEntry(new Zend_Search_Lucene_Search_QueryEntry_Subquery($query));
    }

    /**
     * Process logical operator
     */
    public function logicalOperator()
    {
        $this->_context->addLogicalOperator($this->_currentToken->type);
    }

    /**
     * Process first range query term (opened interval)
     */
    public function openedRQFirstTerm()
    {
        $this->_rqFirstTerm = $this->_currentToken->text;
    }

    /**
     * Process last range query term (opened interval)
     *
     * @throws Zend_Search_Lucene_Search_QueryParserException
     */
    public function openedRQLastTerm()
    {
        throw new Zend_Search_Lucene_Search_QueryParserException('Range queries are not supported yet.');

        // $firstTerm = new Zend_Search_Lucene_Index_Term($this->_rqFirstTerm,        $this->_context->getField());
        // $lastTerm  = new Zend_Search_Lucene_Index_Term($this->_currentToken->text, $this->_context->getField());

        // $query = new Zend_Search_Lucene_Search_Query_Range($firstTerm, $lastTerm, false);
        // $this->_context->addentry($query);
    }

    /**
     * Process first range query term (closed interval)
     */
    public function closedRQFirstTerm()
    {
        $this->_rqFirstTerm = $this->_currentToken->text;
    }

    /**
     * Process last range query term (closed interval)
     *
     * @throws Zend_Search_Lucene_Search_QueryParserException
     */
    public function closedRQLastTerm()
    {
        throw new Zend_Search_Lucene_Search_QueryParserException('Range queries are not supported yet.');

        // $firstTerm = new Zend_Search_Lucene_Index_Term($this->_rqFirstTerm,        $this->_context->getField());
        // $lastTerm  = new Zend_Search_Lucene_Index_Term($this->_currentToken->text, $this->_context->getField());

        // $query = new Zend_Search_Lucene_Search_Query_Range($firstTerm, $lastTerm, true);
        // $this->_context->addentry($query);
    }
}

