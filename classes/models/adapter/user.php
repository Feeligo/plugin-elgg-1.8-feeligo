<?php
/**
 * Feeligo_Elgg18_Model_Adapter_User
 *
 * Adapts the Elgg User model to the interface expected by the Feeligo SDK
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

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../lib/sdk/interfaces/user_adapter.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../selector/user_friends.php');

 
class Feeligo_Elgg18_Model_Adapter_User implements FeeligoUserAdapter {
 
  public function __construct($elgg_user, $crgfv = null) {
    $this->user = $elgg_user;
  }
  
  
  /**
   * Whether the adaptee actually exists in the community (not a new object and not an invalid ID)
   */
  public function user_exists() {
    return ($this->user !== null && $this->user->guid > 0);
  }
  
  /**
   * Accessors for the adaptee's properties
   */
  public function id() {
    return $this->user->guid . '';
  }
  
  public function name() {
    return $this->user->name;
  }
  
  public function username() {
    return $this->user->username;
  }
  
  public function link() {
    return $this->user->getURL();
  }

  public function picture_url() {
    return $this->user->getIconURL('medium');
  }

  public function friends_selector() {
    return new Feeligo_Elgg18_Model_Selector_UserFriends($this);
  }

  // Elgg-specific shorthand method to get the GUID of the user
  public function guid() {
    return $this->user->guid;
  }
  
}
