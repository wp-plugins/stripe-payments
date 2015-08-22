=== Stripe Payments ===
Contributors: Tips and Tricks HQ, wptipsntricks
Donate link: http://www.tipsandtricks-hq.com/
Tags: stripe, payment, payments, button, shortcode, digital goods, payment gateway, instant payment, commerce, digital downloads, download, downloads, e-commerce, e-store, ecommerce, eshop, donation
Requires at least: 3.5
Tested up to: 4.3
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Accept payments from your WordPress site via Stripe payment gateway.

== Description ==

This plugin allows you to accept credit card payments via Stripe. It has a simple shortcode that lets you place buy buttons anywhere on your WordPress site.

= Features =

* Quick installation and setup.
* Easily take payment for a service from your site via Stripe.
* Sell files, digital goods or downloads using your Stripe merchant account.
* Sell music, video, ebook, PDF or any other digital media files.
* The ultimate plugin to create Stripe payment buttons.
* Create buy buttons for your products or services on the fly and embed it anywhere on your site using a user-friendly shortcode.
* Ability to add multiple "Buy Now" buttons to a post/page.
* Allow users to automatically download the digital file after the purchase is complete.
* View purchase orders from your WordPress admin dashboard.
* Accept donation on your WordPress site for a cause.
* Create a stripe payment button widget and add it to your sidebar.

The setup is very easy. Once you have installed the plugin, all you need to do is enter your Stripe API credentials in the plugin settings (Settings -> Accept Stripe Payments) and your website will be ready to accept credit card payments.

You can run it in test mode by specifying test API keys in the plugin settings.

= Shortcode Attributes =

In order to create a buy button insert the following shortcode into a post/page.

`[accept_stripe_payment]`

It supports the following attributes in the shortcode -

    name:
    (string) (required) Name of the product
    Possible Values: 'Awesome Script', 'My Ebook', 'Wooden Table' etc.

    price:
    (number) (required) Price of the product or item
    Possible Values: '9.90', '29.95', '50' etc.

    quantity:
    (number) (optional) Number of products to be charged.
    Possible Values: '1', '5' etc.
    Default: 1

    currency:
    (string) (optional) Currency of the price specified.
    Possible Values: 'USD', 'GBP' etc
    Default: The one set up in Settings area.
    
    url:
    (URL) (optional) URL of the downloadable file.
    Possible Values: http://example.com/my-downloads/product.zip

    button_text:
    (string) (optional) Label of the payment button
    Possible Values: 'Buy Now', 'Pay Now' etc

`[accept_stripe_payment name="Cool Script" price="50" url="http://example.com/downloads/my-script.zip" button_text="Buy Now"]`

For detailed instructions please check the [WordPress Stripe Payments Plugin](https://www.tipsandtricks-hq.com/ecommerce/wordpress-stripe-plugin-accept-payments-using-stripe) documentation page.

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to "Plugins->Add New" from your dashboard
2. Search for 'stripe payments'
3. Click 'Install Now'
4. Activate the plugin

= Uploading via WordPress Dashboard =

1. Navigate to the "Add New" in the plugins dashboard
2. Navigate to the "Upload" area
3. Select `stripe-payments.zip` from your computer
4. Click "Install Now"
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `stripe-payments.zip`
2. Extract the `stripe-payments` directory on your computer
3. Upload the `stripe-payments` directory to the `/wp-content/plugins/` directory
4. Activate it from the Plugins dashboard

== Frequently Asked Questions ==

= Can I have multiple payment buttons on a single page? =

Yes, you can have any number of buttons on a single page.

= Can I use it in a WordPress Widgets? =

Yes, you can.

= Can I specify quantity of the item? =

Yes, please use "quantity" attribute.

= Can I change the button label? =

Yes, please use "button_text" attribute

= Can It be tested before going live? =

Yes, please visit Settings > Accept Stripe Payments screen for options.


== Screenshots ==

1. Stripe Plugin Settings
2. Stripe Plugin Payment Page
3. Stripe Plugin Orders Menu

== Upgrade Notice ==
None

== Changelog ==

= 1.0.2 = 
* Updated the payment shortcode parameter.

= 1.0.1 =
* First Release