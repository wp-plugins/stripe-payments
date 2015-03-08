<?php

class AcceptStripePaymentsShortcode {

    var $AcceptStripePayments = null;

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;
    protected static $payment_buttons = array();

    function __construct() {
        $this->AcceptStripePayments = AcceptStripePayments::get_instance();

        add_shortcode('accept_stripe_payment', array(&$this, 'shortcode_accept_stripe_payment'));
        add_shortcode('accept_stripe_payment_checkout', array(&$this, 'shortcode_accept_stripe_payment_checkout'));
        if (!is_admin()) {
            add_filter('widget_text', 'do_shortcode');
        }

    }

    public function interfer_for_redirect() {
        global $post;
        if (!is_admin()) {
            if (has_shortcode($post->post_content, 'accept_stripe_payment_checkout')) {
                $this->shortcode_accept_stripe_payment_checkout();
                exit;
            }
        }
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    function shortcode_accept_stripe_payment($atts, $content = "") {

        extract(shortcode_atts(array(
            'name' => 'Item Name',
            'price' => '0',
            'quantity' => '1',
            'url' => '',
            'currency' => $this->AcceptStripePayments->get_setting('currency_code'),
            'button_text' => $this->AcceptStripePayments->get_setting('button_text'),
                        ), $atts));

        if (!empty($url)) {
            $url = base64_encode($url);
        }
        else
            $url = '';
	$quantity = strtoupper($quantity);
	if ("$quantity" === "N/A") $quantity = "NA";

        $button_id = 'stripe_button_' . count(self::$payment_buttons);
        self::$payment_buttons[] = $button_id;
        $paymentAmount = ("$quantity" === "NA" ? $price : ($price * $quantity));
        $priceInCents = $paymentAmount * 100 ;

        $output = "<form action='" . $this->AcceptStripePayments->get_setting('checkout_url') . "' METHOD='POST'> ";

        $output .= "<script src='https://checkout.stripe.com/checkout.js' class='stripe-button'
          data-key='".$this->AcceptStripePayments->get_setting('api_publishable_key')."'
          data-panel-label='Pay'
          data-amount='{$priceInCents}' 
          data-name='{$name}'";
	if ("$quantity"==="NA")
          $output .= "data-description='{$paymentAmount} {$currency}'";
	else
          $output .= "data-description='{$quantity} piece".($quantity <> 1 ? "s" : "")." for {$paymentAmount} {$currency}'";
        $output .="data-label='{$button_text}'
          data-currency='{$currency}'
          ></script>";

        $output .= "<input type='hidden' value='{$name}' name='item_name' />";
        $output .= "<input type='hidden' value='{$price}' name='item_price' />";
        $output .= "<input type='hidden' value='{$quantity}' name='item_quantity' />";
        $output .= "<input type='hidden' value='{$currency}' name='currency_code' />";
        $output .= "<input type='hidden' value='{$url}' name='item_url' />";
        $output .= "</form>";

        return $output;
    }


    public function shortcode_accept_stripe_payment_checkout($atts = array(), $content) {


        extract(shortcode_atts(array(
            'item_name' => 'Item Name',
            'price' => '0',
            'quantity' => '1',
            'url' => '',
            'currency' => $this->AcceptStripePayments->get_setting('currency_code'),
            'button_text' => $this->AcceptStripePayments->get_setting('button_text'),
                        ), $atts)
        );

        if (empty($_POST['stripeToken'])) {
            echo ('Invalid Stripe Token');
            return;
        }
        if (empty($_POST['stripeTokenType'])) {
            echo ('Invalid Stripe Token Type');
            return;
        }
        if (empty($_POST['stripeEmail'])) {
            echo ('Invalid Request');
            return;
        }

        $paymentAmount = ($_POST['item_quantity'] !== "NA" ? ($_POST['item_price'] * $_POST['item_quantity']) : $_POST['item_price']);

        $currencyCodeType = strtolower($_POST['currency_code']);


        Stripe::setApiKey($this->AcceptStripePayments->get_setting('api_secret_key'));


        $GLOBALS['PaymentSuccessfull'] = false;

        ob_start();
        try {                

            $customer = Stripe_Customer::create(array(
                'email' => $_POST['stripeEmail'],
                'card'  => $_POST['stripeToken']
            ));

            $charge = Stripe_Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $paymentAmount*100,
                'currency' => $currencyCodeType
            ));

            $order = ASPOrder::get_instance();
            $order->insert($_POST, $charge);

            do_action('AcceptStripePayments_payment_completed', $order, $charge);

            $GLOBALS['PaymentSuccessfull'] = true;
            $item_url = base64_decode($_POST['item_url']);



        }catch (Exception $e) {

            if(!empty($charge->failure_code))
                $GLOBALS['asp_error'] = $charge->failure_code.": ".$charge->failure_message;
            else {
                $GLOBALS['asp_error'] =  $e->getMessage();
            }
        }

        include dirname(dirname(__FILE__)) . '/views/checkout.php';

        return ob_get_clean();

    }

}
