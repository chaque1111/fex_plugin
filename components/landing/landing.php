<?php
wp_enqueue_style('landing-styles', plugin_dir_url("fex.php") . 'fex/assets/css/onboarding.css');
include_once "landing-functions.php";
?>

<div class="pickit">

    <div class="pickit-container">
        <div class="row">
            <div class="column30">
                <div class="left-pane">
                    <img style="width: 15vw;"
                        src="<?php echo plugin_dir_url("fex.php") . 'fex/assets/img/fex.app.png' ?>">
                    <h1 class="welcome">
                        <?= sprintf(__('¡Hola! Bienvenido a FEX', 'wc-pickit'), get_option('blogname')) ?>
                    </h1>
                    <p>
                        <strong>Gestiona tus despachos rápidamente y entrega a tus clientes en 90min.</strong><br>
                        Con este plugin podrás conectar FEX como tu partner de envíos rápidos en tu tienda de
                        WooCommerce. Para continuar con el proceso de configuración, es necesario que cuentes con una
                        cuenta en FEX. Si aún no la tienes crea tu cuenta aquí
                        <?php echo __("<a class='create_account_link' href='https://fex.cl/fex_api/usuario/index/inicio' target='_blank'> Crea tu cuenta aquí.</a>", 'wc-pickit') ?>
                    </p>

                </div>
            </div>
            <div class="column70">
                <div class="right-pane">
                    <h2>
                        <?php echo __('Ingresá tus credenciales de Fex', 'wc-picki') ?>
                    </h2>
                    <p>
                        <?php echo __('¿No conocés tu Access Key? <a class="contact_link" href="https://fex.cl/index.php#support" target="_blank" >Contáctanos</a>', 'wc-picki') ?>
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
                            <!-- <option value="Argentina">Argentina</option> -->
                            <option value="Chile">Chile</option>
                            <!-- <option value="México">México</option>
                            <option value="Perú">Perú</option>
                            <option value="Uruguay">Uruguay</option> -->
                        </select>


                        <input type="submit" style="margin-top: 40px;" value="Ingresar" name="Ingresar"
                            id="submit-credentials">

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- redirección a zona de envíos -->
<?php if (isset($_SESSION["token"]) && $_SESSION["token"] == true && !get_option("shipping_times_is_config")) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("OK");
            jQuery("#pickit-ok-2").css("display", 'block');
        });
    </script>
<?php } ?>
<!-- redirección a zona de envíos -->
<?php if (isset($_SESSION["token"]) && $_SESSION["token"] == true && !get_option("shipping_zones_is_config")) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("OK");
            jQuery("#pickit-ok").css("display", 'block');
        });
    </script>
<?php } ?>



<!-- redirección a dashboard -->
<?php if (isset($_SESSION["token"]) && $_SESSION["token"] == true && get_option("shipping_zones_is_config") && get_option("shipping_times_is_config")) { ?>
    <?php wp_redirect(admin_url('admin.php?page=submenu_dashboard')); ?>
    <!-- <script>
        jQuery(document).ready(function () {
            console.log("OK");
            jQuery("#pickit-ok-3").css("display", 'block');
        });
    </script> -->
<?php } ?>
<!-- credenciales incorrectas -->
<?php if (isset($authorized) && $authorized == false) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("NOK");
            jQuery("#pickit-error").css("display", 'block');
        });
    </script>
<?php } ?>


<!-- modal horarios de envíos -->
<div id="pickit-ok-2" class="modal">
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
            <?php echo __('Ahora necesitas <strong>configurar los horarios de envío</strong>. Para esto, dirígete a <strong>Fex > Horarios de Envío</strong>', 'wc-pickit') ?>
        </p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'shipping_times')) ?>">
            <button class="button-succes">
                <?php echo __('Ir a Horarios de envío', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>
<!-- modals zona de envíos -->
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
            <?php echo __('Ahora necesitas <strong>configurar los puntos de retiro</strong>. Para esto, dirígete a <strong>Fex > Zonas de Envíos</strong>', 'wc-pickit') ?>
        </p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'shipping_zones')) ?>">
            <button>
                <?php echo __('Ir a Zona de envíos', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>


<!-- dashboard -->
<div id="pickit-ok-3" class="modal">
    <div class="modal-content">
        <a href="">
            <span class="close">&times;</span>
        </a>
        <img src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/ok.png') ?>">


        <p>
            <?php echo __('Has finalizado la configuración.', 'wc-pickit') ?>
        </p>
        <p>
            <?php echo __('Ahora puedes <strong>ir al panel de Fex</strong>.Para esto, dirígete a <br/> <strong>Fex > Panel de control</strong>', 'wc-pickit') ?>
        </p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'submenu_dashboard')) ?>">
            <button>
                <?php echo __('Ir al Panel', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>

<!-- error -->
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