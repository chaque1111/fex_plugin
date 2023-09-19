<?php
session_start();
function obtener_direccion_woocommerce()
{
    $direccion = array(
        'estado' => get_option('woocommerce_default_country'),
        'comuna' => get_option('woocommerce_store_city'),
        'calle' => get_option('woocommerce_store_address'),
        'ciudad' => get_option('woocommerce_store_city'),
        'codigo_postal' => get_option('woocommerce_store_postcode'),
    );
    return $direccion;
}

// function obtener_todas_las_regiones_chile($name)
// {
//     $regiones = array(
//         'CL:CL-AI' => 'Aisén del General Carlos Ibáñez del Campo',
//         'CL:CL-AN' => 'Antofagasta',
//         'CL:CL-AP' => 'Arica y Parinacota',
//         'CL:CL-TA' => 'Tarapacá',
//         'CL:CL-AT' => 'Atacama',
//         'CL:CL-CO' => 'Coquimbo',
//         'CL:CL-VA' => 'Valparaíso',
//         'CL:CL-LI' => 'Libertador General Bernardo O\'Higgins',
//         'CL:CL-ML' => 'Maule',
//         'CL:CL-BI' => 'Biobío',
//         'CL:CL-AR' => 'La Araucanía',
//         'CL:CL-LR' => 'Los Ríos',
//         'CL:CL-LS' => 'Los Lagos',
//         'CL:CL-MG' => 'Magallanes y de la Antártica Chilena',
//         'CL:CL-RM' => 'Región Metropolitana de Santiago'
//     );
//     return $regiones[$name];
// }
$direccion = obtener_direccion_woocommerce();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["latitude"]) && isset($_POST["longitude"])) {
        $latitude = $_POST["latitude"];
        $longitude = $_POST["longitude"];
        update_option('get_fex_latitude', $latitude);
        update_option('get_fex_longitude', $longitude);
        update_option('get_fex_dir_org', $direccion["calle"] . ', ' . $direccion["comuna"] . ', ' . $direccion["estado"] . ', Chile');
        $url = 'https://naboo.holocruxe.com/store/config';
        $data = array(
            "access_key" => get_option('access_key'),
            "country" => "Chile",
            "store_name" => get_option('blogname'),
            "url" => get_home_url(),
            "store_lat" => floatval($latitude),
            "store_lng" => floatval($longitude),
            "address" => $direccion["calle"] . ', ' . $direccion["comuna"],
            "post_code" => $direccion["codigo_postal"],
            "city" => $direccion["estado"]
        ); // Datos para enviar en el PATCH

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
            update_option("shipping_zones_is_config", 1);
        }

    }
}
?>