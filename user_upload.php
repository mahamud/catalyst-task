<?php

require_once ('config/config.php');
require_once ('app/helper.php');
require_once ('app/parser.php');
require_once ('lib/database.php');

echo 'Starting the Catalyst Task ... please wait ...'.PHP_EOL;
sleep(2);

echo 'Options passed are : '.PHP_EOL.PHP_EOL;

$arguments = processArguments($argv);
var_dump(print_r($arguments, true));
validateArguments($arguments);

$data = processDataFile(); //Initiate data file parsing

var_dump(print_r($data, true));

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

if(!empty($arguments['create_table'])){ //If this option passed from commandline
    createDatabaseTable($db);
    echo 'Table "Users" created. Please run script with other options to execute further tasks.'.PHP_EOL;
    endScript();
}

//var_dump($db->execute(DROP_TABLE_SQL));
if(doesTableExist($db, 'users') == false){
    echo 'Table "Users" does not exist. Execute script with --create_table option.'.PHP_EOL;
    endScript();
}

//Create the SQL for the clean data obtained. The system will be doing bulk insert rather than row by row.



// End the Execution here if not stopped before.
endScript();

