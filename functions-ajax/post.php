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

add_action('wp_ajax_calculate_shipping', 'calculate_shipping_ajax_callback');
add_action('wp_ajax_nopriv_calculate_shipping', 'calculate_shipping_ajax_callback');

function calculate_shipping_ajax_callback()
{
    session_start();
    if (isset($_POST['vehicle']) && isset($_SESSION["client_latitude"]) && isset($_SESSION["client_longitude"])) {
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
        $_SESSION["price_calculate"] = $response->resultado->total;
        wp_send_json($response->resultado->total);
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
        $_SESSION["vehicle"] = $_SESSION["vehicle_calculate"];
        $_SESSION["price"] = $_SESSION["price_calculate"];
        wp_send_json(true);
    }
    else {
        wp_send_json("false");
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