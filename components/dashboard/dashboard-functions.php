<?php
wp_enqueue_style('dashboard-styles', plugin_dir_url("fex.php") . 'fex/assets/css/panel.css');
wp_enqueue_style('landing-styles', plugin_dir_url("fex.php") . 'fex/assets/css/onboarding.css');
session_start();

// session_destroy();
// echo $_SESSION["token"];
?>