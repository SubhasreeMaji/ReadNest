<?php
/*
 * Plugin Name: ReadNest 
 * Description: Simple Login - Registration - Add/Edit/Delete profile - Logout functionalities with MySQL.
 * Version: 1.0
 * Author: Subhasree
 */

if(!defined('ABSPATH')){
    exit;
}


define(
    'CUP_PATH',
    plugin_dir_path(__FILE__)
);


require_once CUP_PATH.'includes/database.php';
require_once CUP_PATH.'includes/registration.php';
require_once CUP_PATH.'includes/login.php';
require_once CUP_PATH.'includes/profile.php';

register_activation_hook(
    __FILE__,
    'cup_create_table'
);

add_action('wp_enqueue_scripts', function () {

    wp_enqueue_script(
        'cup-register-js',
        plugin_dir_url(__FILE__) . 'assets/js/register.js',
        [],
        '1.0',
        true
    );

});

add_action('wp_enqueue_scripts', 'cup_enqueue_assets');

function cup_enqueue_assets() {

    wp_enqueue_style(
        'cup-style',
        plugin_dir_url(__FILE__) . 'assets/css/style.css',
        [],
        '1.0'
    );

}