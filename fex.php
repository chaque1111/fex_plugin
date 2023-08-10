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

// Cargar estilos o scripts, si es necesario


register_activation_hook(__FILE__, 'fex_add_menu');

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) && get_option('shipping_zones_is_config')) {
    function your_shipping_method_init()
    {
        if (!class_exists('Fex_Shipping_Method')) {
            class Fex_Shipping_Method extends WC_Shipping_Method
            {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct()
                {
                    $this->id = 'fex_shipping_method'; // Id for your shipping method. Should be uunique.
                    $this->method_title = __('Fex express'); // Title shown in admin
                    $this->method_description = __('Fex express, tu producto llega en 30 minutos en la ciudad de Santiago.'); // Description shown in admin
                    $this->enabled = "yes"; // This can be added as an setting but for this example its forced enabled
                    $this->title = "Fex express"; // This can be added as an setting but for this example its forced.

                    $this->init();
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                    $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }
                /**
                 * calculate_shipping function.
                 *
                 * @access public
                 * @param array $package
                 * @return void
                 */

                public function calculate_shipping($package = array())
                {
                    session_start();
                    if (isset($_SESSION["client_latitude"]) && isset($_SESSION["client_longitude"])) {
                        $client_latitude = $_SESSION["client_latitude"];
                        $client_longitude = $_SESSION["client_longitude"];
                        $shop_latitude = get_option('get_fex_latitude');
                        $shop_longitude = get_option('get_fex_longitude');
                        $server_url = 'http://localhost:3001/flete/shipping'; // URL del servidor de obtención de precio
                        $response = file_get_contents($server_url);
                        $cart_weight = WC()->cart->get_cart_contents_weight();

                        $data = array(
                            'client_latitude' => $client_latitude,
                            'client_longitude' => $client_longitude,
                            'shop_latitude' => $shop_latitude,
                            'shop_longitude' => $shop_longitude,
                            'cart_weight' => $cart_weight
                        );

                        // Configurar los encabezados de la solicitud
                        $options = array(
                            'http' => array(
                                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                                'method' => 'POST',
                                'content' => http_build_query($data)
                            )
                        );
                        // Crear el contexto de flujo
                        $context = stream_context_create($options);

                        // Realizar la solicitud POST y obtener la respuesta
                        $response = file_get_contents($server_url, false, $context);

                        // Si necesitas manejar la respuesta, puedes hacerlo aquí
                        $rate = array(
                            'label' => $this->title,
                            'cost' => $response,
                            'calc_tax' => 'per_item'
                        );
                        // Register the rate
                        $this->add_rate($rate);
                    } else {
                        $rate = array(
                            'label' => $this->title,
                            'calc_tax' => 'per_item'
                        );
                        // Register the rate
                        $this->add_rate($rate);
                    }

                }
            }
        }
    }

    add_action('woocommerce_shipping_init', 'your_shipping_method_init');

    function add_your_shipping_method($methods)
    {
        $methods['your_shipping_method'] = 'Fex_Shipping_Method';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_your_shipping_method');

    add_action('wp_ajax_save_coordinates', 'save_coordinates_callback');
    add_action('wp_ajax_nopriv_save_coordinates', 'save_coordinates_callback');

    function save_coordinates_callback()
    {
        if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
            session_start();
            $_SESSION["client_latitude"] = $_POST['latitude'];
            $_SESSION["client_longitude"] = $_POST['longitude'];

            wp_send_json(true);
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid data');
            wp_send_json($response);
        }
    }

    add_action('woocommerce_before_shop_loop', 'fex_cotizar');


    function fex_cotizar()
    {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;
                        console.log("Latitud:", latitude);
                        console.log("Longitud:", longitude);
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'save_coordinates',
                                latitude: latitude,
                                longitude: longitude,
                            },
                            dataType: 'json',
                            success: function (response) {
                                console.log('Respuesta exitosa:', response);
                            },
                            error: function (xhr, status, error) {
                                console.log('Error:', error);
                            }
                        });

                    });

                } else {
                    console.log("La geolocalización no está disponible en este navegador.");
                }
            })
        </script>

        <?php
    }

    // Registrando la acción para detectar cuando se completa un pedido

}


// Agregar la acción al hook woocommerce_new_order
add_action('woocommerce_checkout_order_processed', 'fex_post_flete', 10, 1);

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