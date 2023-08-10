<?php
include_once "shipping-functions.php";

?>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<div id="pickit-error" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'fex_menu')) ?>">
            <span class="close">&times;</span>
        </a>
        <img src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/error.png') ?>">
        <h2>
            <?php echo __('Credenciales incorrectas', 'wc-pickit') ?>
        </h2>
        <p>
            <?php echo __('Las credenciales ingresadas son incorrectas.<br>Por favor, vuelve a intentarlo.', 'wc-pickit') ?>
        </p>

        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'fex_menu')) ?>">
            <button>
                <?php echo __('Aceptar', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>
<div class="container">
    <div class="steps-container">
        <h1>¡Hola Bienvenido a Fex!</h1>
        <p>Gracias por utilizar nuestro plugin. Para obtener las coordenadas del punto de retiro de
            los productos, por favor
            sigue estos pasos:</p>
        <ol>
            <li>Asegúrate de estar en el punto de retiro físico de los productos.</li>
            <li>Haz clic en el botón "Obtener Coordenadas" en esta página.</li>
            <li>Si tu navegador solicita permiso para acceder a tu ubicación, por favor acepta para que podamos capturar
                automáticamente las coordenadas.</li>
            <li>Si prefieres ingresar las coordenadas manualmente, puedes hacerlo en los campos correspondientes.</li>
        </ol>
        <p>Recuerda que la precisión de las coordenadas dependerá de la calidad de la señal GPS o la dirección que
            proporciones. Si tienes problemas para obtener las coordenadas, verifica que tu dispositivo tenga activada
            la
            función de geolocalización y esté conectado a Internet.</p>
        <p>Si necesitas ayuda adicional o tienes alguna pregunta, no dudes en contactarnos.</p>
        <p>¡Gracias y esperamos que disfrutes utilizando Fex!</p>
    </div>
    <div class="container-info">
        <h1>Información de Dirección</h1>
        <h2>
            La información de dirección proporcionada es de su tienda WooCommerce.
        </h2>
        <div class="address-info">
            <p><strong>Pais:</strong>
                Chile
            </p>
            <p><strong>Región:</strong>
                <?php echo $direccion['estado']; ?>
            </p>
            <p><strong>Comuna:</strong>
                <?php echo $direccion['comuna']; ?>
            </p>

            <p><strong>Calle:</strong>
                <?php echo $direccion['calle']; ?>
            </p>
        </div>
    </div>
    <button id="obtain-cors">Obtener cordenadas</button>
    <div id="contain-info" class="ubication-info-hidden">
        <div id="map" style="width: 621px; height: 400px;"></div>

        <form method="post" id="coordinates-form">
            <label for="latitude">Latitud:</label>
            <input type="text" name="latitude" id="latitude" required>
            <label for="longitude">Longitud:</label>
            <input type="text" name="longitude" id="longitude" required>
            <button id="verify-cors">Verificar coordenadas en el mapa</button>
            <p>Si toda la información es correcta guarde guarde la configuración de su tienda</p>
            <input type="submit" id="save-shipping-zones" value="guardar configuración">
        </form>
    </div>
</div>

<?php if (!isset($_SESSION["authorized"]) || isset($_SESSION["authorized"]) && $_SESSION["authorized"] == false) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("NOK");
            jQuery("#pickit-error").css("display", 'block');
        });
    </script>
<?php }
?>

<script>
    (function ($) {
        $(document).ready(function () {
            $("#obtain-cors").click(function () {
                const containInfo = document.getElementById("contain-info")
                containInfo.classList.toggle("ubication-info");
                const coordinatesForm = $("#coordinates-form");
                const latitudeInput = $("#latitude");
                const longitudeInput = $("#longitude");

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            latitudeInput.val(position.coords.latitude);
                            longitudeInput.val(position.coords.longitude);
                            jQuery(document).ready(function ($) {
                                map = L.map('map').setView([-34.6118, -58.4173], 3);
                                // Agrega una capa de mapa base (por ejemplo, Mapbox Streets)
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '© OpenStreetMap contributors'
                                }).addTo(map);

                                marker = L.marker([latitudeInput.val(), longitudeInput.val()]).addTo(map)
                            })
                        },
                        function (error) {
                            console.log("Error al obtener la ubicación: ", error.message);
                        }
                    );
                } else {
                    console.log("Geolocalización no es soportada en este navegador.");
                }
            })
        });
    })(jQuery);
</script>
<script>
    jQuery(document).ready(function ($) {
        $("#verify-cors").click(function (event) {
            event.preventDefault()
            const latitudeInput = $("#latitude");
            const longitudeInput = $("#longitude");
            console.log(latitudeInput.val(), longitudeInput.val())
            jQuery(document).ready(function ($) {
                marker.setLatLng([latitudeInput.val(), longitudeInput.val()]);
                map.setView([-34.6118, -58.4173], 3);
            })
        })
    })
</script>