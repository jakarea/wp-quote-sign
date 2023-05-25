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
		// display_session_data
		add_shortcode( 'submitted-data-page', array( $this, 'display_session_data' ) );
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
			// Handle form submission and store data in session
			session_start();

			// Retrieve form data
			$name = sanitize_text_field($_POST['name']);
			$email = sanitize_email($_POST['email']);
			$phone = sanitize_text_field($_POST['phone']);
			$square_meters = intval($_POST['square_meters']);

			// Store form data in session
			$_SESSION['form_data'] = array(
				'name' => $name,
				'email' => $email,
				'phone' => $phone,
				'square_meters' => $square_meters
			);

			// var_dump($_SESSION['form_data']);
			// exit;

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

			// Redirect to the page showing submitted data
			wp_redirect(get_permalink($submitted_data_page_id));
			exit;
		}

	}


	function display_session_data($atts) {
		ob_start();

		include_once( plugin_dir_path( __FILE__ ) . 'partials/submission-data.php' );

		return ob_get_clean();

	}

	/**
	 * Submit form quotation_sign_pay
	 */
	public function quotation_sign_pay() {
		// Check if the form has been submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quotation_sign_pay'])) {
			// Handle form submission and update data in session form_data
			session_start();
		
			// Add signature to session form_data
			$_SESSION['form_data']['signature'] = $_POST['signature'];
			$_SESSION['form_data']['amount'] = $_POST['amount'];
			$_SESSION['form_data']['dueamount'] = $_POST['dueamount'];
			// $_SESSION['form_data']['signature_img'] = $_POST['signature'];
		
			// Set up Stripe
			require_once( Quotation_sign_PATH . '/vendor/stripe/init.php' );
			\Stripe\Stripe::setApiKey('sk_test_51MyRmPIPOd0zPaLLVoU39SY8hJKkKLWSXU4y8bule6fXQuzRtInpIbdJqD4CPxvPOkzhiRefwgDy1UgEInscPT1100cKRkHxeu');
		
			try {
				// Create a payment intent with the dynamic price
				$price = $_SESSION['form_data']['amount'] * 100; // Stripe requires the amount in cents
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
					'success_url' => $submitted_data_page_url . '&success=true&session_id={CHECKOUT_SESSION_ID}',
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

	public function quotation_sign_pay_success($sessionID) {
		global $wpdb;
		
		// Call the function to store data
		if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['session_id'])) {
			$sessionID = $_GET['session_id'];

			if (!session_id()) {
				session_start();
			}
			
			// Check if $_SESSION['form_data'] is set
			if (isset($_SESSION['form_data'])) {
				$form_data = $_SESSION['form_data'];
				$form_data['session_id'] = $sessionID;


				// get only the signature and upload into media library and set signature as the url
				$signature = $form_data['signature'];

				if ($signature) {
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

				// Store form_data as json into the database
				$table_name = $wpdb->prefix . 'quotation_sign_list';
				$wpdb->insert(
					$table_name,
					array(
						'value' => json_encode($form_data),
						'created_at' => current_time('mysql'),
					)
				);

			}

			// Unset $_SESSION['form_data']
			// unset($_SESSION['form_data']);
		}
	}
	

}
