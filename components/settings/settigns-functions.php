<?php
session_start();
session_destroy();
delete_option("shipping_zones_is_config");
delete_option("shipping_times_is_config");
delete_option("shipping_times_min");
delete_option("shipping_times_max");

?>