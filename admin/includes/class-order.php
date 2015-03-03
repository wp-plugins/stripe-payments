<?php

class ASPOrder {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	function __construct()
	{
		$this->AcceptStripePayments = AcceptStripePayments::get_instance();
		$this->text_domain = $this->AcceptStripePayments->get_plugin_slug();
	}

	public function register_post_type()
	{
		$text_domain = $this->AcceptStripePayments->get_plugin_slug();
		$labels = array(
			'name'                => _x( 'Orders', 'Post Type General Name', $text_domain ),
			'singular_name'       => _x( 'Order', 'Post Type Singular Name', $text_domain ),
			'menu_name'           => __( 'Stripe Orders', $text_domain ),
			'parent_item_colon'   => __( 'Parent Order:', $text_domain ),
			'all_items'           => __( 'All Orders', $text_domain ),
			'view_item'           => __( 'View Order', $text_domain ),
			'add_new_item'        => __( 'Add New Order', $text_domain ),
			'add_new'             => __( 'Add New', $text_domain ),
			'edit_item'           => __( 'Edit Order', $text_domain ),
			'update_item'         => __( 'Update Order', $text_domain ),
			'search_items'        => __( 'Search Order', $text_domain ),
			'not_found'           => __( 'Not found', $text_domain ),
			'not_found_in_trash'  => __( 'Not found in Trash', $text_domain ),
		);
		$args = array(
			'label'               => __( 'orders', $text_domain ),
			'description'         => __( 'Stripe Orders', $text_domain ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'revisions', 'custom-fields', ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 80,
			'menu_icon'           => 'dashicons-clipboard',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'capabilities' => array(
   				'create_posts' => false, // Removes support for the "Add New" function
  			),
  			'map_meta_cap' => true,
		);

		register_post_type( 'stripe_order', $args );
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
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	/**
	 * Receive Response of GetExpressCheckout and ConfirmPayment function returned data.
	 * Returns the order ID.
	 *
	 * @since     1.0.0
	 *
	 * @return    Numeric    Post or Order ID.
	 */
	public function insert($order_details, $charge_details)
	{
		$post = array();
		$post['post_title'] = $order_details['item_quantity'].' '.$order_details['item_name'].' - '.$order_details['item_price'].' '.$order_details['currency_code'];
		$post['post_status'] = 'pending';

		$output = '';

		// Add error info in case of failure
		if( !empty($charge_details->failure_code) ) {

			$output .= "<h2>Payment Failure Details</h2>"."\n";
			$output .= $charge_details->failure_code.": ".$charge_details->failure_message;
			$output .= "\n\n";
		}
		else {
			$post['post_status'] = 'publish';
		}

		$output .= __("<h2>Order Details</h2>")."\n";
		$output .= __("Order Time: ").date("F j, Y, g:i a",strtotime('now'))."\n";
		$output .= __("Transaction ID: ").$charge_details->id."\n";
		$output .= __("Stripe Token: ").$order_details['stripeToken']."\n";
		$output .= "--------------------------------"."\n";
		$output .= __("Product Name: ").$order_details['item_name']."\n";
		$output .= __("Quantity:"). $order_details['item_quantity']."\n";
		$output .= __("Amount:"). $order_details['item_price'].' '.$order_details['currency_code']."\n";
		$output .= "--------------------------------"."\n";
		$output .= __("Total Amount:"). ($order_details['item_price']*$order_details['item_quantity']).' '.$order_details['currency_code']."\n";

		
		$output .= "\n\n";

		$output .= __("<h2>Customer Details</h2>")."\n";
		$output .= __("E-Mail Address: ").$order_details['stripeEmail']."\n";
		$output .= __("Country: ").$charge_details->card->country."\n";

		$post['post_content'] = $output;
		$post['post_type'] = 'stripe_order';

		# code...
		return wp_insert_post( $post );
	}

}
?>
