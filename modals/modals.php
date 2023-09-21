<?php
wp_enqueue_style('fex-modal-styles', plugin_dir_url("fex.php") . 'fex/assets/css/modal-express.css');
session_start();
$_SESSION["shipping_times_min"] = get_option("shipping_times_min");
$_SESSION["shipping_times_max"] = get_option("shipping_times_max");


function agregar_modal_fex()
{
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $('body').on('click', 'input[name="shipping_method[0]"]', function () {
                var metodoEnvioSeleccionado = $(this).val();
                if (metodoEnvioSeleccionado === 'fex_express_shipping_method') {
                    $('.fex-my-modal').empty();
                    // Mostrar el modal aquí
                    var modalContent = `
                     <form class="fex-my-modal">
                     <div class="fex-overlay"></div> 
                     <button class="fex-close-button">x</button>
                     <h2 class="fex-title-fex">Fex express</h2>
                     <p class="fex-description-shipping">¡Tus productos llegan en 30 minutos en la ciudad de Santiago!</p>
                         <p class="fex-p-fex">Elige un vehículo acorde a tus productos para calcular el precio</p>
                         <div class="fex-contain-vehicles">
                         <div  class="fex-contain-opt">
                         <img class="fex-vehicle-icon" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/moto_fex.png') ?>">
                     <label class="fex-container">Moto
                     <input class="fex-input-vehicle" required value="1" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "1") ? "checked" : ""; ?> type="radio" name="radio">
                     <span class="fex-checkmark"></span>
                     </div>
                     <div class="fex-contain-opt">
                     <img class="fex-vehicle-icon"  src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/auto_fex.png') ?>">
                     <label class="fex-container">Auto
                     <input class="fex-input-vehicle" value="2" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "2") ? "checked" : ""; ?> type="radio" name="radio">
                     <span class="fex-checkmark"></span>
                     </div>
                     <div class="fex-contain-opt">
                     <img class="fex-vehicle-icon"  src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/furgon_fex.png') ?>">
                     <label class="fex-container">Furgón
                     <input class="fex-input-vehicle" value="3" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "3") ? "checked" : ""; ?> type="radio" name="radio">
                     <span class="fex-checkmark"></span>
                       </div>
                       <div class="fex-contain-opt">
                       <img class="fex-vehicle-icon"  src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camioneta_fex.png') ?>">
                       <label class="fex-container">Camioneta
                       <input class="fex-input-vehicle" value="5" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "5") ? "checked" : ""; ?> type="radio" name="radio">
                       <span class="fex-checkmark"></span>
                       </div>
                       <div class="fex-contain-opt">
                       <img class="fex-vehicle-icon"  src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camion_abierto_fex.png') ?>">
                       <label class="fex-container">Camión abierto
                       <input class="fex-input-vehicle" value="7" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "7") ? "checked" : ""; ?> type="radio" name="radio">
                       <span class="fex-checkmark"></span>
                       </div>
                       <div class="fex-contain-opt">
                       <img class="fex-vehicle-icon"  src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camion_cerrado_fex.png') ?>">
                       <label class="fex-container">Camión cerrado
                       <input class="fex-input-vehicle" value="8" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "8") ? "checked" : ""; ?> type="radio" name="radio">
                       <span class="fex-checkmark"></span>
                       </div>
                       </div>
                        <p class="fex-p-fex">Ingresa tu dirección.</p>
                       <div class="fex-contain-address">
                       <label for="fex-pais-address">País:</label>
                        <select required id="fex-pais-address" name="pais">
                        <option value="Chile">Chile</option>
                        </select>
                        <label for="fex-region-adress">Región:</label>
                        <select required id="fex-region-adress" name="region">
                         <option disabled value="Araucanía">Araucanía</option>
                         <option disabled value="Arica y Parinacota">Arica y Parinacota</option>
                         <option disabled value="Atacama">Atacama</option>
                         <option disabled value="Aysén">Aysén</option>
                         <option disabled value="Biobío">Biobío</option>
                         <option disabled value="Coquimbo">Coquimbo</option>
                         <option disabled value="Los Lagos">Los Lagos</option>
                         <option disabled value="Los Ríos">Los Ríos</option>
                         <option value="Santiago">Santiago de Chile</option>
                         <option disabled value="Magallanes y de la Antártica Chilena">Magallanes y de la Antártica Chilena</option>
                         <option disabled value="Maule">Maule</option>
                         <option disabled value="Ñuble">Ñuble</option>
                         <option disabled value="O'Higgins">O'Higgins</option>
                         <option disabled value="Tarapacá">Tarapacá</option>
                         <option disabled value="Valparaíso">Valparaíso</option>
                        </select>
                        <label for="fex-comuna-address">Comuna:</label>
                        <?php if (isset($_SESSION["comuna"])) {
                            echo '<input  value="' . $_SESSION["comuna"] . '" required type="text" id="fex-comuna-address" name="comuna">';
                        }
                        else {
                            echo '<input required type="text" id="fex-comuna-address" name="comuna">';
                        }
                        ?>
                        <label for="fex-calle-address">Calle:</label>
                         <?php if (isset($_SESSION["calle"])) {
                             echo '<input  value="' . $_SESSION["calle"] . '" required type="text" id="fex-calle-address" name="calle">';
                         }
                         else {
                             echo '<input required type="text" id="fex-calle-address" name="calle">';
                         }
                         ?>
                       </div>
                       <button id="calculate-shipping-fex" class="fex-calculate-price" >Calcular precio</button>
                       <div class="fex-price-container"><h3 class="fex-price-text">Precio: <span class="fex-price">
                       <?php
                       if (isset($_SESSION["price"])) {
                           echo "$" . $_SESSION["price"];
                       }
                       else {
                           echo "$0";
                       }
                       ?>
                      </class=span></h3></div>
                      <p class="fex-p-fex">Horario de envío Express <?php echo $_SESSION["shipping_times_min"] . " AM - " . $_SESSION["shipping_times_max"] . " PM" ?></p>  
                      <button class="fex-confirm-button" disabled>Confirmar método de envío</button>
                      </form> `;
                    $('.fex-my-modal').fadeOut(function () {
                        $(this).remove();
                    });
                    $('body').append(modalContent);
                    //cerrar modal
                    $('.fex-close-button').click(function () {
                        event.preventDefault();
                        $('.fex-my-modal').fadeOut(function () {
                            $(this).remove();
                        });
                    });
                    //calcular precio
                    $('#calculate-shipping-fex').click(function (event) {
                        event.preventDefault()
                        //accediendo a los valores del formulario de cálculo
                        var pais = $('#fex-pais-address').val();
                        var region = $('#fex-region-adress').val();
                        var comuna = $('#fex-comuna-address').val();
                        var calle = $('#fex-calle-address').val();
                        //valor del vehículo
                        var valorSeleccionado = $('input[name="radio"]:checked').val();
                        const overlay = document.querySelector('.fex-overlay');
                        const modal = document.querySelector('.fex-my-modal');
                        overlay.style.display = 'block';

                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'calculate_shipping',
                                vehicle: valorSeleccionado,
                                pais: pais,
                                region: region,
                                comuna: comuna,
                                calle: calle,
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response === "false") {
                                    Swal.fire({
                                        title: "Rellenar todos los campos",
                                        text: "Por favor, complete todos los campos obligatorios.",
                                        icon: "warning",
                                        confirmButtonText: "Aceptar",
                                    });
                                    overlay.style.display = 'none';
                                } else {
                                    overlay.style.display = 'none';
                                    var h3Element = $(`<h3 class="fex-price-text">Precio: <span class="fex-price">$${response}</span></h3>`);
                                    $('.fex-confirm-button').prop('disabled', false);
                                    // Vaciar el contenido existente del contenedor
                                    $('.fex-price-container').empty();

                                    // Agregar el nuevo elemento h3 al contenedor
                                    $('.fex-price-container').append(h3Element);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Error:', error);
                            }
                        });

                    });
                    //confirmar método de envío
                    $('.fex-my-modal').submit(function () {
                        event.preventDefault();
                        var valorSeleccionado = $('input[name="radio"]:checked').val();
                        //valores de la dirección
                        var pais = $('#fex-pais-address').val();
                        var region = $('#fex-region-adress').val();
                        var comuna = $('#fex-comuna-address').val();
                        var calle = $('#fex-calle-address').val();
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'save_config',
                                vehicle: valorSeleccionado,
                                pais: pais,
                                region: region,
                                comuna: comuna,
                                calle: calle,
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response === true) {
                                    window.location.reload()
                                    $('.fex-my-modal').fadeOut(function () {
                                        $(this).remove();
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Error:', error);
                            }
                        });

                    });

                    $('.fex-my-modal').fadeIn();
                }
            });
        });

    </script>
    <?php
}

