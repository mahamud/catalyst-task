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
 * Method that Ends the Script
 */
function endScript(){
    echo PHP_EOL.'Thanks for running the script.'.PHP_EOL;
    exit;
}
