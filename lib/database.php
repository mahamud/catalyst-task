<?php

interface DatabaseConnectionInterface
{
    public function connect(array $parameters);
    public function execute($sql);
    public function getRows($sql);
}

/**
 * Class MySqlConnection
 */
class MySqlConnection implements DatabaseConnectionInterface {
    //Implementation for a MySQL DB
    public function connect(array $parameters) {}
    public function execute($sql) {}
    public function getRows($sql){}
}


/**
 * Class PgSqlConnection
 */
class PgSqlConnection implements DatabaseConnectionInterface {

    private $_connection;
    public  $lastID;  //Last insert id
    public  $affectedRows; //Affected rows
    public  $numRows; // Number of Rows


    /**
     * @param array $parameters
     * @throws Exception
     */
    public function connect(array $parameters) {
        try {
            $connString = "host=".$parameters['host']." port=".$parameters['port']." 
            dbname=".$parameters['dbname']." user=".$parameters['user']." password=".$parameters['password'];
            $this->_connection = @pg_connect($connString);
            if(empty($this->_connection)){
                throw new Exception('Failed to connect to database. Incorrect credentials.');
            }
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


    // SELECT Query
    // Returns an array of row objects
    // Gets number of rows
    public function getRows($sql)
    {
        $result = @pg_query($this->_connection, $sql);
        if (pg_last_error()) exit(pg_last_error());
        $this->numRows = pg_num_rows($result);
        $rows = array();
        while ($item = pg_fetch_object($result)) {
            $rows[] = $item;
        }
        return $rows;
    }


    /**
     * @param $sql
     * @return int
     * @throws Exception
     */
    public function execute($sql)
    {
        try {
            $result = @pg_query($this->_connection, $sql);
            if (pg_last_error()) exit(pg_last_error());
            $this->affectedRows = pg_affected_rows($result);
            return $this->affectedRows;
        }
        catch(Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
}
