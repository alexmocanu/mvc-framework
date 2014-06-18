<?php

if (!defined('APP_MVC')) {
    throw new MVC_Exception('No direct script access allowed');
}

$config = array(
    'base_url'           => '/mvc/',
    'timezone'           => 'Europe/Bucharest',
    'default_controller' => 'Home_controller',
    'default_method'     => 'index',
    'database'           => array(
        'username' => 'root',
        'password' => '',
        'host'     => 'localhost',
        'database' => 'mvc',
    )
);