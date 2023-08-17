<?php
if (isset($_POST["Ingresar"]) && $_POST["accessKey"] !== "" && $_POST["country"] !== "") {
    $country = $_POST["country"];
    $accessKey = $_POST["accessKey"];

    $datos = array(
        'access_key' => $accessKey,
        'country' => $country
    );

    // Convertir los datos en una cadena de consulta
    $datosConsulta = http_build_query($datos);

    // Configurar el contexto de flujo para la solicitud POST
    $opciones = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $datosConsulta
        )
    );

    $contexto = stream_context_create($opciones);

    // Desactivar la notificación de errores temporariamente para esta solicitud
    error_reporting(0);
    // Realizar la solicitud POST y obtener la respuesta del servidor
    $url = 'http://localhost:3001/login';
    $resultado = file_get_contents($url, false, $contexto);

    if (isset($resultado) && $resultado !== false) {
        // Manejar la respuesta del servidor
        session_start();
        $_SESSION["accessKey"] = $accessKey;
        $_SESSION["token"] = $resultado;
        $_SESSION["authorized"] = true;
        update_option('access_key', $accessKey);
    } else {
        $_SESSION["authorized"] = false;
        $authorized = false;
    }

}
?>