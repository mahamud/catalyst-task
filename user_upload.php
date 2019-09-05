<?php

require_once ('config/config.php');
require_once ('app/helper.php');

echo 'Starting the Catalyst Task ... please wait ...'.PHP_EOL;
sleep(2);

echo 'Options passed are : '.PHP_EOL.PHP_EOL;

$arguments = processArguments($argv);

var_dump(print_r($arguments, true));

// Validate arguments and display error message accordingly
if (is_array($arguments)) {
    if (sizeof($arguments) > ARGUMENT_SIZE) {
        displayErrorMessage("Error: Invalid number of arguments passed. Execute php user_upload --help for details.", true);
    }else{
        //Get all argument keys
        $argumentArrayKeys = array_keys($arguments);
        $result = array_diff($argumentArrayKeys, ARGUMENTKEYS); //Validate against valid argument keys
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

//var_dump(print_r($result), true);

//Validate against valid argument keys


endScript();

