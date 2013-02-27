<?php
/**
 * River entry for "user sent gift to user" action
 *
 * @package Gifts
 */


$action = $vars['item']->getObjectEntity();
$excerpt = elgg_get_excerpt($action->message);
$recipient = get_user($action->recipient_guid);
$sender = get_user($action->sender_guid);
$action_name = $action->name;


/**
 * replace ${..} vars with HTML in the raw body
 */
$body = $action->localized_raw_body;
$body = str_replace('${subject}',
    '<a href="'.$sender->getURL().'" data-flg-role="link" '
    . 'data-flg-type="user" data-flg-id="'.$sender->getGUID().'" '
    . 'data-flg-source="action">'.$sender->name.'</a>'
    , $body);
$body = str_replace('${direct_object}'  ,
    '<span data-flg-role="link" data-flg-type="gift" '
    . 'data-flg-id="'.$action->gift_id.'" data-flg-source="action">'
    . $action->gift_name.'</span>'
    , $body);
$body = str_replace('${indirect_object}',
    '<a href="'.$recipient->getURL().'" data-flg-role="link" '
    . 'data-flg-type="user" data-flg-id="'.$recipient->getGUID().'" '
    . 'data-flg-source="action">'.$recipient->name.'</a>'
    , $body);

/**
 * display the river item
 */
echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'summary' => $body,
	'message' => ($action->image_url ? '<img src = "'.$action->image_url.'" data-flg-role="link" '
      . 'data-flg-type="gift" data-flg-id="'.$action->gift_id.'"'
      . 'data-flg-source="action" >' : '')
      . ($action->gift_message ?
        '<p class="sent-gift-message">&laquo;'.htmlentities(utf8_decode($action->gift_message)).'&raquo;</p>'
        : ''),
)); 

$item = $vars['item'];

?> 
