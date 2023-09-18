<?php
wp_enqueue_style('shipping-styles', plugin_dir_url("fex.php") . 'fex/assets/css/shipping-zones.css');
wp_enqueue_style('landing-styles', plugin_dir_url("fex.php") . 'fex/assets/css/onboarding.css');
include_once "shipping-functions.php";
?>
<!-- Reconfigurar -->
<div id="pickit-ok" class="modal">
    <div class="modal-content">

        <img src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/ok.png') ?>">

        <h2>
            <?php echo __('Zona de envíos configurada', 'wc-pickit') ?>
        </h2>
        <p>
            <?php echo __('Has configurado correctamente la Zonas de envío.', 'wc-pickit') ?>
        </p>
        <p>
            <?php echo __('Puedes <strong>Configurar los zona de envío las veces que quieras</strong>.', 'wc-pickit') ?>
        </p>
        <button style="background: black; " id="button-reconfigure">
            <?php echo __('Configurar Zona', 'wc-pickit') ?>
        </button>
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'submenu_dashboard')) ?>">
            <button>
                <?php echo __('Ir al Panel', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>

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
        <h1>Punto de Retiro</h1>
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
            <h1 class="address-title">Dirección de WooCommerce</h1>
            <h2>
                Ésta dirección proporcionada es de su tienda WooCommerce.
            </h2>
            <p><strong>País:</strong>
                Chile
            </p>
            <p><strong>Ciudad:</strong>
                <?php echo $direccion['comuna']; ?>
            </p>

            <p><strong>Calle:</strong>
                <?php echo $direccion['calle']; ?>
            </p>
        </div>

        <div class="map-container">
            <div id="contain-info" class="ubication-info-hidden">
                <button id="obtain-cors">Obtener Coordenadas</button>
                <div id="map" style="margin: 20px auto; width: 400px; height: 350px;"></div>
                <form method="post" id="coordinates-form">
                    <label for="latitude">Latitud:</label>
                    <input type="text" name="latitude" id="latitude" required>
                    <label for="longitude">Longitud:</label>
                    <input type="text" name="longitude" id="longitude" required>
                    <p class="message-fex">¡Cuando la información sea correcta guarda la configuración de tu tienda!</p>
                    <input type="submit" id="save-shipping-zones" value="Guardar configuración">
                </form>
            </div>
        </div>
    </div>
</div>

<?php if ($_SESSION["authorized"] == true && get_option("shipping_zones_is_config")) { ?>
    <script>
        jQuery(document).ready(function ($) {
            jQuery("#pickit-ok").css("display", 'block');
            jQuery("#map").css("z-index", '9');
            $("#button-reconfigure").click(function () {
                jQuery("#pickit-ok").css("display", 'none');

            })
        });
    </script>
<?php } ?>

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
            $("#contain-info").addClass("ubication-info");
            jQuery(document).ready(function ($) {
                map = L.map('map').setView([-34.6118, -58.4173], 3);
                // Agrega una capa de mapa base (por ejemplo, Mapbox Streets)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                marker = L.marker([0, 0]).addTo(map)
            })
            //si la zona de envío ya está configurada
            if (<?php echo get_option("shipping_zones_is_config") ?> && <?php echo $_SESSION["token"] ?>) {
                $("#contain-info").addClass("ubication-info");
                const coordinatesForm = $("#coordinates-form");
                const latitudeInput = $("#latitude");
                const longitudeInput = $("#longitude");
                latitudeInput.val(<?php echo get_option("get_fex_latitude") ?>);
                longitudeInput.val(<?php echo get_option("get_fex_longitude") ?>);
                jQuery(document).ready(function ($) {
                    marker.setLatLng([latitudeInput.val(), longitudeInput.val()]);
                    map.setView([-34.6118, -58.4173], 3);
                })
            }
            //si la zona de envío no está configurada
            else if (!<?php echo get_option("shipping_zones_is_config") ?> && <?php echo $_SESSION["token"] ?>) {
                $("#contain-info").addClass("ubication-info");
                //obteniendo inputs 
                const coordinatesForm = $("#coordinates-form");
                const latitudeInput = $("#latitude");
                const longitudeInput = $("#longitude");
                //solicitud a google maps
                const pais = 'Chile';
                const region = ' <?php echo $direccion['estado']; ?>';
                const comuna = ' <?php echo $direccion['comuna']; ?>';
                const calle = ' <?php echo $direccion['calle']; ?>';

                // Construir la dirección completa
                const direccion = `${calle}, ${comuna}, ${region}, ${pais}`;
                  const url = `https://naboo.holocruxe.com/geolocalization?address=${encodeURIComponent(direccion)}`;
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'OK') {
                            // Obtener la latitud y longitud desde la respuesta
                            const latitud = data.results[0].geometry.location.lat;
                            const longitud = data.results[0].geometry.location.lng;
                            latitudeInput.val(latitud);
                            longitudeInput.val(longitud);
                            jQuery(document).ready(function ($) {
                                marker.setLatLng([latitudeInput.val(), longitudeInput.val()]);

                            })

                        } else {
                            console.error('No se encontraron resultados para la dirección proporcionada.');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error al realizar la solicitud a la API de Geocodificación de Google Maps.', errorThrown);
                    }
                });
            }
            $("#obtain-cors").click(function () {
                $("#contain-info").addClass("ubication-info");
                //obteniendo inputs 
                const coordinatesForm = $("#coordinates-form");
                const latitudeInput = $("#latitude");
                const longitudeInput = $("#longitude");
                //solicitud a google maps
                const pais = 'Chile';
                const region = ' <?php echo $direccion['estado']; ?>';
                const comuna = ' <?php echo $direccion['comuna']; ?>';
                const calle = ' <?php echo $direccion['calle']; ?>';

                // Construir la dirección completa
                const direccion = `${calle}, ${comuna}, ${region}, ${pais}`;
                const url = `https://naboo.holocruxe.com/geolocalization?address=${encodeURIComponent(direccion)}`;
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'OK') {
                            // Obtener la latitud y longitud desde la respuesta
                            const latitud = data.results[0].geometry.location.lat;
                            const longitud = data.results[0].geometry.location.lng;
                            latitudeInput.val(latitud);
                            longitudeInput.val(longitud);
                            jQuery(document).ready(function ($) {
                                marker.setLatLng([latitudeInput.val(), longitudeInput.val()]);

                            })

                        } else {
                            console.error('No se encontraron resultados para la dirección proporcionada.');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error al realizar la solicitud a la API de Geocodificación de Google Maps.', errorThrown);
                    }
                });
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

            })
        })
        $("#longitude").change(function (event) {
            const latitudeInput = $("#latitude");
            const longitudeInput = $("#longitude");
            jQuery(document).ready(function ($) {
                marker.setLatLng([latitudeInput.val(), longitudeInput.val()]);

            })
        })
    })
</script>