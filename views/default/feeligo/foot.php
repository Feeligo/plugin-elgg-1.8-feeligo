<?php
// instantiate FeeligoGiftbarApp with the singleton instance of Feeligo_Mysite_Api
$giftbar = new FeeligoGiftbarApp(Feeligo_Elgg18_Api::_());
?>

<!-- Feeligo GiftBar -->
<?php
  if ($giftbar->is_enabled()) {
    echo "<script type='text/javascript'>".$giftbar->initialization_js()."</script>";
    echo "<script type='text/javascript' src='".$giftbar->loader_js_url()."'></script>";
    
    echo "<div id='flg_giftbar_container'></div>";
  }
?>