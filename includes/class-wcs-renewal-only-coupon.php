<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Make some coupons only valid for renewal payments via the front end.
 *
 * @version  1.0.0
 * @category Class
 * @author   Prospress
 */

class WCS_Renewal_Only_Coupon {

	/** @var WCS_Renewal_Only_Coupon */
	private static $instance = null;

	/**
	 * Get the active renewal only coupon instance.
	 *
	 * @return WCS_Renewal_Only_Coupon
	 */
	final public static function instance() {

		if ( empty( self::$instance ) ) {
			if ( ! did_action( 'plugins_loaded' ) && 'plugins_loaded' !== current_action() ) {
				wcs_doing_it_wrong( __METHOD__, 'This method was called before the "plugins_loaded" hook. It applies a filter to the customer data store instantiated. For that to work, it should first be called after all plugins are loaded.', '1.0.0' );
			}

			$class = apply_filters( 'wcs_renewal_only_coupon_class', 'WCS_Renewal_Only_Coupon' );
			self::$instance = new $class();
			self::$instance->init();
		}

		return self::$instance;
	}

	protected function init() {
		add_filter( 'woocommerce_coupon_is_valid', [ $this, 'coupon_is_valid' ], 1000 );
	}

	/**
	 * Only mark coupons defined as Renewal Only as valid if the cart contains a renewal (regardless of anything else).
	 *
	 * @param boolean $is_valid
	 * @param WC_Coupon $coupon
	 * @param WC_Discounts $discount Added in WC 3.2 the WC_Discounts object contains information about the coupon being applied to either carts or orders - Optional
	 * @return boolean Whether the coupon is valid or not
	 */
	public function coupon_is_valid( $is_valid, $coupon, $discount = null ) {

		if ( $is_valid && $this->is_invalid_coupon_usage( $coupon->get_code() ) ) {
			$is_valid = false;
			add_filter( 'woocommerce_coupon_error', [ $this, 'coupon_error_message' ], 10, 3 );
		}

		return $is_valid;
	}


	/**
	 * Filter the coupon error message for Renewal Only coupon errors.
	 *
	 * @param string    $error_message Error message.
	 * @param int       $error_code    Error code.
	 * @param WC_Coupon $coupon        Coupon data.
	 * @return string Error message.
	 */
	public function coupon_error_message( $error_message, $error_code, $coupon ) {

		if ( $this->is_invalid_coupon_usage( $coupon->get_code() ) ) {
			$error_message = __( 'Sorry, this coupon can only be applied to renewal payments.', 'woocommerce-subscriptions-renewal-only-coupon' );
		}

		return $error_message;
	}


	/**
	 * If we're not on the front end, we have a coupon that is a renewal only coupon code
	 * and there is not a renewal in the cart, we have invalid usage. Otherwise, we don't.
	 *
	 * @param string $coupon_code A coupon code.
	 * @return boolean Whether the coupon code is a renewal only code or not.
	 */
	protected function is_invalid_coupon_usage( $coupon_code ) {

		if ( ! is_admin() && $this->is_renewal_only_coupon_code( $coupon_code ) && ! wcs_cart_contains_renewal() ) {
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
	protected function is_renewal_only_coupon_code( $coupon_code ) {

		if ( defined( 'WCS_RENEWAL_ONLY_COUPON_CODES' ) && in_array( $coupon_code, WCS_RENEWAL_ONLY_COUPON_CODES ) ) {
			return true;
		}

		return false;
	}
}
