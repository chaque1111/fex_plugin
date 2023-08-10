<?php
wp_enqueue_style('landing-styles', plugin_dir_url("fex.php") . 'fex/assets/css/onboarding.css');
include_once "landing-functions.php";
?>


<div id="pickit-ok" class="modal">
    <div class="modal-content">
        <a href="">
            <span class="close">&times;</span>
        </a>
        <img src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/ok.png') ?>">

        <h2>
            <?php echo __('Credenciales correctas', 'wc-pickit') ?>
        </h2>
        <p>
            <?php echo __('Has finalizado la configuración inicial para fex.', 'wc-pickit') ?>
        </p>
        <p>
            <?php echo __('Ahora necesitas <strong>configurar los puntos de retiro</strong>. Para esto, dirígete a <strong>Fex > Zonas de Envíos</strong>', 'wc-pickit') ?>
        </p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'shipping_zones')) ?>">
            <button>
                <?php echo __('Ir a Zona de envíos', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>
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

        <a href="">
            <button>
                <?php echo __('Aceptar', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>
<div class="pickit">

    <div class="pickit-container">
        <div class="row">
            <div class="column30">
                <div class="left-pane">
                    <img style="width: 15vw;"
                        src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/fex_app.png') ?>">
                    <h1 class="welcome">
                        <?= sprintf(__('¡Hola Bienvenido a Fex!', 'wc-pickit'), get_option('blogname')) ?>
                    </h1>
                    <p>
                        <?php echo __("Gracias por comenzar a usar la aplicación de <strong>fex</strong> para <strong>WooCommerce</strong>, Para continuar con el proceso de configuración, es necesario que ya cuentes con una cuenta en <strong>fex</strong>. Si aún no la tienes, <a class='create_account_link' href='#' target='_blank'> Crea tu cuenta aquí.</a>", 'wc-pickit') ?>
                    </p>
                    <img class="left-pane-img" src="<?php echo esc_url($pickit_left_pane_img) ?>">
                </div>
            </div>
            <div class="column70">
                <div class="right-pane">
                    <h2>
                        <?php echo __('Ingresá tus credenciales de Fex', 'wc-picki') ?>
                    </h2>
                    <p>
                        <?php echo __('¿No conocés tu Access Key? <a class="contact_link" href="#" target="_blank" >Contáctanos</a>', 'wc-picki') ?>
                    </p>
                    <br><br>
                    <form method="post">
                        <?php settings_fields('wc-pickit-settings-onboarding'); ?>
                        <?php do_settings_sections('wc-pickit-settings-onboarding'); ?>

                        <label for="wc-pickit-api-key" required>
                            <?php echo __('Access Key', 'wc-pickit') ?>
                        </label>
                        <input type="text" name="accessKey" id="accessKey">
                        <label for="wc-pickit-api-country" required>
                            <?php echo __('País', 'wc-pickit') ?>
                        </label>
                        <select name="country" id="country">
                            <option value="" id="placeholder-country">Seleccione el país donde opera su tienda</option>
                            <option value="Argentina">Argentina</option>
                            <option value="Chile">Chile</option>
                            <option value="México">México</option>
                            <option value="Perú">Perú</option>
                            <option value="Uruguay">Uruguay</option>
                        </select>


                        <input type="submit" style="margin-top: 40px;" value="Ingresar" name="Ingresar"
                            id="submit-credentials">

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($_SESSION["token"]) && $_SESSION["token"] == true) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("OK");
            jQuery("#pickit-ok").css("display", 'block');
        });
    </script>
<?php } ?>
<?php if (isset($authorized) && $authorized == false) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("NOK");
            jQuery("#pickit-error").css("display", 'block');
        });
    </script>
<?php } ?>


<!-- <script>
    jQuery(document).ready(function ($) {
        var accessKey = document.getElementById("accessKey")
        var country = document.getElementById("country")
        $
        console.log(country, accessKey)

    })
</script> -->

<!-- 

<script>
    jQuery(document).ready(function ($) {
        $("#submit-credentials").click(function (event) {
            event.preventDefault();

            const accessKey = $("#accessKey").val();
            const country = $("#pais").val();

            var formData = {
                accessKey: accessKey,
                country: country
            };

            $.ajax({
                url: "http://localhost:3000/login",
                type: "POST",
                data: formData,
                success: function (data) {
                    // $.ajax({
                    //     url: window.location.href,
                    //     type: "POST",
                    //     data: { action: "action" },
                    //     success: function (data) {
                    //         console.log("post anidado")
                    //     },
                    //     error: function (xhr, status, error) {
                    //         console.log(status, error);
                    //     }
                    // });
                },
                error: function (xhr, status, error) {
                    console.log(status, error);
                }
            });
        });
    });
</script> -->