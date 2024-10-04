<?php
class database
{
    public $conn;
    function __construct(){

        $host       = config::getValue('db_host');
        $username   = config::getValue('db_user');
        $dbname     = config::getValue('db_name');
        $password   = config::getValue('db_pass');
    
        $this->conn = mysqli_connect($host, $username, $password, $dbname);

    }

    public function insert($query)
    {
        mysqli_query($this->conn, $query);
        return mysqli_insert_id($this->conn);
    }

    public function select($query, $isSingle = false)
    {
        $queryPrepare = mysqli_query($this->conn, $query);
        $results = mysqli_fetch_all($queryPrepare, MYSQLI_ASSOC);
        return ( $isSingle && isset($results[0]) ) ? $results[0] : $results;
    }

    public function update($query)
    {
        mysqli_query($this->conn, $query);
        return mysqli_affected_rows($this->conn);
    }

    public function delete($query)
    {
        mysqli_query($this->conn, $query);
    }

}
$db = new database();
$GLOBALS['db'] = $db;