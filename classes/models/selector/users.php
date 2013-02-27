<?php
/**
 * Feeligo_Elgg18_Model_Selector_Users
 *
 * Allows to query the API for users
 *
 * @category   Feeligo
 * @package    Feeligo_Elgg18
 * @copyright  Copyright 2012 Feeligo
 * @license    GPLv3
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    Feeligo_Elgg18_Model_Adapter_User
 * @copyright  Copyright 2012 Feeligo
 * @license    GPLv3
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../lib/sdk/interfaces/users_selector.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../lib/sdk/lib/exceptions/not_found_exception.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../adapter/user.php');
 
class Feeligo_Elgg18_Model_Selector_Users implements FeeligoUsersSelector {
 
  public function __construct() {
   
  }
  
  /**
   * find a single user by ID
   *
   * if not found, throws an exception if $throw=true, and returns null otherwise
   */
  public function find($id, $throw = true) {
    // look for Elgg user with id = $id
    if (($user = get_user($id)) !== null && $user->guid == $id) {
      // return an Adapter adapting the Elgg User
      return new Feeligo_Elgg18_Model_Adapter_User($user);
    }
    if ($throw) throw new FeeligoEntityNotFoundException('type', 'could not find user with id='.$id);
    return null;
  }
  
  /**
   * find many users by a list of IDs
   */
  public function find_all($ids) { 
    return $this->_collect_users($this->_get_users(
      array("e.guid IN (".join(",",$ids).")")
    ));
  }
 
  /**
   * get all users
   */
  public function all($limit = null, $offset = 0) {
    return $this->_collect_users($this->_get_users(
      array(),
      array(),
      $limit, $offset
    ));
  }
  
  /**
   * search for users by name
   */
  public function search($query, $limit = null, $offset = 0) {
    global $CONFIG;
    return $this->_collect_users($this->_get_users(
      array("u.name LIKE '%".$query."%'"),
      array(),
      $limit, 
      $offset
    ));

  }
  
  private function _collect_users($users) {
    $collection =array();
    if (sizeof($users) > 0) {
      foreach($users as $user) {
        $collection[] = new Feeligo_Elgg18_Model_Adapter_User($user, null);
      }
    }
    return $collection;
  }
  
  private function _get_users($wheres = array(), $joins = array(), $limit = null , $offset = 0){
    global $CONFIG;

    return elgg_get_entities( 
      array( 
        'wheres' => $wheres, 
        "types"=> array('user'),
        'limit' => $limit,
        'offset' => $offset,
        'joins' => array_merge($joins, array(
          "JOIN {$CONFIG->dbprefix}users_entity u ON e.guid = u.guid"
        )),
        'order_by' => "u.name ASC"
      )
    );
  }
 
}
