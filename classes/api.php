<?php
/**
 * Feeligo_Elgg18_Api
 *
 * Implementation of the Feeligo API Adapter for Elgg 1.8
 *
 * @category   Feeligo
 * @package    Feeligo_Elgg18
 * @copyright  Copyright 2012 Feeligo
 * @license    GPLv3
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    Feeligo_Elgg18
 * @copyright  Copyright 2012 Feeligo
 * @license    GPLv3
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../lib/sdk/lib/api.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/adapter/user.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/selector/users.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/selector/actions.php');

 
class Feeligo_Elgg18_Api extends FeeligoApi
{

  /**
   * The singleton Api object
   *
   * @var Feeligo_Api_Feeligo
   */
  protected static $_instance;
  

  /**
   * Get or create the current api instance
   * 
   * @return Engine_Api
   */
  public static function getInstance() {
    if( is_null(self::$_instance) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }


  /**
   * Shorthand for getInstance
   *
   * @return Engine_Api
   */
  public static function _() {
    return self::getInstance();
  }
  

  /**
   * constructor (cannot be called directly)
   * 
   */
  protected function __construct() {
    parent::__construct();
    
    $this->_adapter_viewer = null;
    $this->_adapter_subject = null;

    // get the Viewer (i.e. the user who is logged in)
    $viewer_entity = elgg_get_logged_in_user_entity();
    if ($viewer_entity) {
      $this->_adapter_viewer = new Feeligo_Elgg18_Model_Adapter_User($viewer_entity, false);

      // get the Subject (i.e. the owner of the page, if it's an User)
      // make sure that an owner is defined, that it is an user, and not the same as the viewer
      $subject_entity = elgg_get_page_owner_entity();
      if ($subject_entity
          && $subject_entity->getType() == 'user'
          && $subject_entity->guid != $viewer_entity->guid) {
        $this->_adapter_subject = new Feeligo_Elgg18_Model_Adapter_User($subject_entity, true);
      }
    }
  }
  
  /**
   * Accessor for the Users adapter, which allows to query the API for Users
   */
  public function users() {
    if (!isset($this->_users)) {
      $this->_users = new Feeligo_Elgg18_Model_Selector_Users();
    }
    return $this->_users;
  }

  
  /**
   * Accessor for the Actions adapter, which allows to post Actions such as received gifts
   */
  public function actions() {
    if (!isset($this->_actions)) {
      $this->_actions = new Feeligo_Elgg18_Model_Selector_Actions();
    }
    return $this->_actions;
  }
  
  /**
   * Accessor for the adapter for the Viewer
   */
  public function viewer() {
    return $this->_adapter_viewer;
  }
  
  /**
   * Accessor for the adapter for the Subject
   */
  public function subject() {
    return $this->_adapter_subject;
  }
  
  /**
   * Whether a Viewer is defined
   */
  public function has_viewer() {
    return $this->_adapter_viewer !== null;
  }

  /**
   * Whether a Subject is defined
   */
  public function has_subject() {
    return $this->_adapter_subject !== null;
  }
  
}
