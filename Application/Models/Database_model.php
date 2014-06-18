<?php

if (!defined('APP_MVC')) {
    throw new MVC_Exception('No direct script access allowed');
}

/**
 * Class Database_model
 *
 * Sample Database model
 */
class Database_model extends MVC
{
    public function __construct()
    {
        parent::__construct();
        $dbConfig = $this->config->database;

        if (!mysql_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password'])) {
            throw new MVC_Exception(mysql_error());
        }

        if (!mysql_select_db($dbConfig['database'])) {
            throw new MVC_Exception(mysql_error());
        }
    }

    /**
     * Run a SQL query and return it's results as an array
     * @param $query
     *
     * @return array
     * @throws MVC_Exception
     */
    public function query($query)
    {
        $result = array();
        $sql    = mysql_query($query);

        if (!$sql) {
            throw new MVC_Exception(mysql_error());
        }

        while ($q = mysql_fetch_assoc($sql)) {
            $result[] = $q;
        }

        mysql_free_result($sql);

        return $result;
    }

    /**
     * Run a SQL query and return a single result as an array
     *
     * @param $query
     *
     * @return mixed
     * @throws MVC_Exception
     */
    public function queryOne($query)
    {
        $result = $this->query($query);

        if (count($result) != 1) {
            throw new MVC_Exception("queryOne should return only one result. Found " . count($data));
        }

        return $result[0];
    }
}