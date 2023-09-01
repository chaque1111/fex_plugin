<?php
include_once "settigns-functions.php";
wp_enqueue_style('settings-fex-styles', plugin_dir_url("fex.php") . 'fex/assets/css/settings.css');
wp_enqueue_style('landing-styles', plugin_dir_url("fex.php") . 'fex/assets/css/onboarding.css');
// Obtén la información de dirección de WooCommerce
$direccion_tienda = get_option('woocommerce_store_address');
$ciudad_tienda = get_option('woocommerce_store_city');
$estado_tienda = get_option('woocommerce_store_state');
$codigo_postal_tienda = get_option('woocommerce_store_postcode');
$pais_tienda = get_option('woocommerce_default_country');
$ubicacion_tienda = $direccion_tienda . ', ' . $ciudad_tienda . ', ' . $estado_tienda . ' ' . $codigo_postal_tienda . ', ' . $pais_tienda;
?>
<!-- Incluye Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<!-- Incluye Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Información de la Tienda</h4>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Nombre de la Tienda:</strong>
                            <?php echo get_option("blogname") ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Ubicación:</strong>
                            <?php echo esc_html($ubicacion_tienda); ?>
                        </li>
                        <li class="list-group-item">
                            <div id="map" style="width: 100%; height: 300px; border-radius: 4px;"></div>
                        </li>
                        <li class="list-group-item">
                            <strong>Horario de Atención:</strong>
                            <?php echo get_option("shipping_times_min") . " AM" . " - " . get_option("shipping_times_max") . " PM" ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <form class="form-settings" method="post">
        <button class="btn btn-danger" value="logout" name="logout">Cerrar Sesión</button>
    </form>
</div>

<!-- Incluye Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Incluye Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<!-- Mostrar mapa -->
<script>
    $(document).ready(function () {
        if (<?php echo get_option("shipping_zones_is_config") ?> && <?php echo $_SESSION["authorized"] ?>) {
            jQuery(document).ready(function ($) {
                map = L.map('map').setView([-34.6118, -58.4173], 3);
                // Agrega una capa de mapa base (por ejemplo, Mapbox Streets)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                marker = L.marker([<?php echo get_option("get_fex_latitude") ?>, <?php echo get_option("get_fex_longitude") ?>]).addTo(map)
            })
        } else {
            jQuery(document).ready(function ($) {
                map = L.map('map').setView([-34.6118, -58.4173], 3);
                // Agrega una capa de mapa base (por ejemplo, Mapbox Streets)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                marker = L.marker([]).addTo(map)
            })
        }
    })
</script>
<!-- Redirección al login -->

<div style="z-index: 1000;" id="pickit-error" class="modal">
    <!-- Modal content -->
    <div style="padding-top: 10px" class="modal-content">
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'fex_menu')) ?>">
            <span class="close">&times;</span>
        </a>
        <img style="width: 69px; height: 68px;  margin: 0 auto 30px;  "
            src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/error.png') ?>">
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
<?php if (!isset($_SESSION["authorized"]) || $_SESSION["authorized"] == false) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("NOK");
            jQuery("#pickit-error").css("display", 'block');
        });
    </script>
<?php } ?>

<?php ?>