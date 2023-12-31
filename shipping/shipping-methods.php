<?php

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) && get_option('shipping_zones_is_config') && get_option("shipping_times_is_config") && get_option("extra_commission_is_config")) {
    function express_shipping_method_init()
    {
        if (!class_exists('Fex_Express_Shipping_Method')) {
            class Fex_Express_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct()
                {
                    $this->id = 'fex_express_shipping_method'; // Id for your shipping method. Should be uunique.
                    $this->method_title = __('Fex express'); // Title shown in admin
                    $this->method_description = __('Fex express, tu producto llega en 30 minutos en la ciudad de Santiago.'); // Description shown in admin
                    $this->enabled = "yes"; // This can be added as an setting but for this example its forced enabled
                    $this->title = "Fex express"; // This can be added as an setting but for this example its forced.

                    $this->init();
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                    $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }
                /**
                 * calculate_shipping function.
                 *
                 * @access public
                 * @param array $package
                 * @return void
                 */

                public function calculate_shipping($package = array())
                {
                    $rate = array(
                        'label' => $this->title,

                        'calc_tax' => 'per_item'
                    );
                    // Register the rate
                    $this->add_rate($rate);


                }
            }
        }
    }

    add_action('woocommerce_shipping_init', 'express_shipping_method_init');
    function add_express_shipping_method($methods)
    {
        // Verifica si el envío gratuito está activo
        $free_shipping_enabled = false;

        // Obtén los métodos de envío disponibles
        $shipping_methods = WC()->shipping()->get_shipping_methods();

        // Busca el método de envío gratuito y verifica si está activo
        foreach ($shipping_methods as $shipping_method) {
            if ($shipping_method->id === 'free_shipping') {
                $free_shipping_enabled = $shipping_method->enabled === 'yes';
                break;
            }
        }

        if (!$free_shipping_enabled) {
            $methods['express_shipping_method'] = 'Fex_Express_Shipping_Method';
        }

        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_express_shipping_method');


    //programado

    function programado_shipping_method_init()
    {
        if (!class_exists('Fex_Programado_Shipping_Method')) {
            class Fex_Programado_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct()
                {
                    $this->id = 'fex_programado_shipping_method'; // Id for your shipping method. Should be uunique.
                    $this->method_title = __('Fex programado'); // Title shown in admin
                    $this->method_description = __('Fex programado, el cliente programa la fecha de entrega del producto.'); // Description shown in admin
                    $this->enabled = "yes"; // This can be added as an setting but for this example its forced enabled
                    $this->title = "Fex programado"; // This can be added as an setting but for this example its forced.

                    $this->init();
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                    $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }
                /**
                 * calculate_shipping function.
                 *
                 * @access public
                 * @param array $package
                 * @return void
                 */

                public function calculate_shipping($package = array())
                {

                    $rate = array(
                        'label' => $this->title,
                        'calc_tax' => 'per_item'
                    );
                    // Register the rate
                    $this->add_rate($rate);

                }
            }
        }
    }

    add_action('woocommerce_shipping_init', 'programado_shipping_method_init');

    function add_programado_shipping_method($methods)
    {
        // Verifica si el envío gratuito está activo
        $free_shipping_enabled = false;

        // Obtén los métodos de envío disponibles
        $shipping_methods = WC()->shipping()->get_shipping_methods();

        // Busca el método de envío gratuito y verifica si está activo
        foreach ($shipping_methods as $shipping_method) {
            if ($shipping_method->id === 'free_shipping') {
                $free_shipping_enabled = $shipping_method->enabled === 'yes';
                break;
            }
        }

        if (!$free_shipping_enabled) {
            $methods['programado_shipping_method'] = 'Fex_Programado_Shipping_Method';
        }

        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_programado_shipping_method');

}