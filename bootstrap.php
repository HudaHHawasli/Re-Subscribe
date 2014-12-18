<?php
/**
 * Plugin name: Re-Subscribe
 * Description: Modern modal for displaying newsletter subscribtion responsive modal
 * Version: 0.0.5
 * Author name: Ammar Alakkad
 * Author URI: http://aalakkad.me
 * License: MIT
 */

if (file_exists('vendor/autoload.php')) {
    include 'vendor/autoload.php';
}

$resubscribe = new Resubscribe();

register_activation_hook(__FILE__, ['ResubscribeModel', 'activation']);
