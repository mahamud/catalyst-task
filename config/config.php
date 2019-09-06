<?php

define('ARGUMENT_SIZE', 6); //Argument Size
define('DATA_FILE_PATH', 'data/users.csv');
define('COLUMN_NUMBERS', 3);
define('BULK_INSERT_SIZE', 5);

//Database Credentials
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'postgres');
define('DB_PASS', 'test123');
define('DB_NAME', 'demo');
define('DB_PORT', '5432');

//Valid argument keys
const ARGUMENT_KEYS = array('file','create_table','dry_run', 'u', 'p', 'h');

//Due to time constraint, this part is hard coded. Otherwise, would have written script to dynamically create a table
const TABLE_CREATION_SQL = "CREATE TABLE users(
   user_id serial PRIMARY KEY,
   name VARCHAR (100) UNIQUE NOT NULL,
   surname VARCHAR (100) UNIQUE NOT NULL,
   email VARCHAR (100) UNIQUE NOT NULL
);
";
const DROP_TABLE_SQL = "DROP TABLE IF EXISTS users";
const TABLE_INDEX_CREATION = "CREATE INDEX email_users ON users(email)";
