<?php
elgg_register_event_handler('init', 'system', 'feeligo_init'); 
 
//require_once(str_replace('//','/',dirname(__FILE__).'/').'classes/api.php'); 

/**
 * Initialize the plugin
 */
function feeligo_init() { 
  // register classes
  $path = elgg_get_plugins_path() . 'feeligo';
  elgg_register_class('Feeligo_Elgg18_Api'  , "$path/classes/api.php");
  elgg_register_class('Feeligo_Elgg18_Model_Gift'  , "$path/classes/models/gift.php");
  elgg_register_class('FeeligoGiftbarApp'   , "$path/lib/sdk/apps/giftbar.php");
  
  // register event handler to display the GiftBar in the site pages
  elgg_register_event_handler('pagesetup', 'system', 'display_giftbar');
	#add_to_river('example','create',elgg_get_logged_in_user_entity()->guid,51);
  elgg_register_entity_type ('object', 'gifts');
}

/**
 * Event handler to display the GiftBar
 *
 * adds the GiftBar's initialization Javascript code to the footer of your site
 */
function display_giftbar(){
  if ( elgg_get_logged_in_user_entity() != NULL ){
    elgg_extend_view('page/elements/foot', 'feeligo/foot'); 
  }
}

/**
 * Register the page handler for the API endpoint
 */
elgg_register_page_handler('feeligo', 'api_endpoint_page_handler');
elgg_register_page_handler('feeligo-api', 'api_endpoint_page_handler');

/**
 * Page handler for the API endpoint
 *
 * URLs take the form:
 * /feeligo/api?path=<path>&token=<token>
 * 
 * See http://feeligo.github.com/docs/ for implementation details
 *
 * @param array $page
 * @return bool
 */
function api_endpoint_page_handler($page) {
  include dirname(__FILE__) . "/pages/api_endpoint.php";
  return true;
}

    
?>
