<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://giopio.com
 * 
 *
 * @package    Quotation_sign
 * @subpackage Quotation_sign/admin/partials
 */
global $wpdb;
$table_name = $wpdb->prefix . 'quotation_sign_list';
$quotation_sign_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="giopio-container">
   <div class="row">
      <section class="submissions">
         <div class="submisson-list"><h3>All Submission List</h3></div>
        <?php
        if ( !empty($quotation_sign_data) ) :
        foreach ( $quotation_sign_data as $key => $data ) : ?>
         <div class="submission" data-id="<?php echo $data->id; ?>" data-action="<?php echo admin_url('admin-ajax.php'); ?>">
            <div class="photo" style="background-image: url( <?php echo Quotation_sign_URL . 'assets/user.png'; ?> );">
            </div>
            <div class="desc-contact">
                <p class="name">
                    <?php echo json_decode($data->value, true) ['name']; ?>
                </p>
               <p class="message">Submitted Qoute Calculator Form?</p>
            </div>
            <div class="timer">
                <?php echo date('d M', strtotime($data->created_at)); ?>
            </div>
         </div>
        <?php 
        endforeach;
        endif;
        ?>
      </section>
      <section class="chat">
         <div class="header-chat">
            <i class="icon fa fa-user-o" aria-hidden="true"></i>
            <p class="name">Name Here</p>
         </div>
         <div class="giopio-listed-chat">
            No data found yet
         </div>
      </section>
   </div>
</div>