<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add setting to allow store owners to define coupon codes that are renewal only.
 *
 * @version  1.0.0
 * @category Class
 * @author   Prospress
 */

class WCS_Renewal_Only_Coupon_Settings {

	/**
	 * The prefix for subscription settings
	 *
	 * @since 1.0
	 */
	private static $option_prefix = 'woocommerce_subscriptions_renewal_only_coupon';

	public function init() {
		if ( is_admin() ) {
			add_filter( 'woocommerce_subscription_settings', [ $this, 'add_settings' ] );
		}
	}

	/**
	 * Add Renewal Only Coupon setting to the WooCommerce > Subscription's settings page.
	 *
	 * @since 1.0.0
	 */
	public function add_settings( $settings ) {

		array_splice( $settings, 11, 0, array(
			array(
				'name'     => __( 'Renewal Only Coupons', 'woocommerce-subscriptions-renewal-only-coupon' ),
				'desc'     => __( 'Restrict some coupons to only to used on renewal payments, not initial sign-ups. Enter coupon codes here to define the coupons to restrict. Separate codes with a comma e.g. "coupon_one, coupon_two, hallowoondiscount"). Coupon codes do not have to exist yet to be input here, nor does the coupon the code represents need to be a recurring coupon type.', 'woocommerce-subscriptions-renewal-only-coupon' ),
				'tip'      => '',
				'id'       => self::$option_prefix . '_codes',
				'css'      => 'min-width:150px;',
				'type'     => 'text',
				'desc_tip' => true,
			),
		) );

		return $settings;
	}


	/**
	 * Get the coupon codes defined in the setting field.
	 *
	 * @return array[string] Set of coupon codes which should be renewal only.
	 */
	public function get_coupon_codes() {
		$coupon_codes = get_option( self::$option_prefix . '_codes', '' );

		try {
			$coupon_codes = explode( ',', $coupon_codes );
		} catch ( Exception $e ) {
			add_filter( 'admin_notices', [ $this, 'invalid_codes_notice' ], 10, 3 );
			$coupon_codes = array();
		}

		return $coupon_codes;
	}


	/**
	 * Display a notice about an invalid coupon 
	 */
	public static function invalid_codes_notice() {

		if ( current_user_can( 'manage_woocommerce' ) ) {

			$notice_content = __( 'Invalid renewal only coupon codes defined. Please enter valid coupon codes on the %1$sWooCommerce > Settings > Subscriptions%2$s settings page, under the Renewal Only Coupons section.',  'woocommerce-subscriptions-renewal-only-coupon' );
			$notice_content = sprintf( $notice_content, sprintf( '<a href="%s">', WC_Subscriptions_Admin::settings_tab_url() ), '</a>' );

			$notice = new WCS_Admin_Notice( 'error' );
			$notice->set_simple_content( $notice_content );
			$notice->display();
		}
	}
}
