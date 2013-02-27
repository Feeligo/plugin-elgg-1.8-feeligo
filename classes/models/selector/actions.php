<?php
/**
 * Feeligo_Elgg18_Model_Selector_Actions
 *
 * Allows to post notifications on the site
 *
 * @category   Feeligo
 * @package    Feeligo_Elgg18
 * @copyright  Copyright 2012 Feeligo
 * @license    GPLv3
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    Feeligo_Elgg18_Model_Selector_Actions
 * @copyright  Copyright 2012 Feeligo
 * @license    GPLv3
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../lib/gift.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../adapter/action.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../lib/sdk/interfaces/actions_selector.php'); 

class Feeligo_Elgg18_Model_Selector_Actions implements FeeligoActionsSelector {
  
  function create($payload){
    
    // at the moment, only "user sent gift to user" action is supported
    if ($payload->name() != 'user_sent_gift_to_user') return null;
    
    // build an entity to store the action data
    $entity = new ElggObject;
    $entity->subtype = "feeligo_gift";
    $entity->gift_name = $payload->gift()->name;
    $entity->sender_guid = $payload->adapter_sender()->guid();
    $entity->recipient_guid = $payload->adapter_recipient()->guid();
    $entity->gift_message = $payload->gift()->message;
    $entity->gift_id = $payload->gift()->id;
    $entity->image_url = $payload->medium_url('medium', '60x72');
    $entity->access_id = 2; // 0: private, 1: logged in, 2: public
    $entity->localized_raw_body = $payload->localized_raw_body(get_current_language());
    // save the entity
    $entity->save();

    //add to river
    $r = add_to_river('river/gifts_create', 'create', $entity->sender_guid , $entity->getGUID());

    // return an adapter with the ID of the newly added entity to confirm successful creation
    return new Feeligo_Elgg18_Model_Adapter_Action($entity->getGUID());
  }
  
}
?>
