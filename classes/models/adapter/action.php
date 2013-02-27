<?php
/**
 * Feeligo_Elgg18_Model_Adapter_Action
 *
 * Dummy object to be returned after an action is created, as expected by the Feeligo API.
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

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../lib/sdk/interfaces/action_adapter.php');

class Feeligo_Elgg18_Model_Adapter_Action implements FeeligoActionAdapter {
  public function __construct($id) {
    $this->id = $id;
  }
  
  function id(){
    return $this->id;
  }
}
?>
