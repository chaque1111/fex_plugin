<?php
add_action('woocommerce_checkout_order_processed', 'fex_flete_programado', 10, 1);
function fex_flete_programado($order_id)
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
    if ($order->get_shipping_method() === "Fex programado" && isset($_SESSION["client_latitude"]) && isset($_SESSION["client_longitude"])) {
        $currentDateTime = new DateTime(); // Crea un objeto DateTime con la fecha y hora actuales
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
            "vehiculo" => $_SESSION["vehicle"],
            "programado" => $_SESSION["programado"],
            "reg_origen" => "0",
            // "fecha" => $currentDateTime->format('Y-m-d H:i:s'),
            // "wc_order" => $order->get_order_number()
        );

        // URL del servidor externo donde deseas enviar la solicitud POST
        $server_url = 'https://naboo-production.up.railway.app/flete';

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
        }
        else {
            // Procesar la respuesta del servidor si es necesario
            // Puedes realizar acciones basadas en la respuesta del servidor
        }

    }
}