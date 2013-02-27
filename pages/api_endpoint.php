<?php
/**
 * this is the page handler for the Feeligo Community API endpoint
 */

/**
 * load controller from Feeligo SDK as well as Elgg-specific API adapter
 */
//require_once(str_replace('//','/',dirname(__FILE__).'/').'../classes/api.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../lib/sdk/lib/controllers/controller.php'); 


/** 
 * handle the request
 */

// instantiate the FeeligoController class from the SDK, passing an API adapter instance
$flg_ctrl = new FeeligoController(Feeligo_Elgg18_Api::_());

// call the controller's `run` method ; this will process the request and return a response
// based on the parameters
$flg_response = $flg_ctrl->run();


/**
 * output the response headers and body to the client
 */

header("HTTP/1.0 ".$flg_response->code());

foreach($flg_response->headers() as $k => $v) {
  header($k.": ".$v);
}

echo $flg_response->body();