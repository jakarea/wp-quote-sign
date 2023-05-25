<?php
/**
 * CONF Quotation Sign.
 *
 * @package   Quotation_sign_AJAX
 * @author    Md Arif
 * @license   GPL-2.0+
 * @link      CONF_Author_Link
 * @copyright CONF_Plugin_Copyright
 */

class Quotation_sign_DATABASE
{
    /**
     * The name of the table.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $table_name    The name of the table.
     */
    private $table_name;

     /**
     * The name of the table.
     */
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'quotation_sign_list';

        $this->create_table();
    }

    /**
     * Create table
     *
     * @since    1.0.0
     */
    public function create_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            value text NOT NULL,
            created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Drop table
     *
     * @since    1.0.0
     */
    public function drop_table()
    {
        global $wpdb;
        $sql = "DROP TABLE IF EXISTS $this->table_name";
        $wpdb->query($sql);
    }

}
