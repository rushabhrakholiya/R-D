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

    foreach ($woocommerce->cart->cart_contents as $key => $values ) {
        if(isPackageProducts_bypass($values['product_id']))
        {
            unset($available_gateways['invoice']);
            return $available_gateways;
        }        
    }

    if(is_user_logged_in()){    	
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
    }else{
   		unset($available_gateways['invoice']);	
   	}
    return $available_gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'so1809762_set_gateways_by_context');
/* start code on order complete hook */
add_action('woocommerce_order_status_completed', 'custom_process_order', 10, 1);

function isPackageProducts_bypass($productid)
{
    $termName = wp_get_post_terms($productid, 'product_cat', array("fields" => "all"));   
    $termID = $termName[0]->term_taxonomy_id;
    if($termID == get_field('package_category_global','option')){
        return true;
    }else{
        return false;
    }

}

function custom_process_order($order_id) {

    if(is_user_logged_in()){
        $currentUserID = get_current_user_id();
        $order = new WC_Order( $order_id );
        $items = $order->get_items();
        foreach ( $items as $item ) {            
            $product_id = $item['product_id'];
            if(isPackageProducts_bypass($product_id)):
                $downloadlimit = get_field('download_limit',$product_id);   
                $productterm = get_field('product_category',$product_id);
                update_field('user_purchase_limit', $downloadlimit , 'user_'.$currentUserID);
                update_field('product_term_id', $productterm , 'user_'.$currentUserID);
            endif;
        }        
    }    
    return $order_id;
}
/* end code on order complete hook */
?>