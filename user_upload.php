<?php

require_once ('config/config.php');
require_once ('app/helper.php');
require_once ('app/parser.php');
require_once ('lib/database.php');

echo 'Starting the Catalyst Task ... please wait ...'.PHP_EOL;
sleep(2);

$arguments = processArguments($argv);
validateArguments($arguments);
var_dump($arguments);
$data = processDataFile(); //Initiate data file parsing
$data['clean'] = removeDuplicateValuesFromArray($data['clean'], 'email');

$databaseParameters = getDatabaseParameters($arguments);

//Get the DB Handler
$db = getDatabaseConnection(new PgSqlConnection());

//Connect to Database
try {
    $db->connect($databaseParameters);
}
catch(Exception $exception){
    echo $exception->getMessage().PHP_EOL;
    endScript();
}

//Validation based on options passed from command line
if(!empty($arguments['create_table'])){ //If this option passed from commandline, only the table will be created and the script will stop here
    createDatabaseTable($db);
    echo 'Table "Users" created. Please run script with other options to execute further tasks.'.PHP_EOL;
    endScript();
}

//Verifying if database table exists. If not, exit with error message
if(doesTableExist($db, 'users') == false){
    echo 'Table "Users" does not exist. Execute script with --create_table option.'.PHP_EOL;
    endScript();
}

//Create the SQL for the clean data obtained. The system will be doing bulk insert rather than row by row.
try {
    $dryRun = false;
    if(!empty($arguments['dry_run']) && $arguments['dry_run'] == true){
        $dryRun = true;
    }
    if($dryRun == false) {
        try {
            addDataToDatabase($db, $data['clean']);
        }
        catch(Exception $exception){
            echo $exception->getMessage().PHP_EOL;
            endScript();
        }
    }else{
        echo "Records to be inserted : ".print_r($data['clean'], true).PHP_EOL;
        echo "This was a Dry Run. No record was inserted";
    }
}
catch(Exception $exception){
    echo $exception->getMessage().PHP_EOL;
    endScript();
}

//Error Report
if(!empty($data['errors'] && is_array($data['errors']) && sizeof($data['errors']) > 0)){
    foreach ($data['errors'] as $error){
        echo $error.PHP_EOL;
    }
    echo PHP_EOL;
}

// End the Execution here if not stopped before.
endScript();

