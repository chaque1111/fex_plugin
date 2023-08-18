<?php
add_action('wp_ajax_save_coordinates', 'save_coordinates_callback');
add_action('wp_ajax_nopriv_save_coordinates', 'save_coordinates_callback');
function save_coordinates_callback()
{
    if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
        session_start();
        $_SESSION["client_latitude"] = $_POST['latitude'];
        $_SESSION["client_longitude"] = $_POST['longitude'];

        wp_send_json(true);
    }
    else {
        $response = array('status' => 'error', 'message' => 'Invalid data');
        wp_send_json($response);
    }
}

add_action('wp_ajax_save_cookie', 'save_cookie_ajax_callback');
add_action('wp_ajax_nopriv_save_cookie', 'save_cookie_ajax_callback');

function save_cookie_ajax_callback()
{
    session_start();
    if (isset($_POST['vehicle'])) {
        $_SESSION["vehicle"] = $_POST["vehicle"];
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


        wp_send_json($result);
    }
    else {
        $response = array('status' => 'error', 'message' => 'Invalid data');
        wp_send_json($response);
    }
}













/* add_action('woocommerce_before_shop_loop', 'fex_cotizar');
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
*/