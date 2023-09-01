<?php
wp_enqueue_style('shipping-styles', plugin_dir_url("fex.php") . 'fex/assets/css/shipping-zones.css');
wp_enqueue_style('landing-styles', plugin_dir_url("fex.php") . 'fex/assets/css/onboarding.css');
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
            <?php echo __('Las credenciales ingresadas son incorrectas.<br>Por favor, Inicia sesión.', 'wc-pickit') ?>
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
        <h1>Punto de retiro</h1>
        <p>El punto de retiro es el lugar donde los repartidores de Fex recogerán los pedidos para
            realizar el envío.
        </p>
        <p><span style="color: #FF0000; font-weight: 600;">Atención:</span> Es importante que verifiques en el mapa si
            las coordenadas son correctas ya qué dependen mucho de la calidad del GPS de tu dispositivo y tu ubicación
            actual
        </p>
        <p><span style="color: green; font-weight: 600;">Recomendación:</span> Para mayor precisión en las coordenadas
            puedes ir a <a
                href="https://www.google.com/maps/place/Santiago,+Regi%C3%B3n+Metropolitana,+Chile/@-33.4723925,-70.7946379,11z/data=!3m1!4b1!4m6!3m5!1s0x9662c5410425af2f:0x8475d53c400f0931!8m2!3d-33.4488897!4d-70.6692655!16zL20vMGRscXY?entry=ttu"
                target="_blank">google maps</a> .
            Busca la ubicación de tu tienda copia las coordenadas en los inputs correspondientes, <a href=""
                target="_blank">Puedes
                seguir éste tutorial.</a><br>
        </p>
        <p> Puedes seguir estos pasos para configurar la ubicación de tu tienda:</p>
        <ol>
            <li>Presiona el botón de "Obtener Coordenadas" y acepta los permisos para acceder a tu ubicación.</li>
            <li>En el mapa aparecerá tu ubicación actual puede variar dependiendo la calidad del GPS debes verificar
                haciendo zoom.</li>
            <li>Si la ubicación en el mapa no coincide con la ubicación de tu tienda debes seguir la Recomendación de
                arriba.</li>
            <li>Una vez que la configuración sea correcta has click en "Guardar Configuración".</li>
        </ol>
        <p>Si necesitas ayuda adicional o tienes alguna pregunta, no dudes en <a href="https://fex.cl/index.php#support"
                target="_blank">contactarnos</a>.</p>
        <p>¡Gracias y esperamos que disfrutes utilizando Fex!</p>
    </div>
    <div class="container-info">

        <div class="address-info">
            <h1>Información de Dirección</h1>
            <h2>
                La información de dirección proporcionada es de su tienda WooCommerce.
            </h2>
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
        <div>
            <button id="obtain-cors">Obtener cordenadas</button>
            <div id="contain-info" class="ubication-info-hidden">
                <div id="map" style="width: 500px; height: 400px;"></div>

                <form method="post" id="coordinates-form">
                    <label for="latitude">Latitud:</label>
                    <input type="text" name="latitude" id="latitude" required>
                    <label for="longitude">Longitud:</label>
                    <input type="text" name="longitude" id="longitude" required>

                    <p>Si toda la información es correcta guarde la configuración de su tienda</p>
                    <input type="submit" id="save-shipping-zones" value="Guardar configuración">
                </form>
            </div>
        </div>
    </div>
</div>


<?php if (!isset($_SESSION["authorized"]) || $_SESSION["authorized"] == false) { ?>
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
            if (<?php echo get_option("shipping_zones_is_config") ?> && <?php echo $_SESSION["token"] ?>) {
                $("#contain-info").addClass("ubication-info");
                const coordinatesForm = $("#coordinates-form");
                const latitudeInput = $("#latitude");
                const longitudeInput = $("#longitude");
                latitudeInput.val(<?php echo get_option("get_fex_latitude") ?>);
                longitudeInput.val(<?php echo get_option("get_fex_longitude") ?>);
                jQuery(document).ready(function ($) {
                    map = L.map('map').setView([-34.6118, -58.4173], 3);
                    // Agrega una capa de mapa base (por ejemplo, Mapbox Streets)
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    marker = L.marker([latitudeInput.val(), longitudeInput.val()]).addTo(map)
                })
            }
            $("#obtain-cors").click(function () {
                $("#contain-info").addClass("ubication-info");
                const coordinatesForm = $("#coordinates-form");
                const latitudeInput = $("#latitude");
                const longitudeInput = $("#longitude");

                if (navigator.geolocation && <?php echo get_option("shipping_zones_is_config") ?>) {
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            latitudeInput.val(position.coords.latitude);
                            longitudeInput.val(position.coords.longitude);
                            jQuery(document).ready(function ($) {
                                marker.setLatLng([latitudeInput.val(), longitudeInput.val()]);
                                map.setView([-34.6118, -58.4173], 3);
                            })
                        },
                        function (error) {
                            console.log("Error al obtener la ubicación: ", error.message);
                        }
                    );
                }
                else if (navigator.geolocation && !<?php echo get_option("shipping_zones_is_config") ?>) {
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
                }

            })
        });
    })(jQuery);
</script>
<script>
    jQuery(document).ready(function ($) {

        $("#coordinates-form").keydown(function (event) {
            if (event.keyCode === 13) { // Código de tecla "Enter"
                event.preventDefault(); // Evita el comportamiento predeterminado (enviar el formulario)
            }
        })
        $("#latitude").change(function (event) {
            const latitudeInput = $("#latitude");
            const longitudeInput = $("#longitude");
            jQuery(document).ready(function ($) {
                marker.setLatLng([latitudeInput.val(), longitudeInput.val()]);
                map.setView([-34.6118, -58.4173], 3);
            })
        })
        $("#longitude").change(function (event) {
            const latitudeInput = $("#latitude");
            const longitudeInput = $("#longitude");
            jQuery(document).ready(function ($) {
                marker.setLatLng([latitudeInput.val(), longitudeInput.val()]);
                map.setView([-34.6118, -58.4173], 3);
            })
        })
    })
</script>