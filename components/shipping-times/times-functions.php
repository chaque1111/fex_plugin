<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["horaInicio"]) && isset($_POST["horaFin"])) {
        update_option("shipping_times_min", $_POST["horaInicio"]);
        update_option("shipping_times_max", $_POST["horaFin"]);
        update_option("shipping_times_is_config", 1);
           wp_redirect(admin_url('admin.php?page=shipping_times'));
    }
}
?>