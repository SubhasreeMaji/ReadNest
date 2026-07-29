<?php


register_activation_hook(
    __DIR__.'/../index.php',
    'cup_create_table'
);


function cup_create_table(){

global $wpdb;


$table = $wpdb->prefix.'custom_users';


$charset = $wpdb->get_charset_collate();


$sql = "CREATE TABLE $table (

id INT NOT NULL AUTO_INCREMENT,

name VARCHAR(100),

email VARCHAR(150) UNIQUE,

password VARCHAR(255),

created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

PRIMARY KEY(id)

)$charset;";


require_once ABSPATH.'wp-admin/includes/upgrade.php';


dbDelta($sql);

}