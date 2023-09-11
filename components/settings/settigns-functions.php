<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
    session_start();
    session_destroy();
    // delete_option("access_key");
    // delete_option("shipping_zones_is_config");
    // delete_option("shipping_times_is_config");
    // delete_option("shipping_times_min");
    // delete_option("shipping_times_max");
    wp_redirect(admin_url('admin.php?page=fex_menu'));


    exit;
}
?>