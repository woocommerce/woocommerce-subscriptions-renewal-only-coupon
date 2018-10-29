<?php
/*
 * Plugin Name: WooCommerce Subscriptions - Renewal Only Coupon
 * Plugin URI: https://github.com/Prospress/woocommerce-subscriptions-renewal-only-coupon/
 * Description: Make some coupons apply only to renewal payments, not initial sign-ups. To define the coupon codes, set the WCS_RENEWAL_ONLY_COUPON_CODES constant.
 * Author: Prospress Inc.
 * Author URI: https://prospress.com/
 * License: GPLv3
 * Version: 1.0.0
 * Requires at least: 4.0
 * Tested up to: 4.8
 *
 * GitHub Plugin URI: Prospress/woocommerce-subscriptions-renewal-only-coupon
 * GitHub Branch: master
 *
 * Copyright 2018 Prospress, Inc.  (email : freedoms@prospress.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package		WooCommerce Subscriptions - Renewal Only Coupon
 * @author		Prospress Inc.
 * @since		1.0
 */

require_once( 'includes/class-pp-dependencies.php' );

if ( false === PP_Dependencies::is_woocommerce_active( '3.2' ) ) {
	PP_Dependencies::enqueue_admin_notice( 'WooCommerce Subscriptions - Renewal Only Coupon', 'WooCommerce', '3.2' );
	return;
}

if ( false === PP_Dependencies::is_subscriptions_active( '2.4' ) ) {
	PP_Dependencies::enqueue_admin_notice( 'WooCommerce Subscriptions - Renewal Only Coupon', 'WooCommerce Subscriptions', '2.4' );
	return;
}

/**
 * Only mark coupons defined as Renewal Only as valid if the cart contains a renewal (regardless of anything else).
 *
 * @param boolean $is_valid
 * @param WC_Coupon $coupon
 * @param WC_Discounts $discount Added in WC 3.2 the WC_Discounts object contains information about the coupon being applied to either carts or orders - Optional
 * @return boolean Whether the coupon is valid or not
 */
function wcs_roc_coupon_is_valid( $is_valid, $coupon, $discount = null ) {

	if ( $is_valid && wcs_roc_is_invalid_renewal_only_coupon_usage( $coupon->get_code() ) ) {
		$is_valid = false;
		add_filter( 'woocommerce_coupon_error', 'wcs_roc_coupon_error_message', 10, 3 );
	}

	return $is_valid;
}
add_filter( 'woocommerce_coupon_is_valid', 'wcs_roc_coupon_is_valid', 1000 );


/**
 * Filter the coupon error message for Renewal Only coupon errors.
 *
 * @param string    $error_message Error message.
 * @param int       $error_code    Error code.
 * @param WC_Coupon $coupon        Coupon data.
 * @return string Error message.
 */
function wcs_roc_coupon_error_message( $error_message, $error_code, $coupon ) {

	if ( wcs_roc_is_invalid_renewal_only_coupon_usage( $coupon->get_code() ) ) {
		$error_message = __( 'Sorry, this coupon can only be applied to renewal payments.', 'woocommerce-subscriptions-renewal-only-coupon' );
	}

	return $error_message;
}


/**
 * If we have a coupon that is a renewal only coupon code and there is not a renewal in the cart, we have invalid usage. Otherwise, we don't.
 *
 * @param string $coupon_code A coupon code.
 * @return boolean Whether the coupon code is a renewal only code or not.
 */
function wcs_roc_is_invalid_renewal_only_coupon_usage( $coupon_code ) {

	if ( wcs_roc_is_renewal_only_coupon_code( $coupon_code ) && ! wcs_cart_contains_renewal() ) {
		return true;
	}

	return false;
}


/**
 * Check if a given coupon code is defined as a renewal only coupon code.
 *
 * @param string $coupon_code A coupon code.
 * @return boolean Whether the coupon code is a renewal only code or not.
 */
function wcs_roc_is_renewal_only_coupon_code( $coupon_code ) {

	if ( defined( 'WCS_RENEWAL_ONLY_COUPON_CODES' ) && in_array( $coupon_code, WCS_RENEWAL_ONLY_COUPON_CODES ) ) {
		return true;
	}

	return false;
}
