<?php 
/**
 * Plugin Name: Bypass Woocommerce Payment
 * Plugin URI: elayers
 * Description: per user only one particular product purchasable.
 * Author: elayers
 * Version: 1.1
 * Author:       elayers
 * Author URI:   elayers
 * Author Email: elayers
*/
?>
<?php
function so1809762_set_gateways_by_context($available_gateways) {
    global $woocommerce;

    //$endpoint = $woocommerce->query->get_current_endpoint();

    if(is_user_logged_in())
    {    	
    	$currentUserID = get_current_user_id();
    	$purchaseLimit = get_user_meta( $currentUserID, "user_purchase_limit", true );    	
    	if(intval($purchaseLimit) != 0)
    	{
    		unset($available_gateways['cheque']);
		    unset($available_gateways['bacs']);
		    unset($available_gateways['cod']);
    	}
    	else
    	{
    		unset($available_gateways['invoice']);
    	}
    }
    else
   	{
   		unset($available_gateways['invoice']);	
   	}
    /*if ($endpoint == 'order-pay') {
        unset($available_gateways['cod']);
    } else {
        unset($available_gateways['stripe']);
    }*/

    return $available_gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'so1809762_set_gateways_by_context');
?>