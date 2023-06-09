<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://giopio.com
 *
 * @package    Quotation Sign
 * @subpackage Quotation_sign/public/partials
 */

// Get submitted form data from url parameter

$quotation_sign = get_exopite_sof_option( 'quotation-sign' );

if ( ! empty( $quotation_sign['language'] ) ) {
    $language = $quotation_sign['language'];
} else {
    $language = substr( get_locale(), 0, 2 );
}
$square_meter_price = $quotation_sign['square_meter_price'];
$square_meter_price_description = $quotation_sign['square_meter_price_description'];
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="quotation-sign-public-display">
    <div class="quotation-sign-public-display__container">
        <div class="quotation-sign-public-display__container__header">
            <h2 class="quotation-sign-public-display__container__header__title">
                <?php echo esc_html__('Quotation Info', 'quotation-sign') ; ?>
            </h2>
        </div>
        <?php
        if ( !empty( $qs_form_data ) ) { ?>
        <div class="quotation-sign-public-display__container__table">
            <?php
            if ( isset( $_GET['success'] ) && $_GET['success'] == 'true' ) {
                echo '<div class="alert alert-success" role="alert">' . esc_html__('Thank you for your submission. We will contact you soon.', 'quotation-sign') . '</div>';
            } ?>
            <table>
                <tr>
                    <th><?php echo esc_html__('Name', 'quotation-sign') ; ?></th>
                    <td><?php echo esc_html($qs_form_data['name']) ; ?></td>
                </tr>
                <tr>
                    <th><?php echo esc_html__('Email', 'quotation-sign') ; ?></th>
                    <td><?php echo esc_html($qs_form_data['email']) ; ?></td>
                </tr>
                <tr>
                    <th><?php echo esc_html__('Phone', 'quotation-sign') ; ?></th>
                    <td><?php echo esc_html($qs_form_data['phone']) ; ?></td>
                </tr>
                <tr>
                    <th><?php echo esc_html__('Square Meters', 'quotation-sign') ; ?></th>
                    <td><?php echo esc_html($qs_form_data['square_meters']) ; ?></td>
                </tr>
            </table>
            <div class="quotation-sign-public-display__container__table__price">
                <span class="quotation-sign-public-display__container__table__price__label"><?php echo esc_html__('Price ', 'quotation-sign'); echo $qs_form_data['square_meters'] .'×'. $square_meter_price; ?> :</span>
                <span class="quotation-sign-public-display__container__table__price__value"><?php echo esc_html('€' . $qs_form_data['square_meters'] * $square_meter_price) ; ?></span>
            </div>
            <!-- Add Digital Signature and Pay Button -->
            <?php
            if ( !isset( $_GET['success'] ) || $_GET['success'] !== 'true' ) {?>
            <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" id="quotation-sign-form">
                <div class="quotation-sign-public-display__container__table__signature">
                    <div class="quotation-sign-public-display__container__table__signature__label">
                        <?php echo esc_html__('Digital Signature', 'quotation-sign') ; ?>
                    </div>
                    <div class="quotation-sign-public-display__container__table__signature__canvas">
                        <canvas id="signature-pad" class="signature-pad" width="450" height="200" style="background:#fff; border: 1px solid #ddd;"></canvas>
                        <input type="hidden" name="signature" id="signature" value="">
                    </div>
                    <div class="quotation-sign-public-display__container__table__signature__buttons">
                        <button id="clear_signature" class="button button-primary"><?php echo esc_html__('Clear', 'quotation-sign') ; ?></button>
                        <button id="save_signature" class="button button-primary"><?php echo esc_html__('Save', 'quotation-sign') ; ?></button>
                    </div>
                </div>
                <!-- Info [for order confirmation please pay at least 20%] -->
                <div class="quotation-sign-public-display__container__table__info">
                    <span class="quotation-sign-public-display__container__table__info__value"><?php echo esc_html__($square_meter_price_description, 'quotation-sign') ; ?></span>
                </div>
                <div class="quotation-sign-public-display__container__table__pay">
                    <input type="hidden" name="quotation_sign_pay" value="quotation_sign_pay">
                    <input type="hidden" name="qs_name" value="<?php echo htmlspecialchars($qs_form_data['name']); ?>">
                    <input type="hidden" name="qs_email" value="<?php echo htmlspecialchars($qs_form_data['email']) ; ?>">
                    <input type="hidden" name="qs_phone" value="<?php echo htmlspecialchars($qs_form_data['phone']) ; ?>">
                    <input type="hidden" name="qs_square_meters" value="<?php echo htmlspecialchars($qs_form_data['square_meters']) ; ?>">
                    <input type="hidden" name="amount" value="<?php echo esc_html__($qs_form_data['square_meters'] * $square_meter_price / 100 * 20, 'quotation-sign') ; ?>">
                    <input type="hidden" name="dueamount" value="<?php echo esc_html($qs_form_data['square_meters'] * $square_meter_price) - $qs_form_data['square_meters'] * $square_meter_price / 100 * 20; ?>">                    
                    <button type="submit" id="pay" class="button button-primary"><?php echo esc_html__('Pay €'. $qs_form_data['square_meters'] * $square_meter_price / 100 * 20 . ' (20%)', 'quotation-sign') ; ?></button>                                              
                </div>
                <?php } ?> 
            </form>
        </div>
        <?php } else { ?>
        <div class="quotation-sign-public-display__container__table">
            <div class="alert alert-danger" role="alert"><?php echo esc_html__('No data found.', 'quotation-sign') ; ?></div>
        </div>
        <?php } ?>
    </div>
</div>