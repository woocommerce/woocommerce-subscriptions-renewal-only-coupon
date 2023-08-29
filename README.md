> IMPORTANT: This code is made available in the hope that it will be useful, but **without any warranty**. See the GNU General Public License included with the code for more details. Automattic or WooCommerce support services are also not available to assist with the use of this code.

# WooCommerce Subscriptions - Renewal Only Coupon

Need to offer customers a discount that can only be applied to a [manual renewal](https://docs.woocommerce.com/document/subscriptions/renewal-process/#section-4)?

Now you can!

Renewal Only Coupons makes it possible to create WooCommerce Product or Cart coupons that can only be applied to renewal payments, not initial sign-ups, by customers via the cart.

To define the coupon codes that should be renewal only, either:

1. Enter the coupon codes via the administration settings screen; or
1. Use custom code to set them via the `WCS_RENEWAL_ONLY_COUPON_CODES` constant or `wcs_renewal_only_coupon_codes'` filter

Store managers can still apply Renewal Only coupons to any order or subscription. Customers can only apply them to renewal order payments.

Example error when attempting to apply a _Renewal Only_ coupon to a new sign-up:

![](http://pic.pros.pr/ea993559e98b/Screen%20Shot%202018-10-30%20at%2011.15.42.png)

## Usage

### Step 0: Create a Coupon

To create a coupon:

1. Go to **WooCommerce > Coupons > Add Coupon**
1. Click **Discount Type**
1. Click either:
	1. _Recurring Product Discount_ or
	1._Recurring Product % Discount_
1. Complete other coupon fields

**Note:** For best results, we recommend using either a _Recurring Product Discount_ or _Recurring Product % Discount_ discount type.

These discount types can be applied to subscriptions via the **WooCommerce > Edit Subscription** administration screen, while others, like _Fixed cart_ or _Fixed product_ discount types can not be applied to subscriptions.

Other discount types may also yield unexpected behaviour, for example, the _Sign Up Fee_ discount type will not discount any part of a renewal payment, because it only discounts the sign-up fee component of an order, of which there is none on a renewal.


### Option 1: Enter Coupon Codes via Subscriptions Settings

1. Go to **WooCommerce > Settings > Subscriptions**
1. Scroll to the **Renewals** section
1. Click the **Renewal Only Coupons** text field
1. Enter a coupon code to restrict to renewal payments only
1. If additional coupon codes are requireed, enter a comma (`, `) before each additional code

![](http://pic.pros.pr/0f138b7a4d9d/Screen%20Shot%202018-10-30%20at%2010.54.38.png)

### Option 2: Define Coupon Codes in Class Constant

```php
define( 'WCS_RENEWAL_ONLY_COUPON_CODES', array( 'coupon_code_one', 'hallowoon', 'cyber_monday' )  );
```

### Option 3: Define Coupon Codes via Filter

```php
function eg_my_renewal_only_coupon_codes( $existing_codes ) {

	$existing_codes[] = 'my_new_code';
	$existing_codes[] = 'my_other_new_code';

	return $existing_codes;
}
add_filter( 'wcs_renewal_only_coupon_codes', 'eg_my_renewal_only_coupon_codes' );
```

## Installation

To install:

1. Download the latest version of the plugin [here](https://github.com/Prospress/woocommerce-subscriptions-renewal-only-coupon/archive/master.zip)
1. Go to **Plugins > Add New > Upload** administration screen on your WordPress site
1. Select the ZIP file you just downloaded
1. Click **Install Now**
1. Click **Activate**

### Updates

To keep the plugin up-to-date, use the [GitHub Updater](https://github.com/afragen/github-updater).

## Requirements

This plugin requires:

* PHP 5.6 or newer
* WooCommerce 3.2.0 or newer
* WooCommerce Subscriptions 2.4.0 or newer

## Reporting Issues

If you find an problem or would like to request this plugin be extended, please [open a new Issue](https://github.com/Prospress/woocommerce-subscriptions-renewal-only-coupon/issues/new).

---

<p align="center">
	<a href="https://prospress.com/">
		<img src="https://cloud.githubusercontent.com/assets/235523/11986380/bb6a0958-a983-11e5-8e9b-b9781d37c64a.png" width="160">
	</a>
</p>
