<?php
/**
 * Plugin Name: Ibovespa Quote
 * Plugin URI: https://sheilacarneiro.com.br
 * Description: Displays a graph with the Ibovespa quote using the HG Brasil API.
 * Version: 1.0
 * Author: Maria Sheila Carneiro
 * Text Domain: ibovespa-quote-plugin
 * Url: https://github.com/msheila1/
 */

// Prevents direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Enqueue scripts and styles
function ibovespa_enqueue_scripts() {

    // Style
    wp_enqueue_style( 'ibovespa-style', plugin_dir_url( __FILE__ ) . 'css/style.css' );

    // Scripts
    wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true );
    wp_enqueue_script( 'ibovespa-script', plugin_dir_url( __FILE__ ) . 'js/script.js', array('chart-js'), null, true );

    // Pass data to script.js
    wp_localize_script( 'ibovespa-script', 'ibovespaAjax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'api_key'  => '0d2fa930', // API Key
    ));
}
add_action( 'wp_enqueue_scripts', 'ibovespa_enqueue_scripts' );


// Function to fetch data from the HG Brasil API
function ibovespa_fetch_data() {
    // API Key
    $api_key = '0d2fa930'; 

    // API URL
    $api_url = "https://api.hgbrasil.com/finance?format=json&key=$api_key";

    // API request
    $response = wp_remote_get( $api_url );

    // Error request
    if ( is_wp_error( $response ) ) {
        wp_send_json_error( array( 'message' => 'Erro ao buscar os dados da API.' ) );
    }

    // Return data in JSON
    $body = wp_remote_retrieve_body( $response );
    wp_send_json_success( json_decode( $body ) );
}
add_action( 'wp_ajax_ibovespa_data', 'ibovespa_fetch_data' );
add_action( 'wp_ajax_nopriv_ibovespa_data', 'ibovespa_fetch_data' );

// Function to display the shortcode
function ibovespa_shortcode() {
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'templates/ibovespa-template.php';
    return ob_get_clean();
}
add_shortcode( 'ibovespa_quote', 'ibovespa_shortcode' );

// CORS Headers to Avoid Cross-Origin Issues
add_action( 'init', function() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
});
