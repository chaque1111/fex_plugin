<?php
include_once "dashboard-functions.php";

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
            <?php echo __('Las credenciales ingresadas son incorrectas.<br>Por favor, vuelve a intentarlo.', 'wc-pickit') ?>
        </p>

        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'fex_menu')) ?>">
            <button>
                <?php echo __('Aceptar', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>
<img class="fex-logo" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/fex_app.png') ?>">
<div class="dashboard-container">
    <div class="contain_options">
        <ul>
            <div class="contain_opt">
                <img class="icon_opt" src="<?php echo plugin_dir_url("fex.php") . 'fex/assets/icons/cubo.png'; ?>">
                <li class="title_opt"> Órdenes</li>
            </div>
            <div class="contain_opt">
                <img class="icon_opt" src="<?php echo plugin_dir_url("fex.php") . 'fex/assets/icons/camion.png'; ?>">
                <li class="title_opt">Puntos de despacho</li>
            </div>
            <div class="contain_opt">
                <img class="icon_opt" src="<?php echo plugin_dir_url("fex.php") . 'fex/assets/icons/marcador.png'; ?>">
                <li class="title_opt">Puntos de entrega</li>
            </div>
        </ul>
    </div>
    <div class="contain_data">
        <div class="contain-opt-data">
            <div class="contain-title-filters">
                <button type="submit" name="title-filter" class="title-filter" value="title-filter">Todos</button>
                <button class="title-filter" value="colectar">Por colectar</button>
                <button class="title-filter" value="entregados">Entregados</button>
                <button class="title-filter" value="problemas">Con problemas</button>
            </div>
            <div class="contain-seconds-filter">
                <div class="contain-searchbar">
                    <input placeholder="Buscar por #número de seguimiento" class="search-bar" type="text" name="" id="">
                    <img class="search-image"
                        src="<?php echo plugin_dir_url("fex.php") . 'fex/assets/icons/lupa.png'; ?>">
                </div>
                <input placeholder="Filtrar por fecha" class="input-date" type="text">
                <img class="reload-icon"
                    src="<?php echo plugin_dir_url("fex.php") . 'fex/assets/icons/recargar.png'; ?>">
            </div>
            <div class="container-camps">
                <H2 class="camp">#ORDEN DE WOO</H2>
                <H2 class="camp">NÚMERO DE SEGUIMIENTO</H2>
                <H2 class="camp">STATUS FEX</H2>
                <H2 class="camp">MODO</H2>
                <H2 class="camp">FECHA</H2>
                <H2 class="camp">PAGO</H2>
            </div>

            <div class="contain-orders">

            </div>
            <?php

            // foreach ($arrayPersonas as $orden) {
            //     echo '<div class="contain-orders">';
            //     echo '<div class="order">';
            //     echo '<h3 class="camp-order-woo">' . $orden->orden_woo . '</h3>';
            //     echo '<h3>' . $orden->numero_seg . '</h3>';
            //     echo '<h3>' . $orden->status_fex . '</h3>';
            //     echo '<h3>' . $orden->modo . '</h3>';
            //     echo '<h3>' . $orden->fecha . '</h3>';
            //     echo '<h3>' . $orden->pago . '</h3>';
            //     echo '</div>';
            //     echo '</div>';
            // }
            ?>

        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {
        // When the user clicks on <span> (x), close the modal
        $(".search-image").on("click", function () {

            $(".search-bar").val("")
        })
        $(".search-bar").on("keypress", function (event) {
            // Verificar si la tecla presionada es Enter (código 13)
            if (event.which === 13 && $(this).val().trim() !== "") {
                // Ejecutar el código que deseas cuando se cumplan ambas condiciones


                $(this).val("")
            }
        });
    });
</script>

<?php if (!isset($_SESSION["authorized"]) || $_SESSION["authorized"] == false) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("NOK");
            jQuery("#pickit-error").css("display", 'block');
        });
    </script>
<?php }
else { ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                url: "http://localhost:3001/flete",
                type: "GET",
                dataType: "json",
                success: function (data) {
                    // window.apiData = data;

                    renderData(data);
                },
                error: function (xhr, status, error) {
                    console.log("Error en la solicitud GET: " + status + ", " + error);
                }
            });
            $(".reload-icon").click(function () {
                $.ajax({
                    url: "http://localhost:3001/flete",
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        // window.apiData = data;

                        renderData(data);
                    },
                    error: function (xhr, status, error) {
                        console.log("Error en la solicitud GET: " + status + ", " + error);
                    }
                });
            });
            function renderData(data) {
                // Obtener el contenedor donde se mostrarán los datos
                var dataContainer = $(".contain-orders");

                // Limpiar el contenido actual del contenedor
                dataContainer.empty();

                // Iterar sobre los datos y crear los elementos HTML correspondientes
                $.each(data, function (index, item) {
                    var newElement = $("<div>").addClass("order");
                    // Aquí puedes personalizar cómo se mostrará cada elemento del JSON en el HTML
                    var orden_woo = $("<h3>").text(item.orden_woo);
                    var numero_seg = $("<h3>").text(item.numero_seg);
                    var status_fex = $("<h3>").text(item.status_fex);
                    var modo = $("<h3>").text(item.modo);
                    var fecha = $("<h3>").text(item.fecha);
                    var pago = $("<h3>").text(item.pago);
                    // se agregan los nuevos elementos 
                    newElement.append(orden_woo, numero_seg, status_fex, modo, fecha, pago);
                    dataContainer.append(newElement);
                });
            }
        });


    </script>
<?php } ?>