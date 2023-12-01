<?php
wp_enqueue_style('extra-commission-styles', plugin_dir_url("fex.php") . 'fex/assets/css/extra-commission.css');
wp_enqueue_style('landing-styles', plugin_dir_url("fex.php") . 'fex/assets/css/onboarding.css');




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data;
    if (isset($_POST["porcentaje"]) || isset($_POST["precio"])) {
        if (isset($_POST["porcentaje"])) {
            $porcentaje = $_POST["porcentaje"];
            if (strlen($porcentaje) == 1) {
                $porcentaje = '0.0' . $porcentaje;
            }
            elseif (strlen($porcentaje) == 2) {
                $porcentaje = '0.' . $porcentaje;
            }
            update_option('extra_porcentage', $porcentaje);
            delete_option("extra_price");
            $data = array(
                "access_key" => get_option("access_key"),
                "extra_commission" => $_POST["porcentaje"] . "%",
            ); // Datos para enviar en el PATCH
        }
        else {
            update_option('extra_price', $_POST["precio"]);
            delete_option("extra_porcentage");
            $data = array(
                "access_key" => get_option("access_key"),
                "extra_commission" => "$" . $_POST["precio"],
            ); // Datos para enviar en el PATCH
        }

        //Enviando Solicitud Patch
        $url = 'https://naboo.holocruxe.com/store/extra';

        $ch = curl_init($url);

        // Configurar la solicitud cURL como PATCH
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        $json_data = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        if ($response === false) {
            // Error al realizar la solicitud
            echo 'Error de cURL: ' . curl_error($ch);
        }
        else {
            // Procesa la respuesta
            update_option("extra_commission_is_config", 1);
        }

    }
}

?>