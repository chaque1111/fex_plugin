<?php
wp_enqueue_style('modal-styles', plugin_dir_url("fex.php") . 'fex/assets/css/modal-express.css');

function agregar_modal_fex()
{
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $('.cart-collaterals').on('change', 'input[name="shipping_method[0]"]', function () {
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
                      <button class="close-button">x</button>
                        <h2 class="title-fex">Fex express</h2>
                        <p class="description-shipping">¡Tus productos llegan en 30 minutos en la ciudad de Santiago!</p>
                        <p class="p-fex">Elige un vehiculo acorde a tus productos para calcular el precio</p>
                        <p class="p-fex">¡Recuerda que el precio se calcula según tu ubiación actual!</p>
                       <div class="contain-vehicles">
                            <div  class="contain-moto">
                              <img class="vehicle-icon" style="width: 60px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/moto_fex.png') ?>">
                               <label class="container">Moto
                                   <input class="input-vehicle" value="1" type="radio" checked="checked" name="radio">
                                  <span class="checkmark"></span>
                          </div>
                          <div class="contain-opt">
                              <img class="vehicle-icon" style="width: 105px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/auto_fex.png') ?>">
                                 <label class="container">Auto
                                  <input class="input-vehicle" value="2" type="radio" name="radio">
                                    <span class="checkmark"></span>
                           </div>
                           <div class="contain-opt">
                               <img class="vehicle-icon" style="width: 100px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/furgon_fex.png') ?>">
                               <label class="container">Furgón
                                   <input class="input-vehicle" value="3" type="radio" name="radio">
                                    <span class="checkmark"></span>
                           </div>
                            <div class="contain-opt">
                              <img class="vehicle-icon" style="width: 150px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camioneta_fex.png') ?>">
                              <label class="container">Camioneta
                                  <input class="input-vehicle" value="5" type="radio" name="radio">
                                  <span class="checkmark"></span>
                          </div>
                          <div class="contain-opt">
                               <img class="vehicle-icon" style="width: 105px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camion_cerrado_fex.png') ?>">
                              <label class="container">Camión abierto
                                   <input class="input-vehicle" value="7" type="radio" name="radio">
                                  <span class="checkmark"></span>
                                                              </div>
                          <div class="contain-opt">
                                <img class="vehicle-icon" style="width: 155px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camion_abierto_fex.png') ?>">
                               <label class="container">Camión cerrado
                                     <input class="input-vehicle" value="8" type="radio" name="radio">
                                     <span class="checkmark"></span>
                           </div>
                        </div>
                        <p class="p-fex">Debes darnos acceso a tu ubiación para poder calcular el precio del envío</p>
                        
                        <button class="calculate-price" data-option="3">calcular envío</button>
                        <div class="price-container"><h3 class="price-text">Precio: <span class="price">calcula el precio</class=span></h3></div>
                        <p class="p-fex">El envío lo pagarás al repartidor de Fex cuando te entregue tus productos</p>
                        <p class="p-fex">Con efectivo o transferencia.</p>
                        <img style="width: 130px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/tarjetas.png') ?>"><br/>
                        <button class="confirm-button" >Confirmar método de envío</button>
                      </form>
                       `;

                    $('body').append(modalContent);
                    //cerrar modal
                     $('.close-button').click(function () {
                        event.preventDefault();
                        $('.my-modal').fadeOut(function() {
                            $(this).remove();
                        });
                    });
                    //calcular precio
                    $('.calculate-price').click(function () {
                        event.preventDefault();
                        var valorSeleccionado = $('input[name="radio"]:checked').val();
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'save_cookie',
                                vehicle: valorSeleccionado
                            },
                            dataType: 'json',
                            success: function (response) {
                                var h3Element = $(`<h3 class="price-text">Precio: <span class="price">$${response["resultado"]["total"]}</span></h3>`);
            
                                // Vaciar el contenido existente del contenedor
                                $('.price-container').empty();
            
                                // Agregar el nuevo elemento h3 al contenedor
                                $('.price-container').append(h3Element);
                            },
                            error: function (xhr, status, error) {
                                console.log('Error:', error);
                            }
                        });
                       
                    });
                    //confirmar método de envío
                       $('.confirm-button').click(function () {
                        event.preventDefault();
                        $('.my-modal').fadeOut(function() {
                            $(this).remove();
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