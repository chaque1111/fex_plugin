<?php
include_once "dashboard-functions.php";
?>


<div class="dashboard-container">

    <div class="contain_data">
        <div class="contain-opt-data">
            <div class="contain-title-filters">
                <button type="submit" name="title-filter" class="title-filter" value="">Todos</button>
                <button class="title-filter" value="1">Por colectar</button>
                <button class="title-filter" value="4">En camino</button>
                <button class="title-filter" value="2">Entregados</button>
                <button class="title-filter" value="3">Con problemas</button>
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
                <H2 class="camp">#ORDER WOO</H2>
                <H2 class="camp">SEGUIMIENTO FEX</H2>
                <H2 class="camp">STATUS FEX</H2>
                <H2 class="camp">MODO</H2>
                <H2 class="camp">FECHA</H2>
                <H2 class="camp">DISTANCIA</H2>
                <H2 class="camp">TOTAL</H2>
            </div>

            <div class="contain-orders">

            </div>

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

<script>
    jQuery(document).ready(function ($) {
        $.ajax({
            url: "<?php echo 'https://naboo.holocruxe.com/flete/' . get_option("access_key"); ?>",
            type: "GET",
            dataType: "json",
            success: function (data) {
                // window.apiData = data;
                console.log(data)
                renderData(data);
            },
            error: function (xhr, status, error) {
                console.log("Error en la solicitud GET: " + status + ", " + error);
            }
        });
        $(".reload-icon").click(function () {
            $.ajax({
                url: "<?php echo 'https://naboo.holocruxe.com/flete/' . get_option("access_key"); ?>",
                type: "GET",
                dataType: "json",
                success: function (data) {
                    // window.apiData = data;
                    console.log(data)
                    renderData(data);
                },
                error: function (xhr, status, error) {
                    console.log("Error en la solicitud GET: " + status + ", " + error);
                }
            });
        });
        //filtrado
          $(".title-filter").click(function () {
            var valor = $(this).val();
            $.ajax({
                url: "<?php echo 'https://naboo.holocruxe.com/flete/' . get_option("access_key"); ?>?filtro="+valor,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    // window.apiData = data;
                    console.log(data)
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
            function claseStatus(estado) {
                if (estado === 0) return "fex-esperando";
                if (estado === 2) return "fex-aceptado";
                if (estado === 5) return "fex-pagado";
                if (estado === 8) return "fex-cargado";
                if (estado === 9) return "fex-terminando";
                if (estado === 10) return "fex-terminado";
                if (estado === 14) return "fex-no-transportista";
                if (estado === 16) return "fex-cancelado";
                if (estado === 14) return "fex-cacel-system"
            }
            // Iterar sobre los datos y crear los elementos HTML correspondientes
            $.each(data, function (index, item) {
                var newElement = $("<div>").addClass("order-fex");
                // Aquí puedes personalizar cómo se mostrará cada elemento del JSON en el HTML
                var wc_order = $("<h3>").text(`#${item.wc_order}`).addClass("order-woo");
                var numero_seg = $("<h3>").text(item.servicio).addClass("num-seg");
                var status_fex = $("<h3>").text(`${item.estado === 5 ? "pagado" : item.descripcion}`).addClass(claseStatus(item.estado));
                var modo = $("<h3>").text(item.tipo).addClass("modo-fex");
                var fecha = $("<h3>").text(item.fecha).addClass("fecha-fex");
                var distancia = $("<h3>").text(item.distancia).addClass("distancia-envio");
                var total = $("<h3>").text(`$${item.total}`).addClass("total-price-fex");
                // se agregan los nuevos elementos 
                newElement.append(wc_order, numero_seg, status_fex, modo, fecha, distancia, total);
                
                dataContainer.append(newElement);
            });
        }
    });
</script>


<?php if (!isset($_SESSION["authorized"]) || $_SESSION["authorized"] == false) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("NOK");
            jQuery("#pickit-error").css("display", 'block');
        });
    </script>
<?php } ?>
<?php if ($_SESSION["authorized"] == true && !get_option("shipping_zones_is_config")) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("NOK");
            jQuery("#pickit-sz-incomplete").css("display", 'block');
        });
    </script>
<?php } ?>
<?php if ($_SESSION["authorized"] == true && !get_option("shipping_times_is_config")) { ?>
    <script>
        jQuery(document).ready(function () {
            console.log("NOK");
            jQuery("#pickit-st-incomplete").css("display", 'block');
        });
    </script>
<?php } ?>

<!-- error logos -->


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


<!-- verificar si completó la configuración zonas -->
<div id="pickit-sz-incomplete" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <a href="">
            <span class="close">&times;</span>
        </a>
        <img src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/error.png') ?>">
        <h2>
            <?php echo __('Configuración incompleta', 'wc-pickit') ?>
        </h2>
        <p>
            <?php echo __('Necesitas configurar las Zonas de envío para comenzar a trabajar.', 'wc-pickit') ?>
        </p>

        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'shipping_zones')) ?>">
            <button id="button-error">
                <?php echo __('Aceptar', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>
<!-- verificar si completó la configuración horarios -->
<div id="pickit-st-incomplete" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <a href="">
            <span class="close">&times;</span>
        </a>
        <img src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/error.png') ?>">
        <h2>
            <?php echo __('Configuración incompleta', 'wc-pickit') ?>
        </h2>
        <p>
            <?php echo __('Necesitas configurar los Horarios de envío para comenzar a trabajar.', 'wc-pickit') ?>
        </p>

        <a href="<?php echo esc_url(admin_url('admin.php?page=' . 'shipping_times')) ?>">
            <button id="button-error">
                <?php echo __('Aceptar', 'wc-pickit') ?>
            </button>
        </a>
    </div>
</div>