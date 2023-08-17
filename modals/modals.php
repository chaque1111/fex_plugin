<?php
wp_enqueue_style('landing-styles', plugin_dir_url("fex.php") . 'fex/assets/css/modal-express.css');
function agregar_modal_fex()
{
        ?>
        <script>
            jQuery(document).ready(function ($) {
                $('.cart-collaterals').on('change', 'input[name="shipping_method[0]"]', function () {
                    var metodoEnvioSeleccionado = $(this).val();
                    console.log(metodoEnvioSeleccionado)
                    if (metodoEnvioSeleccionado === 'fex_express_shipping_method') {
                        // Mostrar el modal aquí
                        var modalContent = `
                            <div class="my-modal">
                                <h2>Selecciona una opción y una hora</h2>
                                <button class="btn btn-primary btn-option" data-option="1">Opción 1</button>
                                <button class="btn btn-primary btn-option" data-option="2">Opción 2</button>
                                <button class="btn btn-primary btn-option" data-option="3">Opción 3</button>
                               
                            </div>
                        `;
                        $('body').append(modalContent);

                        $('.btn-option').click(function() {
                            var opcionSeleccionada = $(this).data('option');
                            var horaSeleccionada = $('#hora_seleccionada').val();

                            var horaMinima = '08:00';
                            var horaMaxima = '22:00';

                            if (horaSeleccionada < horaMinima || horaSeleccionada > horaMaxima) {
                                alert('Selecciona una hora entre las 8:00 y las 22:00.');
                                return;
                            }

                            console.log('Opción seleccionada: ' + opcionSeleccionada);
                            console.log('Hora seleccionada: ' + horaSeleccionada);
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
