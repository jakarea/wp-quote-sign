<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://giopio.com
 * @since      1.0.0
 *
 * @package    Quotation_sign
 * @subpackage Quotation_sign/admin/partials
 */
global $wpdb;
$table_name = $wpdb->prefix . 'quotation_sign_list';
$quotation_sign_data = $wpdb->get_results("SELECT * FROM $table_name");

// foreach ($quotation_sign_data as $quotation_sign) {
//     $data = json_decode($quotation_sign->value);
//     echo $data->name;
// }
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="giopio-container">
   <div class="row">
      <section class="submissions">

         <div class="submisson-list"><h3>All Submission List</h3></div>

         <!-- <div class="submission message-active">
            <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
            </div>
            <div class="desc-contact">
               <p class="name">Megan Leib</p>
               <p class="message">9 pm at the bar if possible 😳</p>
            </div>
            <div class="timer">12 sec</div>
         </div> -->
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