add_action('wp_footer', 'agregar_modal_fex_programado');

function agregar_modal_fex_programado()
{
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        jQuery(document).ready(function ($) {
            <?php
            if (isset($_SESSION["calle"]) && isset($_SESSION["comuna"])) {
                $direccion_form = $_SESSION["calle"] . ", " . $_SESSION["comuna"];
                $ciudad = $_SESSION["region"];
            }
            else {
                $direccion_form = "";
                $ciudad = "";
            }
            ?>
            if ("<?php echo $direccion_form ?>") {
                $("#billing_address_1").val("<?php echo $direccion_form ?>")
                $("#billing_city").val("<?php echo $ciudad ?>")
                $('#billing_country').val("CL")
                $('#billing_state').val("CL-RM")
            }

            $('body').on('click', 'input[name="shipping_method[0]"]', function () {
                var metodoEnvioSeleccionado = $(this).val();
                if (metodoEnvioSeleccionado === 'fex_programado_shipping_method') {
                    <?php
                    $nextMonth = new DateTime();
                    $nextMonth->modify('+1 month');
                    ?>
                    $('.fex-my-modal').empty();
                    // Mostrar el modal aquí
                    var modalContent = `
                    <form class="fex-my-modal">
                     <div class="fex-overlay"></div> 
                    <button class="fex-close-button">x</button>
                      <h2 class="fex-title-fex">Fex programado</h2>
                      <p class="fex-description-shipping">¡Programa la fecha y hora en la que quieres recibir tus productos!</p>
                      <p class="fex-p-fex">Elige un vehículo acorde a tus productos para calcular el precio</p>
                     <div class="fex-contain-vehicles">
                          <div  class="fex-contain-opt">
                            <img class="fex-vehicle-icon" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/moto_fex.png') ?>">
                             <label class="fex-container">Moto
                                 <input class="fex-input-vehicle" value="1" required <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "1") ? "checked" : ""; ?> type="radio" name="radio">
                                <span class="fex-checkmark"></span>
                        </div>
                        <div class="fex-contain-opt">
                            <img class="fex-vehicle-icon"  src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/auto_fex.png') ?>">
                               <label class="fex-container">Auto
                                 <input class="fex-input-vehicle" value="2" required <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "2") ? "checked" : ""; ?> type="radio" name="radio">
                                  <span class="fex-checkmark"></span>
                         </div>
                         <div class="fex-contain-opt">
                             <img class="fex-vehicle-icon"  src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/furgon_fex.png') ?>">
                             <label class="fex-container">Furgón
                                 <input class="fex-input-vehicle" value="3" required <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "3") ? "checked" : ""; ?> type="radio" name="radio">
                                  <span class="fex-checkmark"></span>
                         </div>
                          <div class="fex-contain-opt">
                            <img class="fex-vehicle-icon"  src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camioneta_fex.png') ?>">
                            <label class="fex-container">Camioneta
                                <input class="fex-input-vehicle" value="5" required <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "5") ? "checked" : ""; ?> type="radio" name="radio">
                                <span class="fex-checkmark"></span>
                        </div>
                        <div class="fex-contain-opt">
                             <img class="fex-vehicle-icon"  src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camion_abierto_fex.png') ?>">
                            <label class="fex-container">Camión abierto
                                 <input class="fex-input-vehicle" value="7" required <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "7") ? "checked" : ""; ?> type="radio" name="radio">
                                <span class="fex-checkmark"></span>
                                                            </div>
                        <div class="fex-contain-opt">
                              <img class="fex-vehicle-icon"  src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camion_cerrado_fex.png') ?>">
                             <label class="fex-container">Camión cerrado
                                   <input class="fex-input-vehicle" value="8" required <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "8") ? "checked" : ""; ?> type="radio" name="radio">
                                   <span class="fex-checkmark"></span>
                         </div>
                      </div>
                       <p class="fex-p-fex">Ingresa tu dirección.</p>
                         <div class="fex-contain-address">
                         <label for="fex-pais-address">País:</label>
                          <select required id="fex-pais-address" name="pais">
                          <option value="Chile">Chile</option>
                          </select>
                          <label for="fex-region-adress">Región:</label>
                          <select required id="fex-region-adress" name="region">
                           <option disabled value="Araucanía">Araucanía</option>
                           <option disabled value="Arica y Parinacota">Arica y Parinacota</option>
                           <option disabled value="Atacama">Atacama</option>
                           <option disabled value="Aysén">Aysén</option>
                           <option disabled value="Biobío">Biobío</option>
                           <option disabled value="Coquimbo">Coquimbo</option>
                           <option disabled value="Los Lagos">Los Lagos</option>
                           <option disabled value="Los Ríos">Los Ríos</option>
                           <option value="Santiago">Santiago de Chile</option>
                           <option disabled value="Magallanes y de la Antártica Chilena">Magallanes y de la Antártica Chilena</option>
                           <option disabled value="Maule">Maule</option>
                           <option disabled value="Ñuble">Ñuble</option>
                           <option disabled value="O'Higgins">O'Higgins</option>
                           <option disabled value="Tarapacá">Tarapacá</option>
                           <option disabled value="Valparaíso">Valparaíso</option>
                          </select>
                          <label for="fex-comuna-address">Comuna:</label>
                          <?php if (isset($_SESSION["comuna"])) {
                              echo '<input  value="' . $_SESSION["comuna"] . '" required type="text" id="fex-comuna-address" name="comuna">';
                          }
                          else {
                              echo '<input required type="text" id="fex-comuna-address" name="comuna">';
                          }
                          ?>
                              <label for="fex-calle-address">Calle:</label>
                               <?php if (isset($_SESSION["calle"])) {
                                   echo '<input  value="' . $_SESSION["calle"] . '" required type="text" id="fex-calle-address" name="calle">';
                               }
                               else {
                                   echo '<input required type="text" id="fex-calle-address" name="calle">';
                               }
                               ?>
                             </div>
                             <button id="calculate-shipping-fex" class="fex-calculate-price" >Calcular precio</button>
                          <div class="fex-price-container"><h3 class="fex-price-text">Precio: <span class="fex-price">                 
                      <?php
                      if (isset($_SESSION["price"])) {
                          echo "$" . $_SESSION["price"];
                      }
                      else {
                          echo "$0";
                      }
                      ?>
                      </class=span></h3></div>
                      <p class="fex-p-fex">Programa la fecha y hora en la que te llegará el producto</p>
                      <p class="fex-p-fex">Ingresa un horario entre las <?php echo $_SESSION["shipping_times_min"] . " AM - " . $_SESSION["shipping_times_max"] . " PM" ?></p>                  
                      <div class="contain-inputs-date-fex">
                      <?php
                      if (isset($_SESSION["programado"])) {
                          $selectedDate = new DateTime();
                          $nextDay = clone $selectedDate;
                          $nextDay->modify('+1 day');

                          $nextMonth = clone $nextDay;
                          $nextMonth->modify('first day of next month');

                          echo '<input id="date-fex" type="date" min=' . $nextDay->format('Y-m-d') . ' max=' . $nextMonth->format('Y-m-28') . ' value=' . $_SESSION["date"] . ' required/>';
                          echo '<input type="time" id="time-fex"   min=' . $_SESSION["shipping_times_min"] . ' max=' . $_SESSION["shipping_times_max"] . ' value=' . $_SESSION["time"] . ' required />';
                      }
                      else {
                          $selectedDate = new DateTime(); // Fecha actual por defecto
                          $nextDay = clone $selectedDate;
                          $nextDay->modify('+1 day');

                          $nextMonth = clone $nextDay;
                          $nextMonth->modify('first day of next month');

                          echo '<input id="date-fex" type="date" min=' . $nextDay->format('Y-m-d') . ' max=' . $nextMonth->format('Y-m-28') . ' value=' . $nextDay->format('Y-m-d') . ' required/>';
                          echo '<input type="time" id="time-fex"   min=' . $_SESSION["shipping_times_min"] . ' max=' . $_SESSION["shipping_times_max"] . ' value=' . $_SESSION["shipping_times_min"] . ' required />';
                      }

                      ?>
                           </div>
                          <input type="submit" class="fex-confirm-button" disabled value="Confirmar método de envío"/input>
                        </form>`;
                    $('.fex-my-modal').fadeOut(function () {
                        $(this).remove();
                    });
                    $('body').append(modalContent);

                    //validación de fechas
                    $('#date-fex').on('blur', function () {
                        //disable confirm-button false
                        $('.fex-confirm-button').prop('disabled', false);

                    });
                    $('#time-fex').on('blur', function () {
                        //disable confirm-button false
                        $('.fex-confirm-button').prop('disabled', false);

                    });

                    //cerrar modal
                    $('.fex-close-button').click(function () {
                        event.preventDefault();
                        $('.fex-my-modal').fadeOut(function () {
                            $(this).remove();
                        });
                    });
                    //calcular precio
                    $('#calculate-shipping-fex').click(function () {
                        event.preventDefault();
                        //accediendo a los valores del formulario de cálculo
                        var pais = $('#fex-pais-address').val();
                        var region = $('#fex-region-adress').val();
                        var comuna = $('#fex-comuna-address').val();
                        var calle = $('#fex-calle-address').val();
                        //accediendo a los valores del modal
                        var valorSeleccionado = $('input[name="radio"]:checked').val();
                        const overlay = document.querySelector('.fex-overlay');
                        const modal = document.querySelector('.fex-my-modal');
                        overlay.style.display = 'block';

                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'calculate_shipping',
                                vehicle: valorSeleccionado,
                                pais: pais,
                                region: region,
                                comuna: comuna,
                                calle: calle,
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response === "false") {
                                    Swal.fire({
                                        title: "Rellenar todos los campos",
                                        text: "Por favor, complete todos los campos obligatorios.",
                                        icon: "warning",
                                        confirmButtonText: "Aceptar",
                                    });
                                    overlay.style.display = 'none';
                                } else {
                                    overlay.style.display = 'none';
                                    var h3Element = $(`<h3 class="fex-price-text">Precio: <span class="fex-price">$${response}</span></h3>`);
                                    $('.fex-confirm-button').prop('disabled', false);
                                    // Vaciar el contenido existente del contenedor
                                    $('.fex-price-container').empty();
                                    // Agregar el nuevo elemento h3 al contenedor
                                    $('.fex-price-container').append(h3Element);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Error:', error);
                            }
                        });

                    });
                    //confirmar método de envío
                    $('.fex-my-modal').submit(function (event) {
                        event.preventDefault()
                        //valores de la dirección
                        var pais = $('#fex-pais-address').val();
                        var region = $('#fex-region-adress').val();
                        var comuna = $('#fex-comuna-address').val();
                        var calle = $('#fex-calle-address').val();
                        //valor del vehículo
                        var valorSeleccionado = $('input[name="radio"]:checked').val();
                        var date = $("#date-fex").val()
                        var time = $("#time-fex").val()
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'save_config',
                                vehicle: valorSeleccionado,
                                pais: pais,
                                region: region,
                                comuna: comuna,
                                calle: calle,
                                time: time,
                                date: date,
                                programado: `${date} ${time}:00`
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response === true) {
                                    window.location.reload()
                                    $('.fex-my-modal').fadeOut(function () {
                                        $(this).remove();
                                    });
                                } else {
                                    Swal.fire({
                                        title: "¡Calcula el precio!",
                                        icon: "warning",
                                        text: "Por favor, Calcula el precio del envío antes de confirmar.",
                                        confirmButtonText: "Aceptar",
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Error:', error);
                            }
                        });
                    });

                    $('.fex-my-modal').fadeIn();
                }
            });
        });

    </script>
    <?php
}

add_action('wp_footer', 'agregar_modal_fex');