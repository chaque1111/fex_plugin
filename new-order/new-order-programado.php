<?php
session_start();
function fex_programado_validation($passed, $checkout)
{
    // Obtén el ID del método de envío seleccionado
    $chosen_shipping_method = WC()->session->get('chosen_shipping_methods')[0];

    // Define el ID del método de envío que quieres validar
    $metodo_envio_validar = 'fex_programado_shipping_method'; // Cambia esto por el ID correcto

    if ($chosen_shipping_method === $metodo_envio_validar) {
        // Realiza tu validación aquí
        if (!is_user_logged_in()) {
            wc_add_notice('Debes iniciar sesión para usar el método de envío de Fex', 'error');
            $passed = false;
            return;
        }

        if (!isset($_SESSION["client_latitude"]) && !isset($_SESSION["client_longitude"])) {
            wc_add_notice('Para usar el método de envío de Fex debes darnos acceso a tu ubicación', 'error');
            $passed = false;
            return;

        }
        if (!isset($_SESSION["vehicle"])) {
            wc_add_notice('Para usar el método de envío de Fex programado necesita configurar un vehiculo', 'error');
            $passed = false;
            return;
        }
        if (!isset($_SESSION["programado"])) {
            wc_add_notice('Para usar el método de envío de Fex programado necesita configurar la fecha y hora en la que llegarán sus pedido', 'error');
            $passed = false;
            return;
        }
    }

    return $passed;
}
add_filter('woocommerce_after_checkout_validation', 'fex_programado_validation', 10, 2);


add_action('woocommerce_checkout_order_processed', 'fex_flete_programado', 10, 1);
function fex_flete_programado($order_id)
{
    // Obtener el objeto del pedido
    $order = wc_get_order($order_id);

    // Obtener el identificador del método de envío
    $info_client = $order->get_address('shipping');
    $customer_id = $order->get_customer_id();

    // Obtener los datos del cliente basados en el ID
    $customer_data = get_userdata($customer_id);
    // Verificar si el método de envío es el deseado (cambia 'fex_shipping_method' por el método real)
    if ($order->get_shipping_method() === "Fex programado" && isset($_SESSION["client_latitude"]) && isset($_SESSION["client_longitude"])) {
        $currentDateTime = new DateTime(); // Crea un objeto DateTime con la fecha y hora actuales
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
            "programado" => $_SESSION["programado"],
            "reg_origen" => "0",
            "fecha" => $currentDateTime->format('Y-m-d'),
            "wc_order" => $order->get_order_number()
        );

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