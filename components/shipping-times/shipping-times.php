<?php
wp_enqueue_style('shipping-times', plugin_dir_url("fex.php") . 'fex/assets/css/shipping-times.css');
wp_enqueue_style('landing-styles', plugin_dir_url("fex.php") . 'fex/assets/css/onboarding.css');
include_once "times-functions.php";
?>

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
    <h1>Optimiza tus Envíos con Fex: Configuración de Horarios</h1>
    <p>Para brindarte la mejor experiencia de entrega, sabemos lo importante que es sincronizar tus horarios con los
        nuestros. Por eso, hemos diseñado un sistema que se adapta perfectamente a tu disponibilidad.</p>
    <p>Ésto nos ayuda a validar que los clientes de tu tienda no puedan programar envíos en horarios que tu tienda no
        esté operando y que Fex pase a recoger pedidos en los horarios correctos.</p>
    <div class="highlight">
        <p><strong>Horario de Envío Sincronizado:</strong> 8am - 10pm</p>
        <p>Nuestro horario de envío está diseñado para trabajar en sintonía con tus necesidades. Si tu tienda o
            ubicación de envío está operativa entre las 8:00 a.m. y las 10:00 p.m., estás de suerte. Hemos configurado
            de manera predeterminada este horario para tus envíos. Esto significa que no necesitas realizar ninguna
            configuración adicional si tus operaciones están dentro de este intervalo de tiempo.</p>
    </div>
    <p><strong>¿Cómo Funciona?</strong><br>Si tu tienda está disponible para operaciones entre las 8:00 a.m. y las 10:00
        p.m., nuestro sistema de envíos automáticamente ajustará los horarios de recogida y entrega en función de este
        rango. Puedes confiar en que tus envíos serán gestionados de manera eficiente y llegarán a su destino en los
        momentos óptimos.</p>

    <p>Si tu tienda no opera durante todo este horario, puedes especificar el horario en que comienza a operar y el
        horario en el que termina:</p>
    <p>Recuerda debes elegir horarios entre el rango de 8am y 22pm</p>
    <form method="post">
        <?php
        if (get_option("shipping_times_is_config")) {
            echo '<label for="horaInicio">Hora de Inicio:</label>';
            echo '<input type="time" id="horaInicio" name="horaInicio" min="08:00" max="22:00" value=' . get_option("shipping_times_min") . ' required ><br><br> ';
            echo '<label for="horaFin">Hora de Fin:</label>';
            echo '<input type="time" id="horaFin" name="horaFin" min="08:00"  value=' . get_option("shipping_times_max") . ' max="22:00" required><br><br>';
        }
        else {
            echo '<label for="horaInicio">Hora de Inicio:</label>';
            echo '<input type="time" id="horaInicio" name="horaInicio" min="08:00" max="22:00" required><br><br>';
            echo '<label for="horaFin">Hora de Fin:</label>';
            echo '<input type="time" id="horaFin" name="horaFin" min="08:00" max="22:00" required><br><br>';
        }
        ?>


        <button type="submit" class="button">Guardar Horarios</button>
    </form>
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