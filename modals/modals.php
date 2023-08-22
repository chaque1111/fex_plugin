<?php
wp_enqueue_style('modal-styles', plugin_dir_url("fex.php") . 'fex/assets/css/modal-express.css');
session_start();
function agregar_modal_fex()
{
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $('body').on('change', 'input[name="shipping_method[0]"]', function () {
                var metodoEnvioSeleccionado = $(this).val();
                if (metodoEnvioSeleccionado === 'fex_express_shipping_method') {
                    //geolocalización 
                    if ("geolocation" in navigator) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            var latitude = position.coords.latitude;
                            var longitude = position.coords.longitude;
                            console.log("Latitud:", latitude);
                            console.log("Longitud:", longitude);
                            $.ajax({
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                type: 'POST',
                                data: {
                                    action: 'save_coordinates',
                                    latitude: latitude,
                                    longitude: longitude,
                                },
                                dataType: 'json',
                                success: function (response) {
                                    console.log('Respuesta exitosa:', response);
                                },
                                error: function (xhr, status, error) {
                                    console.log('Error:', error);
                                }
                            });

                        });
                    } else {
                        console.log("La geolocalización no está disponible en este navegador.");
                    }

                    // Mostrar el modal aquí
                    var modalContent = `
             
                          <form class="my-modal">
                           <div class="overlay"></div> 
                          <button class="close-button">x</button>
                            <h2 class="title-fex">Fex express</h2>
                            <p class="description-shipping">¡Tus productos llegan en 30 minutos en la ciudad de Santiago!</p>
                            <p class="p-fex">Elige un vehículo acorde a tus productos para calcular el precio</p>
                            <p class="p-fex">¡Recuerda que el precio se calcula según tu ubiación actual!</p>
                           <div class="contain-vehicles">
                                <div  class="contain-moto">
                                  <img class="vehicle-icon" style="width: 60px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/moto_fex.png') ?>">
                                   <label class="container">Moto
                                       <input class="input-vehicle" value="1" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "1") ? "checked" : ""; ?> type="radio" name="radio">
                                      <span class="checkmark"></span>
                              </div>
                              <div class="contain-opt">
                                  <img class="vehicle-icon" style="width: 105px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/auto_fex.png') ?>">
                                     <label class="container">Auto
                                       <input class="input-vehicle" value="2" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "2") ? "checked" : ""; ?> type="radio" name="radio">
                                        <span class="checkmark"></span>
                               </div>
                               <div class="contain-opt">
                                   <img class="vehicle-icon" style="width: 100px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/furgon_fex.png') ?>">
                                   <label class="container">Furgón
                                       <input class="input-vehicle" value="3" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "3") ? "checked" : ""; ?> type="radio" name="radio">
                                        <span class="checkmark"></span>
                               </div>
                                <div class="contain-opt">
                                  <img class="vehicle-icon" style="width: 150px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camioneta_fex.png') ?>">
                                  <label class="container">Camioneta
                                      <input class="input-vehicle" value="5" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "5") ? "checked" : ""; ?> type="radio" name="radio">
                                      <span class="checkmark"></span>
                              </div>
                              <div class="contain-opt">
                                   <img class="vehicle-icon" style="width: 155px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camion_abierto_fex.png') ?>">
                                  <label class="container">Camión abierto
                                       <input class="input-vehicle" value="7" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "7") ? "checked" : ""; ?> type="radio" name="radio">
                                      <span class="checkmark"></span>
                                                                  </div>
                              <div class="contain-opt">
                                    <img class="vehicle-icon" style="width: 105px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camion_cerrado_fex.png') ?>">
                                   <label class="container">Camión cerrado
                                         <input class="input-vehicle" value="8" <?php echo (isset($_SESSION["vehicle"]) && $_SESSION["vehicle"] === "8") ? "checked" : ""; ?> type="radio" name="radio">
                                         <span class="checkmark"></span>
                               </div>
                            </div>
                            <p class="p-fex">Debes darnos acceso a tu ubiación para poder calcular el precio del envío</p>
                        
                     
                            <div class="price-container"><h3 class="price-text">Precio: <span class="price">
                              <?php

                              if (isset($_SESSION["price"])) {
                                  echo "$" . $_SESSION["price"];
                              }
                              else {
                                  echo "$0";
                              }
                              ?>
                            </class=span></h3></div>
                            <p class="p-fex">El envío lo pagarás al repartidor de Fex cuando te entregue tus productos</p>
                            <p class="p-fex">Con efectivo o transferencia.</p>
                            <img style="width: 130px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/tarjetas.png') ?>"><br/>
                            <button class="confirm-button" disabled>Confirmar método de envío</button>
                          </form>
                    
                           `;
                    $('body').append(modalContent);
                    //cerrar modal
                    $('.close-button').click(function () {
                        event.preventDefault();
                        $('.my-modal').fadeOut(function () {
                            $(this).remove();
                        });
                    });
                    //calcular precio
                    $('.my-modal').change(function () {
                        event.preventDefault();
                        var valorSeleccionado = $('input[name="radio"]:checked').val();
                        const overlay = document.querySelector('.overlay');
                        const modal = document.querySelector('.my-modal');
                        overlay.style.display = 'block';

                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'calculate_shipping',
                                vehicle: valorSeleccionado
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response === "false") {
                                    // window.alert("Debes dar acceso a tu ubiación");
                                    // window.location.reload()     
                                } else {
                                    overlay.style.display = 'none';
                                    var h3Element = $(`<h3 class="price-text">Precio: <span class="price">$${response}</span></h3>`);
                                    $('.confirm-button').prop('disabled', false);
                                    // Vaciar el contenido existente del contenedor
                                    $('.price-container').empty();

                                    // Agregar el nuevo elemento h3 al contenedor
                                    $('.price-container').append(h3Element);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Error:', error);
                            }
                        });

                    });
                    //confirmar método de envío
                    $('.confirm-button').click(function () {
                        event.preventDefault();
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'save_config',
                            },
                            dataType: 'json',
                            success: function (response) {
                                console.log(response)
                                if (response === true) {

                                    window.location.reload()
                                    $('.my-modal').fadeOut(function () {
                                        $(this).remove();
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Error:', error);
                            }
                        });

                    });

                    $('.my-modal').fadeIn();
                }
            });
        });

    </script>
    <?php
}

add_action('wp_footer', 'agregar_modal_fex');