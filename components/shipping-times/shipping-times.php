<?php
wp_enqueue_style('shipping-times', plugin_dir_url("fex.php") . 'fex/assets/css/shipping-times.css');
wp_enqueue_style('landing-styles', plugin_dir_url("fex.php") . 'fex/assets/css/onboarding.css');
include_once "times-functions.php";
?>

<!-- Reconfigurar -->
<div id="pickit-ok" class="modal">
    <div class="modal-content">

        <img src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/ok.png') ?>">

        <h2>
            <?php echo __('Horarios de envíos configurado', 'wc-pickit') ?>
        </h2>
        <p>
            <?php echo __('Has configurado correctamente los horarios de envío.', 'wc-pickit') ?>
        </p>
        <p>
            <?php echo __('Puedes <strong>Configurar los horarios de envío las veces que quieras</strong>.', 'wc-pickit') ?>
        </p>
        <button style="background: black; " id="button-reconfigure">
            <?php echo __('Configurar Horarios', 'wc-pickit') ?>
        </button>
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'submenu_dashboard')) ?>">
            <button>
                <?php echo __('Ir al Panel', 'wc-pickit') ?>
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
    <div class="title-container">
        <h1 class="time-title">Configuración de Horarios</h1>
        <p>Para brindarte la mejor experiencia de entrega, sabemos lo importante que es sincronizar tus horarios con los
            nuestros. Por eso, hemos diseñado un sistema que se adapta perfectamente a tu disponibilidad.</p>
        <p class="fex-times"><strong>Horario de Envío Fex:</strong> 08:00 AM - 22:00 PM</p>
        <p><strong>¿Cómo Funciona?</strong><br>Si tu tienda no opera durante todo este horario, puedes
            especificar el
            horario en que comienza a operar y el
            horario en el que termina, cuando guardes la configuración nuestro sistema ajustará los horarios de recogida
            y
            entrega en función de este
            rango. Puedes confiar en que tus envíos serán gestionados de manera eficiente y llegarán a su destino en los
            momentos óptimos.</p>
        <p>Ésta configuración nos ayuda a validar que los clientes de tu tienda Woo no puedan programar envíos en
            horarios
            que tu tienda
            no
            esté operando y que Fex pase a recoger pedidos en los horarios correctos.</p>

    </div>

    <div class="highlight">
        <p><strong>Horario de Envío Fex:</strong> 08:00 AM - 22:00 PM</p>
        <p>Si tu horario de atención coincide con el de Fex (08:00 - 22:00), deja los valores por defecto.</p>
    </div>

    <form class="form-times" method="post">
        <div class="contain-inputs">
            <?php
            if (get_option("shipping_times_is_config")) {
                $horaInicioValue = get_option("shipping_times_min");
                $horaFinValue = get_option("shipping_times_max");
            }
            else {
                $horaInicioValue = "08:00";
                $horaFinValue = "22:00";
            }
            ?>

            <label for="horaInicio">Hora de Inicio:</label>
            <input type="time" id="horaInicio" name="horaInicio" min="08:00" max="22:00"
                value="<?php echo $horaInicioValue; ?>" required><br><br>

            <label for="horaFin">Hora de Fin:</label>
            <input type="time" id="horaFin" name="horaFin" min="08:00" max="22:00" value="<?php echo $horaFinValue; ?>"
                required><br><br>

        </div>
        <p class="alert-times">¡Si los horarios ingresados son correctos presiona el botón "Guardar Horarios"!</p>
        <button type="submit" class="submit-times">Guardar Horarios</button>
    </form>
</div>

<?php if ($_SESSION["authorized"] == true && get_option("shipping_times_is_config")) { ?>
    <script>
        jQuery(document).ready(function ($) {
            jQuery("#pickit-ok").css("display", 'block');
            $("#button-reconfigure").click(function () {
                jQuery("#pickit-ok").css("display", 'none');
            })
        });
    </script>
<?php } ?>


<?php if (!isset($_SESSION["authorized"]) || $_SESSION["authorized"] == false) {
    wp_redirect(admin_url('admin.php?page=fex_menu'));
}
?>