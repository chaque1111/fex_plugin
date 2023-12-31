<?php
/*
Plugin Name: Fex Plugin
Plugin URI: https://naboo.holocruxe.com/docs
Description: Agrega los métodos de envío FEX a tu tienda de Woocommerce. Una solución completamente funcional que integra métodos de envío a tu tienda sin modificar su estructura o estilos. El plugin captura y procesa la información o eventos requeridos de tu tienda Woo para mejorar su performance.
Version: 1.0.1
Author: Holocruxe Factory
Author URI: https://www.linkedin.com/company/holocruxe
License: GPL2
*/


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

// Verificar si WooCommerce está activo
function fex_woocommerce_validacion()
{

    if (is_plugin_active('woocommerce/woocommerce.php')) {
        register_activation_hook(__FILE__, 'fex_add_menu');
    }
    else {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Este plugin requiere WooCommerce para funcionar correctamente. Por favor, instala, configura y activa WooCommerce.');
    }
}

register_activation_hook(__FILE__, 'fex_woocommerce_validacion');

//Ajustar el precio
function adjust_shipping_rate($rates)
{
    global $woocommerce;
    foreach ($rates as $rate) {
        if ($rate->method_id === "fex_express_shipping_method" || $rate->method_id === "fex_programado_shipping_method") {
            if (isset($_COOKIE['fex_shipping_cost'])) {
                $rate->cost = $_COOKIE['fex_shipping_cost'];
            }
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
        'Horarios de envíos',
        'Horarios de envíos',
        'manage_options',
        'shipping_times',
        'fex_show_shipping_times'
    );

    add_submenu_page(
        'fex_menu',
        'Comisión Extra',
        'Comisión Extra',
        'manage_options',
        'submenu_extra',
        'fex_show_extra'
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

// Mostrar página de Horarios de envíos
function fex_show_shipping_times()
{
    include_once plugin_dir_path(__FILE__) . '/components/shipping-times/shipping-times.php';
}


// Mostrar página de Panel de control
function fex_show_dashboard()
{
    include_once plugin_dir_path(__FILE__) . '/components/dashboard/dashboard.php';
}

// Mostrar página de comisión extra

function fex_show_extra()
{
    include_once plugin_dir_path(__FILE__) . '/components/extra-commission/extra.php';

}

// Mostrar página de Ajustes
function fex_show_settings()
{
    include_once plugin_dir_path(__FILE__) . '/components/settings/settings.php';
}