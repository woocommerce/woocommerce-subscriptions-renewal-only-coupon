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

require_once( 'includes/class-wcs-renewal-only-coupon.php' );

/**
 * Bootstrap the Renewal Only coupon class.
 */
function wcs_renewal_only_coupon() {
	return WCS_Renewal_Only_Coupon::instance();
}
add_action( 'plugins_loaded', 'wcs_renewal_only_coupon' );
