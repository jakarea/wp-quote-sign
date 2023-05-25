<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://giopio.com
 * @since      1.0.0
 *
 * @package    Quotation Sign
 * @subpackage Quotation_sign/public/partials
 */

 // Detect language automatically.
$quotation_sign = get_exopite_sof_option( 'quotation-sign' );

if ( ! empty( $quotation_sign['language'] ) ) {
    $language = $quotation_sign['language'];
} else {
    $language = substr( get_locale(), 0, 2 );
}

// Get fields form values.
$form_fields = $quotation_sign['form_fields'];
?>
<div class="quotation-sign-public-display">
    <div class="quotation-sign-public-display__container">
        <div class="quotation-sign-public-display__container__header">
            <h2 class="quotation-sign-public-display__container__header__title">
                <?php echo esc_html__('Quotation Sign', 'quotation-sign') ; ?>
            </h2>
        </div>
        <div class="quotation-sign-public-display__container__form">
            <form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" class="quotation-sign-public-display__container__form__form">
                <?php
                foreach ( $form_fields as $field ) {
                    $field_name = $field['field_name'];
                    $field_placeholder = $field['field_placeholder'];
                    $field_required = $field['field_required'];
                    $field_type = $field['field_type'];
                    ?>
                    <div class="quotation-sign-public-display__container__form__form__field">
                        <label for="<?php echo $field_name; ?>" class="quotation-sign-public-display__container__form__form__field__label">
                            <?php echo $field_placeholder; ?>
                            <?php if ( $field_required === 'yes' ) { ?>
                                <span class="quotation-sign-public-display__container__form__form__field__label__required">*</span>
                            <?php } ?>
                        </label>
                        <?php if ( $field_type === 'textarea' ) { ?>
                            <textarea name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" class="quotation-sign-public-display__container__form__form__field__input" placeholder="<?php echo $field_placeholder; ?>" <?php if ( $field_required === 'yes' ) { ?>required<?php } ?>></textarea>
                        <?php } else { ?>
                            <input type="<?php echo $field_type; ?>" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" class="quotation-sign-public-display__container__form__form__field__input" placeholder="<?php echo $field_placeholder; ?>" <?php if ( $field_required === 'yes' ) { ?>required<?php } ?>>
                        <?php } ?>
                    </div>
                    <?php
                }
                ?>
                <div class="quotation-sign-public-display__container__form__form__field">
                    <input type="submit" name="my_form_submit" value="<?php echo esc_html__('Submit', 'quotation-sign') ; ?>" class="quotation-sign-public-display__container__form__form__field__submit">
                </div>
            </form>
        </div>
    </div>
</div>