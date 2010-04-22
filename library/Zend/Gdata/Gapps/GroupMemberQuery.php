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
 * @package    Zend_Gdata
 * @subpackage Gapps
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Gdata_Gapps_Query
 */
require_once('Zend/Gdata/Gapps/Query.php');

/**
 * Assists in constructing queries for Google Apps Group Member entries.
 * Instances of this class can be provided in many places where a URL is
 * required.
 *
 * For information on submitting queries to a server, see the Google Apps
 * service class, Zend_Gdata_Gapps.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Gapps
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Gapps_GroupMemberQuery extends Zend_Gdata_Gapps_Query
{

    /**
     * If not null, indicates the id of the group whose members should be
     * returned by this query.
     *
     * @var string
     */
    protected $_groupId = null;

    /**
     * If not null, indicates the id of the member entry which should be
     * returned by this query.
     *
     * @var string
     */
    protected $_memberId = null;

    /**
     * Create a new instance.
     *
     * @param string $domain The Google Apps-hosted domain to use
     *          when constructing query URIs.
     * @param string $groupId ID for the group.
     * @param string $memberId (optional) ID of the member to query for.
     * @param string $startId (optional) The member ID to start the query at.
     */
    public function __construct($domain, $groupId, $memberId = null,
            $startId = null)
    {
        parent::__construct($domain);
        $this->setGroupId($groupId);
        $this->setMemberId($memberId);
        $this->setStartMemberId($startId);
    }

    /**
     * Set the group ID to query for. When set, only members belonging to a 
     * group with a group ID of the given value will be retrieved, updated,
     * or deleted.
     *
     * @see getGroupId
     * @param string $value The group ID of the group to retrieve, update,
     *          or delete.
     */
     public function setGroupId($value)
     {
         $this->_groupId = $value;
     }

    /**
     * Get the group ID currently set to query for.
     *
     * @see setGroupId
     * @return string The group ID to query for is not set.
     */
    public function getGroupId()
    {
        return $this->_groupId;
    }

    /**
     * Set the member to query for. When set, only groups of which the given
     * username is a member will be returned in queries.  Set this parameter
     * to null to disable it.  This value should be an email address.
     *
     * @see getMember
     * @param string $value The email of the member entry to interact with.
     */
    public function setMemberId($value)
    {
        $this->_memberId = $value;
    }

    /**
     * Get the member to query for. If no member is set, null will be
     * returned.  This value will be an email address.
     *
     * @see setMember
     * @return string The email of the member entry to interact with,
     *         or null if this parameter is not set.
     */
    public function getMemberId()
    {
        return $this->_memberId;
    }

    /**
     * Set the first member ID which should be displayed when retrieving
     * a list of members.
     *
     * @param string $value The first member ID to be returned, or null to
     *         disable.
     */
    public function setStartMemberId($value)
    {
        if ($value !== null) {
            $this->_params['start'] = $value;
        } else {
            unset($this->_params['start']);
        }
    }

    /**
     * Get the first member ID which should be displayed when retrieving
     * a list of groups.
     *
     * @return string The first member ID be returned, or null if this
     *         parameter is disabled.
     */
    public function getStartMemberId()
    {
        if (array_key_exists('start', $this->_params)) {
            return $this->_params['start'];
        } else {
            return null;
        }
    }

    /**
     * Returns the base URL used to access the Groups Google Apps service.
     * This must be defined here to override the default implementation,
     * as Groups member feed URLs do not follow the URL convention of other
     * feeds.  The domain is added to the URL later.
     *
     * @param string $domain (unused) Accepted here to conform to the base
     *          class definition, but is not used in this implementation.
     */
     public function getBaseUrl($domain = null) 
     {
         return Zend_Gdata_Gapps::APPS_BASE_FEED_URI;
     }

    /**
     * Returns the URL generated for this query, based on its current
     * parameters.
     *
     * @return string A URL generated based on the state of this query.
     */
    public function getQueryUrl()
    {

        $uri = $this->getBaseUrl();
        $uri .= Zend_Gdata_Gapps::APPS_GROUP_PATH;
        $uri .= '/' . $this->domain;
        $uri .= '/' . urlencode($this->getGroupId());
        $uri .= '/member';
        if ($this->getMemberId() !== null) {
            $uri .= '/' . urlencode($this->getMemberId());
        }
        $uri .= $this->getQueryString();
        return $uri;
    }

}
