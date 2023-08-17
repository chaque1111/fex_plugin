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




//Agregar métodos de envío
include_once plugin_dir_path(__FILE__) . '/shipping/shipping-methods.php';

//Agregar modal
include_once plugin_dir_path(__FILE__) . '/modals/modals.php';



// Agregar la acción al hook woocommerce_new_order
add_action('woocommerce_checkout_order_processed', 'fex_post_flete', 10, 1);


register_activation_hook(__FILE__, 'fex_add_menu');
function fex_post_flete($order_id)
{
    // Obtener el objeto del pedido
    $order = wc_get_order($order_id);

    // Obtener el identificador del método de envío

    session_start();
    $info_client = $order->get_address('shipping');
    $customer_id = $order->get_customer_id();

    // Obtener los datos del cliente basados en el ID
    $customer_data = get_userdata($customer_id);
    // Verificar si el método de envío es el deseado (cambia 'fex_shipping_method' por el método real)
    if ($order->get_shipping_method() === "Fex express" && isset($_SESSION["client_latitude"]) && isset($_SESSION["client_longitude"])) {
        $post_data = array(
            "acceso" => get_option("access_key"),
            "ori_lat" => get_option("get_fex_latitude"),
            "ori_lng" => get_option("get_fex_longitude"),
            "dir_origen" => get_option("get_fex_dir_org"),
            "des_lat" => $_SESSION["client_latitude"],
            "des_lng" => $_SESSION["client_longitude"],
            "dir_destino" => $info_client["address_1"] . ' ' . $info_client["address_2"] . ', ' . $info_client["address_2"] . $info_client["city"] . ', ' . $info_client["state"] . ', ' . $info_client["country"],
            "des_carga" => "Envío fex para woocommerce",
            "rec_nom" => $info_client["first_name"] . ' ' . $info_client["last_name"],
            "rec_tel" => $customer_data->billing_phone,
            "vehiculo" => "7",
            "reg_origen" => "0"
        );

        // URL del servidor externo donde deseas enviar la solicitud POST
        $server_url = 'http://localhost:3001/flete';

        // Configurar los encabezados de la solicitud
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($post_data),
            ),
        );
        $context = stream_context_create($options);
        $response = file_get_contents($server_url, false, $context);

        // Verificar la respuesta del servidor externo
        if ($response === false) {
            error_log('Error al enviar la solicitud.');
        } else {
            // Procesar la respuesta del servidor si es necesario
            // Puedes realizar acciones basadas en la respuesta del servidor
        }

    }
}



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
    if (class_exists('WC_Shipping_Method')) {

    }
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