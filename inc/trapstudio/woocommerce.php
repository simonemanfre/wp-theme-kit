<?php

//SUPPORTO WOOCOMMERCE
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

//NUMERO PRODOTTI MOSTRATI (-1 per mostrare tutti i prodotti)
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
$cols = -1;
return $cols;
}

//WOOCOMMERCE CHECKBOX PRIVACY
add_action( 'woocommerce_review_order_before_submit', 'add_privacy_checkbox', 9 );

function add_privacy_checkbox() {
    $privacy_policy_page = get_option('wp_page_for_privacy_policy');
    if(function_exists('icl_object_id')) {
        $privacy_policy_page = icl_object_id($privacy_policy_page, 'page', true);
    }
    $privacy = get_permalink($privacy_policy_page);
    $link = '<a target="_blank" href="'.$privacy.'">Privacy Policy</a>';
            
    woocommerce_form_field( 'privacy_policy', array(
    'type' => 'checkbox',
    'class' => array('form-row privacy'),
    'label_class' => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
    'input_class' => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
    'required' => true,
    'label' => sprintf( __('Ho letto ed accetto la %s', 'beelight'), $link )
    ));
}

add_action( 'woocommerce_checkout_process', 'privacy_checkbox_error_message' );

function privacy_checkbox_error_message() {
    if ( ! (int) isset( $_POST['privacy_policy'] ) ) {
        wc_add_notice( __('Devi accettare la nostra privacy policy per proseguire'), 'error');
    }
}

?>