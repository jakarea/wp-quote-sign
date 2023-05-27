<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link              https://giopio.com
 * @since             1.0.0
 *
 * @package     Quotation Sign
 * @subpackage  Quotation Sign/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quotation Sign
 * @subpackage Quotation Sign/includes
 * @author     Md Arif <arifypp@gmail.com>
 */
class Quotation_sign_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $quotation_sign    The ID of this plugin.
	 */
	private $quotation_sign;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/*************************************************************
	 * ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE
	 *
	 * @tutorial access_plugin_admin_public_methodes_from_inside.php
	 */
	/**
	 * Store plugin main class to allow public access.
	 *
	 * @since    20180622
	 * @var object      The main class.
	 */
	public $main;
	// END ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $quotation_sign       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	// public function __construct( $quotation_sign, $version ) {

	// 	$this->Quotation_sign = $quotation_sign;
	// 	$this->version = $version;

	// }

	/*************************************************************
	 * ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE
	 *
	 * @tutorial access_plugin_admin_public_methodes_from_inside.php
	 */
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $quotation_sign       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $quotation_sign, $version, $plugin_main ) {

		$this->quotation_sign = $quotation_sign;
        $this->version = $version;
        $this->main = $plugin_main;

		add_shortcode( 'quotation-sign-form', array( $this, 'quotation_sign_shortcode' ) );

		//Submit form
		add_action( 'init', array( $this, 'my_form_submission_handler' ) );
		// qoutation_sign_display_session_data
		add_shortcode( 'submitted-data-page', array( $this, 'qoutation_sign_display_session_data' ) );
		// END ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE
		add_action( 'init', array( $this, 'quotation_sign_pay' ) );
		add_action( 'init', array( $this, 'quotation_sign_pay_success' ) );

    }
    // END ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quotation_sign_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quotation_sign_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// register bootstrap but before check if it is already registered
		if ( ! wp_style_is( 'bootstrap', 'registered' ) ) {
			wp_register_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
		}
		wp_enqueue_style( 'bootstrap' );

		wp_enqueue_style( $this->quotation_sign, plugin_dir_url( __FILE__ ) . 'css/quotation-sign-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quotation_sign_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quotation_sign_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// register bootstrap but before check if it is already registered
		if ( ! wp_script_is( 'bootstrap', 'registered' ) ) {
			wp_register_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		}
		wp_enqueue_script( 'bootstrap' );

		wp_enqueue_script( 'digital_sign', plugin_dir_url( __FILE__ ) . 'js/quotation-sign.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->quotation_sign, plugin_dir_url( __FILE__ ) . 'js/quotation-sign-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add shortcode
	 *
	 * @since    1.0.0
	 */
	public function quotation_sign_shortcode() {
		ob_start();
		include_once( plugin_dir_path( __FILE__ ) . 'partials/quotation-sign-public-display.php' );
		return ob_get_clean();
	}

	/**
	 * Submit form
	 */
	// In your plugin file
	public function my_form_submission_handler() {
		// Check if the form has been submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['my_form_submit'])) {

			// Check if form data is empty return error
			if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['square_meters'])) {
				// Set error message
				$_SESSION['error'] = 'Please fill all the fields';
				// Redirect to submitted-data-page
				wp_redirect(get_permalink(get_page_by_path('submitted-data-page')));
				exit;
			}

			// Retrieve form data
			$form_data = array(
				'name' => sanitize_text_field($_POST['name']),
				'email' => sanitize_email($_POST['email']),
				'phone' => sanitize_text_field($_POST['phone']),
				'square_meters' => intval($_POST['square_meters'])
			);

			// Pass the form data to the qoutation_sign_display_session_data function
			$this->qoutation_sign_display_session_data($form_data);

			// Check if submitted-data-page exists or not and if not, create it
			$submitted_data_page = get_page_by_path('submitted-data-page');
			if (!$submitted_data_page) {
				// Create post object
				$submitted_data_page_args = array(
					'post_title' => 'Submitted Data Page',
					'post_content' => '[submitted-data-page]',
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type' => 'page',
					'post_name' => 'submitted-data-page'
				);

				// Insert the post into the database and get its ID
				$submitted_data_page_id = wp_insert_post($submitted_data_page_args);
			} else {
				$submitted_data_page_id = $submitted_data_page->ID;
			}

			// Redirect to submitted-data-page
			// Build the redirect URL with form data
			$redirect_url = add_query_arg('form_data', urlencode(base64_encode(serialize($form_data))), get_permalink($submitted_data_page_id));

			// Redirect to submitted-data-page with form data
			wp_redirect($redirect_url);			
			exit;
		}

	}


	function qoutation_sign_display_session_data($form_data) {
		// Start the output buffering
		ob_start();

		// Retrieve form data
		if ( isset($_GET['form_data']) ) {
		$form_data = unserialize(base64_decode($_GET['form_data']));
		}
		else
		{
			$form_data = array();
		}
		//
		require plugin_dir_path(__FILE__) . 'partials/submission-data.php';
	
		return ob_get_clean();
	}	
	

	/**
	 * Submit form quotation_sign_pay
	 */
	public function quotation_sign_pay() {
		// Check if the form has been submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quotation_sign_pay'])) {

			// retrieve form data from base64 encoded string
			$form_data = unserialize(base64_decode($_GET['form_data']));
			
			// Add signature to form_data variable
			$signature = sanitize_text_field($_POST['signature']);
			// upload signature and get the path to signature
			if( $signature ){
				$signature = str_replace('data:image/png;base64,', '', $signature);
				$signature = str_replace(' ', '+', $signature);
				$signature = base64_decode($signature);
				$filename = 'signature-' . time() . '.png';
				$upload_dir = wp_upload_dir();
				$signature_path = $upload_dir['path'] . '/' . $filename;
				file_put_contents($signature_path, $signature);
				$attachment = array(
					'post_mime_type' => 'image/png',
					'post_title' => $filename,
					'post_content' => '',
					'post_status' => 'inherit'
				);
				$attach_id = wp_insert_attachment($attachment, $signature_path);
				$signature_url = wp_get_attachment_url($attach_id);
				$form_data['signature'] = $signature_url;
			}

			$form_data['amount'] = sanitize_text_field($_POST['amount']);
			$form_data['dueamount'] = sanitize_text_field($_POST['dueamount']);

			$quotation_sign = get_exopite_sof_option( 'quotation-sign' );
			$stripe_payment_secret_key = $quotation_sign['stripe_payment_secret_key'];
			$stripe_payment_publishable_key = $quotation_sign['stripe_payment_publishable_key'];
		
			// Set up Stripe
			require_once( Quotation_sign_PATH . '/vendor/stripe/init.php' );
			\Stripe\Stripe::setApiKey($stripe_payment_secret_key);
		
			try {
				// Create a payment intent with the dynamic price
				$price = $form_data['amount'] * 100; // Stripe requires the amount to be in cents
				$payment_intent = \Stripe\PaymentIntent::create([
					'amount' => $price,
					'currency' => 'usd', // Set the appropriate currency code
					// Add any additional parameters as needed
				]);
		
				$submitted_data_page = get_page_by_path('submitted-data-page');
				$submitted_data_page_url = $submitted_data_page ? get_permalink($submitted_data_page->ID) : '';
				// Create sesson for the checkout session and redirect to checkout page with the session ID
				$checkout_session = \Stripe\Checkout\Session::create([
					'payment_method_types' => ['card'],
					'line_items' => [[
						'price_data' => [
							'currency' => 'usd',
							'unit_amount' => $price,
							'product_data' => [
								'name' => 'Quotation Sign',
								'description' => 'Quotation Sign',
							],
						],
						'quantity' => 1,
					]],
					'mode' => 'payment',
					'success_url' => $submitted_data_page_url . '&success=true&session_id={CHECKOUT_SESSION_ID}' . '&form_data=' . urlencode(base64_encode(serialize($form_data))), 
					'cancel_url' => $submitted_data_page_url . '&error=payment_cancelled'
				]);

				// var_dump($checkout_session);
				wp_redirect($checkout_session->url);
				exit;
				
			} catch (\Stripe\Exception\ApiErrorException $e) {
				// Handle any Stripe API errors
				echo 'Error: ' . $e->getMessage();
			}
		}		

	}

	public function quotation_sign_pay_success() {
		global $wpdb;
		
		// Call the function to store data
		if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['session_id'])) {
			$sessionID = $_GET['session_id'];

			$form_data = unserialize(base64_decode($_GET['form_data']));
			$form_data['session_id'] = $sessionID;

			// Store form_data as json into the database
			$table_name = $wpdb->prefix . 'quotation_sign_list';
			$wpdb->insert(
				$table_name,
				array(
					'value' => json_encode($form_data),
					'created_at' => current_time('mysql'),
				)
			);

			// Send email to admin and user
			$quotation_sign_admin_email = get_option('admin_email');
			$quotation_sign_admin_email_subject = 'Quotation Sign - New Submission';
			$quotation_sign_admin_email_body = 'A new submission has been made. Please check the admin panel for more details. You can also see the details about submission bellow:';
			$quotation_sign_admin_table = '<table style="width:50%;border:1px solid #000;border-collapse:collapse;">';
			$quotation_sign_admin_table .= '<tr><th style="border:1px solid #000;padding:5px;">Field</th><th style="border:1px solid #000;padding:5px;">Value</th></tr>';
			foreach ($form_data as $key => $value) {
				$key = str_replace('_', ' ', $key);
				$quotation_sign_admin_table .= '<tr><td style="border:1px solid #000;padding:5px;">' . $key . '</td><td style="border:1px solid #000;padding:5px;">' . $value . '</td></tr>';
			}
			$quotation_sign_admin_table .= '</table>';

			$quotation_sign_admin_email_body .= $quotation_sign_admin_table;

			// $quotation_sign_user_email = $form_data['email'];
			// $quotation_sign_user_email_subject = 'Quotation Sign - New Submission';
			// $quotation_sign_user_email_body = 'Thank you for your submission. You can see the details about submission bellow:';
			// $quotation_sign_user_table = '<table style="width:50%;border:1px solid #000;border-collapse:collapse;">';
			// $quotation_sign_user_table .= '<tr><th style="border:1px solid #000;padding:5px;">Field</th><th style="border:1px solid #000;padding:5px;">Value</th></tr>';
			// foreach ($form_data as $key => $value) {
			// 	$key = str_replace('_', ' ', $key);
			// 	$quotation_sign_user_table .= '<tr><td style="border:1px solid #000;padding:5px;">' . $key . '</td><td style="border:1px solid #000;padding:5px;">' . $value . '</td></tr>';
			// }
			// $quotation_sign_user_table .= '</table>';

			// $quotation_sign_user_email_body .= $quotation_sign_user_table;

			// Set the email headers
			$headers = array(
				"From: " . get_bloginfo( 'name' ) . " <" . get_bloginfo( 'admin_email' ) . ">",
				"Reply-To: " . get_bloginfo( 'admin_email' ),
				"Content-Type: text/html; charset=UTF-8; application/pdf",
			);

			// Send email admin and user
			$result = wp_mail( $quotation_sign_admin_email, $quotation_sign_admin_email_subject, $quotation_sign_admin_email_body, $headers );

			if ($result) {
				// echo 'Email sent successfully';
				return true;
			} else {
				// echo 'Email not sent';
				return false;
			}
		}
	}
	

}
