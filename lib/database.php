<?php

interface DatabaseConnectionInterface
{
    public function connect();
    public function execute($sql);
}

/**
 * Class MySqlConnection
 */
class MySqlConnection implements DatabaseConnectionInterface {
    //Implementation for a MySQL DB
    public function connect() {}
    public function execute($sql) {}
}


/**
 * Class PgSqlConnection
 */
class PgSqlConnection implements DatabaseConnectionInterface {

    private $_connection;
    public  $last_id;  //Last insert id
    public  $affected_rows; //Affected rows

    /**
     * @throws Exception
     */
    public function connect() {
        try {
            $this->_connection = pg_connect('host=DB_HOST port=DB_PORT dbname=DB_NAME user=DB_USER password=DB_PASS');
        }
        catch(Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param $sql
     * @return int
     */
    public function execute($sql)
    {
        $result = pg_query($this->db, $sql);
        if (pg_last_error()) exit(pg_last_error());
        $this->affected_rows = pg_affected_rows($result);
        return $this->affected_rows;
    }
}
