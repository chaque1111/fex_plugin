<?php
/*
Plugin Name: Fex Plugin
Plugin URI: https://holocruxe.com
Description: Integración Fex WooCommerce. Una solución completamente funcional que integra las API de FEx, capturando y procesando los hooks y eventos requeridos de Woocommerce.
Version: 1.0
Author: Holocruxe Factory
Author URI: https://www.linkedin.com/company/holocruxe
License: GPL2
*/

register_activation_hook(__FILE__, 'fex_add_menu');

// function adjust_shipping_rate($rates)
// {
//     global $woocommerce;
//     foreach ($rates as $rate) {
//         $cost = $rate->cost;
//         $rate->cost = 50;
//     }
//     return $rates;
// }
// add_filter('woocommerce_package_rates', 'adjust_shipping_rate', 50, 1);


//Agregar métodos de envío
include_once plugin_dir_path(__FILE__) . '/shipping/shipping-methods.php';

//Agregar modal
include_once plugin_dir_path(__FILE__) . '/modals/modals.php';

//Agregando funciones de ajax

include_once plugin_dir_path(__FILE__) . '/functions-ajax/post.php';

//new order express
include_once plugin_dir_path(__FILE__) . '/new-order/new-order-express.php';
//new order programado
include_once plugin_dir_path(__FILE__) . '/new-order/new-order-programado.php';

function adjust_shipping_rate($rates)
{
    global $woocommerce;
    foreach ($rates as $rate) {
        if ($rate->method_id === "fex_express_shipping_method" || $rate->method_id === "fex_programado_shipping_method") {
            $cost = $rate->cost;
            $rate->cost = $_COOKIE['shipping_city_cost'];
        }
    }
    return $rates;
}
add_filter('woocommerce_package_rates', 'adjust_shipping_rate', 50, 1);

function fex_add_menu()
{
    add_menu_page(
        'Fex',
        'Inicio',
        'manage_options',
        'fex_menu',
        'fex_show_landing',
        plugin_dir_url(__FILE__) . 'assets/img/fexLogo.png',
        1
    );

    add_submenu_page(
        'fex_menu',
        'Zona de envíos',
        'Zona de envíos',
        'manage_options',
        'shipping_zones',
        'fex_show_shipping_zones'
    );

    add_submenu_page(
        'fex_menu',
        'Dashboard',
        'Panel de control',
        'manage_options',
        'submenu_dashboard',
        'fex_show_dashboard'
    );

    add_submenu_page(
        'fex_menu',
        'Ajustes',
        'Ajustes',
        'manage_options',
        'submenu_settings',
        'fex_show_settings'
    );
}
add_action('admin_menu', 'fex_add_menu');

// Mostrar página de inicio
function fex_show_landing()
{
    include_once plugin_dir_path(__FILE__) . '/components/landing/landing.php';
}

// Mostrar página de Zona de envíos
function fex_show_shipping_zones()
{
    include_once plugin_dir_path(__FILE__) . '/components/shipping-zones/shipping-zones.php';
}

// Mostrar página de Panel de control
function fex_show_dashboard()
{
    include_once plugin_dir_path(__FILE__) . '/components/dashboard/dashboard.php';
}

// Mostrar página de Ajustes
function fex_show_settings()
{
    include_once plugin_dir_path(__FILE__) . '/components/settings/settings.php';
}