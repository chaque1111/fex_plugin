<?php
session_start();
function fex_express_validation($passed, $checkout)
{
    // Obtén el ID del método de envío seleccionado
    $chosen_shipping_method = WC()->session->get('chosen_shipping_methods')[0];
    // Define el ID del método de envío que quieres validar
    $metodo_envio_validar = 'fex_express_shipping_method'; // Cambia esto por el ID correcto

    if ($chosen_shipping_method === $metodo_envio_validar) {
        $url = "http://worldtimeapi.org/api/timezone/America/Santiago";
        // Realizar la solicitud GET
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        $datetime = DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $data['datetime']);
        $current_time = $datetime->format('H:i');

        $start_time = get_option("shipping_times_max"); // Hora de inicio
        $end_time = get_option("shipping_times_min"); // Hora de finalización
        // Si la hora actual está dentro del rango, deshabilita el método de envío
        if (($current_time >= $start_time && $current_time <= '23:59') || ($current_time >= '08:00' && $current_time <= $end_time)) {
            wc_add_notice("Los horarios de envío Express en 30 minutos son entre las " . $end_time . " - AM " . "y las " . $start_time . " - PM.<br> ¡Puedes usar Fex programado y programar la llegada para mañana!", 'error');
            $passed = false;
            return;
        }
        if (!is_user_logged_in()) {
            wc_add_notice('Debes iniciar sesión para usar el método de envío de Fex', 'error');
            $passed = false;
            return;
        }
        if (!isset($_SESSION["client_latitude"]) && !isset($_SESSION["client_longitude"])) {
            wc_add_notice('Para usar el método de envío de fex debes darnos acceso a tu ubicación', 'error');
            $passed = false;
            return;
        }
        if (!isset($_SESSION["vehicle"])) {
            wc_add_notice('Necesita configurar un vehiculo para el método de envio Fex', 'error');
            $passed = false;
            return;
        }
    }

    return $passed;
}
add_filter('woocommerce_after_checkout_validation', 'fex_express_validation', 10, 2);


add_action('woocommerce_checkout_order_processed', 'fex_flete_express', 10, 1);

function fex_flete_express($order_id)
{
    // Obtener el objeto del pedido
    $order = wc_get_order($order_id);

    // Obtener el identificador del método de envío
    $info_client = $order->get_address('shipping');
    $customer_id = $order->get_customer_id();

    // Obtener los datos del cliente basados en el ID
    $customer_data = get_userdata($customer_id);
    // Verificar si el método de envío es el deseado (cambia 'fex_shipping_method' por el método real)
    if ($order->get_shipping_method() === "Fex express" && isset($_SESSION["client_latitude"]) && isset($_SESSION["client_longitude"])) {
        date_default_timezone_set('America/Santiago');
        // Obtiene la hora actual de Chile
        $currentDateTime = new DateTime(); // Crea un objeto DateTime con la fecha y hora actuales de Chile
        $post_data = array(
            "acceso" => get_option("access_key"),
            "ori_lat" => get_option("get_fex_latitude"),
            "ori_lng" => get_option("get_fex_longitude"),
            "dir_origen" => get_option("get_fex_dir_org"),
            "des_lat" => $_SESSION["client_latitude"],
            "des_lng" => $_SESSION["client_longitude"],
            "dir_destino" => $info_client["address_1"] . ', ' . $info_client["city"] . ', ' . $info_client["state"] . ', ' . $info_client["country"],
            "des_carga" => "Envío fex para woocommerce",
            "rec_nom" => $info_client["first_name"] . ' ' . $info_client["last_name"],
            "rec_tel" => $customer_data->billing_phone,
            "vehiculo" => $_SESSION["vehicle"],
            "reg_origen" => "0",
            "fecha" => $currentDateTime->format('Y-m-d'),
            "wc_order" => $order->get_order_number()
        );
        print_r($post_data);
        // URL del servidor externo donde deseas enviar la solicitud POST
        $server_url = 'https://naboo.holocruxe.com/flete';

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
        //elimina las variables de sesión
        unset($_SESSION['client_latitude']);
        unset($_SESSION['client_longitude']);
        unset($_SESSION['vehicle']);
        unset($_SESSION['vehicle_calculate']);
        unset($_SESSION['price_calculate']);
        unset($_SESSION['price']);
        unset($_SESSION['programado']);
        unset($_SESSION['pais']);
        unset($_SESSION['region']);
        unset($_SESSION['comuna']);
        unset($_SESSION['calle']);
        setcookie('fex_shipping_cost', 0, time() + (60 * 20), '/');
        $packages = WC()->cart->get_shipping_packages();
        foreach ($packages as $package_key => $package) {
            WC()->session->set('shipping_for_package_' . $package_key, false);
        }
        // Verificar la respuesta del servidor externo
        if ($response === false) {
            error_log('Error al enviar la solicitud.');
        }
        else {
            // Procesar la respuesta del servidor si es necesario
            // Puedes realizar acciones basadas en la respuesta del servidor
        }

    }
}