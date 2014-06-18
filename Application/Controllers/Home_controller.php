<?php

if (!defined('APP_MVC')) {
    throw new MVC_Exception('No direct script access allowed');
}

/**
 * Class Home_controller
 * @property Database_model $db Instance of the Database model
 */
class Home_controller extends MVC
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database_model();
    }

    public function index()
    {
        $this->loadView(
            'home',
            array(
                'var1'   => 'variable1',
                'config' => $this->config,
            )
        );
    }
}