<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link              https://giopio.com
 * @since             1.0.0
 *
 * @package     Quotation Sign
 * @subpackage  Quotation Sign/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the quotation sign, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quotation_sign
 * @subpackage Quotation_sign/admin
 * @author     Md Arif <arifypp@gmail.com>
 */
class Quotation_sign_Admin {

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
	 * @since    1.0.0
	 * @var object      The main class.
	 */
	public $main;
	// ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $quotation_sign       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	// public function __construct( $quotation_sign, $version ) {

	// 	$this->quotation_sign = $quotation_sign;
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

        add_shortcode( 'qoutation_sign_all_submission', array( $this, 'all_submission_shortcode' ) );
        add_action( 'wp_ajax_quotation_sign_single_list', array( $this, 'quotation_sign_single_list' ) );
        add_action( 'wp_ajax_nopriv_quotation_sign_single_list', array( $this, 'quotation_sign_single_list' ) );
    }
    // ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->quotation_sign, plugin_dir_url( __FILE__ ) . 'css/quotation-sign-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->quotation_sign, plugin_dir_url( __FILE__ ) . 'js/quotation-sign-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function get_all_emails() {

        $all_users = get_users();

        $user_email_list = array();

        foreach ($all_users as $user) {
            $user_email_list[esc_html($user->user_email)] = esc_html($user->display_name);
        }

        return $user_email_list;

    }

    public function test_sanitize_callback( $val ) {
        return str_replace ( 'a', 'b', $val );
    }

    // register shortcode to show all submission form data
    public function all_submission_shortcode() {
        ob_start();
        include_once( plugin_dir_path( __FILE__ ) . 'partials/quotation-sign-admin-display.php' );
        return ob_get_clean();
    }

    public function quotation_sign_single_list() {
        global $wpdb;

        $id = $_GET['id'];
        // send json success response

        // get the data based on id from wp_giopio_quote_calculator
        $table_name = $wpdb->prefix . 'quotation_sign_list';
        $result = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $id" );
        if( $result ) {
            wp_send_json_success( $result );
        }
        else {
            wp_send_json_error( 'No data found' );
        }        

        wp_die();

    }

    public function create_menu() {

        /**
         * Create a submenu page under Plugins.
         * Framework also add "Settings" to your plugin in plugins list.
         * @link https://github.com/JoeSz/Exopite-Simple-Options-Framework
         */
        $config_submenu = array(

            'type'              => 'menu',                          // Required, menu or metabox
            'id'                => $this->quotation_sign,              // Required, meta box id, unique per page, to save: get_option( id )
            'submenu'           => true,                            // Required for submenu
            'title'             => 'Quotation Sign',               // The title of the options page and the name in admin menu
            'capability'        => 'manage_options',                // The capability needed to view the page
            'plugin_basename'   =>  plugin_basename( plugin_dir_path( __DIR__ ) . $this->quotation_sign . '.php' ),
            // 'tabbed'            => false,
            // 'multilang'         => false,                        // To turn of multilang, default on.

        );

        /*
         * To add a metabox.
         * This normally go to your functions.php or another hook
         */
        $config_metabox = array(

            /*
             * METABOX
             */
            'type'              => 'metabox',                       // Required, menu or metabox
            'id'                => $this->quotation_sign,              // Required, meta box id, unique, for saving meta: id[field-id]
            'post_types'        => array( 'test' ),                 // Post types to display meta box
            // 'post_types'        => array( 'post', 'page' ),         // Could be multiple
            'context'           => 'advanced',                      // 	The context within the screen where the boxes should display: 'normal', 'side', and 'advanced'.
            'priority'          => 'default',                       // 	The priority within the context where the boxes should show ('high', 'low').
            'title'             => 'Demo Metabox',                  // The title of the metabox
            'capability'        => 'edit_posts',                    // The capability needed to view the page
            'tabbed'            => true,
            // 'multilang'         => false,                        // To turn of multilang, default off except if you have qTransalte-X.
            'options'           => 'simple',                        // Only for metabox, options is stored az induvidual meta key, value pair.
            /**
             * Simple options is stored az induvidual meta key, value pair, otherwise it is stored in an array.
             *
             * I implemented this option because it is possible to search in serialized (array) post meta:
             * @link https://wordpress.stackexchange.com/questions/16709/meta-query-with-meta-values-as-serialize-arrays
             * @link https://stackoverflow.com/questions/15056407/wordpress-search-serialized-meta-data-with-custom-query
             * @link https://www.simonbattersby.com/blog/2013/03/querying-wordpress-serialized-custom-post-data/
             *
             * but there is no way to sort them with wp_query or SQL.
             * @link https://wordpress.stackexchange.com/questions/87265/order-by-meta-value-serialized-array/87268#87268
             * "Not in any reliable way. You can certainly ORDER BY that value but the sorting will use the whole serialized string,
             * which will give * you technically accurate results but not the results you want. You can't extract part of the string
             * for sorting within the query itself. Even if you wrote raw SQL, which would give you access to database functions like
             * SUBSTRING, I can't think of a dependable way to do it. You'd need a MySQL function that would unserialize the value--
             * you'd have to write it yourself.
             * Basically, if you need to sort on a meta_value you can't store it serialized. Sorry."
             *
             * It is possible to get all required posts and store them in an array and then sort them as an array,
             * but what if you want multiple keys/value pair to be sorted?
             *
             * UPDATE
             * it is maybe possible:
             * @link http://www.russellengland.com/2012/07/how-to-unserialize-data-using-mysql.html
             * but it is waaay more complicated and less documented as meta query sort and search.
             * It should be not an excuse to use it, but it is not as reliable as it should be.
             *
             * @link https://wpquestions.com/Order_by_meta_key_where_value_is_serialized/7908
             * "...meta info serialized is not a good idea. But you really are going to lose the ability to query your
             * data in any efficient manner when serializing entries into the WP database.
             *
             * The overall performance saving and gain you think you are achieving by serialization is not going to be noticeable to
             * any major extent. You might obtain a slightly smaller database size but the cost of SQL transactions is going to be
             * heavy if you ever query those fields and try to compare them in any useful, meaningful manner.
             *
             * Instead, save serialization for data that you do not intend to query in that nature, but instead would only access in
             * a passive fashion by the direct WP API call get_post_meta() - from that function you can unpack a serialized entry
             * to access its array properties too."
             */

        );

        /**
         * All Field for admin
        */

        $fields[] = array(
            'name'   => 'basic',
            'title'  => 'Dashboard',
            'icon'   => 'dashicons-admin-generic',
            'fields' => array(

                array(
                    'type'    => 'card',
                    'class'   => 'class-name', // for all fieds
                    'content' => '<p> <code>[quotation-sign-form]</code> This is Here is a short description of the plugin.  This should be no more than 150 characters.  No markup here.</p>',
                    'header' => 'Quotation Sign',
                    'footer' => 'Footer Text',
                ),
            )
        );
        // Square meter price
        $fields[] = array(
            'name'   => 'square_meter_price',
            'title'  => 'Square Meter Price',
            'icon'   => 'fa fa-usd',
            'fields' => array(

                array(
                    'type'    => 'textarea',
                    'id'      => 'square_meter_price',
                    'title'   => esc_html__( 'Square Meter Price $', 'quotation-sign' ),
                    'description'    => esc_html__( 'Enter the square meter price. This price is for per square meter price', 'quotation-sign' ),
                    'default' => '0',
                    'attributes'    => array(
                        'placeholder' => '0',
                    ),
                ),

                array(
                    'type'    => 'textarea',
                    'id'      => 'square_meter_price_description',
                    'title'   => esc_html__( 'Square Meter Price Description', 'quotation-sign' ),
                    'description'    => esc_html__( 'Enter the square meter price description. This description is for per square meter price', 'quotation-sign' ),
                    'attributes'    => array(
                        'placeholder' => 'Enter the square meter price description. This description is for per square meter price',
                    ),
                )

            )
        );

        // Form fields [name, email, phone, number of square meters] use repeater and that form i will display frontend
        $fields[] = array(
            'name'   => 'form_fields',
            'title'  => 'Form Fields',
            'icon'   => 'fa fa-window-maximize',
            'fields' => array(

                array(
                    'type'    => 'group',
                    'id'      => 'form_fields',
                    'title'   => esc_html__( 'Form Fields', 'quotation-sign' ),
                    'options' => array(
                        'repeater'          => true,
                        'accordion'         => true,
                        'button_title'      => esc_html__( 'Add new field', 'quotation-sign' ),
                        'group_title'       => esc_html__( 'Field', 'quotation-sign' ),
                        'remove_confirm'    => esc_html__( 'Are you sure you want to remove?', 'quotation-sign' ),
                        'sortable'          => true,
                        'limit'             => 10,
                    ),
                    'fields' => array(

                        array(
                            'type'    => 'text',
                            'id'      => 'field_name',
                            'title'   => esc_html__( 'Field Name', 'quotation-sign' ),
                            'desc'    => esc_html__( 'Enter the field name', 'quotation-sign' ),
                            'default' => '',
                            'attributes'    => array(
                                'placeholder' => esc_html__( 'Field Name', 'quotation-sign' ),
                            ),
                        ),

                        array(
                            'type'    => 'text',
                            'id'      => 'field_placeholder',
                            'title'   => esc_html__( 'Field Placeholder', 'quotation-sign' ),
                            'desc'    => esc_html__( 'Enter the field placeholder', 'quotation-sign' ),
                            'default' => '',
                            'attributes'    => array(
                                'placeholder' => esc_html__( 'Field Placeholder', 'quotation-sign' ),
                            ),
                        ),

                        array(
                            'type'    => 'switcher',
                            'id'      => 'field_required',
                            'title'   => esc_html__( 'Field Required', 'quotation-sign' ),
                            'desc'    => esc_html__( 'Select the field required', 'quotation-sign' ),
                            'default' => false,
                        ),

                        array(
                            'type'    => 'select',
                            'id'      => 'field_type',
                            'title'   => esc_html__( 'Field Type', 'quotation-sign' ),
                            'desc'    => esc_html__( 'Select the field type', 'quotation-sign' ),
                            'default' => 'text',
                            'options' => array(
                                'text' => esc_html__( 'Text', 'quotation-sign' ),
                                'email' => esc_html__( 'Email', 'quotation-sign' ),
                                'number' => esc_html__( 'Number', 'quotation-sign' ),
                                'textarea' => esc_html__( 'Textarea', 'quotation-sign' ),
                            ),
                        ),

                    ),
                ),

            )
        );

        // Stripe Payment Configuration Fields information
        $fields[] = array(
            'name'   => 'stripe_payment',
            'title'  => 'Stripe Payment',
            'icon'   => 'fa fa-credit-card',
            'fields' => array(

                array(
                    'type'    => 'textarea',
                    'id'      => 'stripe_payment_secret_key',
                    'title'   => esc_html__( 'Stripe Payment Secret Key', 'quotation-sign' ),
                    'desc'    => esc_html__( 'Enter the stripe payment secret key', 'quotation-sign' ),
                    'default' => '',
                    'attributes'    => array(
                        'placeholder' => esc_html__( 'Stripe Payment Secret Key', 'quotation-sign' ),
                    ),
                ),

                array(
                    'type'    => 'textarea',
                    'id'      => 'stripe_payment_publishable_key',
                    'title'   => esc_html__( 'Stripe Payment Publishable Key', 'quotation-sign' ),
                    'desc'    => esc_html__( 'Enter the stripe payment publishable key', 'quotation-sign' ),
                    'default' => '',
                    'attributes'    => array(
                        'placeholder' => esc_html__( 'Stripe Payment Publishable Key', 'quotation-sign' ),
                    ),
                ),

            )
        );

        // panel title
        $fields[] = array(
            'title'   => esc_html__( 'All Submission', 'exopite-combiner-minifier' ),
            'sections' => array(),
            'icon'  => '',
        );

        // All Submission show from shortcode
        $fields[] = array(
            'name'  => 'all_submission',
            'title' => esc_html__('All Submission', 'quotation-sign'),
            'description'  => esc_html__('All Submission', 'quotation-sign'),
            'icon'  => 'fa fa-window-maximize',
            'fields'    =>  array(
                array(
                    'id'    => 'all_submission_page',
                    'type'  => 'content', // Use the 'page_select' field type
                    'desc'  => esc_html__( 'Select the page where you want to show all submission.', 'quotation-sign' ),
                    'content'   => do_shortcode( "[qoutation_sign_all_submission]" ),
                )
            )
        );

        $fields[] = array(
            'name'   => 'backup',
            'title'  => 'Backup',
            'icon'   => 'dashicons-backup',
            'fields' => array(

                array(
                    'type'    => 'backup',
                    'title'   => esc_html__( 'Backup', 'exopite-seo-core' ),
                ),


            )
        );

        /**
         * instantiate your admin page
         */
        $options_panel = new Exopite_Simple_Options_Framework( $config_submenu, $fields );
        $options_panel = new Exopite_Simple_Options_Framework( $config_metabox, $fields );

    }

    /**
     * Add new image size for admin thumbnail.
     *
     * @link https://wordpress.stackexchange.com/questions/54423/add-image-size-in-a-plugin-i-created/304941#304941
     */
    public function add_thumbnail_size() {
        add_image_size( 'new_thumbnail_size', 60, 75, true );
    }

    /**************************************************************
     * ADD/REMOVE/REORDER/SORT CUSTOM POST TYPE LIST COLUMNS (test)
     *
     * @tutorial add_remove_reorder_sort_custom_post_type_list_columns_in_admin_area.php
     */
    /**
     * Modify columns in tests list in admin area.
     */
    public function manage_test_posts_columns( $columns ) {

        // Remove unnecessary columns
        unset(
            $columns['author'],
            $columns['comments']
        );

        // Rename title and add ID and Address
        $columns['thumbnail'] = '';
        $columns['text_1'] = esc_attr__( 'Text', 'quotation-sign' );
        $columns['color_2'] = esc_attr__( 'Color', 'quotation-sign' );
        $columns['date_2'] = esc_attr__( 'Date', 'quotation-sign' );


        /**
         * Rearrange column order
         *
         * Now define a new order. you need to look up the column
         * names in the HTML of the admin interface HTML of the table header.
         *
         *     "cb" is the "select all" checkbox.
         *     "title" is the title column.
         *     "date" is the date column.
         *     "icl_translations" comes from a plugin (eg.: WPML).
         *
         * change the order of the names to change the order of the columns.
         *
         * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
         */
        $customOrder = array('cb', 'thumbnail', 'title', 'text_1', 'color_2', 'date_2', 'icl_translations', 'date');

        /**
         * return a new column array to wordpress.
         * order is the exactly like you set in $customOrder.
         */
        foreach ($customOrder as $column_name)
            $rearranged[$column_name] = $columns[$column_name];

        return $rearranged;

    }

    // Populate new columns in customers list in admin area
    public function manage_posts_custom_column( $column, $post_id ) {

        // For array, not simple options
        // global $post;
        // $custom = get_post_custom();
        // $meta = maybe_unserialize( $custom[$this->Quotation_sign][0] );

        // Populate column form meta
        switch ($column) {

            case "thumbnail":
                echo '<a href="' . get_edit_post_link() . '">';
                echo get_the_post_thumbnail( $post_id, array( 60, 60 ) );
                echo '</a>';
                break;
            case "text_1":
                // no break;
            case "color_2":
                // no break;
            case "date_2":
                echo get_post_meta( $post_id, $column, true );
                break;
            // case "some_column":
            //     // For array, not simple options
            //     echo $meta["some_column"];
            //     break;

        }

    }

    public function add_style_to_admin_head() {
        global $post_type;
        if ( 'test' == $post_type ) {
            ?>
                <style type="text/css">
                    .column-thumbnail {
                        width: 80px !important;
                    }
                    .column-title {
                        width: 30% !important;
                    }
                </style>
            <?php
        }
    }

    /**
     * To sort, Exopite Simple Options Framework need 'options' => 'simple'.
     * Simple options is stored az induvidual meta key, value pair, otherwise it is stored in an array.
     *
     *
     * Meta key value paars need to sort as induvidual.
     *
     * I implemented this option because it is possible to search in serialized (array) post meta:
     * @link https://wordpress.stackexchange.com/questions/16709/meta-query-with-meta-values-as-serialize-arrays
     * @link https://stackoverflow.com/questions/15056407/wordpress-search-serialized-meta-data-with-custom-query
     * @link https://www.simonbattersby.com/blog/2013/03/querying-wordpress-serialized-custom-post-data/
     *
     * but there is no way to sort them with wp_query or SQL.
     * @link https://wordpress.stackexchange.com/questions/87265/order-by-meta-value-serialized-array/87268#87268
     * "Not in any reliable way. You can certainly ORDER BY that value but the sorting will use the whole serialized string,
     * which will give * you technically accurate results but not the results you want. You can't extract part of the string
     * for sorting within the query itself. Even if you wrote raw SQL, which would give you access to database functions like
     * SUBSTRING, I can't think of a dependable way to do it. You'd need a MySQL function that would unserialize the value--
     * you'd have to write it yourself.
     * Basically, if you need to sort on a meta_value you can't store it serialized. Sorry."
     *
     * It is possible to get all required posts and store them in an array and then sort them as an array,
     * but what if you want multiple keys/value pair to be sorted?
     *
     * UPDATE
     * it is maybe possible:
     * @link http://www.russellengland.com/2012/07/how-to-unserialize-data-using-mysql.html
     * but it is waaay more complicated and less documented as meta query sort and search.
     * It should be not an excuse to use it, but it is not as reliable as it should be.
     *
     * @link https://wpquestions.com/Order_by_meta_key_where_value_is_serialized/7908
     * "...meta info serialized is not a good idea. But you really are going to lose the ability to query your
     * data in any efficient manner when serializing entries into the WP database.
     *
     * The overall performance saving and gain you think you are achieving by serialization is not going to be noticeable to
     * any major extent. You might obtain a slightly smaller database size but the cost of SQL transactions is going to be
     * heavy if you ever query those fields and try to compare them in any useful, meaningful manner.
     *
     * Instead, save serialization for data that you do not intend to query in that nature, but instead would only access in
     * a passive fashion by the direct WP API call get_post_meta() - from that function you can unpack a serialized entry
     * to access its array properties too."
     */
    public function manage_sortable_columns( $columns ) {

        $columns['text_1'] = 'text_1';
        $columns['color_2'] = 'color_2';
        $columns['date_2'] = 'date_2';

        return $columns;

    }

    public function manage_posts_orderby( $query ) {

        if( ! is_admin() || ! $query->is_main_query() ) {
            return;
        }

        /**
         * meta_types:
         * Possible values are 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'.
         * Default value is 'CHAR'.
         *
         * @link https://codex.wordpress.org/Class_Reference/WP_Meta_Query
         */
        $columns = array(
            'text_1'  => 'char',
            'color_2' => 'char',
            'date_2'  => 'date',
        );

        foreach ( $columns as $key => $type ) {

            if ( $key === $query->get( 'orderby') ) {
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', $key );
                $query->set( 'meta_type', $type );
                break;
            }

        }

    }
    // END ADD/REMOVE/REORDER/SORT CUSTOM POST TYPE LIST COLUMNS (test)

    /********************************************
     * RUN CODE ON PLUGIN UPGRADE AND ADMIN NOTICE
     *
     * @tutorial run_code_on_plugin_upgrade_and_admin_notice.php
     */
    /**
     * This function runs when WordPress completes its upgrade process
     * It iterates through each plugin updated to see if ours is included
     *
     * @param $upgrader_object Array
     * @param $options Array
     * @link https://catapultthemes.com/wordpress-plugin-update-hook-upgrader_process_complete/
     */
    public function upgrader_process_complete( $upgrader_object, $options ) {

        // If an update has taken place and the updated type is plugins and the plugins element exists
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {

            // Iterate through the plugins being updated and check if ours is there
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == Quotation_sign_BASE_NAME ) {

                    // Set a transient to record that our plugin has just been updated
                    set_transient( 'exopite_sof_updated', 1 );
                    set_transient( 'exopite_sof_updated_message', esc_html__( 'Thanks for updating', 'exopite_sof' ) );

                }
            }
        }
    }

    /**
     * Show a notice to anyone who has just updated this plugin
     * This notice shouldn't display to anyone who has just installed the plugin for the first time
     */
    public function display_update_notice() {
        // Check the transient to see if we've just activated the plugin
        if( get_transient( 'exopite_sof_updated' ) ) {

            // @link https://digwp.com/2016/05/wordpress-admin-notices/
            echo '<div class="notice notice-success is-dismissible"><p><strong>' . get_transient( 'exopite_sof_updated_message' ) . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

            // Delete the transient so we don't keep displaying the activation message
            delete_transient( 'exopite_sof_updated' );
            delete_transient( 'exopite_sof_updated_message' );
        }
    }
    // RUN CODE ON PLUGIN UPGRADE AND ADMIN NOTICE

    // SIMPLE ADMIN NOTE
    /**
     * @link https://developer.wordpress.org/reference/hooks/admin_notices/#comment-3462
     */
    public function test_plugin_admin_notice() {

    //get the current screen
    $screen = get_current_screen();

        //return if not plugin settings page
        //To get the exact your screen ID just do var_dump($screen)
        // $this->quotation_sign
        if ( $screen->id !== 'toplevel_page_YOUR_PLUGIN_PAGE_SLUG' ) return;

        //Checks if settings updated
        if ( isset( $_GET['settings-updated'] ) ) {
            //if settings updated successfully
            if ( 'true' === $_GET['settings-updated'] ) : ?>

                <div class="notice notice-success is-dismissible">
                    <p><?php _e('Congratulations! You did a very good job.', 'textdomain') ?></p>
                </div>

            <?php else : ?>

                <div class="notice notice-warning is-dismissible">
                    <p><?php _e('Sorry, I can not go through this.', 'textdomain') ?></p>
                </div>

            <?php endif;
        }

    }



}
