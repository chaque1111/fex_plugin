<?php
add_action('wp_ajax_calculate_shipping', 'calculate_shipping_ajax_callback');
add_action('wp_ajax_nopriv_calculate_shipping', 'calculate_shipping_ajax_callback');

function calculate_shipping_ajax_callback()
{
    session_start();
    if (isset($_POST['vehicle']) && isset($_POST['pais']) && isset($_POST['region']) && isset($_POST['comuna']) && isset($_POST['calle'])) {
        // Obtener la información de dirección desde algún lugar
        $pais = $_POST['pais'];
        $region = $_POST['region'];
        $comuna = $_POST['comuna'];
        $calle = $_POST['calle'];
        if ($comuna === "" || $calle === "") {
            wp_send_json("false");
        }
        // Construir la dirección completa
        $direccion = $calle . ", " . $comuna . ", " . $region . ", " . $pais;

        // Construir la URL para la solicitud
        $url = "https://naboo.holocruxe.com/geolocalization?address=" . urlencode($direccion);

        // Realizar la solicitud a Google Maps usando wp_remote_get
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            // Error al realizar la solicitud
            echo "Error al realizar la solicitud a la API de Google Maps: " . $response->get_error_message();
        }
        else {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body);

            if ($data && $data->status === 'OK') {
                // Obtener la latitud y longitud desde la respuesta
                $_SESSION["client_latitude"] = $data->results[0]->geometry->location->lat;
                $_SESSION["client_longitude"] = $data->results[0]->geometry->location->lng;
                // $_SESSION["address_name"] = $data->results[0]->formatted_address;
            }
            else {
                // No se encontraron resultados para la dirección proporcionada
                echo "No se encontraron resultados para la dirección proporcionada.";
            }
        }

        $data = array(
            "acceso" => get_option("access_key"),
            "ori_lat" => get_option("get_fex_latitude"),
            "ori_lng" => get_option("get_fex_longitude"),
            "des_lat" => $_SESSION["client_latitude"],
            "des_lng" => $_SESSION["client_longitude"],
            "vehiculo" => $_POST['vehicle'],
            "reg_origen" => "0"
        );

        $jsonData = json_encode($data);
        $url = 'https://fex.cl/fex_api/externo/flete/cotizar';

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => $jsonData
            )
        );

        $context = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        // Mostrar la respuesta (puede ser un JSON u otro formato)

        $response = json_decode($result);
        $_SESSION["vehicle_calculate"] = $_POST["vehicle"];

        if (get_option("extra_porcentage")) {
            $_SESSION["price_calculate"] = $response->resultado->total + $response->resultado->total * get_option("extra_porcentage");
            wp_send_json($response->resultado->total + $response->resultado->total * get_option("extra_porcentage"));
        }
        if (get_option("extra_price")) {
            $_SESSION["price_calculate"] = $response->resultado->total + get_option("extra_price");
            wp_send_json($response->resultado->total + get_option("extra_price"));
        }
        ;
    }
    else {
        wp_send_json("false");
    }
}


add_action('wp_ajax_save_config', 'save_config_ajax_callback');
add_action('wp_ajax_nopriv_save_config', 'save_config_ajax_callback');

function save_config_ajax_callback()
{
    session_start();
    if (isset($_SESSION["vehicle_calculate"]) && isset($_SESSION["price_calculate"])) {
        if ($_POST["programado"] && $_POST["vehicle"]) {
            $_SESSION["programado"] = $_POST["programado"];
            $_SESSION["date"] = $_POST["date"];
            $_SESSION["time"] = $_POST["time"];
        }
        $_SESSION["pais"] = $_POST['pais'];
        $_SESSION["region"] = $_POST['region'];
        $_SESSION["comuna"] = $_POST['comuna'];
        $_SESSION["calle"] = $_POST['calle'];
        $_SESSION["vehicle"] = $_POST["vehicle"];
        $_SESSION["price"] = $_SESSION["price_calculate"];
        setcookie('fex_shipping_cost', $_SESSION["price_calculate"], time() + (60 * 20), '/');
        $_COOKIE['fex_shipping_cost'] = $_SESSION["price_calculate"];
        $packages = WC()->cart->get_shipping_packages();
        foreach ($packages as $package_key => $package) {
            WC()->session->set('shipping_for_package_' . $package_key, false);
        }

        wp_send_json(true);
    }
    else {
        wp_send_json(false);
    }
}