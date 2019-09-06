<?php

require_once ('config/config.php');
require_once ('app/helper.php');
require_once ('lib/database.php');

echo 'Starting the Catalyst Task ... please wait ...'.PHP_EOL;
sleep(2);

echo 'Options passed are : '.PHP_EOL.PHP_EOL;

$arguments = processArguments($argv);
var_dump(print_r($arguments, true));
validateArguments($arguments);
$databaseParameters = getDatabaseParameters($arguments);
var_dump(print_r($databaseParameters, true));

//Get the DB Handler
$db = getDatabaseConnection(new PgSqlConnection());

//Connect to Database
try {
    $db->connect($databaseParameters);
}
catch(Exception $exception){
    echo $exception->getMessage().PHP_EOL;
}

// End the Execution here if not stopped before.
endScript();

