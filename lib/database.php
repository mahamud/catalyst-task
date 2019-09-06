<?php

interface DatabaseConnectionInterface
{
    public function connect(array $parameters);
    public function execute($sql);
    public function getRows($sql);
    public function getConnection();
}

/**
 * Class MySqlConnection
 */
class MySqlConnection implements DatabaseConnectionInterface {
    //Implementation for a MySQL DB
    public function connect(array $parameters) {}
    public function execute($sql) {}
    public function getRows($sql){}
    public function getConnection(){}
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
     * @return mixed
     */
    public function getConnection(){
        return $this->_connection;
    }


    /**
     * @param $sql
     * @return array
     * @throws Exception
     */
    public function getRows($sql)
    {
        try {
            $result = @pg_query($this->_connection, $sql);
            if (pg_last_error()) exit(pg_last_error());
            $this->numRows = pg_num_rows($result);
            $rows = array();
            while ($item = pg_fetch_object($result)) {
                $rows[] = $item;
            }
            return $rows;
        }
        catch(Exception $exception){
            throw new Exception($exception->getMessage());
        }
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
