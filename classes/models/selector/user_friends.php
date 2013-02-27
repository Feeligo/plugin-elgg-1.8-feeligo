<?php
/**
 * Feeligo_Elgg18_Model_Selector_UserFriends
 *
 * Allows to query the API for the friends of a given User
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
 
class Feeligo_Elgg18_Model_Selector_UserFriends implements FeeligoUsersSelector {
 
  public function __construct($user_adapter) {
    $this->_user_adapter = $user_adapter;
  
   
  }
  private function _user(){
    return $this->_user_adapter->user;
  }
 
  public function find($id, $throw = true) {
    // look for Elgg user with id = $id
    if ( user_is_friend($this->_user()->guid,$id) && ($user = get_user($id)) !== null && $user->guid == $id) {
      // return an Adapter adapting the Elgg User
      return new Feeligo_Elgg18_Model_Adapter_User($user);
    }
    if ($throw) throw new FeeligoEntityNotFoundException('type', 'could not find user with id='.$id);
    return null;
  }
  
  public function find_all($ids) { 
    return $this->_collect_users(
      $this->_get_user_friends(
        array("e.guid IN (".join(",",$ids).")")
      )
    );
  }
 
  /**
   * get all friends
   */
  public function all($limit = null, $offset = 0) {
    return $this->_collect_users($this->_get_user_friends(
        array(),
        array(),
        $limit,
        $offset
      ));
  }
  
  /**
   * search for friends by name
   */
  public function search($query, $limit = null, $offset = 0) {
    global $CONFIG;

    return $this->_collect_users(
      $this->_get_user_friends(
        array("u.name LIKE '%".$query."%'"),
        array(),
        $limit, 
        $offset
      )
    );

  }
  
  /**
   * gets a list of users and returns a list of Adapters wrapping each user
   */
  private function _collect_users($users) {
    $collection = array();
    if (sizeof($users) > 0) {
      foreach($users as $user) {
        $collection[]= new Feeligo_Elgg18_Model_Adapter_User($user, null);
      }
    }
    return $collection;
  }
  
  /**
   * Friendship is stored in the `relationship` table. The table has the following columns:
   * | guid_one | relationship | guid_two |
   *
   * Friendship is implemented asymmetrically in Elgg.
   * When user A adds user B as a friend, the following line is created :
   * | guid_one | relationship | guid_two |
   * | A        | friend       | B        |
   *
   * Therefore we can get the guids of A's friends with "WHERE relationship=friends AND guid_one=A"
   */
  private function _get_user_friends($wheres = array(), $joins = array(), $limit = null , $offset = 0){
    global $CONFIG;
    return elgg_get_entities( 
      array( 
        'wheres' => array_merge($wheres, array(
          "r.relationship = 'friend'",
          "r.guid_two != {$this->_user()->guid}" // avoids returning the user itself (should not happen)
        )),
        "types"=> array('user'),
        'limit' => $limit,
        'offset' => $offset,
        'joins' => array_merge(array(
          // the relationships table which contains friendships
          "JOIN {$CONFIG->dbprefix}entity_relationships r
           ON (r.guid_one = {$this->_user()->guid} AND r.guid_two = e.guid)",
           // the users_entity table which contains information about the users
           "JOIN {$CONFIG->dbprefix}users_entity u ON r.guid_two = u.guid"
        ), $joins),
        'order_by' => 'u.name ASC'
      )
    );
  }
 
}
