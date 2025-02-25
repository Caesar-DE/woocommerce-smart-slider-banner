<?php
/**
 * Plugin Name: WooCommerce Smart Slider Banner
 * Plugin URI: https://github.com/
 * Description: Fügt automatisch einen Smart Slider 3 Banner auf der WooCommerce-Shopseite ein.
 * Version: 1.0.0
 * Author: Mo Zaidan
 * Author URI: https://caesar-de.de/
 * License: GPL-2.0+
 * Text Domain: woocommerce-smart-slider-banner
 */

if ( ! defined( 'ABSPATH' ) ) 
{
    exit; // Sicherheit: Verhindert direkten Zugriff
}

// Standard-Shortcode setzen, falls noch nicht gespeichert
function wssb_set_default_shortcode() 
{
    if ( get_option( 'wssb_smartslider_shortcode' ) === false ) 
    {
        update_option( 'wssb_smartslider_shortcode', '[smartslider3 slider="1"]' );
    }
}
register_activation_hook( __FILE__, 'wssb_set_default_shortcode' );

// Funktion zum Einfügen des Sliders
function wssb_add_smartslider_to_shop_page() 
{
    if ( is_shop() ) 
    {
        $slider_shortcode = get_option( 'wssb_smartslider_shortcode', '[smartslider3 slider="1"]' );
        echo do_shortcode( $slider_shortcode );
    }
}
add_action( 'woocommerce_before_main_content', 'wssb_add_smartslider_to_shop_page', 15 );

// Menüpunkt für die Einstellungen hinzufügen
function wssb_add_admin_menu() 
{
    add_options_page(
        'WooCommerce Smart Slider Banner',
        'Smart Slider Banner',
        'manage_options',
        'woocommerce-smart-slider-banner',
        'wssb_settings_page'
    );
}
add_action( 'admin_menu', 'wssb_add_admin_menu' );

// Einstellungsseite für den Shortcode
function wssb_settings_page() 
{
    ?>
    <div class="wrap">
        <h1>WooCommerce Smart Slider Banner Einstellungen</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'wssb_settings_group' );
            do_settings_sections( 'woocommerce-smart-slider-banner' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Einstellungsfelder registrieren
function wssb_register_settings() 
{
    register_setting( 'wssb_settings_group', 'wssb_smartslider_shortcode' );

    add_settings_section(
        'wssb_main_section',
        'Shortcode-Einstellungen',
        'wssb_section_text',
        'woocommerce-smart-slider-banner'
    );

    add_settings_field(
        'wssb_smartslider_shortcode',
        'Smart Slider Shortcode',
        'wssb_shortcode_input_field',
        'woocommerce-smart-slider-banner',
        'wssb_main_section'
    );
}
add_action( 'admin_init', 'wssb_register_settings' );

// Beschreibung für den Abschnitt
function wssb_section_text() 
{
    echo '<p>Hier kannst du den Smart Slider Shortcode anpassen.</p>';
}

// Eingabefeld für den Shortcode
function wssb_shortcode_input_field() 
{
    $shortcode = get_option( 'wssb_smartslider_shortcode', '[smartslider3 slider="1"]' );
    echo '<input type="text" name="wssb_smartslider_shortcode" value="' . esc_attr( $shortcode ) . '" style="width: 100%;">';
}
