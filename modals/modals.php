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
                           <h2>Fex express</h2>
                           <p>¡Tus productos llegan en 30 minutos en la ciudad de Santiago!</p>
                           <p>Elige un vehiculo acorde a tus productos para calcular el precio</p>
                           <p>¡Recuerda que el precio se calcula según tu ubiación actual!</p>
                          <div class="contain-vehicles">
                               <div class="contain-opt">
                                 <img style="width: 70px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/moto_fex.png') ?>">
                                  <label class="container">Moto
                                      <input value="1" type="radio" checked="checked" name="radio">
                                     <span class="checkmark"></span>
                             </div>
                             <div class="contain-opt">
                                 <img style="width: 110px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/auto_fex.png') ?>">
                                    <label class="container">Auto
                                     <input value="2" type="radio" name="radio">
                                       <span class="checkmark"></span>
                              </div>
                              <div class="contain-opt">
                                  <img style="width: 110px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/furgon_fex.png') ?>">
                                  <label class="container">Furgón
                                      <input value="3" type="radio" name="radio">
                                       <span class="checkmark"></span>
                              </div>
                               <div class="contain-opt">
                                 <img style="width: 110px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camioneta_fex.png') ?>">
                                 <label class="container">Camioneta
                                     <input value="5" type="radio" name="radio">
                                     <span class="checkmark"></span>
                             </div>
                             <div class="contain-opt">
                                  <img style="width: 115px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camion_cerrado_fex.png') ?>">
                                 <label class="container">Camión abierto
                                      <input value="7" type="radio" name="radio">
                                     <span class="checkmark"></span>
                                                                 </div>
                             <div class="contain-opt">
                                   <img style="width: 115px" src="<?php echo esc_url(plugin_dir_url("fex.php") . 'fex/assets/img/camion_abierto_fex.png') ?>">
                                  <label class="container">Camión cerrado
                                        <input value="8" type="radio" name="radio">
                                        <span class="checkmark"></span>
                              </div>
                           </div>
                           <button class="btn btn-primary btn-option" data-option="3">confirmar</button>
                         </form>
                                                          `;

                    $('body').append(modalContent);

                    $('.btn-option').click(function () {
                        event.preventDefault();
                        var valorSeleccionado = $('input[name="radio"]:checked').val();
                        console.log("")
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'save_cookie',
                                vehicle: "uww"
                            },
                            dataType: 'json',
                            success: function (response) {
                                console.log('Respuesta exitosa:', response);
                            },
                            error: function (xhr, status, error) {
                                console.log('Error:', error);
                            }
                        });
                        // $('.my-modal').fadeOut(function() {
                        //     $(this).remove();
                        // });
                    });

                    $('.my-modal').fadeIn();
                }
            });
        });

    </script>
    <?php
}

add_action('wp_footer', 'agregar_modal_fex');