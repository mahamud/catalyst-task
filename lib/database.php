<?php

interface DatabaseConnectionInterface
{
    public function connect(array $parameters);
    public function execute($sql);
}

/**
 * Class MySqlConnection
 */
class MySqlConnection implements DatabaseConnectionInterface {
    //Implementation for a MySQL DB
    public function connect(array $parameters) {}
    public function execute($sql) {}
}


/**
 * Class PgSqlConnection
 */
class PgSqlConnection implements DatabaseConnectionInterface {

    private $_connection;
    public  $lastID;  //Last insert id
    public  $affectedRows; //Affected rows


    /**
     * @param array $parameters
     * @throws Exception
     */
    public function connect(array $parameters) {
        try {
            $connString = "host=".$parameters['host']." port=".$parameters['port']." 
            dbname=".$parameters['dbname']." user=".$parameters['user']." password=".$parameters['password'];
            $this->_connection = pg_connect($connString);
        }
        catch(Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * Insert Method and returns last ID
     * @param $sql
     * @param string $id
     * @return string
     */
    public function insert($sql, $id='id')
    {
        $sql = rtrim($sql, ';');
        $sql .= ' RETURNING '.$id;
        $result = pg_query($this->_connection, $sql);
        if (pg_last_error()) exit(pg_last_error());
        $this->lastID = pg_fetch_result($result, 0);
        return $this->lastID;
    }

    /**
     * @param $sql
     * @return int
     */
    public function execute($sql)
    {
        $result = pg_query($this->_connection, $sql);
        if (pg_last_error()) exit(pg_last_error());
        $this->affectedRows = pg_affected_rows($result);
        return $this->affectedRows;
    }
}
