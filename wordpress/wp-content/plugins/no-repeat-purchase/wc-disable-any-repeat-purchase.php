<?php
/**
 * Plugin Name: No Repeat Purchase
 * Plugin URI: elayers
 * Description: per user only one particular product purchasable.
 * Author: elayers
 * Version: 1.1
 * Author:       elayers
 * Author URI:   elayers
 * Author Email: elayers
 * 
 * @param bool $purchasable true if product can be purchased
 * @param \WC_Product $product the WooCommerce product
 * @return bool $purchasable the updated is_purchasable check
 */
function isPackageProducts($product)
{
	$termName = wp_get_post_terms($product->id, 'product_cat', array("fields" => "all"));	
	$termID = $termName[0]->term_taxonomy_id;
	if($termID == get_field('package_category_global','option')){
		return true;
	}else{
		return false;
	}

}
function sv_disable_repeat_purchase( $purchasable, $product ) {

	// Don't run on parents of variations,
	// function will already check variations separately
	if ( $product->is_type( 'variable' ) ) {
		return $purchasable;
	}
	
	// Get the ID for the current product (passed in)
	$product_id = $product->is_type( 'variation' ) ? $product->variation_id : $product->id; 
    
    // return false if the customer has bought the product / variation
    if(isPackageProducts($product)){
	    if ( wc_customer_bought_product( get_current_user()->user_email, get_current_user_id(), $product_id ) ) {
	        $purchasable = false;
	    }
	}
    
    // Double-check for variations: if parent is not purchasable, then variation is not
    if ( $purchasable && $product->is_type( 'variation' ) ) {
        $purchasable = $product->parent->is_purchasable();
    }
    
    return $purchasable;
}
add_filter( 'woocommerce_is_purchasable', 'sv_disable_repeat_purchase', 10, 2 );


/**
 * Shows a "purchase disabled" message to the customer
 */
function sv_purchase_disabled_message() {
	
	// Get the current product to see if it has been purchased
	global $product;			
	if ( $product->is_type( 'variable' ) ) {
		
		foreach ( $product->get_children() as $variation_id ) {
			// Render the purchase restricted message if it has been purchased
			if(isPackageProducts($product)){
				if ( wc_customer_bought_product( get_current_user()->user_email, get_current_user_id(), $variation_id ) ) {
					sv_render_variation_non_purchasable_message( $product, $variation_id );
				}
			}
		}
		
	} else {		
		if(isPackageProducts($product)){			
			if ( wc_customer_bought_product( get_current_user()->user_email, get_current_user_id(), $product->id ) ) {
				echo '<div class="woocommerce"><div class="woocommerce-info wc-nonpurchasable-message">You\'ve already purchased this product! It can only be purchased once.</div></div>';
			}
		}
	}
}
add_action( 'woocommerce_single_product_summary', 'sv_purchase_disabled_message', 31 );


/**
 * Generates a "purchase disabled" message to the customer for specific variations
 * 
 * @param \WC_Product $product the WooCommerce product
 * @param int $no_repeats_id the id of the non-purchasable product
 */
function sv_render_variation_non_purchasable_message( $product, $no_repeats_id ) {
	
	// Double-check we're looking at a variable product
	if ( $product->is_type( 'variable' ) && $product->has_child() ) {
		$variation_purchasable = true;
		foreach ( $product->get_available_variations() as $variation ) {
			// only show this message for non-purchasable variations matching our ID
			if ( $no_repeats_id === $variation['variation_id'] ) {
				$variation_purchasable = false;	
				echo '<div class="woocommerce"><div class="woocommerce-info wc-nonpurchasable-message js-variation-' . sanitize_html_class( $variation['variation_id'] ) . '">You\'ve already purchased this product! It can only be purchased once.</div></div>';
			}
		}
	}
		
	if ( ! $variation_purchasable ) {
		wc_enqueue_js("
			jQuery('.variations_form')
				.on( 'woocommerce_variation_select_change', function( event ) {
					jQuery('.wc-nonpurchasable-message').hide();
				})
				.on( 'found_variation', function( event, variation ) {
					jQuery('.wc-nonpurchasable-message').hide();
					if ( ! variation.is_purchasable ) {
						jQuery( '.wc-nonpurchasable-message.js-variation-' + variation.variation_id ).show();
					}
				})
			.find( '.variations select' ).change();
		");
	}
}