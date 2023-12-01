<?php
include_once "extra-function.php";
?>


<div id="pickit-ok" class="modal">
    <div class="modal-content">

        <img src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/ok.png') ?>">

        <h2>
            <?php echo __('Comisión extra configurada', 'wc-pickit') ?>
        </h2>
        <p>
            <?php echo __('Has configurado correctamente la comisión extra', 'wc-pickit') ?>
        </p>
        <p>
            <?php echo __('Puedes Configurar la <strong>Comisión Extra</strong> las veces que quieras.', 'wc-pickit') ?>
        </p>
        <button style="background: black; " id="button-reconfigure">
            <?php echo __('Configurar Comisión', 'wc-pickit') ?>
        </button>
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'submenu_dashboard')) ?>">
            <button>
                <?php echo __('Ir al Panel', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>

<?php if ($_SESSION["authorized"] == true && get_option("extra_commission_is_config")) { ?>
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

<?php if (!isset($_SESSION["authorized"]) || $_SESSION["authorized"] == false) {
    wp_redirect(admin_url('admin.php?page=fex_menu'));
} ?>


<div id="fexContainerCommission">
    <h1 id="fexTitleCommission">Comisión Extra</h1>
    <p id="descripcionComision">
        Esta sección del menú del plugin de FEX Envíos te permite agregar un porcentaje de ganancia adicional al total
        del envío de cada compra realizada con el método de envío FEX Express o FEX Programado.
        Esta ganancia será destinada al dueño de la tienda de WooCommerce.
    </p>
    <div class="highlight">
        <p><strong>Nota Importante:</strong> recuerda que solo es posible seleccionar una configuración,
            la cual se sumará al precio total de cada envío realizado con Fex. Además, puedes cambiar
            entre porcentaje y un precio fijo las veces que quieras.</p>
    </div>

    <div id="commission-form">
        <form method="post" id="form-porcentaje">
            <p class="fex-commission"><strong>Porcentaje Adicional: </strong>Ingresa un porcentaje (por ejemplo, 5%)
                para
                agregar un monto adicional al costo total del envío. Este método es ideal si prefieres una comisión que
                se
                ajuste en términos de un porcentaje del precio de envío.
            </p>
            <div style="margin-top: 20px;">
                <label for='porcentaje'>Porcentaje:</label>
                <?php
                if (get_option("extra_porcentage")) {
                    echo "<input  value='" . get_option("extra_porcentage") * 100 . "' min='0' max='100' type='number' name='porcentaje' id='porcentaje' required>";
                }
                else {
                    echo "<input min='0' max='100' type='number' name='porcentaje' id='porcentaje' required>";
                }
                ?>
            </div><br>
            <button type="submit" class="submit-commission">Configurar Porcentaje</button>
        </form>
        <form method="post" id="form-precio">
            <p class="fex-commission"><strong>Precio Fijo Adicional: </strong>Establece un precio fijo adicional (por
                ejemplo,
                $2.00) que se sumará directamente al costo total del envío. Esta opción es perfecta si prefieres una
                comisión
                extra en términos de una cantidad específica.
            </p>
            <div style="margin-top: 20px;"> <label for="precio">Precio Fijo:</label>
                <?php
                if (get_option("extra_price")) {
                    echo "<input value='" . get_option("extra_price") . "' min='0' type='number' name='precio' id='precio' required>";
                }
                else {
                    echo "<input min='0' type='number' name='precio' id='precio' required>";
                }
                ?>
            </div> <br>
            <!-- <p class="alert-commission">¡Si decidiste elegir precio fijo como comisión extra presiona "Configurar Precio
            Fijo"!
        </p> -->
            <button type="submit" class="submit-commission">Configurar Precio Fijo</button>
        </form>
    </div>

</div>