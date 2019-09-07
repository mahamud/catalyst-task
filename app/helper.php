<?php

/**
 * Method to process Arguments
 *
 * @param $arguments
 * @return array
 */
function processArguments($arguments){
    array_shift($arguments); $o = array();
    foreach ($arguments as $a){
        if (substr($a,0,2) == '--'){ $eq = strpos($a,'=');
            if ($eq !== false){ $o[substr($a,2,$eq-2)] = substr($a,$eq+1); }
            else { $k = substr($a,2); if (!isset($o[$k])){ $o[$k] = true; } }
        }
        else if (substr($a,0,1) == '-'){
            if (substr($a,2,1) == '='){ $o[substr($a,1,1)] = substr($a,3); }
            else { foreach (str_split(substr($a,1)) as $k){ if (!isset($o[$k])){ $o[$k] = true; } } }
        }
        else { $o[] = $a; } }
    return $o;
}


/**
 * @param array $arguments
 * @return array
 */
function getDatabaseParameters(array $arguments){
    $parameters = array(
        'host' => !empty($arguments['h']) ? $arguments['h'] : DB_HOST,
        'user' => !empty($arguments['u']) ? $arguments['u'] : DB_USER,
        'password' => !empty($arguments['p']) ? $arguments['p'] : DB_PASS,
        'port' => DB_PORT,
        'dbname' => DB_NAME
    );
    return $parameters;
}


/**
 * @param $arguments
 */
function validateArguments($arguments){
    // Validate arguments and display error message accordingly
    if (is_array($arguments)) {
        if (sizeof($arguments) > ARGUMENT_SIZE) {
            displayErrorMessage("Error: Invalid number of arguments passed. Execute php user_upload --help for details.", true);
        }else{
            //Get all argument keys
            $argumentArrayKeys = array_keys($arguments);
            $result = array_diff($argumentArrayKeys, ARGUMENT_KEYS); //Validate against valid argument keys
            if(is_array($result) == false || sizeof($result) > 0){
                displayErrorMessage("Error: Invalid argument key passed. Execute php user_upload --help for details.", true);
            }

            //Validate against dry_run and file combination
            if(in_array('dry_run', $argumentArrayKeys) && in_array('file', $argumentArrayKeys) == false){
                if(empty($argumentArrayKeys['file'])){
                    displayErrorMessage("Error: Dry runs can only be initiated when a file name is provided. Please provide a file name.", true);
                }
            }

        }
    }
}


/**
 * @param DatabaseConnectionInterface $database
 * @return DatabaseConnectionInterface
 */
function getDatabaseConnection(DatabaseConnectionInterface $database){
    return $database;
}


/**
 * @param $message
 * @param bool $endScript
 */
function displayErrorMessage($message, $endScript = false){

    echo PHP_EOL.$message.PHP_EOL;
    if($endScript){
        endScript();
    }
}


/**
 * @param DatabaseConnectionInterface $db
 * @param $tableName
 * @return bool
 */
function doesTableExist(DatabaseConnectionInterface $db, $tableName){
    $tableName = filter_var($tableName, FILTER_SANITIZE_STRIPPED);
    try {
        $sql = "SELECT * FROM information_schema.tables WHERE table_schema = 'public' AND table_name  = '" . $tableName . "'";
        $result = $db->execute($sql);
    }
    catch(Exception $exception){
        return false;
    }
    return $result > 0 ? true : false;
}


/**
 * Not a good method. Hard coded due to time constraint.
 *
 * @param DatabaseConnectionInterface $db
 */
function createDatabaseTable(DatabaseConnectionInterface $db){
    //Drop Table First
    $db->execute(DROP_TABLE_SQL);
    //Create the table
    $db->execute(TABLE_CREATION_SQL);
}


/**
 * * This is a hard coded method as well. Definitely not best practice
 *
 * @param DatabaseConnectionInterface $db
 * @param $data
 * @throws Exception
 */
function addDataToDatabase(DatabaseConnectionInterface $db, $data){
    try {
        $counter = 0;
        $sql = '';
        foreach ($data as $key => $value) {
            $counter++;
            $sql .= "INSERT INTO users (name, surname, email) VALUES ('" . pg_escape_string(($value['name'])) . "', '" . pg_escape_string(($value['surname'])) . "', '"
                . pg_escape_string($value['email']) . "');";
            if ($counter >= BULK_INSERT_SIZE) {
                $db->execute($sql);
                $counter = 0;
                $sql = '';
            }
        }
        $db->execute($sql);
    }
    catch(Exception $exception){
        throw new Exception($exception->getMessage());
    }
}


/**
 * @return string
 */
function displayHelpDocumentation(){
   $output = PHP_EOL.'Usage: user_upload [options...] <url>'.PHP_EOL;
   $output .= '--file [csv file name] – this is the name of the CSV to be parsed'.PHP_EOL;
   $output .= '--create_table – this will cause the PostgreSQL users table to be built (and no further action will be taken)'.PHP_EOL;
   $output .= '--dry_run – this will be used with the --file directive in case we want to run the script but not insert into the DB. All other functions will be executed, but the database won\'t be altered'.PHP_EOL;
   $output .= '-u – PostgreSQL username'.PHP_EOL;
   $output .= '-p – PostgreSQL password'.PHP_EOL;
   $output .= '-h – PostgreSQL host'.PHP_EOL;
   $output.= PHP_EOL;
   return $output;
}


/**
 * @param $data
 * @param $column
 * @return mixed
 */
function removeDuplicateValuesFromArray($data, $column){
    $tempArray = array_unique(array_column($data, $column)); //Cleaning duplicate records based on email address
    $data = array_intersect_key($data, $tempArray);
    return $data;
}


/**
 * Method that Ends the Script
 */
function endScript(){
    echo PHP_EOL.'Thanks for running the script.'.PHP_EOL.PHP_EOL;
    exit;
}
