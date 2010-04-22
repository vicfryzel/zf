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
 * Assists in constructing queries for Google Apps Group entries.
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
class Zend_Gdata_Gapps_GroupQuery extends Zend_Gdata_Gapps_Query
{

    /**
     * If not null, indicates the id of the group entry which
     * should be returned by this query.
     *
     * @var string
     */
    protected $_groupId = null;

    /**
     * Create a new instance.
     *
     * @param string $domain (optional) The Google Apps-hosted domain to use
     *          when constructing query URIs.
     * @param string $id (optional) ID for the group.
     * @param string $name (optional) Name for the group.
     * @param string $description (optional) Description for the group.
     * @param string $emailPermission (optional) Email permission for the
     *          group.
     * @param string $startId (optional) The Group ID to start the query at.
     */
    public function __construct($domain = null, $groupId = null,
            $member = null, $directOnly = null, $startId = null)
    {
        parent::__construct($domain);
        $this->setGroupId($groupId);
        $this->setMember($member);
        $this->setDirectOnly($directOnly);
        $this->setStartGroupId($startId);
    }

    /**
     * Set the group ID to query for. When set, only groups with a group ID
     * of the given value will be retrieved, updated, or deleted.  Set this
     * to null to disable this parameter.
     *
     * @see getGroupId
     * @param string $value The group ID of the group to retrieve, update,
     *          or delete.  Should be null to disable this parameter.
     */
     public function setGroupId($value)
     {
         $this->_groupId = $value;
     }

    /**
     * Get the group ID current set to query for.
     *
     * @see setGroupId
     * @return string The group ID to query for, or null if this parameter 
     *         is not set.
     */
    public function getGroupId()
    {
        return $this->_groupId;
    }

    /**
     * Set the member to query for. When set, only groups of which the given
     * username is a member will be returned in queries.  Set this parameter
     * to null to disable it.
     *
     * @see getMember
     * @param string $value The username to filter group search results by.
     */
    public function setMember($value)
    {
        if ($value !== null) {
            $this->_params['member'] = $value;
        }
        else {
            unset($this->_params['member']);
        }
    }

    /**
     * Get the member to query for. If no member is set, null will be
     * returned.
     *
     * @see setMember
     * @return string The username to filter group search results by,
     *         or null if this parameter is not set.
     */
    public function getMember()
    {
        if (array_key_exists('member', $this->_params)) {
            return $this->_params['member'];
        } else {
            return null;
        }
    }

    /**
     * Set whether to limit this query to return only groups the member is
     * directly subscribed to, or to include groups they are transitively
     * subscribed to.
     *
     * @see getDirectOnly
     * @param boolean $value True to get only groups subscribed to directly,
     *         or false to also include groups transitively subscribed to.
     */
    public function setDirectOnly($value)
    {
        if ($value === true) {
            $this->_params['directOnly'] = "true";
        } else {
            unset($this->_params['directOnly']);
        }
    }

    /**
     * Returns true if the groups this query returns will be limited to 
     * groups to which the member is directly subscribed.  Returns false
     * if the groups this query returns will also include groups that the 
     * member is transitively subscribed to.
     *
     * Example:
     *   Joe is an employee in New York, in the United States.  He works
     *   for a company that has multiple offices throughout the US.  There
     *   is a group for employees in New York called "newyork".  There is 
     *   a group for all employees in the US called "us".  The group "newyork"
     *   is subscribed to the group "us".  Thus, Joe is transitively 
     *   subscribed to "us", but not directly subscribed to "us".
     *
     *   If this method returns True, querying for joe will return the
     *   "newyork" group.
     *   If this method returns false, querying for joe will return the 
     *   groups ["newyork", "us"]
     *
     * @see setDirectOnly
     * @return boolean True to return only directly subscribed groups, or
     *         false to also return transitively subscribed groups.
     */
    public function getDirectOnly()
    {
        if (array_key_exists('directOnly', $this->_params)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set the first group ID which should be displayed when retrieving
     * a list of groups.
     *
     * @param string $value The first group ID to be returned, or null to
     *              disable.
     */
    public function setStartGroupId($value)
    {
        if ($value !== null) {
            $this->_params['start'] = $value;
        } else {
            unset($this->_params['start']);
        }
    }

    /**
     * Get the first group ID which should be displayed when retrieving
     * a list of groups.
     *
     * @return string The first group ID be returned, or null if this
     *              parameter is disabled.
     */
    public function getStartGroupId()
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
     * as Groups feed URLs do not follow the URL convention of other feeds.
     * The domain is added to the URL later.
     *
     * @see 
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
        if ($this->getGroupId() !== null) {
            $uri .= '/' . urlencode($this->getGroupId());
        }
        $uri .= $this->getQueryString();
        return $uri;
    }

}
